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
    public function getUserBookRating($userName,$bookId){
        //查询用户id
        $userInfo=self::callModuleService('user','UserService','info',[
            'condition'=>[
                'username'=>$userName
            ]
        ]);
        if (empty($userInfo)){
            return self::error('ERROR_INVALID_PASSWORD', '用户不存在');
        }
        $bookRatingInfo=$this->info([
            'condition'=>[
                'userid'=>$userInfo['id'],
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

    //设置评分
    public function setBookRating($userId,$bookId,$rank){
        $this->update([
            'score'=>((int)$rank)*2
        ],[
            'userid'=>$userId,
            'bookid'=>$bookId
        ]);
        return [
            'status'=>true
        ];
    }

    public function listHistoryRatingBook($userName){
        //查询用户id
        $userInfo=self::callModuleService('user','UserService','info',[
            'condition'=>[
                'username'=>$userName
            ]
        ]);
        $ratingList=$this->lists([
            'condition'=>[
                'userid'=>$userInfo['id']
            ],
            'with'=>['bookInfo']
        ]);
        return $ratingList;
    }
}