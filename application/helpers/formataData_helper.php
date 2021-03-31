<?php
// para usar o helper $this->load->helper("formataData");
// 'd/m/Y H:i:s para 'Y-m-d H:i:s'
function formataDataPortuguesEmIngles($date){
        $temp = explode(" ", $date);
        $temp1 = explode("/", $temp[0]);
        $fim = $temp1[2]."-".$temp1[1]."-".$temp1[0]." ".$temp[1];
        return $fim;
}