<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Uf_model
 *
 * @author 033234581
 */
class Uf_model extends CI_Model{
    //put your code here
    public function buscaEstadoPorNome($nome){
        $bdgerente = $this->load->database('gerencia', true);
        $bdgerente->where("descricao_uf", $nome);
        return $bdgerente->get("uf")->row_array();
    }
    
    public function buscaEstados(){
        $bdgerente = $this->load->database('gerencia', true);
        return $bdgerente->get('uf')->result_array();
    }
}
