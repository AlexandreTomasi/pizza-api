<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PedidoPizzaria
 *
 * @author 033234581
 */
class PedidoPizzaria extends CI_Model{
    //put your code here
    
    public function incluirPedidoSolicitadoFacebook($pizzaria, $tamanhoPizza, $sabor, $ItemExtra, $ItemBebida, $bairro, $nome, $sobrenome, $fb_id, 
                        $telefoneCliente, $sexo, $estado, $cidade, $cep, $endereco, $complementoEndereco, $custoTotalPedido, $formaPagamento,
                        $observacaoCliente, $trocoCliente, $mapUrl)
    {
        
        $this->load->model("pizzaria/Cliente_model");
        $this->load->model("pizzaria/Pedido_model");
        $this->load->model("pizzaria/Pizza_model");
        $this->load->model("pizzaria/Item_extra_pizza_model");
        $this->load->model("pizzaria/Item_pizza_model");
        $this->load->model("pizzaria/Item_pedido_model");
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Valor_pizza_model");
        $this->load->model("pizzaria/Bebida_model");
        $this->load->model("gerencia/Cidade_model");
        $this->load->model("gerencia/Uf_model");
        $this->load->model("pizzaria/Taxa_entrega_model");
        $this->load->model("gerencia/Bairro_model");
        $this->db->trans_start();
        // verificar se ja existe cadastro do cliente.
        $inserido = 0;
        if($sexo == "male"){
                $sexo='M';
        }else{
                $sexo='F';
        }
        if($this->verificarItensPedidosAtivos($pizzaria, $tamanhoPizza, $sabor, $ItemExtra, $ItemBebida, $bairro, $formaPagamento) == 0){
            return 0;
        }
        // se cidade ou estado n existir pego do bairro
        if($estado == null || $estado == 0 || $estado == "" || $cidade == null || $cidade == 0 || $cidade == ""){
            $local = $this->Bairro_model->buscaBairroPorCodigoComCidadeEstado($bairro);
            $estado = $local["codigo_uf"];
            $cidade = $local["codigo_cidade"];
        }else{
            $estado = $this->Uf_model->buscaEstadoPorNome($estado)["codigo_uf"];
            $cidade = $this->Cidade_model->buscaCidadePorNome($cidade)["codigo_cidade"];
        }
        
        $cliente = $this->Cliente_model->buscarClientesFBid($fb_id, $pizzaria);
        if($trocoCliente != 0){
            $trocoCliente = "Levar troco para R$".$trocoCliente.". ";
        }else{
            $trocoCliente = "";
        }
        if($observacaoCliente != null && 0 == strnatcasecmp($observacaoCliente,"Nenhuma")){
            $observacaoCliente="";
        }else{
            $observacaoCliente = " Observação do cliente: ".$observacaoCliente;
        }
        // verifica se cliente esta ativo ou seja nao esta bloquiado.
        if($cliente != null && $cliente["ativo_cliente_pizzaria"] != 1){
            throw new Exception("Cliente: ".$cliente['nome_cliente_pizzaria']." está bloquiado para fazer pedido");
        }
        if($cliente == null){
            // inserir novo cliente
            
            $cliente = array(
                //"cpf_cliente_pizzaria" => "",
                "nome_cliente_pizzaria" => $nome." ".$sobrenome,
                //"email_cliente_pizzaria" => "",
                "id_facebook_cliente_pizzaria" => $fb_id,
                "telefone_cliente_pizzaria" => $telefoneCliente,
                "cep_cliente_pizzaria" => $cep,
                "endereco_cliente_pizzaria" => $complementoEndereco ,
                "complemento_endereco_cliente_pizzaria" => $mapUrl,
                "cidade_cliente_pizzaria" => $cidade,
                "uf_cliente_pizzaria" => $estado,
                "sexo_cliente_pizzaria" => $sexo,
                "referencia_endereco_cliente_pizzaria" => "",//$endereco,
                "pizzaria_cliente_pizzaria" => $pizzaria,                    
                "bairro_cliente_pizzaria" =>$bairro,
                "ativo_cliente_pizzaria" =>1
            );
            $this->Cliente_model->inserirCliente($cliente);            
        }
        $cliente = $this->Cliente_model->buscarClientesFBid($fb_id, $pizzaria);        
            // registrar pedido
        $pedido = array(
                //"codigo_pedido" => "",
                "data_hora_pedido" => date('Y-m-d H:i:s'),
                "cliente_pizzaria_pedido" => $cliente["codigo_cliente_pizzaria"],
                "valor_total_pedido" => $custoTotalPedido,
                "forma_pagamento_pedido" => $formaPagamento,
                "observacao_pedido" => $trocoCliente.$observacaoCliente,
                "pizzaria_pedido" => $pizzaria,
                "telefone_pedido" => $telefoneCliente,
                "endereco_pedido" => $complementoEndereco ,
               //"numero_endereco_pedido" => ,
                //"complemento_endereco_pedido" => $mapUrl,
                "cidade_pedido" => $cidade,
                "uf_pedido" => $estado,
                "referencia_endereco_pedido" => "",//$endereco,
                "bairro_pedido" => $bairro,
                "cep_pedido"=> $cep,
                "mapa_url_pedido" => $mapUrl,
                "status_pedido"=> 1
        );
        $pedido["codigo_pedido"] = $this->Pedido_model->inserirPedidoRetornadoCodigoInserido($pedido);
        
        
        $tamanhoAtual = explode("-",$tamanhoPizza);
        $saborAtual = explode("@",$sabor);
        if($ItemExtra != 0){
            $extrass = explode("@",$ItemExtra);
        }else{
          $extrass = 0;
        }
        
        for($i=0;$i < count($tamanhoAtual); $i++){
            //inserir pizza
            $somaExtra = 0;
            $somaPizza = 0;
            $temp=0;
            $pizza = array(
                "tamanho_pizza_pizza" => $tamanhoAtual[$i]
            );
            $pizza["codigo_pizza"] = $this->Pizza_model->incluirPizzaRetornandoCodigo($pizza);

            $saboresUni = explode("-",$saborAtual[$i]);
            //insere todos os sabores da pizza
            for($j=0; $j< count($saboresUni);$j++){                   
                $itemPizza = array(
                    "sabor_pizza_item_pizza"=> $saboresUni[$j],
                    "pizza_item_pizza" => $pizza["codigo_pizza"]
                );
                $this->Item_pizza_model->inserirItemPizza($itemPizza);    
                $temp = $temp + $this->Valor_pizza_model->buscarValorPizzaAtivo($pizzaria, $saboresUni[$j], $tamanhoAtual[$i]);
            }
            if($temp != 0){
                    $somaPizza = $somaPizza + $temp/count($saboresUni);
            }
            // insere todos os extras
            if($extrass != 0 && $i < count($extrass)){
               $extraAtual = explode("-",$extrass[$i]);
                for($y=0;$y < count($extraAtual); $y++){
                    if($extraAtual[0] != 0){
                        $itemExtraPizza = array(
                            "pizza_item_extra_pizza" => $pizza["codigo_pizza"],
                            "extra_pizza_item_extra_pizza"=> $extraAtual[$y],
                        );
                        $this->Item_extra_pizza_model->incluirItemExtraPizza($itemExtraPizza);
                        $extraTemp = $this->Pizza_extra_model->buscarExtraCodigo($extraAtual[$y], $pizzaria);
                        $somaExtra = $somaExtra + $extraTemp["preco_extra_pizza"];
                    }
                } 
            }               

            //inserir Item pedido
            $itemPedido = array(
                "quantidade_item_pedido" => 1,
                "valor_subtotal_item_pedido"=> $somaExtra+$somaPizza,
                "pedido_item_pedido" => $pedido["codigo_pedido"],
                "pizza_item_pedido" => $pizza["codigo_pizza"]
            );
            $this->Item_pedido_model->inserirItemPedido($itemPedido);
            
        }
        //inserir bebidas
        if($ItemBebida != 0){
            $bebidaUni = explode("-",$ItemBebida);
            $inseridos = array();
            for($j=0; $j< count($bebidaUni);$j++){    
                //inserir Item pedido para bebida
                $bebidaTemp = $this->Bebida_model->buscarBebidaPorId($bebidaUni[$j]);
                $cont=0;
                for($i=0; $i< count($bebidaUni);$i++){
                    if($bebidaUni[$i] == $bebidaUni[$j]){
                        $cont=$cont+1;
                    }
                }
                $insere = true;
                for($i=0; $i< count($inseridos);$i++){
                    if($inseridos[$i]["bebida_item_pedido"] == $bebidaUni[$j]){
                        $insere = false;
                    }
                }
                if($insere == true){
                    $itemPedido = array(
                    "quantidade_item_pedido" => $cont,
                    "valor_subtotal_item_pedido"=> $bebidaTemp["preco_bebida"]*$cont,
                    "pedido_item_pedido" => $pedido["codigo_pedido"],
                    "bebida_item_pedido" => $bebidaUni[$j]
                    );
                    $this->Item_pedido_model->inserirItemPedido($itemPedido);
                    $inseridos[] = $itemPedido;
                }
            }
        }
        
        //inserir taxa de entrega como item pedido
        $taxa = $this->Taxa_entrega_model->buscarTaxaEntregaPorBairro($pizzaria, $bairro);
        if($taxa == null){throw new Exception("buscarTaxaEntregaPorBairro nao retornou dados. Funcionalidade: pedidoPizzaria->incluirPedidoSolicitadoFacebook");}
        if($taxa != null){
                $itemPedido = array(
                    "quantidade_item_pedido" => 1,
                    "valor_subtotal_item_pedido"=> $taxa["preco_taxa_entrega"],
                    "pedido_item_pedido" => $pedido["codigo_pedido"]
                );
                $this->Item_pedido_model->inserirItemPedido($itemPedido);
        }
        $this->db->trans_complete();
        $inserido = 1;
        return $inserido;
    }
    
