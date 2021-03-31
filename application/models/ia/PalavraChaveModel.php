<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PalavraChaveModel
 *
 * @author 033234581
 */
class PalavraChaveModel extends CI_Model{
    //put your code here
    public function listarBancos(){
        $banco = array("@tamanhos","@sabores","@tipo extra","@extras","@bebidas");
        return $banco;
    }
    public function buscarPalavraChavePorCodigo($codigoPalavra, $codgUsuario){
        if($codigoPalavra == null || $codgUsuario == null){throw new Exception("(PalavraChaveModel) metodo buscarPalavraChavePorCodigo com parametros nulos");}
        $ia = $this->load->database('gerencia', true);
        $ia->where("codigo_ia_palavras_chave", $codigoPalavra);
        $ia->where("gerente_ia_palavras_chave", $codgUsuario);
        $ia->where("ativo_resposta_ia_palavras_chave", 1);
        return $ia->get("ia_palavras_chave")->row_array();
    }
    
    public function listarPalavraChaves($codgUsuario){
        if($codgUsuario == null){throw new Exception("(PalavraChaveModel) metodo buscarPalavraChavePorCodigo com parametros nulos");}
        $ia = $this->load->database('gerencia', true);
        $ia->where("gerente_ia_palavras_chave", $codgUsuario);
        $ia->where("ativo_resposta_ia_palavras_chave", 1);
        return $ia->get("ia_palavras_chave")->result_array();
    }
    
    public function listarPalavraChavesPorCodigo($codgUsuario){
        if($codgUsuario == null){throw new Exception("(PalavraChaveModel) metodo listarPalavraChavesPorCodigo com parametros nulos");}
        $ia = $this->load->database('gerencia', true);
        $ia->where("gerente_ia_palavras_chave", $codgUsuario);
        $ia->where("ativo_resposta_ia_palavras_chave", 1);
        return $ia->get("ia_palavras_chave")->result_array();
    }
    
    public function buscarPalavraChavePorCodigoUserTipo($codgUsuario, $codigoTipo){
        if($codigoTipo == null || $codgUsuario == null){throw new Exception("(PalavraChaveModel) metodo buscarPalavraChavePorCodigoUserTipo com parametros nulos");}
        $ia = $this->load->database('gerencia', true);
        $ia->where("codigo_ia_assunto", $codigoTipo);
        $ia->where("gerente_ia_palavras_chave", $codgUsuario);
        $ia->where("ativo_resposta_ia_palavras_chave", 1);
        return $ia->get("ia_palavras_chave")->result_array();
    }
    
    public function incluirPalavraChave($data){
        $ia = $this->load->database('gerencia', true);
        $ia->trans_start();
        $ia->insert("ia_palavras_chave", $data);
        if($ia->affected_rows() == 0){throw new Exception("(PalavraChaveModel) metodo incluirPalavraChave não alterou nenhuma linha");}
        $resp = $ia->insert_id();
        $ia->trans_complete();
        $data["codigo_ia_palavras_chave"] = $resp;
        $data["ativo_resposta_ia_palavras_chave"] = 1;
        return $data;
    }
    
    public function removerPalavraChave($codgUsuario,  $codigoPalavra){
        if($codigoPalavra == null){throw new Exception("(PalavraChaveModel) metodo removerPalavraChave com codigoPalavra parametros nulos");}
         if($codgUsuario == null){throw new Exception("(PalavraChaveModel) metodo removerPalavraChave com codgUsuario parametros nulos");}
         $ia = $this->load->database('gerencia', true);
        $ia->where("codigo_ia_palavras_chave", $codigoPalavra);
        $ia->where("gerente_ia_palavras_chave", $codgUsuario);
        return $ia->delete("ia_palavras_chave");
    }
    
    public function alterarPalavraChave($assunto){
        $ia = $this->load->database('gerencia', true);
        $ia->where("codigo_ia_palavras_chave", $assunto["codigo_ia_palavras_chave"]);
        $ia->where("gerente_ia_palavras_chave", $assunto["gerente_ia_palavras_chave"]);
        return $ia->update("ia_palavras_chave",$assunto);
    }
}
