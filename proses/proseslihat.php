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
    case 'nilai':
        if (!empty($id)) {
            $where="WHERE nilai_mahasiswa.id_matkul='$id'";
        }else{
            $where=null;
        }
        $query="SELECT id_nilaimahasiswa,id_mahasiswa,mahasiswa.namaMahasiswa AS namaMahasiswa,_matkul.id_matkul AS id_matkul,_matkul.namaMatkul AS namaMatkul FROM nilai_mahasiswa INNER JOIN mahasiswa USING(id_mahasiswa) INNER JOIN _matkul USING (id_matkul) $where GROUP BY id_mahasiswa ORDER BY id_matkul,id_mahasiswa ASC";
        $execute=$konek->query($query);
        if ($execute->num_rows > 0){
            $no=1;
            while($data=$execute->fetch_array(MYSQLI_ASSOC)){
               echo"
                <tr id='data'>
                    <td>$no</td>
                    <td>$data[namaMatkul]</td>
                    <td>$data[namaMahasiswa]</td>
                    <td>
                    <div class='norebuttom'>
                    <a class=\"btn btn-green\" href=\"./?page=penilaian&aksi=lihat&a=$data[id_mahasiswa]&b=$data[id_matkul]\"><i class='fa fa-eye'></i></a>
                    <a class=\"btn btn-light-green\" href=\"./?page=penilaian&aksi=ubah&a=$data[id_mahasiswa]&b=$data[id_matkul]\"><i class='fa fa-pencil-alt'></i></a>
                    <a class=\"btn btn-yellow\" data-a=\"$data[namaMatkul] - $data[namaMahasiswa]\" id='hapus' href='./proses/proseshapus.php/?op=nilai&id=".$data['id_mahasiswa']."'><i class='fa fa-trash-alt'></i></a></td>
                </div></tr>";
                $no++;
            }
        }else{
            echo "<tr><td  class='text-center text-green' colspan='4'><b>Kosong</b></td></tr>";
        }
    break;
    case 'mahasiswa':
        $query = "SELECT distinct id_mahasiswa FROM nilai_mahasiswa WHERE id_matkul=$id";
        $execute = $konek->query($query);
        echo "<option value='0' selected disabled>--Pilih Mahasiswa--</option>";
        if ($execute->num_rows > 0) {
            $query = "SELECT m.id_mahasiswa, m.namaMahasiswa FROM mahasiswa m LEFT JOIN nilai_mahasiswa nm ON m.id_mahasiswa = nm.id_mahasiswa AND nm.id_matkul = $id WHERE nm.id_mahasiswa IS NULL";
            $execute = $konek->query($query);
            if ($execute->num_rows > 0) {
                while ($data = $execute->fetch_array(MYSQLI_ASSOC)) {
                    echo "<option value=\"$data[id_mahasiswa]\">$data[namaMahasiswa]</option>";
                }
            } else {
                echo "<option disabled value=\"\">Semua Mahasiswa Sudah Ditambahkan</option>";
            }
        }else {
            $query = "SELECT * FROM mahasiswa";
            $execute = $konek->query($query);
            if ($execute->num_rows > 0){
                while ($data = $execute->fetch_array(MYSQLI_ASSOC)) {
                    echo "<option value=\"$data[id_mahasiswa]\">$data[namaMahasiswa]</option>";
                }
            } else {
                echo "<option disabled value=\"\">Belum ada Mahasiswa</option>";
            }
        }
}