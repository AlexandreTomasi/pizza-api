<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pizza_extra_movel
 *
 * @author 033234581
 */
class Pizza_extra_model extends CI_Model{
    
    public function inserirExtra($extra){
        if($extra == null){throw new Exception("(Pizza_extra_model) metodo inserirExtra com parametros nulos");}
        $this->db->trans_start();
        $this->db->insert("extra_pizza",$extra);
        if($this->db->affected_rows() == 0){throw new Exception("(Pizza_extra_model) metodo inserirExtra não alterou nenhuma linha");}
        $resp = $this->db->insert_id();
        $this->db->trans_complete();
        $extra["codigo_extra_pizza"] = $resp;
        return $extra;
    }
    
    public function buscarTodosExtrasDaEmpresaAtivosInativos($codigo){
        if($codigo == null){throw new Exception("(Pizza_extra_model) metodo buscarTodosExtrasDaEmpresa com parametros nulos");}
        $this->db->where("pizzaria_extra_pizza", $codigo);
        $this->db->where("pizzaria_tipo_extra_pizza", $codigo);
        $this->db->where_in("ativo_extra_pizza", array(0,1));
        $this->db->where("ativo_tipo_extra_pizza", 1);
        $this->db->where("ativo_tamanho_pizza", 1);
        $this->db->join('tipo_extra_pizza', 'tipo_extra_pizza.codigo_tipo_extra_pizza = extra_pizza.tipo_extra_pizza_extra_pizza');
        $this->db->join('tamanho_pizza', 'tamanho_pizza.codigo_tamanho_pizza = extra_pizza.tamanho_pizza_extra_pizza');
        return $this->db->get("extra_pizza")->result_array();
    }
    
    public function buscarTodosExtrasAtivos($codigo){
        if($codigo == null){throw new Exception("(Pizza_extra_model) metodo buscarTodosExtrasDaEmpresa com parametros nulos");}
        $this->db->where("pizzaria_extra_pizza", $codigo);
        $this->db->where("ativo_extra_pizza", 1);
        return $this->db->get("extra_pizza")->result_array();
    }
    
    public function buscarExtrasAssociadoTamanho($tamanho, $codigo){
        if($tamanho == null || $codigo == null){throw new Exception("(Pizza_extra_model) metodo buscarTodosExtrasDaEmpresa com parametros nulos");}
        $this->db->where("pizzaria_extra_pizza", $codigo);
        $this->db->where("tamanho_pizza_extra_pizza", $tamanho);
        $this->db->where("pizzaria_tipo_extra_pizza", $codigo);
        $this->db->where("ativo_extra_pizza", 1);
        $this->db->where("ativo_tipo_extra_pizza", 1);
        $this->db->join('tipo_extra_pizza', 'tipo_extra_pizza.codigo_tipo_extra_pizza = extra_pizza.tipo_extra_pizza_extra_pizza');
        return $this->db->get("extra_pizza")->result_array();
    }
    
    public function buscarExtraCodigo($codigo, $codgPizzaria){
        if($codigo == null || $codgPizzaria == null){throw new Exception("(Pizza_extra_model) metodo buscarExtraCodigo com parametros nulos");}
        $this->db->where("codigo_extra_pizza", $codigo);
        $this->db->where("pizzaria_extra_pizza", $codgPizzaria);
        $this->db->where("ativo_extra_pizza", 1);
        return $this->db->get("extra_pizza")->row_array();
    }
    
    public function buscarExtraIdAtivoInativo($codigo, $codgPizzaria){
        if($codigo == null || $codgPizzaria == null){throw new Exception("(Pizza_extra_model) metodo buscarExtraCodigo com parametros nulos");}
        $this->db->where("codigo_extra_pizza", $codigo);
        $this->db->where("pizzaria_extra_pizza", $codgPizzaria);
        $this->db->where_in("ativo_extra_pizza", array(0,1));
        return $this->db->get("extra_pizza")->row_array();
    }
    
    public function buscarExtraIdAtivoInativoJoinTipo($codigo, $codgPizzaria){
        if($codigo == null || $codgPizzaria == null){throw new Exception("(Pizza_extra_model) metodo buscarExtraCodigo com parametros nulos");}
        $this->db->where("codigo_extra_pizza", $codigo);
        $this->db->where("pizzaria_extra_pizza", $codgPizzaria);
        $this->db->where("pizzaria_tipo_extra_pizza", $codgPizzaria);
        $this->db->where_in("ativo_extra_pizza", array(0,1));
        $this->db->join('tipo_extra_pizza', 'tipo_extra_pizza.codigo_tipo_extra_pizza = extra_pizza.tipo_extra_pizza_extra_pizza');
        return $this->db->get("extra_pizza")->row_array();
    }
    
    public function buscarExtraPorName($pizzaria, $name){
        if($pizzaria == null || $name == null){throw new Exception("(Pizza_extra_model) metodo buscarExtraPorName com parametros nulos");}
        $this->db->where("descricao_extra_pizza", $name);
        $this->db->where("pizzaria_extra_pizza", $pizzaria);
        $this->db->where("ativo_extra_pizza", 1);
        return $this->db->get("extra_pizza")->row_array();
    }
    
