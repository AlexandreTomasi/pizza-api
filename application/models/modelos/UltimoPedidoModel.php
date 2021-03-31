<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UltimoPedidoModel
 *
 * @author Alexandre
 */
class UltimoPedidoModel extends CI_Model{
    //put your code here
    public function retornaUltimoPedidoCliente($pizzaria, $fb_id){
        $this->load->model("pizzaria/Bebida_model");
        $this->load->model("pizzaria/Pedido_model");
        $this->load->model("pizzaria/Cliente_model");
        $this->load->model("pizzaria/Item_pedido_model");
        $this->load->model("pizzaria/Pizza_model");
        $this->load->model("pizzaria/Item_extra_pizza_model");
        $this->load->model("pizzaria/Item_pizza_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Tipo_extra_model");
        $this->load->model("modelos/UtilitarioGeradorDeJSON");
        
        $resposta = "O seu último pedido foi:";
        $respBebida = "Bebidas: ";
        
        $cliente = $this->Cliente_model->buscarClientesFBid($fb_id, $pizzaria);
        $pedido = null;
        if($cliente["codigo_cliente_pizzaria"] != null){
            $pedido = $this->Pedido_model->buscarUltimoPedidoCodgCliente($pizzaria, $cliente["codigo_cliente_pizzaria"]);
        }
        if($cliente != null && $pedido != null){
            $todosItemPedido = $this->Item_pedido_model->buscarItemPedidoPorPedido($pedido["codigo_pedido"]);
            for($i=0; $i < count($todosItemPedido); $i++){
                if($todosItemPedido[$i]["bebida_item_pedido"] != null){
                    $bebidas =  $this->Bebida_model->buscarBebidaPorIdECodgPizzaria($todosItemPedido[$i]["bebida_item_pedido"], $pizzaria);
                    $respBebida = $respBebida.intval($todosItemPedido[$i]["quantidade_item_pedido"])." - ".$bebidas["descricao_bebida"].", ";
                }else if($todosItemPedido[$i]["pizza_item_pedido"] != null){
                    $pizza = $this->Pizza_model->buscaPizzaPorCodigo( $todosItemPedido[$i]["pizza_item_pedido"]);
                    $tamanhoPizza=$this->Pizza_tamanho_model->buscarTamanhoCodigo($pizza["tamanho_pizza_pizza"]);

                    $resposta=$resposta." ".$tamanhoPizza["descricao_tamanho_pizza"]." Sabor: "; 
                    $todosSabores = $this->Item_pizza_model->buscarItemPizzaPorCodigoPizza($pizza["codigo_pizza"]);
                    for($j=0; $j < count($todosSabores);$j++){
                        $sabor = $this->Pizza_sabor_model->buscarSaborCodigo($todosSabores[$j]["sabor_pizza_item_pizza"]);
                        if($j+1 == count($todosSabores)){
                            $resposta=$resposta.$sabor["descricao_sabor_pizza"].". ";
                        }else{
                            if($j+2 == count($todosSabores)){
                                $resposta=$resposta.$sabor["descricao_sabor_pizza"]." e ";
                            }else{
                                $resposta=$resposta.$sabor["descricao_sabor_pizza"].", ";
                            }
                        }
                    }
                   
                    $todosItemExtras = $this->Item_extra_pizza_model->buscaItemExtraPizzaPorCodigoPizza($pizza["codigo_pizza"]);
                    if($todosItemExtras != null){
                        $resposta=$resposta."Com: ";
                        for($j=0; $j < count($todosItemExtras);$j++){
                            $extra = $this->Pizza_extra_model->buscarExtraCodigo($todosItemExtras[$j]["extra_pizza_item_extra_pizza"], $pizzaria);
                            $tipo = $this->Tipo_extra_model->buscarTipoExtraPorId($extra["tipo_extra_pizza_extra_pizza"], $pizzaria);
                            if($j+1 == count($todosItemExtras)){
                                $resposta=$resposta.$tipo["descricao_tipo_extra_pizza"]." ".$extra["descricao_extra_pizza"].". ";
                            }else{
                                if($j+2 == count($todosItemExtras)){
                                    $resposta=$resposta.$tipo["descricao_tipo_extra_pizza"]." ".$extra["descricao_extra_pizza"]." e ";
                                }else{
                                    $resposta=$resposta.$tipo["descricao_tipo_extra_pizza"]." ".$extra["descricao_extra_pizza"].", ";
                                }
                            }
                        }   
                    }
                } 
            }
            //strrpos
            
            if($respBebida != "Bebidas: "){
                $oco = strrpos($respBebida,",");
                $respBebida = substr_replace($respBebida, '.', $oco);
                $resposta=$resposta.$respBebida; 
            }
            $rapida[] = array("titulo" => "Sim","bloco" => "Fluxo 102");
            $rapida[] = array("titulo" => "Não","bloco" => "começar");
            $dados= $this->UtilitarioGeradorDeJSON->gerarRespostaRapida($resposta." Confirma o pedido?", $rapida);


            $json_str = json_encode($dados);
            return $json_str;
        }else{
            
            $dados = array('messages' => array(
                        array(
                            "attachment" => array(
                                "type" => "template",
                                "payload" => array(
                                    "template_type" => "button",
                                    'text' => "Ops, parece que você nunca pediu pizza comigo.",
                                    "buttons"=>array(
                                        array(
                                            "type"=> "show_block",
                                            "block_name"=> "começar",
                                            "title"=> "Fazer Pedido"
                                        )
                                    )
                                    
                                ) 
                            )
                        )
                    )
                );

            $json_str = json_encode($dados);
            return $json_str;
        }
        
    }
    
