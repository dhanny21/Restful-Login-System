<?php

function Reply($num_code, $msg){
    http_response_code($num_code);
    echo $msg;
    exit();
}

?>