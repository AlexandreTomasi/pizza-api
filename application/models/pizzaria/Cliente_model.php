<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cliente_model
 *
 * @author 033234581
 */
class Cliente_model extends CI_Model{
    
    public function inserirCliente($cliente){
        if($cliente == null){
            throw new Exception("(Cliente_model) metodo inserirCliente com parametros nulos");
        }
        $resultado =  $this->db->insert("cliente_pizzaria",$cliente);    
        if($this->db->affected_rows() == 0){throw new Exception("(Cliente_model) metodo inserirCliente não alterou nenhuma linha");}
        return $resultado;
    }
    
    public function inserirClienteRetornandoCliente($cliente){
        if($cliente == null){
            throw new Exception("(Cliente_model) metodo inserirClienteRetornandoCliente com parametros nulos");
        }
        $resp=0;
        $this->db->trans_start();
        $this->db->insert("cliente_pizzaria",$cliente); 
        if($this->db->affected_rows() == 0){throw new Exception("(Cliente_model) metodo inserirClienteRetornandoCliente não alterou nenhuma linha");}
        $resp = $this->db->insert_id();
        $this->db->trans_complete();
        $cliente["codigo_cliente_pizzaria"] = $resp;
        return $cliente;
    }
    
    public function buscarClientes($codgPizzaria){
        if($codgPizzaria == null){
            throw new Exception("(Cliente_model) metodo buscarClientes com parametros nulos");
        }
        $this->db->where("pizzaria_cliente_pizzaria", $codgPizzaria);
        $this->db->order_by('nome_cliente_pizzaria', 'desc');
        return $this->db->get("cliente_pizzaria")->result_array();
    }
    // busca pelo cliente para verificar se existe no banco de dados ativou ou não
    public function buscarClientesFBid($id, $pizzaria){
        if($id == null){throw new Exception("(Cliente_model) metodo buscarClientesFBid com codigo cliente nulo");}
        if($pizzaria == null){throw new Exception("(Cliente_model) metodo buscarClientesFBid com codigo pizzaria nulo");}
        $this->db->where("id_facebook_cliente_pizzaria", $id);
        $this->db->where("pizzaria_cliente_pizzaria", $pizzaria);
        $this->db->order_by('nome_cliente_pizzaria', 'desc');
        return $this->db->get("cliente_pizzaria")->row_array();
    }
    
    public function buscarClienteCPF($cpf, $codigo){
        if($cpf == null || $codigo == null){
            throw new Exception("(Cliente_model) metodo buscarClienteCPF com parametros nulos");
        }
        $this->db->where("pizzaria_cliente_pizzaria", $codigo);
        $this->db->where("cpf_cliente_pizzaria", $cpf);
        return $this->db->get("cliente_pizzaria")->row_array();
    }
    
    public function buscarClientePorCodigo($codigo, $pizzaria){
        if($pizzaria == null || $codigo == null){
            throw new Exception("(Cliente_model) metodo buscarClientePorCodigo com parametros nulos");
        }
        $this->db->where("codigo_cliente_pizzaria", $codigo);
        $this->db->where("pizzaria_cliente_pizzaria", $pizzaria);
        return $this->db->get("cliente_pizzaria")->row_array();
    }
    
    public function alterarCliente($cliente){
        if($cliente == null){
            throw new Exception("(Cliente_model) metodo alterarCliente com parametros nulos");
        }
        $this->db->where('codigo_cliente_pizzaria', $cliente['codigo_cliente_pizzaria']);
        $this->db->set($cliente);
        $resultado = $this->db->update("cliente_pizzaria",$cliente);
        return $resultado;
    }
    
    public function bloquearCliente($codigo_cliente){
        if($codigo_cliente == null){
            throw new Exception("(Cliente_model) metodo bloquearCliente com parametros nulos");
        }
        $this->db->where("codigo_cliente_pizzaria", $codigo_cliente);
        $this->db->where("ativo_cliente_pizzaria", 1);
        $cliente = $this->db->get("cliente_pizzaria")->row_array();
        
        $cliente["ativo_cliente_pizzaria"]=0;       
        $this->db->where('codigo_cliente_pizzaria', $cliente['codigo_cliente_pizzaria']);
        $this->db->set($cliente);
        $resultado = $this->db->update("cliente_pizzaria",$cliente);
        if($this->db->affected_rows() == 0){throw new Exception("(Cliente_model) metodo bloquearCliente não alterou nenhuma linha");}
        return $resultado;
    }
    
    public function ativarCliente($codigo_cliente){
        if($codigo_cliente == null){
            throw new Exception("(Cliente_model) metodo ativarCliente com parametros nulos");
        }
        $this->db->where("codigo_cliente_pizzaria", $codigo_cliente);
        $this->db->where("ativo_cliente_pizzaria", 0);
        $cliente = $this->db->get("cliente_pizzaria")->row_array();
        
        $cliente["ativo_cliente_pizzaria"]=1;       
        $this->db->where('codigo_cliente_pizzaria', $cliente['codigo_cliente_pizzaria']);
        $this->db->set($cliente);
        $resultado = $this->db->update("cliente_pizzaria",$cliente);
        if($this->db->affected_rows() == 0){throw new Exception("(Cliente_model) metodo ativarCliente não alterou nenhuma linha");}
        return $resultado;
    }
}
