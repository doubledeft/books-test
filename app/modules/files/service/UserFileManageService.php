<?php

/**
 * 更新本地存储文件记录;
 */
namespace app\modules\files\service;

use Yii;
use yii\helpers\ArrayHelper;
use app\helpers\DatetimeHelper;
use app\common\base\ApiService;
use app\modules\files\models\UserFileManage;

class UserFileManageService extends ApiService
{
    function __construct()
    {
        parent::init();
        $this->model = new UserFileManage();
    }

    /**
     * 本地更新文件记录;
     * 两个数组一一对应;
     * @param array $fileUrls 上传文件的url;
     * @param array $fileSizes 上传的文件的大小;
     * @return null 
     */
    public function addFiles($fileUrls, $fileSizes)
    {
        $timeNow = DatetimeHelper::msectime();
        $tuple  = [
            'attachment_url' => '',
            'file_size' => 0,
            'is_valid' => 0,
            'create_timestamp' => $timeNow,
            'owner_id' => '',
            'uploader_id' => '',
            'status' => 1,
        ];
        $data = [];
        $qiniu = Yii::$app->qiniu;
        for ($i = 0; $i < count($fileUrls); $i++) {     //去掉url前缀 并变为json格式
            $tuple['attachment_url'] = json_encode($qiniu->removeCdnHost($fileUrls[$i]));
            $tuple['file_size'] = $fileSizes[$i];
            $data[] = $tuple;
        }

        $this->addMany($data);
    }

    public function updateAllCounters($counters, $condition = '')
    {
        return $this->model->updateAllCounters($counters, $condition);
    }
}
