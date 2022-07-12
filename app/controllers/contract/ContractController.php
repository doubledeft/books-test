<?php

namespace app\controllers\contract;

use app\common\base\ApiController;
use FontLib\Table\Type\name;

class ContractController extends  ApiController
{
    //起草合同
    public function actionCreateContract(){
        $this->rules=[
         [['user_id','name','customer','beginTime','endTime','content','file'],'required'],
            [['user_id','name','customer','content','file'],'string'],
            [['beginTime','endTime'],'string']
        ];
        $input=$this->validate();

        $info=self::callModuleService('contract','ContractService','createContract',$input['user_id'],
            $input['name'], $input['customer'],$input['beginTime'],$input['endTime'],$input['content'],$input['file']);
        $contract=self::callModuleService('contract','ContractService','info',['name'=>$input['name']]);
        //添加完合同之后需要在state里添加合同
        $state=self::callModuleService('contract','StateService','addContract',$contract['id']);

        //添加日志
        $content='用户起草合同,'."合同名:".$info['name'];
        $add_log = self::callModuleService('user','LogService','addLog',$input['user_id'],$content);

        //发送邮件
        $costomer=self::callModuleService('user', 'CustomerService', 'info',[
            'condition'=>[
                'name'=>$input['customer'],
                'status'=>1
            ]
        ]);
        $email=self::callModuleService('email', 'EmailService', 'sendCustomerInfo',$costomer);

        return $info;
    }

    //删除合同
    public function actionDeleteContract(){
        $this->rules=[
            [['id'],'required'],
            [['id'],'integer']
        ];
        $input=$this->validate();
        $info=self::callModuleService('contract','ContractService','deleteContract',$input['id']);
        $state=self::callModuleService('contract','StateService','deleteContract',$input['id']);
        //添加删除合同日志
        $content='管理员删除合同,'."合同名:".$info['name'];
        $log = self::callModuleService('user','LogService','addLog',"0",$content);

        //这些都没有返回值
    }

    //修改合同/定稿合同
    public function actionFinalize(){
        $this->rules=[
            [['id','content'],'required'],
            [['id','content'],'string']
        ];
        $input=$this->validate();
        //查看合同状态
        $state=self::callModuleService('contract','StateService','isStateContract',$input['id'],2);

        $info=self::callModuleService('contract','ContractService','finalize',$input['id'],$input['content']);
        //3是定稿合同
        $state=self::callModuleService('contract','StateService','updateContract',$input['id'],3);

        $content='管理员修改/定稿合同,'."合同名:".$info['name'];
        $log = self::callModuleService('user','LogService','addLog',"0",$content);

    }

    //查询所有的合同 分页形式
    public function actionQueryAllPage(){
        $this->rules=[
            [['size','page'],'required'],
            [['size','page'],'integer']
        ];
        $input=$this->validate();
        $info=self::callModuleService('contract','ContractService','queryAllPage',$input['size'],$input['page']);
        return $info;
    }
    //查询所有的合同
    public function actionQueryAll(){
        $info=self::callModuleService('contract','ContractService','queryAll');
        return $info;
    }
    //模糊查找合同信息
    public function actionQuery($contract_name){
        $this->rules=[
            [['name'],'required'],
            [['name'],'string']
        ];
        $input=$this->validate();
        $info=self::callModuleService('contract','ContractService','query',$input['name']);
        return $info;
    }
    //获取合同信息
    public function actionGetContract(){
        $this->rules=[
            [['contract_id'],'required'],
            [['contract_id'],'integer']
        ];
        $input=$this->validate();
        $info=self::callModuleService('contract','ContractService','queryByContract_id',$input['contract_id']);
        return $info;
    }


    //对应3.7.3功能
    //查询需要起草的合同（已经在state中实现）

    //分配合同
    //需要查询可以分配的人员信息（这个写了）
    //然后再进行分配，发配合同相关人员，选择环节
//    public function actionDistribute(){
//        $this->rules=[
//            [['contract_id','userid','type'],'required'],
//            [['contract_id','userid','type'],'string']
//        ];
//        $input=$this->validate();
//        $info=self::callModuleService('contract','ProcessService','addProcess',
//            $input['contract_id'],$input['userid'],$input['type']);
//        return $info;
//    }



    //分配合同，选择人员分配合同
    public function actionDistribute(){
        $this->rules=[
            [['contract_id','user_list'],'required'],
            ['contract_id','string'],
            ['user_list','app\helpers\ArrayValidator']
        ];
        $input=$this->validate();
        foreach ($input['user_list'] as $type=>$users){
            foreach ($users as $user) {

                $info=self::callModuleService('contract','ProcessService','addProcess',
                    $input['contract_id'],$user,$type);

                $userInfo=self::callModuleService('user','UserService','info',[
                    'condition'=>[
                        'userid'=>$user,
                        'status'=>1
                    ]
                ]);
                $email=self::callModuleService('email', 'EmailService', 'sendContractGet',$userInfo);
            }
        }
        $content='管理员分配合同,'."合同id:".$input['contract_id'];
        $log = self::callModuleService('user','LogService','addLog',"0",$content);



    }



}