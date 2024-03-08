<?php

namespace MintHCM\Firebase;

abstract class PushNotification
{
    protected $config;
    public function __construct()
    {
        $this->config = new Config();
    }

    public function sendNotification($title, $to, $body = '', $image = '', $link = '')
    {
        $response = false;
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $this->config->get('url'));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            $headers = [
                'Content-Type: application/json',
                'Authorization: key=' . $this->config->get('server_key'),
            ];
            $notification_body = [
                'notification' => [
                    'title' => html_entity_decode(htmlspecialchars_decode($title), ENT_QUOTES),
                    'body' => html_entity_decode(htmlspecialchars_decode($body), ENT_QUOTES),
                    'sound' => $this->config->get('sound'),
                    'badge' => $this->config->get('badge'),
                    'android_channel_id' => $this->config->get('android_channel_id'),
                ],
                'priority' => $this->config->get('priority'),
            ];
            if (count($to) > 1) {
                $notification_body += ['registration_ids' => $to];
            } else {
                $notification_body += ['to' => implode('|', $to)];
            }
            if (!empty($image)) {
                $notification_body['notification'] += ['image' => $image];
            }
            if (!empty($link)) {
                $notification_body['data'] = ['link' => $link];
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification_body));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            if (!curl_errno($ch)) {
                $response = json_decode($response, true);
                if (isset($response['success']) && $response['success'] === 0 && !empty($response['results'][0]['error'])) {
                    $GLOBALS['log']->fatal('[Firebase Push Notifications] Response error: ' . $response['results'][0]['error']);
                } elseif (!empty($response['results'][1]['error'])) {
                    $GLOBALS['log']->fatal('[Firebase Push Notifications] Response partial error: ' . $response['results'][1]['error']);
                }
            } else {
                $GLOBALS['log']->fatal('[Firebase Push Notifications] Error:' . curl_error($ch));
            }
            curl_close($ch);
        } catch (\Throwable $e) {
            $GLOBALS['log']->fatal('[Firebase Push Notifications] Error:' . $e->getMessage());
        }
        return $response;
    }

    abstract public function execute($data);

}
