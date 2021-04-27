<?php

class updateRequest implements interfaceEvrosib
{
    private $data;
    private $result = '{"result":995,"data":""}';

    public function __construct($data)
    {
        $this->data = $data;
    }

    private function checkingVersionObject(): int
    {
        $query = "SELECT version FROM request WHERE GUID='" . $this->data->GUID . "';";
        $res = GribovMySQL::getMySQL($query);

        if ($res[0]['version'] == $this->data->version) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function error($id)
    {
        $this->result = '{"result":"' . $id . '","data":""}';
    }

    private function update()
    {

        $avtovivoz_date = ($this->data->avtovivoz == '0') ? 'NULL' : '"' . $this->data->avtovivoz_date . '"';
        $avtodovoz_date = ($this->data->avtodovoz == '0') ? 'NULL' : '"' . $this->data->avtodovoz_date . '"';

        $mas = array();
        $mas[] = 'UPDATE request SET'
            . ' avtodovoz = "' . $this->data->avtodovoz . '",'
            . ' avtodovoz_location = "' . $this->data->avtodovoz_location . '",'
            . ' avtodovoz_date = ' . $avtodovoz_date . ','
            . ' avtovivoz = "' . $this->data->avtovivoz . '",'
            . ' avtovivoz_location = "' . $this->data->avtovivoz_location . '",'
            . ' avtovivoz_date = ' . $avtovivoz_date . ','
            . ' OrderStatus = "' . $this->data->OrderStatus . '"'
            . ' WHERE GUID="' . $this->data->GUID . '";';
        $mas[] = 'UPDATE service SET'
            . ' active = 1'
            . ' WHERE requestGUID="' . $this->data->GUID
            . '" AND serviceID IN '
            . $this->data->inServicesActive
            . ';';
        GribovMySQL::getMySQL($mas);
    }

    private function checkingErrors(): int
    {
        return ($this->checkingVersionObject()) ? 0 : 201;
    }

    private function correctData()
    {
        $this->data->inServicesActive = '("' . implode('","', $this->data->servicesActive) . '")';
    }

    private function getRequest()
    {
        $query = "SELECT * from request WHERE GUID = '" . $this->data->GUID . "';";
        $res = GribovMySQL::getMySQL($query);
        $result = array(
            'result' => 0,
            'data' => getRequestOne::getResult($res[0])
        );
        $this->result = json_encode($result);
    }

    private function do()
    {
        $error = $this->checkingErrors();
        if ($error == 0) {
            $this->correctData();
            $this->update();
            $this->getRequest();
        } else {
            $this->error($error);
        }
    }

    public function getResult(): string
    {
        $this->do();
        return $this->result;
    }
}