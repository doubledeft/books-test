<?php

namespace app\controllers\contract;

use app\common\base\ApiController;
class ProcessController extends ApiController
{

    //分配合同会签人（没用了）
    public function actionAddCountersign(){
        $this->rules=[
            [['contract_id','user_id'],'required'],
            [['contract_id','user_id'],'string']
        ];
        $input=$this->validate();
        $info=self::callModuleService('contract','ProcessService','addCountersign',$input['contract_id'],$input['user_id']);
        return $info;
    }

    //查询用户需要需要查看的合同 按照分页来
    public function actionQueryByUserPage(){
        $this->rules=[
            [['user_id','type','size','page'],'required'],
            [['user_id'],'string'],
            [['type','size','page'],'integer']
        ];
        $input=$this->validate();
        $list=self::callModuleService('contract','ProcessService','queryByUserPage',$input['user_id'],$input['type'],
        $input['size'],$input['page']);
        return $list;
    }
    //查询用户需要查看的合同
    //查询合同类型type，1会签合同，2审批合同，3签订合同
    public function actionQueryByUser(){
        $this->rules=[
            [['user_id','type'],'required'],
            [['user_id'],'string'],
            [['type'],'integer']
        ];
        $input=$this->validate();
        $list=self::callModuleService('contract','ProcessService','queryByUser',$input['user_id'],$input['type']);
        return $list;
    }


//    //查看用户需要会签的全部合同
//    public function actionQueryCountersignByUser(){
//        $this->rules=[
//            [['user_id'],'required'],
//            [['user_id'],'string']
//        ];
//        $input=$this->validate();
//        $info=self::callModuleService('contract','ProcessService','queryCountersignByUser',$input['user_id']);
//        return $info;
//    }


    //用户会签合同
    public function actionCountersign(){
        $this->rules=[
            [['contract_id','user_id','content'],'required'],
            [['contract_id','user_id','content'],'string']
        ];
        $input=$this->validate();
        //查看合同状态
        $state=self::callModuleService('contract','StateService','isStateContract',$input['contract_id'],1);

        $info=self::callModuleService('contract','ProcessService','countersign',$input['contract_id'],$input['user_id'],$input['content']);

        $content='用户会签合同,'."合同id:".$input['contract_id'];
        $log = self::callModuleService('user','LogService','addLog',$input['user_id'],$content);

        return $info;
    }

//    //查询需要定稿的合同
//    public function actionQueryFinalize(){
//        $this->rules=[
//            [['user_id'],'required'],
//            [['user_id'],'string']
//        ];
//        $input=$this->validate();
//        $info=self::callModuleService('contract','ProcessService','queryFinalize',$input['user_id']);
//        return $info;
//    }


    //查询合同里用户已经会签的意见
    public function actionQueryCountersignByContract(){
        $this->rules=[
            [['contract_id'],'required'],
            [['contract_id'],'string']
        ];


        $input=$this->validate();
        $lists=self::callModuleService('contract','ProcessService','queryCountersignByContract',$input['contract_id']);
        $info=self::callModuleService('contract','ContractService','queryByContract_id',$input['contract_id']);

        return [
            'contract'=>$info,
            'countersign'=>$lists
        ];
    }


//    //定稿合同
//    //权限
//    //查询合同信息（未实现）
//    //不需要实现，这是contract里的
//    public function actionFinalize(){
//        $this->rules=[
//            [['contract_id','user_id','content'],'required'],
//            [['contract_id','user_id','content'],'string']
//        ];
//        $input=$this->validate();
//
//        //修改合同内容
//        $info1=self::callModuleService('contract','ContractService',' updateContractContent',$input['contract_id'],$input['content']);
//        return $info1;
//    }

    //查询用户需要审批合同
    public function actionQueryApproveByUser(){
        $this->rules=[
            [['user_id'],'required'],
            [['user_id'],'string']
        ];
        $input=$this->validate();
        $info=self::callModuleService('contract','ProcessService','queryApproveByUser',$input['user_id']);
        return $info;
    }

    //审批合同
    public function actionApprove(){
        //同意是1，否定是2，默认是0
        $this->rules=[
            [['contract_id','user_id','state','content'],'required'],
            [['contract_id','user_id','state','content'],'string']
        ];
        $input=$this->validate();

        //查看合同状态
        $state=self::callModuleService('contract','StateService','isStateContract',$input['contract_id'],3);

        $info=self::callModuleService('contract','ProcessService','approve',$input['contract_id'],$input['user_id'],
        $input['state'],$input['content']);

        $content='用户审批合同,'."合同id:".$input['contract_id'];
        $log = self::callModuleService('user','LogService','addLog',$input['user_id'],$content);

        return $info;
    }

    //签订合同
    public function actionSign(){
        $this->rules=[
            [['contract_id','user_id','content'],'required'],
            [['contract_id','user_id','content'],'string']
        ];
        $input=$this->validate();
        //查看合同状态
        $state=self::callModuleService('contract','StateService','isStateContract',$input['contract_id'],4);

        $info=self::callModuleService('contract','ProcessService','sign',$input['contract_id'],$input['user_id'],
            $input['content']);

        $content='用户签订合同,'."合同id:".$input['contract_id'];
        $log = self::callModuleService('user','LogService','addLog',$input['user_id'],$content);


        return $info;
    }


}