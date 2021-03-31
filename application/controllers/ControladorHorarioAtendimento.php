<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorHorarioAtendimento
 *
 * @author Alexandre
 */
class ControladorHorarioAtendimento extends CI_Controller{
    
    //put your code here
    public function verificaUsuarioLogado(){      
        $this->load->helper(array("currency"));
        $empresaLogada = $this->session->userdata("empresa_logada");
        if($empresaLogada == null){
            $this->session->unset_userdata("empresa_logada");
            $this->session->set_flashdata("sucess" , "Deslogado com sucesso");
            redirect('/');
        }
    }
    
    public function manterHorarioAtendimento(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/HorariosEmpresa");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $horario = $this->HorariosEmpresa->consultaHorarioAtendimento($empresaLogada["codigo_pizzaria"]);
            $remov = array();
            $todos = array();
            for($i=0; $i<count($horario); $i++){
                $fim = date_format(date_create($horario[$i]["fim_horario_atendimento"]), 'H:i:s');
                $temp =  date_format(date_create("23:59:59"), 'H:i:s');
                if($fim == $temp){
                    for($a=0; $a<count($horario); $a++){
                        $ini = date_format(date_create($horario[$a]["inicio_horario_atendimento"]), 'H:i:s');
                        $temp2 =  date_format(date_create("00:00:01"), 'H:i:s');
                        if($temp2 == $ini){
                            if($horario[$i]["dia_semana_horario_atendimento"] == 7 && $horario[$a]["dia_semana_horario_atendimento"] == 1){                           
                                $horario[$i]["fim_horario_atendimento"] = $horario[$a]["fim_horario_atendimento"];
                                $remov[]=$a;
                            }else{
                                if($horario[$a]["dia_semana_horario_atendimento"] == $horario[$i]["dia_semana_horario_atendimento"]+1){
                                    $horario[$i]["fim_horario_atendimento"] = $horario[$a]["fim_horario_atendimento"];
                                    $remov[]=$a;
                                }
                            }
                        }
                    }
                }

            }
            if(count($remov) > 0){
                for($i=0; $i<count($horario); $i++){
                    for($a=0; $a<count($remov); $a++){
                        if($remov[$a] != $i){// adiciona só os que nao tao marcados para remover
                            $todos[] = $horario[$i];
                            break;
                        }               
                    }
                }
            }else{
                $todos = $horario;
            }


            for($i=0; $i<count($todos); $i++){
                $todos[$i]["dia_semana_horario_atendimento"] = $this->converteData($todos[$i]["dia_semana_horario_atendimento"]);
            }
            $dados = array("horario" => $todos);
            $this->load->view("pizzaria/ViewManterHorarioAtendimento.php",$dados); 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function buscarHorarioAtendimentoPorID(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/HorariosEmpresa");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_horario_atendimento"};
            $hora = $this->HorariosEmpresa->consultaHorarioAtendimentoPorCodigo($codigo, $empresaLogada["codigo_pizzaria"]); 
            $horario = $this->HorariosEmpresa->consultaHorarioAtendimento($empresaLogada["codigo_pizzaria"]);

            $fim = date_format(date_create($hora["fim_horario_atendimento"]), 'H:i:s');
            $temp =  date_format(date_create("23:59:59"), 'H:i:s');
            if($fim == $temp){
                    for($a=0; $a<count($horario); $a++){
                        $ini = date_format(date_create($horario[$a]["inicio_horario_atendimento"]), 'H:i:s');
                        $temp2 =  date_format(date_create("00:00:01"), 'H:i:s');
                        if($temp2 == $ini){
                            if($hora["dia_semana_horario_atendimento"] == 7 && $horario[$a]["dia_semana_horario_atendimento"] == 1){                           
                                $hora["fim_horario_atendimento"] = $horario[$a]["fim_horario_atendimento"];
                            }else{
                                if($horario[$a]["dia_semana_horario_atendimento"] == $hora["dia_semana_horario_atendimento"]+1){
                                    $hora["fim_horario_atendimento"] = $horario[$a]["fim_horario_atendimento"];
                                }
                            }
                        }
                    }
            }
            $hora["dia_semana_horario_atendimento"] = $this->converteData($hora["dia_semana_horario_atendimento"]);
            //$temp =  date_format(date_create($hora["fim_horario_atendimento"]), 'H:i:s');
            $hora["inicio_horario_atendimento"] = date("Y-m-d")."T".date_format(date_create($hora["inicio_horario_atendimento"]), 'H:i');
            $hora["fim_horario_atendimento"]= date("Y-m-d")."T".date_format(date_create($hora["fim_horario_atendimento"]), 'H:i');


            $json_str = json_encode($hora);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function confirmarRemoverHorarioAtendimento(){
        $this->verificaUsuarioLogado();       
        $this->load->model("pizzaria/HorariosEmpresa");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_horario_atendimento"};
            $codigo1 = 0;
            $horario = $this->HorariosEmpresa->consultaHorarioAtendimento($empresaLogada["codigo_pizzaria"]);
            for($i=0; $i<count($horario); $i++){
                $ini = date_format(date_create($horario[$i]["inicio_horario_atendimento"]), 'H:i:s');
                $fim = date_format(date_create($horario[$i]["fim_horario_atendimento"]), 'H:i:s');
                $temp =  date_format(date_create("23:59:59"), 'H:i:s');
                if($horario[$i]["codigo_horario_atendimento"] == $codigo){
                    if($fim == $temp){
                        for($a=0; $a<count($horario); $a++){//procurando o segundo
                            $iniOut = date_format(date_create($horario[$a]["inicio_horario_atendimento"]), 'H:i:s');
                            $fimOut = date_format(date_create($horario[$a]["fim_horario_atendimento"]), 'H:i:s');
                            if($iniOut == date_format(date_create("00:00:01"), 'H:i:s') )
                                if($horario[$i]["dia_semana_horario_atendimento"] == 7 && $horario[$a]["dia_semana_horario_atendimento"] == 1){                           
                                    $codigo1 = $horario[$a]["codigo_horario_atendimento"];
                                }else{ if($horario[$a]["dia_semana_horario_atendimento"] == $horario[$i]["dia_semana_horario_atendimento"]+1){
                                    $codigo1 = $horario[$a]["codigo_horario_atendimento"];
                                }
                            }
                        }
                        if( !($this->HorariosEmpresa->removerHorarioAtendimento($codigo, $empresaLogada["codigo_pizzaria"] )) ){
                            throw new Exception("Erro ao excluir dados.");
                        } 
                        if($codigo1 != 0 && !($this->HorariosEmpresa->removerHorarioAtendimento($codigo1, $empresaLogada["codigo_pizzaria"] )) ){
                            throw new Exception("Erro ao excluir dados.");
                        } 
                    }else{
                        if( !($this->HorariosEmpresa->removerHorarioAtendimento($codigo, $empresaLogada["codigo_pizzaria"] )) ){
                            throw new Exception("Erro ao excluir dados.");
                        } 
                    }
                    break;
                }
            }
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function alterarHorarioAtendimento(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/HorariosEmpresa");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_horario_atendimento"};
           // $ini = date_format(date_create($json->{"inicio_horario_atendimento"}), 'H:i:s');
            //$fim = date_format(date_create($json->{"fim_horario_atendimento"}), 'H:i:s');
            // devido php muito fraco para data. Converto uma string em mktime e depois para date.
            $temp = explode(" ",$json->{"inicio_horario_atendimento"});
            $temp = explode(":",$temp[1]);
            $ini = date("H:i:s", mktime($temp[0], $temp[1], $temp[2]));          
            $tp = explode(" ",$json->{"fim_horario_atendimento"});
            $tp = explode(":",$tp[1]);
            $fim = date("H:i:s", mktime($tp[0], $tp[1], $tp[2]));
            

            $todosHorario = $this->HorariosEmpresa->consultaHorarioAtendimento($empresaLogada["codigo_pizzaria"]);
            $horarioDia = $this->HorariosEmpresa->consultaHorarioAtendimentoPorCodigo($codigo, $empresaLogada["codigo_pizzaria"]);
            // verificar choque de horarios    
            for($i=0; $i<count($todosHorario); $i++){
                if($todosHorario[$i]["dia_semana_horario_atendimento"] == $horarioDia["dia_semana_horario_atendimento"] && 
                        $todosHorario[$i]["codigo_horario_atendimento"] != $codigo){
                    //throw new Exception("Erro ao incluir dados. Data já existente.");
                    // primeira verificação verificar se hora inicial ou final esta dentro do intervalo ja cadastrado e se são iguais
                    if(($todosHorario[$i]["inicio_horario_atendimento"] <= $ini && $todosHorario[$i]["fim_horario_atendimento"] >= $ini)
                            || ($todosHorario[$i]["inicio_horario_atendimento"] <= $fim && $todosHorario[$i]["fim_horario_atendimento"] >= $fim)){
                        throw new Exception("Erro ao incluir dados. Data já existente.");
                    }
                    // verificar se o novo horario cobre o outro
                    if(($todosHorario[$i]["inicio_horario_atendimento"] >= $ini && $todosHorario[$i]["fim_horario_atendimento"] <= $fim)){
                        throw new Exception("Erro ao incluir dados. Data já existente.");
                    }
                }
            }
            
            
            
            $codigo1 = 0;
            $dia=0;
            //procura se existe duas linhas no banco para esse horario
            $horario = $this->HorariosEmpresa->consultaHorarioAtendimento($empresaLogada["codigo_pizzaria"]);
            for($i=0; $i<count($horario); $i++){
                if($horario[$i]["codigo_horario_atendimento"] == $codigo){
                    for($a=0; $a<count($horario); $a++){//procurando o segundo
                        $iniOut = date_format(date_create($horario[$a]["inicio_horario_atendimento"]), 'H:i:s');
                        $fimOut = date_format(date_create($horario[$a]["fim_horario_atendimento"]), 'H:i:s');
                        if($iniOut == date_format(date_create("00:00:01"), 'H:i:s') )
                            if($horario[$i]["dia_semana_horario_atendimento"] == 7 && $horario[$a]["dia_semana_horario_atendimento"] == 1){                           
                                $codigo1 = $horario[$a]["codigo_horario_atendimento"];
                            }else{ if($horario[$a]["dia_semana_horario_atendimento"] == $horario[$i]["dia_semana_horario_atendimento"]+1){
                                $codigo1 = $horario[$a]["codigo_horario_atendimento"];
                            }
                        }
                    }
                    $dia = $horario[$i]["dia_semana_horario_atendimento"];
                    break;
                }
            }
            if($codigo1 != 0){//se possui duas linhas
                if($fim < $ini){//verifico se ainda vai ficar as duas linhas se sim
                    $HorarioNovo1 = array(
                        "codigo_horario_atendimento" => $codigo,
                        "inicio_horario_atendimento" => $ini,
                        "pizzaria_horario_atendimento" => $empresaLogada["codigo_pizzaria"]     
                    );

                    $HorarioNovo2 = array(
                        "codigo_horario_atendimento" => $codigo1,
                        "fim_horario_atendimento" => $fim,
                        "pizzaria_horario_atendimento" => $empresaLogada["codigo_pizzaria"]     
                    );
                    if( !(($this->HorariosEmpresa->alteraHorarioAtendimento($HorarioNovo1)) && ($this->HorariosEmpresa->alteraHorarioAtendimento($HorarioNovo2))) ){
                        throw new Exception("Erro ao alterar dados.");
                    }
                }else{
                    // se agora deve possuir 1 linhas entao removo a segunda e altero a primeira
                    if($codigo1 != 0 && !($this->HorariosEmpresa->removerHorarioAtendimento($codigo1, $empresaLogada["codigo_pizzaria"] )) ){
                            throw new Exception("Erro ao excluir dados.");
                    } 
                    $HorarioNovo = array(
                        "codigo_horario_atendimento" => $codigo,
                        "inicio_horario_atendimento" => $ini,
                        "fim_horario_atendimento" => $fim,
                        "pizzaria_horario_atendimento" => $empresaLogada["codigo_pizzaria"]     
                        );
                    if(!($this->HorariosEmpresa->alteraHorarioAtendimento($HorarioNovo))){
                        throw new Exception("Erro ao alterar dados.");
                    } 
                }
            }else{
                if($fim < $ini){// possue 1 linha entao altero a primeira e crio uma nova como segunda
                    // altera o atual
                    $fimDia=date_format(date_create("23:59:59"), 'H:i:s');
                    $Horario1 = array(
                        "codigo_horario_atendimento" => $codigo,
                        "inicio_horario_atendimento" => $ini,
                        "fim_horario_atendimento" => $fimDia,
                        "pizzaria_horario_atendimento" => $empresaLogada["codigo_pizzaria"]     
                        );
                    if(!($this->HorariosEmpresa->alteraHorarioAtendimento($Horario1))){
                        throw new Exception("Erro ao alterar dados.");
                    }
                    // cria uma nova linha no banco
                    $iniDia=date_format(date_create("00:00:01"), 'H:i:s');

                    if( $dia == 7){
                        $dia = 1;
                    }else{
                        $dia = $dia+1;
                    }
                    $Horario2 = array(
                        "dia_semana_horario_atendimento" => $dia,
                        "inicio_horario_atendimento" => $iniDia,
                        "fim_horario_atendimento" => $fim,
                        "pizzaria_horario_atendimento" => $empresaLogada["codigo_pizzaria"]     
                        );
                    $hora = $this->HorariosEmpresa->inserirHorarioAtendimentoRetornandoo($Horario2);
                }else{// se era 1 linha e continua uma linha só altero
                    $HorarioNovo = array(
                        "codigo_horario_atendimento" => $codigo,
                        "inicio_horario_atendimento" => $ini,
                        "fim_horario_atendimento" => $fim,
                        "pizzaria_horario_atendimento" => $empresaLogada["codigo_pizzaria"]     
                        );
                    if(!($this->HorariosEmpresa->alteraHorarioAtendimento($HorarioNovo))){
                        throw new Exception("Erro ao alterar dados.");
                    } 
                }
            }  
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }

    public function incluirHorarioAtendimento(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/HorariosEmpresa");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $todosHorario = $this->HorariosEmpresa->consultaHorarioAtendimento($empresaLogada["codigo_pizzaria"]);
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $ini = date_format(date_create($json->{"inicio_horario_atendimento"}), 'H:i:s');
            $fim = date_format(date_create($json->{"fim_horario_atendimento"}), 'H:i:s');
            // verificar choque de horarios    
            for($i=0; $i<count($todosHorario); $i++){
                if($todosHorario[$i]["dia_semana_horario_atendimento"] == $json->{"dia_semana_horario_atendimento"}){
                    //throw new Exception("Erro ao incluir dados. Data já existente.");
                    // primeira verificação verificar se hora inicial ou final esta dentro do intervalo ja cadastrado e se são iguais
                    if(($todosHorario[$i]["inicio_horario_atendimento"] <= $ini && $todosHorario[$i]["fim_horario_atendimento"] >= $ini)
                            || ($todosHorario[$i]["inicio_horario_atendimento"] <= $fim && $todosHorario[$i]["fim_horario_atendimento"] >= $fim)){
                        throw new Exception("Erro ao incluir dados. Data já existente.");
                    }
                    // verificar se o novo horario cobre o outro
                    if(($todosHorario[$i]["inicio_horario_atendimento"] >= $ini && $todosHorario[$i]["fim_horario_atendimento"] <= $fim)){
                        throw new Exception("Erro ao incluir dados. Data já existente.");
                    }
                }
            }
            $hora=null;
            // ou seja passou da meia noite o fim, ja é outro dia
            if($fim < $ini){            
                $fimDia=date_format(date_create("23:59:59"), 'H:i:s');
                $Horario1 = array(
                    "dia_semana_horario_atendimento" => $json->{"dia_semana_horario_atendimento"},
                    "inicio_horario_atendimento" => $ini,
                    "fim_horario_atendimento" => $fimDia,
                    "pizzaria_horario_atendimento" => $empresaLogada["codigo_pizzaria"]     
                    );
                $iniDia=date_format(date_create("00:00:01"), 'H:i:s');
                $dia=0;
                if($json->{"dia_semana_horario_atendimento"} <= 6){
                    $dia = $json->{"dia_semana_horario_atendimento"}+1;
                }else{
                    $dia = 1;
                }

                $Horario2 = array(
                    "dia_semana_horario_atendimento" => $dia,
                    "inicio_horario_atendimento" => $iniDia,
                    "fim_horario_atendimento" => $fim,
                    "pizzaria_horario_atendimento" => $empresaLogada["codigo_pizzaria"]     
                    );
                $hora1 = $this->HorariosEmpresa->inserirHorarioAtendimentoRetornandoo($Horario1);
                $hora2 = $this->HorariosEmpresa->inserirHorarioAtendimentoRetornandoo($Horario2);
                $hora = $hora1;
                $hora["fim_horario_atendimento"]=$fim;
                $hora["dia_semana_horario_atendimento"] = $this->converteData($hora["dia_semana_horario_atendimento"]);
            }else{
                $Horario = array(
                    "dia_semana_horario_atendimento" => $json->{"dia_semana_horario_atendimento"},
                    "inicio_horario_atendimento" => $ini,
                    "fim_horario_atendimento" => $fim,
                    "pizzaria_horario_atendimento" => $empresaLogada["codigo_pizzaria"]     
                    );
                $hora = $this->HorariosEmpresa->inserirHorarioAtendimentoRetornandoo($Horario);
                $hora["dia_semana_horario_atendimento"] = $this->converteData($hora["dia_semana_horario_atendimento"]);
            }
            if($hora == null){
                throw new Exception("Erro ao incluir dados.");
            }
            $json_str = json_encode($hora);
            echo $json_str;  
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    public function converteData($dia){
        
        if($dia == 1){
            return 'Segunda';
        }
        if($dia == 2){
            return 'Terça';
        }
        if($dia == 3){
            return 'Quarta';
        }
        if($dia == 4){
            return 'Quinta';
        }
        if($dia == 5){
            return 'Sexta';
        }
        if($dia == 6){
           return 'Sábado';
        }
        if($dia == 7){
            return  'Domingo';
        }
    }
}