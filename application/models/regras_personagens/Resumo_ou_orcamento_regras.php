<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Resumo_ou_orcamento_pedido
 *
 * @author 033234581
 */
class Resumo_ou_orcamento_regras extends CI_Model{
    //put your code here
    //ultimo pedido
    public function resumoPedidoUP($pizzaria, $idCliente, $personagem, $nome){
        $this->load->model("modelos/UtilitarioMensagemFacebook");
        $this->load->model("ia/Personagem_respostas_model");
        $this->load->model("regras_personagens/Utilitario");
        $chave = "RESUMO PEDIDO ÚLTIMO PEDIDO";

        $parecidos = $this->Personagem_respostas_model->buscaPorDescricaoParecida($personagem, $chave);
        $resposta = $this->geraResposta($pizzaria, $parecidos, $nome);
        
        $resposta = $this->Utilitario->preenchePalavrasReservadas($pizzaria, $idCliente, $resposta, $nome);
        
        $botoes=array();
        $botoes[] = array('title' => "Sim",'block_names' => array("Fluxo 102"));
        $botoes[] = array('title' => "Não",'block_names' => array("Inicio"));
        return $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($resposta, "", $botoes);
    }
    
    public function orcamentoPedidoUP($pizzaria, $idCliente, $personagem, $nome){
        $this->load->model("modelos/UtilitarioMensagemFacebook");
        $this->load->model("ia/Personagem_respostas_model");
        $this->load->model("regras_personagens/Utilitario");
        $chave = "RESUMO ÚLTIMO PEDIDO FINAL";

        $parecidos = $this->Personagem_respostas_model->buscaPorDescricaoParecida($personagem, $chave);
        $resposta = $this->geraResposta($pizzaria, $parecidos, $nome);
        
        $resposta = $this->Utilitario->preenchePalavrasReservadas($pizzaria, $idCliente, $resposta, $nome);
        
        $botoes=array();
        $botoes[] = array('title' => "Sim",'block_names' => array("Fluxo 102"));
        $botoes[] = array('title' => "Não",'block_names' => array("Inicio"));
        return $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($resposta, "", $botoes);
    }
    
    
    public function geraResposta($pizzaria, $parecidos, $nome){
        if($pizzaria == null){throw new Exception("Funcionalidade (geraResposta). Codigo da pizzaria está nulo ");}
        if($parecidos == null){throw new Exception("Funcionalidade (geraResposta). Sem respostas cadastradas");}
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
}
