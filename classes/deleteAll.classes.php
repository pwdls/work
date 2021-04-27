<?php


class deleteAll  implements interfaceEvrosib
{
public function __construct($data)
{
}

public function getResult(): string
{
    $mas = array(
        '0' => "DELETE from request WHERE 1=1;",
        '1' => "DELETE from service WHERE 1=1;",
        '2' => "DELETE from invoce WHERE 1=1;",
        '3' => "DELETE from task WHERE 1=1;",
    );
GribovMySQL::getMySQL($mas);
}
}