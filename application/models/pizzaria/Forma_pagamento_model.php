<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Forma_pagamento_model
 *
 * @author 033234581
 */
class Forma_pagamento_model extends CI_Model{
    
    public function inserirFormaPagamentoRetornandoForma($formaPaga){
        if($formaPaga == null){throw new Exception("(Forma_pagamento_model) metodo inserirFormaPagamentoRetornandoForma com parametros nulos");}
        $resp=0;
        $this->db->trans_start();
        $this->db->insert("forma_pagamento",$formaPaga);
        if($this->db->affected_rows() == 0){throw new Exception("(Forma_pagamento_model) metodo inserirFormaPagamentoRetornandoForma não alterou nenhuma linha");}
        $resp = $this->db->insert_id();
        $this->db->trans_complete();
        $formaPaga["codigo_forma_pagamento"] = $resp;
        return $formaPaga;
    }
    
    public function buscarFormaPagamentoPorCodgAtiva($codigoFormaPaga, $codgPizzaria){
        if($codigoFormaPaga == null || $codgPizzaria == null){throw new Exception("(Forma_pagamento_model) metodo buscarFormaPagamento com parametros nulos");}
        $this->db->where("codigo_forma_pagamento", $codigoFormaPaga);
        $this->db->where("pizzaria_forma_pagamento", $codgPizzaria);
        $this->db->where("ativo_forma_pagamento", 1);
        return $this->db->get("forma_pagamento")->row_array();
    }
    
    public function buscarFormaPagamentoAtivaInativa($codigoFormaPaga, $codgPizzaria){
        if($codigoFormaPaga == null || $codgPizzaria == null){throw new Exception("(Forma_pagamento_model) metodo buscarFormaPagamento com parametros nulos");}
        $this->db->where("codigo_forma_pagamento", $codigoFormaPaga);
        $this->db->where("pizzaria_forma_pagamento", $codgPizzaria);
        $this->db->where_in("ativo_forma_pagamento", array(0,1));
        return $this->db->get("forma_pagamento")->row_array();
    }
    
    public function buscarFormaPagamentoPorId($codigoFormaPaga, $codgPizzaria){
        if($codigoFormaPaga == null || $codgPizzaria == null){throw new Exception("(Forma_pagamento_model) metodo buscarFormaPagamentoPorId com parametros nulos");}
        $this->db->where("pizzaria_forma_pagamento", $codgPizzaria);
        $this->db->where("codigo_forma_pagamento", $codigoFormaPaga);
        return  $this->db->get("forma_pagamento")->row_array();
    }
    
    public function buscarFormaPagamentoAtivas($codigoPizzaria){
        if($codigoPizzaria == null){throw new Exception("(Forma_pagamento_model) metodo buscarFormaPagamentoAtivas com parametros nulos");}
        $this->db->where("pizzaria_forma_pagamento", $codigoPizzaria);
        $this->db->where("ativo_forma_pagamento", 1);
        return $this->db->get("forma_pagamento")->result_array();
    }
    
    public function buscarFormaPagamentoAtivasInativas($codigoPizzaria){
        if($codigoPizzaria == null){throw new Exception("(Forma_pagamento_model) metodo buscarFormaPagamentoAtivas com parametros nulos");}
        $this->db->where("pizzaria_forma_pagamento", $codigoPizzaria);
        $this->db->where_in("ativo_forma_pagamento", array(0,1));
        return $this->db->get("forma_pagamento")->result_array();
    }
    
    public function buscarFormaPagamentoDescricaoAtivas($descricao, $codigoPizzaria){
        if($descricao == null || $codigoPizzaria == null){throw new Exception("(Forma_pagamento_model) metodo buscarFormaPagamentoDescricaoAtivas com parametros nulos");}
        $this->db->where("pizzaria_forma_pagamento", $codigoPizzaria);
        $this->db->where("ativo_forma_pagamento", 1);
        $this->db->where("descricao_forma_pagamento", $descricao);
        return $this->db->get("forma_pagamento")->row_array();
    }
    
