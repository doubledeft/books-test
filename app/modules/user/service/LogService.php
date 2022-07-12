<?php

namespace app\modules\user\service;


use app\common\base\ApiService;
use app\modules\user\models\Log;

class LogService extends ApiService {

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->model = new Log();
    }
    /**
     * 写入日志
     */
    public function addLog($user_id,$content){
        $newLog=$this->add([
            'user_id'=>$user_id,
            'create_timestamp'=>date("Y-m-d H:i", time()),
            'content'=>$content,
            'status'=>1
        ]);
        return $newLog;
    }
    /**
     * 查询日志
     */
    public function queryLog(){
        return $this->lists([
            'condition'=>[
                'status'=>1
            ]
        ]);
    }

}

