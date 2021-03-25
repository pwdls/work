<?php

class GribovMySQL
{
    private $host;
    private $db;
    private $user;
    private $pass;
    private $mysql;

    private function __construct($id = 0)
    {
        $this->host = 'localhost';
        $this->db = 'evrosib';
        $this->user = 'root';
        $this->pass = '';

        $this->mysql = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if (!$this->mysql) {
            echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
            echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
        } else {
            $this->mysql->query('SET NAMES utf8');
        }
    }

    static public function getMySQL($mas, $id = 0)
    {
        $foo = new GribovMySQL($id);
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