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
}