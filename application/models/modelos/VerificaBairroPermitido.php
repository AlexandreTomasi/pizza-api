<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VerificaBairroPermitido
 *
 * @author 033234581
 */
class VerificaBairroPermitido extends CI_Model{
    
    public function verificaSeEntregaNoBairro($bairro, $pizzaria, $variavel){
        $this->load->model("modelos/UtilitarioMensagemFacebook");
        $this->load->model("pizzaria/Empresa_model");
        $this->load->model("pizzaria/Taxa_entrega_model");
        
        $taxa = $this->Taxa_entrega_model->buscarTaxaEntregaPorBairroAtivoInativo($pizzaria, $bairro);
        if($taxa == null){
            $msg = $this->Empresa_model->buscaConfigEmpresa("mensagem_nao_efetua_entrega",$pizzaria);
            $rapida = array();
            $rapida[] = array('title' => "Recomeçar",'block_names' => array("começar"));
            $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($msg, "", $rapida);
            return $resposta;
        }
        if($taxa["ativo_taxa_entrega"] == 0){
            $msg = "Desculpa, a entrega para esse bairro está temporariamente indisponível";
            $rapida = array();
            $rapida[] = array('title' => "Recomeçar",'block_names' => array("começar"));
            $resposta = $this->UtilitarioMensagemFacebook->gerarBotoesRapidos($msg, "", $rapida);
            return $resposta;
        }
        if($taxa["ativo_taxa_entrega"] == 1){
            $resposta = $this->UtilitarioMensagemFacebook->definirAtributosDoUsuario(array($variavel => $bairro));
            return $resposta;
        }
    }
    
    public function buscaBairroPorBotaoSelecionado($texto, $pizzaria, $variavel){
        $this->load->model("gerencia/Bairro_model");
        $bairro = $this->Bairro_model->buscaBairroPorNome($texto);
        if($bairro == null){
            return $this->verificaSeEntregaNoBairro(0 ,$pizzaria, $variavel);
        }else{
            return $this->verificaSeEntregaNoBairro($bairro["codigo_bairro"] ,$pizzaria, $variavel);
        }
    }
    
    public function identificaBairroDigitado($nomeBairro, $pizzaria){
        $this->load->model("gerencia/Bairro_model");
        $bairrosPermitidos = $this->Bairro_model->buscaBairros();
        if($bairrosPermitidos == null){throw new Exception("buscaBairros, nao retornou nenhum dado. Funcionalidade: verificaBairroPermitido->identificaBairroDigitado");}
        $bairrosCompativeis=array();
        $minima_distancia = 0;
        //encontra a palavra mais proxima
        for($a=0; $a<count($bairrosPermitidos);$a++){
            $palavra_do_dicionario = $bairrosPermitidos[$a]["descricao_bairro"];
            similar_text($nomeBairro,$palavra_do_dicionario,$distancia);

            if($distancia >= $minima_distancia) {
               $minima_distancia = $distancia;             
            }
        }
        //pega os bairros que foram mais proximos 
        for($a=0; $a<count($bairrosPermitidos);$a++){
            $palavra_do_dicionario = $bairrosPermitidos[$a]["descricao_bairro"];
            similar_text($nomeBairro,$palavra_do_dicionario,$distancia);

            if($distancia == $minima_distancia) {
                $bairrosCompativeis[] = $bairrosPermitidos[$a];
            }
        }
        return $bairrosCompativeis;
           /* $minima_distancia = 100;
            echo('levenshtein<br/>');
            foreach ($lista as $palavra_do_dicionario) {
            $distancia = levenshtein($nomeBairro,$palavra_do_dicionario);

                   if($distancia <= $minima_distancia) {
                           $minima_distancia = $distancia; 


                   }
            }
            foreach ($lista as $palavra_do_dicionario) {
            $distancia = levenshtein($palavra_procurada,$palavra_do_dicionario);

                   if($distancia == $minima_distancia) {
                           echo  $palavra_do_dicionario;
                                 echo ' - ';
                                    echo $distancia;
                                    echo '<br/>';


                   }
            }*/
    }
}
