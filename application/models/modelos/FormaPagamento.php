<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormaPagamento
 *
 * @author 033234581
 */
class FormaPagamento extends CI_Model{
    
    public function criaGaleriaFormaPagamento($formas){
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
                        "block_name"=> "Fluxo 34",
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
    
    public function encontraFormaPagamento($pizzaria, $FormaPgSelecionada){
        $this->load->model("pizzaria/Forma_pagamento_model");
        $this->load->model("modelos/SetVariaveisChat");
        $formaPagamento = $this->Forma_pagamento_model->buscarFormaPagamentoDescricaoAtivaInativa($FormaPgSelecionada, $pizzaria);
        return $formaPagamento;
    }
}
