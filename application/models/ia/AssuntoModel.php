<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AssuntoModel
 *
 * @author 033234581
 */
class AssuntoModel extends CI_Model{
    //put your code here
    public function buscarTodosAssuntos($codgUsuario){
        $ia = $this->load->database('gerencia', true);
        if($codgUsuario == null){throw new Exception("(AssuntoModel) metodo buscarTodosAssuntos com parametros nulos");}
        $ia->where("gerente_ia_assunto", $codgUsuario);
        $ia->where("ativo_ia_assunto", 1);
        return $ia->get("ia_assunto")->result_array();
    }
    
    public function buscarAssuntoPorCodigo($codigoAssunto, $codgUsuario){
        if($codigoAssunto == null || $codgUsuario == null){throw new Exception("(AssuntoModel) metodo buscarAssuntoPorCodigo com parametros nulos");}
        $ia = $this->load->database('gerencia', true);
        $ia->where("codigo_ia_assunto", $codigoAssunto);
        $ia->where("gerente_ia_assunto", $codgUsuario);
        $ia->where("ativo_ia_assunto", 1);
        return $ia->get("ia_assunto")->row_array();
    }
    
    public function incluirAssunto($data){
        $ia = $this->load->database('gerencia', true);
        $ia->trans_start();
        $ia->insert("ia_assunto", $data);
        if($ia->affected_rows() == 0){throw new Exception("(AssuntoModel) metodo incluirAssunto não alterou nenhuma linha");}
        $resp = $ia->insert_id();
        $ia->trans_complete();
        $data["codigo_ia_assunto"] = $resp;
        $data["ativo_ia_assunto"] = 1;
        return $data;
    }
    
    public function removerAssunto($codgUsuario,  $codigoAssunto){
        if($codigoAssunto == null){throw new Exception("(AssuntoModel) metodo removerAssunto com codigoAssunto parametros nulos");}
        if($codgUsuario == null){throw new Exception("(AssuntoModel) metodo removerAssunto com codgUsuario parametros nulos");}
        $ia = $this->load->database('gerencia', true);
        $ia->where("codigo_ia_assunto", $codigoAssunto);
        $ia->where("gerente_ia_assunto", $codgUsuario);
        $ia->where("ativo_ia_assunto", 1);
        $assunto = $ia->get("ia_assunto")->row_array();
        $assunto["ativo_ia_assunto"]=0;
        
        $ia->where("codigo_ia_assunto", $codigoAssunto);
        $ia->where("gerente_ia_assunto", $codgUsuario);
        $resp = $ia->update("ia_assunto",$assunto);
        // atualizando todas as frases associadas ao assunto
        $this->load->model("ia/ConversacaoModel");
        return $this->ConversacaoModel->removerPerguntaERespostaPorAssunto($codgUsuario, $codigoAssunto);
        
    }
    
    public function alterarAssunto($assunto){
        $ia = $this->load->database('gerencia', true);
        $ia->where("codigo_ia_assunto", $assunto["codigo_ia_assunto"]);
        $ia->where("gerente_ia_assunto", $assunto["gerente_ia_assunto"]);
        return $ia->update("ia_assunto",$assunto);
    }
}
