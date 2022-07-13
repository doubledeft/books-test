<?php

namespace app\modules\order\model;

use app\common\base\ApiModel;
use app\modules\book\model\BookInfo;

class Cart extends ApiModel
{
    public static function tableName(){
        return '{{%Cart}}';
    }
    public function rules(){
        return [
            [['id','user_id','book_id','status'], 'integer'],
        ];
    }

    public function getBookInfo()
    {
        return $this->hasOne(BookInfo::className(), ['id' => 'book_id'])
            ->select(['id','name', 'author','price']);
    }
}