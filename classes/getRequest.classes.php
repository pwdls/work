<?php


class getRequest implements interfaceEvrosib
{
    private $result = '{"result":995,"data":""}';
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    private function correctData()
    {
        $this->data->inGUID = '("' . implode('","', $this->data->GUID) . '")';
    }

    private function createResult($res)
    {
        $result = array(
            'result' => 0,
            'data' => array()
        );
        foreach ($res as $re) {
            $result['data'][] = getRequestOne::getResult($re);
        }
        $this->result = json_encode($result);
    }

    private function getRequest()
    {
        $query = "SELECT * FROM request WHERE "
            . " (GUIDPartner = '" . $this->data->user
            . "' AND from_datetime BETWEEN '" . $this->data->dateFrom
            . "' AND '" . $this->data->dateTo
            . "') OR GUID IN (" . $this->data->inGUID
            . ");";
        $res = GribovMySQL::getMySQL($query);

        $this->createResult($res);
    }

    private function do()
    {
        $this->correctData();
        $this->getRequest();
    }

    public function getResult(): string
    {
        $this->do();
        return $this->result;
    }
}