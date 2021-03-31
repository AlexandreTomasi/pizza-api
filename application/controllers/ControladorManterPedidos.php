<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorManterPedidos
 *
 * @author 033234581
 */
class ControladorManterPedidos extends CI_Controller{
    //put your code here
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
    public function buscarPedidos(){
        $this->verificaUsuarioLogado();
        $this->load->helper(array("currency"));
        $this->load->view("pizzaria/ViewManterPedido.php"); 
    }
    public function buscarPedidosPorCodigo(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Pedido_model");
        $this->load->model("pizzaria/Cliente_model"); 
        $this->load->model("pizzaria/Forma_pagamento_model");
        $this->load->model("gerencia/Cidade_model");
        $this->load->model("gerencia/Bairro_model");
        $this->load->model("modelos/FormataImpressaoPedido");
        $this->load->model("pizzaria/Empresa_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_pedido"};
            $itensInativos = "";

            $pedido = $this->Pedido_model->buscarPedidosCodgPedido($codigo, $empresaLogada["codigo_pizzaria"]);     
            if($pedido["status_pedido"] == 0){
                    $pedido["status_pedido"] = "Cancelado";
            }else if($pedido["status_pedido"] == 1){
                    $pedido["status_pedido"] = "Solicitado";
            }else if($pedido["status_pedido"] == 2){
                    $pedido["status_pedido"] = "Pedido Atendido";
            }
             //colocando nome dos clientes                          
            $cliente = $this->Cliente_model->buscarClientePorCodigo($pedido["cliente_pizzaria_pedido"], $pedido["pizzaria_pedido"]); 
            $itensInativos = $this->verificaExisteItemInativo($empresaLogada["codigo_pizzaria"], $cliente, $pedido);
            $tabela = $this->retornaDadosUltimoPedidoClienteTabelado($empresaLogada["codigo_pizzaria"], $cliente, $pedido); 
            $pedido["nome_cliente"] = $cliente["nome_cliente_pizzaria"];
            $pedido["data_hora_pedido"] = date_format(date_create($pedido["data_hora_pedido"]), 'H:i:s d/m/Y');

            $forma = $this->Forma_pagamento_model->buscarFormaPagamentoPorId($pedido["forma_pagamento_pedido"], $empresaLogada["codigo_pizzaria"]); 
            $pedido["forma_pagamento_pedido"] = $forma["descricao_forma_pagamento"];

            $pedido["valor_total_pedido"] = "R$ ".$pedido["valor_total_pedido"];
            
            $cidade = $this->Cidade_model->buscaCidadePorCodigo($pedido["cidade_pedido"]);
            $pedido["cidade_pedido"] = $cidade["descricao_cidade"];

            $bairro = $this->Bairro_model->buscaBairrosPorCodigo($pedido["bairro_pedido"]);
            $pedido["bairro_pedido"] = $bairro["descricao_bairro"];
            if($pedido["numero_endereco_pedido"] != null && $pedido["numero_endereco_pedido"] != ""){
                $pedido["endereco_pedido"] = $pedido["endereco_pedido"].". Número: ".$pedido["numero_endereco_pedido"];
            }
            if($pedido["complemento_endereco_pedido"] != null && $pedido["complemento_endereco_pedido"] != ""){
                $pedido["endereco_pedido"] = $pedido["endereco_pedido"].". Complemento: ".$pedido["complemento_endereco_pedido"];
            }
            
            if($pedido == null){
                throw new Exception("Pedido nulo");
            }
            $dados = array();
            $dados[0] = $pedido;
            if($tabela != null && count($tabela) > 0){
                $dados[1] = $tabela;
            }
            // buscar o pedido formatado para imprimir.
            if(intval($this->Empresa_model->buscaConfigEmpresa("tipo_formato_de_impressao",$empresaLogada["codigo_pizzaria"])) == 2){
                $entregador = $this->FormataImpressaoPedido->viaEntregador($empresaLogada["codigo_pizzaria"], $codigo);
                $cozinha = $this->FormataImpressaoPedido->viaCozinha($empresaLogada["codigo_pizzaria"], $codigo);
                if($entregador != null && $cozinha != null){
                    $dados[2] = array(0 => $cozinha, 1 => $entregador);
                }
            }
            if($itensInativos != null && $itensInativos != ""){
                $dados[3] = $itensInativos;
            }
            
            $json_str = json_encode($dados);
            echo $json_str;
        }catch (Exception $e){
                $fp = fopen("log_skybots.txt", "a");
                $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
                fclose($fp); 
                throw new Exception($e->getMessage());
        }
    }
    
    
    public function cancelarPedido(){
        $this->verificaUsuarioLogado();       
        $this->load->model("pizzaria/Pedido_model");
        $this->load->model("pizzaria/Cliente_model"); 
        $this->load->model("modelos/MensagemParaUsuarioFace");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_pedido"};
            $mensagem = $json->{"mensagem_cancelamento"};
            $pedido = $this->Pedido_model->buscarPedidosCodgPedido($codigo, $empresaLogada["codigo_pizzaria"]);
            $pedido["status_pedido"] = 0;

            if( !($this->Pedido_model->alterarPedido($pedido, $empresaLogada["codigo_pizzaria"])) ){
                throw new Exception("Erro ao cancelar pedido.");
            }  
            $msg = " Desculpe-nos pelo transtorno.";
            $cliente = $this->Cliente_model->buscarClientePorCodigo($pedido["cliente_pizzaria_pedido"], $pedido["pizzaria_pedido"]); 
            if( strcasecmp(($this->MensagemParaUsuarioFace->mensagemAoClienteFacebook($cliente["id_facebook_cliente_pizzaria"], "Seu pedido foi cancelado. Motivo: ".$mensagem.$msg, $empresaLogada["codigo_pizzaria"])), "Erro" ) == 0 ){
                throw new Exception("Erro ao comunicar cliente.");
            }
        }catch (Exception $e){
                $fp = fopen("log_skybots.txt", "a");
                $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
                fclose($fp); 
                throw new Exception($e->getMessage());
        }
    } 
    
    public function atenderPedidoAvisaCliente(){
        $this->verificaUsuarioLogado();       
        $this->load->model("pizzaria/Pedido_model");
        $this->load->model("modelos/MensagemParaUsuarioFace");
        $this->load->model("pizzaria/Cliente_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_pedido"};
            $pedido = $this->Pedido_model->buscarPedidosCodgPedido($codigo, $empresaLogada["codigo_pizzaria"]);
            if($pedido["status_pedido"] == 0){
                throw new Exception("Não foi possivel atender o pedido, pois se encontra cancelado.");
            }
            $pedido["status_pedido"] = 2;

            if( !($this->Pedido_model->alterarPedido($pedido, $empresaLogada["codigo_pizzaria"])) ){
                throw new Exception("Erro ao atender pedido.");
            }
             
            $cliente = $this->Cliente_model->buscarClientePorCodigo($pedido["cliente_pizzaria_pedido"], $pedido["pizzaria_pedido"]); 
            if( strcasecmp(($this->MensagemParaUsuarioFace->respostaAoClienteSobrePedido($cliente["id_facebook_cliente_pizzaria"], $empresaLogada["codigo_pizzaria"])), "Erro" ) == 0 ){
                throw new Exception("Erro ao comunicar cliente.");
            }
        }catch (Exception $e){
                $fp = fopen("log_skybots.txt", "a");
                $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
                fclose($fp); 
                throw new Exception($e->getMessage());
        }
    }
    
    public function buscarPedidosConstantemente(){
        //$this->verificaUsuarioLogado();
        $this->load->helper(array("currency"));
        $empresaLogada = $this->session->userdata("empresa_logada");
        if($empresaLogada == null){
            $this->session->unset_userdata("empresa_logada");
            $this->session->set_flashdata("sucess" , "Sessão Expirada. Por favor logue novamente");
            //redirect('/');
            echo "Sessão expirada";
            return "";
        }
        $this->load->model("pizzaria/Pedido_model");
        $this->load->model("pizzaria/Cliente_model");
        
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $dataInicial = $json->{"dataInicial"};
            $dataFinal = $json->{"dataFinal"};

                
            $resp = $this->Pedido_model->buscarPedidosRecentesCodgPizzaria($empresaLogada["codigo_pizzaria"], $dataInicial, $dataFinal);// definir quantidade de dias atras

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
                $cliente = $this->Cliente_model->buscarClientePorCodigo($resp[$i]["cliente_pizzaria_pedido"], $resp[$i]["pizzaria_pedido"]); 
                $resp[$i]["nome_cliente"] = $cliente["nome_cliente_pizzaria"];

                $resp[$i]["data_hora_pedido"] = date_format(date_create($resp[$i]["data_hora_pedido"]), 'H:i:s  d/m/Y');
            }

            $json_str = json_encode($resp);
            echo $json_str;
        }catch (Exception $e){
                $fp = fopen("log_skybots.txt", "a");
                $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
                fclose($fp); 
                throw new Exception($e->getMessage());
        }
    }
    
    //put your code here
    public function retornaUltimoPedidoCliente($pizzaria, $cliente, $pedido){
        $this->verificaUsuarioLogado(); 
        $this->load->model("pizzaria/Bebida_model");
        $this->load->model("pizzaria/Item_pedido_model");
        $this->load->model("pizzaria/Pizza_model");
        $this->load->model("pizzaria/Item_extra_pizza_model");
        $this->load->model("pizzaria/Item_pizza_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Taxa_entrega_model");
        $this->load->model("pizzaria/Valor_pizza_model");
        $this->load->model("pizzaria/Tipo_extra_model");
        try{
            $resposta = "Pedido do ".$cliente["nome_cliente_pizzaria"]." é:";
            $respBebida = "\nBebidas: ";
            $custoTotal = 0;

            if($cliente != null && $pedido != null){
                $todosItemPedido = $this->Item_pedido_model->buscarItemPedidoPorPedido($pedido["codigo_pedido"]);
                for($i=0; $i < count($todosItemPedido); $i++){
                    if($todosItemPedido[$i]["bebida_item_pedido"] != null){
                        $bebidas =  $this->Bebida_model->buscarBebidaPorId($todosItemPedido[$i]["bebida_item_pedido"]);
                        $respBebida = $respBebida.$bebidas["descricao_bebida"]." - valor R$".$bebidas["preco_bebida"].", ";// desce modo preço ja é o atual
                        $custoTotal = $custoTotal+$bebidas["preco_bebida"];
                    }else if($todosItemPedido[$i]["pizza_item_pedido"] != null){
                        $pizza = $this->Pizza_model->buscaPizzaPorCodigo( $todosItemPedido[$i]["pizza_item_pedido"]);
                        $tamanhoPizza=$this->Pizza_tamanho_model->buscarTamanhoCodigo($pizza["tamanho_pizza_pizza"]);

                        $resposta=$resposta."\nPizza ".$tamanhoPizza["descricao_tamanho_pizza"]." Sabor "; 
                        $todosSabores = $this->Item_pizza_model->buscarItemPizzaPorCodigoPizza($pizza["codigo_pizza"]);
                        $preco=0;
                        for($j=0; $j < count($todosSabores);$j++){
                            $sabor = $this->Pizza_sabor_model->buscarSaborCodigo($todosSabores[$j]["sabor_pizza_item_pizza"]);
                            $preco = $preco + $this->Valor_pizza_model->buscarValorPizzaAtivo($pizzaria, $sabor["codigo_sabor_pizza"], $tamanhoPizza["codigo_tamanho_pizza"]);
                            if($j+1 == count($todosSabores)){
                                $resposta=$resposta.$sabor["descricao_sabor_pizza"]." - ";
                            }else{
                                if($j+2 == count($todosSabores)){
                                    $resposta=$resposta.$sabor["descricao_sabor_pizza"]." e ";
                                }else{
                                    $resposta=$resposta.$sabor["descricao_sabor_pizza"].", ";
                                }
                            }                   
                        }
                        $valorPizza = 0;
                        if($preco != 0){
                            $valorP = $preco/count($todosSabores);
                            $custoTotal = $custoTotal + $valorP;
                            $valorPizza = $valorP;
                            $valorP = number_format($valorP, 2, '.', '');
                            $resposta = $resposta."valor R$ ".$valorP.".";
                        }

                       $soma=0;
                        $todosItemExtras = $this->Item_extra_pizza_model->buscaItemExtraPizzaPorCodigoPizza($pizza["codigo_pizza"]);
                        if($todosItemExtras != null){
                            $resposta=$resposta." Com: ";
                            for($j=0; $j < count($todosItemExtras);$j++){
                                $extra = $this->Pizza_extra_model->buscarExtraCodigo($todosItemExtras[$j]["extra_pizza_item_extra_pizza"], $pizzaria);
                                $tipo = $this->Tipo_extra_model->buscarTipoExtraPorId($extra["tipo_extra_pizza_extra_pizza"], $pizzaria);
                                $custoTotal = $custoTotal + $extra["preco_extra_pizza"];
                                $soma = $extra["preco_extra_pizza"]+$soma;
                                if($j+1 == count($todosItemExtras)){
                                    $resposta=$resposta.$tipo["descricao_tipo_extra_pizza"]." ".$extra["descricao_extra_pizza"]." - valor R$".$extra["preco_extra_pizza"].". ";
                                }else{
                                    if($j+2 == count($todosItemExtras)){
                                        $resposta=$resposta.$tipo["descricao_tipo_extra_pizza"]." ".$extra["descricao_extra_pizza"]." - valor R$".$extra["preco_extra_pizza"]." e ";
                                    }else{
                                        $resposta=$resposta.$tipo["descricao_tipo_extra_pizza"]." ".$extra["descricao_extra_pizza"]." - valor R$".$extra["preco_extra_pizza"].", ";
                                    }
                                }
                            }   
                        }
                        $subpreco = number_format(( $todosItemPedido[$i]["valor_subtotal_item_pedido"] ), 2, '.', '');
                        $resposta=$resposta."\nValor subTotal R$ ".$subpreco;

                    } 
                }
                if($respBebida != "Bebidas: "){
                    $oco = strrpos($respBebida,",");
                    $respBebida = substr_replace($respBebida, '.', $oco);
                    $resposta=$resposta.$respBebida; 
                }

                // descobrindo custo da taxa de entrega
                $taxa = $this->Taxa_entrega_model->buscarTaxaEntregaPorBairro($pizzaria, $pedido["bairro_pedido"]);
                $resposta = $resposta."\nTaxa de entrega: R$".$taxa["preco_taxa_entrega"];
                $custoTotal = $custoTotal +$taxa["preco_taxa_entrega"];
                $custoTotal = number_format($custoTotal, 2, '.', '');

                return $resposta;
            }else{
                throw new Exception("Dados nulos (cliente ou pedido)");
            }
        }catch (Exception $e){
                $fp = fopen("log_skybots.txt", "a");
                $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
                fclose($fp); 
                throw new Exception($e->getMessage());
        }
    }
    
    public function verificarHorarioAtendimentoNormalEspecial(){
        $this->verificaUsuarioLogado();       
        $this->load->model("modelos/VerificaHorarioAtendimento");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $resp = $this->VerificaHorarioAtendimento->AutenticaHorario($empresaLogada["codigo_pizzaria"]);
            if($resp != 1){
                echo json_encode(0);   
            }else{
                echo json_encode(1);
            }
        }catch (Exception $e){
                $fp = fopen("log_skybots.txt", "a");
                $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
                fclose($fp); 
                throw new Exception($e->getMessage());
        }
    }
    
    
    public function retornaDadosUltimoPedidoClienteTabelado($pizzaria, $cliente, $pedido){
        $this->verificaUsuarioLogado(); 
        $this->load->model("pizzaria/Bebida_model");
        $this->load->model("pizzaria/Item_pedido_model");
        $this->load->model("pizzaria/Pizza_model");
        $this->load->model("pizzaria/Item_extra_pizza_model");
        $this->load->model("pizzaria/Item_pizza_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Taxa_entrega_model");
        $this->load->model("pizzaria/Valor_pizza_model");
        $this->load->model("pizzaria/Tipo_extra_model");
        try{
            $resposta = array();
            $itenBebida = array();
            $itenPizza = array();
            $resp = "";
            $bebidasPedidas = array();

            if($cliente != null && $pedido != null && $pedido["codigo_pedido"] != null){
                $todosItemPedido = $this->Item_pedido_model->buscarItemPedidoPorPedido($pedido["codigo_pedido"]);
                if($todosItemPedido == null){throw new Exception("(controladorManterPedidos) metodo retornaDadosUltimoPedidoClienteTabelado, todosItemPedido  está vazio");}
                for($i=0; $i < count($todosItemPedido); $i++){
                    if($todosItemPedido[$i]["bebida_item_pedido"] != null){
                        $bebidasPedidas[] = array("codigo" => $todosItemPedido[$i]["bebida_item_pedido"], "quantidade" => $todosItemPedido[$i]["quantidade_item_pedido"]);
                       // $bebidas =  $this->Bebida_model->buscarBebidaPorId($todosItemPedido[$i]["bebida_item_pedido"]);
                        //$respBebida = $bebidas["descricao_bebida"].", ";// desce modo preço ja é o atual
                        //$itenBebida[]=array("quantidade"=> 1, "produto" => $bebidas["descricao_bebida"], "preco_unitario" => "R$ ".$bebidas["preco_bebida"], "preco_total" => "R$ ".$bebidas["preco_bebida"]);
                    }else if($todosItemPedido[$i]["pizza_item_pedido"] != null){
                        $pizza = $this->Pizza_model->buscaPizzaPorCodigo( $todosItemPedido[$i]["pizza_item_pedido"]);
                        $tamanhoPizza=$this->Pizza_tamanho_model->buscarTamanhoPorCodigoAtivoInativo($pizza["tamanho_pizza_pizza"], $pizzaria);

                        $resp="Pizza ".$tamanhoPizza["descricao_tamanho_pizza"]." Sabor "; 
                        $todosSabores = $this->Item_pizza_model->buscarItemPizzaPorCodigoPizza($pizza["codigo_pizza"]);
                        $preco=0;
                        for($j=0; $j < count($todosSabores);$j++){
                            $sabor = $this->Pizza_sabor_model->buscarSaborPorIdAtivoInativo($todosSabores[$j]["sabor_pizza_item_pizza"], $pizzaria);
                            $preco = $preco + $this->Valor_pizza_model->buscarValorPizzaAtivo($pizzaria, $sabor["codigo_sabor_pizza"], $tamanhoPizza["codigo_tamanho_pizza"]);
                            if($j+1 == count($todosSabores)){
                                $resp=$resp.$sabor["descricao_sabor_pizza"];
                            }else{
                                if($j+2 == count($todosSabores)){
                                    $resp=$resp.$sabor["descricao_sabor_pizza"]." e ";
                                }else{
                                    $resp=$resp.$sabor["descricao_sabor_pizza"].", ";
                                }
                            }                   
                        }

                       $soma=0;
                        $todosItemExtras = $this->Item_extra_pizza_model->buscaItemExtraPizzaPorCodigoPizza($pizza["codigo_pizza"]);
                        if($todosItemExtras != null){
                            $resp=$resp." Com: ";
                            for($j=0; $j < count($todosItemExtras);$j++){
                                $extra = $this->Pizza_extra_model->buscarExtraIdAtivoInativo($todosItemExtras[$j]["extra_pizza_item_extra_pizza"], $pizzaria);
                                $tipo = $this->Tipo_extra_model->buscarTipoExtraPorId($extra["tipo_extra_pizza_extra_pizza"], $pizzaria);
                                $soma = $extra["preco_extra_pizza"]+$soma;
                                if($j+1 == count($todosItemExtras)){
                                    $resp=$resp.$tipo["descricao_tipo_extra_pizza"]." ".$extra["descricao_extra_pizza"].". ";
                                }else{
                                    if($j+2 == count($todosItemExtras)){
                                        $resp=$resp.$tipo["descricao_tipo_extra_pizza"]." ".$extra["descricao_extra_pizza"]." e ";
                                    }else{
                                        $resp=$resp.$tipo["descricao_tipo_extra_pizza"]." ".$extra["descricao_extra_pizza"].", ";
                                    }
                                }
                            }   
                        }
                        $subpreco = number_format(( $todosItemPedido[$i]["valor_subtotal_item_pedido"] ), 2, '.', '');
                        $itenPizza[] = array("quantidade" => 1, "produto" => $resp, "preco_unitario" => "R$ ".$subpreco,"preco_total" => "R$ ".$subpreco); 
                    } 
                }
                // colocando as bebidas
                if($bebidasPedidas != null && count($bebidasPedidas)){
                    for($i=0; $i < count($bebidasPedidas); $i++){ 
                        $bebida =  $this->Bebida_model->buscarBebidaPorIdAtivaInativa($bebidasPedidas[$i]["codigo"], $pizzaria);
                        $itenPizza[] = array("quantidade"=> intval($bebidasPedidas[$i]["quantidade"]), "produto" => $bebida["descricao_bebida"],
                            "preco_unitario" => "R$ ".$bebida["preco_bebida"], "preco_total" => "R$ ".number_format(( $bebida["preco_bebida"] * $bebidasPedidas[$i]["quantidade"]), 2, '.', '') );
                    }
                }
                // colocando valor da taxa de entrega
                $taxa = $this->Taxa_entrega_model->buscarTaxaEntregaPorBairro($pizzaria, $pedido["bairro_pedido"]);
                $itenPizza[] = array("quantidade" => 1, "produto" => "Taxa de entrega", "preco_unitario" => "R$ ".$taxa["preco_taxa_entrega"],"preco_total" => "R$ ".$taxa["preco_taxa_entrega"]); 
              
                return $itenPizza;
            }else{
                throw new Exception("Dados nulos (cliente ou pedido)");
            }
        }catch (Exception $e){
                $fp = fopen("log_skybots.txt", "a");
                $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - Codigo pizzaria: ".($pizzaria)." erro: ".$e->getMessage());
                fclose($fp); 
                throw new Exception($e->getMessage());
        }
    }
    
    public function verificaExisteItemInativo($pizzaria, $cliente, $pedido){
        $this->verificaUsuarioLogado(); 
        $this->load->model("pizzaria/Bebida_model");
        $this->load->model("pizzaria/Item_pedido_model");
        $this->load->model("pizzaria/Pizza_model");
        $this->load->model("pizzaria/Item_extra_pizza_model");
        $this->load->model("pizzaria/Item_pizza_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Taxa_entrega_model");
        $this->load->model("pizzaria/Valor_pizza_model");
        $this->load->model("pizzaria/Tipo_extra_model");
        $this->load->model("gerencia/Bairro_model");
        $this->load->model("pizzaria/Forma_pagamento_model");
        try{
            $msg = "O pedido possui os seguintes itens inativos:\n";
            $resp = $msg;
            $bebidasPedidas = array();
            $extraVet = array();
            $tamanVet = array();
            $saborVet = array();
            if($cliente != null && $pedido != null && $pedido["codigo_pedido"] != null){
                $todosItemPedido = $this->Item_pedido_model->buscarItemPedidoPorPedido($pedido["codigo_pedido"]);
                if($todosItemPedido == null){throw new Exception("(controladorManterPedidos) metodo retornaDadosUltimoPedidoClienteTabelado, todosItemPedido  está vazio");}
                for($i=0; $i < count($todosItemPedido); $i++){
                    if($todosItemPedido[$i]["bebida_item_pedido"] != null){
                        $bebidasPedidas[] = array("codigo" => $todosItemPedido[$i]["bebida_item_pedido"], "quantidade" => $todosItemPedido[$i]["quantidade_item_pedido"]);
                    }else if($todosItemPedido[$i]["pizza_item_pedido"] != null){
                        $pizza = $this->Pizza_model->buscaPizzaPorCodigo( $todosItemPedido[$i]["pizza_item_pedido"]);
                        $tamanhoPizza=$this->Pizza_tamanho_model->buscarTamanhoPorCodigoAtivoInativo($pizza["tamanho_pizza_pizza"], $pizzaria);
                        if($tamanhoPizza["ativo_tamanho_pizza"] == 0){
                            $pm= false;
                            for($z=0; $z < count($tamanVet); $z++){
                                if($tamanVet[$z] == $tamanhoPizza["codigo_tamanho_pizza"]){
                                    $pm=true;
                                }
                            }
                            if($pm == false){
                                $tamanVet[] = $tamanhoPizza["codigo_tamanho_pizza"];
                                $resp .= $tamanhoPizza["descricao_tamanho_pizza"]."\n";
                            }
                        }

                        $todosSabores = $this->Item_pizza_model->buscarItemPizzaPorCodigoPizza($pizza["codigo_pizza"]);
                        $preco=0;
                        for($j=0; $j < count($todosSabores);$j++){
                            $sabor = $this->Pizza_sabor_model->buscarSaborPorIdAtivoInativo($todosSabores[$j]["sabor_pizza_item_pizza"], $pizzaria);
                            if($sabor["ativo_sabor_pizza"] == 0 ){
                                $pm= false;
                                for($z=0; $z < count($saborVet); $z++){
                                    if($saborVet[$z] == $sabor["codigo_sabor_pizza"]){
                                        $pm=true;
                                    }
                                }
                                if($pm == false){
                                    $saborVet[] = $sabor["codigo_sabor_pizza"];
                                    $resp .= $sabor["descricao_sabor_pizza"]."\n";
                                }
                            }             
                        }

                        $todosItemExtras = $this->Item_extra_pizza_model->buscaItemExtraPizzaPorCodigoPizza($pizza["codigo_pizza"]);
                        if($todosItemExtras != null){
                            for($j=0; $j < count($todosItemExtras);$j++){
                                $extra = $this->Pizza_extra_model->buscarExtraIdAtivoInativoJoinTipo($todosItemExtras[$j]["extra_pizza_item_extra_pizza"], $pizzaria);
                                if($extra["ativo_extra_pizza"] == 0 ){   
                                    $pm= false;
                                    for($z=0; $z < count($extraVet); $z++){
                                        if($extraVet[$z] == $extra["codigo_extra_pizza"]){
                                            $pm=true;
                                        }
                                    }
                                    if($pm == false){
                                        $extraVet[]=$extra["codigo_extra_pizza"];
                                        $resp .= $extra["descricao_tipo_extra_pizza"]." - ".$extra["descricao_extra_pizza"]."\n";
                                    }
                                }
                            }   
                        }
                    } 
                }
                if($bebidasPedidas != null && count($bebidasPedidas)){
                    for($i=0; $i < count($bebidasPedidas); $i++){ 
                        $bebida =  $this->Bebida_model->buscarBebidaPorIdAtivaInativa($bebidasPedidas[$i]["codigo"], $pizzaria);
                        if($bebida["ativo_bebida"] == 0){
                            $resp .= "Bebida - ".$bebida["descricao_bebida"]."\n";
                        }
                    }
                }
                
                // verificar forma de pagamento
                $forma = $this->Forma_pagamento_model->buscarFormaPagamentoAtivaInativa($pedido["forma_pagamento_pedido"], $pizzaria);
                if($forma == null){
                    $resp .= "Forma de pagamento.";
                }else if($forma["ativo_forma_pagamento"] == 0){
                    $resp .= "Forma de pagamento: ".$forma["descricao_forma_pagamento"]."\n";
                }
                
                // colocando valor da taxa de entrega
                $taxa = $this->Taxa_entrega_model->buscarTaxaEntregaPorBairroAtivoInativo($pizzaria, $pedido["bairro_pedido"]);
                if($taxa == null || intval($taxa["ativo_taxa_entrega"]) == 0 ){
                    $bairro = $this->Bairro_model->buscaBairrosPorCodigo($pedido["bairro_pedido"]);
                    $resp .= "Taxa de entrega do bairro: ".$bairro["descricao_bairro"];
                }
                
                if($resp == $msg){
                    $resp = "";
                }
                return $resp;
            }else{
                throw new Exception("Dados nulos (cliente ou pedido)");
            }
        }catch (Exception $e){
                $fp = fopen("log_skybots.txt", "a");
                $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - Codigo pizzaria: ".($pizzaria)." erro: ".$e->getMessage());
                fclose($fp); 
                throw new Exception($e->getMessage());
        }
    }
    
    public function pausarChatBot(){
        $this->verificaUsuarioLogado();
        date_default_timezone_set('America/Cuiaba');
        $this->load->model("pizzaria/HorariosEmpresa");
        $empresaLogada = $this->session->userdata("empresa_logada");
        $meuPost = file_get_contents("php://input");
        $json = json_decode( $meuPost );
        $minutos = $json->{"minutos"};
        try{
            $ini = date_format(date_create(date('H:i:s')), 'H:i:s');
            $diaEspecial = date_format(date_create(date('Y-m-d')), 'Y-m-d');
            $horaNova = strtotime("+ $minutos minutes");
            $horaNovaFormatada = date("H:i:s",$horaNova);
            $fim = date_format(date_create($horaNovaFormatada), 'H:i:s');
            
            $hora=array();
            // ou seja passou da meia noite o fim, ja é outro dia
            if($fim < $ini){            
                $fimDia=date_format(date_create("23:59:59"), 'H:i:s');
                $Horario1 = array(
                    "data_horario_especial" => $diaEspecial,
                    "inicio_horario_especial" => $ini,
                    "fim_horario_especial" => $fimDia,
                    "pizzaria_horario_especial" => $empresaLogada["codigo_pizzaria"],
                    "aberto_horario_especial" => 2,
                    "ativo_horario_especial" => 1
                    );
                $iniDia=date_format(date_create("00:00:01"), 'H:i:s');

                $Horario2 = array(
                    "data_horario_especial" => date('Y-m-d',strtotime("+1 days",strtotime($diaEspecial))),
                    "inicio_horario_especial" => $iniDia,
                    "fim_horario_especial" => $fim,
                    "pizzaria_horario_especial" => $empresaLogada["codigo_pizzaria"],
                    "aberto_horario_especial" => 2,
                    "ativo_horario_especial" => 1
                    );
                $hora1 = $this->HorariosEmpresa->inserirHorarioEspecialRetornandoo($Horario1);
                $hora2 = $this->HorariosEmpresa->inserirHorarioEspecialRetornandoo($Horario2);
            }else{
                $Horario = array(
                    "data_horario_especial" => $diaEspecial,
                    "inicio_horario_especial" => $ini,
                    "fim_horario_especial" => $fim,
                    "pizzaria_horario_especial" => $empresaLogada["codigo_pizzaria"],
                    "aberto_horario_especial" => 2,
                    "ativo_horario_especial" => 1
                    );
                $hora = $this->HorariosEmpresa->inserirHorarioEspecialRetornandoo($Horario);
            }
            if($hora == null){
                throw new Exception("Erro ao incluir dados.");
            }
            $json_str = json_encode($hora);
            echo $json_str; 

            
        }catch (Exception $e){
                $fp = fopen("log_skybots.txt", "a");
                $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
                fclose($fp); 
                throw new Exception($e->getMessage());
        }
    }
    
    public function despausarChatBot(){
        date_default_timezone_set('America/Cuiaba');
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/HorariosEmpresa");
        $this->load->model("modelos/VerificaHorarioAtendimento");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $horarioEspecial = $this->HorariosEmpresa->consultaHorarioEspecialDiaAtualCodigoPizzaria($empresaLogada["codigo_pizzaria"], date('Y-m-d'));
            if($this->VerificaHorarioAtendimento->AutenticaHorario($empresaLogada["codigo_pizzaria"]) == 1 ){
                throw new Exception("O bot ja está ativo.");
            }
            $codgHorario = 0;
            if($horarioEspecial != null){
                foreach ($horarioEspecial as $horarioDia) {
                    if($horarioDia["inicio_horario_especial"] <= date('H:i:s') && $horarioDia["fim_horario_especial"] >= date('H:i:s') && $horarioDia["aberto_horario_especial"] == 2){
                            $codgHorario = $horarioDia["codigo_horario_especial"];
                    }     
                }
            }
            if($codgHorario != 0){
                if(!$this->HorariosEmpresa->removerHorarioEspecial($codgHorario, $empresaLogada["codigo_pizzaria"])){
                    throw new Exception("Erro ao reiniciar o ChatBot.");
                }
            }else{
                throw new Exception("Não existe nenhum pause cadastrado");
            }
        }catch (Exception $e){
                $fp = fopen("log_skybots.txt", "a");
                $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
                fclose($fp); 
                throw new Exception($e->getMessage());
        }
    }
}
