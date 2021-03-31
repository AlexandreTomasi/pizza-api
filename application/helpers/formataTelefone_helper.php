<?php

function formataTelefone($numero){
    if(strlen($numero) == 10){
        $ddd = substr($numero, 0, 2);
        $pri = substr($numero, 2, 4);
        $seg = substr($numero, 6);
        $novo = "(".$ddd.")".$pri."-".$seg;
    }else{
        $ddd = substr($numero, 0, 2);
        $pri = substr($numero, 2, 5);
        $seg = substr($numero, 7);
        $novo = "(".$ddd.")".$pri."-".$seg;
    }
    return $novo;
}
