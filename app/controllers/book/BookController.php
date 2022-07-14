<?php

namespace app\controllers\book;

use app\common\base\ApiController;
use app\modules\common\service\FileOperateService;

class BookController extends ApiController
{
    public function actionGetBookInfo()
    {
        $this->rules = [
            [['book_id'], 'required'],
            [['book_id'],'integer']
        ];
        $inputs = $this->validate();

        $info = self::callModuleService('book', 'BookInfoService', 'getBookInfo',$inputs['book_id']);
        return [
            'info'=>$info
        ];
    }

    public function actionListBookInfo(){
        $info = self::callModuleService('book', 'BookInfoService', 'listBookInfo');
        return [
            'info'=>$info
        ];
    }

    public function actionUpdateRecommendBook(){
        $this->rules = [
            [['user_name','book_id'], 'required'],
            [['book_id'],'integer'],
            [['user_name'],'string']
        ];
        $inputs = $this->validate();

        $info = self::callModuleService('book', 'BookRecommendService', 'updateRecommendBook',
            $inputs['user_name'],$inputs['book_id']);
        return [
            'info'=>$info
        ];
    }

    //获取用户对书评分
    public function actionGetUserBookRating(){
        $this->rules = [
            [['user_name','book_id'], 'required'],
            [['book_id'],'integer'],
            [['user_name'],'string']
        ];
        $inputs = $this->validate();

        $info = self::callModuleService('book', 'BookRatingService', 'getUserBookRating',
            $inputs['user_name'],$inputs['book_id']);
        return [
            'info'=>$info
        ];
    }


    //搜索书籍
    public function actionSearchBooks(){
        $this->rules = [
            [['keyword'], 'required'],
            [['keyword'],'string']
        ];
        $inputs = $this->validate();

        $info = self::callModuleService('book', 'BookInfoService', 'searchBooks',$inputs['keyword']);
        return [
            'info'=>$info
        ];
    }

    //设置书籍评分
    public function actionSetBookRating(){
        $this->rules = [
            [['user_id','book_id','rank'], 'required'],
            [['user_id','book_id','rank'],'string']
        ];
        $inputs = $this->validate();

        $info = self::callModuleService('book', 'BookRatingService', 'setBookRating',$inputs['user_id']
            ,$inputs['book_id'],$inputs['rank']);
        return [
            'info'=>$info
        ];
    }

    //查询用户历史评分书籍
    public function actionListHistoryRatingBook(){
        $this->rules = [
            [['user_name'], 'required'],
            [['user_name'],'string']
        ];
        $inputs = $this->validate();

        $info = self::callModuleService('book', 'BookRatingService', 'listHistoryRatingBook',$inputs['user_name']);
        return [
            'info'=>$info
        ];
    }


    //删除书籍
    public function actionDeleteBook(){
        $this->rules = [
            [['book_id'], 'required'],
            [['book_id'],'integer']
        ];
        $inputs = $this->validate();

        $info = self::callModuleService('book', 'BookInfoService', 'deleteBook',$inputs['book_id']);
        return [
            'info'=>$info
        ];
    }

    //添加书籍
    public function actionAddBook(){
        $this->rules = [
            [['name','author','publish_date','press','image_url'], 'required'],
            [['name','author','publish_date','press','image_url'],'string']
        ];
        $inputs = $this->validate();

        $info = self::callModuleService('book', 'BookInfoService', 'addBook', $inputs['name'],
            $inputs['author'],$inputs['publish_date'],$inputs['press'],$inputs['iamge_url']);
        return [
            'info'=>$info
        ];
    }
}