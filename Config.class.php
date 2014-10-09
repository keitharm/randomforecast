<?php
class Config
{
    // Application credentials
    const CONSUMER_KEY    = "";
    const CONSUMER_SECRET = "";

    public static function getKey() {
        return Config::CONSUMER_KEY;
    }

    public static function getSecret() {
        return Config::CONSUMER_SECRET;
    }

    public static function getBase64() {
        return base64_encode(Config::CONSUMER_KEY . ":" . Config::CONSUMER_SECRET);
    }

    public static function debug() {
        return Config::DEBUG;
    }
}
?>