    public function buscarExtraPorNameTamanho($pizzaria, $name, $tamanho){
        if($pizzaria == null || $name == null || $tamanho == null){throw new Exception("(Pizza_extra_model) metodo buscarExtraPorName com parametros nulos");}
        $this->db->where("descricao_extra_pizza", $name);
        $this->db->where("tamanho_pizza_extra_pizza", $tamanho);
        $this->db->where("pizzaria_extra_pizza", $pizzaria);
        $this->db->where("ativo_extra_pizza", 1);
        return $this->db->get("extra_pizza")->row_array();
    }
    
    public function buscarExtraPorNameTamanhoTipo($pizzaria, $name, $tamanho, $codgTipo){
        if($pizzaria == null || $name == null || $tamanho == null || $codgTipo == null){throw new Exception("(Pizza_extra_model) metodo buscarExtraPorName com parametros nulos");}
        $this->db->where("descricao_extra_pizza", $name);
        $this->db->where("tipo_extra_pizza_extra_pizza", $codgTipo);
        $this->db->where("tamanho_pizza_extra_pizza", $tamanho);
        $this->db->where("pizzaria_extra_pizza", $pizzaria);
        $this->db->where("ativo_extra_pizza", 1);
        return $this->db->get("extra_pizza")->row_array();
    }
    
    public function alterarExtra($extra){
        if($extra == null){throw new Exception("(Pizza_extra_model) metodo alterarExtra com parametros nulos");}
        $this->db->where('codigo_extra_pizza', $extra['codigo_extra_pizza']);
        $this->db->where('pizzaria_extra_pizza', $extra['pizzaria_extra_pizza']);
        $this->db->where_in("ativo_extra_pizza", array(0,1));
        $this->db->set($extra);
        $resultado =  $this->db->update("extra_pizza",$extra);
        return $resultado;
    }
    
    public function removerExtra($codigo, $codgPizzaria){
        if($codigo == null || $codgPizzaria == null){throw new Exception("(Pizza_extra_model) metodo removerExtra com parametros nulos");}
        $this->db->where("codigo_extra_pizza", $codigo);
        $this->db->where("pizzaria_extra_pizza", $codgPizzaria);
        $this->db->where_in("ativo_extra_pizza", array(0,1));
        $extra =  $this->db->get("extra_pizza")->row_array();
        $extra["ativo_extra_pizza"] = 2;
        
        $this->db->where("codigo_extra_pizza", $extra["codigo_extra_pizza"]);
        $this->db->where("pizzaria_extra_pizza", $extra["pizzaria_extra_pizza"]);
        $this->db->set($extra);
        $resultado =  $this->db->update("extra_pizza",$extra);
        if($this->db->affected_rows() == 0){throw new Exception("(Pizza_extra_model) metodo removerExtra não alterou nenhuma linha");}
        return $resultado;
    }
    
    public function ativarExtraPorCodg($codigo, $codgPizzaria){
        if($codigo == null || $codgPizzaria == null){throw new Exception("(Pizza_extra_model) metodo ativarExtraPorCodg com parametros nulos");}
        $this->db->where("codigo_extra_pizza", $codigo);
        $this->db->where("pizzaria_extra_pizza", $codgPizzaria);
        $this->db->where_in("ativo_extra_pizza", array(0,1));
        $extra =  $this->db->get("extra_pizza")->row_array();
        $extra["ativo_extra_pizza"] = 1;
        
        $this->db->where("codigo_extra_pizza", $extra["codigo_extra_pizza"]);
        $this->db->where("pizzaria_extra_pizza", $extra["pizzaria_extra_pizza"]);
        $this->db->set($extra);
        $resultado =  $this->db->update("extra_pizza",$extra);
        if($this->db->affected_rows() == 0){throw new Exception("(Pizza_extra_model) metodo ativarExtraPorCodg não alterou nenhuma linha");}
        return $resultado;
    }
    
    public function inativarExtraPorCodg($codigo, $codgPizzaria){
        if($codigo == null || $codgPizzaria == null){throw new Exception("(Pizza_extra_model) metodo inativarExtraPorCodg com parametros nulos");}
        $this->db->where("codigo_extra_pizza", $codigo);
        $this->db->where("pizzaria_extra_pizza", $codgPizzaria);
        $this->db->where_in("ativo_extra_pizza", array(0,1));
        $extra =  $this->db->get("extra_pizza")->row_array();
        $extra["ativo_extra_pizza"] = 0;
        
        $this->db->where("codigo_extra_pizza", $extra["codigo_extra_pizza"]);
        $this->db->where("pizzaria_extra_pizza", $extra["pizzaria_extra_pizza"]);
        $this->db->set($extra);
        $resultado =  $this->db->update("extra_pizza",$extra);
        if($this->db->affected_rows() == 0){throw new Exception("(Pizza_extra_model) metodo inativarExtraPorCodg não alterou nenhuma linha");}
        return $resultado;
    }
}
