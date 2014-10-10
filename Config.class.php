<?php
class Config
{
    // Application credentials
    const CONSUMER_KEY    = "";
    const CONSUMER_SECRET = "";

    const ACCESS_TOKEN    = "";
    const ACCESS_SECRET   = "";

    public static function getConsumerKey() {
        return Config::CONSUMER_KEY;
    }

    public static function getConsumerSecret() {
        return Config::CONSUMER_SECRET;
    }

    public static function getAccessToken() {
        return Config::ACCESS_TOKEN;
    }

    public static function getAccessSecret() {
        return Config::ACCESS_SECRET;
    }
}
?>
