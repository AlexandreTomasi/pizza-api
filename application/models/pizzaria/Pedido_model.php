<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pedido_model
 *
 * @author 033234581
 */
class Pedido_model extends CI_Model{
    //put your code here
    public function inserirPedido($pedido){
        if($pedido == null){throw new Exception("(Pedido_model) metodo inserirPedido com parametros nulos");}
        return $this->db->insert("pedido",$pedido);     
    }
    
    public function inserirPedidoRetornadoCodigoInserido($pedido){
        if($pedido == null){throw new Exception("(Pedido_model) metodo inserirPedidoRetornadoCodigoInserido com parametros nulos");}
        $resp=0;
        $this->db->trans_start();
        $in = $this->db->insert("pedido",$pedido);  
        $resp = $this->db->insert_id();
        if($this->db->affected_rows() == 0){throw new Exception("(Pedido_model) metodo inserirPedidoRetornadoCodigoInserido não alterou nenhuma linha");}
        $this->db->trans_complete();
        return $resp;
    }
    
    public function buscarPedidosCodgPizzaria($codigo){
        if($codigo == null){throw new Exception("(Pedido_model) metodo buscarPedidosCodgPizzaria com parametros nulos");}
        $this->db->where("pizzaria_pedido", $codigo);
        $this->db->order_by('data_hora_pedido', 'DESC');
        return $this->db->get("pedido")->result_array();
    }
    
    //dias é a quantidade de dias atras que vai ser trazido os dados
    public function buscarPedidosRecentesCodgPizzaria($codigo, $dataInicio, $datafim){
        if($codigo == null || $dataInicio == null || $datafim == null){throw new Exception("(Pedido_model) metodo buscarPedidosRecentesCodgPizzaria com parametros nulos");}
       // $diaP = (String)$dias;
       // $menos = "-".$diaP."days";
       // $condicaoData = date('Y-m-d H:i:s', strtotime($menos));
        //$fp = fopen("log.txt", "a");
        $script = "SELECT * FROM pedido WHERE ";
        $script = $script."pedido.pizzaria_pedido = ".$codigo ;
        $script = $script." and pedido.data_hora_pedido >= '".$dataInicio."'" ;
        $script = $script." and pedido.data_hora_pedido <= '".$datafim."'" ;
            
        $query = $this->db->query($script);
        $resp = array();
        foreach ($query->result() as $row)
        {
            $resp[]=(array)$row;
        }
        
        
       // fclose($fp);        
        return $resp;        
    }
    
    public function buscarPedidosCodgPedido($codigo, $pizzaria){
        if($codigo == null || $pizzaria == null){throw new Exception("(Pedido_model) metodo buscarPedidosCodgPedido com parametros nulos");}
        $this->db->where("codigo_pedido", $codigo);
        $this->db->where("pizzaria_pedido", $pizzaria);
        return $this->db->get("pedido")->row_array();
    }
    
    public function buscarPedidoCodgCliente($pizzaria, $codigo){
        if($codigo == null || $pizzaria == null){throw new Exception("(Pedido_model) metodo buscarPedidoCodgCliente com parametros nulos");}
        $this->db->where("pizzaria_pedido", $pizzaria);
        $this->db->where("cliente_pizzaria_pedido", $codigo);
        return $this->db->get("pedido")->result_array();
    }
    
    public function buscarUltimoPedidoCodgCliente($pizzaria, $codigo){
        if($codigo == null || $pizzaria == null){throw new Exception("(Pedido_model) metodo buscarUltimoPedidoCodgCliente com parametros nulos");}
        $this->db->where("pizzaria_pedido", $pizzaria);
        $this->db->where("cliente_pizzaria_pedido", $codigo);
        $this->db->order_by("codigo_pedido","desc");
        $resp = $this->db->get("pedido")->result_array();
        if($resp != null){         
            return $resp[0];
        }else{
            return null;       
        }
    }
    
    public function alterarPedido($pedido, $pizzaria){
        if($pedido == null || $pizzaria == null){throw new Exception("(Pedido_model) metodo alterarPedido com parametros nulos");}
        $this->db->where('codigo_pedido', $pedido["codigo_pedido"]);
        $this->db->where("pizzaria_pedido", $pizzaria);
        $this->db->set($pedido);
        return $this->db->update("pedido",$pedido);
    }
    
}
