<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Taxa_entrega_model
 *
 * @author 033234581
 */
class Taxa_entrega_model extends CI_Model{
    //put your code here
    
    public function inserirTaxaEntregaRetornandoTaxa($taxaEntrega){
        if($taxaEntrega == null ){throw new Exception("(Taxa_entrega_model) metodo inserirTaxaEntregaRetornandoTaxa com parametros nulos");}
        $resp=0;
        $this->db->trans_start();
        $this->db->insert("taxa_entrega",$taxaEntrega);
        if($this->db->affected_rows() == 0){throw new Exception("(Taxa_entrega_model) metodo inserirTaxaEntregaRetornandoTaxa não alterou nenhuma linha");}
        $resp = $this->db->insert_id();       
        $taxaEntrega["codigo_taxa_entrega"] = $resp;
        
        $this->db->where("pizzaria_taxa_entrega", $taxaEntrega["pizzaria_taxa_entrega"]);
        $this->db->where("codigo_taxa_entrega", $taxaEntrega["codigo_taxa_entrega"]);
        $this->db->where("bairro_taxa_entrega", $taxaEntrega["bairro_taxa_entrega"]);
        $this->db->where("ativo_taxa_entrega", 1);
        $this->db->join('skybots_gerencia.bairro', 'skybots_gerencia.bairro.codigo_bairro = taxa_entrega.bairro_taxa_entrega');
        $novo =  $this->db->get("taxa_entrega")->row_array();
        $this->db->trans_complete();
        return $novo;
    }
    
    public function buscarTaxaEntregaAtivas($codigoPizzaria){
        if($codigoPizzaria == null ){throw new Exception("(Taxa_entrega_model) metodo buscarTaxaEntregaAtivas com parametros nulos");}
        $this->db->where("pizzaria_taxa_entrega", $codigoPizzaria);
        $this->db->where("ativo_taxa_entrega", 1);
        return $this->db->get("taxa_entrega")->result_array();
    }
    
    public function buscarTaxaEntregaPorId($codigo_taxa, $codgPizzaria){
        if($codgPizzaria == null || $codigo_taxa == null){throw new Exception("(Taxa_entrega_model) metodo buscarTaxaEntregaPorId com parametros nulos");}
        $this->db->where("codigo_taxa_entrega", $codigo_taxa);
        $this->db->where("pizzaria_taxa_entrega", $codgPizzaria);
        $this->db->where("ativo_taxa_entrega", 1);
        return $this->db->get("taxa_entrega")->row_array();
    }
    
    public function buscarTaxaEntregaAtivaInativaPorCodigo($codigo_taxa, $codgPizzaria){
        if($codgPizzaria == null || $codigo_taxa == null){throw new Exception("(Taxa_entrega_model) metodo buscarTaxaEntregaPorId com parametros nulos");}
        $this->db->where("codigo_taxa_entrega", $codigo_taxa);
        $this->db->where("pizzaria_taxa_entrega", $codgPizzaria);
        $this->db->where_in("ativo_taxa_entrega", array(0,1));
        return $this->db->get("taxa_entrega")->row_array();
    }
    
    public function buscarTaxaEntregaPorBairro($codigoPizzaria, $codigoBairro){
        if($codigoPizzaria == null || $codigoBairro == null){throw new Exception("(Taxa_entrega_model) metodo buscarTaxaEntregaPorBairro com parametros nulos");}
        $this->db->where("bairro_taxa_entrega", $codigoBairro);
        $this->db->where("pizzaria_taxa_entrega", $codigoPizzaria);
        $this->db->where("ativo_taxa_entrega", 1);
        return $this->db->get("taxa_entrega")->row_array();
    }
    
    public function buscarTaxaEntregaPorBairroAtivoInativo($codigoPizzaria, $codigoBairro){
        if($codigoPizzaria == null || $codigoBairro == null){throw new Exception("(Taxa_entrega_model) metodo buscarTaxaEntregaPorBairroAtivoInativo com parametros nulos");}
        $this->db->where("bairro_taxa_entrega", $codigoBairro);
        $this->db->where("pizzaria_taxa_entrega", $codigoPizzaria);
        $this->db->where_in("ativo_taxa_entrega", array(0,1));
        return $this->db->get("taxa_entrega")->row_array();
    }
    
