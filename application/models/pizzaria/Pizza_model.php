<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pizza_model
 *
 * @author 033234581
 */
class Pizza_model extends CI_Model{
    //put your code here
    public function incluirPizzaRetornandoCodigo($pizza){
        if($pizza == null){throw new Exception("(Pizza_model) metodo incluirPizzaRetornandoCodigo com parametros nulos");}
        $resp=0;
        $this->db->trans_start();
        $in = $this->db->insert("pizza",$pizza);
        $resp = $this->db->insert_id();
        $this->db->trans_complete();
        return $resp;
    }
    
    public function incluirPizza($pizza){
        if($pizza == null){throw new Exception("(Pizza_model) metodo incluirPizza com parametros nulos");}
        return $this->db->insert("pizza",$pizza);
    }
    
    public function buscaPizzaPorCodigo($codgPizza){
        if($codgPizza == null){throw new Exception("(Pizza_model) metodo buscaPizzaPorCodigo com parametros nulos");}
        $this->db->where("codigo_pizza", $codgPizza);
        return $this->db->get("pizza")->row_array();
    }
}
