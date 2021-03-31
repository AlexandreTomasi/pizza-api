<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorPromocao
 *
 * @author 033234581
 */
class ControladorPromocao extends CI_Controller {

    //put your code here
    public function verificaUsuarioLogado() {
        $this->load->helper(array("currency"));
        $empresaLogada = $this->session->userdata("empresa_logada");
        if ($empresaLogada == null) {
            $this->session->unset_userdata("empresa_logada");
            $this->session->set_flashdata("sucess", "Sessão Expirada. Por favor logue novamente");
            redirect('/');
        }
    }

    public function buscarPromocaos() {
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Promocao_model");
        try {
            $empresaLogada = $this->session->userdata("empresa_logada");
            $promocaos = $this->Promocao_model->buscarPromocaoAtivas($empresaLogada["codigo_pizzaria"]);

            $dados = array("promocaos" => $promocaos);
            $this->load->helper(array("currency"));
            $this->load->view("pizzaria/ViewManterPromocao.php", $dados);
        } catch (Exception $e) {
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n" . date('Y-m-d H:i:s') . " - " . ($empresaLogada["codigo_pizzaria"]) . " " . $e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }

    public function buscarPromocaoPorID() {
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Promocao_model");
        try {
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode($meuPost);
            $codigo = $json->{"codigo_promocao"};
            $promocao = $this->Promocao_model->buscarPromocaoPorId($codigo, $empresaLogada["codigo_pizzaria"]);
//            $promocao["data_hora_inicio_promocao"] = date_format(date_create($promocao["data_hora_inicio_promocao"]), 'd/m/Y H:i');
//            $promocao["data_hora_fim_promocao"] = date_format(date_create($promocao["data_hora_fim_promocao"]), 'd/m/Y H:i');
            $json_str = json_encode($promocao);
            echo $json_str;
        } catch (Exception $e) {
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n" . date('Y-m-d H:i:s') . " - " . ($empresaLogada["codigo_pizzaria"]) . " " . $e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }

    public function confirmarAlterarPromocao() {
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Promocao_model");
        try {
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode($meuPost);

            $inicio = $this->formataIngles($json->{"data_hora_inicio_promocao"});
            $fim = $this->formataIngles($json->{"data_hora_fim_promocao"});
            if ($json->{"codigo_promocao"} != null && $json->{"codigo_promocao"} > 0) {
                $promocao = array(
                    "codigo_promocao" => $json->{"codigo_promocao"},
                    "descricao_promocao" => $json->{"descricao_promocao"},
                    "nome_promocao" => $json->{"nome_promocao"},
                    "tipo_desconto_promocao" => $json->{"tipo_desconto_promocao"},
                    "tipo_produto_promocao" => $json->{"tipo_produto_promocao"},
                    "data_hora_inicio_promocao" => $inicio,
                    "data_hora_fim_promocao" => $fim,
                    "frequencia_repeticao_promocao" => $json->{"frequencia_repeticao_promocao"},
                    "domingo_repeticao_promocao" => $json->{"domingo_repeticao_promocao"},
                    "segunda_repeticao_promocao" => $json->{"segunda_repeticao_promocao"},
                    "terca_repeticao_promocao" => $json->{"terca_repeticao_promocao"},
                    "quarta_repeticao_promocao" => $json->{"quarta_repeticao_promocao"},
                    "quinta_repeticao_promocao" => $json->{"quinta_repeticao_promocao"},
                    "sexta_repeticao_promocao" => $json->{"sexta_repeticao_promocao"},
                    "sabado_repeticao_promocao" => $json->{"sabado_repeticao_promocao"},
                    "pizzaria_promocao" => $empresaLogada["codigo_pizzaria"],
                    "ativo_promocao" => 1
                );
                $this->Promocao_model->alterarPromocao($promocao);
            } else {
                throw new Exception("Erro ao alterar dados.");
            }
        } catch (Exception $e) {
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n" . date('Y-m-d H:i:s') . " - " . ($empresaLogada["codigo_pizzaria"]) . " " . $e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }

    public function formataIngles($date) {
        $temp = explode(" ", $date);
        $temp1 = explode("/", $temp[0]);
        $fim = $temp1[2] . "-" . $temp1[1] . "-" . $temp1[0] . " " . $temp[1];
        return $fim;
    }

    public function incluirPromocao() {
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Promocao_model");
        try {
            $empresaLogada = $this->session->userdata("empresa_logada");

            $meuPost = file_get_contents("php://input");
            $json = json_decode($meuPost);

            $inicio = $this->formataIngles($json->{"data_hora_inicio_promocao"});
            $fim = $this->formataIngles($json->{"data_hora_fim_promocao"});
            $promocao = array(
                "descricao_promocao" => $json->{"descricao_promocao"},
                "nome_promocao" => $json->{"nome_promocao"},
                "tipo_desconto_promocao" => $json->{"tipo_desconto_promocao"},
                "tipo_produto_promocao" => $json->{"tipo_produto_promocao"},
                "data_hora_inicio_promocao" => $inicio,
                "data_hora_fim_promocao" => $fim,
                "frequencia_repeticao_promocao" => $json->{"frequencia_repeticao_promocao"},
                "domingo_repeticao_promocao" => $json->{"domingo_repeticao_promocao"},
                "segunda_repeticao_promocao" => $json->{"segunda_repeticao_promocao"},
                "terca_repeticao_promocao" => $json->{"terca_repeticao_promocao"},
                "quarta_repeticao_promocao" => $json->{"quarta_repeticao_promocao"},
                "quinta_repeticao_promocao" => $json->{"quinta_repeticao_promocao"},
                "sexta_repeticao_promocao" => $json->{"sexta_repeticao_promocao"},
                "sabado_repeticao_promocao" => $json->{"sabado_repeticao_promocao"},
                "pizzaria_promocao" => $empresaLogada["codigo_pizzaria"],
                "ativo_promocao" => 1
            );

            $promocaoResp = $this->Promocao_model->inserirPromocaoRetornandoPromocao($promocao);
            $json_str = json_encode($promocaoResp);
            echo $json_str;
        } catch (Exception $e) {
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n" . date('Y-m-d H:i:s') . " - " . ($empresaLogada["codigo_pizzaria"]) . " " . $e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }

    public function confirmarRemoverPromocao() {
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Promocao_model");
        try {
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode($meuPost);
            if ($json->{"codigo_promocao"} != null && $json->{"codigo_promocao"} > 1) {
                if (!($this->Promocao_model->removerPromocao($json->{"codigo_promocao"}))) {
                    throw new Exception("Erro ao excluir dados.");
                }
            } else {
                throw new Exception("Erro ao alterar dados.");
            }
        } catch (Exception $e) {
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n" . date('Y-m-d H:i:s') . " - " . ($empresaLogada["codigo_pizzaria"]) . " " . $e->getMessage());
            fclose($fp);
            throw new Exception($e->getMessage());
        }
    }

    public function buscarProdutosAtivosPromocao() {
        $this->verificaUsuarioLogado();
        $this->load->model("pizzaria/Promocao_model");
        $this->load->model("pizzaria/Bebida_model");
        $this->load->model("pizzaria/Pizza_extra_model");
        $this->load->model("pizzaria/Tipo_extra_model");
        $this->load->model("pizzaria/Pizza_tamanho_model");
        $this->load->model("pizzaria/Pizza_sabor_model");
        $this->load->model("pizzaria/Valor_pizza_model");
        $this->load->model("pizzaria/Taxa_entrega_model");
        try {
            $empresaLogada = $this->session->userdata("empresa_logada");
            $meuPost = file_get_contents("php://input");
            $json = json_decode($meuPost);
            if ($json->{"tipo_produto_promocao"} != null && $json->{"tipo_produto_promocao"} > 0) {
                $tipo_produto_promocao = intVal($json->{"tipo_produto_promocao"});
                $dados = array();

                switch ($tipo_produto_promocao) {
                    case 1://{codg: 1, desc: "Bebida"},
                        $bebidasAtivas = $this->Bebida_model->buscarBebidasPizzariaAtivas($empresaLogada['codigo_pizzaria']);
                        for ($i = 0; $i < count($bebidasAtivas); $i++) {
                            $codigo = $bebidasAtivas[$i]["codigo_bebida"];
                            $descricao = $bebidasAtivas[$i]["descricao_bebida"];
//                            $valor = doubleval($bebidasAtivas[$i]["preco_bebida"]);
                            $valor="";
                            $dados[] = array("codigo" => $codigo, "descricao" => $descricao, "valor" => $valor);
                        }
                        
                        break;
                    case 2://{codg: 2, desc: "Extra pizza"},
                        $extrasAtivos = $this->Pizza_extra_model->buscarTodosExtrasAtivos($empresaLogada['codigo_pizzaria']);
                        for ($i = 0; $i < count($extrasAtivos); $i++) {
                            $codigo = $extrasAtivos[$i]["codigo_extra_pizza"];
                            $descricao = $extrasAtivos[$i]["descricao_extra_pizza"];
//                            $valor = doubleval($extrasAtivos[$i]["preco_extra_pizza"]);
                            $valor="";
                            $dados[] = array("codigo" => $codigo, "descricao" => $descricao, "valor" => $valor);
                        }
                        
                        break;
                    case 3://{codg: 3, desc: "Tipo extra pizza"},
                        $tiposExtraAtivos = $this->Tipo_extra_model->buscarTipoExtraPizzariaAtivas($empresaLogada['codigo_pizzaria']);
                        for ($i = 0; $i < count($tiposExtraAtivos); $i++) {
                            $codigo = $tiposExtraAtivos[$i]["codigo_tipo_extra_pizza"];
                            $descricao = $tiposExtraAtivos[$i]["descricao_tipo_extra_pizza"];
//                            $valor = doubleval($tiposExtraAtivos[$i]["preco_tipo_extra_pizza"]);
                            $valor="";
                            $dados[] = array("codigo" => $codigo, "descricao" => $descricao, "valor" => $valor);
                        }
                        
                        break;
                    case 4://{codg: 4, desc: "Tamanho pizza"},
                        $tamanhosAtivos = $this->Pizza_tamanho_model->buscarTamanhos($empresaLogada['codigo_pizzaria']);
                        for ($i = 0; $i < count($tamanhosAtivos); $i++) {
                            $codigo = $tamanhosAtivos[$i]["codigo_tamanho_pizza"];
                            $descricao = $tamanhosAtivos[$i]["descricao_tamanho_pizza"];
//                            $valor = doubleval(0);
                            $valor="";
                            $dados[] = array("codigo" => $codigo, "descricao" => $descricao, "valor" => $valor);
                        }
                        
                        break;
                    case 5://{codg: 5, desc: "Sabor pizza"},
                        $saboresAtivos = $this->Pizza_sabor_model->buscarSaboresEmpresaAtivos($empresaLogada['codigo_pizzaria']);
                        for ($i = 0; $i < count($saboresAtivos); $i++) {
                            $codigo = $saboresAtivos[$i]["codigo_sabor_pizza"];
                            $descricao = $saboresAtivos[$i]["descricao_sabor_pizza"];
//                            $valor = doubleval(0);
                            $valor="";
                            $dados[] = array("codigo" => $codigo, "descricao" => $descricao, "valor" => $valor);
                        }
                        
                        break;
                    case 6://{codg: 6, desc: "Valor pizza"},
                        $valoresAtivos = $this->Valor_pizza_model->buscarTodosValoresPizzasAtivos($empresaLogada['codigo_pizzaria']);
                        for ($i = 0; $i < count($valoresAtivos); $i++) {
                            $codigo = $valoresAtivos[$i]["codigo_valor_pizza"];
                            $sabor = $this->Pizza_sabor_model->buscarSaborCodigo($valoresAtivos[$i]["sabor_pizza_valor_pizza"]);
                            $tamanho = $this->Pizza_tamanho_model->buscarTamanhoCodigo($valoresAtivos[$i]["tamanho_pizza_valor_pizza"]);
                            $descricao = $tamanho["descricao_tamanho_pizza"] . " ". $sabor["descricao_sabor_pizza"];
//                            $valor = doubleval($tamanho[$i]["preco_valor_pizza"]);
                            $valor="";
                            $dados[] = array("codigo" => $codigo, "descricao" => $descricao, "valor" => $valor);
                        }
                        
                        break;
                    case 7://{codg: 7, desc: "Taxa entrega"}
                        $taxasEntregaAtivos = $this->Taxa_entrega_model->buscarTaxaEntregaAtivas($empresaLogada['codigo_pizzaria']);
                        for ($i = 0; $i < count($taxasEntregaAtivos); $i++) {
                            $codigo = $taxasEntregaAtivos[$i]["codigo_taxa_entrega"];
                            $bdgerente = $this->load->database('gerencia', true);
                            $this->load->model("gerencia/Cidade_model");
                            $this->load->model("gerencia/Bairro_model");
                            $bairro = $this->Bairro_model->buscaBairrosPorCodigo($taxasEntregaAtivos[$i]["bairro_taxa_entrega"]);
                            $cidade = $this->Cidade_model->buscaCidadePorCodigo($bairro["cidade_bairro"]);
                                $descricao = $bairro["descricao_bairro"]  . ", ".  $cidade["descricao_cidade"];
//                            $valor = doubleval($taxasEntregaAtivos[$i]["preco_taxa_entrega"]);
                            $valor="";
                            $dados[] = array("codigo" => $codigo, "descricao" => $descricao, "valor" => $valor);
                        }
                        
                        break;
                    default :
                        throw new Exception("Erro ao carregar dados. Tipo produto inexistente");
                }
                $this->load->helper(array("currency"));
                $json_str = json_encode($dados);
                echo $json_str;
            }
        } catch (Exception $ex) {
            
        }
    }

}
