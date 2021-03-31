<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OutrosCartoesBotoes
 *
 * @author Alexandre
 */
class OutrosCartoesBotoes extends CI_Model{
    //put your code here
    
    public function criaGaleriaOpcoes($pizzaria){
        //Bebida, Outra pizza, Não
        $this->load->model("pizzaria/Bebida_model");
        $this->load->model("pizzaria/Empresa_model");
        
        $bebidas = $this->Bebida_model->buscarBebidas($pizzaria);
        $galeria = array();
        if($bebidas != null && count($bebidas) > 0){
            $galeria[] = array(
                "title"=> "Bebida",
                "image_url"=> "",
                "subtitle"=> "Exemplo: Refrigerantes",
                "buttons" =>array(
                    array(
                        "type"=> "show_block",
                        "block_name"=> "Fluxo 18",
                        "title"=> "Bebida"
                    )
                )
            );
        }
        $nomeBotao = $this->Empresa_model->buscaConfigEmpresa("nome_botao_acrescenta_novo_produto_ao_pedido",$pizzaria);
        if($nomeBotao == ""){
           $nomeBotao = "Outra pizza"; 
        }
        $galeria[] = array(
            "title"=> $nomeBotao,
            "image_url"=> "",
            "subtitle"=> $nomeBotao,
            "buttons" =>array(
                array(
                    "type"=> "show_block",
                    "block_name"=> "Fluxo 22",
                    "title"=> $nomeBotao
                )
            )
        );
        $galeria[] = array(
            "title"=> "Não, obrigado(a)",
            "image_url"=> "",
            "subtitle"=> "",
            "buttons" =>array(
                array(
                    "type"=> "show_block",
                    "block_name"=> "Fluxo 24",
                    "title"=> "Não, obrigado(a)"
                )
            )
        );

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
