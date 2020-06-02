<?php
require_once 'include/Notifications/WebPushNotifiable.php';
abstract class WebPushNotification implements WebPushNotifiable
{
    protected $title;
    protected $body;
    protected $url_redirect;
    protected $user_id;
    protected $bean;

    public function __construct($bean){
        $this->bean = $bean;
    }

    public function getUserId()
    {
        return $this->user_id;
    }
    
    public function getNotificationTitle()
    {
        return $this->title;
    }
    public function getNotificationBody()
    {
        return $this->body;
    }

    public function getRedirectUrl()
    {
        return empty($this->url_redirect)? '': $this->url_redirect;
    }

    public function push(){
        if($this->canBePushed()){
            $bean = BeanFactory::newBean('Alerts');
            $bean->name = $this->getNotificationTitle();
            $bean->description = $this->getNotificationBody();
            $bean->assigned_user_id = $this->getUserId();
            $bean->is_read = 0;
            $bean->type = "webpush";
            $bean->alert_type = 'custom';
            $bean->url_redirect = $this->getRedirectUrl();
            $bean->save();
        }
    }

    protected function canBePushed(){
        include 'include/Notifications/notify_config.php';
        if(in_array($this->bean->module_dir,$allow_alerts_from)){
            return true;
        }
        return false;
    }

}