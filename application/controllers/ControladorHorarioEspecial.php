<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorHorarioEspecial
 *
 * @author Alexandre
 */
class ControladorHorarioEspecial extends CI_Controller{
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
    
    public function manterHorarioEspecial(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/HorariosEmpresa");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $horarioEsp = $this->HorariosEmpresa->consultaHorarioEspecialAbertoFexado($empresaLogada);

            $remov = array();
            $todos = array();
            $fimDia =  date_format(date_create("23:59:59"), 'H:i:s');
            $iniDia =  date_format(date_create("00:00:01"), 'H:i:s');
            for($i=0; $i<count($horarioEsp); $i++){
                $fim = date_format(date_create($horarioEsp[$i]["fim_horario_especial"]), 'H:i:s');
                if($fim == $fimDia){
                    for($a=0; $a<count($horarioEsp); $a++){
                        $ini = date_format(date_create($horarioEsp[$a]["inicio_horario_especial"]), 'H:i:s');
                        if($iniDia == $ini){                      
                            $flag = date_format(date_create($horarioEsp[$i]["data_horario_especial"]), 'Y-m-d');
                            if(date('Y-m-d',strtotime("+1 days",strtotime($flag))) == date_format(date_create($horarioEsp[$a]["data_horario_especial"]), 'Y-m-d')  ){
                                $horarioEsp[$i]["fim_horario_especial"] = $horarioEsp[$a]["fim_horario_especial"];
                                $remov[]=$a;
                                break;
                            }
                        }
                    }
                }

            }
            if(count($remov) > 0){
                for($i=0; $i<count($horarioEsp); $i++){
                    for($a=0; $a<count($remov); $a++){
                        if($remov[$a] != $i){// adiciona só os que nao tao marcados para remover
                            $todos[] = $horarioEsp[$i];
                            break;
                        }               
                    }
                }
            }else{
                $todos = $horarioEsp;
            }


            for($i=0; $i<count($todos); $i++){
                $todos[$i]["data_horario_especial"] = date_format(date_create($todos[$i]["data_horario_especial"]), 'd/m/Y');
                if($todos[$i]["aberto_horario_especial"] == 1){
                    $todos[$i]["aberto_horario_especial"] = "Aberto";
                }else if($todos[$i]["aberto_horario_especial"] == 0){
                    $todos[$i]["aberto_horario_especial"] = "Fechado";
                }
                else if($todos[$i]["aberto_horario_especial"] == 2){
                    $todos[$i]["aberto_horario_especial"] = "Pausado";
                }
            }

            $dados = array("horarioEsp" => $todos);
            $this->load->view("pizzaria/ViewManterHorarioEspecial.php",$dados);
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    
    public function incluirHorarioEspecial(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/HorariosEmpresa");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $todosHorario = $this->HorariosEmpresa->consultaHorarioEspecial($empresaLogada);
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $ini = date_format(date_create($json->{"inicio_horario_especial"}), 'H:i:s');
            $fim = date_format(date_create($json->{"fim_horario_especial"}), 'H:i:s');
            $diaEspecial = date_format(date_create($json->{"data_horario_especial"}), 'Y-m-d');

//            if(!($diaEspecial >= date('Y-m-d'))){
//                throw new Exception("Erro ao incluir dados. Não é possivel inserir em data inferior a atual");
//            }
            for($i=0; $i<count($todosHorario); $i++){
                $dia = date_format(date_create($todosHorario[$i]["data_horario_especial"]), 'Y-m-d');
                if($dia == $diaEspecial && date_format(date_create($todosHorario[$i]["inicio_horario_especial"]), 'H:i:s') != date_format(date_create("00:00:01"), 'H:i:s')
                        && $todosHorario[$i]["aberto_horario_especial"] != 2){
                    throw new Exception("Erro ao incluir dados. Data já existente.");
                }
            }

            $hora=array();
            // ou seja passou da meia noite o fim, ja é outro dia
            if($fim < $ini){            
                $fimDia=date_format(date_create("23:59:59"), 'H:i:s');
                $Horario1 = array(
                    "data_horario_especial" => $diaEspecial,
                    "inicio_horario_especial" => $ini,
                    "fim_horario_especial" => $fimDia,
                    "pizzaria_horario_especial" => $empresaLogada["codigo_pizzaria"],
                    "aberto_horario_especial" => $json->{"aberto_horario_especial"},
                    "ativo_horario_especial" => 1
                    );
                $iniDia=date_format(date_create("00:00:01"), 'H:i:s');

                $Horario2 = array(
                    "data_horario_especial" => date('Y-m-d',strtotime("+1 days",strtotime($diaEspecial))),
                    "inicio_horario_especial" => $iniDia,
                    "fim_horario_especial" => $fim,
                    "pizzaria_horario_especial" => $empresaLogada["codigo_pizzaria"],
                    "aberto_horario_especial" => $json->{"aberto_horario_especial"},
                    "ativo_horario_especial" => 1
                    );
                $hora1 = $this->HorariosEmpresa->inserirHorarioEspecialRetornandoo($Horario1);
                $hora2 = $this->HorariosEmpresa->inserirHorarioEspecialRetornandoo($Horario2);
                $hora = $hora1;
                $hora["fim_horario_especial"]=$fim;
                if($hora["aberto_horario_especial"] == 1){
                    $hora["aberto_horario_especial"] = "Aberto";
                }else if($hora["aberto_horario_especial"] == 0){
                    $hora["aberto_horario_especial"] = "Fechado";
                }
                else if($hora["aberto_horario_especial"] == 2){
                    $hora["aberto_horario_especial"] = "Pausado";
                }
                $hora["data_horario_especial"] = date_format(date_create($hora["data_horario_especial"]), 'd-m-Y');
            }else{
                $Horario = array(
                    "data_horario_especial" => $diaEspecial,
                    "inicio_horario_especial" => $ini,
                    "fim_horario_especial" => $fim,
                    "pizzaria_horario_especial" => $empresaLogada["codigo_pizzaria"],
                    "aberto_horario_especial" => $json->{"aberto_horario_especial"},
                    "ativo_horario_especial" => 1
                    );
                $hora = $this->HorariosEmpresa->inserirHorarioEspecialRetornandoo($Horario);
                if($hora["aberto_horario_especial"] == 1){
                    $hora["aberto_horario_especial"] = "Aberto";
                }else if($hora["aberto_horario_especial"] == 0){
                    $hora["aberto_horario_especial"] = "Fechado";
                }
                else if($hora["aberto_horario_especial"] == 2){
                    $hora["aberto_horario_especial"] = "Pausado";
                }
                $hora["data_horario_especial"] = date_format(date_create($hora["data_horario_especial"]), 'd-m-Y');
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
    
    
    public function confirmarRemoverHorarioEspecial(){
        $this->verificaUsuarioLogado();       
        $this->load->model("pizzaria/HorariosEmpresa");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_horario_especial"};
            $codigo1 = 0;
            $horario = $this->HorariosEmpresa->consultaHorarioEspecial($empresaLogada);
            for($i=0; $i<count($horario); $i++){
                $ini = date_format(date_create($horario[$i]["inicio_horario_especial"]), 'H:i:s');
                $fim = date_format(date_create($horario[$i]["fim_horario_especial"]), 'H:i:s');
                $temp =  date_format(date_create("23:59:59"), 'H:i:s');
                if($horario[$i]["codigo_horario_especial"] == $codigo){
                    if($fim == $temp){                   
                        for($a=0; $a<count($horario); $a++){//procurando o segundo
                            $iniOut = date_format(date_create($horario[$a]["inicio_horario_especial"]), 'H:i:s');
                            $fimOut = date_format(date_create($horario[$a]["fim_horario_especial"]), 'H:i:s');
                            if($iniOut == date_format(date_create("00:00:01"), 'H:i:s') ){
                                $flag = date_format(date_create($horario[$i]["data_horario_especial"]), 'Y-m-d');
                                if(date('Y-m-d',strtotime("+1 days",strtotime($flag))) == date_format(date_create($horario[$a]["data_horario_especial"]), 'Y-m-d')  ){
                                    $codigo1 = $horario[$a]["codigo_horario_especial"];
                                }
                            }
                        }
                        if( !($this->HorariosEmpresa->removerHorarioEspecial($codigo, $empresaLogada["codigo_pizzaria"] )) ){
                            throw new Exception("Erro ao excluir dados.");
                        } 
                        if($codigo1 != 0 && !($this->HorariosEmpresa->removerHorarioEspecial($codigo1, $empresaLogada["codigo_pizzaria"] )) ){
                            throw new Exception("Erro ao excluir dados.");
                        } 
                    }else{
                        if( !($this->HorariosEmpresa->removerHorarioEspecial($codigo, $empresaLogada["codigo_pizzaria"] )) ){
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
    
    
    public function buscarHorarioEspecialPorID(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/HorariosEmpresa");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_horario_especial"};
            $hora = $this->HorariosEmpresa->consultaHorarioEspecialPorCodigo($codigo, $empresaLogada["codigo_pizzaria"]); 
            $horario = $this->HorariosEmpresa->consultaHorarioEspecial($empresaLogada);

            $fim = date_format(date_create($hora["fim_horario_especial"]), 'H:i:s');
            $flag = date_format(date_create($hora["data_horario_especial"]), 'Y-m-d');

            $fimDia =  date_format(date_create("23:59:59"), 'H:i:s');
            $iniDia =  date_format(date_create("00:00:01"), 'H:i:s');
            if($fim == $fimDia){
                for($a=0; $a<count($horario); $a++){
                    $ini = date_format(date_create($horario[$a]["inicio_horario_especial"]), 'H:i:s');
                    if($iniDia == $ini){
                        if(date('Y-m-d',strtotime("+1 days",strtotime($flag))) == date_format(date_create($horario[$a]["data_horario_especial"]), 'Y-m-d')  ){
                            $hora["fim_horario_especial"] = $horario[$a]["fim_horario_especial"];
                            break;
                        }
                    }
                }
            }
            $hora["data_horario_especial"] = date_format(date_create($hora["data_horario_especial"]), 'd/m/Y');
            $hora["inicio_horario_especial"] = date("Y-m-d")."T".date_format(date_create($hora["inicio_horario_especial"]), 'H:i');
            $hora["fim_horario_especial"]= date("Y-m-d")."T".date_format(date_create($hora["fim_horario_especial"]), 'H:i');

            $json_str = json_encode($hora);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
    
    public function alterarHorarioEspecial(){
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/HorariosEmpresa");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_horario_especial"};
            $status = $json->{"aberto_horario_especial"};
            // devido php muito fraco para data. Converto uma string em mktime e depois para date.
            $temp = explode(" ",$json->{"inicio_horario_especial"});
            $temp = explode(":",$temp[1]);
            $ini = date("H:i:s", mktime($temp[0], $temp[1], $temp[2]));          
            $tp = explode(" ",$json->{"fim_horario_especial"});
            $tp = explode(":",$tp[1]);
            $fim = date("H:i:s", mktime($tp[0], $tp[1], $tp[2]));
                    
            $fimDia=date_format(date_create("23:59:59"), 'H:i:s');
            $iniDia=date_format(date_create("00:00:01"), 'H:i:s');
            
            $codigo1 = 0;
            $dia=0;
            
            //procura se existe duas linhas no banco para esse horario
            $horario = $this->HorariosEmpresa->consultaHorarioEspecial($empresaLogada);
            for($i=0; $i<count($horario); $i++){
                if($horario[$i]["codigo_horario_especial"] == $codigo){
                    for($a=0; $a<count($horario); $a++){//procurando o segundo
                        $iniOut = date_format(date_create($horario[$a]["inicio_horario_especial"]), 'H:i:s');
                        $fimOut = date_format(date_create($horario[$a]["fim_horario_especial"]), 'H:i:s');
                        if($iniOut == date_format(date_create("00:00:01"), 'H:i:s') ){
                            $flag = date_format(date_create($horario[$i]["data_horario_especial"]), 'Y-m-d');
                            if(date('Y-m-d',strtotime("+1 days",strtotime($flag))) == date_format(date_create($horario[$a]["data_horario_especial"]), 'Y-m-d')  ){
                                $codigo1 = $horario[$a]["codigo_horario_especial"];
                            }
                        }
                    }
                    $dia = $horario[$i]["data_horario_especial"];
                    break;
                }
            }
            
            if($codigo1 != 0){//se possui duas linhas
                if($fim < $ini){//verifico se ainda vai ficar as duas linhas se sim
                    $HorarioNovo1 = array(
                        "codigo_horario_especial" => $codigo,
                        "inicio_horario_especial" => $ini,
                        "pizzaria_horario_especial" => $empresaLogada["codigo_pizzaria"],     
                        "aberto_horario_especial" => $status,
                        "ativo_horario_especial" => 1
                    );

                    $HorarioNovo2 = array(
                        "codigo_horario_especial" => $codigo1,
                        "fim_horario_especial" => $fim,
                        "pizzaria_horario_especial" => $empresaLogada["codigo_pizzaria"],
                        "aberto_horario_especial" => $status,
                        "ativo_horario_especial" => 1
                    );
                    if( !(($this->HorariosEmpresa->alteraHorarioEspecial($HorarioNovo1)) && ($this->HorariosEmpresa->alteraHorarioEspecial($HorarioNovo2))) ){
                        throw new Exception("Erro ao alterar dados.");
                    }
                }else{
                    // se agora deve possuir 1 linhas entao removo a segunda e altero a primeira
                    if($codigo1 != 0 && !($this->HorariosEmpresa->removerHorarioEspecial($codigo1, $empresaLogada["codigo_pizzaria"] )) ){
                            throw new Exception("Erro ao excluir dados.");
                    } 
                    $HorarioNovo = array(
                        "codigo_horario_especial" => $codigo,
                        "inicio_horario_especial" => $ini,
                        "fim_horario_especial" => $fim,
                        "pizzaria_horario_especial" => $empresaLogada["codigo_pizzaria"],
                        "aberto_horario_especial" => $status,
                        "ativo_horario_especial" => 1
                        );
                    if(!($this->HorariosEmpresa->alteraHorarioEspecial($HorarioNovo))){
                        throw new Exception("Erro ao alterar dados.");
                    } 
                }
            }else{
                if($fim < $ini){// possue 1 linha entao altero a primeira e crio uma nova como segunda
                    // altera o atual               
                    $Horario1 = array(
                        "codigo_horario_especial" => $codigo,
                        "inicio_horario_especial" => $ini,
                        "fim_horario_especial" => $fimDia,
                        "pizzaria_horario_especial" => $empresaLogada["codigo_pizzaria"],
                        "aberto_horario_especial" => $status,
                        "ativo_horario_especial" => 1
                        );
                    if(!($this->HorariosEmpresa->alteraHorarioEspecial($Horario1))){
                        throw new Exception("Erro ao alterar dados.");
                    }
                    $dia = date('Y-m-d',strtotime("+1 days",strtotime($dia)));
                    // cria uma nova linha no banco
                    $Horario2 = array(
                        'data_horario_especial' => $dia,
                        "inicio_horario_especial" => $iniDia,
                        "fim_horario_especial" => $fim,
                        "pizzaria_horario_especial" => $empresaLogada["codigo_pizzaria"],
                        "aberto_horario_especial" => $status,
                        "ativo_horario_especial" => 1
                        );
                    $hora = $this->HorariosEmpresa->inserirHorarioEspecialRetornandoo($Horario2);
                }else{// se era 1 linha e continua uma linha só altero
                    $HorarioNovo = array(
                        "codigo_horario_especial" => $codigo,
                        "inicio_horario_especial" => $ini,
                        "fim_horario_especial" => $fim,
                        "pizzaria_horario_especial" => $empresaLogada["codigo_pizzaria"],
                        "aberto_horario_especial" => $status,
                        "ativo_horario_especial" => 1
                        );
                   // $fp = fopen("log_skybots.txt", "a");
                   // $escreve = fwrite($fp, "\n inicio ".$HorarioNovo["inicio_horario_especial"]."  ".$HorarioNovo["fim_horario_especial"]);
                  //  fclose($fp);
                    if(!($this->HorariosEmpresa->alteraHorarioEspecial($HorarioNovo))){
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
}
