<?php
/**
 * 用户文件管理;
 */

namespace app\modules\files\models;

use app\common\base\ApiModel;

class UserFileManage extends ApiModel{

    public static function tableName()
    {
        return '{{%user_file_manage}}';
    }

    public function rules()
    {
        return [
            [['attachment_url', 'file_size'], 'required'],
            [['attachment_url', 'owner_id', 'uploader_id'], 'string'],
            [['file_size', 'is_valid', 'create_timestamp', 'delete_timestamp', 'quote_num', 'status'], 'integer'],
            [['is_valid', 'status'], 'in', 'range'=>[0, 1]],
        ];
    }

}