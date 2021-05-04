<?php


class getInvoce implements interfaceEvrosib
{
    private $data;
    private $result = '{"result":995,"data":""}';

    public function __construct($data)
    {
        $this->data = $data;
    }

    private function createInvoiceQuery($data)
    {
        $GUID = Gribov::GUID();
        $query = "INSERT INTO invoce"
            . " (`GUID`,`GUIDinvoce`,`dateOfPayment`,`base`,"
            . "`sum`,`document`,`closingDocuments`,`deletionMark`, `status`)"
            . " VALUES ("
            . "'" . $data["GUID"] . "',"
            . "'" . $GUID . "',"
            . "'" . $data["dateOfPayment"] . "',"
            . $data["base"] . ','
            . "'" . mt_rand(1000, 1000000) . "',"
            . "'https://my-sweet-cherry-pie.ru/file/invoce.docx',"
            . "'https://my-sweet-cherry-pie.ru/file/invoce.docx',"
            . "'0',"
            . "'" . $data["status"] . "'"
            . ");";
        GribovMySQL::getMySQL($query);
        $this->invoiceService($data["GUID"], $GUID, $data['type']);
    }

    private function invoiceService($GUID, $GUIDService, $type){
        switch ($type){
            case 1:
                $query = "SELECT serviceID FROM service WHERE requestGUID = '" . $GUID . "' AND required = 1;";
                $foo = GribovMySQL::getMySQL($query);
                $mas = array();
                foreach ($foo as $item) {
                    $mas[] = "INSERT INTO invoce_service (`idInvoce`, `idService`) VALUES ('" . $GUIDService . "', '" . $item['serviceID'] . "');";
                }
                GribovMySQL::getMySQL($mas);
                break;
            case 13:
                $query = "INSERT INTO invoce_service (`idInvoce`, `idService`) VALUES ('" . $GUIDService . "', 13);";
                GribovMySQL::getMySQL($query);
                break;
            case 14:
                $query = "INSERT INTO invoce_service (`idInvoce`, `idService`) VALUES ('" . $GUIDService . "', 14);";
                GribovMySQL::getMySQL($query);
                break;
            case 15:
                $query = "INSERT INTO invoce_service (`idInvoce`, `idService`) VALUES ('" . $GUIDService . "', 15);";
                GribovMySQL::getMySQL($query);
                break;
        }

    }

    private function createInvoiceQuery14()
    {
        $mas = array(
            "GUID" => $this->data->GUID,
            "dateOfPayment" => $this->data->from_datetime,
            "base" => 0,
            "closingDocuments" => '',
            "status" => 4,
            "type" => 14,
        );
        $this->createInvoiceQuery($mas);
    }

    private function createInvoiceQuery15()
    {
        $mas = array(
            "GUID" => $this->data->GUID,
            "dateOfPayment" => $this->data->from_datetime,
            "base" => 0,
            "closingDocuments" => '/file/invoce.docx',
            "status" => 4,
            "type" => 15,
        );
        $this->createInvoiceQuery($mas);
    }

    private function createInvoiceQuery13()
    {
        $mas = array(
            "GUID" => $this->data->GUID,
            "dateOfPayment" => $this->data->from_datetime,
            "base" => 0,
            "closingDocuments" => '',
            "status" => 1,
            "type" => 13,
        );
        $this->createInvoiceQuery($mas);
    }

    private function createInvoiceQueryBase()
    {
        $mas = array(
            "GUID" => $this->data->GUID,
            "dateOfPayment" => $this->data->from_datetime,
            "base" => 1,
            "closingDocuments" => '',
            "status" => 1,
            "type" => 1,
        );
        $this->createInvoiceQuery($mas);
    }

    public function createInvoce()
    {
        $this->createInvoiceQueryBase();
        $this->createInvoiceQuery13();
        $this->createInvoiceQuery14();
        $this->createInvoiceQuery15();
    }

    private function correctData()
    {
        $this->data->inGUID = '("' . implode('","', $this->data->GUID) . '")';
        $this->data->inGUIDinvoce = '("' . implode('","', $this->data->GUIDinvoce) . '")';
    }

    private function getServiceList($GUID){
        $result = array();
        $query = "SELECT idService from invoce_service WHERE idInvoce = '" . $GUID . "';";
        $mas = GribovMySQL::getMySQL($query);
        foreach ($mas as $value){
            $result[] = $value['idService'];
        }
        return $result;
    }

    private function createResultOne($re): array
    {
        return array(
            "GUID" => $re["GUID"],
            "GUIDinvoce" => $re["GUIDinvoce"],
            "number" => $re["id"],
            "name" => $re["name"],
            "status" => $re["status"],
            "date" => $re["date"],
            "serviceList" => $this->getServiceList($re["GUIDinvoce"]),
            "dateOfPayment" => $re["dateOfPayment"],
            "base" => $re["paid"],
            "sum" => $re["sum"],
            "document" => $re["document"],
            "closingDocuments" => $re["closingDocuments"],
            "deletionMark" => $re["deletionMark"],
        );
    }

    private function createResult($res): void
    {
        $result = array(
            'result' => 0,
            'data' => array()
        );
        foreach ($res as $re) {
            $result['data'][] = $this->createResultOne($re);
        }
        $this->result = json_encode($result);
    }

    private function getRequest()
    {
        $query = "SELECT s1.* from invoce s1"
            . " LEFT JOIN request s2"
            . " ON s1.GUID = s2.GUID"
            . " WHERE"
            . " (s2.GUIDPartner = '" . $this->data->user
            . "' AND s2.from_datetime BETWEEN '" . $this->data->dateFrom
            . "' AND '" . $this->data->dateTo
            . "') OR s2.GUID IN (" . $this->data->inGUID
            . ") OR s1.GUIDinvoce IN (" . $this->data->inGUIDinvoce
            . ");";
        $res = GribovMySQL::getMySQL($query);

        $this->createResult($res);
    }

    function do()
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