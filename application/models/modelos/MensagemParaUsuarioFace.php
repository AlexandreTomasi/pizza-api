<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MensagemParaUsuarioFace
 *
 * @author 033234581
 */
class MensagemParaUsuarioFace extends CI_Model{
    //put your code here
    public function respostaAoClienteSobrePedido($idFaceCliente, $pizzaria){
        $pizzaria = intval($pizzaria);
        $this->load->model("pizzaria/Empresa_model");
        $this->load->model("gerencia/Cliente_pizzaria_model");
        $this->load->model("gerencia/Valor_configuracao_model");
        $empresaLogada = $this->Empresa_model->buscaPizzariaPorCodigo($pizzaria);
        $senhaCliente = $this->session->userdata("senhaCliente");
        $cliente = $this->Cliente_pizzaria_model->buscaPorEmailESenhaMD5($empresaLogada["email_pizzaria"], $senhaCliente);    
        if($cliente == null){
            throw new Exception("Erro ao enviar msg para cliente");
        }
        $fraseBot = $this->Empresa_model->buscaConfigEmpresa("frase_bot_entrega_pizzaria",$pizzaria);

        $user_id = $idFaceCliente;
        $token = $cliente["token_cliente"];
        $bot_id =$cliente["bot_id_cliente"];
        $block_id=$cliente["bloco_id_cliente"];
        $block_name=$cliente["bloco_name_cliente"];

        //$user_atrribute="&mensagem=".'Seu+pedido+esta+sendo+atendido!+:)';
        $user_atrribute="&mensagem=".str_replace(" ","+",$fraseBot);
        
       // $url = "https://api.chatfuel.com/bots/58a26cbee4b0bd0cc832db73/users/58a3030fe4b0bd0cca6dfb54/send?"
        //  ."chatfuel_token=BxXmPaeffbIPttnrUQ00m6jjbqHRpi4Mo2jhqeAnswxKz17iKEpKHxd4hqTbHCSB&chatfuel_block_id=58e65f8ae4b064edb6a68492&mensagem=Pronto";
        
        $url ="https://api.chatfuel.com/bots/";
        $url=$url.$bot_id."/";
        $url=$url."users/".$user_id."/send?chatfuel_token=";
        $url=$url.$token;
        $url=$url."&chatfuel_block_name=".$block_name;
        $url=$url.$user_atrribute;
        
        $post = curl_init();
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, true);
        curl_setopt($post, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($post);
        $resp = json_decode($result);
        //echo $resp->{'result'};//ok
       // echo $resp->{'success'};//true ou 1
        $resposta = "";
        if($resp != null && $resp->{'success'} == true){
            $resposta = "Sucesso";
        }else{
            $resposta = "Erro";
        }
        curl_close($post);
        return $resposta;
    }
    public function mensagemAoClienteFacebook($idFaceCliente, $mensagem, $pizzaria){
        $this->load->model("pizzaria/empresa_model");
        $this->load->model("gerencia/Cliente_pizzaria_model");
        $empresaLogada = $this->empresa_model->buscaPizzariaPorCodigo($pizzaria);
        $senhaCliente = $this->session->userdata("senhaCliente");
        $cliente = $this->Cliente_pizzaria_model->buscaPorEmailESenhaMD5($empresaLogada["email_pizzaria"], $senhaCliente);    
        if($cliente == null){
            throw new Exception("Erro ao enviar msg para cliente");
        }
        $token = $cliente["token_cliente"];
        $bot_id =$cliente["bot_id_cliente"];
        $block_id=$cliente["bloco_id_cliente"];
        $block_name=$cliente["bloco_name_cliente"];
        
        $user_id = $idFaceCliente;
        $mensagem = str_replace(" ","+",$mensagem);
        $user_atrribute="&mensagem=".$mensagem;
        
       // $url = "https://api.chatfuel.com/bots/58a26cbee4b0bd0cc832db73/users/58a3030fe4b0bd0cca6dfb54/send?"
        //  ."chatfuel_token=BxXmPaeffbIPttnrUQ00m6jjbqHRpi4Mo2jhqeAnswxKz17iKEpKHxd4hqTbHCSB&chatfuel_block_id=58e65f8ae4b064edb6a68492&mensagem=Pronto";
        
        $url ="https://api.chatfuel.com/bots/";
        $url=$url.$bot_id."/";
        $url=$url."users/".$user_id."/send?chatfuel_token=";
        $url=$url.$token;
        $url=$url."&chatfuel_block_name=".$block_name;
        $url=$url.$user_atrribute;
        
        $post = curl_init();
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, true);
        curl_setopt($post, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($post);
        $resp = json_decode($result);
        //echo $resp->{'result'};//ok
       // echo $resp->{'success'};//true ou 1
        $resposta = "";
        if($resp != null && $resp->{'success'} == true){
            $resposta = "Sucesso";
        }else{
            $resposta = "Erro";
        }
        curl_close($post);
        return $resposta;
    }
}
