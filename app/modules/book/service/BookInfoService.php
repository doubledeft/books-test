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
}