    public function alterarTaxaEntrega($taxaEntrega){
        if($taxaEntrega == null ){throw new Exception("(Taxa_entrega_model) metodo alterarTaxaEntrega com parametros nulos");}
        $this->db->where('codigo_taxa_entrega', $taxaEntrega['codigo_taxa_entrega']);
        $this->db->where("pizzaria_taxa_entrega", $taxaEntrega["pizzaria_taxa_entrega"]);
        $this->db->set($taxaEntrega);
        $resposta = $this->db->update("taxa_entrega",$taxaEntrega);
        if($this->db->affected_rows() == 0){throw new Exception("(Taxa_entrega_model) metodo alterarTaxaEntrega não alterou nenhuma linha");}
        return $resposta;
    }
    
    
    public function removerTaxaEntrega($codigo_taxa, $codigoPizzaria){
        if($codigo_taxa == null || $codigoPizzaria == null){throw new Exception("(Taxa_entrega_model) metodo removerTaxaEntrega com parametros nulos");}
        $this->db->where("codigo_taxa_entrega", $codigo_taxa);
        $this->db->where("pizzaria_taxa_entrega", $codigoPizzaria);
        $this->db->where_in("ativo_taxa_entrega", array(0,1));
        $taxa = $this->db->get("taxa_entrega")->row_array();
        $taxa["ativo_taxa_entrega"]=2;
        
        $this->db->where('codigo_taxa_entrega', $taxa['codigo_taxa_entrega']);
        $this->db->where("pizzaria_taxa_entrega", $taxa["pizzaria_taxa_entrega"]);
        $this->db->set($taxa);
        $resposta =  $this->db->update("taxa_entrega",$taxa);
        if($this->db->affected_rows() == 0){throw new Exception("(Taxa_entrega_model) metodo removerTaxaEntrega não alterou nenhuma linha");}
        return $resposta;
    }
    
    public function buscarTaxaEntregaAtivasJoinBairro($codigoPizzaria){
        if($codigoPizzaria == null ){throw new Exception("(Taxa_entrega_model) metodo buscarTaxaEntregaAtivasJoinBairro com parametros nulos");}
        $bdgerente = $this->load->database('gerencia', true);
        $this->db->where("pizzaria_taxa_entrega", $codigoPizzaria);
        $this->db->where("ativo_taxa_entrega", 1);
        $todasTaxas = $this->db->get("taxa_entrega")->result_array();
        $valors = array();
        for($i=0;$i<count($todasTaxas);$i++){
            $valors[$i] = $todasTaxas[$i]["bairro_taxa_entrega"];
        }
        $bdgerente->where_in("codigo_bairro",$valors);
        $todosBairros = $bdgerente->get('bairro')->result_array();
        return $todosBairros;
    }
    
    public function buscarTaxaEntregaAtivasUniaoBairro($codigo){
        if($codigo == null ){throw new Exception("(Taxa_entrega_model) metodo buscarTaxaEntregaAtivasUniaoBairro com parametros nulos");}
        $this->db->where("pizzaria_taxa_entrega", $codigo);
        $this->db->where("ativo_taxa_entrega", 1);
        $this->db->join('skybots_gerencia.bairro', 'skybots_gerencia.bairro.codigo_bairro = taxa_entrega.bairro_taxa_entrega');
        $this->db->join('skybots_gerencia.cidade', 'skybots_gerencia.cidade.codigo_cidade = skybots_gerencia.bairro.cidade_bairro');
        return $this->db->get("taxa_entrega")->result_array();
    }
    
    public function buscarTaxaEntregaAtivasInativasUniaoBairro($codigo){
        if($codigo == null ){throw new Exception("(Taxa_entrega_model) metodo buscarTaxaEntregaAtivasUniaoBairro com parametros nulos");}
        $this->db->where("pizzaria_taxa_entrega", $codigo);
        $this->db->where_in("ativo_taxa_entrega", array(0,1));
        $this->db->join('skybots_gerencia.bairro', 'skybots_gerencia.bairro.codigo_bairro = taxa_entrega.bairro_taxa_entrega');
        $this->db->join('skybots_gerencia.cidade', 'skybots_gerencia.cidade.codigo_cidade = skybots_gerencia.bairro.cidade_bairro');
        return $this->db->get("taxa_entrega")->result_array();
    }
    
    public function buscarTaxaPorCodgAtivaJoinCidadeBairro($codigo, $codigoPizzaria){
        if($codigo == null && $codigoPizzaria == null){throw new Exception("(Taxa_entrega_model) metodo buscarTaxaPorCodgAtivaJoinCidadeBairro com parametros nulos");}
        $this->db->where("pizzaria_taxa_entrega", $codigoPizzaria);
        $this->db->where("codigo_taxa_entrega", $codigo);
        $this->db->where("ativo_taxa_entrega", 1);
        $this->db->join('skybots_gerencia.bairro', 'skybots_gerencia.bairro.codigo_bairro = taxa_entrega.bairro_taxa_entrega');
        $this->db->join('skybots_gerencia.cidade', 'skybots_gerencia.cidade.codigo_cidade = skybots_gerencia.bairro.cidade_bairro');
        return $this->db->get("taxa_entrega")->row_array();
    }
    
