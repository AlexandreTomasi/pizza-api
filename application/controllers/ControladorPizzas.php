<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cadastroPizza
 *
 * @author 033234581
 */
class ControladorPizzas extends CI_Controller{  
    
    public function verificaUsuarioLogado(){      
        $this->load->helper(array("currency"));
        $empresaLogada = $this->session->userdata("empresa_logada");
        if($empresaLogada == null){
            $this->session->unset_userdata("empresa_logada");
            $this->session->set_flashdata("sucess" , "Deslogado com sucesso");
            redirect('/');
        }
    }
// controlador de tipo extra    @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    public function buscarTipoExtras(){
        $this->verificaUsuarioLogado();
        $empresaLogada = $this->session->userdata("empresa_logada");
        $this->load->model("pizzaria/Tipo_extra_model");
        try{
            $tipos = $this->Tipo_extra_model->buscarTipoExtraPizzariaAtivas($empresaLogada['codigo_pizzaria']);

            $dados = array("tipoExtra" => $tipos);
            $this->load->helper(array("currency"));
            $this->load->view("pizzaria/ViewManterTipoExtra.php", $dados); 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    } 
    
    public function buscarTipoExtrasPorCodigo(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Tipo_extra_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_tipo_extra_pizza"};
            $tipo = $this->Tipo_extra_model->buscarTipoExtraPorId($codigo, $empresaLogada['codigo_pizzaria']);
            $json_str = json_encode($tipo);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function confirmarAlterarTipoExtra(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Tipo_extra_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );

            if($json->{"codigo_tipo_extra_pizza"} != null && $json->{"codigo_tipo_extra_pizza"} > 0){
                $tipo = array(
                    "codigo_tipo_extra_pizza" => $json->{"codigo_tipo_extra_pizza"},
                    "quantidade_tipo_extra_pizza" => $json->{"quantidade_tipo_extra_pizza"},
                    "descricao_tipo_extra_pizza" => $json->{"descricao_tipo_extra_pizza"},                
                    "pizzaria_tipo_extra_pizza" => $empresaLogada["codigo_pizzaria"],
                    "ativo_tipo_extra_pizza" => 1
                ); 
                $this->Tipo_extra_model->alterarTipoExtra($tipo, $empresaLogada['codigo_pizzaria']);
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
    
    public function confirmarRemoverTipoExtra(){
        $this->verificaUsuarioLogado();       
        $this->load->model("pizzaria/Tipo_extra_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            if( !($this->Tipo_extra_model->removerTipoExtra($json->{"codigo_tipo_extra_pizza"}, $empresaLogada["codigo_pizzaria"] )) ){
                throw new Exception("Erro ao excluir dados.");
            }  
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function incluirTipoExtra(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Tipo_extra_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");        
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $tipo = array(
                    "quantidade_tipo_extra_pizza" => $json->{"quantidade_tipo_extra_pizza"},
                    "descricao_tipo_extra_pizza" => $json->{"descricao_tipo_extra_pizza"},                
                    "pizzaria_tipo_extra_pizza" => $empresaLogada["codigo_pizzaria"],
                    "ativo_tipo_extra_pizza" => 1
                    );

            $tipo = $this->Tipo_extra_model->inserirTipoExtraRetornandoTipo($tipo);  
            $json_str = json_encode($tipo);
            echo $json_str;  
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        } 
    }
    
    
// controlador de extra para pizza @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    
    public function buscarTodosExtras(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Tipo_extra_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $extras = $this->Pizza_extra_model->buscarTodosExtrasDaEmpresaAtivosInativos($empresaLogada["codigo_pizzaria"]);
            $tipos = $this->Tipo_extra_model->buscarTipoExtraPizzariaAtivas($empresaLogada['codigo_pizzaria']);
            $tamanho = $this->Pizza_tamanho_model->buscarTamanhos($empresaLogada['codigo_pizzaria']);
            for($i=0; $i<count($extras); $i++){
                $extras[$i]["tipo_extra_pizza_extra_pizza"] = $extras[$i]["descricao_tipo_extra_pizza"];
                $extras[$i]["tamanho_pizza_extra_pizza"] = $extras[$i]["descricao_tamanho_pizza"];
                
                if($extras[$i]["ativo_extra_pizza"] == 1){
                    $extras[$i]["ativo_extra_pizza"] = "Ativo";
                }else{
                    $extras[$i]["ativo_extra_pizza"] = "Inativo";
                } 
            }
            
            $dados = array("extra" => $extras,"tipos" => $tipos, "tamanho" =>$tamanho);      
            $this->load->helper(array("currency"));
            $this->load->view("pizzaria/ViewManterAdicionalExtra.php", $dados); 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function buscarExtraPorCodigo(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Tipo_extra_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $extra = $this->Pizza_extra_model->buscarExtraIdAtivoInativo($json->{"codigo_extra_pizza"}, $empresaLogada["codigo_pizzaria"]);
            $json_str = json_encode($extra);
            echo $json_str;  
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    } 
    
    public function confirmarAlterarExtra(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_extra_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $extra = array(
                "codigo_extra_pizza" => $json->{"codigo_extra_pizza"},           
                "descricao_extra_pizza" => $json->{"descricao_extra_pizza"},
                "pizzaria_extra_pizza" => $empresaLogada["codigo_pizzaria"],
                "preco_extra_pizza" => $json->{"preco_extra_pizza"}
            );
            if(!($this->Pizza_extra_model->alterarExtra($extra))){
                throw new Exception("Erro ao alterar dados.");
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    
    public function confirmarRemoverExtra(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_extra_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $this->Pizza_extra_model->removerExtra($json->{"codigo_extra_pizza"}, $empresaLogada["codigo_pizzaria"]);
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function ativarExtra(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_extra_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $this->Pizza_extra_model->ativarExtraPorCodg($json->{"codigo_extra_pizza"}, $empresaLogada["codigo_pizzaria"]);
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function inativarExtra(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_extra_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $this->Pizza_extra_model->inativarExtraPorCodg($json->{"codigo_extra_pizza"}, $empresaLogada["codigo_pizzaria"]);
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function incluirNovoExtraRetornandoo(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Tipo_extra_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost , true);
            $valores = $json["contents"];
            $extrasResposta = array();
            for($i=0; $i<count($valores); $i++){
                if(doubleval($valores[$i]["value"]) > 0){
                    $existe = 0;
                    $extra = array(         
                        "descricao_extra_pizza" => $json["descricao_extra_pizza"],
                        "pizzaria_extra_pizza" => $empresaLogada["codigo_pizzaria"],
                        "preco_extra_pizza" => doubleval($valores[$i]["value"]), 
                        "ativo_extra_pizza" => 1,
                        "tipo_extra_pizza_extra_pizza" => $json["tipo_extra_pizza_extra_pizza"],    
                        "tamanho_pizza_extra_pizza" => $valores[$i]["tamanho"]
                    );

                    $extra = $this->Pizza_extra_model->inserirExtra($extra);
                    
                    $tipo = $this->Tipo_extra_model->buscarTipoExtraPorId($extra["tipo_extra_pizza_extra_pizza"], $empresaLogada['codigo_pizzaria']);
                    $extra["tipo_extra_pizza_extra_pizza"] = $tipo["descricao_tipo_extra_pizza"];
                    $tamanho = $this->Pizza_tamanho_model->buscarTamanhoPorCodigoEpizzaria($extra["tamanho_pizza_extra_pizza"], $empresaLogada['codigo_pizzaria']);
                    $extra["tamanho_pizza_extra_pizza"] = $tamanho["descricao_tamanho_pizza"];
                    if($extra["ativo_extra_pizza"] == 1){
                        $extra["ativo_extra_pizza"] = "Ativo";
                    }else{
                        $extra["ativo_extra_pizza"] = "Inativo";
                    }
                    
                    $extrasResposta[] = $extra;
                }
            }
            $json_str = json_encode($extrasResposta);
            echo $json_str; 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    
    
// Tamanho @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@   
    
    public function buscarTodosTamanhos(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_tamanho_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $tamanhos = $this->Pizza_tamanho_model->buscarTamanhosAtivosInativos($empresaLogada["codigo_pizzaria"]);
            
            for($i=0; $i<count($tamanhos); $i++){
                if($tamanhos[$i]["ativo_tamanho_pizza"] == 1){
                    $tamanhos[$i]["ativo_tamanho_pizza"] = "Ativo";
                }else{
                    $tamanhos[$i]["ativo_tamanho_pizza"] = "Inativo";
                }           
            }
            
            $dados = array("tamanhos" => $tamanhos);    
            $this->load->helper(array("currency"));
            $this->load->view("pizzaria/ViewManterTamanho.php", $dados);
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
     
    public function incluirTamanho()
    {
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_tamanho_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );

            $tamanho = array(
                "descricao_tamanho_pizza" => $json->{"descricao_tamanho_pizza"},
                "quantidade_sabor_tamanho_pizza" => intval($json->{"quantidade_sabor_tamanho_pizza"}),
                "pizzaria_tamanho_pizza" => $empresaLogada["codigo_pizzaria"],
                "quantidade_fatias_tamanho_pizza" => intval($json->{"quantidade_fatias_tamanho_pizza"}),
                "ativo_tamanho_pizza" => 1        
            );
            if($tamanho["ativo_tamanho_pizza"] == 1){
                $tamanho["ativo_tamanho_pizza"] = "Ativo";
            }else{
                $tamanho["ativo_tamanho_pizza"] = "Inativo";
            } 
            $tamanho = $this->Pizza_tamanho_model->inserirTamanhoRetornandoo($tamanho);
            $json_str = json_encode($tamanho);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function removerTamanho(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Valor_pizza_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );       
            // remove tamanho na tabela de tamanho
            if(!($this->Pizza_tamanho_model->removerTamanhoPorCodigo($json->{"codigo_tamanho_pizza"}, $empresaLogada["codigo_pizzaria"])) ){
                throw new Exception("Erro ao remover dados.");
            }
            // remover valores na tabela de valor pizza que tem esse tamanho ativo ou inativo
            $valores = $this->Valor_pizza_model->buscarValorPizzaAtivoInativoPorTamanho($empresaLogada["codigo_pizzaria"], $json->{"codigo_tamanho_pizza"});
            for($i=0; $i < count($valores); $i++){
               if(!($this->Valor_pizza_model->removerValorPizza($valores[$i]["codigo_valor_pizza"], $empresaLogada["codigo_pizzaria"]) )){
                   throw new Exception("Erro ao remover dados dos valores.");
               }
            }
            
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
      
    public function buscarTamanhoPorCodigo(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_tamanho_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );        
            $tamanho = $this->Pizza_tamanho_model->buscarTamanhoPorCodigoAtivoInativo($json->{"codigo_tamanho_pizza"}, $empresaLogada["codigo_pizzaria"]);
            $json_str = json_encode($tamanho);
            echo $json_str; 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    } 
    
    public function alterarTamanho(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_tamanho_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost ); 

            $tamanho = array(
                "codigo_tamanho_pizza" => $json->{"codigo_tamanho_pizza"},
                "descricao_tamanho_pizza" => $json->{"descricao_tamanho_pizza"},
                "quantidade_sabor_tamanho_pizza" => intval($json->{"quantidade_sabor_tamanho_pizza"}),
                "pizzaria_tamanho_pizza" => $empresaLogada["codigo_pizzaria"],
                "quantidade_fatias_tamanho_pizza" => intval($json->{"quantidade_fatias_tamanho_pizza"})  
            );
            if(!($this->Pizza_tamanho_model->alterarTamanho($tamanho))){
                throw new Exception("Erro ao alterar dados.");
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function ativarTamanho(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Valor_pizza_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );       
            // remove tamanho na tabela de tamanho
            if(!($this->Pizza_tamanho_model->ativarTamanhoPorCodigo($json->{"codigo_tamanho_pizza"}, $empresaLogada["codigo_pizzaria"])) ){
                throw new Exception("Erro ao ativar dados.");
            }
            // remover valores na tabela de valor pizza que tem esse tamanho ativo ou inativo
            $valores = $this->Valor_pizza_model->buscarValorPizzaAtivoInativoPorTamanho($empresaLogada["codigo_pizzaria"], $json->{"codigo_tamanho_pizza"});
            for($i=0; $i < count($valores); $i++){
               if(!($this->Valor_pizza_model->ativarValorPizza($valores[$i]["codigo_valor_pizza"], $empresaLogada["codigo_pizzaria"]) )){
                   throw new Exception("Erro ao ativar dados dos valores.");
               }
            }
            
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function inativarTamanho(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Valor_pizza_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );       
            // remove tamanho na tabela de tamanho
            if(!($this->Pizza_tamanho_model->inativarTamanhoPorCodigo($json->{"codigo_tamanho_pizza"}, $empresaLogada["codigo_pizzaria"])) ){
                throw new Exception("Erro ao inativar dados.");
            }
            // remover valores na tabela de valor pizza que tem esse tamanho ativo ou inativo
            $valores = $this->Valor_pizza_model->buscarValorPizzaAtivoInativoPorTamanho($empresaLogada["codigo_pizzaria"], $json->{"codigo_tamanho_pizza"});
            for($i=0; $i < count($valores); $i++){
               if(!($this->Valor_pizza_model->inativarValorPizza($valores[$i]["codigo_valor_pizza"], $empresaLogada["codigo_pizzaria"]) )){
                   throw new Exception("Erro ao inativar dados dos valores.");
               }
            }
            
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    
//Fim tamanho @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//Sabores      @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    
    public function buscarTodosSabores(){
        $this->verificaUsuarioLogado();
        $empresaLogada = $this->session->userdata("empresa_logada");
        $this->load->model("pizzaria/Pizza_sabor_model");
        try{
            $sabores = $this->Pizza_sabor_model->buscarSaboresEmpresaAtivoInativo($empresaLogada['codigo_pizzaria']);
            
            for($i=0; $i<count($sabores); $i++){
                if($sabores[$i]["ativo_sabor_pizza"] == 1){
                    $sabores[$i]["ativo_sabor_pizza"] = "Ativo";
                }else{
                    $sabores[$i]["ativo_sabor_pizza"] = "Inativo";
                }           
            }
            
            $dados = array("sabores" => $sabores);      
            $this->load->helper(array("currency"));
            $this->load->view("pizzaria/ViewManterSabores.php", $dados);
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    } 
    
    public function incluirSabor()
    {
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_sabor_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost ); 

            $Sabor = array(
                "descricao_sabor_pizza" => $json->{"descricao_sabor_pizza"},
                "ingredientes_sabor_pizza" => $json->{"ingredientes_sabor_pizza"},
                "pizzaria_sabor_pizza" => $empresaLogada["codigo_pizzaria"],
                "ativo_sabor_pizza" => 1
            );
            $Sabor = $this->Pizza_sabor_model->inserirSaborRetornandoo($Sabor); 
            
            if($Sabor["ativo_sabor_pizza"] == 1){
                $Sabor["ativo_sabor_pizza"] = "Ativo";
            }else{
                $Sabor["ativo_sabor_pizza"] = "Inativo";
            }  
            $json_str = json_encode($Sabor);
            echo $json_str; 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function buscarSaborPorCodigo(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_sabor_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );  

            $Sabor = $this->Pizza_sabor_model->buscarSaborPorIdAtivoInativo($json->{"codigo_sabor_pizza"}, $empresaLogada["codigo_pizzaria"]);
            $json_str = json_encode($Sabor);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    } 
    
    public function alterarSaborPizzaria(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_sabor_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );

            $Sabor = array(
                "codigo_sabor_pizza" => $json->{"codigo_sabor_pizza"},
                "descricao_sabor_pizza" => $json->{"descricao_sabor_pizza"},
                "ingredientes_sabor_pizza" => $json->{"ingredientes_sabor_pizza"},
                "pizzaria_sabor_pizza" => $empresaLogada["codigo_pizzaria"]
            );

            if(!($this->Pizza_sabor_model->alterarSabor($Sabor, $empresaLogada["codigo_pizzaria"]))){
                throw new Exception("Erro ao alterar dados.");
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function confirmarRemoverSabor(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_sabor_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );    

            if(!($this->Pizza_sabor_model->removerSaborPizzaria($json->{"codigo_sabor_pizza"}, $empresaLogada["codigo_pizzaria"]))){
                throw new Exception("Erro ao remover dados.");
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function ativarSabor(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_sabor_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );    

            if(!($this->Pizza_sabor_model->ativarSaborPizzaria($json->{"codigo_sabor_pizza"}, $empresaLogada["codigo_pizzaria"]))){
                throw new Exception("Erro ao remover dados.");
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function inativarSabor(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pizza_sabor_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );    

            if(!($this->Pizza_sabor_model->inativarSaborPizzaria($json->{"codigo_sabor_pizza"}, $empresaLogada["codigo_pizzaria"]))){
                throw new Exception("Erro ao remover dados.");
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }

    
//Valor das pizzas por tamanho e sabor   @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    public function buscarTodosValoresPizzas(){
        $this->verificaUsuarioLogado();
        $empresaLogada = $this->session->userdata("empresa_logada");
        $this->load->model("pizzaria/Valor_pizza_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        try{
            $valores = $this->Valor_pizza_model->buscarTodosValoresPizzasAtivos($empresaLogada['codigo_pizzaria']);
            $a=0;
            $todosValores = array();
            for($i=0; $i<count($valores); $i++){
                $sabor = $this->Pizza_sabor_model->buscarSaborPorCodigoEpizzaria($valores[$i]["sabor_pizza_valor_pizza"] ,$empresaLogada['codigo_pizzaria']);
                $tamanho = $this->Pizza_tamanho_model->buscarTamanhoPorCodigoEpizzaria($valores[$i]["tamanho_pizza_valor_pizza"] ,$empresaLogada['codigo_pizzaria']);

                if($tamanho != null && $sabor != null && $tamanho["ativo_tamanho_pizza"] == 1 && $sabor["ativo_sabor_pizza"] == 1){
                    $todosValores[$a]=$valores[$i];
                    $todosValores[$a]["sabor_pizza_valor_pizza"] = $sabor["descricao_sabor_pizza"];
                    $todosValores[$a]["tamanho_pizza_valor_pizza"] = $tamanho["descricao_tamanho_pizza"];
                    $a++;
                }
            }
            $sabores = $this->Pizza_sabor_model->buscarSaboresEmpresa($empresaLogada['codigo_pizzaria']);
            $tamanhos = $this->Pizza_tamanho_model->buscarTamanhos($empresaLogada['codigo_pizzaria']);
            $dados = array("valores" => $todosValores, "sabores" => $sabores, "tamanhos" => $tamanhos);   
            $this->load->helper(array("currency"));
            $this->load->view("pizzaria/ViewManterValorPizzas.php", $dados);  
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    } 
    
    public function buscarValorPorCodigo(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Valor_pizza_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );  

            $valor = $this->Valor_pizza_model->buscarValorPizzaPorIDAtivo($json->{"codigo_valor_pizza"}, $empresaLogada["codigo_pizzaria"]);
            $sabor = $this->Pizza_sabor_model->buscarSaborPorCodigoEpizzaria($valor["sabor_pizza_valor_pizza"] ,$empresaLogada['codigo_pizzaria']);
            $tamanho = $this->Pizza_tamanho_model->buscarTamanhoPorCodigoEpizzaria($valor["tamanho_pizza_valor_pizza"] ,$empresaLogada['codigo_pizzaria']);
            $valor["sabor_pizza_valor_pizza"] = $sabor["descricao_sabor_pizza"];
            $valor["tamanho_pizza_valor_pizza"] = $tamanho["descricao_tamanho_pizza"];
            $json_str = json_encode($valor);
            echo $json_str; 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    } 
    
    public function alterarValorPizzaria(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Valor_pizza_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );

            $valor = array(
                "codigo_valor_pizza" => $json->{"codigo_valor_pizza"},
                "preco_valor_pizza" => doubleval($json->{"preco_valor_pizza"}),
                "pizzaria_valor_pizza" => $empresaLogada["codigo_pizzaria"],
                "ativo_valor_pizza" => 1
            );

            if(!($this->Valor_pizza_model->alterarValorPizza($valor, $empresaLogada["codigo_pizzaria"]))){
                throw new Exception("Erro ao alterar dados.");
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function confirmarRemoverValor(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Valor_pizza_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );    

            if(!($this->Valor_pizza_model->removerValorPizza($json->{"codigo_valor_pizza"}, $empresaLogada["codigo_pizzaria"]))){
                throw new Exception("Erro ao remover dados.");
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function incluirValorPizzaria(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Valor_pizza_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        $empresaLogada = $this->session->userdata("empresa_logada");
        $meuPost = file_get_contents("php://input");
        $json = json_decode( $meuPost , true);
        $valores = $json["contents"];
        $saborEsco = $json["sabor_pizza_valor_pizza"];      
        
        $todosValores = array();
        try{
            for($i=0; $i<count($valores); $i++){  
                if(doubleval($valores[$i]["value"]) > 0){
                    $existe = 0;
                    $valor = array(
                        "tamanho_pizza_valor_pizza" => $valores[$i]["tamanho"],
                        "sabor_pizza_valor_pizza" => $saborEsco,
                        "preco_valor_pizza" => doubleval($valores[$i]["value"]),
                        "pizzaria_valor_pizza" => $empresaLogada["codigo_pizzaria"],
                        "ativo_valor_pizza" => 1
                    );
                    //busco valores e verifico se ele ja existe
                    $tvalores = $this->Valor_pizza_model->buscarTodosValoresPizzasAtivos($empresaLogada['codigo_pizzaria']);
                    for($a=0; $a<count($tvalores); $a++){
                        if($tvalores[$a]["tamanho_pizza_valor_pizza"] == $valor["tamanho_pizza_valor_pizza"] &&
                            $tvalores[$a]["sabor_pizza_valor_pizza"] == $valor["sabor_pizza_valor_pizza"] &&
                                $tvalores[$a]["ativo_valor_pizza"] == 1)
                        {
                            $valor["codigo_valor_pizza"] = $tvalores[$a]["codigo_valor_pizza"];
                            if(!($this->Valor_pizza_model->alterarValorPizza($valor, $empresaLogada["codigo_pizzaria"]))){
                                throw new Exception("Erro ao alterar dados.");
                            }
                            $existe=1;
                            break;
                        }
                    }
                    if($existe == 0){
                        $valor = $this->Valor_pizza_model->inserirValorPizzaRetornandoo($valor);
                    }

                    $sabor = $this->Pizza_sabor_model->buscarSaborPorCodigoEpizzaria($valor["sabor_pizza_valor_pizza"] ,$empresaLogada['codigo_pizzaria']);
                    $tamanho = $this->Pizza_tamanho_model->buscarTamanhoPorCodigoEpizzaria($valor["tamanho_pizza_valor_pizza"] ,$empresaLogada['codigo_pizzaria']);
                    $valor["sabor_pizza_valor_pizza"] = $sabor["descricao_sabor_pizza"];
                    $valor["tamanho_pizza_valor_pizza"] = $tamanho["descricao_tamanho_pizza"];
                    $todosValores[]=$valor;
                }
            } 
            echo json_encode($todosValores);
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    function arrayToObject( $array ){
        foreach( $array as $key => $value ){
          if( is_array( $value ) ) $array[ $key ] = arrayToObject( $value );
        }
        return (object) $array;
    }
}
