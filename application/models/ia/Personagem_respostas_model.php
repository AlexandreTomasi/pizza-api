<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Personagem_respostas_model
 *
 * @author 033234581
 */
class Personagem_respostas_model extends CI_Model{
    //put your code here
    public function buscaPorCodigoGerente($codigo){
        if($codigo == null){
            throw new Exception("(Valor_configuracao_model) metodo buscaPorCodigoCliente com parametros nulos");
        }
        $bdgerente = $this->load->database('gerencia', true);
        $bdgerente->where("gerente_personagem_respostas", $codigo);
        $bdgerente->order_by("descricao_personagem_respostas", "asc");
        return $bdgerente->get("personagem_respostas")->result_array();
    }
    
    public function buscaPorCodigoIdentificador($gerente ,$codigo){
        if($codigo == null || $gerente == null){
            throw new Exception("(Valor_configuracao_model) metodo buscaPorCodigoCliente com parametros nulos");
        }
        $bdgerente = $this->load->database('gerencia', true);
        $bdgerente->where("codigo_personagem_respostas", $codigo);
        $bdgerente->where("gerente_personagem_respostas", $gerente);
        return $bdgerente->get("personagem_respostas")->row_array();
    }
    
    public function incluirResposta($data){
        $bdgerente = $this->load->database('gerencia', true);
        $bdgerente->where("codigo_personagem_respostas", $data["codigo_personagem_respostas"]);
        $bdgerente->where("gerente_personagem_respostas", $data["gerente_personagem_respostas"]);
        return $bdgerente->update("personagem_respostas",$data);
    }
    
    public function removeResposta($data){
        $bdgerente = $this->load->database('gerencia', true);
        $bdgerente->where("codigo_personagem_respostas", $data["codigo_personagem_respostas"]);
        $bdgerente->where("gerente_personagem_respostas", $data["gerente_personagem_respostas"]);
        return $bdgerente->update("personagem_respostas",$data);
    }
    
    public function removeGrupo($gerente, $codigo){
        $bdgerente = $this->load->database('gerencia', true);
        $bdgerente->where("codigo_personagem_respostas", $codigo);
        $bdgerente->where("gerente_personagem_respostas", $gerente);
        return $bdgerente->delete("personagem_respostas");
    }
    
    public function inserirNovoGrupo($data){
        $bdgerente = $this->load->database('gerencia', true);
        $bdgerente->trans_start();
        $bdgerente->insert("personagem_respostas", $data);
        if($bdgerente->affected_rows() == 0){throw new Exception("(Personagem_respostas_model) metodo inserirNovoGrupo não alterou nenhuma linha");}
        $resp = $bdgerente->insert_id();
        $bdgerente->trans_complete();
        $data["codigo_personagem_respostas"] = $resp;
        return $data;
    }
    
    public function buscaPorDescricaoParecida($gerente ,$desc){
        if($gerente == null || $desc == null){
            throw new Exception("(Valor_configuracao_model) metodo buscaPorCodigoCliente com parametros nulos");
        }
        $bdgerente = $this->load->database('gerencia', true);
        $bdgerente->like("descricao_personagem_respostas", $desc);
        $bdgerente->where("gerente_personagem_respostas", $gerente);
        $bdgerente->order_by("descricao_personagem_respostas", "asc");
        return $bdgerente->get("personagem_respostas")->result_array();
    }
}
