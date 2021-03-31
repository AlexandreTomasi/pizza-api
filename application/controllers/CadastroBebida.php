<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cadastroProdutos
 *
 * @author 033234581
 */
class CadastroBebida extends CI_Controller{
    
    public function verificaUsuarioLogado(){      
        $this->load->helper(array("currency"));
        $empresaLogada = $this->session->userdata("empresa_logada");
        if($empresaLogada == null){
            $this->session->unset_userdata("empresa_logada");
            $this->session->set_flashdata("sucess" , "Sessão Expirada. Por favor logue novamente");
            redirect('/');
        }
    }
    
    public function solicitarBuscarBebida(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Bebida_model");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_bebida"};
            $empresaLogada = $this->session->userdata("empresa_logada");
            $bebida = $this->Bebida_model->buscarBebidaPorIdAtivaInativa($codigo);       
            $dados = array("bebida" => $bebida);         
            $json_str = json_encode($bebida);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }
    
    public function solicitarAlterarBebida(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Bebida_model");     
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $bebidas = $this->Bebida_model->buscarBebidasAtivasInativas($empresaLogada["codigo_pizzaria"]);
            for($i=0; $i<count($bebidas); $i++){
                if($bebidas[$i]["ativo_bebida"] == 1){
                    $bebidas[$i]["ativo_bebida"] = "Ativo";
                }else{
                    $bebidas[$i]["ativo_bebida"] = "Inativo";
                }           
            }
            $dados = array("bebidas" => $bebidas);    
            $this->load->helper(array("currency"));
            $this->load->view("pizzaria/ViewManterBebida.php", $dados); 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function confirmarAlterarBebida(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Bebida_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            if($json->{"codigo_bebida"} != null && $json->{"codigo_bebida"} > 0){
                $bebida = array(
                    "codigo_bebida" => $json->{"codigo_bebida"},
                    "descricao_bebida" => $json->{"descricao_bebida"},
                    "preco_bebida" => doubleval($json->{"preco_bebida"}),
                    "pizzaria_bebida" => $empresaLogada["codigo_pizzaria"]
                ); 
                $this->Bebida_model->alterarBebida($bebida);
            }else{
                throw new Exception("Erro ao alterar dados.");
            }  
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function incluirBebida(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Bebida_model");
        $empresaLogado = $this->session->userdata("empresa_logada");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $bebida = array(
                "descricao_bebida" => $json->{"descBebida"},
                "preco_bebida" => $json->{"valorBebida"},
                "pizzaria_bebida" => $empresaLogado["codigo_pizzaria"],
                "ativo_bebida" => 1
            );
            $bebidaResp = $this->Bebida_model->incluirBebidaRetornandoBebida($bebida);
            if($bebidaResp["ativo_bebida"] == 1){
                    $bebidaResp["ativo_bebida"] = "Ativo";
            }else{
                $bebidaResp["ativo_bebida"] = "Inativo";
            }
            $json_str = json_encode($bebidaResp);
            echo $json_str;  
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
  
    public function confirmarRemoverBebida($codigo_bebida){
        $this->verificaUsuarioLogado();       
        $this->load->model("pizzaria/Bebida_model");
        $empresaLogada = $this->session->userdata("empresa_logada");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            if( !($this->Bebida_model->removerBebida($json->{"codigo_bebida"}, $empresaLogada["codigo_pizzaria"])) ){
                throw new Exception("Erro ao excluir dados.");
            }   
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }  
    
    public function ativarBebida(){
        $this->verificaUsuarioLogado();       
        $this->load->model("pizzaria/Bebida_model");
        $empresaLogada = $this->session->userdata("empresa_logada");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            if( !($this->Bebida_model->ativarBebidaPorCodg($json->{"codigo_bebida"}, $empresaLogada["codigo_pizzaria"])) ){
                throw new Exception("Erro ao excluir dados.");
            }   
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }  
    
    public function inativarBebida(){
        $this->verificaUsuarioLogado();       
        $this->load->model("pizzaria/Bebida_model");
        $empresaLogada = $this->session->userdata("empresa_logada");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            if( !($this->Bebida_model->inativarBebidaPorCodg($json->{"codigo_bebida"}, $empresaLogada["codigo_pizzaria"])) ){
                throw new Exception("Erro ao excluir dados.");
            }   
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }  
}
