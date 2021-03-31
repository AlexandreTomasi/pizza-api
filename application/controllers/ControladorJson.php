<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of controladorJson
 *
 * @author 033234581
 */
class ControladorJson extends CI_Controller{
    
    public function mensagemWelcomeDaPizzaria(){
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            $pizzaria = intval($_GET["Codg_pizzaria"]);
            $this->load->model("pizzaria/Empresa_model");
            $this->load->model("gerencia/Cliente_pizzaria_model");
            $this->load->model("gerencia/Valor_configuracao_model");
            $nomeBot = $this->Empresa_model->buscaConfigEmpresa("nome_bot_pizzaria",$pizzaria);
            $empresa = $this->Empresa_model->buscaPizzariaPorCodigo($pizzaria);//1 busca
            
            $mensagem = "Olá, eu sou o ".$nomeBot.", um assistente virtual de atendimento da ".$empresa["nome_fantasia_pizzaria"];
            $dados = array('messages' => array(
                    array(
                        'text' => $mensagem
                    )
                ));
            $json_str = json_encode($dados);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function setaValorNomeFantasia(){
        try{
            $this->load->model("modelos/SetVariaveisChat");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["NomeFantasia"])){throw new Exception("NomeFantasia não existente");} 
            $pizzaria = $_GET["Codg_pizzaria"];
            $nome = $_GET["NomeFantasia"];
            $this->load->model("pizzaria/Empresa_model");
            $empresa = $this->Empresa_model->buscaPizzariaPorCodigo($pizzaria);
            $nome = $empresa["nome_fantasia_pizzaria"];
            
            echo $this->SetVariaveisChat->mudaValorVariavelUtilitario($nome, "NomeFantasia");
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }

