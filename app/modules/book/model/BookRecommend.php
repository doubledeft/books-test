<?php

namespace app\modules\book\model;

use app\common\base\ApiModel;

class BookRecommend extends ApiModel
{
    public static function tableName(){
        return '{{%book_recommend}}';
    }
    public function rules(){
        return [
            [['id'],'integer'],
            [['user_id','book_id'],'string'],
            [['score'],'double']
        ];
    }
}