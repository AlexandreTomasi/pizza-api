<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Empresa_model extends CI_Model{
    public function inserirNovaPizzaria($empresa){
        if($empresa == null){
            throw new Exception("(Empresa_model) metodo inserirNovaPizzaria com parametros nulos");
        }
        $resposta = $this->db->insert("pizzaria",$empresa);
        if($this->db->affected_rows() == 0){throw new Exception("(Empresa_model) metodo inserirNovaPizzaria não alterou nenhuma linha");}
        return $resposta;
    }
    
    public function buscaPorEmailESenha($email, $senha){
        if($email == null || $senha == null){
            throw new Exception("(Empresa_model) metodo buscaPorEmailESenha com parametros nulos");
        }
        $this->db->where("email_pizzaria", $email);
        $this->db->where("senha_pizzaria", $senha);
        $pizzaria = $this->db->get("pizzaria")->row_array();
        return $pizzaria;
    }
    
    public function buscaPizzariaPorCodigo($codigo){
        if($codigo == null){
            throw new Exception("(Empresa_model) metodo buscaPizzariaPorCodigo com parametros nulos");
        }
        $this->db->where("codigo_pizzaria", $codigo);
        $pizzaria = $this->db->get("pizzaria")->row_array();
        return $pizzaria;
    }
    
    public function buscaPorCNPJemail($email, $cnpj){
        if($email == null || $cnpj == null){
            throw new Exception("(Empresa_model) metodo buscaPorCNPJemail com parametros nulos");
        }
        $this->db->where("email_pizzaria", $email);
        $this->db->where("cnpj_pizzaria", $cnpj);
        $pizzaria = $this->db->get("pizzaria")->row_array();
        return $pizzaria;
    }
    
    public function alteraCadastroPizzaria($pizzaria){
        if($pizzaria == null){
            throw new Exception("(Empresa_model) metodo alteraCadastroPizzaria com parametros nulos");
        }
        $this->db->where('codigo_pizzaria', $pizzaria['codigo_pizzaria']);
        $this->db->set($pizzaria);
        return $this->db->update("pizzaria",$pizzaria);
    }
    
    public function buscaConfigEmpresa($descConfig, $pizzaria){
        if($descConfig == null || $pizzaria == null){throw new Exception("(Empresa_model) metodo buscaConfigEmpresa com parametros nulos");}
       // call PROCEDURE_retorna_valor_configuracao(1, 'tempo_entrega_pizzaria');
        $this->db->trans_start();
        $query = $this->db->query("call PROCEDURE_retorna_valor_configuracao(".$pizzaria.", '".$descConfig."');");
        $resp = array();
        
        foreach ($query->result() as $row)
        {
            if($resp == null){
                $resp = (array)$row;
            }else{
                throw new Exception("(Empresa_model) metodo buscaConfigEmpresa retornou mais de uma linha");
            }
        }
        $this->db->trans_complete();
       // $this->db->close();
        $this->db->reconnect();
        
        if($resp != null){
            return $resp["descricao_valor_configuracao"];
        }else{
            return "";
        }
    }
}