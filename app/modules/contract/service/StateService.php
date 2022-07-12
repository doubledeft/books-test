<?php

namespace app\modules\contract\service;

use app\common\base\ApiService;
use app\modules\contract\models\State;

class StateService extends ApiService
{
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->model=new State();
    }

    //管理人员和合同操作查询合同流程
    //通过type查询合同目前状态，1:起草，2会签完成，3定稿完成，4审核完成，签订完成
    public function queryByType($type){
        $contract_state_not_delete=1;
        $info=$this->lists(
            ['condition'=>[
                'type'=>$type,
                'status'=>$contract_state_not_delete
            ]]
        );
        return $info;
    }


    //查询所有处于某一状态的合同信息 分页
    //合同状态,1:起草，2会签完成，3定稿完成，4审核完成，5签订完成
    public function queryContractByTypePage($type,$size,$page): array
    {
        $lists=$this->lists([
            'size'=>$size,
            'page'=>$page,
            'condition'=>[
                'type'=>$type,
                'status'=>1]
            ]);
        $contracts=array();
        foreach ($lists as $list) {
            $info=self::callModuleService('contract','ContractService','queryByContract_id',$list['contract_id']);
            array_push($contracts,$info);
        }
        return $contracts;
    }
    //查询所有处于某一状态的合同信息
    //合同状态,1:起草，2会签完成，3定稿完成，4审核完成，5签订完成
    public function queryContractByType($type): array
    {
        $lists=$this->lists(
            ['condition'=>[
                'type'=>$type,
                'status'=>1
            ]]
        );
        $contracts=array();
        foreach ($lists as $list) {
            $info=self::callModuleService('contract','ContractService','queryByContract_id',$list['contract_id']);
            array_push($contracts,$info);
        }
        return $contracts;
    }


    //查询所有
    public function queryAll(){
        $lists=$this->lists(
            ['condition'=>[
                'status'=>1
            ]]
        );
        return $lists;
    }



    //添加合同，当合同被创建的时候，就调用这个函数在contract里面添加一个初始合同
    public function addContract($contract_id){
        $info=$this->info([
            'condition'=>[
                'contract_id'=>$contract_id
            ]
        ]);
        if (!empty($info)){
            if ($info['status']==0){
                return self::error('ERROR_INVALID_USERID', '该合同的状态已经被删除');
            }
            return self::error('ERROR_INVALID_USERID', '该合同的状态已存在');
        }

        $newContract=$this->add([
            'type'=>1,
            'time'=>$this->timeNow,
            'status'=>1,
            'contract_id'=> (string)$contract_id
        ]);
        return $newContract;
    }
    //删除状态
    public function deleteContract($contract_id){
        $info=$this->info([
            'condition'=>[
                'contract_id'=>$contract_id,
                'status'=>1
            ]
        ]);
        if (empty($info)){
            return self::error('ERROR_INVALID_USERID', '该合同的状态已被删除');
        }

        $newContract=$this->update(
            ['status'=>0],
            ['contract_id'=>$contract_id]
        );
        return $newContract;
    }
    //修改合同状态
    //合同状态,1:起草，2会签完成，3定稿完成，4审核完成，5签订完成
    public function updateContract($contract_id,$type){
        $info=$this->info([
            'condition'=>[
                'contract_id'=>$contract_id,
                'status'=>1
            ]
        ]);
        if (empty($info)){
            return self::error('ERROR_INVALID_USERID', '该合同的状态已被删除');
        }

        $newContract=$this->update(
            ['type'=>$type,
                'time'=>$this->timeNow],
            ['contract_id'=>$contract_id]
        );
        return $newContract;
    }

    //判断合同状态，不一致就报错
    public function isStateContract($contract_id,$type){
        $info=$this->info([
            'condition'=>[
                'contract_id'=>$contract_id,
                'status'=>1
            ]
        ]);
        if (empty($info)){
            return self::error('ERROR_INVALID_USERID', '该合同的状态已被删除');
        }
        if ($info['type']!=$type){
            if ($type==1){
                return self::error('ERROR_INVALID_USERID',"该合同未处于会签状态" );
            }elseif ($type==2){
                return self::error('ERROR_INVALID_USERID',"该合同未处于定稿状态" );
            }elseif ($type==3){
                return self::error('ERROR_INVALID_USERID',"该合同未处于审批状态" );
            }elseif ($type==4){
                return self::error('ERROR_INVALID_USERID',"该合同未处于签订状态" );
            }
        }

    }
}