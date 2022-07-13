<?php

namespace app\modules\book\service;

use app\common\base\ApiService;
use app\modules\book\model\BookInfo;
use app\modules\book\model\BookRatings;

class BookRatingService extends ApiService
{

    public function __construct()
    {
        parent::init();
        $this->model=new BookRatings();
    }

    //获取用户对书评分
    public function getUserBookRating($userId,$bookId){
        $bookRatingInfo=$this->info([
            'condition'=>[
                'userid'=>$userId,
                'bookid'=>$bookId
            ]
        ]);
        if (empty($bookRatingInfo)){
            return self::error('ERROR_INVALID_PASSWORD', '没有此用户对这本书的评分');
        }
        $score=(int) $bookRatingInfo['score'];
        return [
            'score'=>$score
        ];
    }
}