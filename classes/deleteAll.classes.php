<?php


class deleteAll implements interfaceEvrosib
{
    public function __construct($data)
    {
    }

    public function getResult(): string
    {
        $mas = array(
            '0' => "DELETE from request WHERE true;",
            '1' => "DELETE from service WHERE true;",
            '2' => "DELETE from invoce WHERE true;",
            '3' => "DELETE from task WHERE true;",
        );
        GribovMySQL::getMySQL($mas);
        return '';
    }
}