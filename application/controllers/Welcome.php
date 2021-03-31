<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('errors/BotaoFlutuante');
	}
        public function relogio()
	{
		$this->load->view('errors/ViewRelogio');
	}
        public function horario()
	{
            $extra = '@1';
            $temp = explode('@', $extra);
            echo count($temp);
            echo '====';
            echo $temp[0];
            echo '====';
            echo $temp[count($temp)-1];
		$this->load->view('errors/ViewHorario');
	}
        
    public function buscarTaxaEntrega(){
        $this->load->model("pizzaria/Taxa_entrega_model");
        $this->load->model("pizzaria/Taxa_entrega_model");
        try{
            $empresaLogada = $this->session->userdata("empresa_logada");
            $taxas = $this->Taxa_entrega_model->buscarTaxaEntregaAtivasUniaoBairro($empresaLogada["codigo_pizzaria"]);
            $empresaLogada = $this->session->userdata("empresa_logada");           
            $bairros = $this->Taxa_entrega_model->buscarBairrosSemTaxaEntrega($empresaLogada["codigo_pizzaria"]);  

            $dados = array("produto" => $taxas, "bairro" =>$bairros);    
            $this->load->helper(array("currency"));
            $this->load->view("errors/TesteSelect.php", $dados); 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".($empresaLogada["codigo_pizzaria"])." ".$e->getMessage());
            fclose($fp); 
            throw new Exception($e->getMessage());
        }
    }
    
        function array_sort($array, $elemento)
        {
            for($i=0;$i < count($array); $i++){
                for($j=0;$j < count($array); $j++){
                    if($array[$i][$elemento] < $array[$j][$elemento]){
                        $new_array = $array[$i][$elemento];
                        $array[$i][$elemento] = $array[$j][$elemento];
                        $array[$j][$elemento] = $new_array;
                    }
                }
            }
            return $array;
        }
}