<?php
class Weather
{
    public $data;

    public $raw_json;
    public $raw_object;

    public $forecast;
    public $condition;

    public $location;
    public $image;
    public $xtraCondition;
    public $temperature;

    public function Weather($data) {
        $this->data = $data;
        $this->getRawData();
        $this->analyzeData();
        $this->analyzeForecast();
    }

    public function getRawData() {
        $query            = urlencode("select item.condition.text from weather.forecast where woeid in (select woeid from geo.places(1) where text=" . $this->data . ")");
        $url              = 'https://query.yahooapis.com/v1/public/yql?q=' . $query . '&format=json&diagnostics=true&env=store://datatables.org/alltableswithkeys';
        $json             = @file_get_contents($url);

        // Check for invalid data
        if ($json == false) {
            die("Error, invalid location.\n");
        }
        $this->raw_json   = $json;

        $decode           = json_decode($json);
        $this->raw_object = $decode;
    }

    public function analyzeData() {
        $this->forecast  = $this->raw_object->query->diagnostics->url[1]->content;
        $this->condition = $this->raw_object->query->results->channel->item->condition->text;
    }

    public function analyzeForecast() {
        #file_get_contents($this->forecast);
        $xml = simplexml_load_file($this->forecast, "SimpleXMLElement", LIBXML_NOCDATA);

        $this->location      = substr($xml->channel->title, (strpos($xml->channel->title, "-")+2));
        $this->image         = $this->extractData($xml->channel->item->description, "<img src=\"", "\"/>");
        $data                = $xml->channel->item->description;
        $ex                  = explode("\n", $data);
        $this->xtraCondition = substr($ex[3], 0, -6);
        $this->temperature   = substr($this->xtraCondition, (strpos($this->xtraCondition, ",")+2));
    }

    public function extractData($data, $search, $ending, $specific = -1) {
        $len = strlen($data);
        $matches = $this->findall($search, $data);
        $found = array();
        foreach ($matches as $val) {
            $bad = false;
            $offset = 0;
            $val += strlen($search);
            while (substr($data, $val+$offset, strlen($ending)) != $ending) {
                $offset++;
                // If we are outside of the range of the string, there is no ending match.
                if ($offset > $len) {
                    $bad = true;
                    break;
                }
            }
            if (!$bad) {
                $found[] = substr($data, $val, $offset);
            }
        }
        if ($found == false) {
            return false;
        }

        if ($specific == -1) {
            if (count($found) == 1) {
                return $found[0];
            }
            return $found;
        }
        return $found[$specific-1];
    }

    public function findall($needle, $haystack) {
        $pos       = 0;
        $len       = strlen($haystack);
        $searchlen = strlen($needle);
        $results   = array();

        $data = $haystack;
        while (1) {
            $occurance = strpos($data, $needle);
            if ($occurance === false) {
                return $results;
            } else {
                $pos += $occurance+$searchlen;
                $results[] = $pos-$searchlen;
                $data = substr($haystack, ($pos));
            }
        }
    }

    public function generateReport() {
$report = <<<REPORT
It is currently {$this->getTemperature()} and {$this->getCondition()} in {$this->getLocation()}.
REPORT;
        return $report;
    }

    public function getData() { return $this->data; }
    public function getForecast() { return $this->forecast; }
    public function getCondition() { return $this->condition; }
    public function getLocation() { return $this->location; }
    public function getImage() { return $this->image; }
    public function getXtraCondition() { return $this->xtraCondition; }
    public function getTemperature() { return $this->temperature; }
}
?>
