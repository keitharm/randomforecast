<?php
require_once("Config.class.php");
require_once("codebird/codebird.php");

class Twitter
{
    public static function post($content, $imageurl = null) {
        \Codebird\Codebird::setConsumerKey(Config::CONSUMER_KEY, Config::CONSUMER_SECRET);
        $cb = \Codebird\Codebird::getInstance();
        $cb->setToken(Config::ACCESS_TOKEN, Config::ACCESS_SECRET);


        if ($imageurl == null) {
            $params = array(
                'status' => $content
            );
            $cb->statuses_update($params);
        } else {
            $params = array(
                'status' => $content,
                'media[]' => $imageurl
            );
            $cb->statuses_updateWithMedia($params);
        }
    }
}
?>
