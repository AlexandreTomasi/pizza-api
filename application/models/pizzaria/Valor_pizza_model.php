<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Valor_pizza_model
 *
 * @author 033234581
 */
class Valor_pizza_model extends CI_Model{
    //put your code here
    public function buscarValorPizzaAtivo($codigoPizzaria, $codgSabor, $tamanho){
        if($codigoPizzaria == null || $codgSabor == null || $tamanho == null){throw new Exception("(Valor_pizza_model) metodo buscarValorPizzaAtivo com parametros nulos");}
        $this->db->where("pizzaria_valor_pizza", $codigoPizzaria);
        $this->db->where("sabor_pizza_valor_pizza", $codgSabor);
        $this->db->where("tamanho_pizza_valor_pizza", $tamanho);
        $this->db->where("ativo_valor_pizza", 1);
        $valor = $this->db->get("valor_pizza")->row_array();
        return $valor["preco_valor_pizza"];
    }
    
    public function buscarValorPizzaAtivoInativoPorTamanho($codigoPizzaria, $tamanho){
        if($codigoPizzaria == null ||  $tamanho == null){throw new Exception("(Valor_pizza_model) metodo buscarValorPizzaAtivo com parametros nulos");}
        $this->db->where("pizzaria_valor_pizza", $codigoPizzaria);
        $this->db->where("tamanho_pizza_valor_pizza", $tamanho);
        $this->db->where_in("ativo_valor_pizza", array(0,1));
        $valor = $this->db->get("valor_pizza")->result_array();
        return $valor;
    }
    
    public function buscarTodosValoresPizzasAtivos($codigoPizzaria){
        if($codigoPizzaria == null){throw new Exception("(Valor_pizza_model) metodo buscarTodosValoresPizzasAtivos com parametros nulos");}
        $this->db->where("pizzaria_valor_pizza", $codigoPizzaria);
        $this->db->where("ativo_valor_pizza", 1);
        return $this->db->get("valor_pizza")->result_array();
    }
    
    public function buscarValorPizzaPorIDAtivo($codigo, $codigoPizzaria){
        if($codigoPizzaria == null || $codigo == null){throw new Exception("(Valor_pizza_model) metodo buscarValorPizzaPorIDAtivo com parametros nulos");}
        $this->db->where("pizzaria_valor_pizza", $codigoPizzaria);
        $this->db->where("codigo_valor_pizza", $codigo);
        $this->db->where("ativo_valor_pizza", 1);
        $valor = $this->db->get("valor_pizza")->row_array();
        return $valor;
    }
    
    public function alterarValorPizza($valor, $codigoPizzaria){
        if($codigoPizzaria == null || $valor == null){throw new Exception("(Valor_pizza_model) metodo alterarValorPizza com parametros nulos");}
        $this->db->where('codigo_valor_pizza', $valor['codigo_valor_pizza']);
        $this->db->where("pizzaria_valor_pizza", $codigoPizzaria);
        $this->db->set($valor);
        return $this->db->update("valor_pizza",$valor);
    }
    
    public function inserirValorPizzaRetornandoo($valor){
        if($valor == null){throw new Exception("(Valor_pizza_model) metodo inserirValorPizzaRetornandoo com parametros nulos");}
        $resp=0;
        $this->db->trans_start();
        $in = $this->db->insert("valor_pizza",$valor);
        $resp = $this->db->insert_id();
        $this->db->trans_complete();
        $valor["codigo_valor_pizza"] = $resp;
        return $valor;
    }
    
    public function removerValorPizza($codigo, $codigoPizzaria){
        if($codigo == null || $codigoPizzaria == null){throw new Exception("(Valor_pizza_model) metodo removerValorPizza com parametros nulos");}
        $this->db->where("codigo_valor_pizza", $codigo);
        $this->db->where("pizzaria_valor_pizza", $codigoPizzaria);
        $this->db->where_in("ativo_valor_pizza", array(0,1));
        $valor = $this->db->get("valor_pizza")->row_array();
        $valor["ativo_valor_pizza"]=2;
        
        $this->db->where('codigo_valor_pizza', $valor['codigo_valor_pizza']);
        $this->db->where("pizzaria_valor_pizza", $valor["pizzaria_valor_pizza"]);
        $this->db->set($valor);
        return $this->db->update("valor_pizza",$valor);
    }
    
    public function ativarValorPizza($codigo, $codigoPizzaria){
        if($codigo == null || $codigoPizzaria == null){throw new Exception("(Valor_pizza_model) metodo removerValorPizza com parametros nulos");}
        $this->db->where("codigo_valor_pizza", $codigo);
        $this->db->where("pizzaria_valor_pizza", $codigoPizzaria);
        $this->db->where("ativo_valor_pizza",0);
        $valor = $this->db->get("valor_pizza")->row_array();
        $valor["ativo_valor_pizza"]=1;
        
        $this->db->where('codigo_valor_pizza', $valor['codigo_valor_pizza']);
        $this->db->where("pizzaria_valor_pizza", $valor["pizzaria_valor_pizza"]);
        $this->db->set($valor);
        return $this->db->update("valor_pizza",$valor);
    }
    
    public function inativarValorPizza($codigo, $codigoPizzaria){
        if($codigo == null || $codigoPizzaria == null){throw new Exception("(Valor_pizza_model) metodo removerValorPizza com parametros nulos");}
        $this->db->where("codigo_valor_pizza", $codigo);
        $this->db->where("pizzaria_valor_pizza", $codigoPizzaria);
        $this->db->where("ativo_valor_pizza", 1);
        $valor = $this->db->get("valor_pizza")->row_array();
        $valor["ativo_valor_pizza"]=0;
        
        $this->db->where('codigo_valor_pizza', $valor['codigo_valor_pizza']);
        $this->db->where("pizzaria_valor_pizza", $valor["pizzaria_valor_pizza"]);
        $this->db->set($valor);
        return $this->db->update("valor_pizza",$valor);
    }
}