    public function buscarBairrosSemTaxaEntrega($codigoPizzaria){
        if($codigoPizzaria == null ){throw new Exception("(Taxa_entrega_model) metodo buscarBairrosSemTaxaEntrega com parametros nulos");}
        $bdgerente = $this->load->database('gerencia', true);
        $this->db->where("pizzaria_taxa_entrega", $codigoPizzaria);
        $this->db->where_in("ativo_taxa_entrega", array(0,1));
        $todasTaxas = $this->db->get("taxa_entrega")->result_array();
        $valors = array();
        for($i=0;$i<count($todasTaxas);$i++){
            $valors[$i] = $todasTaxas[$i]["bairro_taxa_entrega"];
        }
        //$bdgerente->where_not_in('codigo_bairro',$valors);
        $bdgerente->order_by('descricao_bairro', 'asc');
        $todosBairros = $bdgerente->get('bairro')->result_array();
        $bairros = array();
        for($i=0;$i<count($todosBairros);$i++){
            $existe = false;
            for($j=0;$j<count($valors);$j++){
                if($valors[$j] == $todosBairros[$i]["codigo_bairro"]){
                    $existe = true;
                }
            }
            if(!$existe){
                $bairros[]=$todosBairros[$i];
            }
        }
        return $bairros;
    }
    
    public function buscarBairrosSemTaxaEntregaPorCidade($codigoPizzaria, $cidade){
        if($codigoPizzaria == null ){throw new Exception("(Taxa_entrega_model) metodo buscarBairrosSemTaxaEntrega com parametros nulos");}
        $bdgerente = $this->load->database('gerencia', true);
        $this->db->where("pizzaria_taxa_entrega", $codigoPizzaria);
        $this->db->where_in("ativo_taxa_entrega", array(0,1));
        $todasTaxas = $this->db->get("taxa_entrega")->result_array();
        $valors = array();
        for($i=0;$i<count($todasTaxas);$i++){
            $valors[$i] = $todasTaxas[$i]["bairro_taxa_entrega"];
        }
        //$bdgerente->where_not_in('codigo_bairro',$valors);
        $bdgerente->order_by('descricao_bairro', 'asc');
        $todosBairros = $bdgerente->get('bairro')->result_array();
        $bairros = array();
        for($i=0;$i<count($todosBairros);$i++){
            $existe = false;
            for($j=0;$j<count($valors);$j++){
                if($valors[$j] == $todosBairros[$i]["codigo_bairro"]){
                    $existe = true;
                }
            }
            if(!$existe && $todosBairros[$i]["cidade_bairro"] == $cidade){
                $bairros[]=$todosBairros[$i];
            }
        }
        return $bairros;
    }
    
    public function ativarTaxaEntrega($codigo_taxa, $codigoPizzaria){
        if($codigo_taxa == null || $codigoPizzaria == null){throw new Exception("(Taxa_entrega_model) metodo removerTaxaEntrega com parametros nulos");}
        $this->db->where("codigo_taxa_entrega", $codigo_taxa);
        $this->db->where("pizzaria_taxa_entrega", $codigoPizzaria);
        $this->db->where("ativo_taxa_entrega", 0);
        $taxa = $this->db->get("taxa_entrega")->row_array();
        $taxa["ativo_taxa_entrega"]=1;
        
        $this->db->where('codigo_taxa_entrega', $taxa['codigo_taxa_entrega']);
        $this->db->where("pizzaria_taxa_entrega", $taxa["pizzaria_taxa_entrega"]);
        $this->db->set($taxa);
        $resposta =  $this->db->update("taxa_entrega",$taxa);
        if($this->db->affected_rows() == 0){throw new Exception("(Taxa_entrega_model) metodo ativarTaxaEntrega não alterou nenhuma linha");}
        return $resposta;
    }
    
    public function inativarTaxaEntrega($codigo_taxa, $codigoPizzaria){
        if($codigo_taxa == null || $codigoPizzaria == null){throw new Exception("(Taxa_entrega_model) metodo removerTaxaEntrega com parametros nulos");}
        $this->db->where("codigo_taxa_entrega", $codigo_taxa);
        $this->db->where("pizzaria_taxa_entrega", $codigoPizzaria);
        $this->db->where("ativo_taxa_entrega", 1);
        $taxa = $this->db->get("taxa_entrega")->row_array();
        $taxa["ativo_taxa_entrega"]=0;
        
        $this->db->where('codigo_taxa_entrega', $taxa['codigo_taxa_entrega']);
        $this->db->where("pizzaria_taxa_entrega", $taxa["pizzaria_taxa_entrega"]);
        $this->db->set($taxa);
        $resposta =  $this->db->update("taxa_entrega",$taxa);
        if($this->db->affected_rows() == 0){throw new Exception("(Taxa_entrega_model) metodo inativarTaxaEntrega não alterou nenhuma linha");}
        return $resposta;
    }
}
