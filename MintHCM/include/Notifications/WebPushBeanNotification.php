<?php
require_once 'include/Notifications/WebPushNotification.php';
class WebPushBeanNotification extends WebPushNotification
{
    public function __construct(SugarBean $bean)
    {
        parent::__construct($bean);
        $this->title = $bean->name;
        $this->body = $bean->description;
        $this->user_id = $bean->assigned_user_id;
        $this->url_redirect = '';
    }

    public function setUrl($url){
        $this->url_redirect = $url;
        return $this;
    }
}