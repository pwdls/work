<?php


class Gribov
{
    static public function GUID(): string
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    static public function dump($data, $e = 0)
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        if ($e) {
            exit();
        }
    }

    static public function log($page, $type = 'NULL')
    {
        $dir = filter_input(INPUT_SERVER, "DOCUMENT_ROOT")
            . '/log/' . date("Y-m-d")
            . '/' . date("H");
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        $filename = $dir . '/' . date("m-i-s") . '---' . $type . '.html';

        file_put_contents($filename, $page);
    }

    static public function randSTR()
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($permitted_chars), 0, 10);
    }

    static public function curlPost($array, $url){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $array);

        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
}