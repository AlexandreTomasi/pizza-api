<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DadosTodosPizzaria
 *
 * @author 033234581
 */
class DadosTodosPizzaria extends CI_Model{
    //put your code here
    
    public function buscarBebidas($codigo){
        if($codigo == null){
            throw new Exception("(Bebida_model) metodo buscarBebidas com parametros nulos");
        }
        $this->db->where("pizzaria_bebida", $codigo);
        $this->db->where("ativo_bebida", 1);
        return $this->db->get("bebida")->result_array();
    }
    
    public function buscarFormaPagamentoAtivas($codigoPizzaria){
        if($codigoPizzaria == null){throw new Exception("(Forma_pagamento_model) metodo buscarFormaPagamentoAtivas com parametros nulos");}
        $this->db->where("pizzaria_forma_pagamento", $codigoPizzaria);
        $this->db->where("ativo_forma_pagamento", 1);
        return $this->db->get("forma_pagamento")->result_array();
    }
    
    
    public function buscarTodosExtrasDaEmpresa($codigo){
        if($codigo == null){throw new Exception("(Pizza_extra_model) metodo buscarTodosExtrasDaEmpresa com parametros nulos");}
        $this->db->where("pizzaria_extra_pizza", $codigo);
        $this->db->where("pizzaria_tipo_extra_pizza", $codigo);
        $this->db->where("ativo_extra_pizza", 1);
        $this->db->where("ativo_tipo_extra_pizza", 1);
        $this->db->join('tipo_extra_pizza', 'tipo_extra_pizza.codigo_tipo_extra_pizza = extra_pizza.tipo_extra_pizza_extra_pizza');
        $this->db->order_by('tipo_extra_pizza_extra_pizza', 'asc');
        return $this->db->get("extra_pizza")->result_array();
    }
    
    public function buscarSaboresEmpresaAtivos($codigo){
        if($codigo == null){throw new Exception("(Pizza_sabor_model) metodo buscarSaboresEmpresaAtivos com parametros nulos");}
        $this->db->where("pizzaria_sabor_pizza", $codigo);
        $this->db->where("ativo_sabor_pizza", 1);
        return $this->db->get("sabor_pizza")->result_array();
    }
    
    public function buscarTamanhos($codigo){
        if($codigo == null ){throw new Exception("(Pizza_tamanho_model) metodo buscarTamanhos com parametros nulos");}
        $this->db->where("pizzaria_tamanho_pizza", $codigo);
        $this->db->where("ativo_tamanho_pizza", 1);
        return $this->db->get("tamanho_pizza")->result_array();
    }
    
    public function buscarTipoExtras($codigo){
        if($codigo == null ){throw new Exception("(Tipo_extra_model) metodo buscarTipoExtras com parametros nulos");}
        $this->db->where("pizzaria_tipo_extra_pizza", $codigo);
        return $this->db->get("tipo_extra_pizza")->result_array();
    }
    
    public function buscarTodosValoresPizzasAtivos($codigoPizzaria){
        if($codigoPizzaria == null){throw new Exception("(Valor_pizza_model) metodo buscarTodosValoresPizzasAtivos com parametros nulos");}
        $this->db->where("pizzaria_valor_pizza", $codigoPizzaria);
        $this->db->where("ativo_valor_pizza", 1);
        return $this->db->get("valor_pizza")->result_array();
    }
}
