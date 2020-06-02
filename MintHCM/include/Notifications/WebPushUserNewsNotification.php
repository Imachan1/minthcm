<?php
require_once 'include/Notifications/WebPushNotification.php';
class WebPushUserNewsNotification extends WebPushNotification
{
    public function __construct(UsersNews $bean)
    {
        parent::__construct($bean);
        $news = BeanFactory::getBean('News',$bean->news_id);
        $this->title = $bean->news_name;
        $this->body = '';
        $this->user_id = $bean->assigned_user_id;

        $this->url_redirect = 'index.php?module=UsersNews&action=DetailView&record=' . $bean->id;
    }

    public function setUrl($url){
        $this->url_redirect = $url;
        return $this;
    }
}