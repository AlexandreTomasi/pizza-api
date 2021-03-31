<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Promocao_model
 *
 * @author 033234581
 */
class Promocao_model extends CI_Model{
    
    public function inserirPromocaoRetornandoPromocao($promocao){
        if($promocao == null){throw new Exception("(Promocao_model) metodo inserirPromocaoRetornandoPromocao com parametros nulos");}
        $resp=0;
        $this->db->trans_start();
        $this->db->insert("promocao",$promocao);
        if($this->db->affected_rows() == 0){throw new Exception("(Promocao_model) metodo inserirPromocaoRetornandoPromocao não alterou nenhuma linha");}
        $resp = $this->db->insert_id();
        $this->db->trans_complete();
        $promocao["codigo_promocao"] = $resp;
        return $promocao;
    }
    
    public function buscarPromocao($codigo){
        if($codigo == null){throw new Exception("(Promocao_model) metodo buscarPromocao com parametros nulos");}
        $this->db->where("pizzaria_promocao", $codigo);
        return $this->db->get("promocao")->result_array();
    }
    
    public function buscarPromocaoAtivas($codigoPizzaria){
        if($codigoPizzaria == null){throw new Exception("(Promocao_model) metodo buscarPromocaoAtivas com parametros nulos");}
        $this->db->where("pizzaria_promocao", $codigoPizzaria);
        $this->db->where("ativo_promocao", 1);
        return $this->db->get("promocao")->result_array();
    }
    
    public function buscarPromocaoDescricaoAtivas($descricao, $codigoPizzaria){
        if($descricao == null || $codigoPizzaria == null){throw new Exception("(Promocao_model) metodo buscarPromocaoDescricaoAtivas com parametros nulos");}
        $this->db->where("pizzaria_promocao", $codigoPizzaria);
        $this->db->where("ativo_promocao", 1);
        $this->db->where("descricao_promocao", $descricao);
        return $this->db->get("promocao")->row_array();
    }
    
    public function buscarPromocaoPorId($codigoPromocao, $codgPizzaria){
        if($codigoPromocao == null || $codgPizzaria == null){throw new Exception("(Promocao_model) metodo buscarPromocaoPorId com parametros nulos");}
        $this->db->where("pizzaria_promocao", $codgPizzaria);
        $this->db->where("codigo_promocao", $codigoPromocao);
        return  $this->db->get("promocao")->row_array();
    }
    public function alterarPromocao($promocao){
        if($promocao == null){throw new Exception("(Promocao_model) metodo alterarPromocao com parametros nulos");}
        $this->db->where('codigo_promocao', $promocao['codigo_promocao']);
        $this->db->where("pizzaria_promocao", $promocao["pizzaria_promocao"]);
        $this->db->set($promocao);
        $resultado = $this->db->update("promocao",$promocao);
        return $resultado;
    }
    
    public function removerPromocao($codigoPromocao){
        if($codigoPromocao == null){throw new Exception("(Promocao_model) metodo removerPromocao com parametros nulos");}
        if($codigoPromocao != 1){
            $this->db->where("codigo_promocao", $codigoPromocao);
            $this->db->where("ativo_promocao", 1);
            $promocao = $this->db->get("promocao")->row_array();
            $promocao["ativo_promocao"]=0;

            $this->db->where('codigo_promocao', $promocao['codigo_promocao']);
            $this->db->set($promocao);
            $resultado = $this->db->update("promocao",$promocao);
            if($this->db->affected_rows() == 0){throw new Exception("(Promocao_model) metodo removerPromocao não alterou nenhuma linha");}
            return $resultado;
        }else{
            throw new Exception("Não é possivel remover a forma de pagamento referente a Dinheiro");
        }
    }
}