    // começo do tamanho
    public function buscaTamanhosPizza(){
        try{  
            $this->load->model("modelos/tamanhosPizza");
            $this->load->model("modelos/UtilitarioMensagemFacebook");
            $this->load->model("modelos/VerificadorStatusItens");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["FaixaTamanho"])){throw new Exception("FaixaTamanho não existente");}
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");}
            if(!isset($_GET["Sabor"])){throw new Exception("Sabor não existente");}
            if(!isset($_GET["ItemExtra"])){throw new Exception("ItemExtra não existente");}
            if(!isset($_GET["ItemBebida"])){throw new Exception("ItemBebida não existente");}
            if(!isset($_GET["BairroCliente"])){throw new Exception("BairroCliente não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $faixa = $_GET["FaixaTamanho"];
            $tamanho = $_GET["TamanhoPizza"];
            $sabor = $_GET["Sabor"];
            $ItemExtra = $_GET["ItemExtra"];
            $ItemBebida = $_GET["ItemBebida"];
            $bairro = $_GET["BairroCliente"];
            
            if($this->VerificadorStatusItens->verificarExistenciaItensInativos($pizzaria, $tamanho, $sabor, $ItemExtra, $ItemBebida, $bairro,"") == 0){
                $botoes=array();
                $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
                $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
                $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
                $json_str = json_encode($resposta);
                echo $json_str;
            }else{
                $cartao = $this->tamanhosPizza->buscaTamanhosNaFaixa($pizzaria, $faixa);
                echo $cartao;
            }
                       
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    // se a pizzaria trab com 1 tamanho coloca o valor 999 na faixa para que ao receber o codigo do tamanho ja seta o unico que tem.
    // se nao incrementa a faixa de opcoes de tamanhos
    public function incrementaValorTamanhoDaPizza(){
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["FaixaTamanho"])){throw new Exception("FaixaTamanho não existente");} 
            if(!isset($_GET["FluxoRespostaPadrao"])){throw new Exception("FluxoRespostaPadrao não existente");} 
            $pizzaria = $_GET["Codg_pizzaria"];
            $faixa = $_GET["FaixaTamanho"];
            $fluxoEspecial = $_GET["FluxoRespostaPadrao"];
            $this->load->model("modelos/SetVariaveisChat");
            $this->load->model("pizzaria/Pizza_tamanho_model");
            $tamanhos = $this->Pizza_tamanho_model->buscarTamanhosComValorSaborAtivos($pizzaria);
            if(count($tamanhos) == 1){
                $faixa=999;
            }else{   
                if($fluxoEspecial == 1){// se veio do Default Answer seta para 1 de inicio de faixa.
                    $faixa = 1;
                }else{
                    if($faixa != 0){
                        if((count($tamanhos)/9) > $faixa ){
                            $faixa=$faixa+1;
                        }else{
                            $faixa=1;
                        }
                    }else{
                        $faixa=$faixa+1;
                    }
                }
            }
            
            $retorno = $this->SetVariaveisChat->mudaValorVariavelUtilitario($faixa, "FaixaTamanho");
            echo $retorno;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function recebeValorTamanhoPizza(){   
        try{  
            $this->load->model("modelos/SetVariaveisChat");
            $this->load->model("modelos/UtilitarioMensagemFacebook");
            $this->load->model("modelos/VerificadorStatusItens");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}          
            if(!isset($_GET["last_clicked_button_name"])){throw new Exception("last_clicked_button_name não existente");}           
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");}    
            if(!isset($_GET["FaixaTamanho"])){throw new Exception("FaixaTamanho não existente");} 
            if(!isset($_GET["Sabor"])){throw new Exception("Sabor não existente");}
            if(!isset($_GET["ItemExtra"])){throw new Exception("ItemExtra não existente");}
            if(!isset($_GET["ItemBebida"])){throw new Exception("ItemBebida não existente");}
            if(!isset($_GET["BairroCliente"])){throw new Exception("BairroCliente não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $tamanhoNome = $_GET["last_clicked_button_name"];
            $tamanho = $_GET["TamanhoPizza"];
            $faixa = $_GET["FaixaTamanho"];
            $sabor = $_GET["Sabor"];
            $ItemExtra = $_GET["ItemExtra"];
            $ItemBebida = $_GET["ItemBebida"];
            $bairro = $_GET["BairroCliente"];
            if($this->VerificadorStatusItens->verificarExistenciaItensInativos($pizzaria, $tamanho, $sabor, $ItemExtra, $ItemBebida, $bairro,"") == 0){
                $botoes=array();
                $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
                $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
                $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
                $json_str = json_encode($resposta);
                echo $json_str;
            }else{
                $teste = $this->SetVariaveisChat->mudaValorTamanho($tamanhoNome, $tamanho, $pizzaria, $faixa);
                echo $teste;
            }
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp);
            
        }
    }
    
    // fim tamanho
    // começo sabores 
    // primeiro
    public function incrementaFaixaSabores(){
        try{
            $this->load->model("modelos/SetVariaveisChat");
            $this->load->model("modelos/ControladorFluxo");
            $this->load->model("pizzaria/Pizza_sabor_model");
            
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["Pg"])){throw new Exception("Variavel de paginação sabor não existente");} 
            if(!isset($_GET["FluxoRespostaPadrao"])){throw new Exception("FluxoRespostaPadrao não existente");} 
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");} 
             
            $pizzaria = $_GET["Codg_pizzaria"];
            $fluxoEspecial = $_GET["FluxoRespostaPadrao"];
            $pg = $_GET["Pg"];
            $tamanho = $_GET["TamanhoPizza"];
            
            $tamanAtual = explode("-",$tamanho);
            $sabores = $this->Pizza_sabor_model->buscarSaboresEmpresaComValorAtivos($pizzaria, $tamanAtual[count($tamanAtual)-1]);
            if($pg != 0){
                if($fluxoEspecial == 1){
                    $pg=1;
                }else{
                    if((count($sabores)/8) > $pg ){
                        $pg=$pg+1;
                    }else{
                        $pg=1;
                    } 
                }
            }else{
                $pg=$pg+1;
            }
            echo $this->SetVariaveisChat->mudaValorVariavelUtilitario($pg, "Pg");
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    //segundo
    public function buscaSaboresPizza(){
        try{
            $this->load->model("modelos/SaboresPizza");
            $this->load->model("modelos/UtilitarioMensagemFacebook");
            $this->load->model("modelos/VerificadorStatusItens");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["Pg"])){throw new Exception("Variavel de paginação sabor não existente");}       
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");}    
            if(!isset($_GET["Sabor"])){throw new Exception("Sabor não existente");}
            if(!isset($_GET["ItemExtra"])){throw new Exception("ItemExtra não existente");}
            if(!isset($_GET["ItemBebida"])){throw new Exception("ItemBebida não existente");}
            if(!isset($_GET["BairroCliente"])){throw new Exception("BairroCliente não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $pg = $_GET["Pg"];
            $tamanho = $_GET["TamanhoPizza"];
            $sabor = $_GET["Sabor"];
            $ItemExtra = $_GET["ItemExtra"];
            $ItemBebida = $_GET["ItemBebida"];
            $bairro = $_GET["BairroCliente"];
            
            if($this->VerificadorStatusItens->verificarExistenciaItensInativos($pizzaria, $tamanho, $sabor, $ItemExtra, $ItemBebida, $bairro,"") == 0){
                $botoes=array();
                $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
                $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
                $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
                $json_str = json_encode($resposta);
                echo $json_str;
            }else{
                if($tamanho != 0){
                    $cartao = $this->SaboresPizza->retornaGaleriaSabores($pg, $pizzaria, $tamanho, $sabor);
                    echo $cartao;
                }else{
                    throw new Exception("Busca de sabores não retornou nada");
                }
            }
            
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp);
            $this->load->model("modelos/UtilitarioMensagemFacebook");
            
            $botoes=array();
            $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
            $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
            $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
            $json_str = json_encode($resposta);
            echo $json_str; 
        }
    }
    //terceiro
    public function adicionaValorSaboresPizza(){
        try{
            $this->load->model("modelos/SaboresPizza");
            $this->load->model("modelos/VerificadorStatusItens");
            $this->load->model("modelos/UtilitarioMensagemFacebook");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");}
            if(!isset($_GET["Sabor"])){throw new Exception("Sabor não existente");}
            if(!isset($_GET["ItemExtra"])){throw new Exception("ItemExtra não existente");}
            if(!isset($_GET["ItemBebida"])){throw new Exception("ItemBebida não existente");}
            if(!isset($_GET["BairroCliente"])){throw new Exception("BairroCliente não existente");}
            if(!isset($_GET["last_clicked_button_name"])){throw new Exception("last_clicked_button_name não existente");}
            if(!isset($_GET["FimSabores"])){throw new Exception("FimSabores não existente");}
            
            $pizzaria = $_GET["Codg_pizzaria"];
            $saborSelecionado = $_GET["last_clicked_button_name"];
            $fimSabores = $_GET["FimSabores"];
            $tamanho = $_GET["TamanhoPizza"];
            $sabor = $_GET["Sabor"];
            $ItemExtra = $_GET["ItemExtra"];
            $ItemBebida = $_GET["ItemBebida"];
            $bairro = $_GET["BairroCliente"];
            
            if($this->VerificadorStatusItens->verificarExistenciaItensInativos($pizzaria, $tamanho, $sabor, $ItemExtra, $ItemBebida, $bairro,"") == 0){
                $botoes=array();
                $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
                $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
                $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
                $json_str = json_encode($resposta);
                echo $json_str;
            }else{
                $texto = $this->SaboresPizza->adicionaValor($pizzaria, $tamanho, $sabor, $saborSelecionado, $fimSabores);
                echo $texto;
            }
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
            
            $this->load->model("modelos/UtilitarioMensagemFacebook");
            $botoes=array();
            $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
            $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
            $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
            $json_str = json_encode($resposta);
            echo $json_str; 
        }
    }
    
