<?php

namespace app\modules\user\service;

use app\common\base\ApiService;
use app\modules\user\models\Role;

class RoleService extends ApiService
{
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->model=new Role();
    }

    public function addRole($name,$description){
        $info=$this->info([
            'condition'=>[
                'name'=>$name
            ]
        ]);

        if(!empty($info)) {
            if ($info['status']==0){
                return self::error('ERROR_INVALID_USERID', '该角色处于删除状态，请您进行恢复操作');
            }
            return self::error('ERROR_INVALID_USERID', '该角色已存在');
        }

        $newRole=$this->add([
            'name'=>$name,
            'description'=>$description,
            'status'=>1
        ]);
        return $newRole ;
    }
    public function deleteRole($name){
        $info=$this->info([
            'condition'=>[
                'name'=>$name,
                'status'=>1
            ]
        ]);
        if(empty($info)) {
            return self::error('ERROR_INVALID_USERID', '该角色不存在');
        }
        //删除权限
        self::callModuleService('user','RightService','deleteUserRight',$info['id']);
        //删除角色
        $info=$this->update(
            ['status'=>0],
            ['name'=>$name]
        );
        return $info;
    }

    public function updateRole($id,$name,$description){
        $info=$this->info([
            'condition'=>[
                'id'=>$id,
                'status'=>1
            ]
        ]);
        if(empty($info)) {
            return self::error('ERROR_INVALID_USERID', '该角色不存在');
        }
        $role=$this->update(
            ['name'=>$name,
            'description'=>$description],
            ['id'=>$id]
        );
        $newRole=$this->info([
            'condition'=>[
                'id'=>$id,
                'status'=>1
            ]
        ]);
        return $newRole;
    }

    //通过角色名，获取权限
    public function getNameRight($role_name){
        $info=$this->info([
            'condition'=>[
                'name'=>$role_name,
                'status'=>1
            ]
        ]);
        if (empty($info)){
            return self::error('ERROR_INVALID_USERID', '该角色不存在');
        }
        $lists=self::callModuleService("user",'RightService','getRoleRight',$info['id']);
        return $lists;
    }
}