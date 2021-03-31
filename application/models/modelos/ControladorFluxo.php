<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorFluxo
 *
 * @author 033234581
 */
class ControladorFluxo extends CI_Model{
    public function validaFluxoAtual($blocoName, $fluxo){
        $valido = 0;
        $blocoAtual = $this->retornaNumeroBlocoPorName($blocoName, 0);
        
        for($i=0;$i < count($blocoAtual["ant"]); $i++){
            if($blocoAtual["ant"][$i] == $fluxo){
                $valido = 1;
            }
        }
        return $valido;
    }
    
    public function validaBloco($blocoName, $fluxo){
        $this->load->model("modelos/SetVariaveisChat");
        $valido = 0;
        $blocoAtual = $this->retornaNumeroBlocoPorName($blocoName, 0);
        //$blocoAnterior = $this->retornaNumeroBlocoPorName("",$fluxo);
        
        for($i=0;$i < count($blocoAtual["ant"]); $i++){
            if($blocoAtual["ant"][$i] == $fluxo){
                $valido = 1;
            }
        }
        
        if($valido == 1){
            return  $this->SetVariaveisChat->mudaValorVariavelUtilitario($valido, "PermissaoFluxo");
        }else{
            if($fluxo == 119 || $fluxo == 41){// se for 119 pedido ja ta finalizado e 41 tambem
               $dados = array('messages' => array(
                        array(
                            "attachment" => array(
                                "type" => "template",
                                "payload" => array(
                                    "template_type" => "button",
                                    'text' => "Seu pedido já foi finalizado. Para fazer um novo pedido clique abaixo 👇",
                                    "buttons"=>array(
                                        array(
                                            "type"=> "show_block",
                                            "block_name"=> "começar",
                                            "title"=> "Fazer novo pedido"
                                        )
                                    )
                                    
                                ) 
                            )
                        )
                    )
                );
                $json_str = json_encode($dados);
                return $json_str;
            }else{
                return $this->ControladorFluxo->voltarParaBlocoFluxoAtual($blocoName, $fluxo);
            }
        }
    }
    
    public function voltarParaBlocoFluxoAtual($blocoName, $fluxoAtual){
        $this->load->model("modelos/UtilitarioGeradorDeJSON");
        $atual = $this->retornaNumeroBlocoPorName("", $fluxoAtual);     
        $rapida[] = array("titulo" => "Cancelar pedido","bloco" => "Cancela Pedido");
        $rapida[] = array("titulo" => "Continuar","bloco" => $atual["nome"]);
        $dados= $this->UtilitarioGeradorDeJSON->gerarRespostaRapida("Desculpa, eu não consegui entender o que você quis dizer.", $rapida);

        $json_str = json_encode($dados);
        return $json_str;
        
    }

