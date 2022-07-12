<?php

namespace app\modules\email\service;
use Yii;
use app\common\base\ApiService;

class SendEmailService extends ApiService
{
    /**
     * 发送邮件
     *
     * @param array $emailAddress    邮箱
     * @param string $subject   邮件主题
     * @param string  $message  邮件内容
     * @return 
     */
    public function send(
        $emailAddress,
        $subject,
        $message
    ) {
        if(empty($emailAddress)) {
            return false;
        }
        // 超过200 条循环发送
        if (is_array($emailAddress) && count($emailAddress) > 200) {
            $res = $this->sendOperate(array_slice($emailAddress, 0, 200), $subject, $message);
            $this->send(array_slice($emailAddress, 200), $subject, $message);
            return $res;
        } else {
            $res = $this->sendOperate($emailAddress, $subject, $message);
            return $res;
        }
    }

    /**
     * 具体发送操作, 少于200条
     */
    private function sendOperate(
        $emailAddress,
        $subject,
        $message
    ) {
        $mail= Yii::$app->mailer->compose();
        $mail->setTo($emailAddress); //要发送给那个人的邮箱
        $mail->setSubject($subject); //邮件主题
        $mail->setHtmlBody($message); //发送带有HTML标签的文本
        $res = $mail->send();
        return $res;
    }
}
