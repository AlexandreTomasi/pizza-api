<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Endereco_regras
 *
 * @author 033234581
 */
class Endereco_regras extends CI_Model{
    //put your code here
    public function buscaEnderecoDoUP($pizzaria, $idCliente, $personagem, $nome, $permissao){
        $this->load->model("modelos/UtilitarioMensagemFacebook");
        $this->load->model("pizzaria/Pedido_model");
        $this->load->model("pizzaria/Cliente_model");
        $this->load->model("modelos/VerificaBairroPermitido");
        $this->load->model("pizzaria/Taxa_entrega_model");
        $this->load->model("ia/Personagem_respostas_model");
        $this->load->model("regras_personagens/Utilitario");
        
        $chave = "ENDERECO ÚLTIMO PEDIDO";
        $parecidos = $this->Personagem_respostas_model->buscaPorDescricaoParecida($personagem, $chave);
        $cliente = $this->Cliente_model->buscarClientesFBid($idCliente, $pizzaria);
        if($cliente == null || $cliente["ativo_cliente_pizzaria"] == 0){throw new Exception("(Endereco_regras) buscaEnderecoDoUP cliente não encontrado no banco ou bloqueado");}

        $pedido = $this->Pedido_model->buscarUltimoPedidoCodgCliente($pizzaria, $cliente["codigo_cliente_pizzaria"]);
        if($cliente != null && $pedido != null){
            $taxa = $this->Taxa_entrega_model->buscarTaxaEntregaPorBairro($pizzaria, $pedido["bairro_pedido"]);
            if($taxa == null){
                $resposta= $this->UtilitarioMensagemFacebook->definirAtributosDoUsuario(array("PermissaoUP" => "0"));
            }else{
                $resposta = $this->geraResposta($pizzaria, $parecidos, $nome);
                $resposta = $this->Utilitario->preenchePalavrasReservadas($pizzaria, $idCliente, $resposta, $nome);
                $rapida = array();
                $rapida[] = array('title' => "Sim",'block_names' => array("Fluxo 106"));
                $rapida[] = array('title' => "Não",'block_names' => array("Fluxo 104"));
                return $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($resposta, array("PermissaoUP" => "0"), $rapida);
            }
        }else{
            throw new Exception("(Endereco_regras) buscaEnderecoDoUP cliente não encontrado ou pedido nao encontrado");
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
}