    //quarto
    public function setaValorVariavelFimSabores(){
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["FimSabores"])){throw new Exception("FimSabores não existente");} 
            if(!isset($_GET["Sabor"])){throw new Exception("Sabor não existente");}
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");} 
            if(!isset($_GET["VariosSabores"])){throw new Exception("VariosSabores não existente");} 
            $pizzaria = $_GET["Codg_pizzaria"];
            $fimSabores = $_GET["FimSabores"];
            $sabor = $_GET["Sabor"];
            $tamanho = $_GET["TamanhoPizza"];
            $variosSabores = $_GET["VariosSabores"];
            
            $this->load->model("modelos/SetVariaveisChat");
            $retorno = $this->SetVariaveisChat->mudaValorFimSabores($pizzaria, $fimSabores, $sabor, $tamanho, $variosSabores);
            echo $retorno;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
            
            $this->load->model("modelos/UtilitarioMensagemFacebook");
            $botoes=array();
            $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
            $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
            $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
            $json_str = json_encode($resposta);
            echo $json_str; 
        }
    }
    //quinto
    public function verificaSaboresVersusFatias(){
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["Sabor"])){throw new Exception("Sabor não existente");}
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");} 
            $pizzaria = $_GET["Codg_pizzaria"];
            $sabor = $_GET["Sabor"];
            $tamanho = $_GET["TamanhoPizza"];
            $this->load->model("modelos/SaboresPizza");
            $permite = $this->SaboresPizza->verificaObrigatoriedadeMaisUmSabor($pizzaria, $tamanho, $sabor);
            if($permite != 1){
                $resposta= array("messages" =>array(array("text"=>"Ops, preciso que você escolha mais um sabor. Pode repetir, se desejar ;)"))
                    ,"redirect_to_blocks" => array("Fluxo 06"));
                $json_str = json_encode($resposta);
                echo $json_str; 
            }
            
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp);
            
            $this->load->model("modelos/UtilitarioMensagemFacebook");
            $botoes=array();
            $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
            $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
            $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
            $json_str = json_encode($resposta);
            echo $json_str; 
        }
    }
    
    // fim sabores
    
    public function incrementaValorQuantidadePizza(){
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["QuantidadePizza"])){throw new Exception("QuantidadePizza não existente");} 
            $pizzaria = $_GET["Codg_pizzaria"];
            $quantidade = $_GET["QuantidadePizza"];
            $this->load->model("modelos/SetVariaveisChat");
            $retorno = $this->SetVariaveisChat->mudaValorVariavelUtilitario($quantidade+1, "QuantidadePizza");
            echo $retorno;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }

  // começo do extra
    public function buscaExtraParaPizza(){     
        try{
            $this->load->model("modelos/ExtraParaPizza");
            $this->load->model("modelos/VerificadorStatusItens");
            $this->load->model("modelos/UtilitarioMensagemFacebook");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["FaixaExtra"])){throw new Exception("FaixaExtra não existente");}
            if(!isset($_GET["QuantidadePizza"])){throw new Exception("QuantidadePizza não existente");}
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");}
            if(!isset($_GET["Sabor"])){throw new Exception("Sabor não existente");}
            if(!isset($_GET["ItemExtra"])){throw new Exception("ItemExtra não existente");}
            if(!isset($_GET["ItemBebida"])){throw new Exception("ItemBebida não existente");}
            if(!isset($_GET["BairroCliente"])){throw new Exception("BairroCliente não existente");}
            
            $pizzaria = $_GET["Codg_pizzaria"];
            $tamanho = $_GET["TamanhoPizza"];
            $sabor = $_GET["Sabor"];
            $ItemExtra = $_GET["ItemExtra"];
            $ItemBebida = $_GET["ItemBebida"];
            $bairro = $_GET["BairroCliente"];  
            $faixa = $_GET["FaixaExtra"];
            $quantidade = $_GET["QuantidadePizza"];
            
            if($this->VerificadorStatusItens->verificarExistenciaItensInativos($pizzaria, $tamanho, $sabor, $ItemExtra, $ItemBebida, $bairro,"") == 0){
                $botoes=array();
                $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
                $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
                $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
                $json_str = json_encode($resposta);
                echo $json_str;
            }else{
                $cartao = $this->ExtraParaPizza->buscaExtrasNaFaixa($pizzaria, $faixa, $ItemExtra, $quantidade,$tamanho);
                echo $cartao;
            }
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
            
            $this->load->model("modelos/UtilitarioMensagemFacebook");
            $botoes=array();
            $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
            $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
            $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
            $json_str = json_encode($resposta);
            echo $json_str; 
        }
    }
    
    public function incrementaFaixaExtra(){
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["FaixaExtra"])){throw new Exception("FaixaExtra não existente");}
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");}
            $tamanho = $_GET["TamanhoPizza"];
            $pizzaria = $_GET["Codg_pizzaria"];
            $faixa = $_GET["FaixaExtra"];
            
            $this->load->model("modelos/SetVariaveisChat");
            $this->load->model("pizzaria/Pizza_extra_model");
            $todosTam = explode("-",$tamanho);
            $extras = $this->Pizza_extra_model->buscarExtrasAssociadoTamanho($todosTam[count($todosTam)-1], $pizzaria);
            
            if($faixa != 0){
                    if((count($extras)/8) > $faixa ){
                        $faixa=$faixa+1;
                    }else{
                        $faixa=1;
                    }
            }else{
                $faixa=$faixa+1;
            }
            $retorno = $this->SetVariaveisChat->mudaValorVariavelUtilitario($faixa, "FaixaExtra");
            echo $retorno;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function setaValorAdicionalExtra(){
        try{
            $this->load->model("modelos/ExtraParaPizza");
            $this->load->model("modelos/VerificadorStatusItens");
            $this->load->model("modelos/UtilitarioMensagemFacebook");

            if(!isset($_GET["QuantidadePizza"])){throw new Exception("QuantidadePizza não existente");}
            if(!isset($_GET["last_clicked_button_name"])){throw new Exception("last_clicked_button_name não existente");}    
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");}
            if(!isset($_GET["Sabor"])){throw new Exception("Sabor não existente");}
            if(!isset($_GET["ItemExtra"])){throw new Exception("ItemExtra não existente");}
            if(!isset($_GET["ItemBebida"])){throw new Exception("ItemBebida não existente");}
            if(!isset($_GET["BairroCliente"])){throw new Exception("BairroCliente não existente");}
            
            $quantidade = $_GET["QuantidadePizza"];
            $extraSelecionado = $_GET["last_clicked_button_name"];
            $pizzaria = $_GET["Codg_pizzaria"];
            $tamanho = $_GET["TamanhoPizza"];
            $sabor = $_GET["Sabor"];
            $ItemExtra = $_GET["ItemExtra"];
            $ItemBebida = $_GET["ItemBebida"];
            $bairro = $_GET["BairroCliente"];
            
            if($this->VerificadorStatusItens->verificarExistenciaItensInativos($pizzaria, $tamanho, $sabor, $ItemExtra, $ItemBebida, $bairro,"") == 0){
                $botoes=array();
                $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
                $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
                $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
                $json_str = json_encode($resposta);
                echo $json_str;
            }else{
                $retorno = $this->ExtraParaPizza->adicionaValorAdicionalExtra($pizzaria, $ItemExtra, $extraSelecionado, $quantidade, $tamanho);
                echo $retorno;
            }
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
            
            $this->load->model("modelos/UtilitarioMensagemFacebook");
            $botoes=array();
            $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
            $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
            $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
            $json_str = json_encode($resposta);
            echo $json_str; 
        }
    }
    
    public function verificaExisteExtraCadastrado(){
        try{
            $this->load->model("modelos/SetVariaveisChat");
            $this->load->model("modelos/ExtraParaPizza");
            if(!isset($_GET["QuantidadePizza"])){throw new Exception("QuantidadePizza não existente");}
            if(!isset($_GET["ItemExtra"])){throw new Exception("ItemExtra não existente");}
            if(!isset($_GET["Sabor"])){throw new Exception("Sabor não existente");}
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");}
            $tamanho = $_GET["TamanhoPizza"];
            $pizzaria = $_GET["Codg_pizzaria"];       
            $ItemExtra = $_GET["ItemExtra"];
            $quantidade = $_GET["QuantidadePizza"];
            $sabor = $_GET["Sabor"];

            $saborAtual = explode("@",$sabor);
            $saboresUni = explode("-",$saborAtual[ count($saborAtual)-1 ]);
            // verifica se existe algum sabor, se nao então ta errado e volta para adc um sabor.
            if($sabor == 0 || $quantidade != count($saborAtual) || count($saboresUni) == 0 ){
               $dados = array('redirect_to_blocks' => array("Escolhe Sabores"));
                $json_str = json_encode($dados);
                echo $json_str; 
            }else{
                //verifica se existe extra e se ja foram todos preenchidos se sim vai para prox etapa
                $disponivel = $this->ExtraParaPizza->verificaExtraFinalizado($pizzaria, $ItemExtra, $quantidade, $tamanho);
                if($disponivel == 0){       
                    echo $this->SetVariaveisChat->mudaValorVariavelUtilitario(999, "FaixaExtra");// coloca valor 999 indicando que nao existe extras
                }
            }
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }

// fim extra
    
    public function buscaBebidasPizzaria(){
        try{
            $this->load->model("modelos/UtilitarioMensagemFacebook");
            $this->load->model("modelos/VerificadorStatusItens");
            $this->load->model("modelos/BebidasPizzaria");
            
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["FaixaBebida"])){throw new Exception("FaixaBebida não existente");}
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");}
            if(!isset($_GET["Sabor"])){throw new Exception("Sabor não existente");}
            if(!isset($_GET["ItemExtra"])){throw new Exception("ItemExtra não existente");}
            if(!isset($_GET["ItemBebida"])){throw new Exception("ItemBebida não existente");}
            if(!isset($_GET["BairroCliente"])){throw new Exception("BairroCliente não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $faixa = $_GET["FaixaBebida"];
            $tamanho = $_GET["TamanhoPizza"];
            $sabor = $_GET["Sabor"];
            $ItemExtra = $_GET["ItemExtra"];
            $ItemBebida = $_GET["ItemBebida"];
            $bairro = $_GET["BairroCliente"];
            
            if($this->VerificadorStatusItens->verificarExistenciaItensInativos($pizzaria, $tamanho, $sabor, $ItemExtra, $ItemBebida, $bairro,"") == 0){
                $botoes=array();
                $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
                $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
                $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
                $json_str = json_encode($resposta);
                echo $json_str;
            }else{
                $cartao = $this->BebidasPizzaria->buscaBebidasNaFaixa($pizzaria, $faixa);
                echo $cartao;
            }

        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
            
    }
    public function incrementaFaixaBebida(){
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["FaixaBebida"])){throw new Exception("FaixaBebida não existente");}
            if(!isset($_GET["FluxoRespostaPadrao"])){throw new Exception("FluxoRespostaPadrao não existente");} 
            $pizzaria = $_GET["Codg_pizzaria"];
            $faixa = $_GET["FaixaBebida"];
            $fluxoEspecial = $_GET["FluxoRespostaPadrao"];
            
            if($fluxoEspecial == 1){
                $faixa = 0;
            }
            $this->load->model("modelos/SetVariaveisChat");
            $retorno = $this->SetVariaveisChat->mudaValorVariavelUtilitario($faixa+1, "FaixaBebida");
            echo $retorno;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function setaValorBebidaSelecionada(){
        $this->load->model("modelos/BebidasPizzaria");
        $this->load->model("modelos/UtilitarioMensagemFacebook");
        $this->load->model("modelos/VerificadorStatusItens");
        try{
            if(!isset($_GET["last_clicked_button_name"])){throw new Exception("last_clicked_button_name não existente");}
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");}
            if(!isset($_GET["Sabor"])){throw new Exception("Sabor não existente");}
            if(!isset($_GET["ItemExtra"])){throw new Exception("ItemExtra não existente");}
            if(!isset($_GET["ItemBebida"])){throw new Exception("ItemBebida não existente");}
            if(!isset($_GET["BairroCliente"])){throw new Exception("BairroCliente não existente");}
            
            $extraSelecionado = $_GET["last_clicked_button_name"];
            $pizzaria = $_GET["Codg_pizzaria"];
            $tamanho = $_GET["TamanhoPizza"];
            $sabor = $_GET["Sabor"];
            $ItemExtra = $_GET["ItemExtra"];
            $ItemBebida = $_GET["ItemBebida"];
            $bairro = $_GET["BairroCliente"];
            
            if($this->VerificadorStatusItens->verificarExistenciaItensInativos($pizzaria, $tamanho, $sabor, $ItemExtra, $ItemBebida, $bairro,"") == 0){
                $botoes=array();
                $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
                $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
                $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
                $json_str = json_encode($resposta);
                echo $json_str;
            }else{
                $retorno = $this->BebidasPizzaria->adicionaValorVariavelBebida($pizzaria, $ItemBebida, $extraSelecionado);
                echo $retorno;
            }
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
    
    public function buscaFormaPagamento(){
        $this->load->model("modelos/UtilitarioMensagemFacebook");
        $this->load->model("modelos/VerificadorStatusItens");
        $this->load->model("modelos/FormaPagamento");
        $this->load->model("pizzaria/Forma_pagamento_model");
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");}
            if(!isset($_GET["Sabor"])){throw new Exception("Sabor não existente");}
            if(!isset($_GET["ItemExtra"])){throw new Exception("ItemExtra não existente");}
            if(!isset($_GET["ItemBebida"])){throw new Exception("ItemBebida não existente");}
            if(!isset($_GET["BairroCliente"])){throw new Exception("BairroCliente não existente");}
            
            $pizzaria = $_GET["Codg_pizzaria"];
            $tamanho = $_GET["TamanhoPizza"];
            $sabor = $_GET["Sabor"];
            $ItemExtra = $_GET["ItemExtra"];
            $ItemBebida = $_GET["ItemBebida"];
            $bairro = $_GET["BairroCliente"];
            
            if($this->VerificadorStatusItens->verificarExistenciaItensInativos($pizzaria, $tamanho, $sabor, $ItemExtra, $ItemBebida, $bairro,"") == 0){
                $botoes=array();
                $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
                $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
                $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
                $json_str = json_encode($resposta);
                echo $json_str;
            }else{
                $formas = $this->Forma_pagamento_model->buscarFormaPagamentoAtivas($pizzaria);
                if($formas != null){
                    $cartao = $this->FormaPagamento->criaGaleriaFormaPagamento($formas);
                    echo $cartao;
                }else{
                    throw new Exception("buscar Forma Pagamento Ativas, nao retornou nenhum dado. Funcionalidade: buscaFormaPagamento->buscarFormaPagamentoAtivas");
                }
            }
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function setaValorFormaPagamento(){
        $this->load->model("modelos/UtilitarioMensagemFacebook");
        $this->load->model("modelos/VerificadorStatusItens");
        $this->load->model("modelos/FormaPagamento");
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["last_clicked_button_name"])){throw new Exception("last_clicked_button_name não existente");}
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");}
            if(!isset($_GET["Sabor"])){throw new Exception("Sabor não existente");}
            if(!isset($_GET["ItemExtra"])){throw new Exception("ItemExtra não existente");}
            if(!isset($_GET["ItemBebida"])){throw new Exception("ItemBebida não existente");}
            if(!isset($_GET["BairroCliente"])){throw new Exception("BairroCliente não existente");}
            
            $pizzaria = $_GET["Codg_pizzaria"];
            $formaPgSelecionada = $_GET["last_clicked_button_name"];
            $tamanho = $_GET["TamanhoPizza"];
            $sabor = $_GET["Sabor"];
            $ItemExtra = $_GET["ItemExtra"];
            $ItemBebida = $_GET["ItemBebida"];
            $bairro = $_GET["BairroCliente"];

            if($this->VerificadorStatusItens->verificarExistenciaItensInativos($pizzaria, $tamanho, $sabor, $ItemExtra, $ItemBebida, $bairro,"") == 0){
                $botoes=array();
                $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
                $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
                $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
                $json_str = json_encode($resposta);
                echo $json_str;
            }else{
                $formaPagamento = $this->FormaPagamento->encontraFormaPagamento($pizzaria, $formaPgSelecionada);
                if($formaPagamento == null){
                    throw new Exception("buscar Forma Pagamento Descricao Ativas, nao retornou dados. Funcionalidade: setaValorFormaPagamento");
                }
                if($formaPagamento["ativo_forma_pagamento"] != 1){
                    throw new Exception("Forma de pagamento está inativa");
                }
                $retorno = $this->UtilitarioMensagemFacebook->definirAtributosDoUsuario(array("FormaPgCliente" => $formaPagamento["codigo_forma_pagamento"]));
                $json_str = json_encode($retorno);
                echo $json_str; 
            }
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
    
    public function verificarHorarioAtendimentoNormalEspecial(){
        try{
            $this->load->model("modelos/VerificaHorarioAtendimento");
            $this->load->model("modelos/SetVariaveisChat");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            
            $resp = $this->VerificaHorarioAtendimento->AutenticaHorario($pizzaria);
            if($resp != 1){
                $text =  $this->VerificaHorarioAtendimento->MensagemNaoFuncionamento($pizzaria);
                $dados = array('messages' => array(array('text' => $text)));
                    echo json_encode($dados);
            }else{
                echo $this->SetVariaveisChat->mudaValorVariavelUtilitario($resp, "HorarioPermitido");
            }
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        } 
    }
    
    public function solicitaExtraBebidaPizza(){
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            
            $this->load->model("modelos/OutrosCartoesBotoes");
            $cartao = $this->OutrosCartoesBotoes->criaGaleriaOpcoes($pizzaria);
            echo $cartao;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        } 
    }
//inicio bairros
    
    public function mensagemPerguntaBairroCliente(){//1
        try{
            $this->load->model("modelos/UtilitarioGeradorDeJSON");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["NomeBairroCliente"])){throw new Exception("NomeBairroCliente não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $nomeBairro = $_GET["NomeBairroCliente"];
            if($nomeBairro == null || $nomeBairro == ""){
                $resposta = $this->UtilitarioGeradorDeJSON->definirAtributosDoUsuario(array('PerguntaBairro' =>
                    "Preciso verificar se atendemos a sua região. Favor digitar o bairro do endereço de entrega:"));
            }else{
                $resposta = $this->UtilitarioGeradorDeJSON->definirAtributosDoUsuario(array('PerguntaBairro' =>
                    "Me desculpa, tenho algumas dificuldades para entender palavras escritas. Favor digitar novamente seu bairro:"));
            }
            $json_str = json_encode($resposta);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        } 
    }
    
    public function verificaBairroPermitidoPorNome(){ //2 
        try{
            $this->load->model("modelos/VerificaBairroPermitido");
            $this->load->model("modelos/UtilitarioGeradorDeJSON");
            $this->load->model("gerencia/Cidade_model");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["NomeBairroCliente"])){throw new Exception("NomeBairroCliente não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $bairro = $_GET["NomeBairroCliente"];
            
            $permissao = $this->VerificaBairroPermitido->identificaBairroDigitado($bairro, $pizzaria);
            
            if(count($permissao) == 1){
                $cidade = $this->Cidade_model->buscaCidadePorCodigo($permissao[0]["cidade_bairro"]);
                $rapida = array();
                $rapida[] = array("titulo" => "Sim","bloco" => "Fluxo 28");
                $rapida[] = array("titulo" => "Não","bloco" => "Fluxo 26");
                $resposta = $this->UtilitarioGeradorDeJSON->gerarRespostaRapidaAlterandoAtributos(
                        "Seu bairro de entrega é: ".$permissao[0]["descricao_bairro"]." - ".$cidade["descricao_cidade"], array("BairroCliente" => $permissao[0]["codigo_bairro"]) ,$rapida);
            }else if(count($permissao) > 1){
                $rapida = array();
                for($i=0; $i < count($permissao); $i++){
                    $rapida[] = array("titulo" => $permissao[$i]["descricao_bairro"],"bloco" => "Fluxo 28");
                    if($i == 8){
                        break;
                    }
                }
                $rapida[] = array("titulo" => "Não","bloco" => "Fluxo 26");
                $resposta= $this->UtilitarioGeradorDeJSON->gerarRespostaRapida("Não fui capaz de definir o seu bairro. É algum dos bairros abaixo 👇?", $rapida);
            }
            $json_str = json_encode($resposta);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        } 
    }

    public function recebeBairroDigitado(){//3 Recebe Endereco
        try{
            $this->load->model("modelos/VerificaBairroPermitido");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["last_user_freeform_input"])){throw new Exception("last_user_freeform_input não existente");}
            if(!isset($_GET["BairroCliente"])){throw new Exception("BairroCliente não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $bairroSelecionado = $_GET["last_user_freeform_input"];
            $bairro = $_GET["BairroCliente"]; 
            if($bairroSelecionado != "Sim" && $bairro == 0){
                $resposta = $this->VerificaBairroPermitido->buscaBairroPorBotaoSelecionado($bairroSelecionado, $pizzaria, 'BairroCliente');
                $json_str = json_encode($resposta);
                echo $json_str;
            }else{//quando clica em sim eu tenho o bairro do cliente ja, verifico se ta taxado
                $resposta = $this->VerificaBairroPermitido->verificaSeEntregaNoBairro($bairro, $pizzaria, 'BairroCliente');
                $json_str = json_encode($resposta);
                echo $json_str;
            }
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    } 
//fim bairros    
     
    public function verificaTelefoneValido(){
        try{
            $this->load->model("modelos/SetVariaveisChat");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["TelefoneCliente"])){throw new Exception("TelefoneCliente não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $telefone = $_GET["TelefoneCliente"];
            
            $fimTelefone = 0;
            $telefone = str_replace(" ","",$telefone);//retira espacos
            $telefone = str_replace("-","",$telefone);// retira o -
            $telefone = str_replace("(","",$telefone);// retira o (
            $telefone = str_replace(")","",$telefone);// retira o )
            if( $telefone != null && strlen($telefone) >= 8 && is_numeric($telefone) && substr_count($telefone, '0') != strlen($telefone) 
                        && substr_count($telefone, '1') != strlen($telefone) && substr_count($telefone, '2') != strlen($telefone) 
                        && substr_count($telefone, '3') != strlen($telefone) && substr_count($telefone, '4') != strlen($telefone)
                        && substr_count($telefone, '5') != strlen($telefone) && substr_count($telefone, '6') != strlen($telefone)
                        && substr_count($telefone, '7') != strlen($telefone) && substr_count($telefone, '8') != strlen($telefone)
                        && substr_count($telefone, '9') != strlen($telefone) )
            {

                $fimTelefone = 1;
            }else{
                $fimTelefone = 0;
            }
            echo $this->SetVariaveisChat->mudaValorVariavelUtilitario($fimTelefone, "FimTelefone");
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function verificaTrocoValido(){
        try{
            $this->load->model("modelos/SetVariaveisChat");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["CustoTotalPedidoCliente"])){throw new Exception("CustoTotalPedidoCliente não existente");}
            if(!isset($_GET["TrocoCliente"])){throw new Exception("TrocoCliente não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $custoPedido = $_GET["CustoTotalPedidoCliente"];
            $troco = $_GET["TrocoCliente"];          
            $resp = 0;
            
            if(is_numeric($troco)){
                if($troco >= $custoPedido){
                    $resp = 1; 
                }
            }               
            echo $this->SetVariaveisChat->mudaValorVariavelUtilitario($resp, "FimTroco");
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function solicitaDadosPedido(){
        $this->load->model("modelos/GeraOrcamentoPedido");
        $this->load->model("modelos/UtilitarioMensagemFacebook");
        $this->load->model("modelos/VerificadorStatusItens");
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");}
            if(!isset($_GET["Sabor"])){throw new Exception("Sabor não existente");}
            if(!isset($_GET["ItemExtra"])){throw new Exception("ItemExtra não existente");}
            if(!isset($_GET["ItemBebida"])){throw new Exception("ItemBebida não existente");}
            if(!isset($_GET["BairroCliente"])){throw new Exception("BairroCliente não existente");}
            
            $pizzaria = $_GET["Codg_pizzaria"];
            $tamanho = $_GET["TamanhoPizza"];
            $sabor = $_GET["Sabor"];
            $ItemExtra = $_GET["ItemExtra"];
            $ItemBebida = $_GET["ItemBebida"];
            $bairro = $_GET["BairroCliente"];

            if($this->VerificadorStatusItens->verificarExistenciaItensInativos($pizzaria, $tamanho, $sabor, $ItemExtra, $ItemBebida, $bairro,"") == 0){
                $botoes=array();
                $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
                $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
                $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
                $json_str = json_encode($resposta);
                echo $json_str;
            }else{
                $mensagem = $this->GeraOrcamentoPedido->dadosPedidoAtual($pizzaria, $tamanho, $sabor, $ItemExtra, $ItemBebida);
                $resposta = $this->UtilitarioMensagemFacebook->gerarMensagem($mensagem, "");
                $json_str = json_encode($resposta);
                echo $json_str;
            }
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
            
            $this->load->model("modelos/UtilitarioMensagemFacebook");
            $botoes=array();
            $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
            $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
            $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
            $json_str = json_encode($resposta);
            echo $json_str; 
        }
    }
    
    public function solicitaOrcamentoPedido(){
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");}
            if(!isset($_GET["Sabor"])){throw new Exception("Sabor não existente");}
            if(!isset($_GET["ItemExtra"])){throw new Exception("ItemExtra não existente");}
            if(!isset($_GET["ItemBebida"])){throw new Exception("ItemBebida não existente");}
            if(!isset($_GET["BairroCliente"])){throw new Exception("BairroCliente não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $tamanhoAtual = $_GET["TamanhoPizza"];
            $sabor = $_GET["Sabor"];
            $ItemExtra = $_GET["ItemExtra"];
            $ItemBebida = $_GET["ItemBebida"];
            $bairro = $_GET["BairroCliente"];

            $this->load->model("modelos/GeraOrcamentoPedido");
            $resposta = $this->GeraOrcamentoPedido->solicitaOrcamentoPedidoPizza($pizzaria, $tamanhoAtual, $sabor, $ItemExtra, $ItemBebida, $bairro);
            $dados = array('messages' => array(
                    array(
                        'text' => $resposta[1]
                    )
                ));
             $json_str = json_encode($dados);
             echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
            
            $this->load->model("modelos/UtilitarioMensagemFacebook");
            $botoes=array();
            $botoes[] = array('title' => "Recomeçar",'block_names' => array("começar"));
            $mensagem = "Ops, parece que um ou mais itens não está mais disponível.";
            $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($mensagem, "", $botoes);
            $json_str = json_encode($resposta);
            echo $json_str; 
        }
    }
    
    public function setaValorOrcamentoPedido(){
        try{
            $this->load->model("modelos/SetVariaveisChat");
            $this->load->model("modelos/GeraOrcamentoPedido");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");}
            if(!isset($_GET["Sabor"])){throw new Exception("Sabor não existente");}
            if(!isset($_GET["ItemExtra"])){throw new Exception("ItemExtra não existente");}
            if(!isset($_GET["ItemBebida"])){throw new Exception("ItemBebida não existente");}
            if(!isset($_GET["BairroCliente"])){throw new Exception("BairroCliente não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $tamanhoAtual = $_GET["TamanhoPizza"];
            $sabor = $_GET["Sabor"];
            $ItemExtra = $_GET["ItemExtra"];
            $ItemBebida = $_GET["ItemBebida"];
            $bairro = $_GET["BairroCliente"];


            $resposta = $this->GeraOrcamentoPedido->solicitaOrcamentoPedidoPizza($pizzaria, $tamanhoAtual, $sabor, $ItemExtra, $ItemBebida, $bairro);

            echo $this->SetVariaveisChat->mudaValorVariavelUtilitario($resposta[0], "CustoTotalPedidoCliente");
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }

    }
    
    public function incluiPedidoSolicitado(){
        $this->load->model("modelos/VerificaHorarioAtendimento");
        $this->load->model("modelos/PedidoPizzaria");
        $this->load->model("modelos/UtilitarioGeradorDeJSON");
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["TamanhoPizza"])){throw new Exception("TamanhoPizza não existente");}
            if(!isset($_GET["Sabor"])){throw new Exception("Sabor não existente");}
            if(!isset($_GET["ItemExtra"])){throw new Exception("ItemExtra não existente");}
            if(!isset($_GET["ItemBebida"])){throw new Exception("ItemBebida não existente");}
            if(!isset($_GET["BairroCliente"])){throw new Exception("BairroCliente não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $tamanhoPizza = $_GET["TamanhoPizza"];
            $sabor = $_GET["Sabor"];
            $ItemExtra = $_GET["ItemExtra"];
            $ItemBebida = $_GET["ItemBebida"];
            $bairro = $_GET["BairroCliente"];
            
            if(!isset($_GET["first_name"])){throw new Exception("fb_first_name não existente");}
            if(!isset($_GET["last_name"])){throw new Exception("fb_last_name não existente");}
            if(!isset($_GET["chatfuel_user_id"])){throw new Exception("chatfuel_user_id não existente");}
            if(!isset($_GET["TelefoneCliente"])){throw new Exception("TelefoneCliente não existente");}
            if(!isset($_GET["gender"])){throw new Exception("gender não existente");}
            if(!isset($_GET["state"])){throw new Exception("state não existente");}
            $nome = $_GET["first_name"];
            $sobrenome = $_GET["last_name"];
            $fb_id = $_GET["chatfuel_user_id"];
            $telefoneCliente = $_GET["TelefoneCliente"];
            $sexo = $_GET["gender"];
            $estado = $_GET["state"];
            
            if(!isset($_GET["city"])){throw new Exception("city não existente");}
            if(!isset($_GET["zip"])){throw new Exception("zip não existente");}
            if(!isset($_GET["address"])){throw new Exception("address não existente");}
            if(!isset($_GET["EnderecoComplemento"])){throw new Exception("EnderecoComplemento não existente");}
            if(!isset($_GET["CustoTotalPedidoCliente"])){throw new Exception("CustoTotalPedidoCliente não existente");}
            if(!isset($_GET["FormaPgCliente"])){throw new Exception("FormaPgCliente não existente");}
            
            
            $cep = $_GET["zip"];
            if($cep == null || $cep == "" || strlen($cep)<6){
                $cep = 78000000;
            }
            $cidade = $_GET["city"];
            $endereco = $_GET["address"];
            $complementoEndereco = $_GET["EnderecoComplemento"];
            $custoTotalPedido = $_GET["CustoTotalPedidoCliente"];
            $formaPagamento = $_GET["FormaPgCliente"];
            
            if(!isset($_GET["ObservacaoCliente"])){throw new Exception("ObservacaoCliente não existente");}
            if(!isset($_GET["TrocoCliente"])){throw new Exception("TrocoCliente não existente");}
            if(!isset($_GET["map_url"])){throw new Exception("map_url não existente");}
            if(!isset($_GET["messenger_user_id"])){throw new Exception("messenger_user_id não existente");}
            $observacaoCliente = $_GET["ObservacaoCliente"];
            $trocoCliente = $_GET["TrocoCliente"];
            $mapUrl = $_GET["map_url"];
            $messengerId = $_GET["messenger_user_id"];
        
            $resp = $this->VerificaHorarioAtendimento->AutenticaHorario($pizzaria);
            //verificar se a empresa ta no horario de expediente
            if($resp == 1){
                $ok = $this->PedidoPizzaria->incluirPedidoSolicitadoFacebook($pizzaria, $tamanhoPizza, $sabor, $ItemExtra, $ItemBebida, $bairro, $nome, $sobrenome, $fb_id,
                        $telefoneCliente, $sexo, $estado, $cidade, $cep, $endereco, $complementoEndereco, $custoTotalPedido, $formaPagamento, $observacaoCliente, $trocoCliente, $mapUrl);  
                if($ok == 1){
                    $dados =  $this->mensagemFinalizandoPedido($pizzaria);
                    $json_str = json_encode($dados);
                    echo $json_str;
                }else{
                    $texto = "Ops, parece que um ou mais itens não está mais disponível.";
                    $rapida = array();
                    $rapida[] = array("titulo"=>"Refazer Pedido","bloco" => "começar");
                    $dados = $this->UtilitarioGeradorDeJSON->gerarRespostaRapida($texto, $rapida);
                    $json_str = json_encode($dados);
                    echo $json_str;
                }
            }else{
                $dados = array('messages' => array(
                    array(
                        'text' => $this->VerificaHorarioAtendimento->MensagemNaoFuncionamento($pizzaria)
                    )
                ));
             $json_str = json_encode($dados);
             echo $json_str;
            }
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function mensagemFinalizandoPedido($codigoPizza){
        $this->load->model("pizzaria/Empresa_model");
        $this->load->model("gerencia/Valor_configuracao_model");
        $this->load->model("gerencia/Cliente_pizzaria_model");
        //$addres = $_GET["address"];      
        $pizzaria = $this->Empresa_model->buscaPizzariaPorCodigo($codigoPizza);
        $telefone = $pizzaria['telefone_pizzaria'];
        
        $tempo = $this->Empresa_model->buscaConfigEmpresa("tempo_entrega_pizzaria",$codigoPizza);
        $dados = array('messages' => array(
                array(
                    'text' => "Tempo estimado de entrega: ".($tempo)." minutos. Caso não receba uma confirmação do preparo do seu pedido,"
                    . " favor entrar em contato conosco pelo telefone ".$this->formataTelefone($telefone)."."
                )
            ));
         return $dados;
    }
    
    function formataTelefone($numero){
        if(strlen($numero) == 10){
            $ddd = substr($numero, 0, 2);
            $pri = substr($numero, 2, 4);
            $seg = substr($numero, 6);
            $novo = "(".$ddd.")".$pri."-".$seg;
        }else{
            $ddd = substr($numero, 0, 2);
            $pri = substr($numero, 2, 5);
            $seg = substr($numero, 7);
            $novo = "(".$ddd.")".$pri."-".$seg;
        }
        return $novo;
    }
    
    //cancela pedido feito pelo usuario, Mas somente se o pedido não foi atendido
    public function pedidoCancelamentoDoUsuario(){
        $this->load->model("modelos/PedidoPizzaria");
        $this->load->model("pizzaria/Empresa_model");
        $this->load->model("modelos/UtilitarioMensagemFacebook");
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["chatfuel_user_id"])){throw new Exception("chatfuel_user_id não existente");}
            if(!isset($_GET["fluxo"])){throw new Exception("fluxo não existente");}
            
            $pizzaria = $_GET["Codg_pizzaria"];
            $fb_id = $_GET["chatfuel_user_id"];
            $fluxo = $_GET["fluxo"];

            $resp = $this->PedidoPizzaria->solicitarCancelarPedido($pizzaria, $fb_id, $fluxo);
            if($resp == 2){
                $pizzariaTemp = $this->Empresa_model->buscaPizzariaPorCodigo($pizzaria);
                $telefone = $pizzariaTemp['telefone_pizzaria'];
                $msg = "Ops, parece que seu pedido já está sendo atendido e não pode ser cancelado. Qualquer dúvida, entre em contato conosco ".$this->formataTelefone($telefone);
                $dados = $this->UtilitarioMensagemFacebook->definirAtributosDoUsuario(array("mensagem" => $msg));
                $json_str = json_encode($dados);
                echo $json_str;
            }
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    
    /*public function solicitaAdicionarMaisSaboresPizza(){//desativado
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];

            $this->load->model("modelos/SaboresPizza");
            $texto = $this->SaboresPizza->criaCartaoOpcaoMaisSabores();
            echo $texto;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }*/
    
    /*
    public function verificaBairroPermitido(){   
        try{
            $this->load->model("modelos/VerificaBairroPermitido");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["address"])){throw new Exception("address não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $addres = $_GET["address"];
            
            $permissao = $this->VerificaBairroPermitido->analizaLocalCompartilhado($addres, $pizzaria);
            $resposta = $this->VerificaBairroPermitido->finalizaResposta($permissao, $pizzaria);
            $json_str = json_encode($resposta);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        } 
    }*/
    
    /*
    public function respostaAoClienteSobrePedido(){
        $messengerId = $_GET["messenger_user_id"];
        $pizzaria = $_GET["Codg_pizzaria"];
        $user_id = $_GET["chatfuel_user_id"];
        $messengerId = "1266198270132804";
        $token = "BxXmPaeffbIPttnrUQ00m6jjbqHRpi4Mo2jhqeAnswxKz17iKEpKHxd4hqTbHCSB";
        $bot_id ="58a26cbee4b0bd0cc832db73";
        $block_id="58e65f8ae4b064edb6a68492";
        $block_name="Fim";
        $user_atrribute="&mensagem=".'Seu+pedido+esta+sendo+atendido!+:)';
        
       // $url = "https://api.chatfuel.com/bots/58a26cbee4b0bd0cc832db73/users/58a3030fe4b0bd0cca6dfb54/send?"
        //  ."chatfuel_token=BxXmPaeffbIPttnrUQ00m6jjbqHRpi4Mo2jhqeAnswxKz17iKEpKHxd4hqTbHCSB&chatfuel_block_id=58e65f8ae4b064edb6a68492&mensagem=Pronto";
        
        $url ="https://api.chatfuel.com/bots/";
        $url=$url.$bot_id."/";
        $url=$url."users/".$user_id."/send?chatfuel_token=";
        $url=$url.$token;
        $url=$url."&chatfuel_block_name=".$block_name;
        $url=$url.$user_atrribute;
        
        $post = curl_init();
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, true);
        curl_setopt($post, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($post);
        $resp = json_decode($result);
        //echo $resp->{'result'};//ok
       // echo $resp->{'success'};//true ou 1

        if($resp != null && $resp->{'success'} == true){
            echo "Sucesso no envio";
        }else{
            echo "Erro ao enviar msg";
        }
        curl_close($post);
    }*/
}
