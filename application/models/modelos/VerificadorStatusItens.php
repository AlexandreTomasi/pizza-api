<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VerificadorStatusItens
 *
 * @author 033234581
 */
class VerificadorStatusItens extends CI_Model{
    //put your code here
    //$this->load->model("modelos/VerificadorStatusItens");
    //$this->VerificadorStatusItens->verificarExistenciaItensInativos($pizzaria, $tamanho, $sabor, $ItemExtra, $ItemBebida, $bairro, $formaPagamento)
    public function verificarExistenciaItensInativos($pizzaria, $tamanhoPizza, $sabor, $ItemExtra, $ItemBebida, $bairro, $formaPagamento){
        $this->load->model("pizzaria/Bebida_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Taxa_entrega_model");
        $this->load->model("pizzaria/Forma_pagamento_model");
        
        //verificando se tamanhos estão ativos
        if($tamanhoPizza != null && $tamanhoPizza != 0 && $tamanhoPizza != ""){
            $tamanhoAtual = explode("-",$tamanhoPizza);
            $temp = array();
            for($i=0;$i < count($tamanhoAtual); $i++){
                $resp = $this->Pizza_tamanho_model->buscarTamanhoPorCodigoEpizzaria($tamanhoAtual[$i], $pizzaria);
                if($resp == null){
                    return 0;
                }
            }
        }
        
        // verificar sabores
        if($sabor != null && $sabor != 0 && $sabor != ""){
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
        }
        
        //verificar extras
        if($ItemExtra != null && $ItemExtra != "" && $ItemExtra != 0){
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
        if($ItemBebida != null && $ItemBebida != "" && $ItemBebida != 0){
            $bebidaAtual = explode("-",$ItemBebida);
            for($i=0;$i < count($bebidaAtual); $i++){
                $resp = $this->Bebida_model->buscarBebidaPorIdECodgPizzaria($bebidaAtual[$i], $pizzaria);
                if($resp == null){
                    return 0;
                }
            }
        }
        
        if($bairro != null && $bairro != "" && $bairro != 0){
            $taxa = $this->Taxa_entrega_model->buscarTaxaEntregaPorBairro($pizzaria, $bairro);
            if($taxa == null){
                return 0;
            }
        }
        
        //verificar extras
        if($formaPagamento != null && $formaPagamento != "" && $formaPagamento != 0){
            $forma = $this->Forma_pagamento_model->buscarFormaPagamentoPorCodgAtiva($formaPagamento, $pizzaria);
            if($forma == null){
                return 0;
            }
        }
        return 1;
    }
}
