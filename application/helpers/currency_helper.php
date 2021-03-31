<?php

function numeroEmReais($numero){
    return "R$ " . number_format($numero,2, ",",".");
}