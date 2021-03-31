<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Bebida_model extends CI_Model{
    
    public function buscarBebidas($codigo){
        if($codigo == null){
            throw new Exception("(Bebida_model) metodo buscarBebidas com parametros nulos");
        }
        $this->db->where("pizzaria_bebida", $codigo);
        $this->db->where("ativo_bebida", 1);
        return $this->db->get("bebida")->result_array();
    }
    
    public function inserirBebida($bebida){
        if($bebida == null){
            throw new Exception("(Bebida_model) metodo inserirBebida com parametros nulos");
        }
       $resultado = $this->db->insert("bebida",$bebida);  
       if($this->db->affected_rows() == 0){throw new Exception("(Bebida_model) metodo inserirBebida não alterou nenhuma linha");}
       return $resultado;
    }
    
    public function incluirBebidaRetornandoBebida($bebida){
        if($bebida == null){
            throw new Exception("(Bebida_model) metodo incluirBebidaRetornandoBebida com parametros nulos");
        }
        $resp=0;
        $this->db->trans_start();
        $this->db->insert("bebida",$bebida);
        if($this->db->affected_rows() == 0){throw new Exception("(Bebida_model) metodo incluirBebidaRetornandoBebida não alterou nenhuma linha");}
        $resp = $this->db->insert_id();
        $this->db->trans_complete();
        $bebida["codigo_bebida"] = $resp;
        $bebida["ativo_bebida"] = 1;
        return $bebida;
    }
    
    public function buscarBebidasAtivasInativas($codigo){
        if($codigo == null){
            throw new Exception("(Bebida_model) metodo buscarBebidas com parametros nulos");
        }
        $this->db->where("pizzaria_bebida", $codigo);
        //$this->db->where("ativo_bebida !=", 2);
        $this->db->where_in("ativo_bebida", array(0,1));
        return $this->db->get("bebida")->result_array();
    }
    
    public function buscarBebidasPizzariaAtivas($codigoPizzaria){
        if($codigoPizzaria == null){
            throw new Exception("(Bebida_model) metodo buscarBebidasPizzariaAtivas com parametros nulos");
        }
        $this->db->where("pizzaria_bebida", $codigoPizzaria);
        $this->db->where("ativo_bebida", 1);
        return $this->db->get("bebida")->result_array();
    }
    
    public function buscarBebidasPizzariaAtivaPorNome($codigoPizzaria, $nome){
        if($codigoPizzaria == null || $nome == null){
            throw new Exception("(Bebida_model) metodo buscarBebidasPizzariaAtivaPorNome com parametros nulos");
        }
        $this->db->where("pizzaria_bebida", $codigoPizzaria);
        $this->db->where("ativo_bebida", 1);
        $this->db->where("descricao_bebida", $nome);
        return $this->db->get("bebida")->row_array();
    }
    
    public function buscarBebidaPorId($codigo_bebida){
        if($codigo_bebida == null){
            throw new Exception("(Bebida_model) metodo buscarBebidaPorId com parametros nulos");
        }
        $this->db->where("codigo_bebida", $codigo_bebida);
        $this->db->where("ativo_bebida", 1);
        return $this->db->get("bebida")->row_array();
    }
    
    public function buscarBebidaPorIdAtivaInativa($codigo_bebida){
        if($codigo_bebida == null){
            throw new Exception("(Bebida_model) metodo buscarBebidaPorId com parametros nulos");
        }
        $this->db->where("codigo_bebida", $codigo_bebida);
        $this->db->where_in("ativo_bebida", array(0,1));
        return $this->db->get("bebida")->row_array();
    }
    
    public function buscarBebidaPorIdECodgPizzaria($codigo_bebida, $codigoPizzaria){
        if($codigo_bebida == null || $codigoPizzaria == null){
            throw new Exception("(Bebida_model) metodo buscarBebidaPorId com parametros nulos");
        }
        $this->db->where("pizzaria_bebida", $codigoPizzaria);
        $this->db->where("codigo_bebida", $codigo_bebida);
        $this->db->where("ativo_bebida", 1);
        return $this->db->get("bebida")->row_array();
    }
    public function alterarBebida($bebida){
        if($bebida == null){
            throw new Exception("(Bebida_model) metodo alterarBebida com parametros nulos");
        }
        $this->db->where('codigo_bebida', $bebida['codigo_bebida']);
        $this->db->set($bebida);
        $resultado =  $this->db->update("bebida",$bebida);
        return $resultado;
    }
    
    public function removerBebida($codigo_bebida, $empresa){
        if($codigo_bebida == null && $empresa != null){
            throw new Exception("(Bebida_model) metodo removerBebida com parametros nulos");
        }
        $this->db->trans_start();
        $this->db->where("codigo_bebida", $codigo_bebida);
        $this->db->where("pizzaria_bebida", $empresa);
        $bebida = $this->db->get("bebida")->row_array();
        $bebida["ativo_bebida"]=2;
        
        $this->db->where('codigo_bebida', $bebida['codigo_bebida']);
        $this->db->where("pizzaria_bebida", $empresa);
        $this->db->set($bebida);
        $resultado = $this->db->update("bebida",$bebida);
        if($this->db->affected_rows() == 0){throw new Exception("(Bebida_model) metodo removerBebida não alterou nenhuma linha");}
        $this->db->trans_complete();
        return $resultado;
    }
    
    public function ativarBebidaPorCodg($codigo_bebida, $empresa){
        if($codigo_bebida == null && $empresa != null){
            throw new Exception("(Bebida_model) metodo ativarBebidaPorCodg com parametros nulos");
        }
        $this->db->trans_start();
        $this->db->where("codigo_bebida", $codigo_bebida);
        $this->db->where("pizzaria_bebida", $empresa);
        $bebida = $this->db->get("bebida")->row_array();
        $bebida["ativo_bebida"]=1;
        
        $this->db->where('codigo_bebida', $bebida['codigo_bebida']);
        $this->db->set($bebida);
        $resultado = $this->db->update("bebida",$bebida);
        if($this->db->affected_rows() == 0){throw new Exception("(Bebida_model) metodo ativarBebidaPorCodg não alterou nenhuma linha");}
        $this->db->trans_complete();
        return $resultado;
    }
    
    public function inativarBebidaPorCodg($codigo_bebida, $empresa){
        if($codigo_bebida == null && $empresa != null){
            throw new Exception("(Bebida_model) metodo inativarBebidaPorCodg com parametros nulos");
        }
        $this->db->trans_start();
        $this->db->where("codigo_bebida", $codigo_bebida);
        $this->db->where("pizzaria_bebida", $empresa);
        $bebida = $this->db->get("bebida")->row_array();
        $bebida["ativo_bebida"]=0;
        
        $this->db->where('codigo_bebida', $bebida['codigo_bebida']);
        $this->db->set($bebida);
        $resultado = $this->db->update("bebida",$bebida);
        if($this->db->affected_rows() == 0){throw new Exception("(Bebida_model) metodo inativarBebidaPorCodg não alterou nenhuma linha");}
        $this->db->trans_complete();
        return $resultado;
    }
}
