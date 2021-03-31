<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Item_extra_pizza_model
 *
 * @author 033234581
 */
class Item_extra_pizza_model extends CI_Model{
    public function incluirItemExtraPizza($itemExtra){
        if($itemExtra == null){throw new Exception("(Item_extra_pizza_model) metodo incluirItemExtraPizza com parametros nulos");}
        return $this->db->insert("item_extra_pizza",$itemExtra);
    }
    
    public function buscaItemExtraPizzaPorCodigoPizza($codgPizza){
        if($codgPizza == null){throw new Exception("(Item_extra_pizza_model) metodo buscaItemExtraPizzaPorCodigoPizza com parametros nulos");}
        $this->db->where("pizza_item_extra_pizza", $codgPizza);
        return $this->db->get("item_extra_pizza")->result_array();
    }
}
