<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cadastroEmpresa
 *
 * @author 033234581
 */
class ControladorDadosEmpresa extends CI_Controller{

    public function verificaUsuarioLogado(){      
        $this->load->helper(array("currency"));
        $empresaLogada = $this->session->userdata("empresa_logada");
        $this->load->model("pizzaria/Empresa_model");      
        if($empresaLogada == null){
            $this->session->unset_userdata("empresa_logada");
            $this->session->set_flashdata("sucess" , "Deslogado com sucesso");
            redirect('/');
        }
    }
    
    public function alterarDadosEmpresa()
    {
        $this->verificaUsuarioLogado();
        $this->load->model("gerencia/Bairro_model");
        $this->load->model("gerencia/Cidade_model");
        $this->load->model("gerencia/Uf_model");
        $this->load->model("pizzaria/Empresa_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");        
            $pizzaria = $this->Empresa_model->buscaPizzariaPorCodigo($empresaLogada["codigo_pizzaria"]);
            $bairros = $this->Bairro_model->buscaBairros();
            $cidade = $this->Cidade_model->buscaCidades();
            $uf = $this->Uf_model->buscaEstados();
            $dados = array("pizzaria" => $pizzaria, "bairro" =>$bairros, "cidade" =>$cidade, "uf" =>$uf);
            $this->session->set_userdata("empresa_logada" , $pizzaria);
            $this->load->view("pizzaria/ViewManterDadosEmpresa.php",$dados);
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function confirmarAlterarDadosEmpresa()
    {
        $this->verificaUsuarioLogado();
        $this->load->model("gerencia/Cliente_pizzaria_model");
        $this->load->model("pizzaria/Empresa_model");
        $empresaLogado = $this->session->userdata("empresa_logada");
        $meuPost = file_get_contents("php://input");
        $json = json_decode( $meuPost );
        try{
            if($json->{"senha_cliente"} != null  && $json->{"nova_senha"} != null && $json->{"confirma_nova_senha"} != null){
                $cliente = $this->Cliente_pizzaria_model->buscaPorEmailESenha($json->{"email_pizzaria"}, $json->{"senha_cliente"});
                if($cliente){
                   if(0 == strcmp($json->{"nova_senha"}, $json->{"confirma_nova_senha"}) ){
                       $cliente["senha_cliente"] = $json->{"nova_senha"};
                       if(!$this->Cliente_pizzaria_model->alterarClientePizzariaGerencia($cliente)){
                           $escreve = fwrite($fp, "Erro ao senha.");
                           throw new Exception("Erro ao alterar senha.");
                       }
                   }else{
                        throw new Exception("Erro ao alterar senha.");
                   } 
                }else{               
                    throw new Exception("Erro ao alterar dados. Senha incorreta");
                }
            }
            
           // $pizzaria = $json->{"pizzaria"};
            $pizzariaAlt = array(
                "codigo_pizzaria" => $empresaLogado["codigo_pizzaria"],
                "nome_fantasia_pizzaria" => $json->{"nome_fantasia_pizzaria"},         
                "telefone_pizzaria" => $json->{"telefone_pizzaria"},
                "cep_pizzaria" => str_replace("-","",str_replace(".","",$json->{"cep_pizzaria"})),
                "endereco_pizzaria" => $json->{"endereco_pizzaria"},
                "numero_endereco_pizzaria" => $json->{"numero_endereco_pizzaria"},
                "complemento_endereco_pizzaria" => $json->{"complemento_endereco_pizzaria"},
                "cidade_pizzaria" => $json->{"cidade_pizzaria"},
                "uf_pizzaria" => $json->{"uf_pizzaria"},
                "bairro_pizzaria" => $json->{"bairro_pizzaria"}
            );

            if(!($this->Empresa_model->alteraCadastroPizzaria($pizzariaAlt))){
                throw new Exception("Erro ao alterar dados.");
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    
    public function alterarConfigEmpresa()
    {
        $this->verificaUsuarioLogado();
        $this->load->model("gerencia/Bairro_model");
        $this->load->model("gerencia/Cidade_model");
        $this->load->model("gerencia/Uf_model");
        $this->load->model("gerencia/Valor_configuracao_model");
        $this->load->model("pizzaria/Empresa_model");
        $this->load->model("gerencia/Configuracao");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");        
            $bairros = $this->Bairro_model->buscaBairros();
            $cidade = $this->Cidade_model->buscaCidades();
            $uf = $this->Uf_model->buscaEstados();
            
            $tipoImpressao = $this->Valor_configuracao_model->tipoImpressao();
            
            $configuracao = $this->Configuracao->todasConfiguracoesAlteraveis();
            $configuracoes = array();
            for($i=0;$i < count($configuracao); $i++){
                $temp = $this->Empresa_model->buscaConfigEmpresa($configuracao[$i],$empresaLogada["codigo_pizzaria"]);
                if(is_numeric($temp)){
                    $configuracoes[$configuracao[$i]] = doubleval($this->Empresa_model->buscaConfigEmpresa($configuracao[$i],$empresaLogada["codigo_pizzaria"]));
                }else{
                    $configuracoes[$configuracao[$i]] = $this->Empresa_model->buscaConfigEmpresa($configuracao[$i],$empresaLogada["codigo_pizzaria"]);
                }
            }    
            $dados = array("configuracoes" => $configuracoes, "tipoImpressao" => $tipoImpressao);
            $this->load->view("pizzaria/ViewManterConfigEmpresa.php",$dados);
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    
    public function confirmarAlterarConfigEmpresa()
    {
        $this->verificaUsuarioLogado();
        $this->load->model("gerencia/Cliente_pizzaria_model");
        $this->load->model("pizzaria/Empresa_model");
        $this->load->model("gerencia/Valor_configuracao_model");
        $this->load->model("gerencia/Configuracao");
        $empresaLogada = $this->session->userdata("empresa_logada");
        $meuPost = file_get_contents("php://input");
        $json = json_decode( $meuPost );
        try{
            $senhaCliente = $this->session->userdata("senhaCliente");
            $cliente = $this->Cliente_pizzaria_model->buscaPorEmailESenhaMD5($empresaLogada["email_pizzaria"], $senhaCliente);
            $dadosConfigura = $this->Valor_configuracao_model->buscaPorCodigoCliente($cliente["codigo_cliente"]);
            
            $configuracao = $this->Configuracao->todasConfiguracoesAlteraveis();
            
            for($i=0;$i < count($dadosConfigura); $i++){
                for($a=0;$a < count($configuracao); $a++){
                    if($dadosConfigura[$i]["descricao_configuracao"] == $configuracao[$a]){
                        $temp = array(
                                "codigo_valor_configuracao" => $dadosConfigura[$i]["codigo_valor_configuracao"],
                                "descricao_valor_configuracao" => $json->{$configuracao[$a]},
                                "configuracao_valor_configuracao" => $dadosConfigura[$i]["configuracao_valor_configuracao"],
                                "cliente_valor_configuracao" => $dadosConfigura[$i]["cliente_valor_configuracao"]
                                );
                        $this->Valor_configuracao_model->alterarValorConfiguracao($temp);
                    }
                }
            }
            for($i=0;$i < count($configuracao); $i++){ 
                $status = true;
                for($a=0;$a < count($dadosConfigura); $a++){
                    if($dadosConfigura[$a]["descricao_configuracao"] == $configuracao[$i]){
                        $status = false;
                    }
                }
                if($status){
                    $confg = array(
                        "descricao_valor_configuracao" => $json->{$configuracao[$i]},
                        "configuracao_valor_configuracao" => $this->Configuracao->buscaConfigPorDescricaoReturnCodigo($configuracao[$i]),
                        "cliente_valor_configuracao" => $cliente["codigo_cliente"]
                        );
                    $this->Valor_configuracao_model->insereValorConfiguracao($confg);   
                }
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
}
