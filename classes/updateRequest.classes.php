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
        $query = 'SELECT version FROM request WHERE GUID = "' . $this->data->GUID . '";';
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
        $mas = array();
        $mas[] = 'UPDATE request SET'
            . ' avtodovoz = "' . $this->data->avtodovoz . '",'
            . ' avtodovoz_location = "' . $this->data->avtodovoz_location . '",'
            . ' avtodovoz_date = "' . $this->data->avtodovoz_date . '",'
            . ' avtovivoz = "' . $this->data->avtovivoz . '",'
            . ' avtovivoz_location = "' . $this->data->avtovivoz_location . '",'
            . ' avtovivoz_date = "' . $this->data->avtovivoz_date . '",'
            . ' OrderStatus = "' . $this->data->OrderStatus . '",'
            . ' WHERE GUID="' . $this->data->GUID . '";';
        $mas[] = 'UPDATE service SET'
            . ''
            . ' WHERE GUID="' . $this->data->GUID
            . '" AND serviceID IN '
            . $this->data->inServicesActive
            . ';';
        GribovMySQL::getMySQL($mas);
    }

    private function checkingErrors(): int
    {
        $result = 0;
        $result = ($this->checkingVersionObject()) ? $result : 201;
        return $result;
    }

    private function correctData()
    {
        $this->data->inServicesActive = '("' . implode('","', $this->data->servicesActive) . '")';
    }

    private function getRequest()
    {
        $query = 'SELECT * from request WHERE GUID = "' . $this->data->GUID . '";';
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