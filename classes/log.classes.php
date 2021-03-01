<?php


class log
{
    private $str;

    public function __construct($str)
    {
        $this->str = $str;
    }


    public function add()
    {
        if ($this->check()) {
            $log = $this->createLog();
        }
    }

    private function dump($str)
    {
        echo '<pre>';
        echo $str;
        echo '</pre>';
    }


    private function createLog()
    {
        ob_start();

        echo '<p>POST</p>';
        $this->dump($_POST);
        echo '<p>GET</p>';
        $this->dump($_GET);
        echo '<p>REQUEST</p>';
        $this->dump($this->str);

        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }

    private function createFile()
    {
        $type = filter_input(INPUT_POST, 'type');
        $data = filter_input(INPUT_POST, 'data');
        $DOCUMENT_ROOT = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
        $file = $DOCUMENT_ROOT . '/log/';
    }

    private function check()
    {
        if (!empty(filter_input(INPUT_POST, 'type')) && !empty(filter_input(INPUT_POST, 'data'))) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}