<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pizza_model
 *
 * @author 033234581
 */
class Pizza_tamanho_model extends CI_Model{
    //put your code here
    
    
    public function buscarTamanhos($codigo){
        if($codigo == null ){throw new Exception("(Pizza_tamanho_model) metodo buscarTamanhos com parametros nulos");}
        $this->db->where("pizzaria_tamanho_pizza", $codigo);
        $this->db->where("ativo_tamanho_pizza", 1);
        return $this->db->get("tamanho_pizza")->result_array();
    }
    
    public function buscarTamanhosAtivosInativos($codigo){
        if($codigo == null ){throw new Exception("(Pizza_tamanho_model) metodo buscarTamanhos com parametros nulos");}
        $this->db->where("pizzaria_tamanho_pizza", $codigo);
        $this->db->where_in("ativo_tamanho_pizza", array(0,1));
        return $this->db->get("tamanho_pizza")->result_array();
    }

    public function inserirTamanhoRetornandoo($tamanho){
        if($tamanho == null ){throw new Exception("(Pizza_tamanho_model) metodo inserirTamanhoRetornandoo com parametros nulos");}
        $resp=0;
        $this->db->trans_start();
        $in = $this->db->insert("tamanho_pizza",$tamanho);
        $resp = $this->db->insert_id();
        $this->db->trans_complete();
        $tamanho["codigo_tamanho_pizza"] = $resp;
        return $tamanho;
    }
    
    public function removerTamanhoPorCodigo($codigo, $codgPizzaria){
        if($codigo == null || $codgPizzaria == null){throw new Exception("(Pizza_tamanho_model) metodo removerTamanho com parametros nulos");}
        $this->db->where("codigo_tamanho_pizza", $codigo);
        $this->db->where("pizzaria_tamanho_pizza", $codgPizzaria);
        $this->db->where_in("ativo_tamanho_pizza", array(0,1));
        $taman = $this->db->get("tamanho_pizza")->row_array();
        $taman["ativo_tamanho_pizza"] = 2;
        
        $this->db->where("codigo_tamanho_pizza", $taman["codigo_tamanho_pizza"]);
        $this->db->where("pizzaria_tamanho_pizza", $taman["pizzaria_tamanho_pizza"]);
        return $this->db->update("tamanho_pizza",$taman);
    }
    public function ativarTamanhoPorCodigo($codigo, $codgPizzaria){
        if($codigo == null || $codgPizzaria == null){throw new Exception("(Pizza_tamanho_model) metodo removerTamanho com parametros nulos");}
        $this->db->where("codigo_tamanho_pizza", $codigo);
        $this->db->where("pizzaria_tamanho_pizza", $codgPizzaria);
        $this->db->where("ativo_tamanho_pizza", 0);
        $taman = $this->db->get("tamanho_pizza")->row_array();
        $taman["ativo_tamanho_pizza"] = 1;
        
        $this->db->where("codigo_tamanho_pizza", $taman["codigo_tamanho_pizza"]);
        $this->db->where("pizzaria_tamanho_pizza", $taman["pizzaria_tamanho_pizza"]);
        return $this->db->update("tamanho_pizza",$taman);
    }
    public function inativarTamanhoPorCodigo($codigo, $codgPizzaria){
        if($codigo == null || $codgPizzaria == null){throw new Exception("(Pizza_tamanho_model) metodo removerTamanho com parametros nulos");}
        $this->db->where("codigo_tamanho_pizza", $codigo);
        $this->db->where("pizzaria_tamanho_pizza", $codgPizzaria);
        $this->db->where("ativo_tamanho_pizza", 1);
        $taman = $this->db->get("tamanho_pizza")->row_array();
        $taman["ativo_tamanho_pizza"] = 0;
        
        $this->db->where("codigo_tamanho_pizza", $taman["codigo_tamanho_pizza"]);
        $this->db->where("pizzaria_tamanho_pizza", $taman["pizzaria_tamanho_pizza"]);
        return $this->db->update("tamanho_pizza",$taman);
    }
    
