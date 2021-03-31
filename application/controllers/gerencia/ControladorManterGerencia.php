<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorManterGerencia
 *
 * @author 033234581
 */
class ControladorManterGerencia extends CI_Controller{
    //put your code here
    public function verificaUsuarioLogado(){      
        $usuario = $this->session->userdata("gerente");
        if($usuario == null){
            $this->session->unset_userdata("gerente");
            $this->session->set_flashdata("sucess" , "Sessão Expirada. Por favor logue novamente");
            redirect('/');
        }
    }
    
    public function menuPizzariaAntigo(){
        $this->load->view("menu/ViewMenuPizzaria.php");  
    }
    public function paginaTesteAgenda(){
        $this->load->view("menu/MenuTesteJsonAgenda.php");  
    }
    public function paginaTesteProduto(){
        $this->load->view("menu/MenuTesteJsonProduto.php");  
    }
    public function paginaTesteAnna(){
        $this->load->view("gerencia/MenuTesteJsonAnna.php");  
    }
    
    public function listarLogs(){
        $this->verificaUsuarioLogado(); 
        $ponteiro = fopen ("log_skybots.txt","r");
        $texto = "\n";
        $contator =0;
        while ((!feof ($ponteiro)) && !($contator == 2000)) {
            $linha = fgets($ponteiro,4096);
            $texto = $linha.$texto;
            $contator = $contator+1;
        }
        fclose ($ponteiro);
        $dados = array("dados" => $texto); 
        $this->load->view("gerencia/ViewManterLogs.php",$dados);  
    }
    
    public function listarLogsChatBot(){
        $this->verificaUsuarioLogado(); 
        $ponteiro = fopen ("log.txt","r");
        $texto = "\n";
        $contator =0;
        while ((!feof ($ponteiro)) && !($contator == 2000)) {
            $linha = fgets($ponteiro,4096);
            $texto = $linha.$texto;
            $contator = $contator+1;
        }
        fclose ($ponteiro);
        $dados = array("dados" => $texto); 
        $this->load->view("gerencia/ViewManterLogs.php",$dados);  
    }
    
