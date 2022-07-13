<?php

namespace app\modules\book\service;

use app\common\base\ApiService;
use app\modules\book\model\BookRatings;
use app\modules\book\model\BookRecommend;

class BookRecommendService extends ApiService
{

    public function __construct()
    {
        parent::init();
        $this->model=new BookRecommend();
    }

    //更新推荐书籍
    public function updateRecommendBook($userName,$bookId){
        //查询用户id
        $userInfo=self::callModuleService('user','UserService','info',[
            'condition'=>[
                'username'=>$userName
            ]
        ]);
        if (empty($userInfo)){
            return self::error('ERROR_INVALID_PASSWORD', '用户不存在');
        }
        $recommendBookInfo=$this->info([
            'condition'=>[
                'user_id'=>$userInfo['id'],
                'book_id'=>$bookId
            ]
        ]);
        if (!empty($recommendBookInfo)){
            $score=(int)$recommendBookInfo['score'];
            if ($score+0.5>10){
                $score=10;
            }else{
                $score+=0.5;
            }
            $this->update([
                'score'=>$score
            ],[
                'id'=>$recommendBookInfo['id']
            ]);
        }else{
            $score=0.5;
            $this->add([
                'user_id'=>$userInfo['id'],
                'book_id'=>$bookId,
                'score'=>$score
            ]);
        }
        return [
            'status'=>true
        ];
    }
}