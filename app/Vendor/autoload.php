<?php
function mush_autoload($pClassName) {    
    $pClassName = str_replace('/', DS, $pClassName);
    $pClassName = str_replace('\\', DS, $pClassName);
    include(__DIR__ . DS . $pClassName . ".php");    
}
spl_autoload_register("mush_autoload");