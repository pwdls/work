<?php
$type = filter_input(POST, '$type');
$priority = filter_input(POST, '$type');
$data = filter_input(POST, 'data');
$get = filter_input(GET, 'grte');
$DOCUMENT_ROOT = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');

function dump($data){
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

if(!empty($type) && !empty($data)){
    $foo = json_decode($data);
    dump($foo);

    if(!empty($foo['request'])){

        $pathToFile = DOCUMENT_ROOT . '/' . $foo['request'] . '.json';

        if (file_exists($pathToFile)) {
            $GetContentFile = file_get_contents($pathToFile);
            echo $GetContentFile;
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