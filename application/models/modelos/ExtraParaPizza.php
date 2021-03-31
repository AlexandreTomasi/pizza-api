<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExtraParaPizza
 *
 * @author Alexandre
 */
class ExtraParaPizza extends CI_Model{
    
    public function buscarExtraPorBotaoClicado($pizzaria, $extraSelecionado, $tamanho){
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Tipo_extra_model");
        $todosTam = explode("-",$tamanho);
        $temp = explode(" ",$extraSelecionado);//o primeiro é o tipo o resto é o extra
        // descobrir qual é o tipo
        $codgTipo = 0;
        $botao = "";
        
        $tipos = $this->Tipo_extra_model->buscarTipoExtras($pizzaria);
        for($i=0; $i < count($tipos); $i++){
            $tempT = explode(" ",$tipos[$i]["descricao_tipo_extra_pizza"]);
            $abreviado = "";
            for($y=0; $y < count($tempT); $y++){
                $abreviado .= substr($tempT[$y], 0,1);
            }
            if($abreviado == $temp[0]){
                $codgTipo = $tipos[$i]["codigo_tipo_extra_pizza"];
                break;
            }
        }
        // nome do extra
        for($i=1; $i < count($temp); $i++){
            if($i == 1){$botao .= $temp[$i];}else{$botao .= " ".$temp[$i];}
        }
        if($codgTipo == 0 || $botao == ""){throw new Exception("busca Extra Por Name, não retornou nada. Funcionalidade setaValorAdicionalExtra->buscarExtraPorBotaoClicado");}
        return $this->Pizza_extra_model->buscarExtraPorNameTamanhoTipo($pizzaria, $botao, $todosTam[count($todosTam)-1], $codgTipo);
    }
    public function adicionaValorAdicionalExtra($pizzaria, $ItemExtra, $extraSelecionado, $quantidade, $tamanho){
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("modelos/SetVariaveisChat");
        $todosTam = explode("-",$tamanho);
        $temp = explode(" ",$extraSelecionado);

        $extraBd = $this->buscarExtraPorBotaoClicado($pizzaria, $extraSelecionado, $tamanho);
        if($extraBd == null){throw new Exception("busca Extra Por Name, não retornou nada. Funcionalidade setaValorAdicionalExtra->adicionaValorAdicionalExtra->buscarExtraPorName");}
        
        if($ItemExtra == '0' && $quantidade ==1){
            $resp = $this->SetVariaveisChat->mudaValorVariavelUtilitario($extraBd["codigo_extra_pizza"], "ItemExtra");
            return $resp;
        }else{           
            if($quantidade == 1){
                if($ItemExtra == 0){
                    $ItemExtra="";
                }
                $resp = $this->SetVariaveisChat->mudaValorVariavelUtilitario( $ItemExtra."-".$extraBd["codigo_extra_pizza"] , "ItemExtra");
                return $resp;
            }else{
                $quant= substr_count($ItemExtra, '@');
                if($quantidade - $quant == 1){ 
                    if($ItemExtra == '0'){
                        $ItemExtra="";
                    }
                    $resp = $this->SetVariaveisChat->mudaValorVariavelUtilitario( $ItemExtra."-".$extraBd["codigo_extra_pizza"] , "ItemExtra");
                    return $resp;    
                }else{
                    if($quantidade - $quant > 1){
                        $arroba ="";
                        for($i=1;$i < ($quantidade - $quant); $i++){
                            $arroba=$arroba."@";
                        }
                        if($ItemExtra == '0'){
                            $ItemExtra="";
                        }
                        $resp = $this->SetVariaveisChat->mudaValorVariavelUtilitario( $ItemExtra.$arroba.$extraBd["codigo_extra_pizza"] ,"ItemExtra");
                        return $resp;
                    }

                }
            }
        }       
    }
    
