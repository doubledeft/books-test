<?php

namespace app\controllers\user;

use app\common\base\ApiController;

class UserController extends ApiController
{
    public function actionLogin(){
        $this->rules=[
            [['userid','password'],'required'],
            [['userid','password','type'],'string'],
        ];
        $input=$this->validate();
        $info=self::callModuleService('user','UserService','login',$input['userid'],$input['password']);

        return $info;
    }
    //注册
    public function actionRegister(){
        $this->rules=[
            [['userid','password','age'],'required'],
            [['userid','password'],'string'],
            [['age'],'integer']
        ];
        $input=$this->validate();
        $info=self::callModuleService('user','UserService','register',$input['userid'],$input['password'],$input['age']);
        return $info;
    }


    //查询用户信息
    public function actionGetUserInfo(){
        $this->rules=[
            [['userid'],'required'],
            [['userid'],'string']
        ];
        $input=$this->validate();
        $info=self::callModuleService('user','UserService','getUserInfo',$input['userid']);
        return $info;
    }

    //删除账号
    public function actionDelete(){
        $this->rules=[
            [['user_name'],'required'],
            [['user_name'],'string'],
        ];
        $input=$this->validate();
        $info=self::callModuleService('user','UserService','delete',$input['user_name']);
        return $info;
    }
    //修改用户账号名
    public function actionUpdateUserid(){
        $this->rules=[
            [['userid','newUserid'],'required'],
            [['userid','newUserid'],'string'],
        ];
        $input=$this->validate();
        $info=self::callModuleService('user','UserService','updateUserid',$input['userid'],$input['newUserid']);
        return $info;
    }
    //修改密码
    public function actionUpdatePassword(){
        $this->rules=[
            [['user_name','password'],'required'],
            [['user_name','password'],'string'],
        ];
        $input=$this->validate();
        $info=self::callModuleService('user','UserService','updatePassword',$input['user_name'],$input['password']);
        return $info;
    }



    //管理员添加用户可以设置用户权限，更新right表
    public function actionUpdateRole(){
        $this->rules=[
            [['userid','role_name'],'required'],
            [['userid','role_name'],'string'],
        ];
        $input=$this->validate();
        $info=self::callModuleService('user','UserService','updateRole',$input['userid'],$input['role_name']);

        $content='管理员设置用户的角色,'."角色名:".$input['role_name'];
        $log = self::callModuleService('user','LogService','addLog',$input['userid'],$content);

        return $info;
    }

    //管理员查询所有用户的基本信息
    public function actionQueryAll(){
        $lists=self::callModuleService('user','UserService','queryAll');
        return $lists;
    }

    //查询所有角色权限 分页
    public function actionQueryRightPage(){
        $this->rules=[
            [['size','page'],'required'],
            [['size','page'],'integer'],
        ];
        $input=$this->validate();
        $lists=self::callModuleService('user','UserService','queryAllPage',$input['size'],$input['page']);
        $info=array();
        foreach ($lists as $list) {
            $right=self::callModuleService('user','RoleService','getNameRight',$list['role_name']);
            $user=['user'=>$list,'right'=>$right];
            array_push($info,$user);
        }
        return $info;
    }

    //查询所有的角色权限
    public function actionQueryRight(){
        $lists=$this->actionQueryAll();
        $info=array();
        foreach ($lists as $list) {
            $right=self::callModuleService('user','RoleService','getNameRight',$list['role_name']);
            $user=['user'=>$list,'right'=>$right];
            array_push($info,$user);
        }
        return $info;
    }

//    //查询跟操作合同有关的用户信息
//    public function actionQueryContractRight(){
//        $lists=$this->actionQueryAll();
//        $info=array();
//        foreach ($lists as $list) {
//            $right=self::callModuleService('user','RoleService','getNameRight',$list['role_name']);
//            if ($right[''])
//            $user=['user'=>$list,'right'=>$right];
//            array_push($info,$user);
//        }
//        return $info;
//    }

    //添加邮箱或者修改邮箱
    public function  actionAddEmail(){
        $this->rules=[
            [['userid','email'],'required'],
            [['userid','email'],'string'],
        ];
        $input=$this->validate();
        $info=self::callModuleService('user','UserService','addEmail',$input['userid'],$input['email']);

        $content='用户设置邮件,'."邮箱:".$input['email'];
        $log = self::callModuleService('user','LogService','addLog',$input['user_id'],$content);

        return $info;
    }
    //修改用户信息
    public function actionUpdateUserInfo(){
        $this->rules=[
            [['user_name','location','age'],'required'],
            [['user_name','location'],'string'],
            [['age'],'integer']
        ];
        $input=$this->validate();
        $info=self::callModuleService('user','UserService','updateUserInfo',$input['user_name'],$input['location'],$input['age']);
        return $info;
    }

    //关键词查询用户
    public function actionSearchUsers(){
        $this->rules = [
            [['keyword'], 'required'],
            [['keyword'],'string']
        ];
        $inputs = $this->validate();

        $info = self::callModuleService('user', 'UserService', 'searchUsers',$inputs['keyword']);
        return [
            'info'=>$info
        ];
    }
}