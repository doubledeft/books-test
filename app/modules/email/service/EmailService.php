<?php

namespace app\modules\email\service;

use app\common\base\ApiService;

class EmailService extends ApiService
{
    const TEMPLATE_SUBJECT = [
        'CONTRACT_GET'=>'新的合同分配安排',
        'CONTRACT_UPDATE'=>'合同进度更新',
        'CUSTOMER_INFO'=>'合同创建信息',
        'CONTRACT_INFO'=>'合同相关信息'
    ];

    const TEMPLATE_MESSAGE = [
        'CONTRACT_GET'=>'
          <p>尊敬的用户：</p>
          <p>&nbsp;&nbsp;您好！</p>
         <p>&nbsp;&nbsp;您有新的合同分配安排，请您及时登录北京交通大学合同管理系统，查看分配的合同工作，感谢您的支持！</p>
          <p align="right">北京交通大学合同管理系统团队&nbsp;&nbsp;&nbsp;</p>
        ',
        'CONTRACT_UPDATE'=>'
          <p>尊敬的用户：</p>
          <p>&nbsp;&nbsp;您好！</p>
         <p>&nbsp;&nbsp;您有所管理的合同进度有更新，请您及时登录北京交通大学合同管理系统，查看合同最新进度以开展相关工作，感谢您的支持！</p>
          <p align="right">北京交通大学合同管理系统团队&nbsp;&nbsp;&nbsp;</p>
        ',
        'CUSTOMER_INFO'=>'
          <p>尊敬的客户：</p>
          <p>&nbsp;&nbsp;您好！</p>
         <p>&nbsp;&nbsp;新的合同已经生成，请您及时登录北京交通大学合同管理系统，查看合同内容，感谢您的支持！</p>
          <p align="right">北京交通大学合同管理系统团队&nbsp;&nbsp;&nbsp;</p>
        ',
        'CONTRACT_INFO'=>'
           <p>尊敬的客户：</p>
          <p>&nbsp;&nbsp;您好！</p>
         <p>&nbsp;&nbsp;合同已经完成，请您及时登录北京交通大学合同管理系统，查看合同内容，感谢您的支持！</p>
          <p align="right">北京交通大学合同管理系统团队&nbsp;&nbsp;&nbsp;</p>
        '
    ];

    //需要要发送的用户信息，包含邮箱就行
    public function sendContractGet($user){
//        $mails=array();
//        foreach ($users as $user) {
//            if (!isset($user['email'])){
//                continue;
//            }
//            array_push($mails,$user['email']);
//        }
        if (!empty($user)&&$user['email']!=null){
            $email=self::callModuleService('email', 'SendEmailService', 'send',$user['email'],
                self::TEMPLATE_SUBJECT['CONTRACT_GET'],self::TEMPLATE_MESSAGE['CONTRACT_GET']);
            return $email;
        }
    }

    public function sendContractUpdate($user){
        $email=self::callModuleService('email', 'SendEmailService', 'send',$user['email'],
            self::TEMPLATE_SUBJECT['CONTRACT_UPDATE'],self::TEMPLATE_MESSAGE['CONTRACT_UPDATE']);
        return $email;
    }

    public function sendCustomerInfo($user){

        if (!empty($user)){
            $email=self::callModuleService('email', 'SendEmailService', 'send',$user['email'],
                self::TEMPLATE_SUBJECT['CUSTOMER_INFO'],self::TEMPLATE_MESSAGE['CUSTOMER_INFO']);
            return $email;
        }
    }
    public function sendContractInfo($users){
        $mails=array();
        foreach ($users as $user) {
            if (!isset($user['email'])){
                continue;
            }
            array_push($mails,$user['email']);
        }
        $email=self::callModuleService('email', 'SendEmailService', 'send',$mails,
            self::TEMPLATE_SUBJECT['CONTRACT_INFO'],self::TEMPLATE_MESSAGE['CONTRACT_INFO']);
        return $email;
    }

}