    public function verificaExcessaoHoraAtual(){
        $this->verificaUsuarioLogado();
        $this->load->model("servicos/BuscaNovasExcessoes");
        $this->load->model("servicos/EnviarEmail");
        try{
            $resposta = $this->BuscaNovasExcessoes->buscaExcessaoHoraAtual("log.txt");
                $dados = array("dados" => $resposta); 
                $this->load->view("gerencia/ViewManterLogs.php",$dados);  
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function servicoVerificaExcessaoHoraAtual(){
        //curl http://skybots.com.br/pizza-api/index.php/gerencia/ControladorManterGerencia/servicoVerificaExcessaoHoraAtual
        $this->load->model("servicos/BuscaNovasExcessoes");
        $this->load->model("servicos/EnviarEmail");
        try{
            $resposta = $this->BuscaNovasExcessoes->buscaExcessaoHoraAtual("log.txt");
            if($resposta != null && $resposta != ""){
                $temp = "Foram encontrados os seguintes erros:\n\n".$resposta."\n\n Resposta enviada do servidor não responder.";
                $resp = $this->EnviarEmail->enviarEmailmessage($temp);
                if($resp == false){
                    throw new Exception("Erro ao enviar email.");
                }
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function recebeMensagemServico(){
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            if(!isset($_GET["servicoMensagem"])){throw new Exception("Codigo da Pizzaria não existente");}
            $mensagem = $_GET["servicoMensagem"];
            $fp = fopen("log_servico_chatfuel.txt", "w");
            $escreve = fwrite($fp, "\n".$mensagem);
            fclose($fp); 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function enviarMensagemAoChatbotViaSite(){
        $this->verificaUsuarioLogado();
        $this->load->model("servicos/EnviarReceberMsgChatfuel");
        try{
            $resposta = $this->EnviarReceberMsgChatfuel->enviarMensagemAoChatbotViaSite();
            $dados = array("dados" => $resposta); 
            $this->load->view("gerencia/ViewManterLogs.php",$dados);
        }catch (Exception $e){
                $fp = fopen("log_skybots.txt", "a");
                fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".'gerado apartir do serviço '.$e->getMessage());
                fclose($fp); 
        }  
    }
    
    public function servicoVerificaMensagemRecebidaViaSite(){
        $this->verificaUsuarioLogado();
        $this->load->model("servicos/EnviarReceberMsgChatfuel");
        try{
            $resposta = $this->EnviarReceberMsgChatfuel->servicoVerificaMensagemRecebidaViaSite();
            $dados = array("dados" => $resposta); 
            $this->load->view("gerencia/ViewManterLogs.php",$dados);
        }catch (Exception $e){
                $fp = fopen("log_skybots.txt", "a");
                fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".'gerado apartir do serviço '.$e->getMessage());
                fclose($fp); 
        }
    }
    
    public function servicoEnviarMensagemAoChatbot(){
        $this->load->model("servicos/EnviarReceberMsgChatfuel");
        try{
            $this->EnviarReceberMsgChatfuel->enviarMensagemAoChatbotViaServico();
        }catch (Exception $e){
                $fp = fopen("log_skybots.txt", "a");
                fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".'gerado apartir do serviço '.$e->getMessage());
                fclose($fp); 
        }  
    }
    public function servicoVerificaMensagemRecebida(){
        $this->load->model("servicos/EnviarReceberMsgChatfuel");
        try{
            $this->EnviarReceberMsgChatfuel->servicoVerificaMensagemRecebidaViaServico();
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".'gerado apartir do serviço '.$e->getMessage());
            fclose($fp); 
        }  
    }
    
    public function verificarMudancaTelefonePizzaria(){
        $this->verificaUsuarioLogado();
        $this->load->model("servicos/EnviarEmail");
        $this->load->model("gerencia/Cliente_pizzaria_model");
        $this->load->model("pizzaria/Empresa_model");
        try{
            $clientes = $this->Cliente_pizzaria_model->buscarTodosClientesAtivos();
            $texto = "";
            $achou = false;
            for($i=0; $i<count($clientes); $i++){
                $pizzaria = $this->Empresa_model->buscaPorCNPJemail($clientes[$i]["email_cliente"], $clientes[$i]["cnpj_cliente"]);
                if($clientes[$i]["telefone_cliente"] != $pizzaria["telefone_pizzaria"]){
                    $texto = $texto.("A Pizzaria na razao social: ".$clientes[$i]["razao_social_cliente"].
                            ".\nCom o nome: ".$clientes[$i]["nome_fantasia_cliente"].
                            ".\nCom o código: ".$pizzaria["codigo_pizzaria"].".\nMudou o Telefone de ".
                            $clientes[$i]["telefone_cliente"]." para ".$pizzaria["telefone_pizzaria"]."\n");
                     $achou = true;
                }
            }
            if($achou){
                $resp = $this->EnviarEmail->enviarEmailmessage($texto);
                if($resp == false){
                    throw new Exception("Erro ao enviar email.");
                }
            }else{
                $texto = "Nenhum telefone alterado";
            }
            $dados = array("dados" => $texto); 
            $this->load->view("gerencia/ViewManterLogs.php",$dados);
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".'gerado apartir do serviço '.$e->getMessage());
            fclose($fp); 
        }      
    }
    
    public function servicoVerMudancaTelPizzaria(){
        $this->verificaUsuarioLogado();
        $this->load->model("servicos/EnviarEmail");
        $this->load->model("gerencia/Cliente_pizzaria_model");
        $this->load->model("pizzaria/Empresa_model");
        try{
            $clientes = $this->Cliente_pizzaria_model->buscarTodosClientesAtivos();
            $texto = "";
            $achou = false;
            for($i=0; $i<count($clientes); $i++){
                $pizzaria = $this->Empresa_model->buscaPorCNPJemail($clientes[$i]["email_cliente"], $clientes[$i]["cnpj_cliente"]);
                if($clientes[$i]["telefone_cliente"] != $pizzaria["telefone_pizzaria"]){
                    $texto = $texto.("A Pizzaria na razao social: ".$clientes[$i]["razao_social_cliente"].
                            ".\nCom o nome: ".$clientes[$i]["nome_fantasia_cliente"].
                            ".\nCom o código: ".$pizzaria["codigo_pizzaria"].".\nMudou o Telefone de ".
                            $clientes[$i]["telefone_cliente"]." para ".$pizzaria["telefone_pizzaria"]."\n");
                     $achou = true;
                }
            }
            if($achou){
                $resp = $this->EnviarEmail->enviarEmailmessage($texto);
                if($resp == false){
                    throw new Exception("Erro ao enviar email.");
                }
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".'gerado apartir do serviço '.$e->getMessage());
            fclose($fp); 
        }      
    }
    
    public function verHorario(){
        $this->load->model("pizzaria/HorariosEmpresa");
            $empresaLogada = $this->session->userdata("empresa_logada");
            $horarioEsp = $this->HorariosEmpresa->consultaHorarioEspecialAbertoFexado($empresaLogada);

            $remov = array();
            $todos = array();
            $fimDia =  date_format(date_create("23:59:59"), 'H:i:s');
            $iniDia =  date_format(date_create("00:00:01"), 'H:i:s');
            for($i=0; $i<count($horarioEsp); $i++){
                $fim = date_format(date_create($horarioEsp[$i]["fim_horario_especial"]), 'H:i:s');
                if($fim == $fimDia){
                    for($a=0; $a<count($horarioEsp); $a++){
                        $ini = date_format(date_create($horarioEsp[$a]["inicio_horario_especial"]), 'H:i:s');
                        if($iniDia == $ini){                      
                            $flag = date_format(date_create($horarioEsp[$i]["data_horario_especial"]), 'Y-m-d');
                            if(date('Y-m-d',strtotime("+1 days",strtotime($flag))) == date_format(date_create($horarioEsp[$a]["data_horario_especial"]), 'Y-m-d')  ){
                                $horarioEsp[$i]["fim_horario_especial"] = $horarioEsp[$a]["fim_horario_especial"];
                                $remov[]=$a;
                                break;
                            }
                        }
                    }
                }

            }
            if(count($remov) > 0){
                for($i=0; $i<count($horarioEsp); $i++){
                    for($a=0; $a<count($remov); $a++){
                        if($remov[$a] != $i){// adiciona só os que nao tao marcados para remover
                            $todos[] = $horarioEsp[$i];
                            break;
                        }               
                    }
                }
            }else{
                $todos = $horarioEsp;
            }


            for($i=0; $i<count($todos); $i++){
                $todos[$i]["data_horario_especial"] = date_format(date_create($todos[$i]["data_horario_especial"]), 'd/m/Y');
                if($todos[$i]["aberto_horario_especial"] == 1){
                    $todos[$i]["aberto_horario_especial"] = "Aberto";
                }else if($todos[$i]["aberto_horario_especial"] == 0){
                    $todos[$i]["aberto_horario_especial"] = "Fechado";
                }
                else if($todos[$i]["aberto_horario_especial"] == 2){
                    $todos[$i]["aberto_horario_especial"] = "Pausado";
                }
            }

            $dados = array("horarioEsp" => $todos);
        $this->load->view("errors/ViewHorario.php",$dados);  
    }
    
    public function verRelogio(){
        $this->verificaUsuarioLogado(); 
        
        $dados = array("dados" => date('Y-m-d H:i:s')); 
        $this->load->view("errors/ViewRelogio.php",$dados);  
    } 
    
    public function testesJson(){
        //$this->verificaUsuarioLogado(); 
        $this->load->model("modelos/UtilitarioMensagemFacebook");

        $galeria = array();
        for($i=0; $i < 4; $i++){
            $galeria[$i] = array(
                "title"=> $i,
                "image_url"=> "",
                "subtitle"=> $i,
                "buttons" =>array(
                    array(
                        "type"=> "show_block",
                        "block_name"=> "teste2",
                        "title"=> $i
                    )
                )
            );
            /*$galeria[$i] = array(
                "title"=> $i,
                "image_url"=> "https://portaldeplanos.com.br/wp-content/uploads/2016/08/como-cancelar-o-pacote-de-internet-da-oi.jpg",
                "subtitle"=> $i,
                "buttons" =>array(
                    array(
                        "type"=> "web_url",
                        "url"=> "https://www.uol.com.br",
                        "title"=> $i
                    )
                )
            );*/
        }
        $atributos = array("BairroCliente" => "500");
        /*$rapida = array();
                $rapida[] = array('title' => "Sim",'block_names' => array("teste2"));
                $rapida[] = array('title' => "Não",'block_names' => array("começar"));
               $botoes = array();
               $botoes[]= array('type'=> 'show_block', 'block_name' => "teste2", 'title' => "Sim");
               $botoes[]= array('type'=> 'show_block', 'block_name' => "teste2", 'title' => "Não");*/
        //$resposta = $this->UtilitarioMensagemFacebook->gerarLista("testando varios att",$atributos, $galeria, "");
        $resposta = $this->UtilitarioMensagemFacebook->enviarImagen("https://portaldeplanos.com.br/wp-content/uploads/2016/08/como-cancelar-o-pacote-de-internet-da-oi.jpg");
        $json_str = json_encode($resposta);
        echo $json_str;
    }
}
