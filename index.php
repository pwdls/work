<?php
$type = filter_input(INPUT_POST, 'type');
$priority = filter_input(INPUT_POST, '$priority');
$data = filter_input(INPUT_POST, 'data');
$get = filter_input(INPUT_GET, 'grte');
$DOCUMENT_ROOT = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');

function dump($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

function GUID()
{
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }
    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

if (!empty($type) && !empty($data)) {

    $foo = json_decode($data);
    //dump($foo);

    if (!empty($foo->request) || $type == 'getResult') {

        if ($type == 'execAsync') {
            $guid = GUID();
            $pathToFile = $DOCUMENT_ROOT . '/file/' . $foo->request . '.json';
            $pathToFileNew = $DOCUMENT_ROOT . '/file/hlam/' . $guid . '.json';

            if (!copy($pathToFile, $pathToFileNew)) {
                echo '{"result":996,"data":""}';
                exit();
            } else {
                echo '{"result":0,"data":{"GUID":"' . $guid . '"}}';
            }
        } elseif ($type == 'getResult' || $type == 'execSync') {

            if ($type == 'getResult') {
                $pathToFile = $DOCUMENT_ROOT . '/file/hlam/' . $foo->data->GUID . '.json';
            } else {
                $pathToFile = $DOCUMENT_ROOT . '/file/' . $foo->request . '.json';
            }
            //dump($pathToFile);

            if (file_exists($pathToFile)) {
                $GetContentFile = file_get_contents($pathToFile);
                echo $GetContentFile;
            } else {
                echo '{"result":997,"data":""}';
            }
        } else {
            echo '{"result":998,"data":""}';
        }
    }
} elseif ($get == 'qwerdawe') {
    echo "
        <form method='post'>
            <p><input type='text' name='type'></p>
            <p><input type='text' name='data'></p>
            <p><input type='submit'></p>
        </form>";
} else {
    echo '{"result":999,"data":""}';
}