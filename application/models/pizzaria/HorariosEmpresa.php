<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HorariosEmpresa
 *
 * @author 033234581
 */
class HorariosEmpresa extends CI_Model{
    //put your code here
    
    public function alteraHorarioAtendimento($horario){
        if($horario == null){throw new Exception("(HorariosEmpresa) metodo alteraHorarioAtendimento com parametros nulos");}
        $this->db->where('codigo_horario_atendimento', $horario["codigo_horario_atendimento"]);
        $this->db->where('pizzaria_horario_atendimento', $horario['pizzaria_horario_atendimento']);
        $this->db->set($horario);
        return $this->db->update("horario_atendimento",$horario);
    }   
    public function consultaHorarioAtendimento($pizzaria){
        if($pizzaria == null){throw new Exception("(HorariosEmpresa) metodo consultaHorarioAtendimento com parametros nulos");}
        $this->db->where('pizzaria_horario_atendimento', $pizzaria);
        $horario = $this->db->get("horario_atendimento")->result_array();
        return $horario;
    }
    
    public function consultaHorarioAtendimentoPorCodigo($codigo, $pizzaria){
        if($pizzaria == null || $codigo == null){throw new Exception("(HorariosEmpresa) metodo consultaHorarioAtendimentoPorCodigo com parametros nulos");}
        $this->db->where('codigo_horario_atendimento', $codigo);
        $this->db->where('pizzaria_horario_atendimento', $pizzaria);
        $horario = $this->db->get("horario_atendimento")->row_array();
        return $horario;
    }  
    
    public function removerHorarioAtendimento($codigo, $pizzaria){
        if($pizzaria == null || $codigo == null){throw new Exception("(HorariosEmpresa) metodo removerHorarioAtendimento com parametros nulos");}
        $this->db->where('codigo_horario_atendimento', $codigo);
        $this->db->where('pizzaria_horario_atendimento', $pizzaria);
        return $this->db->delete("horario_atendimento");
    }
    
    public function inserirHorarioAtendimentoRetornandoo($horario){
        if($horario == null){throw new Exception("(HorariosEmpresa) metodo inserirHorarioAtendimentoRetornandoo com parametros nulos");}
        $resp=0;
        $this->db->trans_start();
        $in = $this->db->insert("horario_atendimento",$horario);
        $resp = $this->db->insert_id();
        $this->db->trans_complete();
        $horario["codigo_horario_atendimento"] = $resp;
        return $horario;
    }
    
    
    
    
// controler horario ESPECIAL @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@    
    
    public function consultaHorarioEspecial($pizzaria){
        $this->db->where('pizzaria_horario_especial', $pizzaria['codigo_pizzaria']);
        $this->db->where('ativo_horario_especial', 1);
        $horario = $this->db->get("horario_especial")->result_array();
        return $horario;
    }
    
    public function consultaHorarioEspecialAbertoFexado($pizzaria){
        $this->db->where('pizzaria_horario_especial', $pizzaria['codigo_pizzaria']);
        $this->db->where('ativo_horario_especial', 1);
        $this->db->where_in("aberto_horario_especial",[0,1]);
        $horario = $this->db->get("horario_especial")->result_array();
        return $horario;
    }
    
    public function inserirHorarioEspecialRetornandoo($horario){
        $resp=0;
        $this->db->trans_start();
        $in = $this->db->insert("horario_especial",$horario);
        $resp = $this->db->insert_id();
        $this->db->trans_complete();
        $horario["codigo_horario_especial"] = $resp;
        return $horario;
    }
    
    public function removerHorarioEspecial($codigo, $pizzaria){
        $this->db->where('codigo_horario_especial', $codigo);
        $this->db->where('pizzaria_horario_especial', $pizzaria);
        $this->db->where('ativo_horario_especial', 1);
        $especial = $this->db->get("horario_especial")->row_array();
        $especial["ativo_horario_especial"]=0;
        
        $this->db->where('codigo_horario_especial', $especial["codigo_horario_especial"]);
        $this->db->where('pizzaria_horario_especial', $especial["pizzaria_horario_especial"]);
        $this->db->where('ativo_horario_especial', 1);
        $this->db->set($especial);
        $resultado = $this->db->update("horario_especial",$especial);
        if($this->db->affected_rows() == 0){throw new Exception("(Bebida_model) metodo removerBebida não alterou nenhuma linha");}
        $this->db->trans_complete();
        return $resultado;
    }
    /*
    public function removerHorarioEspecial($codigo, $pizzaria){
        $this->db->where('codigo_horario_especial', $codigo);
        $this->db->where('pizzaria_horario_especial', $pizzaria);
        return $this->db->delete("horario_especial");
    }*/
    
    public function consultaHorarioEspecialPorCodigo($codigo, $pizzaria){
        $this->db->where('codigo_horario_especial', $codigo);
        $this->db->where('pizzaria_horario_especial', $pizzaria);
        $this->db->where('ativo_horario_especial', 1);
        $horario = $this->db->get("horario_especial")->row_array();
        return $horario;
    } 
    
    public function alteraHorarioEspecial($horario){
        $this->db->where('codigo_horario_especial', $horario["codigo_horario_especial"]);
        $this->db->where('pizzaria_horario_especial', $horario['pizzaria_horario_especial']);
        $this->db->set($horario);
        return $this->db->update("horario_especial",$horario);
    } 
    
    public function consultaHorarioEspecialDiaAtualCodigoPizzaria($pizzaria, $data){
        $this->db->where('pizzaria_horario_especial', $pizzaria);
        $this->db->where('data_horario_especial', $data);
        $this->db->where('ativo_horario_especial', 1);
        $this->db->order_by('aberto_horario_especial', 'desc');
        $horario = $this->db->get("horario_especial")->result_array();
        return $horario;
    }
}
