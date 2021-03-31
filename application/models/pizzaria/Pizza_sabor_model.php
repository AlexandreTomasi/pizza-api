<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pizza_sabor_model
 *
 * @author 033234581
 */
class Pizza_sabor_model extends CI_Model{
    //put your code here
    public function inserirSabor($sabor){
        if($sabor == null){throw new Exception("(Pizza_sabor_model) metodo inserirSabor com parametros nulos");}
        return $this->db->insert("sabor_pizza",$sabor);     
    }
    
    public function inserirSaborRetornandoo($sabor){
        if($sabor == null){throw new Exception("(Pizza_sabor_model) metodo inserirSaborRetornandoo com parametros nulos");}
        $resp=0;
        $this->db->trans_start();
        $in = $this->db->insert("sabor_pizza",$sabor);
        $resp = $this->db->insert_id();
        $this->db->trans_complete();
        $sabor["codigo_sabor_pizza"] = $resp;
        return $sabor;
    }
    
    public function ultimoIDinserido($codigo){
        if($codigo == null){throw new Exception("(Pizza_sabor_model) metodo ultimoIDinserido com parametros nulos");}
        $this->db->where('pizzaria_sabor_pizza', $codigo);
        return $this->db->insert_id("sabor_pizza");     
    }
    
    public function buscarSaboresEmpresaAtivos($codigo){
        if($codigo == null){throw new Exception("(Pizza_sabor_model) metodo buscarSaboresEmpresaAtivos com parametros nulos");}
        $this->db->where("pizzaria_sabor_pizza", $codigo);
        $this->db->where("ativo_sabor_pizza", 1);
        return $this->db->get("sabor_pizza")->result_array();
    }
    
    public function buscarSaboresEmpresa($codigo){
        if($codigo == null){throw new Exception("(Pizza_sabor_model) metodo buscarSaboresEmpresa com parametros nulos");}
        $this->db->where("pizzaria_sabor_pizza", $codigo);
        $this->db->where("ativo_sabor_pizza", 1);
        return $this->db->get("sabor_pizza")->result_array();
    }
    
    public function buscarSaboresEmpresaAtivoInativo($codigo){
        if($codigo == null){throw new Exception("(Pizza_sabor_model) metodo buscarSaboresEmpresa com parametros nulos");}
        $this->db->where("pizzaria_sabor_pizza", $codigo);
        $this->db->where_in("ativo_sabor_pizza", array(0,1));
        return $this->db->get("sabor_pizza")->result_array();
    }
    
    public function buscarSaborCodigo($codigo){
        if($codigo == null){throw new Exception("(Pizza_sabor_model) metodo buscarSaborCodigo com parametros nulos");}
        $this->db->where("codigo_sabor_pizza", $codigo);
        $this->db->where("ativo_sabor_pizza", 1);
        return $this->db->get("sabor_pizza")->row_array();
    }
    
    public function buscarSaborPorCodigoEpizzaria($codigo, $pizzaria){
        if($codigo == null || $pizzaria == null){throw new Exception("(Pizza_sabor_model) metodo buscarSaborPorCodigoEpizzaria com parametros nulos");}
        $this->db->where("codigo_sabor_pizza", $codigo);
        $this->db->where("pizzaria_sabor_pizza", $pizzaria);
        $this->db->where("ativo_sabor_pizza", 1);
        return $this->db->get("sabor_pizza")->row_array();
    }
    
    public function buscarSaborPorIdAtivoInativo($codigo, $pizzaria){
        if($codigo == null || $pizzaria == null){throw new Exception("(Pizza_sabor_model) metodo buscarSaborPorCodigoEpizzaria com parametros nulos");}
        $this->db->where("codigo_sabor_pizza", $codigo);
        $this->db->where("pizzaria_sabor_pizza", $pizzaria);
        $this->db->where_in("ativo_sabor_pizza", array(0,1));
        return $this->db->get("sabor_pizza")->row_array();
    }
    
