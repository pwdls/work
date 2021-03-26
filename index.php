<?php
header("Content-Type: text/html; charset=utf-8");
$error = 0;

ini_set('display_errors', $error);
ini_set('display_startup_errors', $error);
error_reporting(E_ALL);

spl_autoload_register(function ($class_name) {
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/classes/' . $class_name . '.classes.php';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/classes/' . $class_name . '.interface.php';
});

$type = filter_input(INPUT_POST, 'type');
$priority = filter_input(INPUT_POST, '$priority');
$data = filter_input(INPUT_POST, 'data');
$get = filter_input(INPUT_GET, 'grte');

if (!empty($type) && !empty($data)) {

    $do = new evrosibWork($type, $priority, $data);
    $result = $do->result();

} elseif ($get == 'qwerdawe') {
    $result = "
        <form method='post'>
            <p><input type='text' name='type'></p>
            <p><input type='text' name='data'></p>
            <p><input type='submit'></p>
        </form>";
} else {
    $result = '{"result":999,"data":""}';
}

echo $result;