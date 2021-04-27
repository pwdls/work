<?php


class getInvoce implements interfaceEvrosib
{
    private $data;
    private $result = '{"result":995,"data":""}';

    public function __construct($data)
    {
        $this->data = $data;
    }

    private static function createInvoiceQuery($data)
    {
        $query = "INSERT INTO invoce"
            . " (`GUID`,`GUIDinvoce`,`dateOfPayment`,`paid`,"
            . "`sum`,`document`,`closingDocuments`,`deletionMark`)"
            . " VALUES ("
            . "'" . $data["GUID"] . "',"
            . "'" . Gribov::GUID() . "',"
            . "'" . $data["dateOfPayment"] . "',"
            . $data["paid"] . ','
            . "'" . mt_rand(1000, 1000000) . "',"
            . "'https://https://my-sweet-cherry-pie.ru/file/invoce.docx',"
            . "'" . $data["closingDocuments"] . "',"
            . "'0'"
            . ");";
        GribovMySQL::getMySQL($query);
    }

    private static function createInvoiceQueryNew($data)
    {
        $mas = array(
            "GUID" => $data->GUID,
            "dateOfPayment" => $data->from_datetime,
            "paid" => 0,
            "closingDocuments" => '',
        );
        self::createInvoiceQuery($mas);
    }

    private static function createInvoiceQueryPaid($data)
    {
        $mas = array(
            "GUID" => $data->GUID,
            "dateOfPayment" => $data->from_datetime,
            "paid" => 1,
            "closingDocuments" => 'https://https://my-sweet-cherry-pie.ru/file/invoce.docx',
        );
        self::createInvoiceQuery($mas);
    }

    private static function createInvoiceQueryClose($data)
    {
        $mas = array(
            "GUID" => $data->GUID,
            "dateOfPayment" => $data->from_datetime,
            "paid" => 0,
            "closingDocuments" => 'https://https://my-sweet-cherry-pie.ru/file/invoce.docx',
        );
        self::createInvoiceQuery($mas);
    }

    static public function createInvoce($data)
    {
        self::createInvoiceQueryNew($data);
        self::createInvoiceQueryPaid($data);
        self::createInvoiceQueryClose($data);
    }

    private function correctData()
    {
        $this->data->inGUID = '("' . implode('","', $this->data->GUID) . '")';
        $this->data->inGUIDinvoce = '("' . implode('","', $this->data->GUIDinvoce) . '")';
    }

    private function createResultOne($re): array
    {
        return array(
            "GUID" => $re["GUID"],
            "GUIDinvoce" => $re["GUIDinvoce"],
            "number" => $re["id"],
            "date" => $re["date"],
            "dateOfPayment" => $re["dateOfPayment"],
            "paid" => $re["paid"],
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