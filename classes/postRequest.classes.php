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
    }

    private function createService($marker = 0)
    {
        $mas = array();
        if ($marker == 1) {
            $mas[] = 'INSERT INTO service'
                . '(`requestGUID`, `serviceID`, `required`, `active`, `price`)'
                . ' VALUES ('
                . '"' . $this->data->GUID . '", '
                . '"' . 1 . '", '
                . '"' . 0 . '", '
                . '"' . 1 . '", '
                . '"' . 10000 . '" '
                . ');';
        } elseif($marker == 2){
            $mas[] = 'INSERT INTO service'
                . '(`requestGUID`, `serviceID`, `required`, `active`, `price`)'
                . ' VALUES ('
                . '"' . $this->data->GUID . '", '
                . '"' . 2 . '", '
                . '"' . 0 . '", '
                . '"' . 1 . '", '
                . '"' . 20000 . '" '
                . ');';
        }
        else {
            for ($i = 3; $i <= 15; $i++) {
                $required = mt_rand(0, 1);
                $price = mt_rand(1, 100000);
                $mas[] = 'INSERT INTO service'
                    . '(`requestGUID`, `serviceID`, `required`, `active`, `price`)'
                    . ' VALUES ('
                    . '"' . $this->data->GUID . '", '
                    . '"' . $i . '", '
                    . '"' . $required . '", '
                    . '"' . $required . '", '
                    . '"' . $price . '" '
                    . ');';
            }
        }
        GribovMySQL::getMySQL($mas);
    }

    private function addRequest()
    {
        $this->correctData();

        if($this->data->avtovivoz){
            $this->createService(1);
        }

        if($this->data->avtodovoz){
            $this->createService(2);
        }

        $query = "INSERT INTO request "
            . "(`GUID`, `version`, `OrderStatus`, `OrderРrice`, "
            . "`other_fio`, `GUIDPartner`, `other_tel`, `other_email`, "
            . "`from_datetime`, `from_location`, `to_location`, `containers`, "
            . "`other_longdescription`, `driver_fio`, `passport`, `driver_reg`, "
            . "`avtodovoz`, `avtodovoz_location`, `avtodovoz_date`, `avtovivoz`, "
            . "`avtovivoz_location`, `avtovivoz_date`) "
            . "VALUES "
            . "('" . $this->data->GUID . "', '" . $this->data->version . "', '" . $this->data->status . "', '" . $this->data->orderPrice . "', "
            . "'" . $this->data->other_fio . "', '" . $this->data->GUIDPartner . "', '" . $this->data->other_tel . "', '" . $this->data->other_email . "', "
            . "'" . $this->data->from_datetime . "', '" . $this->data->from_location . "', '" . $this->data->to_location . "', '" . json_encode($this->data->containers) . "', "
            . "'" . $this->data->other_longdescription . "', '" . $this->data->driver_fio . "', '" . $this->data->passport . "', '" . $this->data->driver_reg . "', "
            . "'" . $this->data->avtodovoz . "', '" . $this->data->avtodovoz_location . "', '" . $this->data->avtodovoz_date . "', '" . $this->data->avtovivoz . "', "
            . "'" . $this->data->avtovivoz_location . "', '" . $this->data->avtovivoz_date . "');";

        GribovMySQL::getMySQL($query);
    }

    private function getRequest()
    {
        $query = 'SELECT GUID, version, DocumentId, OrderStatus, OrderРrice FROM request WHERE GUID ="' . $this->data->GUID . '";';
        $arr = GribovMySQL::getMySQL($query);
        $result = array(
            'result' => 0,
            'data' => array(
                'GUID' => $arr[0][0],
                'version' => $arr[0][1],
                'DocumentId' => $arr[0][2],
                'OrderStatus' => $arr[0][3],
                'OrderPrice' => $arr[0][4]
            )
        );

        $this->result = json_encode($result);
    }

    private function do()
    {
        $this->addRequest();
        $this->getRequest();
        $this->createService();
        getInvoce::createInvoce($this->data);
    }

    public function getResult(): string
    {
        $this->do();
        return $this->result;
    }
}