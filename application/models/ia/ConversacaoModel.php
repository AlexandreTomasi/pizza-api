<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PalavraModel
 *
 * @author 033234581
 */
class ConversacaoModel extends CI_Model{
    //put your code here
    
    public function listarTodasPerguntas($codgUsuario){
        if($codgUsuario == null){throw new Exception("(PerguntasModel) metodo buscarPalavraPorCodigo com parametros nulos");}
        $ia = $this->load->database('gerencia', true);
        $ia->where("gerente_ia_conversacao", $codgUsuario);
        $ia->where("tipo_ia_conversacao", 0);// pergunta
        $ia->where("ativo_ia_conversacao", 1);
        return $ia->get("ia_conversacao")->result_array();
    }
    
    public function removerPerguntaERespostaPorAssunto($codgUsuario,  $codigo){
        if($codigo == null){throw new Exception("(PerguntasModel) metodo removerPalavra com codigo parametros nulos");}
        if($codgUsuario == null){throw new Exception("(PerguntasModel) metodo removerPalavra com codgUsuario parametros nulos");}
        $ia = $this->load->database('gerencia', true);
        //listar todas as frases
        $ia->where("gerente_ia_conversacao", $codgUsuario);
        $ia->where("ia_assunto_ia_conversacao", $codigo);
        $ia->where("ativo_ia_conversacao", 1);
        $lista = $ia->get("ia_conversacao")->result_array();
        for($i=0; $i < count($lista);$i++){
            $this->removerPerguntaOuResposta($codgUsuario,$lista[$i]["codigo_ia_conversacao"]);
        }
        return true;
    }
    
    
    public function buscarPalavraPorCodigo($codigoPalavra, $codgUsuario){
        if($codigoPalavra == null || $codgUsuario == null){throw new Exception("(PerguntasModel) metodo buscarPalavraPorCodigo com parametros nulos");}
        $ia = $this->load->database('gerencia', true);
        $ia->where("codigo_ia_conversacao", $codigoPalavra);
        $ia->where("gerente_ia_conversacao", $codgUsuario);
        $ia->where("ativo_ia_conversacao", 1);
        return $ia->get("ia_conversacao")->row_array();
    }
    
    public function buscarPerguntaPorCodigoUserTipo($codgUsuario, $codigoTipo){
        if($codigoTipo == null || $codgUsuario == null){throw new Exception("(PerguntasModel) metodo buscarPalavraPorCodigo com parametros nulos");}
        $ia = $this->load->database('gerencia', true);
        $ia->where("ia_assunto_ia_conversacao", $codigoTipo);
        $ia->where("gerente_ia_conversacao", $codgUsuario);
        $ia->where("tipo_ia_conversacao", 0);// pergunta
        $ia->where("ativo_ia_conversacao", 1);
        return $ia->get("ia_conversacao")->result_array();
    }
    
    public function incluirPerguntaOuResposta($data){
        $ia = $this->load->database('gerencia', true);
        $ia->trans_start();
        $ia->insert("ia_conversacao", $data);
        if($ia->affected_rows() == 0){throw new Exception("(PerguntasModel) metodo incluirPalavra não alterou nenhuma linha");}
        $resp = $ia->insert_id();
        $ia->trans_complete();
        $data["codigo_ia_conversacao"] = $resp;
        return $data;
    }
    
    public function removerPerguntaOuResposta($codgUsuario,  $codigo){
        if($codigo == null){throw new Exception("(PerguntasModel) metodo removerPalavra com codigo parametros nulos");}
        if($codgUsuario == null){throw new Exception("(PerguntasModel) metodo removerPalavra com codgUsuario parametros nulos");}
        $ia = $this->load->database('gerencia', true);
        $ia->where("codigo_ia_conversacao", $codigo);
        $ia->where("gerente_ia_conversacao", $codgUsuario);
        $palavra = $ia->get("ia_conversacao")->row_array();
        $palavra["ativo_ia_conversacao"] = 0;
        
        $ia->where("codigo_ia_conversacao", $codigo);
        $ia->where("gerente_ia_conversacao", $codgUsuario);
        return $ia->update("ia_conversacao",$palavra);
    }
    
    public function listarRespostaPorCodigoUser($codgUsuario){
        if($codgUsuario == null){throw new Exception("(RespostaModel) metodo buscarRespostaPorCodigoUserTipo com parametros nulos");}
        $ia = $this->load->database('gerencia', true);
        $ia->where("gerente_ia_conversacao", $codgUsuario);
        $ia->where("ativo_ia_conversacao", 1);
        return $ia->get("ia_conversacao")->result_array();
    }
    
    public function buscarRespostaPorCodigoUserTipo($codgUsuario, $codigoTipo){
        if($codigoTipo == null || $codgUsuario == null){throw new Exception("(RespostaModel) metodo buscarRespostaPorCodigoUserTipo com parametros nulos");}
        $ia = $this->load->database('gerencia', true);
        $ia->where("ia_assunto_ia_conversacao", $codigoTipo);
        $ia->where("gerente_ia_conversacao", $codgUsuario);
        $ia->where("tipo_ia_conversacao", 1);// resposta
        $ia->where("ativo_ia_conversacao", 1);
        return $ia->get("ia_conversacao")->result_array();
    }
}
