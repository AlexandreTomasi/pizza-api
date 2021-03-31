<?php

function geraResposta($pizzaria, $parecidos, $nome){
    if($pizzaria == null){throw new Exception("Funcionalidade (geraRespostaCodigo). Codigo da pizzaria está nulo ");}
    if($parecidos == null){throw new Exception("Funcionalidade (geraRespostaCodigo). Sem respostas cadastradas");}
    $resposta = "";
    if(count($parecidos) == 1){
        $temp = explode("||",$parecidos[0]["respostas_personagem_respostas"]);
        $op = rand(0,count($temp)-1);
        $resposta = $temp[$op];
        return $resposta;
    }else{// sei que tem varias respostas
        for($i=0; $i< count($parecidos); $i++){
            if($parecidos[$i]["respostas_personagem_respostas"] != ""){
                $temp = explode("||",$parecidos[$i]["respostas_personagem_respostas"]);
                $op = rand(0,count($temp)-1);
                if($resposta == ""){
                    $resposta = $temp[$op];
                }else{
                    $resposta .= "\n".$temp[$op];
                }
            }
        }
        return $resposta;          
    }
}