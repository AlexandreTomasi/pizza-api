<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorManterClientes
 *
 * @author 033234581
 */
class ControladorManterClientes extends CI_Controller{
    //put your code here
    public function verificaUsuarioLogado(){      
        $usuario = $this->session->userdata("gerente");
        if($usuario == null){
            $this->session->unset_userdata("gerente");
            $this->session->set_flashdata("sucess" , "Sessão Expirada. Por favor logue novamente");
            redirect('/');
        }
    }
    public function cadastrarNovaEmpresaBancoGerencia()
    {
        $this->verificaUsuarioLogado();
        $this->load->model("gerencia/Bairro_model");
        $this->load->model("gerencia/Cidade_model");
        $this->load->model("gerencia/Uf_model");
        $this->load->model("pizzaria/Empresa_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada"); 
            $bairros = $this->Bairro_model->buscaBairros();
            $cidade = $this->Cidade_model->buscaCidades();
            $uf = $this->Uf_model->buscaEstados();
            $pizzaria =array("cnpj_cliente" => "",
                "razao_social_cliente" => "",
                "nome_fantasia_cliente" => "",
                "email_cliente" => "",
                "senha_cliente" => "",
                "telefone_cliente" => "",
                "endereco_cliente" => "",
                "perfil_facebook_pizzaria",
                "numero_endereco_cliente" => "",
                "complemento_endereco_cliente" => "",       
                "cep_cliente" => "",
                "cidade_cliente" => "",
                "uf_cliente" => "",
                "bairro_cliente"=> "",
                "token_cliente" => "",
                "bot_id_cliente" => "",
                "bloco_id_cliente" => "",
                "bloco_name_cliente" => "");
            $dados = array("pizzaria" => $pizzaria, "bairro" =>$bairros, "cidade" =>$cidade, "uf" =>$uf);
            $this->load->view("gerencia/ViewCadastraEmpresaGerencia.php",$dados);
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function confirmarNovaEmpresaBancoGerencia()
    {
        $this->verificaUsuarioLogado();
        $this->load->model("gerencia/Cliente_pizzaria_model");
        $this->load->model("pizzaria/Empresa_model");
        $meuPost = file_get_contents("php://input");
        $json = json_decode( $meuPost );
        try{
            if($json->{"nova_senha"} != null && $json->{"confirma_nova_senha"} != null){
                if($json->{"nova_senha"} != $json->{"confirma_nova_senha"}){
                    throw new Exception("Senha não confirmada, a confirmação da nova senha está diferente da nova senha.");
                }
            }
            //echo str_replace("/","",str_replace("-","",str_replace(".","",$json->{"cnpj_cliente"})));
            $cliente = array(
                "cnpj_cliente" =>  str_replace("/","",str_replace("-","",str_replace(".","",$json->{"cnpj_cliente"}))),
                "razao_social_cliente" => $json->{"razao_social_cliente"},
                "nome_fantasia_cliente" => $json->{"nome_fantasia_cliente"},
                "email_cliente" => $json->{"email_cliente"},
                "senha_cliente" => $json->{"nova_senha"},   
                "telefone_cliente" => $json->{"telefone_cliente"},
                "endereco_cliente" => $json->{"endereco_cliente"},
                "numero_endereco_cliente" => $json->{"numero_endereco_cliente"},
                "complemento_endereco_cliente" => $json->{"complemento_endereco_cliente"},       
                "cep_cliente" => str_replace("-","",str_replace(".","",$json->{"cep_cliente"})),
                "cidade_cliente" => $json->{"cidade_cliente"},
                "uf_cliente" => $json->{"uf_cliente"},
                "ativo_cliente"=> 1,
                "bairro_cliente"=> $json->{"bairro_cliente"},
                "token_cliente" => $json->{"token_cliente"},
                "bot_id_cliente" => $json->{"bot_id_cliente"},
                "bloco_id_cliente" => $json->{"bloco_id_cliente"},
                "bloco_name_cliente" => $json->{"bloco_name_cliente"}
            );

            if(!($this->Cliente_pizzaria_model->inserirNovoCliente($cliente))){
                throw new Exception("Erro ao inserir cliente no banco da gerencia.");
            }else{
                    $pizzaria = array(
                            "cnpj_pizzaria" => $json->{"cnpj_cliente"},
                            "razao_social_pizzaria" => $json->{"razao_social_cliente"},
                            "nome_fantasia_pizzaria" => $json->{"nome_fantasia_cliente"},
                            "email_pizzaria" => $json->{"email_cliente"},
                            "perfil_facebook_pizzaria" => $json->{"perfil_facebook_pizzaria"},    
                            "telefone_pizzaria" => $json->{"telefone_cliente"},
                            "cep_pizzaria" => str_replace("-","",str_replace(".","",$json->{"cep_cliente"})),
                            "endereco_pizzaria" => $json->{"endereco_cliente"},
                            "numero_endereco_pizzaria" => $json->{"numero_endereco_cliente"},
                            "complemento_endereco_pizzaria" => $json->{"complemento_endereco_cliente"},  
                            "cidade_pizzaria" => $json->{"cidade_cliente"},
                            "uf_pizzaria" => $json->{"uf_cliente"},
                            "token_facebook_pizzaria" => $json->{"token_cliente"},
                            "data_hora_inclusao_pizzaria" => date('Y-m-d H:i:s'),
                            "bairro_pizzaria"=> $json->{"bairro_cliente"}
                    );
                if(!($this->Empresa_model->inserirNovaPizzaria($pizzaria))){
                    throw new Exception("Erro ao inserir cliente no banco da pizzaria.");
                }
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
}
