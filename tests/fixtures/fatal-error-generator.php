<?php

ini_set('memory_limit', '1');

$a = ['megaarray'];
while(true){
    $a[] = $a;
}