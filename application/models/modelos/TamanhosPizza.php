<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tamanhosPizza
 *
 * @author 033234581
 */
/*
 * {
  "messages": [{"attachment": {"type": "template","payload": {"template_type": "button","text": "Hello!","buttons": [
            {
              "type": "show_block",
              "block_name": "some block name",
              "title": "Show the block!"
            },
            {
              "type": "web_url",
              "url": "https://xxxx.parseapp.com/buy_item?item_id=100",
              "title": "Buy Item"
            }
          ]
        }
      }
    }
  ]
}
 */
class TamanhosPizza extends CI_Model{
    //put your code here
    public function criaCartao($tamanhos){
        $total = count($tamanhos);
        $botoes = array();
        for($i=0; $i < $total; $i++){
            $botoes[$i] = array(
                "type"=> "show_block",
                "block_name"=> "some block name",
                "title"=> $tamanhos[$i]["descricao_tamanho_pizza"]
            );
        }
        $dados = array('messages' => array(
                    array('attachment' => array(
                        'type' => 'template',
                        'payload' => array(
                            'template_type' => "button",
                            'text' => "Escolha!",
                            'buttons' => $botoes                               
                            )                
                    ))
                 ));
        $json_str = json_encode($dados);
        return $json_str;
    }

    public function criaGaleriaTamanho($pizzaria, $tamanhos){
        $this->load->model("pizzaria/Pizza_sabor_model");
        
        $total = count($tamanhos);
        $galeria = array();
        for($i=0; $i < $total; $i++){
            $sabores = $this->Pizza_sabor_model->buscarSaboresEmpresaComValorAtivos($pizzaria, $tamanhos[$i]["codigo_tamanho_pizza"]);
            if($sabores != null && count($sabores) > 0){
                $galeria[] = array(
                    "title"=> $tamanhos[$i]["descricao_tamanho_pizza"],
                    "image_url"=> "",
                    "subtitle"=> $tamanhos[$i]["quantidade_sabor_tamanho_pizza"]." sabores, ".$tamanhos[$i]["quantidade_fatias_tamanho_pizza"]." fatias",
                    "buttons" =>array(
                        array(
                            "type"=> "show_block",
                            "block_name"=> "Fluxo 04",
                            "title"=> $tamanhos[$i]["descricao_tamanho_pizza"]
                        )
                    )
                );
            }
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
    
    public function buscaTamanhosNaFaixa($pizzaria, $faixa){
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        $tamanhos = $this->Pizza_tamanho_model->buscarTamanhosComValorSaborAtivos($pizzaria);
        if($tamanhos == null && count($tamanhos) < 1){
                throw new Exception("busca Tamanhos Ativos, nao retornou nenhum dado. Funcionalidade: buscaTamanhosPizza->buscaTamanhosNaFaixa");
        }
        $total = count($tamanhos);
        $botoes = array();
        $galeria = array();
        
        if($faixa == 1){
            if($total > 9){
                $total = 9;
            }
            for($i=0; $i < $total; $i++){
                    $galeria[] = array(
                        "title"=> $tamanhos[$i]["descricao_tamanho_pizza"],
                        "image_url"=> "",
                        "subtitle"=> $tamanhos[$i]["quantidade_sabor_tamanho_pizza"]." sabores, ".$tamanhos[$i]["quantidade_fatias_tamanho_pizza"]." fatias",
                        "buttons" =>array(
                            array(
                                "type"=> "show_block",
                                "block_name"=> "Fluxo 04",
                                "title"=> $tamanhos[$i]["descricao_tamanho_pizza"]
                            )
                        )
                    );               
            }
            if($total == 9){
            $galeria[] = array(
                    "title"=> "Mais Tamanhos?",
                    "image_url"=> "",
                    "subtitle"=> "Clique aqui para mais tamanhos",
                    "buttons" =>array(
                        array(
                            "type"=> "show_block",
                            "block_name"=> "Fluxo 02",
                            "title"=> "+ Tamanhos"
                        )
                    )
                );
            }

        }else{
            $max = $faixa *10;
            $min= ($max -10)-($faixa-1);
            $max=$max-$faixa;
            if($max > $total){
                $max = $total;
            }
             
            $a=0;
            for($i=$min; $i < $max; $i++){  
                    $galeria[] = array(
                        "title"=> $tamanhos[$i]["descricao_tamanho_pizza"],
                        "image_url"=> "",
                        "subtitle"=> $tamanhos[$i]["quantidade_sabor_tamanho_pizza"]." sabores, ".$tamanhos[$i]["quantidade_fatias_tamanho_pizza"]." fatias",
                        "buttons" =>array(
                            array(
                                "type"=> "show_block",
                                "block_name"=> "Fluxo 04",
                                "title"=> $tamanhos[$i]["descricao_tamanho_pizza"]
                            )
                        )
                    );
                    $a=$a+1;
            }
            if($a == 9 && $max != $total){
                $galeria[] = array(
                    "title"=> "Mais Tamanhos?",
                    "image_url"=> "",
                    "subtitle"=> "Clique aqui para mais tamanhos",
                    "buttons" =>array(
                        array(
                            "type"=> "show_block",
                            "block_name"=> "Fluxo 02",
                            "title"=> "+ Tamanhos"
                        )
                    )
                );
            }
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
}
