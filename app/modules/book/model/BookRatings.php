<?php

namespace app\modules\book\model;

use app\common\base\ApiModel;
use app\modules\business\models\ApplyNotifyConfig;

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


    public function getBookInfo()
    {
        return $this->hasOne(BookInfo::className(), ['id' => 'bookid'])
            ->select(['id','name', 'author', 'publish_date','image_url']);
    }
}