    public function verificarItensPedidosAtivos($pizzaria, $tamanhoPizza, $sabor, $ItemExtra, $ItemBebida, $bairro, $formaPagamento){
        $this->load->model("pizzaria/Bebida_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Forma_pagamento_model");
        // tamanho e sabor não pode ser nulos ou zerados
        if($tamanhoPizza == null || $tamanhoPizza == 0 || $sabor == null || $sabor == 0){
            return 0;
        }
        
        //verificando se tamanhos estão ativos
        $tamanhoAtual = explode("-",$tamanhoPizza);
        $temp = array();
        for($i=0;$i < count($tamanhoAtual); $i++){
            $resp = $this->Pizza_tamanho_model->buscarTamanhoPorCodigoEpizzaria($tamanhoAtual[$i], $pizzaria);
            if($resp == null){
                return 0;
            }
        }
        
        // verificar sabores
        $saborAtual = explode("@",$sabor);
        for($i=0;$i < count($saborAtual); $i++){
            $saboresUni = explode("-",$saborAtual[$i]);
            for($j=0; $j< count($saboresUni);$j++){ 
                $resp = $this->Pizza_sabor_model->buscarSaborPorCodigoEpizzaria($saboresUni[$j], $pizzaria);
                if($resp == null){
                    return 0;
                }
            }
        }
        
        //verificar extras
        if($ItemExtra != 0){
            $extraAtual = explode("@",$ItemExtra);
            for($i=0;$i < count($extraAtual); $i++){
                $extra = explode("-",$extraAtual[$i]);
                for($j=0; $j< count($extra);$j++){ 
                    $resp = $this->Pizza_extra_model->buscarExtraCodigo($extra[$j], $pizzaria);
                    if($resp == null){
                        return 0;
                    }
                }
            }
        }
        //verificar bebidas
        if($ItemBebida != 0){
            $bebidaAtual = explode("-",$ItemBebida);
            for($i=0;$i < count($bebidaAtual); $i++){
                $resp = $this->Bebida_model->buscarBebidaPorIdECodgPizzaria($bebidaAtual[$i], $pizzaria);
                if($resp == null){
                    return 0;
                }
            }
        }
        //verificar taxa de entrega
        $taxa = $this->Taxa_entrega_model->buscarTaxaEntregaPorBairro($pizzaria, $bairro);
        if($taxa == null){
            return 0;
        }
        
        if($formaPagamento != -1){
            $forma = $this->Forma_pagamento_model->buscarFormaPagamentoPorCodgAtiva($formaPagamento, $pizzaria);
            if($forma == null){
                return 0;
            }
        }
        
        return 1;
    }
    
    public function solicitarCancelarPedido($pizzaria, $fb_id, $fluxo){
        $this->load->model("pizzaria/Cliente_model");
        $this->load->model("pizzaria/Pedido_model");
        $cliente = $this->Cliente_model->buscarClientesFBid($fb_id, $pizzaria);
        
        $pedido = null;
        if($cliente["codigo_cliente_pizzaria"] != null){
            $pedido = $this->Pedido_model->buscarUltimoPedidoCodgCliente($pizzaria, $cliente["codigo_cliente_pizzaria"]);
        }
        if($cliente != null && $pedido != null){
            if($pedido["status_pedido"] == 1 && ($fluxo == 41 || $fluxo == 119) ){// fluxo 41 é o finalizar pedido
                $pedido["status_pedido"] = 0;
                if( !($this->Pedido_model->alterarPedido($pedido, $pizzaria)) ){
                    throw new Exception("Erro ao cancelar pedido.");
                }
                return 1;
            }else{
                if($pedido["status_pedido"] != 1 && ($fluxo == 41 || $fluxo == 119)){
                    return 2;
                }else{
                    return 0;
                }
            }
            
        }
    }
}
