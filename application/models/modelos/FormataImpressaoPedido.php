<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormataImpressaoPedido
 *
 * @author 033234581
 */
class FormataImpressaoPedido extends CI_Model{
    
    public function viaEntregador($pizzaria, $codgPedido){
        $this->load->model("pizzaria/Empresa_model");
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
        $this->load->model("gerencia/Valor_configuracao_model");
        $this->load->model("gerencia/Cliente_pizzaria_model");
        $this->load->model("pizzaria/Forma_pagamento_model");

        $quantCaracteres = 42;
        $pedido = null;
        $resposta = "";
        $bebidasPedidas = array();
        $taxaEntrega = "";
        $nomeBot = "";
        $pizzaria = intval($pizzaria);
        $empresa = $this->Empresa_model->buscaPizzariaPorCodigo($pizzaria);
        $pedido = $this->Pedido_model->buscarPedidosCodgPedido($codgPedido, $pizzaria);        
        $cliente = $this->Cliente_model->buscarClientePorCodigo($pedido["cliente_pizzaria_pedido"], $pizzaria);
        $forma = $this->Forma_pagamento_model->buscarFormaPagamentoPorId($pedido["forma_pagamento_pedido"], $pizzaria); 
        $pedido["forma_pagamento_pedido"] = $forma["descricao_forma_pagamento"];
        $nomeBot = $this->Empresa_model->buscaConfigEmpresa("nome_bot_pizzaria",$pizzaria);       
        
        // primeira parte informacoes pizzaria
        $resposta .= "******".$this->centralizaEntreStrings("PEDIDO ".$pedido["codigo_pedido"], 30)."******\n";
        $resposta .= $this->centraliza($empresa["nome_fantasia_pizzaria"],$quantCaracteres)."\n";
        $resposta .= $this->centraliza("CNPJ: ".$empresa["cnpj_pizzaria"],$quantCaracteres)."\n";
        $resposta .= $this->centraliza($empresa["telefone_pizzaria"],$quantCaracteres)."\n";
        $resposta .= $this->centraliza("CLIENTE: ".$cliente["nome_cliente_pizzaria"],$quantCaracteres)."\n";
        $resposta .= "-----------------------------------------\n";
        $resposta .= "QTDD    PRODUTO                    VALOR\n";
        $resposta .= "-----------------------------------------\n";
        //segunda parte o pedido

        if($cliente != null && $pedido != null){
            $todosItemPedido = $this->Item_pedido_model->buscarItemPedidoPorPedido(intval($pedido["codigo_pedido"]));
            for($i=0; $i < count($todosItemPedido); $i++){
                if($todosItemPedido[$i]["bebida_item_pedido"] != null){
                    $bebidasPedidas[] = array("codigo" => $todosItemPedido[$i]["bebida_item_pedido"], "quantidade" => $todosItemPedido[$i]["quantidade_item_pedido"]);
                }else if($todosItemPedido[$i]["pizza_item_pedido"] != null){
                    $pizza = $this->Pizza_model->buscaPizzaPorCodigo( $todosItemPedido[$i]["pizza_item_pedido"]);
                    $tamanhoPizza=$this->Pizza_tamanho_model->buscarTamanhoCodigo($pizza["tamanho_pizza_pizza"]);

                    $resposta .= $this->formataItemPedido($todosItemPedido[$i]["quantidade_item_pedido"], $tamanhoPizza["descricao_tamanho_pizza"],
                            $todosItemPedido[$i]["valor_subtotal_item_pedido"],$quantCaracteres)."\n";

                    $todosSabores = $this->Item_pizza_model->buscarItemPizzaPorCodigoPizza($pizza["codigo_pizza"]);
                    for($j=0; $j < count($todosSabores);$j++){
                        $sabor = $this->Pizza_sabor_model->buscarSaborCodigo($todosSabores[$j]["sabor_pizza_item_pizza"]);
                       // if($j+1 == count($todosSabores)){
                            $resposta .= "        - ".( (strlen($sabor["descricao_sabor_pizza"]) < 32) ? $sabor["descricao_sabor_pizza"] : substr($sabor["descricao_sabor_pizza"], 0, 32))."\n";
                       // }
                    }
                   
                    $todosItemExtras = $this->Item_extra_pizza_model->buscaItemExtraPizzaPorCodigoPizza($pizza["codigo_pizza"]);
                    if($todosItemExtras != null){
                        for($j=0; $j < count($todosItemExtras);$j++){
                            $extra = $this->Pizza_extra_model->buscarExtraCodigo($todosItemExtras[$j]["extra_pizza_item_extra_pizza"], $pizzaria);
                            if($extra != null){
                                $tipo = $this->Tipo_extra_model->buscarTipoExtraPorId($extra["tipo_extra_pizza_extra_pizza"], $pizzaria);
                              //  if($j+1 == count($todosItemExtras)){
                                    $resposta .= "        - ".( (strlen($tipo["descricao_tipo_extra_pizza"]." ".$extra["descricao_extra_pizza"]) < 32) ?
                                            $tipo["descricao_tipo_extra_pizza"]." ".$extra["descricao_extra_pizza"] :
                                        substr($tipo["descricao_tipo_extra_pizza"]." ".$extra["descricao_extra_pizza"], 0, 32))."\n";
                             //   }
                            }
                        }   
                    }
                    
                    
                }else if($todosItemPedido[$i]["pizza_item_pedido"] == null && $todosItemPedido[$i]["bebida_item_pedido"] == null){//sei que é taxa de entrega
                    $taxaEntrega .= $this->formataItemPedido($todosItemPedido[$i]["quantidade_item_pedido"], "TAXA ENTREGA", $todosItemPedido[$i]["valor_subtotal_item_pedido"],$quantCaracteres);
                } 
            }
            // colocando as bebidas
            if($bebidasPedidas != null && count($bebidasPedidas)){
                for($i=0; $i < count($bebidasPedidas); $i++){ 
                    $bebida =  $this->Bebida_model->buscarBebidaPorId($bebidasPedidas[$i]["codigo"]);
                    $resposta .= $this->formataItemPedido($bebidasPedidas[$i]["quantidade"], $bebida["descricao_bebida"],$bebida["preco_bebida"] , $quantCaracteres)."\n";
                }
            }
            //colocando a taxa de entrega
            $resposta .= $taxaEntrega."\n"; 
        }else{  
            return "";
        }
        
        
        $resposta .= "-----------------------------------------\n";
        $resposta .= $this->colocaDadoNoFim("TOTAL A PAGAR:", $pedido["valor_total_pedido"],$quantCaracteres)."\n";
        $resposta .= "-----------------------------------------\n";
        
        //terceira parte
        $resposta .= "DATA: ".date_format(date_create($pedido["data_hora_pedido"]), 'H:i:s  d/m/Y')."\n\n";
        
        $resposta .= "*************** ENTREGADOR ***************\n";
        $resposta .= $this->colocaDadoNoFim("CLIENTE: ", $cliente["nome_cliente_pizzaria"],$quantCaracteres)."\n";
        $resposta .= wordwrap("ENDERECO: ".$pedido["endereco_pedido"], $quantCaracteres,"\n")."\n";
        $resposta .= $this->colocaDadoNoFim("REFERENCIA: ", $pedido["referencia_endereco_pedido"],$quantCaracteres)."\n";
        $resposta .= $this->colocaDadoNoFim("TELEFONE: ", $pedido["telefone_pedido"],$quantCaracteres)."\n";
        $resposta .= $this->colocaDadoNoFim("PAGAMENTO: ", $pedido["forma_pagamento_pedido"],$quantCaracteres)."\n";
        $resposta .= $this->colocaDadoNoFim("OBSERVACAO: ", $pedido["observacao_pedido"],$quantCaracteres)."\n";
        $resposta .= $this->colocaDadoNoFim("USUARIO LANC: ", $nomeBot,$quantCaracteres)."\n";
        
        $resposta .= "\n################ SKYBOTS #################\n"."### Trabalhe no que realmente importa  ###\n"."###         Automatize o resto         ###\n"
                ."###       http://skybots.com.br/       ###\n"."##########################################\n";
        $this->verificaCaracteresDasLinhas($resposta, $quantCaracteres);
        return $resposta;
    }
    
