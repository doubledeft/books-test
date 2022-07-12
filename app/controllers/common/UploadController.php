<?php
/**
 * 上传控制器
 * @author heyijia
 * @date 2018-7-11
 * @update mazhenling
 */

namespace app\controllers\common;

use Yii;
use app\common\base\ApiController;
use app\helpers\DatetimeHelper;
use app\modules\common\service\FileOperateService;

class UploadController extends ApiController
{
    public function actionFile()
    {
        $this->rules = [
            [['asset', 'file'], 'required'],
            ['asset', 'in', 'range' => array_keys((new FileOperateService)->uploadAsset)],
            ['file', 'file'],
        ];
        $inputs = $this->validate();

        $info = self::callModuleService('common', 'FileOperateService', 'upload', $inputs['asset'], $inputs['file']);
        self::callModuleService('files', 'UserFileManageService', 'addFiles', [$info['url']], [$inputs['file']['size']]);
        return [
            'url' => $info['url'],
        ];
    }
    
    //上传多个文件
    public function actionMultiFile()
    {
        $this->rules = [
            [['asset', 'files'], 'required'],
            ['asset', 'string'],
            ['files', 'app\helpers\ArrayValidator'],
            ['files', 'file'],
        ];
        $inputs = $this->validate();
        $info = self::callModuleService('common', 'FileOperateService', 'uploadMulti', $inputs['asset'], $inputs['files']);
        self::callModuleService('files', 'UserFileManageService', 'addFiles', $info['urls'], $inputs['files']['size']);
        return [
            'urls' => $info['urls'],
        ];
    }

    // 上传私有文件(无验证不能下载)
    public function actionPrivateFile()
    {
        $this->rules = [
            [['asset', 'file'], 'required'],
            ['asset', 'in', 'range' => array_keys((new FileOperateService)->uploadAsset)],
            ['file', 'file'],
        ];
        $inputs = $this->validate();
        $info = self::callModuleService('common', 'FileOperateService', 'uploadPrivate', $inputs['asset'], $inputs['file']);
        return [
            'url' => $info['url'],
        ];
    }

     // 获取私有文件下载地址
    public function actionDownloadPrivate()
    {
        $this->rules = [
            [['url'], 'required'],
            ['url', 'string'],
        ];
        $inputs = $this->validate();
        $downloadUrl = Yii::$app->qiniu->generatePriDownloadUrl($inputs['url']);

        return [
            'downloadUrl' => $downloadUrl,
        ];
    }
    
    // 存储文件到服务器本地
    public function actionFileNative()
    {
        $this->rules = [
            [['asset', 'file'], 'required'],
            ['asset', 'in', 'range' => array_keys((new FileOperateService)->uploadAsset)],
            ['file', 'file'],
        ];
        $inputs = $this->validate();
		
		$userId = $this->userInfo->id;
        $info = self::callModuleService('common', 'FileOperateService', 'uploadNative', $inputs['asset'], $inputs['file']);
		
		self::callModuleService('files', 'FileRecordService', 'add', [
			'user_id' => $userId,
			'url' => $info['url']['tmp_name'],
			'size' => $info['url']['size'],
			'create_timestamp' => DatetimeHelper::msectime(),
		]);
        return [
            'url' => $info['url'],
        ];
    }
	
    // 下载本地文件
    public function actionDownloadNative()
    {
        $inputs = Yii::$app->request->get();
		
        $userId = $this->userInfo->id;
		$fileInfo = self::callModuleService('files', 'FileRecordService', 'info', [
			'condition' => [
				'url' => $inputs['url'],
				'status' => 1,
			]
		]);
		if(empty($fileInfo)) {
			return self::error('ERROR_DOWNLOAD_RECORD_FILE_FAIL', '本地文件不存在或已被删除');//300211
		}else if($userId != $fileInfo['user_id']) {
			return self::error('ERROR_DOWNLOAD_RECORD_FILE_FAIL_PERMISSION', '非文件上传人不可下载该文件');//300212
		}
		self::callModuleService('common', 'FileOperateService', 'downloadNative', $inputs['url']);
        
		return [
            'status' => true,
        ];
    }
}