    public function buscaFormasPagamentoAtivasUP($pizzaria){
        $this->load->model("pizzaria/Forma_pagamento_model");
        $formas = $this->Forma_pagamento_model->buscarFormaPagamentoAtivas($pizzaria);
        
        $total = count($formas);
        $botoes = array();
        $galeria = array();
        for($i=0; $i < $total; $i++){
            $galeria[$i] = array(
                "title"=> $formas[$i]["descricao_forma_pagamento"],
                "image_url"=> "",
                "subtitle"=> $formas[$i]["descricao_forma_pagamento"],
                "buttons" =>array(
                    array(
                        "type"=> "show_block",
                        "block_name"=> "Fluxo 112",
                        "title"=> $formas[$i]["descricao_forma_pagamento"]
                    )
                )
            );
        }
        $dados = array('messages' => array(
                    array('attachment' => array(
                        'type' => 'template',
                        'payload' => array(
                            'template_type' => "generic",
                            'elements' => $galeria                         
                        )                
                    ))
                 ));
        $json_str = json_encode($dados);
        return $json_str;
    
    }
    
    public function encontraFormaPagamentoUP($pizzaria, $FormaPgSelecionada){
        $this->load->model("pizzaria/Forma_pagamento_model");
        $formaPagamento = $this->Forma_pagamento_model->buscarFormaPagamentoDescricaoAtivaInativa($FormaPgSelecionada, $pizzaria);
        return $formaPagamento;
    }
    
