<?php

namespace app\modules\book\service;

use app\common\base\ApiService;

class BookRecommendService extends ApiService
{
    //更新推荐书籍
    public function updateRecommendBook($userId,$bookId){
        $recommendBookInfo=$this->info([
            'condtion'=>[
                'user_id'=>$userId,
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
                'user_id'=>$userId,
                'book_id'=>$bookId,
                'score'=>$score
            ]);
        }
        return [
            'status'=>true
        ];
    }
}