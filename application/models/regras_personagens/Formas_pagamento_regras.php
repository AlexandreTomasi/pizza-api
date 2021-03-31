<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Formas_pagamento_regras
 *
 * @author 033234581
 */
class Formas_pagamento_regras extends CI_Model{
    //put your code here
    public function galeriaFormasPagamento($pizzaria, $idCliente, $personagem, $nome){
        $this->load->model("ia/Personagem_respostas_model");
        $this->load->model("regras_personagens/Utilitario");
        $this->load->model("modelos/UtilitarioMensagemFacebook");
        $this->load->helper(array("geraResposta"));
        $chave = "FORMA PAGAMENTO ÚLTIMO PEDIDO";
        $parecidos = $this->Personagem_respostas_model->buscaPorDescricaoParecida($personagem, $chave);
        $resposta = geraResposta($pizzaria, $parecidos, $nome);
        $resposta = $this->Utilitario->preenchePalavrasReservadas($pizzaria, $idCliente, $resposta, $nome);
        
        $this->load->model("pizzaria/Forma_pagamento_model");
        $formas = $this->Forma_pagamento_model->buscarFormaPagamentoAtivas($pizzaria);
        if($formas == null){throw new Exception("Não existe formas de pagamentos para essa empresa");}
        $total = count($formas);
        if($total > 10){$total = 10;}
        $galeria = array();
        for($i=0; $i < $total; $i++){
            $galeria[$i] = array(
                "title"=> $formas[$i]["descricao_forma_pagamento"],
                "image_url"=> "",
                "subtitle"=> $formas[$i]["descricao_forma_pagamento"],
                "buttons" =>array(
                    array(
                        "type"=> "show_block",
                        "block_name"=> "Fluxo 112",
                        "title"=> $formas[$i]["descricao_forma_pagamento"]
                    )
                )
            );
        }
       
        return $this->UtilitarioMensagemFacebook->gerarGaleria($resposta, "", $galeria);
    }
    
    public function recebeFormaPagamento($pizzaria, $idCliente, $personagem, $nome, $formaPgSelecionada){
        $this->load->model("ia/Personagem_respostas_model");
        $this->load->model("regras_personagens/Utilitario");
        $this->load->model("modelos/UtilitarioMensagemFacebook");
        $this->load->model("pizzaria/Forma_pagamento_model");
        $this->load->helper(array("geraResposta"));
   
        $formaPagamento = $this->Forma_pagamento_model->buscarFormaPagamentoDescricaoAtivaInativa($formaPgSelecionada, $pizzaria);
        if($formaPagamento == null){throw new Exception("buscar Forma Pagamento Descricao Ativas, nao retornou dados. Funcionalidade: setaValorFormaPagamento");}
        if($formaPagamento["ativo_forma_pagamento"] != 1){throw new Exception("Forma de pagamento está inativa");}
        
        // verifico se é dinheiro
        if($formaPagamento["codigo_forma_pagamento"] == 1){
            $chave = "FORMA PAGAMENTO DINHEIRO";
            $parecidos = $this->Personagem_respostas_model->buscaPorDescricaoParecida($personagem, $chave);
            $resposta = geraResposta($pizzaria, $parecidos, $nome);
            $resposta = $this->Utilitario->preenchePalavrasReservadas($pizzaria, $idCliente, $resposta, $nome);

            $botoes = array();
            $botoes[] = array('title' => "Sim",'block_names' => array("Fluxo 110"));
            $botoes[] = array('title' => "Não",'block_names' => array("Fluxo 108"));
            
            $chave = "FORMA PAGAMENTO TROCO";
            $parece = $this->Personagem_respostas_model->buscaPorDescricaoParecida($personagem, $chave);
            $resp = geraResposta($pizzaria, $parece, $nome);
            $resp = $this->Utilitario->preenchePalavrasReservadas($pizzaria, $idCliente, $resposta, $nome);
            
            
            $atributos = array("FormaPgClienteUP" => $formaPagamento["codigo_forma_pagamento"], "SolicitaTroco" => $resp);
            return $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($resposta, $atributos, $botoes);
        }else{
            $bloco = "Fluxo 118";
            return $this->UtilitarioMensagemFacebook->redirecionarParaBlocos("", "", $bloco);
        }
    }
    
    public function recebeTroco($pizzaria, $idCliente, $personagem, $nome, $troco){
        $this->load->model("ia/Personagem_respostas_model");
        $this->load->model("regras_personagens/Utilitario");
        $this->load->model("modelos/UtilitarioMensagemFacebook");
        $this->load->model("pizzaria/Forma_pagamento_model");
        $this->load->model("pizzaria/Pedido_model");
        $this->load->model("pizzaria/Cliente_model");
        $this->load->helper(array("geraResposta"));
        
        // validar o troco
        $cliente = $this->Cliente_model->buscarClientesFBid($idCliente, $pizzaria);
        $pedido = null;
        if($cliente["codigo_cliente_pizzaria"] != null){
            $pedido = $this->Pedido_model->buscarUltimoPedidoCodgCliente($pizzaria, $cliente["codigo_cliente_pizzaria"]);
        }
        if($cliente == null && $pedido == null){throw new Exception("(recebeTroco) Cliente ou pedido retornou nulos");}
        $custoPedido = $pedido["valor_total_pedido"];
        if($this->validaTroco($troco, $custoPedido) == true){//segue para prox fluxo
            $bloco = "Fluxo 118";
            return $this->UtilitarioMensagemFacebook->redirecionarParaBlocos("", "", $bloco);
        }else{
            // errou o valor volta no fluxo
            $chave = "FORMA PAGAMENTO TROCO ERRADO";
            $bloco = "Fluxo 112";
            $parecidos = $this->Personagem_respostas_model->buscaPorDescricaoParecida($personagem, $chave);
            $resposta = geraResposta($pizzaria, $parecidos, $nome);
            $resposta = $this->Utilitario->preenchePalavrasReservadas($pizzaria, $idCliente, $resposta, $nome);
            
            return $this->UtilitarioMensagemFacebook->redirecionarParaBlocos($resposta, "", $bloco);
        }
        //buscar o valor do troco na mensagem
        
    }
    public function validaTroco($troco, $custoPedido){
        $troco = str_replace(",",".",$troco);
        $numeros = array();
        for($i=0; $i < strlen($troco); $i++){
            $caracter = substr($troco,$i,1);
            if(is_numeric($caracter) == 1){
                $temp = "";
                for($i=$i; $i < strlen($troco); $i++){
                    $caracter = substr($troco,$i,1);
                    if(is_numeric($caracter) == 1 || $caracter == '.'){
                        $temp .= $caracter;
                    }else{
                        break;
                    }
                }
                $numeros[] = $temp;
            }
        }
        for($i=0; $i < count($numeros); $i++){
            if(substr($numeros[$i],strlen($numeros[$i])-1,1) == '.'){// se o ultimo valor é ponto tiro ele
                $numeros[$i] = substr($numeros[$i],0,strlen($numeros[$i])-1);
            }
        }
        if(count($numeros) == 1){
            if(is_numeric($numeros[0])){
                if(doubleval($numeros[0]) >= $custoPedido && doubleval($numeros[0]) <= $custoPedido+100){
                    return true;
                }else{
                    return false;
                }
            }
            return false;
        }else{
            return false;
        }
    }
}
