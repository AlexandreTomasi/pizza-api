<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorMsgsPadraoAnna
 *
 * @author Alexandre
 */
class ControladorPersonMsgsPadrao extends CI_Controller{
    //put your code here
    public function verificaUsuarioLogado(){      
        $usuario = $this->session->userdata("gerente");
        if($usuario == null){
            $this->session->unset_userdata("gerente");
            $this->session->set_flashdata("sucess" , "Sessão Expirada. Por favor logue novamente");
            redirect('/');
        }
    }
    
    public function listarRespostas(){
        $this->verificaUsuarioLogado();
        try{
            $usuario = $this->session->userdata("gerente");
            $this->load->model("ia/Personagem_respostas_model");
            $resp = $this->Personagem_respostas_model->buscaPorCodigoGerente($usuario["codigo_gerente"]);
            $resposta = array();
            for($i=0; $i<count($resp); $i++){
                $temp = explode('||',$resp[$i]['respostas_personagem_respostas']);
                $respTemp = array();
                for($a=0; $a<count($temp); $a++){
                    $respTemp[$a] = $temp[$a];
                }
                if(count($respTemp) == 1 && $respTemp[0] == ""){
                    $respTemp = [];
                }
                $resposta[$i] = array('codigo' => $resp[$i]['codigo_personagem_respostas'],'grupo' => $resp[$i]['descricao_personagem_respostas'], "respostas" => $respTemp);
            }
            $dados = array("dados" => $resposta);    
            $this->load->view("ia/ViewPersonMsgsPadrao.php",$dados);  
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorAssunto ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }
    
    public function solicitarIncluirResposta(){
        $this->verificaUsuarioLogado();
        $this->load->model("ia/Personagem_respostas_model");
        try{
            $usuario = $this->session->userdata("gerente");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_grupo"};
            $resposta = $json->{"texto_resposta"};  
            
            $grupo = $this->Personagem_respostas_model->buscaPorCodigoIdentificador($usuario["codigo_gerente"], $codigo);
            if($grupo["respostas_personagem_respostas"] == ""){
                $grupo["respostas_personagem_respostas"] = $resposta;
            }else{
                $grupo["respostas_personagem_respostas"] = $grupo["respostas_personagem_respostas"]."||".$resposta;
            }
            if(!$this->Personagem_respostas_model->incluirResposta($grupo)){
                throw new Exception("Erro ao inserir");
            }
            return true;
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorConversa ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }
    
    
    
    public function solicitarRemoverResposta(){
        $this->verificaUsuarioLogado();
        $this->load->model("ia/Personagem_respostas_model");
        try{
            $usuario = $this->session->userdata("gerente");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_grupo"};
            $resposta = $json->{"texto_resposta"};  
            
            $grupo = $this->Personagem_respostas_model->buscaPorCodigoIdentificador($usuario["codigo_gerente"], $codigo);
            $temp = explode('||',$grupo['respostas_personagem_respostas']);
            $fim = "";
            if(count($temp) == 1){
                $grupo["respostas_personagem_respostas"] = "";
            }else{
                for($i=0; $i<count($temp); $i++){
                    if($fim == ""){
                        if($resposta != $temp[$i]){
                           $fim = $temp[$i]; 
                        }
                    }else{
                        if($resposta != $temp[$i]){
                            $fim .= "||".$temp[$i];
                        }
                    }
                }
            }
            $grupo["respostas_personagem_respostas"] = $fim;
            if(!$this->Personagem_respostas_model->removeResposta($grupo)){
                throw new Exception("Erro ao remover");
            }
            $temp = explode('||',$grupo["respostas_personagem_respostas"]);
            $respTemp = array();
            for($a=0; $a<count($temp); $a++){
                $respTemp[$a] = $temp[$a];
            }

            $json_str = json_encode(array("novo" => $respTemp));
            echo $json_str;

        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorConversa ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }
    public function solicitarAumentarGrupo(){
        $this->verificaUsuarioLogado();
        $this->load->model("ia/Personagem_respostas_model");
        try{
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_grupo"};
            
            $usuario = $this->session->userdata("gerente");
            $grupo = $this->Personagem_respostas_model->buscaPorCodigoIdentificador($usuario["codigo_gerente"], $codigo);
            
            $temp = explode(" ",$grupo["descricao_personagem_respostas"]);
            $nome = "";
            if(is_numeric($temp[count($temp)-1])){
                
                for($i=0; $i< (count($temp))-1; $i++){
                    if($nome == ""){
                        $nome .= $temp[$i];
                    }else{
                        $nome .= " ".$temp[$i];
                    }
                }
                $parecidos = $this->Personagem_respostas_model->buscaPorDescricaoParecida($usuario["codigo_gerente"], $nome);
                $maior = 0;
                for($i=0; $i< count($parecidos); $i++){
                    $tp = explode(" ",$parecidos[$i]["descricao_personagem_respostas"]);
                    if(is_numeric($tp[count($tp)-1])){
                        $a = intval($tp[count($tp)-1]);
                        if($a > $maior){
                            $maior = $a;
                        }
                    }
                }
                $grupo["codigo_personagem_respostas"] = null;
                $grupo["respostas_personagem_respostas"] = "";
                $grupo["descricao_personagem_respostas"] = $nome." ".($maior+1);
                if(!$this->Personagem_respostas_model->inserirNovoGrupo($grupo)){
                    throw new Exception("erro");
                }
                
            }else{
                $grupo["codigo_personagem_respostas"] = null;
                $grupo["respostas_personagem_respostas"] = "";
                $grupo["descricao_personagem_respostas"] = $grupo["descricao_personagem_respostas"]." 0";
                if(!$this->Personagem_respostas_model->inserirNovoGrupo($grupo)){
                    throw new Exception("erro");
                }
            }
            
            $resp = $this->Personagem_respostas_model->buscaPorCodigoGerente($usuario["codigo_gerente"]);
            $resposta = array();
            for($i=0; $i<count($resp); $i++){
                $temp = explode('||',$resp[$i]['respostas_personagem_respostas']);
                $respTemp = array();
                for($a=0; $a<count($temp); $a++){
                    $respTemp[$a] = $temp[$a];
                }
                if(count($respTemp) == 1 && $respTemp[0] == ""){
                    $respTemp = [];
                }
                $resposta[$i] = array('codigo' => $resp[$i]['codigo_personagem_respostas'],'grupo' => $resp[$i]['descricao_personagem_respostas'], "respostas" => $respTemp);
            }
            $dados = array("resposta" => $resposta);  
            $json_str = json_encode($dados);
            echo $json_str;
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorConversa ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }
    
    public function removerGrupo(){
        $this->verificaUsuarioLogado();
        $this->load->model("ia/Personagem_respostas_model");
        try{
            $usuario = $this->session->userdata("gerente");
            $meuPost = file_get_contents("php://input");
            $json = json_decode( $meuPost );
            $codigo = $json->{"codigo_grupo"};
            $resp = $this->Personagem_respostas_model->removeGrupo($usuario["codigo_gerente"], $codigo);

        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - IA excessão no controller ControladorConversa ".$e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
            
            
    }
}
