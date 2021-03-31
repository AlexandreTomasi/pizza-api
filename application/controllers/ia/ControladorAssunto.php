<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorAssunto
 *
 * @author 033234581
 */
class ControladorAssunto extends CI_Controller{
    //put your code here
    public function verificaUsuarioLogado(){      
        $usuario = $this->session->userdata("gerente");
        if($usuario == null){
            $this->session->unset_userdata("gerente");
            $this->session->set_flashdata("sucess" , "Sessão Expirada. Por favor logue novamente");
            redirect('/');
        }
    }
    
    public function listarAssuntos(){
        $this->verificaUsuarioLogado();
        try{
            $usuario = $this->session->userdata("gerente");
            $this->load->model("ia/AssuntoModel");
            $resp = $this->AssuntoModel->buscarTodosAssuntos($usuario["codigo_gerente"]);
            $dados = array("dados" => $resp);    
            $this->load->view("ia/ViewAssunto.php",$dados);  
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorAssunto ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }
    
    public function solicitarIncluirAssunto(){
        $this->verificaUsuarioLogado();
        $this->load->model("ia/AssuntoModel");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $descAssunto = $json->{"descricao_ia_assunto"};           
            $usuario = $this->session->userdata("gerente");
            $novo = array(
                "descricao_ia_assunto" => $descAssunto,
                "gerente_ia_assunto" => $usuario["codigo_gerente"],
                "ativo_ia_assunto" => 1
            );
            $assunto = $this->AssuntoModel->incluirAssunto($novo);
            $json_str = json_encode($assunto);
            echo $json_str; 
            
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorAssunto ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }
    
    public function solicitarRemoverAssunto(){
        $this->verificaUsuarioLogado();       
        $this->load->model("ia/AssuntoModel");
        $usuario = $this->session->userdata("gerente");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            if( !($this->AssuntoModel->removerAssunto($usuario["codigo_gerente"], $json->{"codigo_ia_assunto"}) ) ){
                throw new Exception("Erro ao excluir dados.");
            }   
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorAssunto ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function solicitarAlterarAssunto(){
        $this->verificaUsuarioLogado();       
        $this->load->model("ia/AssuntoModel");
        $usuario = $this->session->userdata("gerente");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_ia_assunto"};
            $descricao = $json->{"descricao_ia_assunto"};
            $novo = array(
                "codigo_ia_assunto"=> $codigo,
                "descricao_ia_assunto" => $descricao,
                "gerente_ia_assunto" => $usuario["codigo_gerente"],
                "ativo_ia_assunto" => 1
            );
            
            
            if( !($this->AssuntoModel->alterarAssunto($novo) ) ){
                throw new Exception("Erro ao alterar dados.");
            }   
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorAssunto ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
}
