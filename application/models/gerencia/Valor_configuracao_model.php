<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of valor_configuracao_model
 *
 * @author Alexandre
 */
class Valor_configuracao_model extends CI_Model{
    //put your code here
    public function tipoImpressao (){
        $tipoImpressao[0] = array("valor" => 1,"desc" => "Não Imprimir");
        $tipoImpressao[1] = array("valor" => 2,"desc" => "Bematech MP-4200");
        return $tipoImpressao;
    }
    public function buscaPorCodigoCliente($codigo){
        if($codigo == null){
            throw new Exception("(Valor_configuracao_model) metodo buscaPorCodigoCliente com parametros nulos");
        }
        $bdgerente = $this->load->database('gerencia', true);
        $bdgerente->where("cliente_valor_configuracao", $codigo);
        $bdgerente->join('configuracao', 'configuracao.codigo_configuracao = valor_configuracao.configuracao_valor_configuracao');
        $configuracao = $bdgerente->get("valor_configuracao")->result_array();
        return $configuracao;
    }

    public function buscaPorCodigoClienteEConfiguracao($codigo, $config){
        if($codigo == null || $config == null){
            throw new Exception("(Valor_configuracao_model) metodo buscaPorCodigoClienteEConfiguracao com parametros nulos");
        }
        $bdgerente = $this->load->database('gerencia', true);
        $bdgerente->where("cliente_valor_configuracao", $codigo);
        $bdgerente->where("configuracao_valor_configuracao", $config);
        $configuracao = $bdgerente->get("valor_configuracao")->result_array();
        return $configuracao;
    }
    public function alterarValorConfiguracao($confg){
        if($confg == null){
            throw new Exception("(Valor_configuracao_model) metodo alterarValorConfiguracao com parametros nulos");
        }
        $bdgerente = $this->load->database('gerencia', true);
        $bdgerente->where('codigo_valor_configuracao', $confg['codigo_valor_configuracao']);
        $bdgerente->set($confg);
        $resultado =  $bdgerente->update("valor_configuracao",$confg);
    }
    
    public function insereValorConfiguracao($valorConfg){
        if($valorConfg == null){throw new Exception("(Valor_configuracao_model) metodo insereValorConfiguracao com parametros nulos");}
        $bdgerente = $this->load->database('gerencia', true);
        $resultado = $bdgerente->insert("valor_configuracao",$valorConfg);
        return $resultado;
    }
}
