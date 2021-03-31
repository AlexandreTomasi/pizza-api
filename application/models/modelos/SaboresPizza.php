<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SaboresPizza
 *
 * @author Alexandre
 */
class SaboresPizza extends CI_Model{
    
    /*public function criaCartaoOpcaoMaisSabores(){
        
        $dados = array('messages' => array(
                    array('attachment' => array(
                        'type' => 'template',
                        'payload' => array(
                            'template_type' => "button",
                            'text' => "Gostaria de escolher mais um sabor?",
                            'buttons' => array(
                                            array(
                                            "type"=> "show_block",
                                            "block_name"=> "Fluxo 06",
                                            "title"=> "Sim"   
                                            ),
                                            array(
                                            "type"=> "show_block",
                                            "block_name"=> "Fluxo 10",
                                            "title"=> "Não"   
                                            )
                                        )
                            )                
                    ))
                 ));
        $json_str = json_encode($dados);
        return $json_str;
    }*/
    
    /*public function criaGaleriaSabores( $faixa, $pizzaria, $tamanho, $sabor){
        $this->load->model("pizzaria/Valor_pizza_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $todosTam = explode("-",$tamanho);
        $sabores = $this->Pizza_sabor_model->buscarSaboresEmpresaComValorAtivos($pizzaria, $todosTam[count($todosTam)-1]);
        $tamanhoBd = $this->Pizza_tamanho_model->buscarTamanhoPorCodigoEpizzaria($todosTam[count($todosTam)-1], $pizzaria);
        $total = count($sabores);
        $galeria = array();
        $fp = fopen("log.txt", "a");
        
             
        // metodo especial de faixa colocando opção NAO
        if($sabor != 0){//1-2@12
           // fwrite($fp, "\n".date('Y-m-d H:i:s')." - "."primeiro if");
            $saboresTemp = explode("@",$sabor);
            $saborAtual = explode("-",$saboresTemp[count($saboresTemp)-1]);
            if($saborAtual != null && count($saborAtual) < $tamanhoBd["quantidade_sabor_tamanho_pizza"] && count($saborAtual) > 0 && count($todosTam) == count($saboresTemp)){//se entrar ja sei que esta escolhendo segundo sabor ou mais.
                if($faixa == 1){
                    if($total > 9){
                        $total =9;
                    }
                    $galeria[0] = array(
                            "title"=> "Não desejo outro sabor, obrigado(a)",
                            "image_url"=> "",
                            "subtitle"=> "",
                            "buttons" =>array(
                                array(
                                    "type"=> "show_block",
                                    "block_name"=> "Fluxo 10",
                                    "title"=> "Não, obrigado(a)"
                                )
                            )
                        );
                    for($i=0; $i < $total; $i++){
                        $galeria[$i+1] = array(
                            "title"=> $sabores[$i]["descricao_sabor_pizza"]." - R$ ".$sabores[$i]["preco_valor_pizza"],
                            "image_url"=> "",
                            "subtitle"=> $sabores[$i]["ingredientes_sabor_pizza"],
                            "buttons" =>array(
                                array(
                                    "type"=> "show_block",
                                    "block_name"=> "Fluxo 08",
                                    "title"=> $sabores[$i]["descricao_sabor_pizza"]
                                )
                            )
                        );
                    }
                    if(count($sabores) > 8){
                        $galeria[$i] = array(
                            "title"=> "Mais Sabores?",
                            "image_url"=> "",
                            "subtitle"=> "Clique aqui para mais sabores",
                            "buttons" =>array(
                                array(
                                    "type"=> "show_block",
                                    "block_name"=> "Fluxo 06",
                                    "title"=> "+ Sabores"
                                )
                            )
                        );                   
                    }
                }else{
                    
                    $max = $faixa *10;
                    $min= (($max -10)-($faixa))-($faixa-2);
                    $max=$max-($faixa+2);
                    if($max > $total || ($max + 1) == $total){
                        $max = $total;
                    }
                    //fwrite($fp, "\n".date('Y-m-d H:i:s')." - "."primeiro if no else max,min,total ".$max.", ".$min.", ".$total);
                    $a=0;
                    $galeria[$a] = array(
                            "title"=> "Não desejo outro sabor, obrigado(a)",
                            "image_url"=> "",
                            "subtitle"=> "",
                            "buttons" =>array(
                                array(
                                    "type"=> "show_block",
                                    "block_name"=> "Fluxo 10",
                                    "title"=> "Não, obrigado(a)"
                                )
                            )
                        );
                    $a++;
                    for($i=$min; $i < $max; $i++){      
                        $galeria[$a] = array(
                            "title"=> $sabores[$i]["descricao_sabor_pizza"]." - R$ ".$sabores[$i]["preco_valor_pizza"],
                            "image_url"=> "",
                            "subtitle"=> $sabores[$i]["ingredientes_sabor_pizza"],
                            "buttons" =>array(
                                array(
                                    "type"=> "show_block",
                                    "block_name"=> "Fluxo 08",
                                    "title"=> $sabores[$i]["descricao_sabor_pizza"]
                                )
                            )
                        );
                        $a=$a+1;
                    }
                    if($faixa % 2 == 0 &&  $a == 9){// se for par
                        $galeria[$a] = array(
                            "title"=> "Mais Extras?",
                            "image_url"=> "",
                            "subtitle"=> "Clique aqui para mais adicionais",
                            "buttons" =>array(
                                array(
                                    "type"=> "show_block",
                                    "block_name"=> "Fluxo 06",
                                    "title"=> "+ Sabores"
                                )
                            ));
                    }else if($a == 9){
                        $galeria[$a] = array(
                            "title"=> "Mais Extras?",
                            "image_url"=> "",
                            "subtitle"=> "Clique aqui para mais adicionais",
                            "buttons" =>array(
                                array(
                                    "type"=> "show_block",
                                    "block_name"=> "Fluxo 06",
                                    "title"=> "+ Sabores"
                                )
                            )
                        );
                    }
                }
            }
        }
        // metodo normal de faixa
        if($faixa == 1 && $galeria == null){
            //fwrite($fp, "\n".date('Y-m-d H:i:s')." - "."segundo if");
            if($total > 9){
                $total = 9;
            }
            for($i=0; $i < $total; $i++){
                $galeria[$i] = array(
                    "title"=> $sabores[$i]["descricao_sabor_pizza"]." - R$ ".$sabores[$i]["preco_valor_pizza"],
                    "image_url"=> "",
                    "subtitle"=> $sabores[$i]["ingredientes_sabor_pizza"],
                    "buttons" =>array(
                        array(
                            "type"=> "show_block",
                            "block_name"=> "Fluxo 08",
                            "title"=> $sabores[$i]["descricao_sabor_pizza"]
                        )
                    )
                );
            }
            if($total == 9){
            $galeria[$i] = array(
                    "title"=> "Mais Sabores?",
                    "image_url"=> "",
                    "subtitle"=> "Clique aqui para mais sabores",
                    "buttons" =>array(
                        array(
                            "type"=> "show_block",
                            "block_name"=> "Fluxo 06",
                            "title"=> "+ Sabores"
                        )
                    )
                );
            }

        }else if($galeria == null){
            //fwrite($fp, "\n".date('Y-m-d H:i:s')." - "."terceiro if");
            $max = $faixa *10;
            $min= ($max -10)-($faixa-1);
            $max=$max-$faixa;
            if($max > $total){
                $max = $total;
            }
             
            $a=0;
            for($i=$min; $i < $max; $i++){              
                $galeria[$a] = array(
                    "title"=> $sabores[$i]["descricao_sabor_pizza"]." - R$ ".$sabores[$i]["preco_valor_pizza"],
                    "image_url"=> "",
                    "subtitle"=> $sabores[$i]["ingredientes_sabor_pizza"],
                    "buttons" =>array(
                        array(
                            "type"=> "show_block",
                            "block_name"=> "Fluxo 08",
                            "title"=> $sabores[$i]["descricao_sabor_pizza"]
                        )
                    )
                );
                $a=$a+1;
            }
            if($a == 9 && $max != $total){
                $galeria[$a] = array(
                    "title"=> "Mais Sabores?",
                    "image_url"=> "",
                    "subtitle"=> "Clique aqui para mais sabores",
                    "buttons" =>array(
                        array(
                            "type"=> "show_block",
                            "block_name"=> "Fluxo 06",
                            "title"=> "+ Sabores"
                        )
                ));
            }
        }
        
        fclose($fp);
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
    }*/

