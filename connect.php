<?php
$konek=new mysqli('localhost','root','','spk_asprak_topsis');
if ($konek->connect_errno){
    "Database Error".$konek->connect_error;
}
?>