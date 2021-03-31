<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controlador_forma_pagamento
 *
 * @author 033234581
 */
class Controlador_forma_pagamento extends CI_Controller{
    //put your code here
    public function buscaFormasPagamento(){
        $this->load->model("regras_personagens/Formas_pagamento_regras");
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
            
            $resposta = $this->Formas_pagamento_regras->galeriaFormasPagamento($pizzaria, $idCliente, $personagem, $nome);
            $json_str = json_encode($resposta);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function recebeFormaPagamentoUP(){
        $this->load->model("regras_personagens/Formas_pagamento_regras");
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["Codg_personagem"])){throw new Exception("Codigo da Personagem não existente");}
            if(!isset($_GET["chatfuel_user_id"])){throw new Exception("chatfuel_user_id não existente");}
            if(!isset($_GET["first_name"])){throw new Exception("fb_first_name não existente");}
            if(!isset($_GET["last_name"])){throw new Exception("fb_last_name não existente");}
            if(!isset($_GET["last_clicked_button_name"])){throw new Exception("last_clicked_button_name não existente");}
            $pizzaria = intval($_GET["Codg_pizzaria"]);
            $personagem = $_GET["Codg_personagem"];
            $idCliente = $_GET["chatfuel_user_id"];
            $nome = $_GET["first_name"];
            $sobrenome = $_GET["last_name"];
            $formaPgSelecionada = $_GET["last_clicked_button_name"];

            $resposta = $this->Formas_pagamento_regras->recebeFormaPagamento($pizzaria, $idCliente, $personagem, $nome, $formaPgSelecionada);
            $json_str = json_encode($resposta);
            echo $json_str;

        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
            
            $botoes=array();
            $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
            $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
            $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
            $json_str = json_encode($resposta);
            echo $json_str; 
        }   
    }
    
    public function recebeTrocoInformadoUP(){
        $this->load->model("regras_personagens/Formas_pagamento_regras");
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["Codg_personagem"])){throw new Exception("Codigo da Personagem não existente");}
            if(!isset($_GET["chatfuel_user_id"])){throw new Exception("chatfuel_user_id não existente");}
            if(!isset($_GET["first_name"])){throw new Exception("fb_first_name não existente");}
            if(!isset($_GET["last_name"])){throw new Exception("fb_last_name não existente");}
            if(!isset($_GET["TrocoClienteUP"])){throw new Exception("TrocoClienteUP não existente");}
            $pizzaria = intval($_GET["Codg_pizzaria"]);
            $personagem = $_GET["Codg_personagem"];
            $idCliente = $_GET["chatfuel_user_id"];
            $nome = $_GET["first_name"];
            $sobrenome = $_GET["last_name"];
            $troco = $_GET["TrocoClienteUP"];

            $resposta = $this->Formas_pagamento_regras->recebeTroco($pizzaria, $idCliente, $personagem, $nome, $troco);
            $json_str = json_encode($resposta);
            echo $json_str;

        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
            
            $botoes=array();
            $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
            $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
            $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
            $json_str = json_encode($resposta);
            echo $json_str; 
        }   
    }
}
