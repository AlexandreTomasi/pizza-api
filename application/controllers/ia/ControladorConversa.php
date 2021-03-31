<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorConversa
 *
 * @author 033234581
 */
class ControladorConversa extends CI_Controller{
    //put your code here
    public function verificaUsuarioLogado(){      
        //$this->load->helper(array("currency"));
        $usuario = $this->session->userdata("gerente");
        if($usuario == null){
            $this->session->unset_userdata("gerente");
            $this->session->set_flashdata("sucess" , "Sessão Expirada. Por favor logue novamente");
            redirect('/');
        }
    }
    // mostrar view inicial
    public function solicitarPerguntasRespostas(){
        $this->verificaUsuarioLogado();
        try{
            $usuario = $this->session->userdata("gerente");
            $this->load->model("ia/AssuntoModel");
            $resp = $this->AssuntoModel->buscarTodosAssuntos($usuario["codigo_gerente"]);
            if($usuario != null){
                $dados = array("dados" => $resp);    
                $this->load->view("ia/ViewManterConversa.php",$dados); 
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorConversa ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }


    public function solicitarBuscarPergunta(){
        $this->verificaUsuarioLogado();
        $this->load->model("ia/ConversacaoModel");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigoAssunto = $json->{"assuntoSelecionado"};
            $usuario = $this->session->userdata("gerente");
            
            $palavras = $this->ConversacaoModel->buscarPerguntaPorCodigoUserTipo($usuario["codigo_gerente"], $codigoAssunto);       
           // $dados = array("palavras" => $palavras);         
            $json_str = json_encode($palavras);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorConversa ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }
    
    public function solicitarBuscarResposta(){
        $this->verificaUsuarioLogado();
        $this->load->model("ia/ConversacaoModel");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigoAssunto = $json->{"assuntoSelecionado"};
            $usuario = $this->session->userdata("gerente");
            
            $respostas = $this->ConversacaoModel->buscarRespostaPorCodigoUserTipo($usuario["codigo_gerente"], $codigoAssunto);       
           // $dados = array("palavras" => $palavras);         
            $json_str = json_encode($respostas);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorConversa ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }
    
    public function solicitarIncluirPergunta(){
        $this->verificaUsuarioLogado();
        $this->load->model("ia/ConversacaoModel");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigoTipo = $json->{"assuntoSelecionado"};
            $descPalavra = $json->{"novaPalavra"};           
            $usuario = $this->session->userdata("gerente");
            $novo = array(
                "descricao_ia_conversacao" => $descPalavra,
                "gerente_ia_conversacao" => $usuario["codigo_gerente"],
                "tipo_ia_conversacao" => 0,
                "ia_assunto_ia_conversacao" => $codigoTipo,
                "ativo_ia_conversacao" => 1
            );
            $palavra = $this->ConversacaoModel->incluirPerguntaOuResposta($novo);
            $json_str = json_encode($palavra);
            echo $json_str; 
            
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorConversa ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }
    
    public function solicitarIncluirResposta(){
        $this->verificaUsuarioLogado();
        $this->load->model("ia/ConversacaoModel");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigoTipo = $json->{"assuntoSelecionado"};
            $descResposta = $json->{"texto_resposta"};           
            $usuario = $this->session->userdata("gerente");
            $novo = array(
                "descricao_ia_conversacao" => $descResposta,
                "gerente_ia_conversacao" => $usuario["codigo_gerente"],
                "tipo_ia_conversacao" => 1,
                "ia_assunto_ia_conversacao" => $codigoTipo,
                "ativo_ia_conversacao" => 1
            );
            $resposta = $this->ConversacaoModel->incluirPerguntaOuResposta($novo);
            $json_str = json_encode($resposta);
            echo $json_str; 
            
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorConversa ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }
    
    public function solicitarRemoverPerguntaOuResposta(){
        $this->verificaUsuarioLogado();
        $this->load->model("ia/ConversacaoModel");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_ia_conversacao"};       
            $usuario = $this->session->userdata("gerente");
            
            $palavra = $this->ConversacaoModel->removerPerguntaOuResposta($usuario["codigo_gerente"], $codigo);
            $json_str = json_encode($palavra);
            echo $json_str; 
            
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorConversa ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }
    
    public function solicitarResposta(){
        $this->verificaUsuarioLogado();
        $this->load->model("ia/AnalisadorPergunta");
        $usuario = $this->session->userdata("gerente");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $pergunta = $json->{"pergunta"};  
            $resp = $this->AnalisadorPergunta->analisador($pergunta, $usuario["codigo_gerente"], 1);
            if(strnatcasecmp($resp,"null") == 0){
                $resp = "";
            }
            echo $resp; 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorConversa ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }
}
