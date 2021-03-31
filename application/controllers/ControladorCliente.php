<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorCliente
 *
 * @author 033234581
 */
class ControladorCliente extends CI_Controller{
    //put your code here
    public function verificaUsuarioLogado(){      
        $this->load->helper(array("currency"));
        $empresaLogada = $this->session->userdata("empresa_logada");
        if($empresaLogada == null){
            $this->session->unset_userdata("empresa_logada");
            $this->session->set_flashdata("sucess" , "Sessão Expirada. Por favor logue novamente");
            redirect('/');
        }
    }
    public function buscarClientesAtivos(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Cliente_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $resp = $this->Cliente_model->buscarClientes($empresaLogada["codigo_pizzaria"]);

            for($i=0; $i<count($resp); $i++){
                if($resp[$i]["ativo_cliente_pizzaria"] == 1){
                    $resp[$i]["ativo_cliente_pizzaria"] = "Ativo";
                }else{
                    $resp[$i]["ativo_cliente_pizzaria"] = "Bloqueado";
                }           
            }

            $dados = array("produto" => $resp);    
            $this->load->helper(array("currency"));
            $this->load->view("pizzaria/ViewManterClientes.php", $dados); 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function buscarClientesPorCodigo(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Cliente_model");
        $this->load->model("gerencia/Cidade_model");
        $this->load->model("gerencia/Bairro_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_cliente_pizzaria"};
            $cliente = $this->Cliente_model->buscarClientePorCodigo($codigo, $empresaLogada["codigo_pizzaria"]);    
            if($cliente["ativo_cliente_pizzaria"] == 1){
                    $cliente["ativo_cliente_pizzaria"] = "Ativo";
                }else{
                    $cliente["ativo_cliente_pizzaria"] = "Bloqueado";
            }
            
            $cidade = $this->Cidade_model->buscaCidadePorCodigo($cliente["cidade_cliente_pizzaria"]);
            $cliente["cidade_cliente"] = $cidade["descricao_cidade"];

            $bairro = $this->Bairro_model->buscaBairrosPorCodigo($cliente["bairro_cliente_pizzaria"]);
            $cliente["bairro_cliente"] = $bairro["descricao_bairro"];
            if($cliente["complemento_endereco_cliente_pizzaria"] != null && $cliente["complemento_endereco_cliente_pizzaria"] != ""){
                $cliente["endereco_cliente_pizzaria"] = $cliente["endereco_cliente_pizzaria"].". Complemento: ".$cliente["complemento_endereco_cliente_pizzaria"];
            }
            
            $json_str = json_encode($cliente);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function confirmarAlterarClientes(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Cliente_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );

            if($json->{"codigo_cliente_pizzaria"} != null && $json->{"codigo_cliente_pizzaria"} > 0){
                $clienteBanco = $this->Cliente_model->buscarClientePorCodigo($json->{"codigo_cliente_pizzaria"}, $empresaLogada["codigo_pizzaria"]);
                $cliente = array(
                    "codigo_cliente_pizzaria" => $json->{"codigo_cliente_pizzaria"},
                    "cpf_cliente_pizzaria" => ($clienteBanco["cpf_cliente_pizzaria"] != null && $clienteBanco["cpf_cliente_pizzaria"] != "") ? $clienteBanco["cpf_cliente_pizzaria"] : $json->{"cpf_cliente_pizzaria"},
                    "nome_cliente_pizzaria" => $json->{"nome_cliente_pizzaria"},
                    "email_cliente_pizzaria" => $json->{"email_cliente_pizzaria"},
                    "id_facebook_cliente_pizzaria" => $clienteBanco["id_facebook_cliente_pizzaria"],
                    "telefone_cliente_pizzaria" => $json->{"telefone_cliente_pizzaria"},
                    "cep_cliente_pizzaria" => doubleval($json->{"cep_cliente_pizzaria"}),
                    "endereco_cliente_pizzaria" => $json->{"endereco_cliente_pizzaria"},
                    "complemento_endereco_cliente_pizzaria" => $json->{"complemento_endereco_cliente_pizzaria"},
                    "referencia_endereco_cliente_pizzaria" => $json->{"referencia_endereco_cliente_pizzaria"},
                    "pizzaria_cliente_pizzaria" => $empresaLogada["codigo_pizzaria"],
                    "ativo_cliente_pizzaria" => 1,
                    //"cidade_cliente_pizzaria" => $json->{"cidade_cliente_pizzaria"},
                    //"uf_cliente_pizzaria" => $json->{"uf_cliente_pizzaria"},
                    //"sexo_cliente_pizzaria" => $json->{"sexo_cliente_pizzaria"},    
                    //"bairro_cliente_pizzaria" => doubleval($json->{"bairro_cliente_pizzaria"}),

                    "cidade_cliente_pizzaria" => $clienteBanco["cidade_cliente_pizzaria"],
                    "uf_cliente_pizzaria" => $clienteBanco["uf_cliente_pizzaria"],
                    "sexo_cliente_pizzaria" => $clienteBanco["sexo_cliente_pizzaria"],    
                    "bairro_cliente_pizzaria" => $clienteBanco["bairro_cliente_pizzaria"]

                );
                $this->Cliente_model->alterarCliente($cliente);
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
    
    public function incluirClientes(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Cliente_model");
        try{
            $empresaLogado = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $cliente = array(
                    "cpf_cliente_pizzaria" => $json->{"cpf_cliente_pizzaria"},
                    "nome_cliente_pizzaria" => $json->{"nome_cliente_pizzaria"},
                    "email_cliente_pizzaria" => $json->{"email_cliente_pizzaria"},
                    "id_facebook_cliente_pizzaria" => $json->{"id_facebook_cliente_pizzaria"},
                    "telefone_cliente_pizzaria" => $json->{"telefone_cliente_pizzaria"},
                    "cep_cliente_pizzaria" => doubleval($json->{"cep_cliente_pizzaria"}),

                    "endereco_cliente_pizzaria" => $json->{"endereco_cliente_pizzaria"},
                    "complemento_endereco_cliente_pizzaria" => $json->{"complemento_endereco_cliente_pizzaria"},
                    "cidade_cliente_pizzaria" => $json->{"cidade_cliente_pizzaria"},
                    "uf_cliente_pizzaria" => $json->{"uf_cliente_pizzaria"},
                    "sexo_cliente_pizzaria" => $json->{"sexo_cliente_pizzaria"},
                    "referencia_endereco_cliente_pizzaria" => $json->{"referencia_endereco_cliente_pizzaria"},
                    "pizzaria_cliente_pizzaria" => $empresaLogada["codigo_pizzaria"],
                    "bairro_cliente_pizzaria" => $json->{"bairro_cliente_pizzaria"},
                    "ativo_cliente_pizzaria" => 1
                );

            $formaResp = $this->Forma_pagamento_model->inserirFormaPagamentoRetornandoForma($forma);  
            $json_str = json_encode($formaResp);
            echo $json_str; 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function confirmarBloquearClientes(){
        $this->verificaUsuarioLogado();       
        $this->load->model("pizzaria/Cliente_model");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            if( !($this->Cliente_model->bloquearCliente($json->{"codigo_cliente_pizzaria"})) ){
                throw new Exception("Erro ao bloquear cliente.");
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }  
    
    public function confirmarDesbloquearCliente(){
        $this->verificaUsuarioLogado();       
        $this->load->model("pizzaria/Cliente_model");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            if( !($this->Cliente_model->ativarCliente($json->{"codigo_cliente_pizzaria"})) ){
                throw new Exception("Erro ao ativar cliente.");
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }  
}
