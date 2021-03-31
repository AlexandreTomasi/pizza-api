<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of logar
 *
 * @author 033234581
 */
class Logar extends CI_Controller{
    
    public function index()
    {    
        $this->load->model("pizzaria/empresa_model");
        $this->load->model("gerencia/Cliente_pizzaria_model");
        $this->load->helper(array("currency"));
        $empresaLogada = $this->session->userdata("empresa_logada");
        $senha = $this->session->userdata("senhaCliente");
        if($empresaLogada == null){
            $this->load->view("login/ViewLogin.php");          
        }else if($empresaLogada["email_pizzaria"] != null && $senha != null){
            
            $cliente = $this->Cliente_pizzaria_model->buscaPorEmailESenhaMD5($empresaLogada["email_pizzaria"], $senha);
            $pizzaria = null;
            if($cliente != null){
                $pizzaria = $this->empresa_model->buscaPorCNPJemail($empresaLogada["email_pizzaria"], $cliente["cnpj_cliente"]);
            }
            
            if($pizzaria){
                $this->load->model("pizzaria/Pedido_model");
                $empresaLogada = $this->session->userdata("empresa_logada");
                $resp = $this->Pedido_model->buscarPedidosCodgPizzaria($empresaLogada["codigo_pizzaria"]);

                for($i=0; $i<count($resp); $i++){
                    // descrevendo o status
                    if($resp[$i]["status_pedido"] == 0){
                        $resp[$i]["status_pedido"] = "Cancelado";
                    }else if($resp[$i]["status_pedido"] == 1){
                        $resp[$i]["status_pedido"] = "Solicitado";
                    }else if($resp[$i]["status_pedido"] == 2){
                        $resp[$i]["status_pedido"] = "Pedido Atendido";
                    }
                    //colocando nome dos clientes
                    $this->load->model("pizzaria/Cliente_model");                   
                    $cliente = $this->Cliente_model->buscarClientePorCodigo($resp[$i]["cliente_pizzaria_pedido"], $resp[$i]["pizzaria_pedido"]); 
                    $resp[$i]["nome_cliente"] = $cliente["nome_cliente_pizzaria"];

                    $resp[$i]["data_hora_pedido"] = date_format(date_create($resp[$i]["data_hora_pedido"]), 'd-m-Y H:i:s');
                }

                $dados = array("produto" => $resp);    
                $this->load->helper(array("currency"));
                $this->load->view("pizzaria/ViewManterPedido.php", $dados); 
            }else{  
                
                $this->load->view("login/ViewLogin.php");
            }
        }else{   
            $this->load->view("login/ViewLogin.php");
        }
    }
    
    public function autenticar(){
        $this->load->model("pizzaria/empresa_model");
        $this->load->model("gerencia/Cliente_pizzaria_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $senhaCliente = $this->session->userdata("senhaCliente");
            $pizzaria = null;
            $cliente = array();
            if($empresaLogada["email_pizzaria"] != null && $senhaCliente != null){     
                $pizzaria = null;
                $cliente = $this->Cliente_pizzaria_model->buscaPorEmailESenhaMD5($empresaLogada["email_pizzaria"], $senhaCliente);
                if($cliente != null){
                    $pizzaria = $this->empresa_model->buscaPorCNPJemail($empresaLogada["email_pizzaria"], $cliente["cnpj_cliente"]);
                }
            }else{
                $pizzaria = null;
                $email = $this->input->post("email");
                $senha = $this->input->post("senha");
                $cliente = $this->Cliente_pizzaria_model->buscaPorEmailESenha($email, $senha);
                if($cliente != null){
                    $pizzaria = $this->empresa_model->buscaPorCNPJemail($email, $cliente["cnpj_cliente"]);
                }
            }

            if($pizzaria){
                $this->session->set_userdata("empresa_logada" , $pizzaria);
                $this->session->set_userdata("senhaCliente" , $cliente["senha_cliente"]);
                $this->load->helper(array("currency"));
                redirect('ControladorManterPedidos/buscarPedidos', 'refresh');
              //  $this->load->view("pizzaria/ViewManterPedido.php"); 
               // $this->load->view("pizzaria/ViewMenuPizzaria.php");
            }else{
                $this->session->unset_userdata("empresa_logada");
                $this->session->unset_userdata("senhaCliente");
                $this->session->set_flashdata("erro" , "Usuário ou senha inválida.");
                $this->load->view("login/ViewLogin.php");
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function logout(){
        $this->session->unset_userdata("empresa_logada");
        $this->session->unset_userdata("senhaCliente");
        $this->session->set_flashdata("sucess" , "Deslogado com sucesso");
        redirect('../');
    }
    
    public function cadastrar(){
        $this->load->view("pizzaria/ViewCadastrarPizzaria.php");
    }
    
    public function voltarPgInicial(){
        $this->load->view("/");
    }
    
    public function logarGerencia(){
        //link de acesso
        //http://localhost/pizza-api/index.php/Logar/logarGerencia
        $this->load->view("login/ViewLoginGerencia.php");
    }
    
    public function autenticarGerencia(){
        $this->load->model("gerencia/GerenteModel");
        try{
            $email = $this->input->post("email");
            $senha = $this->input->post("senha");
            
            if($this->GerenteModel->verificaExistenciaEmail($email) ){
                $gerente = $this->GerenteModel->buscaPorEmailESenha($email, $senha);
                if($gerente != null){
                   $this->session->set_userdata("gerente" , $gerente);
                   $this->load->helper(array("currency"));                   
                   redirect('ia/ControladorConversa/solicitarPerguntasRespostas', 'refresh');
                }else{
                    $this->session->set_flashdata("erro" , "Usuário ou senha inválida.");
                    $this->load->view("login/ViewLoginGerencia.php");
                }
            }else{
                $this->session->set_flashdata("erro" , "Email invalido.");
                $this->load->view("login/ViewLoginGerencia.php");
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function logoutGerencia(){
        $this->session->unset_userdata("gerente");
        $this->session->set_flashdata("sucess" , "Gerente deslogado com sucesso");
        $this->load->view("login/ViewLoginGerencia.php");
    }
}
