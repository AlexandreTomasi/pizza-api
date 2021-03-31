<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cidade_model
 *
 * @author 033234581
 */
class Cidade_model extends CI_Model{
    //put your code here
    public function buscaCidadePorNome($nome){
        $bdgerente = $this->load->database('gerencia', true);
        $bdgerente->where("descricao_cidade", $nome);
        return $bdgerente->get("cidade")->row_array();
    }
    
    public function buscaCidadePorCodigo($codigo){
        $bdgerente = $this->load->database('gerencia', true);
        $bdgerente->where("codigo_cidade", $codigo);
        return $bdgerente->get("cidade")->row_array();
    }
    
    public function buscaCidades(){
        $bdgerente = $this->load->database('gerencia', true);
        return $bdgerente->get('cidade')->result_array();
    }
    
}
