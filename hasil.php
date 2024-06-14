<?php
require 'connect.php';
require 'class/saw.php';
$cookiePilih=@$_COOKIE['pilih'];
if (isset($cookiePilih) and !empty($cookiePilih)) {
    $saw=new saw();
    $saw->setconfig($konek,$cookiePilih);
    $kriteria = $saw->getKriteria();
    $alternatives = $saw->getAlternative();
?>
<div id="Matriks Keputusan">
    <h3>Matriks Keputusan</h3>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Alternative</th>
                <th colspan="<?php echo count($kriteria) ?>">Kriteria</th>
            </tr>
            <tr>
                <?php
                foreach ($kriteria as $key) {
                    echo "<th>{$key['namaKriteria']}</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($alternatives as $key) {
             echo "<tr id='data'>";
                echo "<td>".$key['namaSupplier']."</td>";
                $no=0;
                foreach ($saw->getNilaiMatriks($key['id_supplier']) as $data) {
                    echo "<td>$data[nilai]</td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<br>
<div id="Normalisasi Matriks Keputusan">
    <h3>Normalisasi Matriks Keputusan</h3>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Alternative</th>
                <th colspan="<?php echo count($kriteria) ?>">Kriteria</th>
            </tr>
            <tr>
                <?php
                foreach ($kriteria as $key) {
                    echo "<th>{$key['namaKriteria']}</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            // Membuat array untuk menyimpan nilai kriteria
            $matriksKeputusan = [];
            foreach ($alternatives as $alternative) {
                $id_supplier = $alternative['id_supplier'];
                foreach ($saw->getNilaiMatriks($id_supplier) as $data) {
                    $matriksKeputusan[$id_supplier][$data['id_kriteria']] = $data['nilai'];
                }
            }

            // Menghitung nilai normalisasi
            $normalisasi = [];
            foreach($kriteria as $krit) {
                $sumSquare = 0;
                foreach ($alternatives as $alternative) {
                    $id_supplier = $alternative['id_supplier'];
                    $sumSquare += pow($matriksKeputusan[$id_supplier][$krit['id_kriteria']], 2);
                }
                $sqrtSumSquare = sqrt($sumSquare);
                foreach ($alternatives as $alternative) {
                    $id_supplier = $alternative['id_supplier'];
                    $normalisasi[$id_supplier][$krit['id_kriteria']] = $matriksKeputusan[$id_supplier][$krit['id_kriteria']] / $sqrtSumSquare;
                }
            }

            // Mencetak tabel dengan nilai normalisasi
            foreach ($alternatives as $alternative) {
                $id_supplier = $alternative['id_supplier'];
                echo "<tr id='data'>";
                echo "<td>".$alternative['namaSupplier']."</td>";
                foreach($kriteria as $krit) {
                    $hasil = number_format((float)$normalisasi[$id_supplier][$krit['id_kriteria']], 4, '.', '');
                    echo "<td>{$hasil}</td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<br>
<div id="Normalisasi Matriks Keputusan Terbobot">
    <h3>Normalisasi Matriks Keputusan Terbobot</h3>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Alternative</th>
                <th colspan="<?php echo count($kriteria) ?>">Kriteria</th>
            </tr>
            <tr>
                <?php
                foreach ($kriteria as $key) {
                    echo "<th>{$key['namaKriteria']}</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            // Menghitung nilai normalisasi
            $normalisasi_terbobot = [];
            foreach($kriteria as $krit) {
                foreach ($alternatives as $alternative) {
                    $id_supplier = $alternative['id_supplier'];
                    $normalisasi_terbobot[$id_supplier][$krit['id_kriteria']] = $normalisasi[$id_supplier][$krit['id_kriteria']]*$saw->getBobot($krit['id_kriteria']);
                }
            }

            // Mencetak tabel dengan nilai normalisasi
            foreach ($alternatives as $alternative) {
                $id_supplier = $alternative['id_supplier'];
                echo "<tr id='data'>";
                echo "<td>".$alternative['namaSupplier']."</td>";
                foreach($kriteria as $krit) {
                    $hasil = $normalisasi_terbobot[$id_supplier][$krit['id_kriteria']];
                    $hasil = number_format((float)$hasil, 4, '.', '');
                    echo "<td>$hasil</td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<br>
<div id="Solusi Ideal Positif dan Negatif">
    <h3>Solusi Ideal Positif dan Negatif</h3>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Solusi</th>
                <th colspan="<?php echo count($kriteria) ?>">Kriteria</th>
            </tr>
            <tr>
                <?php
                foreach ($kriteria as $key) {
                    echo "<th>{$key['namaKriteria']}</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            // Menghitung nilai normalisasi
            $solusi_ideal = [];
            foreach($kriteria as $krit) {
                $data_kriteria = [];
                foreach ($alternatives as $alternative) {
                    $id_supplier = $alternative['id_supplier'];
                    $data_kriteria[$id_supplier] = $normalisasi_terbobot[$id_supplier][$krit['id_kriteria']];
                }

                if($krit['sifat']=="Benefit"){
                    $solusi_ideal['A+'][$krit['id_kriteria']] = max($data_kriteria);
                    $solusi_ideal['A-'][$krit['id_kriteria']] = min($data_kriteria);
                } else{
                    $solusi_ideal['A+'][$krit['id_kriteria']] = min($data_kriteria);
                    $solusi_ideal['A-'][$krit['id_kriteria']] = max($data_kriteria);
                }
            }

            // Mencetak tabel dengan nilai normalisasi
            foreach ($solusi_ideal as $key=>$solusi) {
                echo "<tr id='data'>";
                echo "<td>".$key."</td>";
                foreach($kriteria as $krit) {
                    $hasil = number_format((float)$solusi[$krit['id_kriteria']], 4, '.', '');
                    echo "<td>$hasil</td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<br>
<div id="Jarak Alternatif dengan Solusi Ideal Positif dan Negatif">
    <h3>Jarak Alternatif dengan Solusi Ideal Positif dan Negatif</h3>
    <table>
        <thead>
            <tr>
                <th>Alternative</th>
                <th>D+</th>
                <th>D-</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Menghitung nilai normalisasi
            $jarak_solusi = [];
            foreach ($alternatives as $alternative) {
                $id_supplier = $alternative['id_supplier'];

                $sumSquarePos = 0;
                $sumSquareNeg = 0;

                foreach($kriteria as $krit) {
                    $sumSquarePos += pow($solusi_ideal['A+'][$krit['id_kriteria']]-$normalisasi_terbobot[$id_supplier][$krit['id_kriteria']], 2);
                    $sumSquareNeg += pow($normalisasi_terbobot[$id_supplier][$krit['id_kriteria']]-$solusi_ideal['A-'][$krit['id_kriteria']], 2);
                }

                $jarak_solusi[$id_supplier]['D+'] = sqrt($sumSquarePos);
                $jarak_solusi[$id_supplier]['D-'] = sqrt($sumSquareNeg);
            }

            // Mencetak tabel dengan nilai normalisasi
            foreach ($alternatives as $alternative) {
                $id_supplier = $alternative['id_supplier'];
                $distPos = number_format((float)$jarak_solusi[$id_supplier]['D+'], 4, '.', '');
                $distNeg = number_format((float)$jarak_solusi[$id_supplier]['D-'], 4, '.', '');
                echo "<tr id='data'>";
                echo "<td>".$alternative['namaSupplier']."</td>";
                echo "<td>$distPos</td>";
                echo "<td>$distNeg</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<br>
<div id="Perangkingan">
    <h3>Perangkingan</h3>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Alternative</th>
                <th>Nilai Preferensi</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $preferensi = [];
            foreach ($alternatives as $alternative) {
                $id_supplier = $alternative["id_supplier"];
                $preferensi[$alternative["namaSupplier"]] = $jarak_solusi[$id_supplier]['D-']/($jarak_solusi[$id_supplier]['D+']+$jarak_solusi[$id_supplier]['D-']);
            }
            arsort($preferensi);
            
            $no=1;
            foreach ($preferensi as $key=>$hasil) {
                $hasil_compact = number_format((float)$hasil, 4, '.', '');
                echo "<tr id='data'>";
                echo "<td>{$no}</td>";
                echo "<td>{$key}</td>";
                echo "<td>{$hasil_compact}</td>";
                echo "</tr>";
                $no++;
                foreach($alternatives as $alternative){
                    if($key==$alternative['namaSupplier']) $saw->simpanHasil($alternative['id_supplier'],$hasil);
                }
            }
            ?>
        </tbody>
    </table>
</div>
<?php
//cetak hasil
    $saw->getHasil();
}