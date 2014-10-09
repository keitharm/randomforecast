<?php
require_once("Config.class.php");

class Twitter
{
    const OAUTH_TOKEN     = "https://api.twitter.com/oauth2/token";

    private $bearer;

    public function Twitter() {
        $this->generateBearer();
    }

    private function generateBearer() {
        $ch = curl_init();

        $postfields = http_build_query(
            array(
                "grant_type" => "client_credentials"
            )
        );
 
        curl_setopt($ch, CURLOPT_URL, Twitter::OAUTH_TOKEN);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . Config::getBase64()));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);

        $json = json_decode(curl_exec($ch));
        curl_close($ch);

        if ($json->access_token == null) {
            $this->error($json->errors[0]);
        }

        $this->bearer = $json->access_token;
    }

    private function error($data) {
        if (is_object($data)) {
            die("Error " . $data->code . ": " . $data->message . " [" . (($data->label == null) ? "NULL" : $data->label) . "].\n");
        }
        die("Error: " . $data . "\n");
    }

    public function getBearer() { return $this->bearer; }
}

$twitter = new Twitter;
?>
