<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorPalavraChave
 *
 * @author 033234581
 */
class ControladorPalavraChave extends CI_Controller{
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
    
    public function listarPalavraChaves(){
        $this->verificaUsuarioLogado(); 
        try{
            $usuario = $this->session->userdata("gerente");
            $this->load->model("ia/PalavraChaveModel");
            $resp = $this->PalavraChaveModel->listarPalavraChavesPorCodigo($usuario["codigo_gerente"]);

            $bancos = $this->PalavraChaveModel->listarBancos();

            $dados = array("dados" => $resp, "bancos" => $bancos);    
            $this->load->view("ia/ViewPalavraChaves.php",$dados);  
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorPalavraChave ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }
    
    public function solicitarIncluirPalavraChave(){
        $this->verificaUsuarioLogado();
        $this->load->model("ia/PalavraChaveModel");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $descPalavra = $json->{"nome_ia_palavras_chave"};           
            $usuario = $this->session->userdata("gerente");
            $novo = array(
                "nome_ia_palavras_chave" => $descPalavra,
                "gerente_ia_palavras_chave" => $usuario["codigo_gerente"],
                "ativo_resposta_ia_palavras_chave" => 1
            );
            $pala = $this->PalavraChaveModel->incluirPalavraChave($novo);
            $json_str = json_encode($pala);
            echo $json_str; 
            
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorPalavraChave ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }
    
    public function solicitarRemoverPalavraChave(){
        $this->verificaUsuarioLogado();       
        $this->load->model("ia/PalavraChaveModel");
        $usuario = $this->session->userdata("gerente");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            if( !($this->PalavraChaveModel->removerPalavraChave($usuario["codigo_gerente"], $json->{"codigo_ia_palavras_chave"}) ) ){
                throw new Exception("Erro ao excluir dados.");
            }   
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorPalavraChave ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function solicitarAlterarPalavraChave(){
        $this->verificaUsuarioLogado();       
        $this->load->model("ia/PalavraChaveModel");
        $usuario = $this->session->userdata("gerente");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_ia_palavras_chave"};
            $descricao = $json->{"nome_ia_palavras_chave"};
            $novo = array(
                "codigo_ia_palavras_chave"=> $codigo,
                "nome_ia_palavras_chave" => $descricao,
                "gerente_ia_palavras_chave" => $usuario["codigo_gerente"],
                "ativo_resposta_ia_palavras_chave" => 1
            );
            
            
            if( !($this->PalavraChaveModel->alterarPalavraChave($novo) ) ){
                throw new Exception("Erro ao alterar dados.");
            }   
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorPalavraChave ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function solicitarAlterarPalavraChaveBanco(){
        $this->verificaUsuarioLogado();       
        $this->load->model("ia/PalavraChaveModel");
        $usuario = $this->session->userdata("gerente");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_ia_palavras_chave"};
            $descricao = $json->{"resposta_ia_palavras_chave"};
            $palavra = $this->PalavraChaveModel->buscarPalavraChavePorCodigo($codigo, $usuario["codigo_gerente"]);
            $palavra["resposta_ia_palavras_chave"]=$descricao;
            
            
            if( !($this->PalavraChaveModel->alterarPalavraChave($palavra) ) ){
                throw new Exception("Erro ao alterar dados.");
            }   
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorPalavraChave ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
}
