<?php
/**
 * 公共的文件操作 API 接口
 */

namespace app\modules\common\service;

use Yii;
use app\common\base\ApiService;
use app\helpers\DatetimeHelper;

class FileOperateService extends ApiService
{
    public $uploadAsset;

    CONST RECORD_PATH_PREFIX = 'record_path/';
    CONST EXCEL_PATH_PREFIX = 'excel_path/';
    CONST FILE_PIECE_SIZE = 1024*1024*10;
	
    function init() {
        parent::init();
        $timeNow = DatetimeHelper::msectime();
        $this->uploadAsset = [
            'notify' => [
                'path' => 'keyan/upload/notify/' . date('Y/m/d') . $timeNow . '/%s',
            ],
            'answer' => [
                'path' => 'keyan/upload/answer/' . date('Y/m/d') . $timeNow . '/%s',
            ],
            'zhengji' => [
                'path' => 'keyan/upload/zhengji/' . date('Y/m/d') . $timeNow . '/%s',
            ],
            'project' => [
                'path' => 'keyan/upload/project/' . date('Y/m/d') . $timeNow . '/%s',
            ],
            'setting' => [
                'path' => 'keyan/upload/setting/' . date('Y/m/d') . $timeNow . '/%s',
            ],
            'record' => [
                'path' => (Yii::$app->params['downloadTmpPath']) . self::RECORD_PATH_PREFIX . date('Y/m/d') . '/%s',
            ],
            'excel' => [
                'path' => (Yii::$app->params['downloadTmpPath']) . self::EXCEL_PATH_PREFIX . date('Y/m/d') . '/%s',
            ],
        ];
    }

    /**
     * 上传
     */
    public function upload($asset, $file)
    {
        $key = sprintf($this->uploadAsset[$asset]['path'], $file['name']);
        if(empty($file['tmp_name'])) {
            return self::error('ERROR_QINIU_UPLOAD_FAILED', '上传文件失败');
        }
        $key = Yii::$app->qiniu->upload ($file['tmp_name'], $key);
//        print_r($key);
        return [
            'key' => $key,
            'url' => Yii::$app->qiniu->generateUrl($key),
        ];
    }

    /**
     * 上传私有文件
     */
    public function uploadPrivate($asset, $file)
    {
        $key = sprintf($this->uploadAsset[$asset]['path'], $file['name']);
        $key = Yii::$app->qiniu->upload($file['tmp_name'], $key, Yii::$app->qiniu->privateBucket);

        return [
            'key' => $key,
            'url' => Yii::$app->qiniu->generateUrl($key, 'private'),
        ];
    }

    // 上传多个文件
    public function uploadMulti($asset, $files)
    {
        $keys = [];
        $urls = [];
        // 解析文件格式
        $length = 1;
        $isFileArray = false;
        if (is_array($files['name'])) {
            $length = count($files['name']);
            $isFileArray = true;
        }
        for ($i = 0; $i < $length; $i ++) {
            if (!$isFileArray) {
                $file = $files;
            } else {
                $file = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size'  => $files['size'][$i],
                ];
            }
            $upload = $this->upload($asset, $file);
            $keys[] = $upload['key'];
            $urls[]  = $upload['url'];
        }
        return [
            'keys' => $keys,
            'urls' => $urls,
        ];
    }
    
    /**
     * 设置文件生存时间，$days=0代表取消生存时间,默认15天;
     * @param integer $days 生存时间，单位：天
     * @param array $keys 在七牛上存储的文件名
     *
     * @return null
     */
    public function setSurviveDays($days = 15, $keys)
    {
        foreach($keys as $key){
            Yii::$app->qiniu->setSurviveDays($days, $key);
        }
    }

    /**
     * 删除文件;
     * @param string $key 在七牛上存储的文件名
     *
     * @return null
     */
    public function removeFile($key)
    {
        Yii::$app->qiniu->removeFile($key);
    }

    /**
     * 删除文件;
     * @param array $key 在七牛上存储的文件名
     *
     * @return null
     */
    public function removeFiles($keys)
    {
        foreach($keys as $key){
            Yii::$app->qiniu->removeFile($key);
        }
    }


    /**
     * 上传文件到本地
     */
    public function uploadNative($asset, $file)
    {
        $filePath = sprintf($this->uploadAsset[$asset]['path'], $file['name']);
        
        if(!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }
        if(move_uploaded_file($file['tmp_name'], $filePath)){
            $file['tmp_name'] = $filePath;
            
            return [
                'url' => $file,
            ];
        }else{
            return self::error('ERROR_SAVE_RECORD_FILE_FAIL', '文件本地保存失败');//300210
        }
    }
	
	/**
     * 下载本地文件
     */
    public function downloadNative($url)
    {		
		if(file_exists($url)) {
			$fp = fopen($url,"r"); 
			$fileSize = filesize($url); 
			$fileName = basename($url);
			$fileName = iconv("utf-8", "gb2312", $fileName);
			
			//下载文件需要用到的头 
			Header("Content-type: application/octet-stream"); 
			Header("Accept-Ranges: bytes"); 
			Header("Accept-Length:" . $fileSize); 
			Header("Content-Disposition: attachment; filename=".$fileName); 
			$buffer = 1024; 
			$file_count = 0; 

			//向浏览器返回数据 
			while(!feof($fp) && $file_count < $fileSize){ 
				$file_con = fread($fp, $buffer); 
				$file_count += $buffer; 
				echo $file_con; 
			} 
			fclose($fp); 
        }else {
			return self::error('ERROR_DOWNLOAD_RECORD_FILE_FAIL', '本地文件不存在');//300211
		}
        return true;
    }
}
