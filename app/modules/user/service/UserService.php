<?php

namespace app\modules\user\service;



use app\common\base\ApiService;
use app\modules\user\models\User;
class UserService extends ApiService
{
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->model=new User();
    }

    /**
     * 登录；
     *
     * @params int $user_id 用户名
     * @params string $password 密码
     */
    public function login($username, $password)
    {
        //查询数据库表
        $info=$this->info([
            'condition'=>[
                'username'=>$username,
            ]
        ]);
        //验证账号密码
        if(empty($info)) {
            return self::error('ERROR_INVALID_USERID', '用户不存在');
        }
        if($password!= $info['password']) {
            return self::error('ERROR_INVALID_PASSWORD', '密码不正确');
        }
//        $token = Yii::$app->jwt->createToken([
//            'id'=>$userid,
//            'type'=>$info['type']
//        ]);

     //获得角色信息和权限
//        $lists= self::callModuleService('user','RoleService','getNameRight',$info['role_name']);
        return [
            'info'=>$info,
//            'right'=>$lists
        ];

    }

    /**
     * 注册;
     * @params int $user_id 用户名
     * @params string $password 密码
     */
    public function register($username, $password,$age){
        $info=$this->info([
            'condition'=>[
                'username'=>$username
            ]
        ]);
        if(!empty($info)) {
//            if ($info['status']==0){
//                return self::error('ERROR_INVALID_USERID', '该用户已被删除.请联系管理员恢复');
//            }
            return self::error('ERROR_INVALID_USERID', '用户已存在');
        }
        $newAccount=$this->add([
            'username'=>$username,
            'password'=>$password,
            'age'=>$age
        ]);
        return [
            'status'=>true
        ];
    }

    //获取用户信息
    public function getUserInfo($username){
        $userInfo=$this->info([
            'fields'=>[
                'username','password','age'
            ],
            'condition'=>[
                'username'=>$username
            ]
        ]);
        return $userInfo;
    }


    //删除用户
    public function delete($userid){
        $info=$this->info([
            'condition'=>[
                'userid'=>$userid,
            ]
        ]);
        if(empty($info)) {
            return self::error('ERROR_INVALID_USERID', '不存在该用户');
        }
        if ($info['status']==0){
            return self::error('ERROR_INVALID_USERID', '该用户已被删除');
        }
        $info=$this->update(
            ['status'=>0],
            ['userid'=>$userid]
        );

        return $info;
    }
    //修改账号
    public function updateUserid($userid,$newUserid){
        $info=$this->info([
            'condition'=>[
                'userid'=>$userid,
                'status'=>1
            ]
        ]);
        if (empty($info)){
            return self::error('ERROR_INVALID_USERID', '用户不存在');
        }
        $info=$this->info([
            'condition'=>[
                'userid'=>$newUserid
            ]
        ]);
        if(!empty($info)){
            return self::error('ERROR_INVALID_USERID', '新用户名已经被注册');
        }
        $this->update(
            ['userid'=>$newUserid],
            ['userid'=>$userid]
        );
        $info=$this->info([
            'condition'=>[
                'userid'=>$newUserid
            ]
        ]);
        return $info;
    }

    //修改密码
    public function updatePassword($userid,$password){
        $info=$this->info([
            'condition'=>[
                'userid'=>$userid,
                'status'=>1
            ]
        ]);
        if (empty($info)){
            return self::error('ERROR_INVALID_USERID', '用户不存在');
        }
        $this->update(
            ['password'=>$password],
            ['userid'=>$userid]
        );
        $info=$this->info([
            'condition'=>[
                'userid'=>$userid,
                'status'=>1
            ]
        ]);
        return $info;
    }


    //更新用户的权限
    /**
     * @param $userid
     * @param $role_name
     * @return null $info
     */
    public function updateRole($userid,$role_name){
        $info=$this->info([
            'condition'=>[
                'userid'=>$userid,
                'status'=>1
            ]
        ]);
        if(empty($info)) {
            return self::error('ERROR_INVALID_USERID', '不存在该用户');
        }
        $info=$this->update(
            ['role_name'=>$role_name],
            ['userid'=>$userid]
        );
        return $info;
    }

    //查询所有用户的基本信息 分页
    public function queryAllPage($size,$page){
        $lists=$this->lists([
            'size'=>$size,
            'page'=>$page,
            'condition'=>[
                'status'=>1
            ]
        ]);
        return $lists;
    }
    //查询所有用户的基本信息
    public function queryAll(){
        $lists=$this->lists([
            'condition'=>[
                'status'=>1
            ]
        ]);
        return $lists;
    }

    //添加或者修改邮箱
    public function addEmail($userid,$email){
        $info=$this->info([
            'condition'=>[
                'userid'=>$userid,
                'status'=>1
            ]
        ]);

        if (empty($info)){
            return self::error('ERROR_INVALID_USERID', '用户不存在');
        }
        $this->update(
            ['email'=>$email],
            ['userid'=>$userid,
                'status'=>1]
        );
        $info=$this->info([
            'condition'=>[
                'userid'=>$userid,
                'status'=>1
            ]
        ]);
        return [
            'id'=>$info['id'],
            'userid'=>$info['userid'],
            'role_name'=>$info['role_name'],
            'email'=>$info['email']
        ];
    }
}