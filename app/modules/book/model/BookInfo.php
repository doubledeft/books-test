<?php

namespace app\modules\book\model;

use app\common\base\ApiModel;
use app\modules\business\models\ApplyNotifyConfig;

class BookInfo extends ApiModel
{
    public static function tableName(){
        return '{{%book_info}}';
    }
    public function rules(){
        return [
            [['book_id', 'book_category_id', 'store_id','pages','deal_mount','look_mount','store_mount','is_shelf'], 'integer'],
            [['name', 'outline','detail','press','publish_date','size','version','author','translator','isbn','catalog',
                'image_url','pack_style','cname','description','cata','content','store_time'], 'string'],
            [['market_price','member_price','discount'],'double'],
        ];
    }


//    public function getRecommendInfo()
//    {
//        return $this->hasOne(BookRecommend::className(), ['user_id' => 'user_id']);
//    }
}