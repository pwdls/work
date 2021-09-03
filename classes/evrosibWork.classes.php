<?php

class evrosibWork
{
    private $type;
    private $priority;
    private $data;
    private $dataJSON;
    private $result = '{"result":990,"data":""}';

    public function __construct($type, $priority, $data)
    {
        $this->data = json_decode($data);
       // $this->data = $this->data->data;
        $this->dataJSON = $data;
        $this->priority = $priority;
        $this->type = $type;
    }

    private function execAsync()
    {
            $this->execAsyncStandard();
    }

    private function execAsyncCurl()
    {
        $array = array(
            'type' => filter_input(INPUT_POST, 'type'),
            'priority' => filter_input(INPUT_POST, '$priority'),
            'data' => filter_input(INPUT_POST, 'data'),
        );

        $this->result = Gribov::curlPost($array, $this->url);
    }

    private function execAsyncStandard()
    {
        $guid = Gribov::GUID();
        $query = "insert into task (`GUID`, `data`) VALUES ('"
            . $guid
            . "', '"
            . $this->dataJSON
            . "');";
        GribovMySQL::getMySQL($query);
        $this->result = '{"result":0,"data":{"GUID":"' . $guid . '"}}';
    }

    private function getFileData($pathToFile)
    {
        if (file_exists($pathToFile)) {
            return file_get_contents($pathToFile);
        } else {
            return '{"result":997,"data":""}';
        }
    }

    private function execSync()
    {

        if (class_exists($this->data->request)) {
            $do = new $this->data->request($this->data->data);
            $this->result = $do->getResult();
        } else {
            $pathToFile = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/file/' . $this->data->request . '.json';
            $this->result = $this->getFileData($pathToFile);
        }
    }

    private function getResult()
    {

            $query = "SELECT * FROM task WHERE GUID = '" . $this->data->data->GUID . "';";
            $result = GribovMySQL::getMySQL($query);
            $json = json_decode($result[0]['data']);

            // $this->type = $result['request'];
            $this->dataJSON = json_encode($json);
            $this->data = $json;

        $this->execSync();
    }

    public function result(): string
    {
        switch ($this->type) {
            case 'execAsync':
                $this->execAsync();
                break;
            case 'getResult':
                $this->getResult();
                break;
            case 'execSync':
                $this->execSync();
                break;
            default:
                $this->result = '{"result":998,"data":""}';
        }
        return $this->result;
    }
}