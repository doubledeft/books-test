<?php

namespace app\modules\contract\service;

use app\common\base\ApiService;
use  app\modules\contract\models\Process;
class ProcessService extends ApiService
{
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->model=new Process();
    }


//    //增加会签合同
//    public function addCountersign($contract_id,$user_id){
//        $info=$this->info([
//            'condition'=>[
//                'contract_id'=>$contract_id,
//                'user_id'=>$user_id
//            ]
//        ]);
//        if(!empty($info)) {
//            return self::error('ERROR_INVALID_NUM', '该用户已经分配该合同');
//        }
//        $type_countersign=1;
//        $state_not_finish=0;
//        $state_not_delete=1;
//        $newProcess=$this->add([
//            'contract_id'=>$contract_id,
//            'type'=>$type_countersign,
//            'state'=>$state_not_finish,
//            'user_id'=>$user_id,
//            'status'=>$state_not_delete,
//            'content'=>" ",
//            'time'=>$this->timeNow
//        ]);
//        return $newProcess;
//    }


    //查询用户需要查看的合同 按分页来
    public function queryByUserPage($user_id,$type,$size,$page){
        $lists=$this->lists([
            'size'=>$size,
            'page'=>$page,
            'condition'=>[
                'user_id'=>$user_id,
                'type'=>$type
            ]
        ]);
        if(empty($lists)) {
            return self::error('ERROR_INVALID_NUM', '该用户没有可查询合同');
        }
        return $lists;
    }
    //查询用户需要查看的合同
    //查询合同类型type，1会签合同，2审批合同，3签订合同
    public function queryByUser($user_id,$type){
        $lists=$this->lists([
            'condition'=>[
                'user_id'=>$user_id,
                'type'=>$type
            ]
        ]);
        if(empty($lists)) {
            return self::error('ERROR_INVALID_NUM', '该用户没有可查询合同');
        }
        return $lists;
    }
    //查看合同的不同状态
    public function queryByContract($contract_id,$type){
        $lists=$this->lists([
            'condition'=>[
                'contract_id'=>$contract_id,
                'type'=>$type
            ]
        ]);
        if(empty($lists)) {
            return self::error('ERROR_INVALID_NUM', '该用户没有可查询合同');
        }
        return $lists;
    }




//    //查询用户的所有需要会签合同
//    public function queryCountersignByUser($user_id){
//        $lists=$this->lists([
//            'condition'=>[
//                'user_id'=>$user_id
//            ]
//        ]);
//        if(empty($lists)) {
//            return self::error('ERROR_INVALID_NUM', '该用户没有可查阅合同');
//        }
//        return $lists;
//    }

    //用户会签合同
    //会签完合同后判断这个合同会签的情况，是否会签完，会签完的话，就修改state的状态
    public function countersign($contract_id,$user_id,$content){
        $contract_process_type_countersign=1;
        $info=$this->info([
            'condition'=>[
                'contract_id'=>$contract_id,
                'user_id'=>$user_id,
                'type'=>$contract_process_type_countersign
            ]
        ]);
        if(empty($info)) {
            return self::error('ERROR_INVALID_NUM', '该用户没有被分配该合同进行会签');
        }
        $contract_process_state_complete=1;
        if ($info['state']==$contract_process_state_complete){
            return self::error('ERROR_INVALID_NUM', '该用户已经对该合同进行会签');
        }
        $this->update(
            [
                'state'=>$contract_process_state_complete,
                'content'=>$content
                ],
            [
                'contract_id'=>$contract_id,
                'user_id'=>$user_id,
                'type'=>$contract_process_type_countersign
                ]
        );

        //查询这个合同，会签情况
       $lists=$this->queryByContract($contract_id,1);
       //循环遍历查询是否会签完
        $isAllContersign=true;
        foreach ($lists as $list) {
            if ($list['state']==0){
                $isAllContersign=false;
            }
        }
        if ($isAllContersign==true){
            //修改这个合同的状态
            //2代表会签完成
                $info=self::callModuleService('contract','StateService','updateContract',$contract_id,2);
        }

    }


    //查询合同的所有已完成的会签信息
    public function queryCountersignByContract($contract_id){
        $contract_countersign_complete=1;
        $lists=$this->lists([
            'condition'=>[
                'contract_id'=>$contract_id,
                'state'=>$contract_countersign_complete
            ]
        ]);
        if(empty($lists)) {
            return self::error('ERROR_INVALID_NUM', '该合同没有可查询的会签合同');
        }
        return $lists;
    }


