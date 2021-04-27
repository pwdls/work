<?php

class GribovMySQL
{
    private $host;
    private $db;
    private $user;
    private $pass;
    private $mysql;

    private function __construct()
    {
        $cfg =include filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/mysql--conf.php';

        $this->host = $cfg['host'];
        $this->db = $cfg['db'];
        $this->user = $cfg['user'];
        $this->pass = $cfg['pass'];

        $this->mysql = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if (!$this->mysql) {
            echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
            echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
        } else {
            $this->mysql->query('SET NAMES utf8');
        }
    }

    static public function getMySQL($mas)
    {
        Gribov::dump($mas);

        $foo = new GribovMySQL();
        if (gettype($mas) == 'array') {
            $i = 1;
            while ($i <= count($mas)) {
                $res = $foo->mysql->query($mas[$i - 1]);
                $i++;
            }
        } else {
            $res = $foo->mysql->query($mas);
        }
        $foo->mysql->close();
        if (!empty($res->num_rows) && $res->num_rows != 0) {
            while ($row = $res->fetch_array()) {
                $result[] = $row;
            }
        } else {
            $result = 'FALSE';
        }
        return $result;
    }
}