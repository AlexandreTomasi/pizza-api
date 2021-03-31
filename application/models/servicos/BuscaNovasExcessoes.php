<?php

class BuscaNovasExcessoes extends CI_Model {
    //put your code here
    public function buscaExcessaoHoraAtual($arquivo){
        $ponteiro = fopen ($arquivo,"r");
        $texto = "";
        //date('Y-m-d H:i:s');
        while (!feof ($ponteiro)) {
            $linha = fgets($ponteiro,4096);
            $dt = substr($linha,0,19);
            if(strlen($dt) == 19){
                $temp = explode(" ",$dt);
                $dia = explode("-",$temp[0]);
                $hora = explode(":",$temp[1]);
                if($dia[0] == date("Y") && $dia[1] == date("m") && $dia[2] == date("d")
                        && $hora[0] == date("H")){
                        $texto = $texto.$linha;
                }
            }
        }
        fclose ($ponteiro);
        return $texto;
    }
}
