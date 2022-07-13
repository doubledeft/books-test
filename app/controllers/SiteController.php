<?php
/**
 * 测试
 *
 */

namespace app\controllers;

use Yii;
use yii\apidoc\models\TypeDoc;
use yii\helpers\ArrayHelper;
use app\helpers\DatetimeHelper;
use app\common\base\ApiController;

class SiteController extends ApiController
{
    /**
     * 测试
     */
    public function actionIndex()
    {
		$this->rules = [
            ['param', 'required'],
            ['param', 'string'],
        ];
        $inputs = $this->validate();
        // ..

		$info = self::callModuleService('site', 'SiteService', 'info', [
			'condition' => [
				'param' => $inputs['param'],
				'status' => 1
			]
		]);
		return $info;

//        $users=self::callModuleService('user', 'UserService', 'lists',[]);
//      $email=self::callModuleService('email', 'EmailService', 'sendContractUpdate',$users);
    }

    public function actionInfo(){
        return [
            'name'=>'book',
            'number'=>1
        ];
    }
}
