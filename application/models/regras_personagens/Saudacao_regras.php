<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of saudacao_regras
 *
 * @author Alexandre
 */
class Saudacao_regras extends CI_Model{
    //put your code here
    public function mensagemInicial($pizzaria, $idCliente, $personagem, $nome){
        $this->load->model("modelos/UtilitarioMensagemFacebook");
        $this->load->model("pizzaria/Cliente_model");
        $this->load->model("pizzaria/Pedido_model");
        $this->load->model("ia/Personagem_respostas_model");
        $this->load->model("regras_personagens/Utilitario");
        
        $cliente = $this->Cliente_model->buscarClientesFBid($idCliente, $pizzaria);
        if($cliente["ativo_cliente_pizzaria"] == 0){throw new Exception("");}
        if($cliente == null){// NUNCA FEZ PEDIDO
            $parecidos = $this->Personagem_respostas_model->buscaPorDescricaoParecida($personagem, "SAUDAÇÃO NUNCA FEZ PEDIDO");
            $resposta = $this->geraResposta($pizzaria, $parecidos, $nome);
            $resposta = $this->Utilitario->preenchePalavrasReservadas($pizzaria, $idCliente, $resposta, $nome);
            return $this->UtilitarioMensagemFacebook->gerarMensagem($resposta, "");
        }else{// ja fez pedido
            // verificar se ja faz x dias
            $pedido = $this->Pedido_model->buscarUltimoPedidoCodgCliente($pizzaria, $cliente["codigo_cliente_pizzaria"]);
            // aumento em 45 dias a data do pedido
            $dataPedido = date('d/m/Y', strtotime('+45 days', strtotime($pedido["data_hora_pedido"])));
            // verifico se a data do pedido +45 dias é superior a data atual
            if($dataPedido > (date('d/m/Y')))//PEDIDO RECENTE
            {
                $parecidos = $this->Personagem_respostas_model->buscaPorDescricaoParecida($personagem, "SAUDAÇÃO PEDIDO RECENTE");
                $resposta = $this->geraResposta($pizzaria, $parecidos, $nome);
                $resposta = $this->Utilitario->preenchePalavrasReservadas($pizzaria, $idCliente, $resposta, $nome);
                return $this->UtilitarioMensagemFacebook->gerarMensagem($resposta, "");
                
            }else{//PEDIDO NÃO RECENTE
                $parecidos = $this->Personagem_respostas_model->buscaPorDescricaoParecida($personagem, "SAUDAÇÃO PEDIDO NÃO RECENTE");
                $resposta = $this->geraResposta($pizzaria, $parecidos, $nome);
                $resposta = $this->Utilitario->preenchePalavrasReservadas($pizzaria, $idCliente, $resposta, $nome);
                return $this->UtilitarioMensagemFacebook->gerarMensagem($resposta, "");
            }
        }
    }
    
    public function geraResposta($pizzaria, $parecidos, $nome){
        
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
    
    public function menuInicial($pizzaria, $idCliente, $personagem, $nome){
        $this->load->model("modelos/UtilitarioMensagemFacebook");
        $this->load->model("pizzaria/Cliente_model");
        $this->load->model("ia/Personagem_respostas_model");
        $this->load->model("regras_personagens/Utilitario");
        
        $cliente = $this->Cliente_model->buscarClientesFBid($idCliente, $pizzaria);
        if($cliente["ativo_cliente_pizzaria"] == 0){throw new Exception("");}
        $parecidos = $this->Personagem_respostas_model->buscaPorDescricaoParecida($personagem, "SAUDAÇÃO TODOS CASOS");
        $resposta = $this->geraResposta($pizzaria, $parecidos, $nome);
        $resposta = $this->Utilitario->preenchePalavrasReservadas($pizzaria, $idCliente, $resposta, $nome);
        $botoes=array();
        if($cliente == null){// NUNCA FEZ PEDIDO
            $botoes[] = array('title' => "NOVO PEDIDO",'block_names' => array("Fluxo 26"));
            $botoes[] = array('title' => "FALAR COM O GERENTE",'block_names' => array("Falar com gerente"));
        }else{// ja fez pedido
            $this->load->model("regras_personagens/Orcamento_pedidos");
            $valido = $this->Orcamento_pedidos->validaIgredientesUltimoPedido($pizzaria, $idCliente);
            if($valido == true){
                $botoes[] = array('title' => "NOVO PEDIDO",'block_names' => array("Fluxo 26"));
                $botoes[] = array('title' => "ÚLTIMO PEDIDO",'block_names' => array("Fluxo 100"));
                $botoes[] = array('title' => "FALAR COM O GERENTE",'block_names' => array("Falar com gerente"));
            }else{
                $botoes[] = array('title' => "NOVO PEDIDO",'block_names' => array("Fluxo 26"));
                $botoes[] = array('title' => "FALAR COM O GERENTE",'block_names' => array("Falar com gerente"));
            }
        }
        return $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($resposta, "", $botoes);
    }
    
    public function mensagemInicialUP($pizzaria, $idCliente, $personagem, $nome){
        $this->load->model("modelos/UtilitarioMensagemFacebook");
        $this->load->model("ia/Personagem_respostas_model");
        $this->load->model("regras_personagens/Utilitario");
        $chave = "MENSAGEM INICIAL ÚLTIMO PEDIDO";

        $parecidos = $this->Personagem_respostas_model->buscaPorDescricaoParecida($personagem, $chave);
        $resposta = $this->geraResposta($pizzaria, $parecidos, $nome);
        $resposta = $this->Utilitario->preenchePalavrasReservadas($pizzaria, $idCliente, $resposta, $nome);
        return $this->UtilitarioMensagemFacebook->gerarMensagem($resposta, "");
    }
}
