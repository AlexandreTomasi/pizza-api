<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AnalisadorPergunta
 *
 * @author 033234581
 */
class AnalisadorPergunta extends CI_Model{
    //put your code here
    public function analisador($pergunta, $usuario, $pizzaria){       
       // $this->load->model("AssuntoModel");      
        $this->load->model("ia/ConversacaoModel");
        try{
            $pergunta = str_replace(","," ",$pergunta);
            $pergunta = str_replace("."," ",$pergunta);
            $pergunta = str_replace("?"," ",$pergunta);
            $pergunta = str_replace("!"," ",$pergunta);
            $pergunta = trim($pergunta);
            $resposta = "";
            $listaPerguntas = $this->ConversacaoModel->listarTodasPerguntas($usuario);
            //verifica se existe pergunta igual.
            for($i=0; $i< count($listaPerguntas);$i++){
                $temp = trim($listaPerguntas[$i]["descricao_ia_conversacao"]);
                if(strnatcasecmp($pergunta,$temp)==0){// se tiver pergunta igual
                    $listaRespostas = $this->ConversacaoModel->buscarRespostaPorCodigoUserTipo($usuario, $listaPerguntas[$i]["ia_assunto_ia_conversacao"]);
                    if($listaRespostas != null && count($listaRespostas) > 0){
                        // escolhe uma resposta aleatoria se tiver.
                        $aleatorio = rand(0,count($listaRespostas)-1);   
                        $resposta = $listaRespostas[$aleatorio]["descricao_ia_conversacao"];
                        //verifica se possui palavra reservada de consulta ao banco                      
                        $resposta = $this->palavraReservadaNaResposta($resposta, $pizzaria);
                        if($resposta == ""){
                            return "null";
                            break;
                        }
                        return $resposta;
                        break;
                    }
                }
            }
            if(strnatcasecmp($resposta,"")==0){
                $resposta = $this->palavraChaveNaPergunta($pergunta , $usuario);
                //verifica se possui palavra reservada de consulta ao banco
                $resposta = $this->palavraReservadaNaResposta($resposta, $pizzaria);
                return $resposta;
            }else{
                return $resposta;
            }
            // se não achou uma pergunta igual entao procura palavras chaves
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }
    public function palavraChaveNaPergunta($pergunta, $codigo){
        $this->load->model("ia/PalavraChaveModel");
        $resposta = "";
        $palavrasChave = $this->PalavraChaveModel->listarPalavraChaves($codigo);
        $perguntaQuebrada = explode(" ", $pergunta);
        for($i=0; $i< count($perguntaQuebrada);$i++){
            for($j=0; $j < count($palavrasChave);$j++){     
                if(strnatcasecmp($perguntaQuebrada[$i],$palavrasChave[$j]["nome_ia_palavras_chave"])==0 ){// se tiver pergunta igual
                    return $palavrasChave[$j]["resposta_ia_palavras_chave"];
                }
            }
        }
    }
    
    
    public function palavraReservadaNaResposta($resposta, $pizzaria){
        $this->load->model("ia/PalavraChaveModel");
        $palavrasChave = $this->PalavraChaveModel->listarBancos();
        $temp = str_replace(","," ",$resposta);
        $temp = str_replace("."," ",$temp);
        $respostaLista = explode(" ", $temp);
        for($i=0; $i< count($respostaLista);$i++){
            for($j=0; $j < count($palavrasChave);$j++){
                if(strnatcasecmp($respostaLista[$i],$palavrasChave[$j])==0){
                    
                    $texto = $this->buscarOpcoesBancos($palavrasChave[$j], $pizzaria);
                    $resposta = str_replace($palavrasChave[$j], $texto, $resposta);
                    return $resposta;
                }
            }
        }
        return $resposta;
    }
    
    public function buscarOpcoesBancos($bancoNome, $pizzaria){
         $this->load->model("ia/DadosTodosPizzaria");
         $texto = "";
        try{
            if(strnatcasecmp("@tamanhos",$bancoNome) == 0){
                $resp = $this->DadosTodosPizzaria->buscarTamanhos($pizzaria);
                for($i=0; $i < count($resp); $i++){
                    if($i == count($resp)-2){
                        $texto=$texto.$resp[$i]["descricao_tamanho_pizza"]." e ";
                    }else{
                        if($i == count($resp)-1){
                            $texto=$texto.$resp[$i]["descricao_tamanho_pizza"].".";
                        }else{
                            $texto=$texto.$resp[$i]["descricao_tamanho_pizza"].", ";
                        }
                    }
                    
                }
            }else if(strnatcasecmp("@sabores",$bancoNome) == 0){
                $resp = $this->DadosTodosPizzaria->buscarSaboresEmpresaAtivos($pizzaria);
                for($i=0; $i < count($resp); $i++){  
                    if($i == count($resp)-2){
                        $texto=$texto.$resp[$i]["descricao_sabor_pizza"]." e ";
                    }else{
                        if($i == count($resp)-1){
                            $texto=$texto.$resp[$i]["descricao_sabor_pizza"].".";
                        }else{
                            $texto=$texto.$resp[$i]["descricao_sabor_pizza"].", ";
                        }
                    }
                }
            }else if(strnatcasecmp("@extras",$bancoNome) == 0){
                $resp = $this->DadosTodosPizzaria->buscarTodosExtrasDaEmpresa($pizzaria);
                $temp =0;
                for($i=0; $i < count($resp); $i++){
                    if($temp == 0 || $temp != $resp[$i]["tipo_extra_pizza_extra_pizza"]){
                        $temp = $resp[$i]["tipo_extra_pizza_extra_pizza"];
                        $texto=$texto." ".$resp[$i]["descricao_tipo_extra_pizza"].": "; 
                    }
                    if($i == count($resp)-1){
                        $texto=$texto.strtolower($resp[$i]["descricao_extra_pizza"].".");
                    }else{
                        if(count($resp) != $i+1 && $temp != $resp[$i+1]["tipo_extra_pizza_extra_pizza"]){
                            $texto=$texto.strtolower($resp[$i]["descricao_extra_pizza"].".");
                        }else{
                            $texto=$texto.strtolower($resp[$i]["descricao_extra_pizza"].", ");
                        }
                    }
                }
            }else if(strnatcasecmp("@bebidas",$bancoNome) == 0){
                $resp = $this->DadosTodosPizzaria->buscarBebidas($pizzaria);
                for($i=0; $i < count($resp); $i++){
                    if($i == count($resp)-2){
                        $texto=$texto.$resp[$i]["descricao_bebida"]." e ";
                    }else{
                        if($i == count($resp)-1){
                            $texto=$texto.$resp[$i]["descricao_bebida"].".";
                        }else{
                            $texto=$texto.$resp[$i]["descricao_bebida"].", ";
                        }
                    }
                }
            }else if(strnatcasecmp("@tipo extra",$bancoNome) == 0){
                $resp = $this->DadosTodosPizzaria->buscarTipoExtras($pizzaria);
                for($i=0; $i < count($resp); $i++){
                    if($i == count($resp)-2){
                        $texto=$texto.$resp[$i]["descricao_tipo_extra_pizza"]." e ";
                    }else{
                        if($i == count($resp)-1){
                            $texto=$texto.$resp[$i]["descricao_tipo_extra_pizza"].".";
                        }else{
                            $texto=$texto.$resp[$i]["descricao_tipo_extra_pizza"].", ";
                        }
                    }
                }
            }
            return $texto;
            
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
}
