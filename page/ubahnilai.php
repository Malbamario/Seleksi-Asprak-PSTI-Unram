<?php
$a = htmlspecialchars(@$_GET['a']);
$b = htmlspecialchars(@$_GET['b']);
$getData = array();
$querylihat = "SELECT nilai FROM nilai_mahasiswa WHERE id_mahasiswa='$a' AND id_matkul='$b'";
$getnilaiKriteria = $konek->query($querylihat);
while ($data = $getnilaiKriteria->fetch_array(MYSQLI_ASSOC)) {
    array_push($getData, $data['nilai']);
}
?>
<div class="panel-top panel-top-edit">
    <b><i class="fa fa-pencil-alt"></i> Ubah data</b>
</div>
<form id="form" action="./proses/prosesubah.php" method="POST">
    <input type="hidden" value="nilai" name="op">
    <div class="panel-middle">
        <div class="group-input">
            <?php
            $query = "SELECT namaMahasiswa FROM mahasiswa WHERE id_mahasiswa='$a'";
            $execute = $konek->query($query);
            $data = $execute->fetch_array(MYSQLI_ASSOC);
            ?>
            <div class="group-input">
                <label for="matkul">Nama Mahasiswa</label>
                <input class="form-custom" value="<?php echo $data['namaMahasiswa']; ?>" disabled type="text" autocomplete="off" required name="matkul" id="matkul">
            </div>
        </div>
        <div class="group-input">
            <?php
            $query = "SELECT namaMatkul FROM _matkul WHERE id_matkul='$b'";
            $execute = $konek->query($query);
            $data = $execute->fetch_array(MYSQLI_ASSOC);
            ?>
            <div class="group-input">
                <label for="matkul">Matkul</label>
                <input class="form-custom" value="<?php echo $data['namaMatkul']; ?>" disabled type="text" autocomplete="off" required name="matkul" id="matkul" placeholder="matkul">
            </div>
        </div>
        <?php
        $query = "SELECT namaKriteria, id_nilaimahasiswa, id_kriteria FROM nilai_mahasiswa INNER JOIN kriteria USING(id_kriteria) WHERE id_mahasiswa='$a'";
        $execute = $konek->query($query);
        if ($execute->num_rows > 0) {
            while ($data = $execute->fetch_array(MYSQLI_ASSOC)) {
                echo "<div class=\"group-input\">";
                echo "<label for=\"nilai\">$data[namaKriteria]</label>";
                echo "<input type='hidden' value=\"$data[id_nilaimahasiswa]\" name=\"id[]\">";
                echo "<input type=\"number\" class=\"form-custom\" step=\"0.01\" required name=\"nilai[]\" id=\"$data[namaKriteria]\" placeholder=\"Masukkan nilai untuk $data[namaKriteria]\" min=\"0\" max=\"100\">";
                echo "</div>";
            }
        }
        ?>
    </div>
    <div class="panel-bottom">
        <button type="submit" id="buttonsimpan" class="btn btn-green"><i class="fa fa-save"></i> Simpan</button>
        <button type="reset" id="buttonreset" class="btn btn-second">Reset</button>
    </div>
</form>