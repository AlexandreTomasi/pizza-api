<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cliente_model
 *
 * @author 033234581
 */
class Cliente_pizzaria_model extends CI_Model{
    //put your code here
    public function buscaPorEmailESenha($email, $senha){
        $bdgerente = $this->load->database('gerencia', true);
        
        $bdgerente->where("email_cliente", $email);
        $bdgerente->where("senha_cliente", md5($senha));
        $cliente = $bdgerente->get("cliente")->row_array();
        return $cliente;
    }
    public function buscaPorEmailESenhaMD5($email, $senha){
        $bdgerente = $this->load->database('gerencia', true);
        
        $bdgerente->where("email_cliente", $email);
        $bdgerente->where("senha_cliente", $senha);
        $cliente = $bdgerente->get("cliente")->row_array();
        return $cliente;
    }
    
    public function buscaPorEmailECNPJ($email, $cnpj){
        if($email == null && $cnpj == null){throw new Exception("(Cliente_pizzaria_model) metodo buscaPorEmailECNPJ com parametros nulos");}
        $bdgerente = $this->load->database('gerencia', true);
        $bdgerente->where("email_cliente", $email);
        $bdgerente->where("cnpj_cliente", $cnpj);
        $cliente = $bdgerente->get("cliente")->row_array();
        return $cliente;
    }
    
    public function alterarClientePizzariaGerencia($cliente){
        $bdgerente = $this->load->database('gerencia', true);
        $cliente["senha_cliente"] = md5($cliente["senha_cliente"]);
        $bdgerente->where("codigo_cliente", $cliente["codigo_cliente"]);
        $bdgerente->where("cnpj_cliente", $cliente["cnpj_cliente"]);       
        $bdgerente->set($cliente);
        return $bdgerente->update("cliente",$cliente);
    }
    
    public function buscarTodosClientesAtivos(){
        $bdgerente = $this->load->database('gerencia', true);
        
        $bdgerente->where("ativo_cliente", 1);
        $clientes = $bdgerente->get("cliente")->result_array();
        return $clientes;
    }
    public function inserirNovoCliente($cliente){
        if($cliente == null){
            throw new Exception("(Cliente_pizzaria_model) metodo inserirNovoCliente com parametros nulos");
        }
        $bdgerente = $this->load->database('gerencia', true);
        $cliente["senha_cliente"] = md5($cliente["senha_cliente"]);
        $resultado = $bdgerente->insert("cliente",$cliente);  
        if($bdgerente->affected_rows() == 0){throw new Exception("(Cliente_pizzaria_model) metodo inserirNovoCliente não alterou nenhuma linha");}
        return $resultado; 
    }
}
