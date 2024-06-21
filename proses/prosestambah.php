<?php
require '../connect.php';
require '../class/crud.php';
$crud=new crud($konek);

if ($_SERVER['REQUEST_METHOD']=='GET') {
    $id=@$_GET['id'];
    $op=@$_GET['op'];
}else if ($_SERVER['REQUEST_METHOD']=='POST'){
    $id=@$_POST['id'];
    $op=@$_POST['op'];
}
$matkul=@$_POST['matkul'];
$mahasiswa=@$_POST['mahasiswa'];
$kriteria=@$_POST['kriteria'];
$sifat=@$_POST['sifat'];
$nilai=@$_POST['nilai'];
$keterangan=@$_POST['keterangan'];
$bobot=@$_POST['bobot'];
switch ($op){
    case 'matkul'://tambah data matkul
        $query="INSERT INTO _matkul (namaMatkul) VALUES ('$matkul')";
        $crud->addData($query,$konek);
    break;
    case 'mahasiswa': //tambah data mahasiswa
        $query="INSERT INTO mahasiswa (namaMahasiswa) VALUES ('$mahasiswa')";
        $crud->addData($query,$konek);
    break;
    case 'kriteria'://tambah data kriteria
        $cek="SELECT namaKriteria FROM kriteria WHERE namaKriteria='$kriteria'";
        $query=null;
        $query="INSERT INTO kriteria (namaKriteria,sifat) VALUES ('$kriteria','$sifat')";
        $crud->multiAddData($cek,$query,$konek);
    break;
    case 'bobot'://tambah data bobot
        $cek="SELECT id_bobotkriteria FROM bobot_kriteria WHERE id_matkul='$matkul'";
        $query=null;
        for ($i=0;$i<count($kriteria);$i++){
            $query.="INSERT INTO bobot_kriteria (id_matkul,id_kriteria,bobot) VALUES ('$matkul','$kriteria[$i]','$bobot[$i]');";
        }
        $crud->multiAddData($cek,$query,$konek);
    break;
    case 'nilai'://tambah data nilai
        $cek="SELECT id_mahasiswa FROM nilai_mahasiswa WHERE id_mahasiswa='$mahasiswa'";
        $query=null;
        for ($i=0;$i<count($nilai);$i++){
            $query.="INSERT INTO nilai_mahasiswa (id_mahasiswa,id_matkul,id_kriteria,nilai) VALUES ('$mahasiswa','$matkul','$kriteria[$i]','$nilai[$i]');";
        }
        $crud->multiAddData($cek,$query,$konek);
    break;
}