<?php


class updateReliz implements interfaceEvrosib
{
    private $data;
    private $result = '{"result":990,"data":""}';

    public function __construct($data)
    {
        $this->data = $data;
    }

    function do()
    {
        $query = "UPDATE reliz SET containerNumber='" . $this->data->containerNumber
            . "', gettingFIO = '" . $this->data->containerNumber . "', " .
            "gettingPassport = '" . $this->data->gettingPassport . "', " .
            "gettingAvto = '" . $this->data->gettingAvto . "' " .
            " WHERE GUIDreliz='" . $this->data->GUIDreliz . "';";
        GribovMySQL::getMySQL($query);

        $this->result = '{"result":0,"data":""}';
    }

    public function getResult(): string
    {
        $this->do();
        return $this->result;
    }
}