<?php

class Requester {
    public $ch;

    function __construct() {
        $this->ch = curl_init();
    }

    function GET($url, $cookie) {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($this->ch, CURLOPT_HEADER, 1);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, ["Cookie: {$cookie}"]);
        try {
            return curl_exec($this->ch);
        } finally {
            curl_reset($this->ch);
        }
    }

    function POST($url, $postdata, $headers) {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_HEADER, 1);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
        try {
            return curl_exec($this->ch);
        } finally {
            curl_reset($this->ch);
        }
    }
}

?>