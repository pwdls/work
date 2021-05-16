<?php


class getReliz implements interfaceEvrosib
{
    private $data;
    private $result = '{"result":995,"data":""}';

    public function __construct($data)
    {
        $this->data = $data;
    }

    private function createRelizQuery()
    {
        $GUIDreliz = Gribov::GUID();
        $query = "INSERT INTO reliz" .
            " (`GUID`, `documentId`, `GUIDreliz`, `containerNumber`," .
            " `containerType`, `gettingStok`, `gettingFIO`, `gettingPassport`," .
            " `gettingAvto`, `returnStok`)" .
            " VALUES (" .
            "'" . $this->data->GUID . "'," .
            "'" . $this->data->documentId . "'," .
            "'" . $GUIDreliz . "'," .
            "'" . $this->data->containerNumber . "'," .
            "'22G1'," .
            "'5'," .
            "'" . $this->data->gettingFIO . "'," .
            "'" . $this->data->gettingPassport . "'," .
            "'" . $this->data->gettingAvto . "'," .
            "'4');";
        GribovMySQL::getMySQL($query);
    }

    private function createRelizNull()
    {
        $this->data->containerNumber = '';
        $this->data->gettingFIO = '';
        $this->data->gettingPassport = '';
        $this->data->gettingAvto = '';
        $this->createRelizQuery();
    }

    private function createRelizFull()
    {
        $this->data->containerNumber = '';
        $this->data->gettingFIO = 'Сидоров Сидор Сидорович';
        $this->data->gettingPassport = '1111 121212';
        $this->data->gettingAvto = 'x124xo77';
        $this->createRelizQuery();
    }

    private function correctData()
    {
        $query = "SELECT DocumentId FROM request WHERE GUID = '" . $this->data->GUID . "';";
        $result = GribovMySQL::getMySQL($query);
        $this->data->documentId = $result[0]['DocumentId'];
    }

    public function createReliz()
    {
        $this->correctData();
        $this->createRelizNull();
        $this->createRelizFull();
    }

    private function createResultOne($re): array
    {
        return array(
            "GUID" => $re["GUID"],
            "documentId" => $re["documentId"],
            "GUIDreliz" => $re["GUIDreliz"],
            "relizId" => $re["relizId"],
            "containerNumber" => $re["containerNumber"],
            "containerType" => $re["containerType"],
            "gettingStok" => $re["gettingStok"],
            "gettingDate" => $re["gettingDate"],
            "gettingFIO" => $re["gettingFIO"],
            "gettingPassport" => $re["gettingPassport"],
            "gettingAvto" => $re["gettingAvto"],
            "returnStok" => $re["returnStok"],
            "returnDate" => $re["returnDate"],
            "returnPhone" => $re["returnPhone"],
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
        $query = "SELECT s1.* from reliz s1 "
            . " LEFT JOIN request s2"
            . " ON s1.GUID = s2.GUID"
            . " WHERE"
            . " (s2.GUIDPartner = '" . $this->data->user
            . "' AND s2.from_datetime BETWEEN '" . $this->data->dateFrom
            . "' AND '" . $this->data->dateTo
            . "') OR s2.GUID IN (" . $this->data->inGUID
            . ") OR s1.GUIDreliz IN (" . $this->data->inGUIDreliz
            . ");";
        $res = GribovMySQL::getMySQL($query);

        $this->createResult($res);
    }

    private function do()
    {
        $this->correctDataResult();
        $this->getRequest();
    }

    private function correctDataResult()
    {
        $this->data->inGUID = '("' . implode('","', $this->data->GUID) . '")';
        $this->data->inGUIDreliz = '("' . implode('","', $this->data->GUIDreliz) . '")';
    }

    public function getResultOne(): srting
    {
        $query = "SELECT * FROM reliz" .
            "WHERE GUIDreliz = '" .  $this->data->GUIDreliz ."'";
        $re = GribovMySQL::getMySQL($query);

        $result = $this->createResultOne($re[0]);
        $this->result = json_encode($result);

        return $this->result;
    }

    public function getResult(): string
    {
        $this->do();
        return $this->result;
    }
}