<?php
require_once("Config.class.php");
require_once("codebird/codebird.php");

class Twitter
{
    public static function post($content, $imageurl, $in_reply_to = null) {
        \Codebird\Codebird::setConsumerKey(Config::CONSUMER_KEY, Config::CONSUMER_SECRET);
        $cb = \Codebird\Codebird::getInstance();
        $cb->setToken(Config::ACCESS_TOKEN, Config::ACCESS_SECRET);

        // Post
        $params = array(
            'status' => $content,
            'media[]' => $imageurl
        );
        if ($in_reply_to != null) {
            $params['in_reply_to_status_id'] = $in_reply_to;
        }

        $cb->statuses_updateWithMedia($params);
    }

    public static function personalReplies() {
        \Codebird\Codebird::setConsumerKey(Config::CONSUMER_KEY, Config::CONSUMER_SECRET);
        $cb = \Codebird\Codebird::getInstance();
        $cb->setToken(Config::ACCESS_TOKEN, Config::ACCESS_SECRET);

        $mentions = $cb->statuses_mentionsTimeline();
        if ($mentions == null) {
            return;
        }
        foreach ($mentions as $mention) {
            // Within last minute
            if (time() - strtotime($mention->created_at) <= 60) {
                $replyto[] = $mention;
            }
        }

        foreach ($replyto as $request) {
            $location = trim(substr($request->text, 15));
            $weather = new Weather($location);
            if (!$weather->isValid()) {
                Twitter::post("@" . $request->user->screen_name . " Sorry, but I did not understand the location you tweeted me :(", "http://l.yimg.com/a/i/us/we/52/3200.gif", $request->id);
            } else {
                Twitter::post("@" . $request->user->screen_name . " " . $weather->generateReport() . "\n#personalrandomforecast", $weather->getImage(), $request->id);
            }
        }
    }
}
?>
