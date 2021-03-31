<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GerenteModel
 *
 * @author 033234581
 */
class GerenteModel extends CI_Model{
    //put your code here
    
    public function verificaExistenciaEmail($email){
        $bdgerente = $this->load->database('gerencia', true);
        
        $bdgerente->where("email_gerente", $email);
        $cliente = $bdgerente->get("gerente")->row_array();
        if($cliente != null){
            return true;
        }
        return false;
    }
    
    public function buscaPorEmailESenha($email, $senha){
        $bdgerente = $this->load->database('gerencia', true);
        
        $bdgerente->where("email_gerente", $email);
        $bdgerente->where("senha_gerente", md5($senha));
        $cliente = $bdgerente->get("gerente")->row_array();
        return $cliente;
    }
    
    
    public function buscaPorEmailESenhaMD5($email, $senha){
        $bdgerente = $this->load->database('gerencia', true);
        
        $bdgerente->where("email_gerente", $email);
        $bdgerente->where("senha_gerente", $senha);
        $cliente = $bdgerente->get("gerente")->row_array();
        return $cliente;
    }
}
