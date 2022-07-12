<?php

namespace app\controllers\user;

use app\common\base\ApiController;
use FontLib\Table\Type\name;
use function GuzzleHttp\Psr7\str;

class RoleController extends ApiController
{
    //增加角色
    //也要在right中添加权限
    public function  actionAddRole(){
        $this->rules=[
            [['role_name','lists','description'],'required'],
            [['role_name','description'],'string'],
            ['lists','app\helpers\ArrayValidator']
        ];
        $input=$this->validate();

        //创建角色
        $info=self::callModuleService('user','RoleService','addRole',$input['role_name'],$input['description']);
        //查询角色信息
        $role=self::callModuleService('user','RoleService','info',[
            'name'=>$input['role_name'],
            'status'=>1
        ]);
        //先添加权限
        foreach ($input['lists'] as $key=>$list){
            foreach ($list as $value){
                self::callModuleService('user','RightService','addRight',(string) $role['id'],$key,$value);
            }
        }

        $content='管理员添加角色,'."角色名:".$input['role_name'];
        $log = self::callModuleService('user','LogService','addLog',"0",$content);


        return $role;
    }



    //删除角色
    public function  actionDeleteRole(){
        $this->rules=[
            [['role_name'],'required'],
            ['role_name','string']
        ];
        $input=$this->validate();
        $info=self::callModuleService('user','RoleService','deleteRole',$input['role_name']);
            return $info;
    }

    //修改角色
    public function actionUpdateUser(){
        $this->rules=[
            [['id','role_name','lists','description'],'required'],
            [['role_name','description'],'string'],
            ['lists','app\helpers\ArrayValidator'],
            ['id','integer']
        ];
        $input=$this->validate();

        //更新角色名和描述
        $newRole=self::callModuleService('user','RoleService','UpdateRole',$input['id'],$input['role_name'],$input['description']);
        //删除角色之前拥有的权限
        self::callModuleService('user','RightService','deleteUserRight',$newRole['id']);
        //重新添加权限
        foreach ($input['lists'] as $key=>$list){
            foreach ($list as $value){
               $info=self::callModuleService('user','RightService','updateRight',(string) $newRole['id'],$key,$value);
            }
        }

        $content='管理员修改角色信息,'."角色名:".$input['role_name'];
        $log = self::callModuleService('user','LogService','addLog',"0",$content);

        return $info;
    }

    public function actionQueryRightAllPage(){
        $this->rules=[
            [['size','page'],'required'],
            [['size','page'],'integer']
        ];
        $input=$this->validate();
        $lists=self::callModuleService('user','RoleService','lists',
            [
                'size'=>$input['size'],
                'page'=>$input['page'],
                'condition'=>['status'=>1]
            ]);
        $infos=array();
        foreach ($lists as $list) {
            $right=self::callModuleService('user','RightService','getRoleRight',(string) $list['id']);
            $info=['role'=>$list,'right'=>$right];
            array_push($infos,$info);
        }
        return $infos;
    }

    //查询所有角色的大致信息
    public function actionQueryAll(){
        $lists= self::callModuleService('user','RoleService','lists',
        ['condition'=>[
            'status'=>1
        ]]);
        return $lists;
    }
    //查询某个角色拥有的所有权限,根据角色id查询
    public function actionQueryRole(){
        $this->rules=[
            [['role_id'],'required'],
            [['role_id'],'string'],
        ];
        $input=$this->validate();
        $lists= self::callModuleService('user','RightService','lists',$input['role_id']);
        return $lists;
    }

}