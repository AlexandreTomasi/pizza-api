<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorPrincipal
 *
 * @author Alexandre Tomasi
 */
class ControladorPrincipal extends CI_Controller{
    public function primeiraMensagemPersonagem(){
        $this->load->model("regras_personagens/Saudacao_regras");
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["Codg_personagem"])){throw new Exception("Codigo da Personagem não existente");}
            if(!isset($_GET["chatfuel_user_id"])){throw new Exception("chatfuel_user_id não existente");}
            if(!isset($_GET["first_name"])){throw new Exception("fb_first_name não existente");}
            if(!isset($_GET["last_name"])){throw new Exception("fb_last_name não existente");}
            $pizzaria = intval($_GET["Codg_pizzaria"]);
            $personagem = $_GET["Codg_personagem"];
            $idCliente = $_GET["chatfuel_user_id"];
            $nome = $_GET["first_name"];
            $sobrenome = $_GET["last_name"];
            
            $resposta = $this->Saudacao_regras->mensagemInicial($pizzaria, $idCliente, $personagem, $nome);
            $json_str = json_encode($resposta);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function mostrarMenuInicial(){
        $this->load->model("regras_personagens/Saudacao_regras");
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["Codg_personagem"])){throw new Exception("Codigo da Personagem não existente");}
            if(!isset($_GET["chatfuel_user_id"])){throw new Exception("chatfuel_user_id não existente");}
            if(!isset($_GET["first_name"])){throw new Exception("fb_first_name não existente");}
            if(!isset($_GET["last_name"])){throw new Exception("fb_last_name não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $personagem = $_GET["Codg_personagem"];
            $idCliente = $_GET["chatfuel_user_id"];
            $nome = $_GET["first_name"];
            $sobrenome = $_GET["last_name"];
            
            $resposta = $this->Saudacao_regras->menuInicial($pizzaria, $idCliente, $personagem, $nome);
            $json_str = json_encode($resposta);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function mensagemInicialUltimoPedido(){
        $this->load->model("regras_personagens/Saudacao_regras");
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["Codg_personagem"])){throw new Exception("Codigo da Personagem não existente");}
            if(!isset($_GET["chatfuel_user_id"])){throw new Exception("chatfuel_user_id não existente");}
            if(!isset($_GET["first_name"])){throw new Exception("fb_first_name não existente");}
            if(!isset($_GET["last_name"])){throw new Exception("fb_last_name não existente");}
            $pizzaria = intval($_GET["Codg_pizzaria"]);
            $personagem = $_GET["Codg_personagem"];
            $idCliente = $_GET["chatfuel_user_id"];
            $nome = $_GET["first_name"];
            $sobrenome = $_GET["last_name"];
            
            $resposta = $this->Saudacao_regras->mensagemInicialUP($pizzaria, $idCliente, $personagem, $nome);
            $json_str = json_encode($resposta);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function solicitaObservacaoPedido(){
        $this->load->model("regras_personagens/Observacao_pedido");
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["Codg_personagem"])){throw new Exception("Codigo da Personagem não existente");}
            if(!isset($_GET["chatfuel_user_id"])){throw new Exception("chatfuel_user_id não existente");}
            if(!isset($_GET["first_name"])){throw new Exception("fb_first_name não existente");}
            if(!isset($_GET["last_name"])){throw new Exception("fb_last_name não existente");}
            $pizzaria = intval($_GET["Codg_pizzaria"]);
            $personagem = $_GET["Codg_personagem"];
            $idCliente = $_GET["chatfuel_user_id"];
            $nome = $_GET["first_name"];
            $sobrenome = $_GET["last_name"];
            
            $resposta = $this->Observacao_pedido->observacaoPedidoUP($pizzaria, $idCliente, $personagem, $nome);
            $json_str = json_encode($resposta);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function confirmaUltimoTelefone(){
        $this->load->model("regras_personagens/Telefone_regras");
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["Codg_personagem"])){throw new Exception("Codigo da Personagem não existente");}
            if(!isset($_GET["chatfuel_user_id"])){throw new Exception("chatfuel_user_id não existente");}
            if(!isset($_GET["first_name"])){throw new Exception("fb_first_name não existente");}
            if(!isset($_GET["last_name"])){throw new Exception("fb_last_name não existente");}
            $pizzaria = intval($_GET["Codg_pizzaria"]);
            $personagem = $_GET["Codg_personagem"];
            $idCliente = $_GET["chatfuel_user_id"];
            $nome = $_GET["first_name"];
            $sobrenome = $_GET["last_name"];
            
            $resposta = $this->Telefone_regras->confirmaTelefone($pizzaria, $idCliente, $personagem, $nome);
            $json_str = json_encode($resposta);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
}