    public function centraliza($info, $n_colunas)
    {         
        $aux = strlen($info);
        if ($aux < $n_colunas) {
            // calcula quantos espaços devem ser adicionados
            // antes da string para deixa-la centralizada
            $espacos = floor(($n_colunas - $aux) / 2);
            $espaco = '';
            for ($i = 0; $i < $espacos; $i++){
                $espaco .= ' ';
            }
            // retorna a string com os espaços necessários para centraliza-la
            return $espaco.$info;
        } else {
            // se for maior ou igual ao número de colunas
            // retorna a string cortada com o número máximo de colunas.
            return substr($info, 0, $n_colunas);
        }     
    }
    
    public function centralizaEntreStrings($info, $n_colunas)
    {         
        $aux = strlen($info);
        if ($aux < $n_colunas) {
            $espacos = floor(($n_colunas - $aux) / 2);
            $espaco = '';
            for ($i = 0; $i < $espacos; $i++){
                $espaco .= ' ';
            }
            // retorna a string com os espaços necessários para centraliza-la
            return $espaco.$info.$espaco;
        } else {
            // se for maior ou igual ao número de colunas
            // retorna a string cortada com o número máximo de colunas.
            return substr($info, 0, $n_colunas);
        }     
    }
    
    public function colocaDadoNoFim($info, $dado, $n_colunas)
    {         
        $prim = strlen($info);
        $ultim = strlen($dado);
        $espaco = "";
        if (0 < $n_colunas-($prim+$ultim)) {
            for ($i = ($prim+$ultim); $i < $n_colunas; $i++){
                $espaco .= ' ';
            }
            // retorna a string com os espaços necessários para centraliza-la
            return $info.$espaco.$dado;
        }else{
            return wordwrap($info.$dado, $n_colunas,"\n");
        }     
    }
    
    
    public function verificaCaracteresDasLinhas($info, $n_colunas){
        $cont = 0;
        $texto = explode("\n",$info);
        for($i=0; $i < count($texto);$i++) {
            $linha = $texto[$i];
            $cont++;
            if(strlen($linha) > 42){
                throw new Exception("Linha com mais de 42 caracteres. Linha: ".$linha);
            }
        }
    }
    
