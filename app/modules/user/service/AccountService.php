<?php

namespace app\modules\user\service;

use app\modules\user\models\Account;
use yii;
class AccountService extends \app\common\base\ApiService
{
    public function __construct()
    {
        parent::init();
        $this->model=new Account();
    }


    /**
     * 登录；
     *
     * @params int $user_id 用户名
     * @params string $password 密码
     */
    public function login($userid,$password)
    {
        //查询数据库表
        $info=$this->info([
            'condition'=>[
                'userid'=>$userid,
                'status'=>1,
            ]
        ]);
        //验证账号密码
        if(empty($info)) {
            return self::error('ERROR_INVALID_USERID', '用户不存在');
        }
        if($password!= $info['password']) {
            return self::error('ERROR_INVALID_PASSWORD', '密码不正确');
        }
        $token = Yii::$app->jwt->createToken([
            'id'=>$userid,
            'type'=>$info['type']
        ]);
        return [
            'info'=>$info,
            'token'=>$token
            ];
    }

    /**
     * 注册;
     *
     * @params int $user_id 用户名
     * @params string $password 密码
     */
    public function register($userid,$password){
        $info=$this->info([
            'condition'=>[
                'userid'=>$userid,
            ]
        ]);
        if(!empty($info)) {
            return self::error('ERROR_INVALID_USERID', '用户已存在');
        }

        $newAccount=$this->add([
            'userid'=>$userid,
            'password'=>$password,
            'is_user'=>1,
            'status'=>1,
            'create_timestamp'=>$this->timeNow,
            'update_timestamp'=>$this->timeNow,
        ]);
        return $newAccount;
    }

}