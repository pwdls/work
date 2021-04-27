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

    private function createService()
    {

        $mas = array();
            for ($i = 3; $i <= 15; $i++) {
                switch ($i){
                    case 1:
                        $required = 1;
                        $active = 1;
                        break;
                    case 2:
                        $required = 0;
                        $active = $this->data->avtodovoz;
                        break;
                    case 15:
                    case 14:
                        $required = 0;
                        $active = 1;
                        break;
                    default:
                        $required = mt_rand(0, 1);
                        $active = $required ? 1 : mt_rand(0, 1);
                        break;
                }
                $price = mt_rand(1, 100000);
                $mas[] = 'INSERT INTO service'
                    . '(`requestGUID`, `serviceID`, `required`, `active`, `price`)'
                    . ' VALUES ('
                    . '"' . $this->data->GUID . '", '
                    . '"' . $i . '", '
                    . '"' . $required . '", '
                    . '"' . $active . '", '
                    . '"' . $price . '" '
                    . ');';
            }

        GribovMySQL::getMySQL($mas);
    }

    private function addRequest()
    {
        $this->correctData();

        $avtovivoz_date = ($this->data->avtovivoz_date == '') ? 'NULL' : "'" . $this->data->avtovivoz_date . "'";
        $avtodovoz_date = ($this->data->avtovivoz_date == '') ? 'NULL' : "'" . $this->data->avtodovoz_date . "'";

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
            . "'" . $this->data->avtodovoz . "', '" . $this->data->avtodovoz_location . "', " . $avtodovoz_date . ", '" . $this->data->avtovivoz . "', "
            . "'" . $this->data->avtovivoz_location . "', " . $avtovivoz_date . ");";

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