    public function buscarFormaPagamentoDescricaoAtivaInativa($descricao, $codigoPizzaria){
        if($descricao == null || $codigoPizzaria == null){throw new Exception("(Forma_pagamento_model) metodo buscarFormaPagamentoDescricaoAtivas com parametros nulos");}
        $this->db->where("pizzaria_forma_pagamento", $codigoPizzaria);
        $this->db->where_in("ativo_forma_pagamento", array(0,1));
        $this->db->where("descricao_forma_pagamento", $descricao);
        return $this->db->get("forma_pagamento")->row_array();
    }
    
    public function alterarFormaPagamento($formaPaga){
        if($formaPaga == null){throw new Exception("(Forma_pagamento_model) metodo alterarFormaPagamento com parametros nulos");}
        $this->db->where('codigo_forma_pagamento', $formaPaga['codigo_forma_pagamento']);
        $this->db->where("pizzaria_forma_pagamento", $formaPaga["pizzaria_forma_pagamento"]);
        $this->db->set($formaPaga);
        $resultado = $this->db->update("forma_pagamento",$formaPaga);
        return $resultado;
    }
    
    public function removerFormaPagamento($codigoFormaPaga){
        if($codigoFormaPaga == null){throw new Exception("(Forma_pagamento_model) metodo removerFormaPagamento com parametros nulos");}
        if($codigoFormaPaga != 1){
            $this->db->where("codigo_forma_pagamento", $codigoFormaPaga);
            $this->db->where_in("ativo_forma_pagamento", array(0,1));
            $forma = $this->db->get("forma_pagamento")->row_array();
            $forma["ativo_forma_pagamento"]=2;

            $this->db->where('codigo_forma_pagamento', $forma['codigo_forma_pagamento']);
            $this->db->set($forma);
            $resultado = $this->db->update("forma_pagamento",$forma);
            if($this->db->affected_rows() == 0){throw new Exception("(Forma_pagamento_model) metodo removerFormaPagamento não alterou nenhuma linha");}
            return $resultado;
        }else{
            throw new Exception("Não é possivel remover a forma de pagamento referente a Dinheiro");
        }
    }
    
    public function ativarFormaPagamento($codigoFormaPaga, $codgPizzaria){
        if($codigoFormaPaga == null && $codgPizzaria != null){throw new Exception("(Forma_pagamento_model) metodo ativarFormaPagamento com parametros nulos");}
        if($codigoFormaPaga != 1){
            $this->db->where("codigo_forma_pagamento", $codigoFormaPaga);
            $this->db->where("pizzaria_forma_pagamento", $codgPizzaria);
            $this->db->where("ativo_forma_pagamento", 0);
            $forma = $this->db->get("forma_pagamento")->row_array();
            $forma["ativo_forma_pagamento"]=1;

            $this->db->where('codigo_forma_pagamento', $forma['codigo_forma_pagamento']);
            $this->db->set($forma);
            $resultado = $this->db->update("forma_pagamento",$forma);
            if($this->db->affected_rows() == 0){throw new Exception("(Forma_pagamento_model) metodo ativarFormaPagamento não alterou nenhuma linha");}
            return $resultado;
        }else{
            throw new Exception("Não é possivel ativar a forma de pagamento referente a Dinheiro");
        }
    }
    
    public function inativarFormaPagamento($codigoFormaPaga, $codgPizzaria){
        if($codigoFormaPaga == null && $codgPizzaria != null){throw new Exception("(Forma_pagamento_model) metodo inativarFormaPagamento com parametros nulos");}
        if($codigoFormaPaga != 1){
            $this->db->where("codigo_forma_pagamento", $codigoFormaPaga);
            $this->db->where("pizzaria_forma_pagamento", $codgPizzaria);
            $this->db->where("ativo_forma_pagamento", 1);
            $forma = $this->db->get("forma_pagamento")->row_array();
            $forma["ativo_forma_pagamento"]=0;

            $this->db->where('codigo_forma_pagamento', $forma['codigo_forma_pagamento']);
            $this->db->set($forma);
            $resultado = $this->db->update("forma_pagamento",$forma);
            if($this->db->affected_rows() == 0){throw new Exception("(Forma_pagamento_model) metodo inativarFormaPagamento não alterou nenhuma linha");}
            return $resultado;
        }else{
            throw new Exception("Não é possivel inativar a forma de pagamento referente a Dinheiro");
        }
    }
}