    public function adicionaValor($pizzaria, $tamanho, $sabor, $saborSelecionado, $fimSabores){
        $this->load->model("pizzaria/Pizza_sabor_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("modelos/SetVariaveisChat");
        
        $tamanhoAtual = explode("-",$tamanho);
        $saborAtual = explode("@",$sabor);
        $saboresUni = explode("-",$saborAtual[ count($saborAtual)-1 ]);
        
        $tamanhoPizza = $this->Pizza_tamanho_model->buscarTamanhoCodigo($tamanhoAtual[ count($tamanhoAtual)-1 ]);
        if($tamanhoPizza == null){throw new Exception("(SaboresPizza) metodo adicionaValor com tamanho vindo do banco nulo");}
        $saborBd = $this->Pizza_sabor_model->buscarSaborPorNome($pizzaria, $saborSelecionado);
        if($saborBd == null){
            throw new Exception("Busca Sabores por nome, não retornou nada. adicionaValorSaboresPizza->adicionaValor->buscarSaborPorNome");
        }
        if($fimSabores == 0 || count($saboresUni) <= $tamanhoPizza["quantidade_sabor_tamanho_pizza"]){
            if($sabor == 0){
                $sabor = $saborBd["codigo_sabor_pizza"];
                $resp = $this->SetVariaveisChat->mudaValorVariavelUtilitario($sabor, "Sabor");
                return $resp;
            }else{
                if($fimSabores == 0){
                    $resp = $this->SetVariaveisChat->mudaValorVariavelUtilitario( $sabor."@".$saborBd["codigo_sabor_pizza"] ,"Sabor");
                    return $resp;
                }else{
                    $resp = $this->SetVariaveisChat->mudaValorVariavelUtilitario( $sabor."-".$saborBd["codigo_sabor_pizza"] ,"Sabor");
                    return $resp;
                }
            }
        }
    }   
    
    public function criaMensagemTexto($pizzaria, $tamanho, $sabor){
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $taman = $this->Pizza_tamanho_model->buscarTamanhoCodigo($tamanho);
        if($sabor == 0){
            
        }
        if($taman["quantidade_sabor_tamanho_pizza"] > 1){
            $textos = array('messages' => array(
                        array( 'text' => "Gostaria de escolher mais um sabor?")
                      ));
        }
        
        $json_str = json_encode($textos);
        return $json_str;
                    
    }
    
    
    public function retornaGaleriaSabores( $faixa, $pizzaria, $tamanho, $sabor){
        $this->load->model("pizzaria/Valor_pizza_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("modelos/VerificadorStatusItens");
        
        if($this->VerificadorStatusItens->verificarExistenciaItensInativos($pizzaria, $tamanho, $sabor, "", "", "", "") == 0){
            throw new Exception("Ops, parece que um ou mais itens não está mais disponível.");
        }
        $todosTam = explode("-",$tamanho);
        $sabores = $this->Pizza_sabor_model->buscarSaboresEmpresaComValorAtivos($pizzaria, $todosTam[count($todosTam)-1]);
        $tamanhoBd = $this->Pizza_tamanho_model->buscarTamanhoPorCodigoEpizzaria($todosTam[count($todosTam)-1], $pizzaria);
        if($sabores == null || $tamanhoBd == null){throw new Exception("(SaboresPizza) metodo retornaGaleriaSabores com sabores ou tamanho vindo do banco nulo");}

        $total = count($sabores);
        $galeria = array(); 
        $naoObrigado = false;
        $saboresTemp = explode("@",$sabor);
        $saborAtual = explode("-",$saboresTemp[count($saboresTemp)-1]);
        if($sabor != 0){// verifica se o sabor é obrigatorio, se for entao tira o não obrigado
            $resultado = ($tamanhoBd["quantidade_fatias_tamanho_pizza"] / count($saborAtual));
            if(is_int($resultado)){
                $naoObrigado = true;
            }
        }
            
        // metodo especial de faixa colocando opção NAO
        if($sabor != 0){//1-2@12
        
            //se entrar ja sei que esta escolhendo segundo sabor ou mais.   
            if($saborAtual != null && count($saborAtual) > 0 && count($saborAtual) < $tamanhoBd["quantidade_sabor_tamanho_pizza"]  && count($todosTam) == count($saboresTemp)){
                if($faixa == 1){
                    $a=0;
                    if($total > 8){
                        $total =8;
                    }
                    if($naoObrigado == true){                        
                        $galeria[$a] = array(
                                "title"=> "Não desejo outro sabor, obrigado(a)","image_url"=> "","subtitle"=> "","buttons" =>array(
                                    array("type"=> "show_block", "block_name"=> "Fluxo 10","title"=> "Não, obrigado(a)"))
                        );
                        $a++;
                    }
                    
                    for($i=0; $i < $total; $i++){
                        $galeria[$a] = array(
                            "title"=> $sabores[$i]["descricao_sabor_pizza"]." - R$ ".$sabores[$i]["preco_valor_pizza"],
                            "image_url"=> "",
                            "subtitle"=> $sabores[$i]["ingredientes_sabor_pizza"],
                            "buttons" =>array(
                                array("type"=> "show_block","block_name"=> "Fluxo 08","title"=> $sabores[$i]["descricao_sabor_pizza"])
                            )
                        );
                        $a++;
                    }
                    if(count($sabores) > 8){
                        $galeria[$a] = array(
                            "title"=> "Mais Sabores?","image_url"=> "","subtitle"=> "Clique aqui para mais sabores","buttons" =>array(
                                array("type"=> "show_block","block_name"=> "Fluxo 06", "title"=> "+ Sabores"))
                        );                   
                    }
                }else{
                    $a=0;
                    $max = $faixa *10;
                    $min= (($max -10)-($faixa))-($faixa-2);
                    $max=$max-($faixa+2);
                    if($max > $total || ($max + 1) == $total){
                        $max = $total;
                    }
                    
                    if($naoObrigado == true){
                        $galeria[$a] = array(
                                "title"=> "Não desejo outro sabor, obrigado(a)", "image_url"=> "","subtitle"=> "","buttons" =>array(
                                    array("type"=> "show_block","block_name"=> "Fluxo 10","title"=> "Não, obrigado(a)"))
                            );
                        $a++;
                    }
                    for($i=$min; $i < $max; $i++){      
                        $galeria[$a] = array(
                            "title"=> $sabores[$i]["descricao_sabor_pizza"]." - R$ ".$sabores[$i]["preco_valor_pizza"],
                            "image_url"=> "",
                            "subtitle"=> $sabores[$i]["ingredientes_sabor_pizza"],
                            "buttons" =>array(
                                array("type"=> "show_block","block_name"=> "Fluxo 08","title"=> $sabores[$i]["descricao_sabor_pizza"])
                            )
                        );
                        $a++;
                    }
                    if(($a == 8 || $a == 9) && count($sabores) > $max){// se for par
                        $galeria[$a] = array(
                            "title"=> "Mais Sabores?","image_url"=> "","subtitle"=> "Clique aqui para mais sabores","buttons" =>array(
                                array("type"=> "show_block","block_name"=> "Fluxo 06","title"=> "+ Sabores"))
                        );
                    }
                }
            }
        }
        // metodo normal de faixa
        if($faixa == 1 && $galeria == null){
            if($total > 8){
                $total = 8;
            }
            for($i=0; $i < $total; $i++){
                $galeria[$i] = array(
                    "title"=> $sabores[$i]["descricao_sabor_pizza"]." - R$ ".$sabores[$i]["preco_valor_pizza"],
                    "image_url"=> "",
                    "subtitle"=> $sabores[$i]["ingredientes_sabor_pizza"],
                    "buttons" =>array(
                        array("type"=> "show_block","block_name"=> "Fluxo 08","title"=> $sabores[$i]["descricao_sabor_pizza"])
                    )
                );
            }
            if($total == 8 && count($sabores) > 8){
                $galeria[$i] = array(
                    "title"=> "Mais Sabores?",
                    "image_url"=> "",
                    "subtitle"=> "Clique aqui para mais sabores",
                    "buttons" =>array(
                        array("type"=> "show_block","block_name"=> "Fluxo 06","title"=> "+ Sabores")
                    )
                );
            }

        }else if($galeria == null){
            $a=0;
            $max = $faixa *10;
            $min= (($max -10)-($faixa))-($faixa-2);
            $max=$max-($faixa+2);
            if($max > $total || ($max + 1) == $total){
                $max = $total;
            }
             
            for($i=$min; $i < $max; $i++){              
                $galeria[$a] = array(
                    "title"=> $sabores[$i]["descricao_sabor_pizza"]." - R$ ".$sabores[$i]["preco_valor_pizza"],
                    "image_url"=> "",
                    "subtitle"=> $sabores[$i]["ingredientes_sabor_pizza"],
                    "buttons" =>array(
                        array("type"=> "show_block","block_name"=> "Fluxo 08","title"=> $sabores[$i]["descricao_sabor_pizza"])
                    )
                );
                $a++;
            }
            if($a == 8 && count($sabores) > $max){
                $galeria[$a] = array(
                    "title"=> "Mais Sabores?",
                    "image_url"=> "",
                    "subtitle"=> "Clique aqui para mais sabores",
                    "buttons" =>array(
                        array("type"=> "show_block","block_name"=> "Fluxo 06","title"=> "+ Sabores")
                ));
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
    
    public function verificaObrigatoriedadeMaisUmSabor($pizzaria, $tamanho, $sabor){
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $todosTam = explode("-",$tamanho);
        $tamanhoBd = $this->Pizza_tamanho_model->buscarTamanhoPorCodigoEpizzaria($todosTam[count($todosTam)-1], $pizzaria);
        if($tamanhoBd == null && $sabor != null){throw new Exception("(SaboresPizza) metodo retornaGaleriaSabores com sabores ou tamanho vindo do banco nulo");}

        $saboresTemp = explode("@",$sabor);
        $saborAtual = explode("-",$saboresTemp[count($saboresTemp)-1]);
        if($sabor == 0){
            return 0;
        }
        $resultado = ($tamanhoBd["quantidade_fatias_tamanho_pizza"] / count($saborAtual));
        if(is_int($resultado)){
            return 1;
        }
        return 0;
    }
        
}
