<?php
date_default_timezone_set("Europe/Moscow");
header('Content-type: text/html; charset=utf-8');

$key = filter_input(INPUT_GET, 'asdfeafe');
if($key == 'r3sqeff432'){

    $url = (filter_input(INPUT_GET, 'dir')) ? filter_input(INPUT_GET, 'dir').'/' : '';
    $urlFull = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')
        . '/log/'
        . $url;
    $result = array(
        'dir' => array(),
        'file' => array(),
    );
        $dir = scandir($urlFull);
        foreach ($dir as $d){
            $foo = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . 'log/' . $url  . $d . '/';
            if(is_dir($foo) && $d != '.' && $d != '..'){
                $result['dir'][$d] = '?asdfeafe=r3sqeff432&dir=' . $url . $d;
            } elseif($d != '.' && $d != '..'){
                $result['file'][$d] = '/log/' . $url . $d;
            }
        }

    foreach ($result['dir'] as $key => $item) {
            echo '<p><a href="' . $item . '">' . $key . '</a></p>';
        }
    foreach ($result['file'] as $key => $item) {
        echo '<p><a target="_blank" href="' . $item . '">' . $key . '</a></p>';
    }

    echo '<p></p><a href="/log.php?asdfeafe=r3sqeff432">ГЛАВНАЯ</a>';
}
