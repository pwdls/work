<?php


class updateInvoce implements interfaceEvrosib
{
    private $data;
    private $result = '{"result":990,"data":""}';

    public function __construct($data)
    {
        $this->data = $data;
    }

    function do()
    {
        $query = "UPDATE invoce SET status=" . $this->data->status . " WHERE GUIDinvoce='" . $this->data->GUIDinvoce . "';";
        GribovMySQL::getMySQL($query);

        $this->result = '{"result":0,"data":""}';
    }

    public function getResult(): string
    {
        $this->do();
        return $this->result;
    }
}