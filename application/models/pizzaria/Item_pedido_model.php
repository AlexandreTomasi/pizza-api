<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Item_pedido_model
 *
 * @author Alexandre
 */
class Item_pedido_model extends CI_Model{
    //put your code here
    public function inserirItemPedido($itemPedido){
        if($itemPedido == null){throw new Exception("(Item_pedido_model) metodo inserirItemPedido com parametros nulos");}
       return $this->db->insert("item_pedido",$itemPedido);     
    }
    
    public function buscarItemPedidoPorPedido($codgPedido){
        if($codgPedido == null){throw new Exception("(Item_pedido_model) metodo buscarItemPedidoPorPedido com parametros nulos");}
        $this->db->where("pedido_item_pedido", $codgPedido);
        return $this->db->get("item_pedido")->result_array();
    }
}
