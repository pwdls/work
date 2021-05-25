<?php

class createUser implements interfaceEvrosib
{
    private $url = 'http://atg-ca.selfip.biz:48081/send';
    private $data;
    private $result = '{"result":995,"data":""}';

    public function __construct($data)
    {
        $this->data = $data;
    }

    private function do(){
        $array = array(
            'type' => 'execSync',
            'priority' => '1',
            'data' => '{"request":"createUser","data":{"FIO": "Иванов Иван Иванович","Email": "asfdasdf@mail.ru","Phone": "89465466554","type": "Юр. лицо","INN": "7721546864","KPP": "507401001","OGRN": ""}}',
        );

        $this->result = Gribov::curlPost($array, $this->url);
    }

    public function getResult(): string
    {
        $this->do();
        return $this->result;
    }
}