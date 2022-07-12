<?php

namespace app\modules\user\service;

use app\common\base\ApiService;
use app\modules\user\models\Right;

class RightService extends ApiService
{
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->model=new Right();
    }
    public function addRight($role_id, $type, $role){
        $info=$this->info([
            'condition'=>[
                'role_id'=>$role_id,
                'type'=>$type,
                'role'=>$role,
            ]
        ]);
        if(!empty($info)) {
            if ($info['status']==0){
               $info=$this->update([
                    'status'=>1
                ],[
                    'role_id'=>role_id,
                    'type'=>$type,
                    'role'=>$role
                ]);
                return $info;
            }else{
                return self::error('ERROR_INVALID_USERID', '该角色已分配该权限');
            }
        }else{
            $newRight=$this->add([
                'role_id'=>$role_id,
                'type'=>$type,
                'role'=>$role,
                'status'=>1
            ]);
            return $newRight;
        }
    }

    public function updateRight($role_id, $type, $role){
        $info=$this->info([
            'condition'=>[
                'role_id'=>$role_id,
                'type'=>$type,
                'role'=>$role,
            ]
        ]);
        if(!empty($info)) {

            if ($info['status']==0){
                $this->update(
                    ['status'=>1],
                    ['id'=>$info['id']]
                );
            }
        }else{
            $newRight=$this->add([
                'role_id'=>$role_id,
                'type'=>$type,
                'role'=>$role,
                'status'=>1
            ]);
        }
    }

    //感觉不需要了
    //想的话，是读取所有的权限，然后再更新权限
    public function updateUserRight($userid){
        //查询
        $lists=$this->lists(
            ['condition'=>[
                'userid'=>$userid
            ]]
        );
        //合并
        $rights="";
        foreach ($lists as $list){
           $right= $list['type'].".".$list['role'];
           $rights=$rights.$right.",";
        }
        //写入
        $info=self::callModuleService("user",'UserService','updateRight',$userid,$rights);
        return $info;
    }


    //查询单独每个权限的相关信息
    public function queryByRole($type,$role){
        //查询
        $lists=$this->lists(
            ['condition'=>[
                'type'=>$type,
                'role'=>$role,
                'status'=>1
            ]]
        );
        return $lists;
    }

    //删除角色的权限
     public function deleteUserRight($role_id){
         $lists=$this->lists(
             ['condition'=>[
                 'role_id'=>$role_id
             ]]
         );
         foreach ($lists as $right){
             $this->update(
                 ['status'=>0],
                 ['id'=>$right['id']]
             );
         }
     }

     //获得角色权限
    public function getRoleRight($role_id){
        //查询
        $lists=$this->lists(
            ['condition'=>[
                'role_id'=>$role_id
            ]]
        );
        $infos=array();
        foreach ($lists as $list){
            $right= $list['type'].".".$list['role'];
            array_push($infos,$right);
        }
        return $infos;
    }

}