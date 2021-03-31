<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BebidasPizzaria
 *
 * @author Alexandre
 */
class BebidasPizzaria extends CI_Model{
    //put your code here
    public function criaCartao($bebidas){
        $total = count($bebidas);
        $botoes = array();
        for($i=0; $i < $total; $i++){
            $botoes[$i] = array(
                "type"=> "show_block",
                "block_name"=> "some block name",
                "title"=> $bebidas[$i]["descricao_bebida"]
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
    
    public function criaGaleriaBebidas($bebidas, $faixa){
        $total = count($bebidas);
        $galeria = array();
        for($i=0; $i < $total; $i++){
            $galeria[$i] = array(
                "title"=> $bebidas[$i]["descricao_bebida"],
                "image_url"=> "",
                "subtitle"=> "Preço: R$".$bebidas[$i]["preco_bebida"],
                "buttons" =>array(
                    array(
                        "type"=> "show_block",
                        "block_name"=> "Recebe Bebida",
                        "title"=> $bebidas[$i]["descricao_bebida"]
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
    
    public function adicionaValorVariavelBebida($pizzaria, $ItemBebida, $extraSelecionado){
        $this->load->model("pizzaria/Bebida_model");
        $this->load->model("modelos/SetVariaveisChat");
        $extraBd = $this->Bebida_model->buscarBebidasPizzariaAtivaPorNome($pizzaria, $extraSelecionado);
        if($extraBd == null){throw new Exception("buscar Bebidas Pizzaria Ativa Por Nome, não retonou nenhum dado. Funcionalidade: setaValorBebidaSelecionada->adicionaValorVariavelBebida");}
        
        if($ItemBebida == 0){
            $ItemBebida = $extraBd["codigo_bebida"];
            $resp = $this->SetVariaveisChat->mudaValorVariavelUtilitario($ItemBebida, "ItemBebida");
            return $resp;
        }else{
            $resp = $this->SetVariaveisChat->mudaValorVariavelUtilitario( $ItemBebida."-".$extraBd["codigo_bebida"] , "ItemBebida");
            return $resp;
        }
        
    }
    
    public function buscaBebidasNaFaixa($pizzaria, $faixa){
        $this->load->model("pizzaria/Bebida_model");
        $bebidas = $this->Bebida_model->buscarBebidasPizzariaAtivas($pizzaria);
        if($bebidas == null){
                throw new Exception("busca Bebidas Pizzaria Ativas, nao retornou nenhum dado. Funcionalidade: buscaBebidasPizzaria->buscarBebidasPizzariaAtivas");
        }
        
        $total = count($bebidas);
        $galeria = array();
        
        if($faixa == 1){
            if($total > 9){
                $total = 9;
            }
            
            $galeria[0] = array(
                    "title"=> "Não, obrigado(a)",
                    "image_url"=> "",
                    "subtitle"=> "",
                    "buttons" =>array(
                        array(
                            "type"=> "show_block",
                            "block_name"=> "Fluxo 16",
                            "title"=> "Não, obrigado(a)"
                        )
                    )
                );
            for($i=0; $i < $total; $i++){
                $galeria[$i+1] = array(
                    "title"=> $bebidas[$i]["descricao_bebida"],
                    "image_url"=> "",
                    "subtitle"=> "Preço: R$".$bebidas[$i]["preco_bebida"],
                    "buttons" =>array(
                        array(
                            "type"=> "show_block",
                            "block_name"=> "Fluxo 20",
                            "title"=> $bebidas[$i]["descricao_bebida"]
                        )
                    )
                );
            }
            if(count($bebidas) > 8){
                $galeria[$i] = array(
                    "title"=> "Mais Bebidas?",
                    "image_url"=> "",
                    "subtitle"=> "Clique aqui para mais opções de bebidas",
                    "buttons" =>array(
                        array(
                            "type"=> "show_block",
                            "block_name"=> "Fluxo 18",
                            "title"=> "+ Bebidas"
                        )
                    )
                );
            }

        }else{
            $max = $faixa *10;
            $min= ($max -10)-($faixa);
            $max=$max-$faixa+1;
            if($max > $total){
                $max = $total;
            }
             
            $a=0;
            $galeria[$a] = array(
                    "title"=> "Não, obrigado(a)",
                    "image_url"=> "",
                    "subtitle"=> "",
                    "buttons" =>array(
                        array(
                            "type"=> "show_block",
                            "block_name"=> "Fluxo 16",
                            "title"=> "Não, obrigado(a)"
                        )
                    )
                );
            $a++;
            for($i=$min; $i < $max; $i++){      
                $galeria[$a] = array(
                    "title"=> $bebidas[$i]["descricao_bebida"],
                    "image_url"=> "",
                    "subtitle"=> "Preço: R$".$bebidas[$i]["preco_bebida"],
                    "buttons" =>array(
                        array(
                            "type"=> "show_block",
                            "block_name"=> "Fluxo 20",
                            "title"=> $bebidas[$i]["descricao_bebida"]
                        )
                    )
                );
                $a=$a+1;
            }
            if($faixa % 2 == 0 &&  $a == 9){// se for par
                $galeria[$a] = array(
                    "title"=> "Mais Bebidas?",
                    "image_url"=> "",
                    "subtitle"=> "Clique aqui para mais opções de bebidas",
                    "buttons" =>array(
                        array(
                            "type"=> "show_block",
                            "block_name"=> "Fluxo 18",
                            "title"=> "+ Bebidas"
                        )
                    ));
            }else if($a == 9){
                $galeria[$a] = array(
                    "title"=> "Mais Bebidas?",
                    "image_url"=> "",
                    "subtitle"=> "Clique aqui para mais opções de bebidas",
                    "buttons" =>array(
                        array(
                            "type"=> "show_block",
                            "block_name"=> "Fluxo 18",
                            "title"=> "+ Bebidas"
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