    public function autorizaUltimoPedido($pizzaria, $fb_id){
        $this->load->model("pizzaria/Bebida_model");
        $this->load->model("pizzaria/Pedido_model");
        $this->load->model("pizzaria/Cliente_model");
        $this->load->model("pizzaria/Item_pedido_model");
        $this->load->model("pizzaria/Pizza_model");
        $this->load->model("pizzaria/Item_extra_pizza_model");
        $this->load->model("pizzaria/Item_pizza_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Taxa_entrega_model");
        $this->load->model("modelos/SetVariaveisChat");
        
        $cliente = $this->Cliente_model->buscarClientesFBid($fb_id, $pizzaria);
        $pedido = null;
        if($cliente != null){
            $pedido = $this->Pedido_model->buscarUltimoPedidoCodgCliente($pizzaria, $cliente["codigo_cliente_pizzaria"]);
        }
        if($cliente != null && $pedido != null){
            $todosItemPedido = $this->Item_pedido_model->buscarItemPedidoPorPedido($pedido["codigo_pedido"]);
            for($i=0; $i < count($todosItemPedido); $i++){
                if($todosItemPedido[$i]["bebida_item_pedido"] != null){
                    $bebida =  $this->Bebida_model->buscarBebidaPorIdECodgPizzaria($todosItemPedido[$i]["bebida_item_pedido"], $pizzaria);
                    if($bebida["ativo_bebida"] == 0){
                        $dados = array('messages' => array(
                                        array(
                                                   'text' => "Ops, fizemos alguns ajustes para melhorar os nossos serviços e não temos como repetir o seu último pedido. Agradecemos a compreensão."
                                               )
                                           ));
                        $json_str = json_encode($dados);
                        return $json_str;
                   }
                }else if($todosItemPedido[$i]["pizza_item_pedido"] != null){
                    $pizza = $this->Pizza_model->buscaPizzaPorCodigo( $todosItemPedido[$i]["pizza_item_pedido"]);
                    $tamanhoPizza=$this->Pizza_tamanho_model->buscarTamanhoCodigo($pizza["tamanho_pizza_pizza"]);
                    if($tamanhoPizza["ativo_tamanho_pizza"] == 0){
                        $dados = array('messages' => array(
                                    array(
                                               'text' => "Ops, fizemos alguns ajustes para melhorar os nossos serviços e não temos como repetir o seu último pedido. Agradecemos a compreensão."
                                           )
                                       ));
                        $json_str = json_encode($dados);
                        return $json_str;
                    }
                    $todosSabores = $this->Item_pizza_model->buscarItemPizzaPorCodigoPizza($pizza["codigo_pizza"]);
                    for($j=0; $j < count($todosSabores);$j++){
                        $sabor = $this->Pizza_sabor_model->buscarSaborCodigo($todosSabores[$j]["sabor_pizza_item_pizza"]);   
                        if($sabor["ativo_sabor_pizza"] == 0){
                            $dados = array('messages' => array(
                                        array(
                                                   'text' => "Ops, fizemos alguns ajustes para melhorar os nossos serviços e não temos como repetir o seu último pedido. Agradecemos a compreensão."
                                               )
                                           ));
                            $json_str = json_encode($dados);
                            return $json_str;
                        }
                    }
                   
                    $todosItemExtras = $this->Item_extra_pizza_model->buscaItemExtraPizzaPorCodigoPizza($pizza["codigo_pizza"]);
                    if($todosItemExtras != null){
                        for($j=0; $j < count($todosItemExtras);$j++){
                            $extra = $this->Pizza_extra_model->buscarExtraCodigo($todosItemExtras[$j]["extra_pizza_item_extra_pizza"], $pizzaria);
                            if($extra["ativo_extra_pizza"] == 0){
                                $dados = array('messages' => array(
                                        array(
                                            'text' => "Ops, fizemos alguns ajustes para melhorar os nossos serviços e não temos como repetir o seu último pedido. Agradecemos a compreensão."
                                        )
                                    ));
                                $json_str = json_encode($dados);
                                return $json_str;
                            }
                        }   
                    }
                } 
            }
        }else{           
            $dados = array('messages' => array(
                    array(
                        'text' => "Ops, fizemos alguns ajustes para melhorar os nossos serviços e não temos como repetir o seu último pedido. Agradecemos a compreensão."
                    )
                ));
            $json_str = json_encode($dados);
            return $json_str;
        }
        return $this->SetVariaveisChat->mudaValorVariavelUtilitario(1, "PermissaoUP");
    }
    
    
    public function orcamentoUltimoPedido($pizzaria, $fb_id){
        $this->load->model("pizzaria/Bebida_model");
        $this->load->model("pizzaria/Pedido_model");
        $this->load->model("pizzaria/Cliente_model");
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
        
        $resposta = "";
        $respBebida = " Bebidas: ";
        $custoTotal = 0;
        
        $cliente = $this->Cliente_model->buscarClientesFBid($fb_id, $pizzaria);
        $pedido = $this->Pedido_model->buscarUltimoPedidoCodgCliente($pizzaria, $cliente["codigo_cliente_pizzaria"]);
        
        if($pedido != null){            
            $todosItemPedido = $this->Item_pedido_model->buscarItemPedidoPorPedido($pedido["codigo_pedido"]);
            for($i=0; $i < count($todosItemPedido); $i++){
                if($todosItemPedido[$i]["bebida_item_pedido"] != null){
                    $bebidas =  $this->Bebida_model->buscarBebidaPorIdECodgPizzaria($todosItemPedido[$i]["bebida_item_pedido"], $pizzaria);
                    $respBebida = $respBebida.intval($todosItemPedido[$i]["quantidade_item_pedido"]).
                        " - ".$bebidas["descricao_bebida"]." - valor R$".number_format($bebidas["preco_bebida"]*$todosItemPedido[$i]["quantidade_item_pedido"], 2, '.', '').", ";
                    $custoTotal = $custoTotal + ($bebidas["preco_bebida"]*$todosItemPedido[$i]["quantidade_item_pedido"]);
                }else if($todosItemPedido[$i]["pizza_item_pedido"] != null){
                    $pizza = $this->Pizza_model->buscaPizzaPorCodigo( $todosItemPedido[$i]["pizza_item_pedido"]);
                    $tamanhoPizza=$this->Pizza_tamanho_model->buscarTamanhoCodigo($pizza["tamanho_pizza_pizza"]);

                    $resposta=$resposta." ".$tamanhoPizza["descricao_tamanho_pizza"]." Sabor "; 
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
                    if($preco != 0){
                        $valorP = $preco/count($todosSabores);
                        $custoTotal = $custoTotal + $valorP;
                        $valorP = number_format($valorP, 2, '.', '');
                        $resposta = $resposta."valor R$ ".$valorP.".";
                    }
                    
                   
                    $todosItemExtras = $this->Item_extra_pizza_model->buscaItemExtraPizzaPorCodigoPizza($pizza["codigo_pizza"]);
                    if($todosItemExtras != null){
                        $resposta=$resposta." Com: ";
                        for($j=0; $j < count($todosItemExtras);$j++){
                            $extra = $this->Pizza_extra_model->buscarExtraCodigo($todosItemExtras[$j]["extra_pizza_item_extra_pizza"], $pizzaria);
                            $tipo = $this->Tipo_extra_model->buscarTipoExtraPorId($extra["tipo_extra_pizza_extra_pizza"], $pizzaria);
                            $custoTotal = $custoTotal + $extra["preco_extra_pizza"];
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
                } 
            }
            if($respBebida != "Bebidas: "){
                $oco = strrpos($respBebida,",");
                $respBebida = substr_replace($respBebida, '.', $oco);
                $resposta=$resposta.$respBebida; 
            }
            
            // descobrindo custo da taxa de entrega
            $taxa = $this->Taxa_entrega_model->buscarTaxaEntregaPorBairro($pizzaria, $pedido["bairro_pedido"]);
            $resposta = $resposta." Taxa de entrega: R$".$taxa["preco_taxa_entrega"];
            $custoTotal = $custoTotal +$taxa["preco_taxa_entrega"];
            $custoTotal = number_format($custoTotal, 2, '.', '');
            //$resposta=$resposta.$pedido["valor_total_pedido"].".";
            $dados = array('messages' => array(
                    array(
                        'text' => "Valor total do pedido: R$ ".$custoTotal." ".$resposta
                    )
                ));
             $json_str = json_encode($dados);
             return $json_str;
        }else{
            
            $dados = array('messages' => array(
                        array(
                            "attachment" => array(
                                "type" => "template",
                                "payload" => array(
                                    "template_type" => "button",
                                    'text' => "Não foi possivel fazer seu pedido",
                                    "buttons"=>array(
                                        array(
                                            "type"=> "show_block",
                                            "block_name"=> "começar",
                                            "title"=> "Fazer novo pedido."
                                        )
                                    )
                                    
                                ) 
                            )
                        )
                    )
                );

            $json_str = json_encode($dados);
            return $json_str;
        }
    }
    
    
    
    
    
    public function repetirUltimoPedido($pizzaria, $fb_id, $telefoneCliente, $estadoUP, $cidadeUP, $cep, $endereco, $complementoEndereco, $formaPagamento, $observacaoCliente, $trocoCliente, $mapUrl, $bairro){
        $this->load->model("pizzaria/Bebida_model");
        $this->load->model("pizzaria/Pedido_model");
        $this->load->model("pizzaria/Cliente_model");
        $this->load->model("pizzaria/Item_pedido_model");
        $this->load->model("pizzaria/Pizza_model");
        $this->load->model("pizzaria/Item_extra_pizza_model");
        $this->load->model("pizzaria/Item_pizza_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Taxa_entrega_model");
        $this->load->model("modelos/SetVariaveisChat");
        $this->load->model("gerencia/Cidade_model");
        $this->load->model("gerencia/Uf_model");
        $this->load->model("modelos/VerificaBairroPermitido");
        $this->load->model("pizzaria/Valor_pizza_model");
        $this->load->model("modelos/VerificadorStatusItens");
        $this->load->model("gerencia/Bairro_model");
        
        
        if($this->VerificadorStatusItens->verificarExistenciaItensInativos($pizzaria, "", "", "", "", $bairro, $formaPagamento) == 0){
            return 0;
        }
        
        $custoTotal = 0;
        if($estadoUP == null || $estadoUP == 0 || $estadoUP == "" || $cidadeUP == null || $cidadeUP == 0 || $cidadeUP == ""){
            $local = $this->Bairro_model->buscaBairroPorCodigoComCidadeEstado($bairro);
            $estado = $local["codigo_uf"];
            $cidade = $local["codigo_cidade"];
        }else{
            $estado = $this->Uf_model->buscaEstadoPorNome($estadoUP)["codigo_uf"];
            $cidade = $this->Cidade_model->buscaCidadePorNome($cidadeUP)["codigo_cidade"];
        }
        
        $cliente = $this->Cliente_model->buscarClientesFBid($fb_id, $pizzaria);
        if($cliente == null || $cliente == ""){
            throw new Exception("Metodo repetirUltimoPedido Cliente não encontrado");
        }
        $pedido = $this->Pedido_model->buscarUltimoPedidoCodgCliente($pizzaria, $cliente["codigo_cliente_pizzaria"]);
        if($pedido == null || $pedido == ""){
            throw new Exception("Metodo repetirUltimoPedido Pedido não encontrado");
        }
        
        if($pedido != null){
            $novoPedido = $this->insereNovoPedido($pedido, $telefoneCliente, $complementoEndereco, $observacaoCliente, $formaPagamento, $cliente, $pizzaria,
                    $endereco, $cidade, $estado, $bairro, $cep, $endereco, $trocoCliente, $mapUrl);

            if($novoPedido == null || $novoPedido["codigo_pedido"] == null){
                throw new Exception("Metodo repetirUltimoPedido Pedido não inserido");
            }
            $todosItemPedido = $this->Item_pedido_model->buscarItemPedidoPorPedido($pedido["codigo_pedido"]);
            if($todosItemPedido == null ){
                throw new Exception("\n".date('s:i:H d/m/Y')." - "."Codigo da pizzaria = ".$pizzaria.". Metodo repetirUltimoPedido todosItemPedido não encontrado");
            }
            for($i=0; $i < count($todosItemPedido); $i++){
                // verifica se é bebida
                if($todosItemPedido[$i]["bebida_item_pedido"] != null){
                    $bebida =  $this->Bebida_model->buscarBebidaPorId($todosItemPedido[$i]["bebida_item_pedido"]);
                    $this->insereNovoItemPedido($todosItemPedido[$i], $novoPedido, $todosItemPedido[$i]["bebida_item_pedido"], 0);
                    $custoTotal = $custoTotal + ($bebida["preco_bebida"]*$todosItemPedido[$i]["quantidade_item_pedido"]);
                 // se nao verifica se é pizza
                }else if($todosItemPedido[$i]["pizza_item_pedido"] != null){
                    $pizza = $this->Pizza_model->buscaPizzaPorCodigo( $todosItemPedido[$i]["pizza_item_pedido"]);
                    $tamanhoPizza=$this->Pizza_tamanho_model->buscarTamanhoCodigo($pizza["tamanho_pizza_pizza"]);
                    // insere nova pizza
                    $novaPizza = array("tamanho_pizza_pizza" => $pizza["tamanho_pizza_pizza"]);
                    $novaPizza["codigo_pizza"] = $this->Pizza_model->incluirPizzaRetornandoCodigo($novaPizza);
                                       
                    //$tamanhoPizza=$this->Pizza_tamanho_model->buscarTamanhoCodigo($pizza["tamanho_pizza_pizza"]);
                    $todosSabores = $this->Item_pizza_model->buscarItemPizzaPorCodigoPizza($pizza["codigo_pizza"]);
                    $preco=0;
                    for($j=0; $j < count($todosSabores);$j++){
                        $sabor = $this->Pizza_sabor_model->buscarSaborCodigo($todosSabores[$j]["sabor_pizza_item_pizza"]);
                        $preco = $preco + $this->Valor_pizza_model->buscarValorPizzaAtivo($pizzaria, $sabor["codigo_sabor_pizza"], $tamanhoPizza["codigo_tamanho_pizza"]);
                        $novoItemPizza = array(
                                        "sabor_pizza_item_pizza"=> $todosSabores[$j]["sabor_pizza_item_pizza"],
                                        "pizza_item_pizza" => $novaPizza["codigo_pizza"]
                                    );
                        $this->Item_pizza_model->inserirItemPizza($novoItemPizza);     
                    }
                    if($preco != 0){
                        $custoTotal = $custoTotal + $preco/count($todosSabores);
                        $preco = $preco/count($todosSabores);
                    }
                   
                    $todosItemExtras = $this->Item_extra_pizza_model->buscaItemExtraPizzaPorCodigoPizza($pizza["codigo_pizza"]);
                    if($todosItemExtras != null){
                        for($j=0; $j < count($todosItemExtras);$j++){
                            $extra = $this->Pizza_extra_model->buscarExtraCodigo($todosItemExtras[$j]["extra_pizza_item_extra_pizza"], $pizzaria);
                            $custoTotal = $custoTotal + $extra["preco_extra_pizza"];
                            $preco=$preco+$extra["preco_extra_pizza"];
                            $itemExtraPizza = array(
                                                "pizza_item_extra_pizza" => $novaPizza["codigo_pizza"],
                                                "extra_pizza_item_extra_pizza"=> $todosItemExtras[$j]["extra_pizza_item_extra_pizza"],
                                                );
                            $this->Item_extra_pizza_model->incluirItemExtraPizza($itemExtraPizza);
                        }   
                    }
                    // insere novo item pedido
                    $todosItemPedido[$i]["valor_subtotal_item_pedido"] = $preco;
                    $this->insereNovoItemPedido($todosItemPedido[$i], $novoPedido, 0, $novaPizza["codigo_pizza"]);
                } 
            }
            
            $taxa = $this->Taxa_entrega_model->buscarTaxaEntregaPorBairro($pizzaria, $bairro);
            $custoTotal = $custoTotal +$taxa["preco_taxa_entrega"];
            $novoPedido["valor_total_pedido"]=$custoTotal;
            $this->Pedido_model->alterarPedido($novoPedido, $pizzaria);
            return 1;
        }else{           
            return 0;
        }
        
    }
    public function insereNovoPedido($pedido, $telefoneCliente, $complementoEndereco, $observacaoCliente, $formaPagamento, $cliente, $pizzaria, $enderecoUP, $cidade, $estado, $bairro, $cep, $endereco, $trocoCliente, $mapUrl){
        $this->load->model("pizzaria/Pedido_model");
        if($pedido["valor_total_pedido"] == null){throw new Exception("Metodo insereNovoPedido valor_total_pedido nullo");}
        if($cliente == null){throw new Exception("Metodo insereNovoPedido cliente nullo");}
        if($formaPagamento == null){throw new Exception("Metodo insereNovoPedido formaPagamento nullo");}
        if($pizzaria == null){throw new Exception("Metodo insereNovoPedido pizzaria nullo");}
        if($telefoneCliente == null){throw new Exception("Metodo insereNovoPedido telefoneCliente nullo");}
        if($complementoEndereco == null){throw new Exception("Metodo insereNovoPedido endereco nullo");}
        if($cidade == null){throw new Exception("Metodo insereNovoPedido cidade nullo");}
        if($estado == null){throw new Exception("Metodo insereNovoPedido estado nullo");}
        if($bairro == null){throw new Exception("Metodo insereNovoPedido bairro nullo");}
        
        if($trocoCliente != null && $trocoCliente != 0){
            $trocoCliente = "Levar troco para R$".$trocoCliente.". ";
        }else{
            $trocoCliente = "";
        }
        if(strlen($mapUrl) >= 500){
            //throw new Exception("\nMetodo insereNovoPedido mapUrl maior que 500");
        }
        if($observacaoCliente != null && 0 == strnatcasecmp($observacaoCliente,"Nenhuma")){
            $observacaoCliente="";
        }else{
            $observacaoCliente = " Observação do cliente: ".$observacaoCliente;
        }
        $pedidoAinserir = array(
                "data_hora_pedido" => date('Y-m-d H:i:s'),
                "cliente_pizzaria_pedido" => $cliente["codigo_cliente_pizzaria"],
                "valor_total_pedido" => $pedido["valor_total_pedido"],
                "forma_pagamento_pedido" => $formaPagamento,
                "observacao_pedido" => $trocoCliente.$observacaoCliente,
                "pizzaria_pedido" => $pizzaria,
                "telefone_pedido" => $telefoneCliente,
                "endereco_pedido" => $complementoEndereco,
               //"numero_endereco_pedido" => ,
               // "complemento_endereco_pedido" => $mapUrl,
                "cidade_pedido" => $cidade,
                "uf_pedido" => $estado,
                "referencia_endereco_pedido" =>  "",//$enderecoUP
                "bairro_pedido" => $bairro,
                "cep_pedido"=> $cep,
                "mapa_url_pedido" => $mapUrl,
                "status_pedido"=> 1
        );
        $pedidoAinserir["codigo_pedido"] = $this->Pedido_model->inserirPedidoRetornadoCodigoInserido($pedidoAinserir);
        return $pedidoAinserir;
    }
    
    public function insereNovoItemPedido($itemPedido, $novoPedido, $codgBebida, $codgPizza){
        $this->load->model("pizzaria/Item_pedido_model");
        if($itemPedido != null && $novoPedido != null){
            //inserir Item pedido
            if($codgBebida != 0){
                $novoItemPedido = array(
                    "quantidade_item_pedido" => $itemPedido["quantidade_item_pedido"],
                    "valor_subtotal_item_pedido"=> $itemPedido["valor_subtotal_item_pedido"],
                    "pedido_item_pedido" => $novoPedido["codigo_pedido"],
                    "bebida_item_pedido" => $codgBebida
                );
            }else if($codgPizza != 0){
                $novoItemPedido = array(
                    "quantidade_item_pedido" => $itemPedido["quantidade_item_pedido"],
                    "valor_subtotal_item_pedido"=> $itemPedido["valor_subtotal_item_pedido"],
                    "pedido_item_pedido" => $novoPedido["codigo_pedido"],
                    "pizza_item_pedido" => $codgPizza
                );     
            }
            $this->Item_pedido_model->inserirItemPedido($novoItemPedido);
        }
    }
}
