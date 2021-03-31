<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorTaxaEntrega
 *
 * @author 033234581
 */
class ControladorTaxaEntrega extends CI_Controller{
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
    
    public function buscarTaxaEntrega(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Taxa_entrega_model");
        $this->load->model("gerencia/Cidade_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $taxas = $this->Taxa_entrega_model->buscarTaxaEntregaAtivasInativasUniaoBairro($empresaLogada["codigo_pizzaria"]);
            $empresaLogada = $this->session->userdata("empresa_logada");           
            $bairros = $this->Taxa_entrega_model->buscarBairrosSemTaxaEntrega($empresaLogada["codigo_pizzaria"]);  
            $cidades = $this->Cidade_model->buscaCidades();  
            
            for($i=0; $i<count($taxas); $i++){  
                if($taxas[$i]["ativo_taxa_entrega"] == 1){
                    $taxas[$i]["ativo_taxa_entrega"] = "Ativo";
                }else{
                    $taxas[$i]["ativo_taxa_entrega"] = "Inativo";
                } 
            }
            $dados = array("produto" => $taxas, "bairro" =>$bairros, "cidade" => $cidades);    
            $this->load->helper(array("currency"));
            $this->load->view("pizzaria/ViewManterTaxaEntrega.php", $dados); 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    
    public function buscarTaxaEntregaPorID(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Taxa_entrega_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_taxa_entrega"};
            $taxa = $this->Taxa_entrega_model->buscarTaxaEntregaAtivaInativaPorCodigo($codigo, $empresaLogada["codigo_pizzaria"]);   
            $json_str = json_encode($taxa);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function confirmarAlterarTaxaEntrega(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Taxa_entrega_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );

            if($json->{"codigo_taxa_entrega"} != null && $json->{"codigo_taxa_entrega"} > 0){
                $taxa = array(
                    "codigo_taxa_entrega" => $json->{"codigo_taxa_entrega"},
                    "bairro_taxa_entrega" => $json->{"bairro_taxa_entrega"},
                    "preco_taxa_entrega" => doubleval($json->{"preco_taxa_entrega"}),
                    "pizzaria_taxa_entrega" => $empresaLogada["codigo_pizzaria"]
                ); 
                $this->Taxa_entrega_model->alterarTaxaEntrega($taxa);
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
    
    public function incluirTaxaEntrega(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Taxa_entrega_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");        
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $taxa = array(
                    "bairro_taxa_entrega" => $json->{"bairro_taxa_entrega"},
                    "preco_taxa_entrega" => $json->{"preco_taxa_entrega"},
                    "ativo_taxa_entrega" => 1,
                    "pizzaria_taxa_entrega" => $empresaLogada["codigo_pizzaria"]
                    );
            // verifica se ja existe taxa para o bairro.
            $bairros = $this->Taxa_entrega_model->buscarBairrosSemTaxaEntrega($empresaLogada["codigo_pizzaria"]); 
            $taxaResp=array();
            for($i=0; $i<count($bairros); $i++){
                if($bairros[$i]["codigo_bairro"] == $json->{"bairro_taxa_entrega"}){
                    $taxaResp = $this->Taxa_entrega_model->inserirTaxaEntregaRetornandoTaxa($taxa);  
                    $taxaResp = $this->Taxa_entrega_model->buscarTaxaPorCodgAtivaJoinCidadeBairro($taxaResp["codigo_taxa_entrega"], $empresaLogada["codigo_pizzaria"]);
                    if($taxaResp["ativo_taxa_entrega"] == 1){
                        $taxaResp["ativo_taxa_entrega"] = "Ativo";
                    }else{
                        $taxaResp["ativo_taxa_entrega"] = "Inativo";
                    } 
                    $json_str = json_encode($taxaResp);
                    echo $json_str;    
                    break;
                }
            }
            if($taxaResp == null){
                throw new Exception("Bairro ja possui uma taxa.");
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }           
    }
    
    public function confirmarRemoverTaxaEntrega(){
        $this->verificaUsuarioLogado();       
        $this->load->model("pizzaria/Taxa_entrega_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            if( !($this->Taxa_entrega_model->removerTaxaEntrega($json->{"codigo_taxa_entrega"}, $empresaLogada["codigo_pizzaria"] )) ){
                throw new Exception("Erro ao excluir dados.");
            } 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    public function buscaBairrosSemTaxa(){
        $this->verificaUsuarioLogado(); 
        $this->load->model("pizzaria/Taxa_entrega_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $bairros = $this->Taxa_entrega_model->buscarBairrosSemTaxaEntrega($empresaLogada["codigo_pizzaria"]);  
            $json_str = json_encode($bairros);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function buscaBairrosSemTaxaPorCidade(){
        $this->verificaUsuarioLogado(); 
        $this->load->model("pizzaria/Taxa_entrega_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            
            
            $bairros = $this->Taxa_entrega_model->buscarBairrosSemTaxaEntregaPorCidade($empresaLogada["codigo_pizzaria"], $json->{"cidade_bairro"});  
            $json_str = json_encode($bairros);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function ativarTaxa(){
        $this->verificaUsuarioLogado();       
        $this->load->model("pizzaria/Taxa_entrega_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            if( !($this->Taxa_entrega_model->ativarTaxaEntrega($json->{"codigo_taxa_entrega"}, $empresaLogada["codigo_pizzaria"] )) ){
                throw new Exception("Erro ao ativar dados.");
            } 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function inativarTaxa(){
        $this->verificaUsuarioLogado();       
        $this->load->model("pizzaria/Taxa_entrega_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            if( !($this->Taxa_entrega_model->inativarTaxaEntrega($json->{"codigo_taxa_entrega"}, $empresaLogada["codigo_pizzaria"] )) ){
                throw new Exception("Erro ao inativar dados.");
            } 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
}