    public function buscarTamanhoCodigo($codigo){
        if($codigo == null ){throw new Exception("(Pizza_tamanho_model) metodo buscarTamanhoCodigo com parametros nulos");}
        $this->db->where("codigo_tamanho_pizza", $codigo);
        $this->db->where("ativo_tamanho_pizza", 1);
        return $this->db->get("tamanho_pizza")->row_array();
    }
    
    public function buscarListaDeTamanho($lista){
        if($codigo == null ){throw new Exception("(Pizza_tamanho_model) metodo buscarTamanhoCodigo com parametros nulos");}
        $this->db->where_in("codigo_tamanho_pizza", $lista);
        $this->db->where("ativo_tamanho_pizza", 1);
        return $this->db->get("tamanho_pizza")->result_array();
    }
    
    public function buscarTamanhoPorCodigoEpizzaria($codigo, $pizzaria){
        if($codigo == null || $pizzaria == null){throw new Exception("(Pizza_tamanho_model) metodo buscarTamanhoPorCodigoEpizzaria com parametros nulos");}
        $this->db->where("codigo_tamanho_pizza", $codigo);
        $this->db->where("pizzaria_tamanho_pizza", $pizzaria);
        $this->db->where("ativo_tamanho_pizza", 1);
        return $this->db->get("tamanho_pizza")->row_array();
    }
    
    public function buscarTamanhoPorCodigoAtivoInativo($codigo, $pizzaria){
        if($codigo == null || $pizzaria == null){throw new Exception("(Pizza_tamanho_model) metodo buscarTamanhoPorCodigoEpizzaria com parametros nulos");}
        $this->db->where("codigo_tamanho_pizza", $codigo);
        $this->db->where("pizzaria_tamanho_pizza", $pizzaria);
        $this->db->where_in("ativo_tamanho_pizza", array(0,1));
        return $this->db->get("tamanho_pizza")->row_array();
    }
    
    public function buscarTamanhoNome($nome, $pizzaria){
        if($nome == null || $pizzaria == null){throw new Exception("(Pizza_tamanho_model) metodo buscarTamanhoNome com parametros nulos");}
        $this->db->where("descricao_tamanho_pizza", $nome);
        $this->db->where("pizzaria_tamanho_pizza", $pizzaria);
        $this->db->where("ativo_tamanho_pizza", 1);
        return $this->db->get("tamanho_pizza")->row_array();
    }
    
    public function alterarTamanho($tamanho){
        if($tamanho == null ){throw new Exception("(Pizza_tamanho_model) metodo alterarTamanho com parametros nulos");}
        $this->db->where('codigo_tamanho_pizza', $tamanho['codigo_tamanho_pizza']);
        $this->db->where("pizzaria_tamanho_pizza", $tamanho["pizzaria_tamanho_pizza"]);
        $this->db->set($tamanho);
        return $this->db->update("tamanho_pizza",$tamanho);
    }
    
    
    
    public function buscarTamanhosComValorSaborAtivos($codigo){
        if($codigo == null ){throw new Exception("(Pizza_tamanho_model) metodo buscarTamanhosComValorSaborAtivos com parametros nulos");}
        $this->load->model("pizzaria/Valor_pizza_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        $this->db->where("pizzaria_tamanho_pizza", $codigo);
        $this->db->where("ativo_tamanho_pizza", 1);
        $tamanhos =  $this->db->get("tamanho_pizza")->result_array();
        
        $this->db->where("pizzaria_sabor_pizza", $codigo);
        $this->db->where("ativo_sabor_pizza", 1);
        $sabor = $this->db->get("sabor_pizza")->result_array();
        $resp = array();
        for($i=0;$i < count($tamanhos);$i++){
            $temp=0;
            for($a=0;$a < count($sabor);$a++){
                $valor = $this->Valor_pizza_model->buscarValorPizzaAtivo($codigo, $sabor[$a]["codigo_sabor_pizza"], $tamanhos[$i]["codigo_tamanho_pizza"]);
                if($valor != null && $valor >= 0){
                    $temp=1;
                }
            }
            if($temp==1){
                $resp[] = $tamanhos[$i];
            }
        }
        return $resp;
    }
}
