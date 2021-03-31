<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utilitario
 *
 * @author Alexandre
 */
class Utilitario extends CI_Model{
    //put your code here
    public function preenchePalavrasReservadas($pizzaria, $idCliente, $resposta, $usuario_nome){      
        $this->load->helper(array("formataTelefone"));
        $resposta = str_replace("@nome_usuario",$usuario_nome,$resposta);
        $resposta = str_replace("@mao_baixo",'👇',$resposta);
        
        if (strpos($resposta, "@nome_pizzaria") !== false) {// se existir
            $this->load->model("pizzaria/Empresa_model");
            $empresa = $this->Empresa_model->buscaPizzariaPorCodigo($pizzaria);
            $resposta = str_replace("@nome_pizzaria",$empresa["nome_fantasia_pizzaria"],$resposta);
        }
        
        // verifica se existe atalho do resumo do pedido.
        if (strpos($resposta, "@resumo_ultimo_pedido") !== false) {// se existir
            $this->load->model("regras_personagens/Orcamento_pedidos");
            $resumo = $this->Orcamento_pedidos->resumoPedidoUP($pizzaria, $idCliente);
            $resposta = str_replace("@resumo_ultimo_pedido",$resumo,$resposta);
        }
        
        // verifica se existe atalho do resumo do pedido.
        if (strpos($resposta, "@orcamento_up") !== false) {// se existir
            $this->load->model("regras_personagens/Orcamento_pedidos");
            $resumo = $this->Orcamento_pedidos->retornaOrcamentoUPCliente($pizzaria, $idCliente);
            $resposta = str_replace("@orcamento_up",$resumo,$resposta);
        }
        
        // verifica se existe atalho do ultimo endereço.
        if (strpos($resposta, "@ultimo_endereco") !== false) {// se existir
            $this->load->model("pizzaria/Cliente_model");
            $this->load->model("pizzaria/Pedido_model");
            $cliente = $this->Cliente_model->buscarClientesFBid($idCliente, $pizzaria);
            if($cliente == null || $cliente["ativo_cliente_pizzaria"] == 0){throw new Exception("(Endereco_regras) buscaEnderecoDoUP cliente não encontrado no banco ou bloqueado");}
            $pedido = $this->Pedido_model->buscarUltimoPedidoCodgCliente($pizzaria, $cliente["codigo_cliente_pizzaria"]);
            if($cliente != null && $pedido != null){
                $resumo = $pedido["endereco_pedido"];
                $resposta = str_replace("@ultimo_endereco",$resumo,$resposta);
            }
        }
        
        // verifica se existe atalho do ultimo endereço.
        if (strpos($resposta, "@cliente_telefone_up") !== false) {// se existir
            $this->load->model("pizzaria/Cliente_model");
            $this->load->model("pizzaria/Pedido_model");
            $cliente = $this->Cliente_model->buscarClientesFBid($idCliente, $pizzaria);
            if($cliente == null || $cliente["ativo_cliente_pizzaria"] == 0){throw new Exception("(Endereco_regras) buscaEnderecoDoUP cliente não encontrado no banco ou bloqueado");}
            $pedido = $this->Pedido_model->buscarUltimoPedidoCodgCliente($pizzaria, $cliente["codigo_cliente_pizzaria"]);
            if($cliente != null && $pedido != null){
                $resumo = formataTelefone($pedido["telefone_pedido"]);
                $resposta = str_replace("@cliente_telefone_up",$resumo,$resposta);
            }
        }
        
        return $resposta;
    }
}
