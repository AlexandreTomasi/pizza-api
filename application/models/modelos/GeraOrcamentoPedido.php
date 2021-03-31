<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GeraOrcamentoPedido
 *
 * @author Alexandre
 */
class GeraOrcamentoPedido extends CI_Model{
    //put your code here
    public function solicitaOrcamentoPedidoPizza($pizzaria, $tamanho, $sabor, $ItemExtra, $ItemBebida, $bairro){
        $this->load->model("pizzaria/Valor_pizza_model");
        $this->load->model("pizzaria/Bebida_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Taxa_entrega_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        $this->load->model("pizzaria/Tipo_extra_model");
        $this->load->model("modelos/PedidoPizzaria");
        $custoTotal = 0;
        $texto = "";
        if($this->PedidoPizzaria->verificarItensPedidosAtivos($pizzaria, $tamanho, $sabor, $ItemExtra, $ItemBebida, $bairro, -1) == 0){
            throw new Exception("Existem produtos inativos");
        }
        // descobrindo custo das pizza
        $tamanhoAtual = explode("-",$tamanho);
        $saborAtual = explode("@",$sabor);
        $extrass = explode("@",$ItemExtra);
        for($i=0;$i < count($tamanhoAtual); $i++){
            for($a=$i; $a < $i+1;$a++){
                $saboresUni = explode("-",$saborAtual[$a]);
                $temp=0;
                $tamanhoPizza=$this->Pizza_tamanho_model->buscarTamanhoCodigo($tamanhoAtual[$i]);
                if($tamanhoPizza == null){throw new Exception("buscarTamanhoCodigo nao retornou dados. Funcionalidade: solicitaOrcamentoPedido->solicitaOrcamentoPedido");}
                $texto = $texto."\n".$tamanhoPizza["descricao_tamanho_pizza"]." Sabor: ";
                for($j=0; $j< count($saboresUni);$j++){
                    $saboresUni[$j];
                    $temp = $temp + $this->Valor_pizza_model->buscarValorPizzaAtivo($pizzaria, $saboresUni[$j], $tamanhoAtual[$i]);
                    $sabor = $this->Pizza_sabor_model->buscarSaborCodigo($saboresUni[$j]);
                    if($sabor == null){throw new Exception("buscarSaborCodigo nao retornou dados. Funcionalidade: solicitaOrcamentoPedido->solicitaOrcamentoPedido. Sabor uni = ".$saboresUni[$j]);}
                    if($j == count($saboresUni)-1){
                        $texto = $texto.$sabor["descricao_sabor_pizza"]." = R$ ";
                    }else{
                        if($j+2 == count($saboresUni)){
                            $texto = $texto.$sabor["descricao_sabor_pizza"]." e ";
                        }else{
                            $texto = $texto.$sabor["descricao_sabor_pizza"].", ";
                        }
                    }             
                }
                if($temp != 0){
                    $valorP = $temp/count($saboresUni);
                    $custoTotal = $custoTotal + $valorP;
                    $valorP = number_format($valorP, 2, '.', '');
                    $texto=$texto.$valorP.". ";
                }
                if($a < count($extrass)){
                   $extraAtual = explode("-",$extrass[$a]);
                    if($extraAtual[0] != 0){
                        $texto=$texto."Com ";
                    }
                    for($y=0;$y < count($extraAtual); $y++){
                        if($extraAtual[0] != 0){
                            $extraTemp = $this->Pizza_extra_model->buscarExtraCodigo($extraAtual[$y], $pizzaria);
                            $tipo = $this->Tipo_extra_model->buscarTipoExtraPorId($extraTemp["tipo_extra_pizza_extra_pizza"], $pizzaria);
                            if($extraTemp == null){throw new Exception("buscarExtraCodigo nao retornou dados. Funcionalidade: solicitaOrcamentoPedido->solicitaOrcamentoPedido");}
                               if($y+1 == count($extraAtual)){
                                    $texto = $texto.$tipo["descricao_tipo_extra_pizza"]." ".$extraTemp["descricao_extra_pizza"]."  R$ ".$extraTemp["preco_extra_pizza"].". ";
                               }else{
                                   if($y+2 == count($extraAtual)){
                                       $texto = $texto.$tipo["descricao_tipo_extra_pizza"]." ".$extraTemp["descricao_extra_pizza"]."  R$ ".$extraTemp["preco_extra_pizza"]." e ";
                                   }else{
                                       $texto = $texto.$tipo["descricao_tipo_extra_pizza"]." ".$extraTemp["descricao_extra_pizza"]."  R$ ".$extraTemp["preco_extra_pizza"].", ";
                                   }
                               }
                        }
                    } 
                }
                               
            }
        }
        
        
        // descobrindo custo dos extras
        $extrass = explode("@",$ItemExtra);
        for($j=0; $j < count($extrass) ;$j++){
            if($extrass[$j] != null){
                $extraAtual = explode("-",$extrass[$j]);
               /* if($extraAtual[0] != 0){
                    $texto=$texto." Extras: ";
                }*/
                $soma = 0;
                for($i=0;$i < count($extraAtual); $i++){
                    if($extraAtual[0] != 0){
                        $extraTemp = $this->Pizza_extra_model->buscarExtraCodigo($extraAtual[$i], $pizzaria);
                        if($extraTemp == null){throw new Exception("buscarExtraCodigo nao retornou dados. Funcionalidade: solicitaOrcamentoPedido->solicitaOrcamentoPedido");}
                        $soma = $soma + $extraTemp["preco_extra_pizza"];
                       // $texto = $texto.$extraTemp["descricao_extra_pizza"]." - R$ ".$extraTemp["preco_extra_pizza"]." ,";
                    }
                }
                if($soma != 0){
                    $custoTotal = $custoTotal+$soma;
                }
            }
        }
        
        // descobrindo custo das bebidas      
        $bebidaAtual = explode("-",$ItemBebida);
        if($bebidaAtual[0] != 0){
            $texto=$texto."\nBebidas: ";
        }
        $soma = 0;
        $inseridos = array();
        for($i=0;$i < count($bebidaAtual); $i++){
            if($bebidaAtual[0] != 0){
                $bebidaTemp = $this->Bebida_model->buscarBebidaPorIdECodgPizzaria($bebidaAtual[$i], $pizzaria);
                if($bebidaTemp == null){throw new Exception("buscarBebidaPorId nao retornou dados. Funcionalidade: solicitaOrcamentoPedido->solicitaOrcamentoPedido");}
                // quantas bebidas iguais existem no pedido
                $cont = 0;
                for($j=0; $j< count($bebidaAtual);$j++){
                    if($bebidaAtual[$i] == $bebidaAtual[$j]){
                        $cont=$cont+1;
                    }
                }
                // verifica se ela ja foi inserida
                $insere = true;
                for($j=0;$j < count($inseridos); $j++){
                    if($inseridos[$j] == $bebidaTemp["codigo_bebida"]){
                        $insere = false;
                    }
                }
                if($insere == true){
                    $soma = $soma + ($bebidaTemp["preco_bebida"] * $cont);
                    $texto = $texto.$cont." - ".$bebidaTemp["descricao_bebida"]." - R$ ".number_format($bebidaTemp["preco_bebida"]*$cont, 2, '.', '').", ";
                    $inseridos[] = $bebidaTemp["codigo_bebida"];
                }
            }
        }
        if($bebidaAtual[0] != 0){
            $oco = strrpos($texto,",");
            $texto = substr_replace($texto, '.', $oco);
        }
        if($soma != 0){
            $custoTotal = $custoTotal+$soma;
        }
        
        // descobrindo custo da taxa de entrega
        $soma = 0;
        $taxa = $this->Taxa_entrega_model->buscarTaxaEntregaPorBairro($pizzaria, $bairro);
        if($taxa == null){throw new Exception("buscarTaxaEntregaPorBairro nao retornou dados. Funcionalidade: solicitaOrcamentoPedido->solicitaOrcamentoPedido");}
        $soma = $taxa["preco_taxa_entrega"];
        if($soma != 0){
            $texto = $texto."\nTaxa de entrega: R$ ".$soma;
            $custoTotal = $custoTotal + $soma;
        }
        $custoTotal = number_format($custoTotal, 2, '.', '');
        $resposta = array();
        $resposta[0] = $custoTotal;
        $texto = "Valor total do pedido: R$ ".$custoTotal.".".$texto;
        $resposta[1] =  $texto;
        return $resposta;
    }
    
    public function dadosPedidoAtual($pizzaria, $tamanho, $sabor, $ItemExtra, $ItemBebida){
        $this->load->model("pizzaria/Valor_pizza_model");
        $this->load->model("pizzaria/Bebida_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Taxa_entrega_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        $this->load->model("pizzaria/Tipo_extra_model");
        $this->load->model("modelos/VerificadorStatusItens");
        $texto = "";
               
        if($this->VerificadorStatusItens->verificarExistenciaItensInativos($pizzaria, $tamanho, $sabor, $ItemExtra, $ItemBebida, "", "") == 0){
            throw new Exception("Existem produtos inativos");
        }
        // descobrindo custo das pizza
        $tamanhoAtual = explode("-",$tamanho);
        $saborAtual = explode("@",$sabor);
        $extrass = explode("@",$ItemExtra);
        for($i=0;$i < count($tamanhoAtual); $i++){
            for($a=$i; $a < $i+1;$a++){
                $saboresUni = explode("-",$saborAtual[$a]);
                $tamanhoPizza=$this->Pizza_tamanho_model->buscarTamanhoCodigo($tamanhoAtual[$i]);
                if($tamanhoPizza == null){throw new Exception("buscarTamanhoCodigo nao retornou dados. Funcionalidade: solicitaOrcamentoPedido->solicitaOrcamentoPedido");}
                $texto = $texto."\n".$tamanhoPizza["descricao_tamanho_pizza"]." Sabor: ";
                for($j=0; $j< count($saboresUni);$j++){
                    $saboresUni[$j];
                    $saborTemp = $this->Pizza_sabor_model->buscarSaborCodigo($saboresUni[$j]);
                    if($saborTemp == null){throw new Exception("buscarSaborCodigo nao retornou dados. Funcionalidade: solicitaOrcamentoPedido->solicitaOrcamentoPedido.");}
                    if($j == count($saboresUni)-1){
                        $texto = $texto.$saborTemp["descricao_sabor_pizza"].". ";
                    }else{
                        if($j+2 == count($saboresUni)){
                            $texto = $texto.$saborTemp["descricao_sabor_pizza"]." e ";
                        }else{
                            $texto = $texto.$saborTemp["descricao_sabor_pizza"].", ";
                        }
                    }             
                }
                if($a < count($extrass)){
                   $extraAtual = explode("-",$extrass[$a]);
                    if($extraAtual[0] != 0){
                        $texto=$texto."Com ";
                    }
                    for($y=0;$y < count($extraAtual); $y++){
                        if($extraAtual[0] != 0){
                            $extraTemp = $this->Pizza_extra_model->buscarExtraCodigo($extraAtual[$y], $pizzaria);
                            $tipo = $this->Tipo_extra_model->buscarTipoExtraPorId($extraTemp["tipo_extra_pizza_extra_pizza"], $pizzaria);
                            if($extraTemp == null){throw new Exception("buscarExtraCodigo nao retornou dados. Funcionalidade: solicitaOrcamentoPedido->solicitaOrcamentoPedido");}
                            if($y+1 == count($extraAtual)){
                                $texto = $texto.$tipo["descricao_tipo_extra_pizza"]." ".$extraTemp["descricao_extra_pizza"].". ";
                            }else{
                                if($y+2 == count($extraAtual)){
                                    $texto = $texto.$tipo["descricao_tipo_extra_pizza"]." ".$extraTemp["descricao_extra_pizza"]." e ";
                                }else{
                                    $texto = $texto.$tipo["descricao_tipo_extra_pizza"]." ".$extraTemp["descricao_extra_pizza"].", ";
                                }
                            }
                        }
                    } 
                }
                
                
            }
        }
        
        $bebidaAtual = explode("-",$ItemBebida);
        if($bebidaAtual[0] != 0){
            $texto=$texto." Bebidas: ";
        }
        $inseridos = array();
        for($i=0;$i < count($bebidaAtual); $i++){
            if($bebidaAtual[0] != 0){
                $bebidaTemp = $this->Bebida_model->buscarBebidaPorIdECodgPizzaria ($bebidaAtual[$i], $pizzaria);
                if($bebidaTemp == null){throw new Exception("buscarBebidaPorId nao retornou dados. Funcionalidade: solicitaOrcamentoPedido->solicitaOrcamentoPedido");}
                $cont = 0;
                for($j=0; $j< count($bebidaAtual);$j++){
                    if($bebidaAtual[$i] == $bebidaAtual[$j]){
                        $cont=$cont+1;
                    }
                }
                // verifica se ela ja foi inserida
                $insere = true;
                for($j=0;$j < count($inseridos); $j++){
                    if($inseridos[$j] == $bebidaTemp["codigo_bebida"]){
                        $insere = false;
                    }
                }
                if($insere == true){
                    $texto = $texto.$cont." - ".$bebidaTemp["descricao_bebida"].", ";
                    $inseridos[] = $bebidaTemp["codigo_bebida"];
                }
            }
        }
        if($bebidaAtual[0] != 0){
            $oco = strrpos($texto,",");
            $texto = substr_replace($texto, '.', $oco);
        }
        // descobrindo custo da taxa de entrega
        $resposta =  $texto;
        return $resposta;
    }
}