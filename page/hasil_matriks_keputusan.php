<?php
require '../connect.php';
require '../function.php';
$cookiePilih=@$_COOKIE['pilih'];
//$cookiePilih=null;
if (isset($cookiePilih) && !empty($cookiePilih)){
/***************awal set variabel************/
    $valueMinMax=array(); $kriteriaArray=array(); $mahasiswaArray=array(); $forminmax=array(); $simpanNormalisasi=array(); $bobotArray=array();
    $querykriteria="SELECT namaKriteria FROM kriteria";//query tabel kriteria
    //query get data alternative
    $queryAlternative="SELECT mahasiswa.namaMahasiswa AS namaMahasiswa,id_mahasiswa FROM nilai_mahasiswa INNER JOIN mahasiswa USING(id_mahasiswa) WHERE id_matkul='$cookiePilih' GROUP BY id_mahasiswa ";
    //query get data bobot
    $queryBobot="SELECT id_kriteria,bobot FROM bobot_kriteria WHERE id_matkul='$cookiePilih'";
    //query get data nilai
    $indexArray=0;//variabel index array
/***************akhir set variabel************/
    $executeBobot=$konek->query($queryBobot);
    if ($executeBobot->num_rows>0) {
        while ($dataBobot=$executeBobot->fetch_array(MYSQLI_ASSOC)) {
            $bobotArray[$dataBobot['id_kriteria']]=@$dataBobot['bobot'];
        }
    }
/////////////////////////////////////////////////////////////////awal set header table matriks keputusan
$executeQueryTabel=$konek->query( $querykriteria);
echo "<div class='panel-middle'>";
echo "<p><h3>Matriks Keputusan</h3></p><table><tr><th rowspan='2'>Alternative</th><th colspan='$executeQueryTabel->num_rows'>Kriteria</th></tr><tr>";
while ($data=$executeQueryTabel->fetch_array(MYSQLI_ASSOC)){
    echo "<th>$data[namaKriteria]</th>";
    array_push($kriteriaArray,$data['namaKriteria']);//simpan nama nama kriteria ke array
}
echo "</tr>";
/////////////////////////////////////////////////////////////////akhir set header table matriks keputusan
/******awal isi table matriks keputusan****/
$executeGetAlternative=$konek->query($queryAlternative);
$colspan=$executeQueryTabel->num_rows+1;
if ($executeGetAlternative->num_rows > 0){
    while ($dataAlternative=$executeGetAlternative->fetch_array(MYSQLI_ASSOC)){
        echo"<tr id='data'><td>$dataAlternative[namaMahasiswa]</td>";
        $queryGetNilai="SELECT nilai_kriteria.nilai AS nilai,kriteria.sifat AS sifat,nilai_mahasiswa.id_kriteria AS id_kriteria FROM nilai_mahasiswa JOIN kriteria ON kriteria.id_kriteria=nilai_mahasiswa.id_kriteria JOIN nilai_kriteria ON nilai_kriteria.id_nilaikriteria=nilai_mahasiswa.id_nilaikriteria WHERE (id_matkul='$cookiePilih' AND id_mahasiswa='$dataAlternative[id_mahasiswa]')";
        $executeNilai=$konek->query($queryGetNilai);
        $i=0;
        while ($dataNilai=$executeNilai->fetch_array(MYSQLI_ASSOC)){
            echo "<td>$dataNilai[nilai]</td>";
            $nilaiMahasiswa[$indexArray][$i]=array("sifat"=>$dataNilai['sifat'],"id_kriteria"=>$dataNilai['id_kriteria']);
            $forminmax[$dataNilai['id_kriteria']][$indexArray]=$dataNilai['nilai'];
            $i++;
        }
            echo "</tr>";
            $mahasiswaArray[$indexArray]=["namaMahasiswa"=>$dataAlternative['namaMahasiswa'],"id_mahasiswa"=>$dataAlternative['id_mahasiswa']];
            $indexArray++;
    }
}else{
    echo "<tr class='text-center'><td colspan=\"$colspan\">Data Kosong</td></tr>";
}
echo "</table>";
/******akhir isi table matriks keputusan****/
/////////////////////////////////////////////////////////////////awal set header table normalisasi
echo "<p><h3>Normalisasi Matriks Keputusan</h3></p><table><tr><th rowspan='2'>Alternative</th><th colspan='$executeQueryTabel->num_rows'>Kriteria</th></tr><tr>";
foreach ($kriteriaArray as $namaKriteria) {
    echo "<th>$namaKriteria</th>";
}
echo "</tr>";
/////////////////////////////////////////////////////////////////akhir set header table normalisasi
/******awal isi table normalisasi****/
if (!empty($mahasiswaArray)){
    $simpanrangking=array();
    if (!empty($bobotArray)) {
        for ($j=0; $j< count($mahasiswaArray); $j++) { 
            echo "<tr id='data'><td>".$mahasiswaArray[$j]['namaMahasiswa']."</td>";
                for ($k=0; $k<count($nilaiMahasiswa[$j]) ; $k++) {
                    $idKriteria=$nilaiMahasiswa[$j][$k]['id_kriteria'];
                    echo "<td>".$hasil=normalisasi($forminmax[$idKriteria][$j],$forminmax[$idKriteria],$nilaiMahasiswa[$j][$k]["sifat"])."</td>";
                    $simpanrangking[$j][$k]=floatval($hasil)*$bobotArray[$idKriteria];
                }
            echo"</tr>";
        }
    }else{
        echo "<tr class='text-center'><td colspan=\"$colspan\"><b>Bobot Kriteria tidak boleh kosong</b></td></tr>";
    }
}else{
    echo "<tr class='text-center'><td colspan=\"$colspan\">Data Kosong</td></tr>";
}
echo "</table>";
/******akhir isi table normalisasi****/
/////////////////////////////////////////////////////////////////awal set header table perangkingan
echo "<p><h3>Normalisasi Matriks Keputusan</h3></p> <table> <tr><th rowspan='2'>Alternative</th><th colspan='$executeQueryTabel->num_rows'>Kriteria</th><th rowspan='2'>Hasil</th></tr><tr>";
foreach ($kriteriaArray as $namaKriteria) {
    echo "<th>$namaKriteria</th>";
}
/////////////////////////////////////////////////////////////////akhir set header table perangkingan
/******awal isi table perangkingan****/
if (!empty($mahasiswaArray)){
    if (!empty($bobotArray)) {
        for ($j=0; $j< count($mahasiswaArray); $j++) {
            $hasilakhir=0;
            echo "<tr id='data'><td>".$mahasiswaArray[$j]['namaMahasiswa']."</td>";
                for ($k=0; $k<count($simpanrangking[$j]) ; $k++) {
                    echo "<td>".$hasil=$simpanrangking[$j][$k]."</td>";
                    $hasilakhir+=floatval($hasil);
                }
                    echo "<td>".round($hasilakhir,3)."</td>";
            echo"</tr>";
        }
    }else{
        echo "<tr class='text-center'><td colspan=\"$colspan\"><b>Bobot Kriteria tidak boleh kosong</b></td></tr>";
    }
}else{
    echo "<tr class='text-center'><td colspan=\"$colspan\">Data Kosong</td></tr>";
}
echo "</table>";
/******akhir isi table perangkingan****/
    $queryHasil="SELECT hasil.hasil AS hasil,_matkul.namaMatkul,mahasiswa.namaMahasiswa AS namaMahasiswa FROM hasil JOIN _matkul ON _matkul.id_matkul=hasil.id_matkul JOIN mahasiswa ON mahasiswa.id_mahasiswa=hasil.id_mahasiswa WHERE hasil.hasil=(SELECT MAX(hasil) FROM hasil WHERE id_matkul='$cookiePilih')";
    $execute=$konek->query($queryHasil)->fetch_array(MYSQLI_ASSOC);
    echo "<p>Jadi rekomendasi pemilihan mahasiswa <i>$execute[namaMatkul]</i> jatuh pada <i>$execute[namaMahasiswa]</i> dengan Nilai <b>".round($execute['hasil'],3)."</b></p>";
echo "</div>";
}else{
    echo "<p class='text-center'><b>Pilih List Matkul, untuk menampilkan hasil</b></p>";
}