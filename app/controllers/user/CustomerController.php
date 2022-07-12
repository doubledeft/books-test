<?php

namespace app\controllers\user;

use app\common\base\ApiController;

class CustomerController extends ApiController
{
    //添加客户
    public function actionAddCustomer()
    {
        $this->rules = [
            [['name', 'tel', 'address', 'fax', 'email', 'bank', 'account', 'comment'], 'required'],
            [[ 'name', 'tel', 'address', 'fax', 'email', 'bank', 'account', 'comment'], 'string'],
            [['tel'],'integer']
        ];
        $input = $this->validate();
        $info = self::callModuleService('user', 'CustomerService', 'addCustomer',
            $input['name'], $input['tel'], $input['address'], $input['fax'], $input['email'],
            $input['bank'], $input['account'], $input['comment']);

        $content='管理员添加客户信息,'."客户名:".$input['name'];
        $log = self::callModuleService('user','LogService','addLog',"0",$content);

        return $info;
    }

    //查询所有用户信息
    public function actionQueryAll(){
        $query_info = self::callModuleService('user', 'CustomerService', 'queryAll');
        return $query_info;
    }
    //查询所有用户信息，以分页形式
    public function actionQueryAllPage(){
        $this->rules = [
            [['size','page'], 'required'],
            [['size','page'], 'integer'],
        ];
        $input = $this->validate();
        $lists = self::callModuleService('user', 'CustomerService', 'queryAllPage',$input['size'],$input['page']);
        return $lists;
    }

    //查看客户信息
    //通过客户id查询信息
    public function actionQueryCustomer()
    {
        $this->rules = [
            [['id'], 'required'],
            [['id'], 'integer'],
        ];
        $input = $this->validate();
        $query_info = self::callModuleService('user', 'CustomerService', 'queryCustomer',$input['id']);
        return $query_info;
    }

    //删除客户
    public function actionDeleteCustomer()
    {
        $this->rules = [
            [['id'], 'required'],
            [['id'], 'integer'],
        ];
        $input = $this->validate();
        $delete_info = self::callModuleService('user', 'CustomerService', 'deleteCustomer',
            $input['id']);
        return $delete_info;
    }

    //修改客户信息
    public function actionModifyCustomer()
    {
        $this->rules = [
            [['id', 'name', 'tel', 'address', 'fax', 'email', 'bank', 'account', 'comment'], 'required'],
            [['name', 'address', 'fax', 'email', 'bank', 'account', 'comment'], 'string'],
            [['id','tel'],'integer']
        ];
        $input = $this->validate();
        $modify_info = self::callModuleService('user', 'CustomerService', 'modifyCustomer',
            $input['id'], $input['name'], $input['tel'], $input['address'], $input['fax'], $input['email'], $input['bank'], $input['account'], $input['comment']);

       $content='管理员修改客户信息,'."客户名:".$input['name'];
        $log = self::callModuleService('user','LogService','addLog',"0",$content);

        return $modify_info;
    }
}
