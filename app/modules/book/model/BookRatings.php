<?php

namespace app\modules\book\model;

use app\common\base\ApiModel;

class BookRatings extends ApiModel
{
    public static function tableName(){
        return '{{%book_ratings}}';
    }
    public function rules(){
        return [
           [['id','userid','bookid','score'],'integer']
        ];
    }
}