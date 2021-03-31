<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tipo_extra_model
 *
 * @author Alexandre
 */
class Tipo_extra_model extends CI_Model{
    //put your code here
    
    public function inserirTipoExtraRetornandoTipo($tipo){
        if($tipo == null ){throw new Exception("(Tipo_extra_model) metodo inserirTipoExtraRetornandoTipo com parametros nulos");}
        $resp=0;
        $this->db->trans_start();
        $in = $this->db->insert("tipo_extra_pizza",$tipo);
        $resp = $this->db->insert_id();
        $this->db->trans_complete();
        $tipo["codigo_tipo_extra_pizza"] = $resp;
        return $tipo;
    }
    
    public function buscarTipoExtras($codigo){
        if($codigo == null ){throw new Exception("(Tipo_extra_model) metodo buscarTipoExtras com parametros nulos");}
        $this->db->where("pizzaria_tipo_extra_pizza", $codigo);
        return $this->db->get("tipo_extra_pizza")->result_array();
    }
    
    public function buscarTipoExtraPizzariaAtivas($codigoPizzaria){
        if($codigoPizzaria == null ){throw new Exception("(Tipo_extra_model) metodo buscarTipoExtraPizzariaAtivas com parametros nulos");}
        $this->db->where("pizzaria_tipo_extra_pizza", $codigoPizzaria);
        $this->db->where("ativo_tipo_extra_pizza", 1);
        return $this->db->get("tipo_extra_pizza")->result_array();
    }
   
    public function buscarTipoExtraPorId($codigoTipo, $codigoPizzaria){
        if($codigoTipo == null || $codigoPizzaria == null){throw new Exception("(Tipo_extra_model) metodo buscarTipoExtraPorId com parametros nulos");}
        $this->db->where("codigo_tipo_extra_pizza", $codigoTipo);
        $this->db->where("pizzaria_tipo_extra_pizza", $codigoPizzaria);
        $this->db->where("ativo_tipo_extra_pizza", 1);
        return $this->db->get("tipo_extra_pizza")->row_array();
    }
    
   /* public function buscarTipoExtraPorIdAtivoInativo($codigoTipo, $codigoPizzaria){
        if($codigoTipo == null || $codigoPizzaria == null){throw new Exception("(Tipo_extra_model) metodo buscarTipoExtraPorId com parametros nulos");}
        $this->db->where("codigo_tipo_extra_pizza", $codigoTipo);
        $this->db->where("pizzaria_tipo_extra_pizza", $codigoPizzaria);
        $this->db->where_in("ativo_tipo_extra_pizza", array(0,1));
        return $this->db->get("tipo_extra_pizza")->row_array();
    }*/
    
    public function alterarTipoExtra($tipo, $codigoPizzaria){
        if($tipo == null || $codigoPizzaria == null){throw new Exception("(Tipo_extra_model) metodo alterarTipoExtra com parametros nulos");}
        $this->db->where('codigo_tipo_extra_pizza', $tipo['codigo_tipo_extra_pizza']);
        $this->db->where("pizzaria_tipo_extra_pizza", $codigoPizzaria);
        $this->db->set($tipo);
        return $this->db->update("tipo_extra_pizza",$tipo);
    }
    
    public function removerTipoExtra($codigo, $codigoPizzaria){
        if($codigo == null || $codigoPizzaria == null){throw new Exception("(Tipo_extra_model) metodo removerTipoExtra com parametros nulos");}
        $this->db->where("codigo_tipo_extra_pizza", $codigo);
        $this->db->where("pizzaria_tipo_extra_pizza", $codigoPizzaria);
        $this->db->where("ativo_tipo_extra_pizza", 1);
        $tipo = $this->db->get("tipo_extra_pizza")->row_array();
        $tipo["ativo_tipo_extra_pizza"]=0;
        
        $this->db->where('codigo_tipo_extra_pizza', $tipo['codigo_tipo_extra_pizza']);
        $this->db->where("pizzaria_tipo_extra_pizza", $tipo["pizzaria_tipo_extra_pizza"]);
        $this->db->set($tipo);
        return $this->db->update("tipo_extra_pizza",$tipo);
    }
}
