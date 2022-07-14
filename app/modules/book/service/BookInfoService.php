<?php

namespace app\modules\book\service;

use app\common\base\ApiService;
use app\modules\book\Book;
use app\modules\book\model\BookInfo;

class BookInfoService extends ApiService
{
    public function __construct()
    {
        parent::init();
        $this->model=new BookInfo();
    }

    //查询图书信息
    public function getBookInfo($bookId){
        $bookInfo=$this->info([
            'fields'=>['name','id','publish_date','author','image_url','detail'],
            'condition'=>[
                'id'=>$bookId
            ]
        ]);
        if (empty($bookInfo)){
            return self::error('ERROR_INVALID_PASSWORD', '没有此图书信息');
        }
        return $bookInfo;
    }


    //列出所有图书信息
    public function listBookInfo(){
        $bookList=$this->lists([
            'fields'=>['id','name','author','press'],
            'condition'=>[
                'is_shelf'=>1
            ],
            'size'=>20
        ]);
        return $bookList;
    }

    //近似查询图书
    public function searchBooks($keyword){
        $bookList=$this->lists([
            'fields'=>['name','author','id','image_url'],
            'condition'=>[
                'and',
                ['like','name',$keyword]
            ],
            'size'=>20
        ]);
        return $bookList;
    }

    public function deleteBook($bookId){
        $bookInfo=$this->info([
            'fields'=>['name','id','publish_date','author','image_url','detail'],
            'condition'=>[
                'id'=>$bookId
            ]
        ]);
        if (empty($bookInfo)){
            return self::error('ERROR_INVALID_PASSWORD', '没有此图书信息');
        }
        $this->update([
            'is_shelf'=>0
        ],[
            'id'=>$bookId
        ]);
        return [
            'status'=>true
        ];
    }


    public function listHotBook(){
       $recommendList= self::callModuleService('book','BookRecommendService','lists',[
            'condition'=>[
                'and',
                ['>','score',2]
            ],
            'size'=>20
        ]);
        $bookIds=array_column($recommendList,'book_id');
        $bookList=$this->lists([
            'fields'=>[
                'name','author','id','image_url'
            ],
            'condition'=>[
                'id'=>$bookIds
            ]
        ]);
        return $bookList;
    }

    public function recommendByUser($userName){
        //查询用户id
        $userInfo=self::callModuleService('user','UserService','info',[
            'condition'=>[
                'username'=>$userName
            ]
        ]);
        if (empty($userInfo)){
            return self::error('ERROR_INVALID_PASSWORD', '用户不存在');
        }
        $userRecommendList=self::callModuleService('book','BookRecommendService','lists',[
            'condition'=>[
                'user_id'=>$userInfo['id']
            ],
        ]);
        return $userRecommendList;
        $bookIds=array_column($userRecommendList,'book_id');
        //查询其他用户对这些书的打分
        $userRating=self::callModuleService('book','BookRatingService','lists',[
            'condition'=>[
                'bookid'=>$bookIds
            ]
        ]);
        //查询这些用户的推荐书籍
        $recomendBookList=self::callModuleService('book','BookRecommendService','lists',[
            'condition'=>[
                'user_id'=>array_column($userRating,'userid')
            ]
        ]);
        $bookList=$this->lists([
            'fields'=>['name','author','id','image_url'],
            'condition'=>[
                'id'=>array_column($recomendBookList,'book_id')
            ]
        ]);
        return $bookList;
    }
}