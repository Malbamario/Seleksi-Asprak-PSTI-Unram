<?php
require '../connect.php';
require '../class/crud.php';
if ($_SERVER['REQUEST_METHOD']=='GET') {
    $id=@$_GET['id'];
    $op=@$_GET['op'];
}else if ($_SERVER['REQUEST_METHOD']=='POST'){
    $id=@$_POST['id'];
    $op=@$_POST['op'];
}
$crud=new crud();
switch ($op){
    case 'matkul':
        $query="DELETE FROM _matkul WHERE id_matkul='$id'";
        $crud->delete($query,$konek);
        break;
    case 'mahasiswa':
        $query="DELETE FROM mahasiswa WHERE id_mahasiswa='$id'";
        $crud->delete($query,$konek);
        break;
    case 'kriteria':
        $query="DELETE FROM kriteria WHERE id_kriteria='$id'";
        $crud->delete($query,$konek);
        break;
    case 'bobot':
        $query="DELETE FROM bobot_kriteria WHERE id_matkul='$id'";
        $crud->delete($query,$konek);
        break;
    case 'nilai':
        $query="DELETE FROM nilai_mahasiswa WHERE id_mahasiswa='$id'";
        $crud->delete($query,$konek);
        break;
}