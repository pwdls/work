<?php


class createUser implements interfaceEvrosib
{
    private $url = 'http://atg-ca.selfip.biz:48081/';
    private $data;
    private $result = '{"result":995,"data":""}';

    public function __construct($data)
    {
        $this->data = $data;
    }

    private function do(){

        $type = filter_input(INPUT_POST, 'type');

        $array = array(
            'type' => $type,
            'priority' => filter_input(INPUT_POST, '$priority'),
            'data' => filter_input(INPUT_POST, 'data'),
        );

        $this->result = Gribov::curlPost($array, $this->url);
    }

    public function getResult(): string
    {
        $this->do();
        return $this->result;
    }
}