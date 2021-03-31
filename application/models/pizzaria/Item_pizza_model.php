<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Item_pizza_model
 *
 * @author Alexandre
 */
class Item_pizza_model extends CI_Model{
    //put your code here
    public function inserirItemPizza($itemPizza){
        if($itemPizza == null){throw new Exception("(Item_pizza_model) metodo inserirItemPizza com parametros nulos");}
        return $this->db->insert("item_pizza",$itemPizza);     
    }
    
    public function buscarItemPizzaPorCodigo($codigo){
        if($codigo == null){throw new Exception("(Item_pizza_model) metodo buscarItemPizzaPorCodigo com parametros nulos");}
        $this->db->where("codigo_item_pizza", $codigo);
        return $this->db->get("item_pizza")->result_array();
    }
    
    public function buscarItemPizzaPorCodigoPizza($codigo){
        if($codigo == null){throw new Exception("(Item_pizza_model) metodo buscarItemPizzaPorCodigoPizza com parametros nulos");}
        $this->db->where("pizza_item_pizza", $codigo);
        return $this->db->get("item_pizza")->result_array();
    }
}
