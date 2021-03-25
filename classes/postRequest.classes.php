<?php


class postRequest implements interfaceEvrosib
{
    private $result = '{"result":995,"data":""}';
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    private function correctData()
    {
        $this->data->version = Gribov::randSTR();
        $this->data->GUID = Gribov::GUID();
        $this->data->status = 2;
        $this->data->orderPrice = mt_rand(1000, 1000000);
        $this->data->from_datetime = str_replace('T', ' ', $this->data->from_datetime);
        $this->data->avtodovoz_date = str_replace('T', ' ', $this->data->avtodovoz_date);
        $this->data->avtovivoz_date = str_replace('T', ' ', $this->data->avtovivoz_date);
    }

    private function addRequest()
    {
        $this->correctData();

        $query = "INSERT INTO request "
            . "(`GUID`, `version`, `OrderStatus`, `OrderРrice`, "
            . "`other_fio`, `GUIDPartner`, `other_tel`, `other_email`, "
            . "`from_datetime`, `from_location`, `to_location`, `containers`, "
            . "`other_longdescription`, `driver_fio`, `passport`, `driver_reg`, "
            . "`avtodovoz`, `avtodovoz_location`, `avtodovoz_date`, `avtovivoz`, "
            . "`avtovivoz_location`, `avtovivoz_date`) "
            . "VALUES "
            . "('" . $this->data->GUID . "', '" . $this->data->version . "', '" . $this->data->status . "', '" . $this->data->orderPrice . "', "
            . "('" . $this->data->other_fio . "', '" . $this->data->GUIDPartner . "', '" . $this->data->other_tel . "', '" . $this->data->other_email . "', "
            . "('" . $this->data->from_datetime . "', '" . $this->data->from_location . "', '" . $this->data->to_location . "', '" . json_encode($this->data->containers) . "', "
            . "('" . $this->data->other_longdescription . "', '" . $this->data->driver_fio . "', '" . $this->data->passport . "', '" . $this->data->driver_reg . "', "
            . "('" . $this->data->avtodovoz . "', '" . $this->data->avtodovoz_location . "', '" . $this->data->other_fio . "', '" . $this->data->other_fio . "', "
            . "('" . $this->data->other_fio . "', '" . $this->data->other_fio . "', '" . $this->data->avtodovoz_date . "', '" . $this->data->avtovivoz . "', "
            . "('" . $this->data->avtovivoz_location . "', '" . $this->data->avtovivoz_date . "');";

        GribovMySQL::getMySQL($query);
    }

    private function getRequest()
    {
        $query = 'SELECT GUID, version, DocumentId, OrderStatus, OrderРrice FROM request WHERE GUID ="' . $this->data->GUID . '";';
        $arr = GribovMySQL::getMySQL($query);
        $result = array(
            'result' => 0,
            'data' => array(
                'GUID' => $arr[0]['GUID'],
                'version' => $arr[0]['version'],
                'DocumentId' => $arr[0]['DocumentId'],
                'OrderStatus' => $arr[0]['OrderStatus'],
                'OrderPrice' => $arr[0]['OrderPrice']
            )
        );

        $this->result = json_encode($result);
    }

    private function do()
    {
        $this->addRequest();
        $this->getRequest();
    }

    public function getResult(): string
    {
        $this->do();
        return $this->result;
    }
}