    public function formataItemPedido($quant, $desc, $valor, $n_colunas){
        //divido em 3 partes. 8, 27, 7 caracteres. iniciando 0, 9, 36
        
        $resposta = "";
        $prim = strlen($quant);
        $ultim = strlen($valor);
        $espaco = "";
        for ($i = 0; $i < (8 - $prim); $i++){
            $espaco .= ' ';
        }
        $resposta .= $quant.$espaco;
        if(strlen($desc) > 27){
            $resposta .= substr($desc, 0, 27);
        }else{
            $espaco = "";
            for ($i = 0; $i < (27 - strlen($desc)); $i++){
                $espaco .= ' ';
            }
            $resposta .= $desc.$espaco;
        }
        $espaco = "";
        for ($i = 0; $i < (7 - $ultim); $i++){
            $espaco .= ' ';
        }
        $resposta .= $espaco.$valor;
        return $resposta;
    }
    
    public function viaCozinha($pizzaria, $codgPedido){
        $this->load->model("pizzaria/Empresa_model");
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
        $this->load->model("gerencia/Valor_configuracao_model");
        $this->load->model("gerencia/Cliente_pizzaria_model");
        $this->load->model("pizzaria/Forma_pagamento_model");
        
        $quantCaracteres = 42;
        $pedido = null;
        $resposta = "";
        $bebidasPedidas = array();
        $taxaEntrega = "";
        $pizzaria = intval($pizzaria);
        
        $empresa = $this->Empresa_model->buscaPizzariaPorCodigo($pizzaria);
        $pedido = $this->Pedido_model->buscarPedidosCodgPedido($codgPedido, $pizzaria);        
        $cliente = $this->Cliente_model->buscarClientePorCodigo($pedido["cliente_pizzaria_pedido"], $pizzaria);
        $forma = $this->Forma_pagamento_model->buscarFormaPagamentoPorId($pedido["forma_pagamento_pedido"], $pizzaria); 
        $pedido["forma_pagamento_pedido"] = $forma["descricao_forma_pagamento"];
        
        // primeira parte informacoes pizzaria
        $resposta .= "******".$this->centralizaEntreStrings("PEDIDO ".$pedido["codigo_pedido"], 30)."******\n";
        $resposta .= "DATA: ".date_format(date_create($pedido["data_hora_pedido"]), 'H:i:s  d/m/Y')."\n\n";
        $resposta .= "************".$this->centralizaEntreStrings("CLIENTE/ENDERECO", 18)."************\n";
        $resposta .= "CLIENTE: ".(strlen($cliente["nome_cliente_pizzaria"]) < 33 ? $cliente["nome_cliente_pizzaria"]: substr($cliente["nome_cliente_pizzaria"], 0, 33))."\n";
        $resposta .= wordwrap("ENDERECO: ".$pedido["endereco_pedido"], $quantCaracteres,"\n")."\n";
        $resposta .= "******************************************\n";
        $resposta .= "-----------------------------------------\n";
        $resposta .= "QTDD    PRODUTO                           \n";
        $resposta .= "-----------------------------------------\n";

        
        if($cliente != null && $pedido != null){
            $todosItemPedido = $this->Item_pedido_model->buscarItemPedidoPorPedido($pedido["codigo_pedido"]);
            for($i=0; $i < count($todosItemPedido); $i++){
                if($todosItemPedido[$i]["bebida_item_pedido"] != null){
                    $bebidasPedidas[] = array("codigo" => $todosItemPedido[$i]["bebida_item_pedido"], "quantidade" => $todosItemPedido[$i]["quantidade_item_pedido"]);
                }else if($todosItemPedido[$i]["pizza_item_pedido"] != null){
                    $pizza = $this->Pizza_model->buscaPizzaPorCodigo( $todosItemPedido[$i]["pizza_item_pedido"]);
                    $tamanhoPizza=$this->Pizza_tamanho_model->buscarTamanhoCodigo($pizza["tamanho_pizza_pizza"]);

                    $resposta .= $this->formataItemPedido($todosItemPedido[$i]["quantidade_item_pedido"], $tamanhoPizza["descricao_tamanho_pizza"],
                            "",$quantCaracteres)."\n";

                    $todosSabores = $this->Item_pizza_model->buscarItemPizzaPorCodigoPizza($pizza["codigo_pizza"]);
                    for($j=0; $j < count($todosSabores);$j++){
                        $sabor = $this->Pizza_sabor_model->buscarSaborCodigo($todosSabores[$j]["sabor_pizza_item_pizza"]);
                        //if($j+1 == count($todosSabores)){
                            $resposta .= "        - ".( (strlen($sabor["descricao_sabor_pizza"]) < 32) ? $sabor["descricao_sabor_pizza"] : substr($sabor["descricao_sabor_pizza"], 0, 32))."\n";
                        //}
                    }
                   
                    $todosItemExtras = $this->Item_extra_pizza_model->buscaItemExtraPizzaPorCodigoPizza($pizza["codigo_pizza"]);
                    if($todosItemExtras != null){
                        for($j=0; $j < count($todosItemExtras);$j++){
                            $extra = $this->Pizza_extra_model->buscarExtraCodigo($todosItemExtras[$j]["extra_pizza_item_extra_pizza"], $pizzaria);
                            if($extra != null){
                                $tipo = $this->Tipo_extra_model->buscarTipoExtraPorId($extra["tipo_extra_pizza_extra_pizza"], $pizzaria);
                                //if($j+1 == count($todosItemExtras)){
                                    $resposta .= "        - ".( (strlen($tipo["descricao_tipo_extra_pizza"]." ".$extra["descricao_extra_pizza"]) < 32) ?
                                            $tipo["descricao_tipo_extra_pizza"]." ".$extra["descricao_extra_pizza"] :
                                        substr($tipo["descricao_tipo_extra_pizza"]." ".$extra["descricao_extra_pizza"], 0, 32))."\n";
                               // }
                            }
                        }   
                    }
                    
                    
                }
            }
            // colocando as bebidas
            if($bebidasPedidas != null && count($bebidasPedidas)){
                for($i=0; $i < count($bebidasPedidas); $i++){ 
                    $bebida =  $this->Bebida_model->buscarBebidaPorId($bebidasPedidas[$i]["codigo"]);
                    $resposta .= $this->formataItemPedido($bebidasPedidas[$i]["quantidade"], $bebida["descricao_bebida"],"" , $quantCaracteres)."\n";
                }
            }
        }else{  
            return "";
        }
        
        
        $resposta .= "-----------------------------------------\n";
        $resposta .= $this->colocaDadoNoFim("OBSERVACAO: ", $pedido["observacao_pedido"],$quantCaracteres)."\n";

        $this->verificaCaracteresDasLinhas($resposta, $quantCaracteres);
        return $resposta;
    }
}
