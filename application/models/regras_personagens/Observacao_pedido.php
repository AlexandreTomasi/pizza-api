<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Observacao_pedido
 *
 * @author 033234581
 */
class Observacao_pedido extends CI_Model{
    //put your code here
    public function observacaoPedidoUP($pizzaria, $idCliente, $personagem, $nome){
        $this->load->model("modelos/UtilitarioMensagemFacebook");
        $this->load->model("ia/Personagem_respostas_model");
        $this->load->model("regras_personagens/Utilitario");
        $chave = "OBSERVACAO PEDIDO";

        $parecidos = $this->Personagem_respostas_model->buscaPorDescricaoParecida($personagem, $chave);
        if($parecidos == null){throw new Exception("Funcionalidade (geraResposta). Sem respostas cadastradas");}
        $resposta = "";
        $pergunta = "";
        if(count($parecidos) == 1){
            if($parecidos[0]["respostas_personagem_respostas"] != ""){
                $temp = explode("||",$parecidos[0]["respostas_personagem_respostas"]);
                $op = rand(0,count($temp)-1);
                if($resposta == ""){
                    $resposta = $temp[$op];
                }else{
                    $resposta .= "\n".$temp[$op];
                }
                $resposta = $this->Utilitario->preenchePalavrasReservadas($pizzaria, $idCliente, $resposta, $nome);
                $botoes=array();
                $botoes[] = array('title' => "Não",'block_names' => array("Fluxo 104"));
                return $this->UtilitarioMensagemFacebook->gerarBotaoPadrao($resposta, "", $botoes);
            }
        }else{
            if($parecidos[0]["respostas_personagem_respostas"] != ""){
                $temp = explode("||",$parecidos[0]["respostas_personagem_respostas"]);
                $op = rand(0,count($temp)-1);
                if($resposta == ""){
                    $resposta = $temp[$op];
                }else{
                    $resposta .= "\n".$temp[$op];
                }
            }
            for($i=1; $i< count($parecidos); $i++){
                if($parecidos[$i]["respostas_personagem_respostas"] != ""){
                    $temp = explode("||",$parecidos[$i]["respostas_personagem_respostas"]);
                    $op = rand(0,count($temp)-1);
                    if($pergunta == ""){
                        $pergunta = $temp[$op];
                    }else{
                        $pergunta .= "\n".$temp[$op];
                    }
                }
            }
            $resposta = $this->Utilitario->preenchePalavrasReservadas($pizzaria, $idCliente, $resposta, $nome);
            $botoes=array();
            $botoes[] = array("type" => "show_block",'block_names' => array("Fluxo 104"),'title' => "Não");  
            return $this->UtilitarioMensagemFacebook->gerarBotaoPadrao($resposta, array("PerguntaObservacao" => $pergunta), $botoes);
        }
        throw new Exception("Não foi possivel gerar observacaoPedidoUP para o cliente");
    }
    
}