//    //查询用户的所有需要定稿的合同
//    public function queryFinalize($user_id){
//        $lists=$this->lists([
//            'condition'=>[
//                'user_id'=>$user_id
//            ]
//        ]);
//        if(empty($lists)) {
//            return self::error('ERROR_INVALID_NUM', '该用户没有可定稿合同');
//        }
//
//        return $lists;
//    }


    public function queryApproveByUser($user_id){
        $contract_procees_type_approve=2;
        $lists=$this->lists([
            'condition'=>[
                'user_id'=>$user_id,
                'type'=>$contract_procees_type_approve
            ]
        ]);
        if(empty($lists)) {
            return self::error('ERROR_INVALID_NUM', '该用户没有可查询的审批合同');
        }
        return $lists;
    }

    //审批合同
    public function approve($contract_id,$user_id,$state,$content){
        $contract_process_state_not_complete=0;
        $contract_process_type_approve=2;
        $info=$this->info([
            'condition'=>[
                'contract_id'=>$contract_id,
                'user_id'=>$user_id,
                'type'=>$contract_process_type_approve
            ]
        ]);
        if(empty($info)) {
            return self::error('ERROR_INVALID_NUM', '该用户没有被分配该合同进行审批');
        }
        if ($state!=2 and $state!=1){
            return self::error('ERROR_INVALID_NUM', '输入错误的合同审批选项');
        }
        //暂时不要，万一别人想改呢
//        if ($info['state']!=$contract_process_state_not_complete){
//            return self::error('ERROR_INVALID_NUM', '该用户已经对该合同进行审批');
//        }
        $this->update(
            [
                'state'=>$state,
                'content'=>$content
            ],
            [
                'contract_id'=>$contract_id,
                'user_id'=>$user_id,
                'type'=>$contract_process_type_approve
            ]
        );

        //然后当所有的审批人员审批通过合同后，更新合同状态
        $lists=$this->queryByContract($contract_id,2);
        //循环遍历查询是否审批完
        $isAllApprove=true;
        foreach ($lists as $list) {
            if ($list['state']==0 or $list['state']==2){
                $isAllApprove=false;
            }
        }
        if ($isAllApprove==true){
            //修改这个合同的状态
            //4代表完成审批完成
            $info=self::callModuleService('contract','StateService','updateContract',$contract_id,4);
        }
    }

    //签订合同
    public function sign($contract_id,$user_id,$content){
        $contract_process_type_sign=3;
        $info=$this->info([
            'condition'=>[
                'contract_id'=>$contract_id,
                'user_id'=>$user_id,
                'type'=>$contract_process_type_sign
            ]
        ]);
        if(empty($info)) {
            return self::error('ERROR_INVALID_NUM', '该用户没有被分配该合同进行签订');
        }
        $contract_process_state_complete=1;
        if ($info['state']==$contract_process_state_complete){
            return self::error('ERROR_INVALID_NUM', '该用户已经对该合同进行签订');
        }
        $this->update(
            [
                'state'=>$contract_process_state_complete,
                'content'=>$content
            ],
            [
                'contract_id'=>$contract_id,
                'user_id'=>$user_id,
                'type'=>$contract_process_type_sign
            ]
        );
        //然后当所有的签订人员签订合同后，更新合同状态
        $lists=$this->queryByContract($contract_id,3);
        //循环遍历查询是否审批完
        $isAllApprove=true;
        foreach ($lists as $list) {
            if ($list['state']==0){
                $isAllApprove=false;
            }
        }
        if ($isAllApprove==true){
            //修改这个合同的状态
            //5代表完成审批完成
            $info=self::callModuleService('contract','StateService','updateContract',$contract_id,5);
        }

    }


    //添加一个执行合同的人
    public function addProcess($contract_id,$userid,$type){
        $info=$this->info([
            'condition'=>[
                'contract_id'=>$contract_id,
                'user_id'=>$userid,
                'type'=>$type
            ]
        ]);
        if(!empty($info)) {
            if ($type==1){
                return self::error('ERROR_INVALID_NUM', '该用户已经被赋予要会签该合同');
            }elseif ($type==2){
                return self::error('ERROR_INVALID_NUM', '该用户已经被赋予要审批该合同');
            }elseif ($type==3){
                return self::error('ERROR_INVALID_NUM', '该用户已经被赋予要签订该合同');
            }
       }
        //查询合同名
        $contract=self::callModuleService('contract','ContractService','queryByContract_id',$contract_id);
        $contract_name=$contract['name'];

        $process=$this->add(
           [ 'contract_id'=>$contract_id,
               'contract_name'=>$contract_name,
               'type'=>$type,
               'state'=>0,
               'user_id'=>$userid,
               'status'=>1,
               'content'=>"",
               'time'=>$this->timeNow
        ]);
       return $process;
    }
}