    public function retornaNumeroBlocoPorName($ultimoBloco, $numrBloco){

        if(strcmp("Default answer", $ultimoBloco) == 0 || $numrBloco == 999){
            $map = array();$antecessor = array(); $proximo = array();                   
            $antecessor[0]=0;//welcome
            $antecessor[1]=1;//começar
            $antecessor[2]=41;//Finalizando Pedido
            $antecessor[3]=43;//Cancela Pedido
            $antecessor[4]=119;//Finalizando Pedido UP
            $antecessor[5]=999;//Default Answer
            $proximo[0]=0;
            $proximo[1]=1;
            $proximo[2]=999;
            
            $map["ant"]=$antecessor;
            $map["atual"]=999;
            $map["nome"]="Default Answer";
            $map["prox"]=$proximo;
            return $map;
        } 
        
        // fluxo quero pedir pizza
        if(strcmp("começar", $ultimoBloco) == 0 || $numrBloco == 1){
            $map = array();$antecessor = array(); $proximo = array();                   
            $antecessor[0]=0;           
            $proximo[0]=26;//novo
            $proximo[1]=100;
            $proximo[2]=999;
            
            $map["ant"]=$antecessor;
            $map["atual"]=1;
            $map["nome"]="começar";
            $map["prox"]=$proximo;
            return $map;
        }
        
        if(strcmp("Fluxo 02", $ultimoBloco) == 0 || $numrBloco == 2){           
            $map = array(); $antecessor = array(); $proximo = array();   
            $antecessor[0]=3;
            $antecessor[1]=23;
            $antecessor[2]=29;// novo
            
            $proximo[0]=3;
            
            $map["ant"]=$antecessor;
            $map["atual"]=2;
            $map["nome"]="Fluxo 02";
            $map["prox"]=$proximo;
            return $map;
        }
        
        if(strcmp("Quero pedir pizza!", $ultimoBloco) == 0 || $numrBloco == 3){
            $map = array();$antecessor = array(); $proximo = array();                   
            $antecessor[0]=2; 
            $antecessor[1]=3; 
            $proximo[0]=3;
            $proximo[1]=4;
            
            $map["ant"]=$antecessor;
            $map["atual"]=3;
            $map["nome"]="Quero pedir pizza!";
            $map["prox"]=$proximo;
            return $map;
        }
        
        if(strcmp("Fluxo 04", $ultimoBloco) == 0 || $numrBloco == 4){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=3;
            $proximo[0]=5;
            
            $map["ant"]=$antecessor;
            $map["atual"]=4;
            $map["nome"]="Fluxo 04";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Recebe Tamanho", $ultimoBloco) == 0 || $numrBloco == 5){
            $map = array();$antecessor = array(); $proximo = array();                   
            $antecessor[0]=4;           
            $proximo[0]=6;
            
            $map["ant"]=$antecessor;
            $map["atual"]=5;
            $map["nome"]="Recebe Tamanho";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 06", $ultimoBloco) == 0 || $numrBloco == 6){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=5;
            $antecessor[1]=7;
            $antecessor[2]=9;
            $antecessor[3]=11;
            $proximo[0]=7;
            
            $map["ant"]=$antecessor;
            $map["atual"]=6;
            $map["nome"]="Fluxo 06";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Escolhe Sabores", $ultimoBloco) == 0 || $numrBloco == 7){
            $map = array();$antecessor = array(); $proximo = array();                   
            $antecessor[0]=6; 
            $antecessor[1]=7;
            $proximo[0]=8;
            $proximo[1]=10;
            
            $map["ant"]=$antecessor;
            $map["atual"]=7;
            $map["nome"]="Escolhe Sabores";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 08", $ultimoBloco) == 0 || $numrBloco == 8){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=7;
            $proximo[0]=9;
            
            $map["ant"]=$antecessor;
            $map["atual"]=8;
            $map["nome"]="Fluxo 08";
            $map["prox"]=$proximo;
            return $map;
        }
        
        if(strcmp("Verifica Mais Sabores", $ultimoBloco) == 0 || $numrBloco == 9){
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=8;
            $proximo[0]=10;
            
            $map["ant"]=$antecessor;
            $map["atual"]=9;
            $map["nome"]="Verifica Mais Sabores";
            $map["prox"]=$proximo;
            return $map;
        }
        
        if(strcmp("Fluxo 10", $ultimoBloco) == 0 || $numrBloco == 10){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=9;
            $antecessor[1]=15;
            $antecessor[2]=7;
            $proximo[0]=11;
            
            $map["ant"]=$antecessor;
            $map["atual"]=10;
            $map["nome"]="Fluxo 10";
            $map["prox"]=$proximo;
            return $map;
        }
        
        if(strcmp("Escolhe Extra", $ultimoBloco) == 0 || $numrBloco == 11){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=10;
            $proximo[0]=12;
            $proximo[1]=14;
            $proximo[2]=16;
            $proximo[3]=6;
            
            $map["ant"]=$antecessor;
            $map["atual"]=11;
            $map["nome"]="Escolhe Extra";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 12", $ultimoBloco) == 0 || $numrBloco == 12){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=11;
            $antecessor[1]=13;
            $proximo[0]=13;
            
            $map["ant"]=$antecessor;
            $map["atual"]=12;
            $map["nome"]="Fluxo 12";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Mais Extra", $ultimoBloco) == 0 || $numrBloco == 13){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=12;
            
            $proximo[0]=12;
            $proximo[1]=14;
            
            $map["ant"]=$antecessor;
            $map["atual"]=13;
            $map["nome"]="Mais Extra";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 14", $ultimoBloco) == 0 || $numrBloco == 14){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=11;
            $antecessor[1]=13;
            $proximo[0]=15;
            
            $map["ant"]=$antecessor;
            $map["atual"]=14;
            $map["nome"]="Fluxo 14";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Recebe Adicional", $ultimoBloco) == 0 || $numrBloco == 15){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=14;
            $proximo[0]=10;
            
            $map["ant"]=$antecessor;
            $map["atual"]=15;
            $map["nome"]="Recebe Adicional";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 16", $ultimoBloco) == 0 || $numrBloco == 16){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=11;
            $antecessor[1]=15;
            $antecessor[2]=19;
            $antecessor[3]=21;
            $antecessor[4]=13;
            $proximo[0]=17;
            
            $map["ant"]=$antecessor;
            $map["atual"]=16;
            $map["nome"]="Fluxo 16";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Mais Opcoes", $ultimoBloco) == 0 || $numrBloco == 17){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=16;
            $proximo[0]=18;
            $proximo[1]=22;
            $proximo[2]=24;
            
            $map["ant"]=$antecessor;
            $map["atual"]=17;
            $map["nome"]="Mais Opcoes";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 18", $ultimoBloco) == 0 || $numrBloco == 18){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=17;
            $antecessor[1]=19;
            $proximo[0]=19;
            
            $map["ant"]=$antecessor;
            $map["atual"]=18;
            $map["nome"]="Fluxo 18";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Escolhe Bebida", $ultimoBloco) == 0 || $numrBloco == 19){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=18;
            $proximo[0]=18;
            $proximo[1]=16;
            $proximo[2]=20;
            
            $map["ant"]=$antecessor;
            $map["atual"]=19;
            $map["nome"]="Escolhe Bebida";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 20", $ultimoBloco) == 0 || $numrBloco == 20){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=19;
            $proximo[0]=21;
            
            $map["ant"]=$antecessor;
            $map["atual"]=20;
            $map["nome"]="Fluxo 20";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Recebe Bebida", $ultimoBloco) == 0 || $numrBloco == 21){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=20;
            $proximo[0]=16;
            
            $map["ant"]=$antecessor;
            $map["atual"]=21;
            $map["nome"]="Recebe Bebida";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 22", $ultimoBloco) == 0 || $numrBloco == 22){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=17;
            $proximo[0]=23;
            
            $map["ant"]=$antecessor;
            $map["atual"]=22;
            $map["nome"]="Fluxo 22";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Compra mais Pizza", $ultimoBloco) == 0 || $numrBloco == 23){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=22;
            $proximo[0]=02;
            
            $map["ant"]=$antecessor;
            $map["atual"]=23;
            $map["nome"]="Compra mais Pizza";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 24", $ultimoBloco) == 0 || $numrBloco == 24){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=17;
            $proximo[0]=25;
            
            $map["ant"]=$antecessor;
            $map["atual"]=24;
            $map["nome"]="Fluxo 24";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Observacão Cliente", $ultimoBloco) == 0 || $numrBloco == 25){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=24;
            $proximo[0]=26;// velhor retirar
            $proximo[1]=46;//novo
            
            $map["ant"]=$antecessor;
            $map["atual"]=25;
            $map["nome"]="Observacão Cliente";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 26", $ultimoBloco) == 0 || $numrBloco == 26){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=1;//novo
            $antecessor[1]=27;
            //$antecessor[2]=27;
            $proximo[0]=27;
            
            $map["ant"]=$antecessor;
            $map["atual"]=26;
            $map["nome"]="Fluxo 26";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Solicita Endereço", $ultimoBloco) == 0 || $numrBloco == 27){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=26;
            $proximo[0]=26;
            $proximo[1]=28;
            
            $map["ant"]=$antecessor;
            $map["atual"]=27;
            $map["nome"]="Solicita Endereço";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 28", $ultimoBloco) == 0 || $numrBloco == 28){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=27;
            $proximo[0]=29;
            
            $map["ant"]=$antecessor;
            $map["atual"]=28;
            $map["nome"]="Fluxo 28";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Recebe Endereco", $ultimoBloco) == 0 || $numrBloco == 29){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=28;
            $proximo[0]=2;
            $proximo[1]=42;
            
            $map["ant"]=$antecessor;
            $map["atual"]=29;
            $map["nome"]="Recebe Endereco";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 30", $ultimoBloco) == 0 || $numrBloco == 30){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=47;
            $antecessor[1]=29;
            $antecessor[2]=31;
            $proximo[0]=31;
            
            $map["ant"]=$antecessor;
            $map["atual"]=30;
            $map["nome"]="Fluxo 30";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Valida Telefone", $ultimoBloco) == 0 || $numrBloco == 31){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=30;
            $proximo[0]=31;
            $proximo[1]=32;
            
            $map["ant"]=$antecessor;
            $map["atual"]=31;
            $map["nome"]="Valida Telefone";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 32", $ultimoBloco) == 0 || $numrBloco == 32){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=31;
            $proximo[0]=33;
            
            $map["ant"]=$antecessor;
            $map["atual"]=32;
            $map["nome"]="Fluxo 32";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Custo Total", $ultimoBloco) == 0 || $numrBloco == 33){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=32;
            $proximo[0]=34;
            
            $map["ant"]=$antecessor;
            $map["atual"]=33;
            $map["nome"]="Custo Total";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 34", $ultimoBloco) == 0 || $numrBloco == 34){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=33;
            $proximo[0]=35;
            
            $map["ant"]=$antecessor;
            $map["atual"]=34;
            $map["nome"]="Fluxo 34";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Forma Pagamento", $ultimoBloco) == 0 || $numrBloco == 35){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=34;
            $proximo[0]=36;
            $proximo[1]=40;
            
            $map["ant"]=$antecessor;
            $map["atual"]=35;
            $map["nome"]="Forma Pagamento";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 36", $ultimoBloco) == 0 || $numrBloco == 36){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=35;
            $proximo[0]=37;
            
            $map["ant"]=$antecessor;
            $map["atual"]=36;
            $map["nome"]="Fluxo 36";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Pergunta se quer troco", $ultimoBloco) == 0 || $numrBloco == 37){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=36;
            $proximo[0]=38;
            $proximo[1]=40;
            
            $map["ant"]=$antecessor;
            $map["atual"]=37;
            $map["nome"]="Pergunta se quer troco";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 38", $ultimoBloco) == 0 || $numrBloco == 38){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=37;
            $proximo[0]=39;
            
            $map["ant"]=$antecessor;
            $map["atual"]=38;
            $map["nome"]="Fluxo 38";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Recebe valor troco", $ultimoBloco) == 0 || $numrBloco == 39){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=38;
            $proximo[0]=40;
            
            $map["ant"]=$antecessor;
            $map["atual"]=39;
            $map["nome"]="Recebe valor troco";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 40", $ultimoBloco) == 0 || $numrBloco == 40){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=35;
            $antecessor[1]=37;
            $antecessor[2]=39;
            $proximo[0]=41;
            
            $map["ant"]=$antecessor;
            $map["atual"]=40;
            $map["nome"]="Fluxo 40";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Finalizando Pedido", $ultimoBloco) == 0 || $numrBloco == 41){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=40;
            $proximo[0]=1;
            $proximo[1]=999;
            
            $map["ant"]=$antecessor;
            $map["atual"]=41;
            $map["nome"]="Finalizando Pedido";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 42", $ultimoBloco) == 0 || $numrBloco == 42){           
            $map = array(); $antecessor = array(); $proximo = array();    
            $i=2;
            for($i=2;$i<42;$i++){
                $antecessor[$i-2]=$i;
            }
            $a=100;
            for($j=$i-2;$j<120;$j++){
                $antecessor[$j]=$a;
                $a++;
            }
            $proximo[0]=43;
            
            $map["ant"]=$antecessor;
            $map["atual"]=42;
            $map["nome"]="Fluxo 42";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Cancela Pedido", $ultimoBloco) == 0 || $numrBloco == 43){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=42;
            $proximo[0]=1;
            $proximo[1]=999;
            
            $map["ant"]=$antecessor;
            $map["atual"]=43;
            $map["nome"]="Cancela Pedido";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 46", $ultimoBloco) == 0 || $numrBloco == 46){           
            $map = array(); $antecessor = array(); $proximo = array();    
            $antecessor[0]=25;// novo
            $proximo[0]=47;
            
            $map["ant"]=$antecessor;
            $map["atual"]=46;
            $map["nome"]="Fluxo 46";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Solicita Dados Local", $ultimoBloco) == 0 || $numrBloco == 47){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=46;
            $proximo[0]=30;
            
            $map["ant"]=$antecessor;
            $map["atual"]=47;
            $map["nome"]="Solicita Dados Local";
            $map["prox"]=$proximo;
            return $map;
        }
        
        
        
        // fluxo ultimo pedido        
        if(strcmp("Fluxo 100", $ultimoBloco) == 0 || $numrBloco == 100){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=1;
            $proximo[0]=101;
            
            $map["ant"]=$antecessor;
            $map["atual"]=100;
            $map["nome"]="Fluxo 100";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Quero repetir meu último pedido!", $ultimoBloco) == 0 || $numrBloco == 101){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=1;
            $proximo[0]=102;
            
            $map["ant"]=$antecessor;
            $map["atual"]=101;
            $map["nome"]="Quero repetir meu último pedido!";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 102", $ultimoBloco) == 0 || $numrBloco == 102){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=101;
            $proximo[0]=103;
            
            $map["ant"]=$antecessor;
            $map["atual"]=102;
            $map["nome"]="Fluxo 102";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Observacão Cliente UP", $ultimoBloco) == 0 || $numrBloco == 103){
            $map = array(); $antecessor = array(); $proximo = array();
            $antecessor[0]=102;
            $proximo[0]=104;
            
            $map["ant"]=$antecessor;
            $map["atual"]=103;
            $map["nome"]="Observacão Cliente UP";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 104", $ultimoBloco) == 0 || $numrBloco == 104){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=103;
            $antecessor[1]=105;
            $proximo[0]=105;
            
            $map["ant"]=$antecessor;
            $map["atual"]=104;
            $map["nome"]="Fluxo 102";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Solicita Endereço UP",$ultimoBloco) == 0 || $numrBloco == 105){
            $map = array(); $antecessor = array(); $proximo = array();
            $antecessor[0]=104;
            $proximo[0]=104;
            $proximo[1]=106;
            
            $map["ant"]=$antecessor;
            $map["atual"]=105;
            $map["nome"]="Solicita Endereço UP";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 106", $ultimoBloco) == 0 || $numrBloco == 106){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=105;
            $proximo[0]=107;
            
            $map["ant"]=$antecessor;
            $map["atual"]=106;
            $map["nome"]="Fluxo 106";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Recebe Ender UP" ,$ultimoBloco) == 0 || $numrBloco == 107){
            $map = array(); $antecessor = array(); $proximo = array();
            $antecessor[0]=106;
            $proximo[1]=108;
            $proximo[2]=42;
            
            $map["ant"]=$antecessor;
            $map["atual"]=107;
            $map["nome"]="Recebe Ender UP";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 108", $ultimoBloco) == 0 || $numrBloco == 108){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=107;
            $proximo[0]=109;
            
            $map["ant"]=$antecessor;
            $map["atual"]=108;
            $map["nome"]="Fluxo 108";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Valida Telefone UP", $ultimoBloco) == 0 || $numrBloco == 109){
            $map = array(); $antecessor = array(); $proximo = array();
            $antecessor[0]=108;
            $proximo[0]=110;
            
            $map["ant"]=$antecessor;
            $map["atual"]=109;
            $map["nome"]="Valida Telefone UP";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 110", $ultimoBloco) == 0 || $numrBloco == 110){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=109;
            $proximo[0]=111;
            
            $map["ant"]=$antecessor;
            $map["atual"]=110;
            $map["nome"]="Fluxo 110";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Custo Total UP", $ultimoBloco) == 0 || $numrBloco == 111){
            $map = array(); $antecessor = array(); $proximo = array();
            $antecessor[0]=110;
            $proximo[0]=112;
            
            $map["ant"]=$antecessor;
            $map["atual"]=111;
            $map["nome"]="Custo Total UP";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 112", $ultimoBloco) == 0 || $numrBloco == 112){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=111;
            $proximo[0]=113;
            
            $map["ant"]=$antecessor;
            $map["atual"]=112;
            $map["nome"]="Fluxo 112";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Forma Pagamento UP", $ultimoBloco) == 0 || $numrBloco == 113){
            $map = array(); $antecessor = array(); $proximo = array();
            $antecessor[0]=112;
            $proximo[0]=114;
            $proximo[1]=118;
            
            $map["ant"]=$antecessor;
            $map["atual"]=113;
            $map["nome"]="Forma Pagamento UP";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Fluxo 114", $ultimoBloco) == 0 || $numrBloco == 114){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=113;
            $proximo[0]=115;
            
            $map["ant"]=$antecessor;
            $map["atual"]=114;
            $map["nome"]="Fluxo 114";
            $map["prox"]=$proximo;
            return $map;
        }
        
        if(strcmp("Troco sim ou nao", $ultimoBloco) == 0 || $numrBloco == 115){
            $map = array(); $antecessor = array(); $proximo = array();
            $antecessor[0]=114;
            $proximo[0]=116;
            $proximo[1]=118;
            
            $map["ant"]=$antecessor;
            $map["atual"]=115;
            $map["nome"]="Troco sim ou nao";
            $map["prox"]=$proximo;
            return $map;
        }
        
        if(strcmp("Fluxo 116", $ultimoBloco) == 0 || $numrBloco == 116){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=115;
            $proximo[0]=117;
            
            $map["ant"]=$antecessor;
            $map["atual"]=116;
            $map["nome"]="Fluxo 116";
            $map["prox"]=$proximo;
            return $map;
        }
        
        if(strcmp("Solicita Troco UP", $ultimoBloco) == 0 || $numrBloco == 117){
            $map = array(); $antecessor = array(); $proximo = array();
            $antecessor[0]=116;
            $proximo[0]=118;
            
            $map["ant"]=$antecessor;
            $map["atual"]=117;
            $map["nome"]="Solicita Troco UP";
            $map["prox"]=$proximo;
            return $map;
        }
        
        if(strcmp("Fluxo 118", $ultimoBloco) == 0 || $numrBloco == 118){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=113;
            $antecessor[1]=115;
            $antecessor[2]=117;
            $proximo[0]=119;
            
            $map["ant"]=$antecessor;
            $map["atual"]=118;
            $map["nome"]="Fluxo 118";
            $map["prox"]=$proximo;
            return $map;
        }
        
        if(strcmp("Finalizando Pedido UP", $ultimoBloco) == 0 || $numrBloco == 119){
            $map = array(); $antecessor = array(); $proximo = array();
            $antecessor[0]=118;
            $proximo[0]=0;
            $proximo[1]=999;
            
            $map["ant"]=$antecessor;
            $map["atual"]=119;
            $map["nome"]="Finalizando Pedido UP";
            $map["prox"]=$proximo;
            return $map;
        }
        
       /* if(strcmp("Fluxo 120", $ultimoBloco) == 0 || $numrBloco == 120){           
            $map = array(); $antecessor = array(); $proximo = array();    
            $antecessor[0]=105;
            $proximo[0]=121;
            
            $map["ant"]=$antecessor;
            $map["atual"]=120;
            $map["nome"]="Fluxo 120";
            $map["prox"]=$proximo;
            return $map;
        }
        if(strcmp("Complementa Ender UP", $ultimoBloco) == 0 || $numrBloco == 121){           
            $map = array(); $antecessor = array(); $proximo = array();         
            $antecessor[0]=120;
            $proximo[0]=108;
            
            $map["ant"]=$antecessor;
            $map["atual"]=121;
            $map["nome"]="Complementa Ender UP";
            $map["prox"]=$proximo;
            return $map;
        }*/
 // fim do mapeamento do ultimo pedido 
    }
}
