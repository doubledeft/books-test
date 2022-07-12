<?php

namespace app\modules\site\models;

use app\common\base\ApiModel;

class Site extends ApiModel
{
    public static function tableName()
    {
        return '{{%test_param}}';
    }
	
	/**
     * rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['param', 'value'], 'required'],
            ['create_timestamp', 'integer'],
            [['param', 'value'], 'string'],
            ['status', 'in', 'range' => [0, 1]],
        ];
    }

    /**
     * beforeSave
     *
     * @param bool $insert
     * @return void
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->status = 1;
        }

        return parent::beforeSave($insert);
    }


    public function fields(){
        $fields = parent::fields();

        if(isset($fields['create_timestamp'])){
            $fields['create_time'] = function($model){
                return date("Y-m-d H:i", $model->create_timestamp/1000);
            };
        }
        return $fields;
    }
}
