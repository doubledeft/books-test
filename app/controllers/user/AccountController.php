<?php

namespace app\controllers\user;

class AccountController extends \app\common\base\ApiController
{

    //登录
    public function actionLogin(){
        $this->rules=[
            [['userid','password'],'required'],
        [['userid','password','type'],'string'],
        ];
        $input=$this->validate();
        $info=self::callModuleService('user','AccountService','login',$input['userid'],$input['password']);
        return $info;
    }

    //注册
    public function actionRegister(){
        $this->rules=[
            [['userid','password'],'required'],
            [['userid','password'],'string'],
        ];
        $input=$this->validate();
        $info=self::callModuleService('user','AccountService','register',$input['userid'],$input['password']);
        return $info;
    }

    //删除账号(暂时不写)

    
    //管理员添加用户可以设置用户权限，更新right表

    //管理员查询所有用户

    //管理员设置某一用户，为一个合同的管理员或者操作员（存哪不知道，暂定）



}