    // verifica se a quantidade possivel de extras para cada tipo ja foi preenchida, retornando a quantidade de extras ainda disponivel
    public function verificaExtraFinalizado($pizzaria, $ItemExtra, $quantidade, $tamanho){
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Tipo_extra_model");
        $todosTam = explode("-",$tamanho);
        $extras = $this->Pizza_extra_model->buscarExtrasAssociadoTamanho($todosTam[count($todosTam)-1], $pizzaria);
        if($extras == null || count($extras) < 1){
            return 0;// ja indica que nao tem extras
        }
        $total = count($extras);
        $galeria = array();
       
        // crio vetor que tem todos os tipos de extras
        $quantTipos = array();
        for($i=0; $i < $total; $i++){
            $possui =0;                     
            for($y=0; $y < count($quantTipos); $y++){
                if($extras[$i]["tipo_extra_pizza_extra_pizza"] == $quantTipos[$y]["codgTipo"]){
                    $possui=1;
                }
            }  
            if($possui == 0){
                $quantTipos[]=array(
                    "codgTipo" => $extras[$i]["tipo_extra_pizza_extra_pizza"],
                    "quant"=> 0
                        );       
            }          
        }
        // verificar quais extras ja foram adicionados 
        if($ItemExtra != 0){
            $extrass = explode("@",$ItemExtra);
            if(count($extrass) == $quantidade){
                $extraAtual = explode("-",$extrass[count($extrass)-1]);
                for($a=0; $a < count($extraAtual); $a++){
                    for($i=0; $i < $total; $i++){
                        if($extras[$i]["codigo_extra_pizza"] == $extraAtual[$a]){
                            for($y=0; $y < count($quantTipos); $y++){
                                if($quantTipos[$y]["codgTipo"] == $extras[$i]["tipo_extra_pizza_extra_pizza"]){
                                    $quantTipos[$y]["quant"] = $quantTipos[$y]["quant"]+1;
                                }
                            }
                        }
                
                    }
                }
            }
            // retira os tipos que ja foram escolhidos 
            $tempo = array();
            for($i=0; $i < $total; $i++){
                for($y=0; $y < count($quantTipos); $y++){
                    if($extras[$i]["tipo_extra_pizza_extra_pizza"] == $quantTipos[$y]["codgTipo"]){
                        if( ($quantTipos[$y]["quant"] < $extras[$i]["quantidade_tipo_extra_pizza"]) || ($extras[$i]["quantidade_tipo_extra_pizza"] == 0)){
                            $tempo[]=$extras[$i];
                        }
                    }
                }              
            }
            if($tempo == null || count($tempo) == 0){
                return 0;
            }else{
                return count($tempo);
            }
        }
        
        return $total;
    }
    
    
    public function buscaExtrasNaFaixa($pizzaria, $faixa, $ItemExtra, $quantidade, $tamanho){
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Tipo_extra_model");
        $this->load->model("pizzaria/Empresa_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        
        if($tamanho == '0'){
            throw new Exception("Busca de extra não retornou nada, pois tamanho está vazio");
        }
        $pizzaria = intval($pizzaria);
        $todosTam = explode("-",$tamanho);

        $extras = $this->Pizza_extra_model->buscarExtrasAssociadoTamanho($todosTam[count($todosTam)-1], $pizzaria);
        if($extras == null){throw new Exception("busca Todos Extras Da Empresa, não retornou nada. Funcionalidade buscaExtraParaPizza->buscaSaboresNaFaixa-> buscarTodosExtrasDaEmpresa");}      
        $total = count($extras);
        $galeria = array();
        
        $extraObrigatorio = $this->Empresa_model->buscaConfigEmpresa("extras_obrigatorio", $pizzaria);
        $extraObrigatorio = intval($extraObrigatorio);
        // crio vetor que tem todos os tipos de extras
        $quantTipos = array();
        for($i=0; $i < $total; $i++){
            $possui =0;                     
            for($y=0; $y < count($quantTipos); $y++){
                if($extras[$i]["tipo_extra_pizza_extra_pizza"] == $quantTipos[$y]["codgTipo"]){
                    $possui=1;
                }
            }  
            if($possui == 0){
                $quantTipos[]=array(
                    "codgTipo" => $extras[$i]["tipo_extra_pizza_extra_pizza"],
                    "quant"=> 0
                        );       
            }          
        }
        // verificar quais extras ja foram adicionados 
        if($ItemExtra != '0'){
            $extrass = explode("@",$ItemExtra);
            if(count($extrass) == $quantidade){
                $extraAtual = explode("-",$extrass[count($extrass)-1]);
                for($a=0; $a < count($extraAtual); $a++){
                    for($i=0; $i < $total; $i++){
                        if($extras[$i]["codigo_extra_pizza"] == $extraAtual[$a]){
                            for($y=0; $y < count($quantTipos); $y++){
                                if($quantTipos[$y]["codgTipo"] == $extras[$i]["tipo_extra_pizza_extra_pizza"]){
                                    $quantTipos[$y]["quant"] = $quantTipos[$y]["quant"]+1;
                                }
                            }
                        }
                
                    }
                }
            }
            
            // retira os tipos que ja foram escolhidos 
            $tempo = array();
            for($i=0; $i < $total; $i++){
                for($y=0; $y < count($quantTipos); $y++){
                    if($extras[$i]["tipo_extra_pizza_extra_pizza"] == $quantTipos[$y]["codgTipo"]){
                        if( ($quantTipos[$y]["quant"] < $extras[$i]["quantidade_tipo_extra_pizza"]) || ($extras[$i]["quantidade_tipo_extra_pizza"] == 0)){
                            $tempo[]=$extras[$i];
                        }
                    }
                }              
            }
            $extras=null;
            $extras=$tempo;
            $total = count($extras);
        }
        
        if($faixa == 1){
            if($total > 9){
                $total =9;
            }
            
            $a=0;
            if($extraObrigatorio != 1 && $extraObrigatorio == ""){
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
            }
            for($i=0; $i < $total; $i++){
                $tipo = $this->Tipo_extra_model->buscarTipoExtraPorId(intval($extras[$i]["tipo_extra_pizza_extra_pizza"]), $pizzaria);
                if($tipo == null){throw new Exception("busca Tipo Extra Por Id, não retornou nada. Funcionalidade buscaExtraParaPizza->buscaSaboresNaFaixa-> buscarTipoExtraPorId");}
                
                $nomeBotao = "";
                $tempT = explode(" ",$extras[$i]["descricao_tipo_extra_pizza"]);
                for($y=0; $y < count($tempT); $y++){
                    $nomeBotao .= substr($tempT[$y], 0,1);
                }
                $nomeBotao .= " ".$extras[$i]["descricao_extra_pizza"];
                $galeria[$a] = array(
                    "title"=> $tipo["descricao_tipo_extra_pizza"]." ".$extras[$i]["descricao_extra_pizza"],
                    "image_url"=> "",
                    "subtitle"=> "Preço: R$".$extras[$i]["preco_extra_pizza"],
                    "buttons" =>array(
                        array(
                            "type"=> "show_block",
                            "block_name"=> "Fluxo 14",
                            "title"=> $nomeBotao
                        )
                    )
                );
                $a++;
            }
            if(count($extras) > 8){
                $galeria[$i] = array(
                    "title"=> "Mais Extras?",
                    "image_url"=> "",
                    "subtitle"=> "Clique aqui para mais adicionais",
                    "buttons" =>array(
                        array(
                            "type"=> "show_block",
                            "block_name"=> "Fluxo 12",
                            "title"=> "+ Extras"
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
            if($extraObrigatorio != 1 && $extraObrigatorio == ""){
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
            }
            for($i=$min; $i < $max; $i++){      
                $tipo = $this->Tipo_extra_model->buscarTipoExtraPorId(intval($extras[$i]["tipo_extra_pizza_extra_pizza"]), $pizzaria);
                if($tipo == null){throw new Exception("busca Tipo Extra Por Id, não retornou nada. Funcionalidade buscaExtraParaPizza->buscaSaboresNaFaixa-> buscarTipoExtraPorId");}
                $nomeBotao = "";
                $tempT = explode(" ",$extras[$i]["descricao_tipo_extra_pizza"]);
                for($y=0; $y < count($tempT); $y++){
                    $nomeBotao .= substr($tempT[$y], 0,1);
                }
                $nomeBotao .= " ".$extras[$i]["descricao_extra_pizza"];
                $galeria[$a] = array(
                    "title"=> $tipo["descricao_tipo_extra_pizza"]." ".$extras[$i]["descricao_extra_pizza"],
                    "image_url"=> "",
                    "subtitle"=> "Preço: R$".$extras[$i]["preco_extra_pizza"],
                    "buttons" =>array(
                        array(
                            "type"=> "show_block",
                            "block_name"=> "Fluxo 14",
                            "title"=> $nomeBotao
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
                            "block_name"=> "Fluxo 12",
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
                            "block_name"=> "Fluxo 12",
                            "title"=> "+ Sabores"
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
    
    
    
    /*
    public function criaGaleria($extras){
        $total = count($extras);
        $galeria = array();
        for($i=0; $i < $total; $i++){
            $galeria[$i] = array(
                "title"=> $extras[$i]["descricao_extra_pizza"],
                "image_url"=> "",
                "subtitle"=> "Preço: ".$extras[$i]["preco_extra_pizza"],
                "buttons" =>array(
                    array(
                        "type"=> "show_block",
                        "block_name"=> "Recebe Adicional",
                        "title"=> $extras[$i]["descricao_extra_pizza"]
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
    }*/
    
    //put your code here
    /*public function criaCartao($extras){
        $total = count($extras);
        $botoes = array();
        for($i=0; $i < $total; $i++){
            $botoes[$i] = array(
                "type"=> "show_block",
                "block_name"=> "some block name",
                "title"=> $extras[$i]["descricao_extra_pizza"]
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
    }*/
}
