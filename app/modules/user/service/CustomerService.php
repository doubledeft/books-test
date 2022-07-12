<?php

namespace app\modules\user\service;

use app\common\base\ApiService;
use app\modules\user\models\Customer;

class CustomerService extends ApiService
{
    public function __construct()
    {
        parent::init();
        $this->model=new Customer();
    }

    /**
     * add；
     *
     * @params string $name 姓名
     * @params string $address 电话
     * @params string $fax 传真
     * @params string $email 邮箱
     * @params string $bank 银行
     * @params string $account 账户
     * @params string $comment 备注
     */
    public function addCustomer($name,$tel,$address,$fax,$email,$bank,$account,$comment){
        $info=$this->info([
            'condition'=>[
                'name'=>$name,
                'tel'=>$tel,
            ]
        ]);
        if(!empty($info)) {
            return self::error('ERROR_INVALID_USERID', '客户已存在');
        }
        $newCustomer=$this->add([
            'name'=>$name,
            'address'=>$address,
            'tel'=>$tel,
            'fax'=>$fax,
            'email'=>$email,
            'bank'=>$bank,
            'account'=>$account,
            'comment'=>$comment,
            'status'=>1
        ]);
        return $newCustomer;
    }

    public function queryAll(){
        $lists=$this->lists([
            'condition'=>[
                'status'=>1
            ]
        ]);
        return $lists;
    }
    //以分页形式返回列表
    public function queryAllPage($size,$page){
        $lists=$this->lists([
            'page'=>$page,
            'size'=>$size,
            'condition'=> ['status'=>1]]
        );
        return $lists;
    }

    /**
     * query；
     *
     * @params string $name 姓名
     * @params string $address 电话
     * @params string $fax 传真
     * @params string $email 邮箱
     * @params string $bank 银行
     * @params string $account 账户
     * @params string $comment 备注
     */
    public function queryCustomer($id){
        //查询客户表
        $info=$this->info([
            'condition'=>[
                'id'=>$id
            ]
        ]);
        if (empty($info)){
            return self::error('ERROR_INVALID_USERID', '不存在该用户');
        }
        return $info;
    }
    /**
     * delete；
     *
     * @params integer $id 编号
     */
    public function deleteCustomer($id){
        $info=$this->info([
            'condition'=>[
                'id'=>$id,
                'status'=>1
            ]
        ]);
        if (empty($info)){
            return self::error('ERROR_INVALID_USERID', '不存在该用户');
        }
        $deleteCustomer=$this->update(
            ['status'=>0],
            ['id'=>$id]
        );
        return $deleteCustomer;
    }

    //想着稍微有bug，目标值要是存在就完了
    public function modifyCustomer($id,$name,$tel,$address,$fax,$email,$bank,$account,$comment){
        $info=$this->info([
            'condition'=>[
                'id'=>$id,
                'status'=>1
            ]
        ]);
        if (empty($info)){
            return self::error('ERROR_INVALID_USERID', '不存在该用户');
        }
        $info=$this->info([
            'condition'=>[
               'name'=>$name,
                'tel'=>$tel
            ]
        ]);
        if (!empty($info)){
            return self::error('ERROR_INVALID_USERID', '用户信息已存在，无法修改');
        }

        $newCustomer=$this->update([
            'name'=>$name,
            'address'=>$address,
            'tel'=>$tel,
            'fax'=>$fax,
            'email'=>$email,
            'bank'=>$bank,
            'account'=>$account,
            'comment'=>$comment,
        ],
            ['id'=>$id]
        );
        return $newCustomer;
    }

}
