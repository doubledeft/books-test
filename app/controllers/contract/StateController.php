<?php

namespace app\controllers\contract;

use app\common\base\ApiController;

class StateController extends ApiController
{


    //对应3.5.2功能
    //管理人员和合同操作查询合同流程
    //通过type查询合同目前状态，1:起草，2会签完成，3定稿完成，4审核完成，签订完成
    public function actionQueryByType(){
        $this->rules=[
            [['type'],'required'],
            [['type'],'integer']
        ];
        $input=$this->validate();
        $info=self::callModuleService('contract','StateService','queryByType',$input['type']);
        return $info;
    }
    //查询所有处于某一状态的合同信息 分页
    //合同状态,1:起草，2会签完成，3定稿完成，4审核完成，5签订完成
    public function actionQueryByTypePage(){
        $this->rules=[
            [['type','size','page'],'required'],
            [['type','size','page'],'integer']
        ];
        $input=$this->validate();
        $info=self::callModuleService('contract','StateService','queryContractByTypePage',$input['type'],
        $input['size'],$input['page']);
        return $info;
    }

    //查询所有处于某一状态的合同信息
    //合同状态,1:起草，2会签完成，3定稿完成，4审核完成，5签订完成
    public function actionQueryContractByType(){
        $this->rules=[
            [['type'],'required'],
            [['type'],'integer']
        ];
        $input=$this->validate();
        $info=self::callModuleService('contract','StateService','queryContractByType',$input['type']);
        return $info;
    }

//    //查询所有的合同
//    public function actionQueryAll(){
//        $list=self::callModuleService('contract','StateService','queryAll
//        ');
//        return $list;
//    }

}