    public function buscarSaborPorNome($pizzaria , $nome){
        if($nome == null || $pizzaria == null){throw new Exception("(Pizza_sabor_model) metodo buscarSaborPorNome com parametros nulos");}
        $this->db->where("descricao_sabor_pizza", $nome);
        $this->db->where("pizzaria_sabor_pizza", $pizzaria);
        $this->db->where("ativo_sabor_pizza", 1);
        return $this->db->get("sabor_pizza")->row_array();
    }
    
    public function alterarSabor($sabor, $pizzaria){
        if($sabor == null || $pizzaria == null){throw new Exception("(Pizza_sabor_model) metodo alterarSabor com parametros nulos");}
        $this->db->where('codigo_sabor_pizza', $sabor['codigo_sabor_pizza']);
        $this->db->where("pizzaria_sabor_pizza", $pizzaria);
        $this->db->set($sabor);
        return $this->db->update("sabor_pizza",$sabor);
    }
    
    public function removerSaborPizzaria($codigo, $codgPizzaria){
        if($codigo == null || $codgPizzaria == null){throw new Exception("(Pizza_sabor_model) metodo removerSaborPizzaria com parametros nulos");}
        $this->db->where("codigo_sabor_pizza", $codigo);
        $this->db->where("pizzaria_sabor_pizza", $codgPizzaria);
        $this->db->where_in("ativo_sabor_pizza", array(0,1));
        $sabor = $this->db->get("sabor_pizza")->row_array();
        $sabor["ativo_sabor_pizza"] = 2;
        
        $this->db->where("codigo_sabor_pizza", $sabor["codigo_sabor_pizza"]);
        $this->db->where("pizzaria_sabor_pizza", $sabor["pizzaria_sabor_pizza"]);
        return $this->db->update("sabor_pizza",$sabor);
    }
    
    public function ativarSaborPizzaria($codigo, $codgPizzaria){
        if($codigo == null || $codgPizzaria == null){throw new Exception("(Pizza_sabor_model) metodo removerSaborPizzaria com parametros nulos");}
        $this->db->where("codigo_sabor_pizza", $codigo);
        $this->db->where("pizzaria_sabor_pizza", $codgPizzaria);
        $this->db->where("ativo_sabor_pizza", 0);
        $sabor = $this->db->get("sabor_pizza")->row_array();
        $sabor["ativo_sabor_pizza"] = 1;
        
        $this->db->where("codigo_sabor_pizza", $sabor["codigo_sabor_pizza"]);
        $this->db->where("pizzaria_sabor_pizza", $sabor["pizzaria_sabor_pizza"]);
        return $this->db->update("sabor_pizza",$sabor);
    }
    
    public function inativarSaborPizzaria($codigo, $codgPizzaria){
        if($codigo == null || $codgPizzaria == null){throw new Exception("(Pizza_sabor_model) metodo removerSaborPizzaria com parametros nulos");}
        $this->db->where("codigo_sabor_pizza", $codigo);
        $this->db->where("pizzaria_sabor_pizza", $codgPizzaria);
        $this->db->where("ativo_sabor_pizza", 1);
        $sabor = $this->db->get("sabor_pizza")->row_array();
        $sabor["ativo_sabor_pizza"] = 0;
        
        $this->db->where("codigo_sabor_pizza", $sabor["codigo_sabor_pizza"]);
        $this->db->where("pizzaria_sabor_pizza", $sabor["pizzaria_sabor_pizza"]);
        return $this->db->update("sabor_pizza",$sabor);
    }

    public function buscarSaboresEmpresaComValorAtivos($codigo, $tamanho){
        if($codigo == null || $tamanho == null){throw new Exception("(Pizza_sabor_model) metodo buscarSaboresEmpresaComValorAtivos com parametros nulos");}
        $this->db->where("pizzaria_sabor_pizza", $codigo);
        $this->db->where("ativo_sabor_pizza", 1);
        $this->db->where("ativo_valor_pizza", 1);
        $this->db->where("tamanho_pizza_valor_pizza", $tamanho);
        $this->db->where("pizzaria_valor_pizza", $codigo);
        $this->db->join('valor_pizza', 'valor_pizza.sabor_pizza_valor_pizza = sabor_pizza.codigo_sabor_pizza');
        return $this->db->get("sabor_pizza")->result_array();
    }
    
}
