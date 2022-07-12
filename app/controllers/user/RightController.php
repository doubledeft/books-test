<?php

namespace app\controllers\user;

use app\common\base\ApiController;
use function Complex\add;

class RightController extends ApiController
{
    //为每个角色分配权限，单独分配一个权限
    //通过type标识角色大的权限
    //通过roleName标识角色具体权限
    public function actionAddRight(){
        $this->rules=[
            [['role_id','type','role'],'required'],
            [['role_id','password'],'string'],
            [['type','role'],'integer']
        ];
        $input=$this->validate();
        $info=self::callModuleService('user','RightService','addRight',$input['role_id'],$input['type'],$input['role']);
        return $info;
    }
    //然后给一个用户分配权限
    public function actionAddUserRight()
    {
        $this->rules=[
            [['role_id','lists'],'required'],
            [['role_id'],'string'],
            [['lists'],'app\helpers\ArrayValidator']
        ];
        $input=$this->validate();

        foreach ($input['lists'] as $key=>$list){
            foreach ($list as $value){
                self::callModuleService('user','RightService','addRight',$input['role_id'],$key,$value);
            }
        }
    }


    //那感觉这玩意变成了查询拥有权限的角色有哪些了
    public function actionQueryByContractAllocation(){
        //根据role不同，分配返回不同类型合同权限用户
        //2流程管理下：1会签合同，2.审批合同，3.签订合同，4.分配会签，5分配审批，6分配签订，7流程查询
        $type=2;
        $roles=[1,2,3];
        $lists=array();
        foreach ($roles as $role){
            $list=self::callModuleService('user','RightService','queryByRole',$type,$role);
            $info=array($role=>$list);
            array_push($lists,$info);
            //$lists[$role]=$list;
        }
        return $lists;
    }



}