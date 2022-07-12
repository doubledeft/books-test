<?php

namespace app\modules\contract\service;
use app\common\base\ApiService;
use  app\modules\contract\models\Contract;
class ContractService extends  ApiService
{
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->model=new Contract();
    }

    //创建合同
    public function createContract($user_id,$name, $customer, $beginTime, $endTime,$content,$file){

        $info=$this->info([
                'condition'=>[
                    'name'=>$name,
                ]
            ]);
        if(!empty($info)) {
            if ($info['status']==0){
                return self::error('ERROR_INVALID_NUM', '合同已被删除，请联系管理员恢复');
            }
            return self::error('ERROR_INVALID_NUM', '合同已存在');
        }
        $contract_not_delete=1;
        $newContract=$this->add([
            'name'=>$name,
            'user_id'=>$user_id,
            'customer'=>$customer,
            'content'=>$content,
            'file'=>$file,
            'status'=>$contract_not_delete,
            'beginTime'=>$beginTime,
            'endTime'=>$endTime,
            'createTime'=>date("Y-m-d H:i", $this->timeNow/1000),
        ]);
        $info=$this->info([
            'condition'=>[
                'name'=>$name,
            ]
        ]);

        return $info;
    }

    //删除合同
    public function deleteContract($id){
        $info=$this->info([
            'condition'=>[
                'id'=>$id
            ]
        ]);
        if (empty($info)){
            return self::error('ERROR_INVALID_NUM','合同不存在');
        }
        $contract_delete=0;
        //避免重复删除合同
        if ($info['status']==$contract_delete){
            return self::error('ERROR_INVALID_NUM','合同已被删除');
        }
        //删除合同
        $delContract=$this->update(
                ['status'=>$contract_delete],
            ['id'=>$id]
        );
        return $info;
    }

    //完成定稿
    public function finalize($id,$content){
        $info=$this->info([
            'condition'=>[
                'id'=>$id
            ]
        ]);
        if (empty($info)){
            return self::error('ERROR_INVALID_NUM','合同不存在');
        }
        $this->update(
          ['content'=>$content],
            ['id'=>$id]
        );
        return $info;
    }

    //查询所有的合同 分页形式
    public function queryAllPage($size,$page){
        $lists=$this->lists([
            'size'=>$size,
            'page'=>$page,
            'condition'=>['status'=>1]
        ]);
        return $lists;
    }
    //查询所有合同
    public function queryAll(){
        $info=$this->lists(
            ['status'=>1]
        );
        return $info;
    }
    //模糊查找合同
    public function query($name){
        //使用正则表达式，查询包含这个name的内容
        $info=$this->lists(
            ['condition'=>[
                'name'=>'/'+$name+"/"
            ]]
        );
        return $info;
    }

    //根据合同id查找合同
    public function queryByContract_id($contract_id){
       $contract=$this->info(
           ['condition'=>[
               'id'=>$contract_id,
               'status'=>1
           ]
           ]);
       if (empty($contract)){
           return self::error('ERROR_INVALID_NUM', '输入合同id错误，该合同不存在');
       }
       return  $contract;
    }
}