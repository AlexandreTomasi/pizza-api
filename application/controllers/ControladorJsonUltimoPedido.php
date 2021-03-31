<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorJsonUltimoPedido
 *
 * @author 033234581
 */
class ControladorJsonUltimoPedido extends CI_Controller{
    //put your code here
    public function verificaFluxo(){
        try{
            $this->load->model("modelos/ControladorFluxo");
            if(!isset($_GET["last_visited_block_name"])){throw new Exception("last_visited_block_name não existente");}
            if(!isset($_GET["fluxo"])){throw new Exception("fluxo não existente");}
            $blocoAtual = $_GET["last_visited_block_name"];
            $fluxo = $_GET["fluxo"];
            echo $this->ControladorFluxo->validaBloco($blocoAtual, $fluxo);

            
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function buscaUltimoPedidoCliente(){
        try{
            $this->load->model("modelos/UltimoPedidoModel");
            $this->load->model("modelos/ControladorFluxo");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["chatfuel_user_id"])){throw new Exception("chatfuel_user_id não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $fb_id = $_GET["chatfuel_user_id"];
            echo $this->UltimoPedidoModel->retornaUltimoPedidoCliente($pizzaria, $fb_id);
            
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function verificaIgredientesAtivosUltimoPedido(){
        try{
            $this->load->model("modelos/UltimoPedidoModel");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["chatfuel_user_id"])){throw new Exception("chatfuel_user_id não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $fb_id = $_GET["chatfuel_user_id"];
            echo $this->UltimoPedidoModel->autorizaUltimoPedido($pizzaria, $fb_id);
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
 // parte referente a bairros   
   /* public function verificaBairroPermitidoUP(){   
        try{
            $this->load->model("modelos/VerificaBairroPermitido");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["address"])){throw new Exception("address não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $addres = $_GET["address"];
            
            $permissao = $this->VerificaBairroPermitido->analizaLocalCompartilhado($addres, $pizzaria);
            $resposta = $this->VerificaBairroPermitido->finalizaRespostaUltimoPedido($permissao, $pizzaria);
            $json_str = json_encode($resposta);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }*/
    
    public function buscaEnderecoDoUltimoPedido(){//1
        try{
            $this->load->model("pizzaria/Pedido_model");
            $this->load->model("pizzaria/Cliente_model");
            $this->load->model("modelos/UtilitarioGeradorDeJSON");
            $this->load->model("modelos/VerificaBairroPermitido");
            
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["chatfuel_user_id"])){throw new Exception("chatfuel_user_id não existente");}
            if(!isset($_GET["PermissaoUP"])){throw new Exception("PermissaoUP não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $fb_id = $_GET["chatfuel_user_id"];
            $permissao = $_GET["PermissaoUP"];
            
            if(intval($permissao) != 0){
                $cliente = $this->Cliente_model->buscarClientesFBid($fb_id, $pizzaria);
                $pedido = null;
                if($cliente["codigo_cliente_pizzaria"] != null){
                    $pedido = $this->Pedido_model->buscarUltimoPedidoCodgCliente($pizzaria, $cliente["codigo_cliente_pizzaria"]);
                }
                if($cliente != null && $pedido != null){
                    $this->load->model("pizzaria/Taxa_entrega_model");
                    $taxa = $this->Taxa_entrega_model->buscarTaxaEntregaPorBairro($pizzaria, $pedido["bairro_pedido"]);

                    if($taxa == null){
                        $resposta= $this->UtilitarioGeradorDeJSON->definirAtributosDoUsuario(array("PermissaoUP" => "0"));
                        $json_str = json_encode($resposta);
                        echo $json_str;
                    }else{
                        $rapida[] = array("titulo" => "Sim","bloco" => "Fluxo 106");
                        $rapida[] = array("titulo" => "Não","bloco" => "Fluxo 104");
                        $resposta= $this->UtilitarioGeradorDeJSON->gerarRespostaRapidaAlterandoAtributos
                                ("Endereço do último pedido: ".$pedido["endereco_pedido"].".\nEntregar nesse endereço?", array("PermissaoUP" => "0"), $rapida);
                        $json_str = json_encode($resposta);
                        echo $json_str;
                    }
                }
            }
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function mensagemPerguntaBairroClienteUP(){//2
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
    
    public function verificaBairroPermitidoPorNomeUP(){//3
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
                $rapida[] = array("titulo" => "Sim","bloco" => "Fluxo 106");
                $rapida[] = array("titulo" => "Não","bloco" => "Fluxo 104");
                $resposta = $this->UtilitarioGeradorDeJSON->gerarRespostaRapidaAlterandoAtributos(
                        "Seu bairro de entrega é: ".$permissao[0]["descricao_bairro"]." - ".$cidade["descricao_cidade"], array("BairroClienteUP" => $permissao[0]["codigo_bairro"]) ,$rapida);
            }else if(count($permissao) > 1){
                $rapida = array();
                for($i=0; $i < count($permissao); $i++){
                    $rapida[] = array("titulo" => $permissao[$i]["descricao_bairro"],"bloco" => "Fluxo 106");
                    if($i == 8){
                        break;
                    }
                }
                $rapida[] = array("titulo" => "Não","bloco" => "Fluxo 104");
                $resposta= $this->UtilitarioGeradorDeJSON->gerarRespostaRapida("Não fui capaz de definir o seu bairro. É algum dos bairros abaixo 👇?", $rapida);
            }
            //$resposta = $this->VerificaBairroPermitido->finalizaResposta($permissao, $pizzaria);
            $json_str = json_encode($resposta);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        } 
    }
 
    public function recebeBairroDigitadoUP(){//4
        try{
            $this->load->model("modelos/VerificaBairroPermitido");
            $this->load->model("modelos/UtilitarioGeradorDeJSON");
            $this->load->model("pizzaria/Pedido_model");
            $this->load->model("pizzaria/Cliente_model");
            $this->load->model("pizzaria/Empresa_model");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["last_user_freeform_input"])){throw new Exception("last_user_freeform_input não existente");}
            if(!isset($_GET["BairroClienteUP"])){throw new Exception("BairroCliente não existente");}
            if(!isset($_GET["chatfuel_user_id"])){throw new Exception("chatfuel_user_id não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $bairroSelecionado = $_GET["last_user_freeform_input"];
            $bairro = $_GET["BairroClienteUP"]; 
             $fb_id = $_GET["chatfuel_user_id"];
             
            if($bairroSelecionado != "Sim" && $bairro == 0){
                $resposta = $this->VerificaBairroPermitido->buscaBairroPorBotaoSelecionado($bairroSelecionado, $pizzaria, 'BairroClienteUP');
                $json_str = json_encode($resposta);
                echo $json_str;
            }
            if($bairroSelecionado == "Sim" && $bairro == 0){
                $cliente = $this->Cliente_model->buscarClientesFBid($fb_id, $pizzaria);
                $pedido = null;
                if($cliente["codigo_cliente_pizzaria"] != null){
                    $pedido = $this->Pedido_model->buscarUltimoPedidoCodgCliente($pizzaria, $cliente["codigo_cliente_pizzaria"]);
                }
                if($cliente != null && $pedido != null){
                    $atributes = array("BairroClienteUP" => $pedido["bairro_pedido"],"EnderecoComplementoUP" => $pedido["endereco_pedido"]);
                    $resposta = $this->UtilitarioGeradorDeJSON->definirAtributosDoUsuario($atributes);
                    $json_str = json_encode($resposta);
                    echo $json_str;
                }
            }else if($bairroSelecionado == "Sim" && $bairro != 0){
                $resposta = $this->VerificaBairroPermitido->verificaSeEntregaNoBairro($bairro, $pizzaria, 'BairroClienteUP');
                $json_str = json_encode($resposta);
                echo $json_str;
            }
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('H:i:s d-m-Y')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
 //fim bairros   
    
    public function verificaTelefoneValidoUP(){
        try{
            $this->load->model("modelos/SetVariaveisChat");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["TelefoneClienteUP"])){throw new Exception("TelefoneClienteUP não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $telefone = $_GET["TelefoneClienteUP"];
            
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
    
    public function solicitaOrcamentoPedidoUP(){
        try{
            $this->load->model("modelos/UltimoPedidoModel");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["chatfuel_user_id"])){throw new Exception("chatfuel_user_id não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $fb_id = $_GET["chatfuel_user_id"];
            echo $this->UltimoPedidoModel->orcamentoUltimoPedido($pizzaria, $fb_id);
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function buscaFormaPagamentoUP(){
        try{
            $this->load->model("modelos/UltimoPedidoModel");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            echo $this->UltimoPedidoModel->buscaFormasPagamentoAtivasUP($pizzaria);
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
    
    public function setaValorFormaPagamentoUP(){
        $this->load->model("modelos/UtilitarioMensagemFacebook");
        try{
            $this->load->model("modelos/UltimoPedidoModel");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["last_clicked_button_name"])){throw new Exception("last_clicked_button_name não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $formaPgSelecionada = $_GET["last_clicked_button_name"];

            
            $formaPagamento = $this->UltimoPedidoModel->encontraFormaPagamentoUP($pizzaria, $formaPgSelecionada);
            if($formaPagamento == null){
                throw new Exception("buscar Forma Pagamento Descricao Ativas, nao retornou dados. Funcionalidade: setaValorFormaPagamento");
            }
            if($formaPagamento["ativo_forma_pagamento"] != 1){
                throw new Exception("Forma de pagamento está inativa");
            }
            $retorno = $this->UtilitarioMensagemFacebook->definirAtributosDoUsuario(array("FormaPgClienteUP" => $formaPagamento["codigo_forma_pagamento"]));
            $json_str = json_encode($retorno);
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
    
    public function verificaTrocoValidoUP(){
        try{
            $this->load->model("modelos/SetVariaveisChat");
            $this->load->model("pizzaria/Pedido_model");
            $this->load->model("pizzaria/Cliente_model");
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["chatfuel_user_id"])){throw new Exception("chatfuel_user_id não existente");}
            if(!isset($_GET["TrocoClienteUP"])){throw new Exception("TrocoClienteUP não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $fb_id = $_GET["chatfuel_user_id"];
            $troco = $_GET["TrocoClienteUP"];
            
            $cliente = $this->Cliente_model->buscarClientesFBid($fb_id, $pizzaria);
            $pedido = $this->Pedido_model->buscarUltimoPedidoCodgCliente($pizzaria, $cliente["codigo_cliente_pizzaria"]);
            $custoPedido = $pedido["valor_total_pedido"];

            $resp = 0;
            if($troco >= $custoPedido){
               $resp = 1; 
            }
            echo $this->SetVariaveisChat->mudaValorVariavelUtilitario($resp, "FimTroco");
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }  
    }
    
    public function incluiPedidoSolicitadoUP(){
        $this->load->model("modelos/VerificaHorarioAtendimento");
        $this->load->model("modelos/PedidoPizzaria");
        $this->load->model("modelos/UltimoPedidoModel");
        $this->load->model("modelos/UtilitarioGeradorDeJSON");
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["chatfuel_user_id"])){throw new Exception("chatfuel_user_id não existente");}
            if(!isset($_GET["TelefoneClienteUP"])){throw new Exception("TelefoneClienteUP não existente");}
            if(!isset($_GET["state"])){throw new Exception("state não existente");}
            if(!isset($_GET["city"])){throw new Exception("city não existente");}
            if(!isset($_GET["zip"])){throw new Exception("zip não existente");}
            if(!isset($_GET["address"])){throw new Exception("address não existente");}
            if(!isset($_GET["EnderecoComplementoUP"])){throw new Exception("EnderecoComplementoUP não existente");}
            if(!isset($_GET["FormaPgClienteUP"])){throw new Exception("FormaPgClienteUP não existente");}
            if(!isset($_GET["ObservacaoClienteUP"])){throw new Exception("ObservacaoClienteUP não existente");}
            if(!isset($_GET["messenger_user_id"])){throw new Exception("messenger_user_id não existente");}
            if(!isset($_GET["TrocoClienteUP"])){throw new Exception("TrocoClienteUP não existente");}         
            if(!isset($_GET["map_url"])){throw new Exception("map_url não existente");}
            if(!isset($_GET["BairroClienteUP"])){throw new Exception("BairroCliente não existente");}
            $pizzaria = $_GET["Codg_pizzaria"];
            $fb_id = $_GET["chatfuel_user_id"];
            $telefoneCliente = $_GET["TelefoneClienteUP"];
            $estado = $_GET["state"];
            $cidade = $_GET["city"];
            $cep = $_GET["zip"];
            $bairro = $_GET["BairroClienteUP"]; 
            if($cep == null || $cep == "" || strlen($cep)<6){
                $cep = 78000000;
            }
            
            $endereco = $_GET["address"];
            $complementoEndereco = $_GET["EnderecoComplementoUP"];
            $formaPagamento = $_GET["FormaPgClienteUP"];
            $observacaoCliente = $_GET["ObservacaoClienteUP"];
            $messengerId = $_GET["messenger_user_id"];
            $trocoCliente = $_GET["TrocoClienteUP"];
            $mapUrl = $_GET["map_url"];

            $resp = $this->VerificaHorarioAtendimento->AutenticaHorario($pizzaria);
            
            //verificar se a empresa ta no horario de expediente
            if($resp != null && $resp == 1){
                if($formaPagamento != null && $formaPagamento == 0){
                    throw new Exception("Dados incompletos na hora de reincerir ultimo pedido (forma de pagamento inexistente)");
                }
                $ok = $this->UltimoPedidoModel->repetirUltimoPedido($pizzaria, $fb_id, $telefoneCliente, $estado, $cidade, $cep, $endereco, $complementoEndereco,
                        $formaPagamento, $observacaoCliente, $trocoCliente, $mapUrl, $bairro);  
                
                if($ok != null && $ok == 1){
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
        $tempo = $this->Empresa_model->buscaConfigEmpresa("tempo_entrega_pizzaria",intval($codigoPizza));
        $telefone = $pizzaria['telefone_pizzaria'];
        
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
}