<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SetVariaveisChat
 *
 * @author 033234581
 *
{
  "set_attributes": 
    {
      "some attribute": "some value",
      "another attribute": "another value"
    },
  "block_names": ["BlockWithUserAttributes"],
  "type": "show_block",
  "title": "go"
}
 */
class SetVariaveisChat extends CI_Model{
    //put your code here
    
    public function mudaValorQuantidadePizza($quantidade){
        $valor = $quantidade+1;
        $dados = array('set_attributes' => array(                  
                        'QuantidadePizza' => $valor         
                    ),
            "block_names"=> "",
            "type"=> "show_block",
            "title"=> "go"
            );
        $json_str = json_encode($dados);
        return $json_str;
    }
    
    public function mudaValorTamanho($nome, $tamanhoAtual, $pizzaria, $faixa){
        $this->load->model("pizzaria/Pizza_tamanho_model"); 
        $taman = array();
        if($faixa == 999){// se for 999 entao só exite 1 opção de tamanho entao encontroo e seto.
            $resp = $this->Pizza_tamanho_model->buscarTamanhosComValorSaborAtivos($pizzaria);
            $taman = $resp[0];
            if(count($resp) != 1){
               throw new Exception("existe mais que 1 tamanho"); 
            }
        }else{                
            $taman = $this->Pizza_tamanho_model->buscarTamanhoNome($nome, $pizzaria);
        }
        
        if($taman != null){
            $valor = $taman["codigo_tamanho_pizza"];
            if($tamanhoAtual == 0){
                    $dados = array('set_attributes' => array(                  
                                    'TamanhoPizza' => $valor     
                                ),
                        "block_names"=> "",
                        "type"=> "show_block",
                        "title"=> "go"
                        );

                    $json_str = json_encode($dados);
                    return $json_str;
            }else{
                    $novo = $tamanhoAtual."-".$valor;
                    $dados = array('set_attributes' => array(                  
                                    'TamanhoPizza' => $novo       
                                ),
                        "block_names"=> "",
                        "type"=> "show_block",
                        "title"=> "go"
                        );

                    $json_str = json_encode($dados);
                    return $json_str;
            }
        }else{
            throw new Exception("A buscar tamanho por Nome, não retornou nada");
        }            
    }
    // metodo indica que não pode mais adicionar mais sabores na pizza.
    //incrementa valor na variavel VariosSabores para saber qual sabor esta escolhendo(primeiro segundo etc)
    // seta o valor 0 para variavel pg que indica faixa de sabores
    public function mudaValorFimSabores($pizzaria, $fimSabores, $sabor, $tamanho, $variosSabores){
        $this->load->model("pizzaria/Pizza_tamanho_model");
        //pegar o tamanho correto
        $temp = explode("-", $tamanho);
        if( count($temp) > 1){
            $tamanho = $temp[count($temp)-1];
        }
        
        
        
        $taman = $this->Pizza_tamanho_model->buscarTamanhoCodigo($tamanho);
        if($taman == null){throw new Exception("busca Tamanho por Codigo, não retornou nenhuma valor. Funcionalidade: mudaValorFimSabores");}
        // para calcular o total ja escolhido
        $separaPizza = explode("@", $sabor);
        if(count($separaPizza) > 1){
            $novo = $separaPizza[count($separaPizza)-1];
        }else{
            $novo = $sabor;
        }
            
        
        $string = explode("-", $novo);
        $total = count($string);
        if($total >= $taman["quantidade_sabor_tamanho_pizza"]){
            $dados = array('set_attributes' => array(                  
                        'FimSabores' => 0,
                        'VariosSabores' => 1,
                        'Pg' => 0
                    ),
            "block_names"=> "",
            "type"=> "show_block",
            "title"=> "go"
            );
        }else{
            $dados = array('set_attributes' => array(                  
                        'FimSabores' => 1,
                        'VariosSabores' => $variosSabores+1,
                        'Pg' => 0
                    ),
            "block_names"=> "",
            "type"=> "show_block",
            "title"=> "go"
            );
        }
        
        
        $json_str = json_encode($dados);
        return $json_str;
    }
    
    public function mudaValorVariavelUtilitario($valor, $nome){
        $dados = array('set_attributes' => array(                  
                        $nome => $valor         
                    ),
            "block_names"=> "",
            "type"=> "show_block",
            "title"=> "go"
            );
        $json_str = json_encode($dados);
        return $json_str;
    }    
    
    
}
