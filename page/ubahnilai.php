<?php
$a = htmlspecialchars(@$_GET['a']);
$b = htmlspecialchars(@$_GET['b']);
$getData = array();
$querylihat = "SELECT nilai FROM nilai_supplier WHERE id_supplier='$a' AND id_jenisbarang='$b'";
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
            $query = "SELECT namaSupplier FROM supplier WHERE id_supplier='$a'";
            $execute = $konek->query($query);
            $data = $execute->fetch_array(MYSQLI_ASSOC);
            ?>
            <div class="group-input">
                <label for="jenisbarang">Nama Supplier</label>
                <input class="form-custom" value="<?php echo $data['namaSupplier']; ?>" disabled type="text" autocomplete="off" required name="jenisbarang" id="jenisbarang">
            </div>
        </div>
        <div class="group-input">
            <?php
            $query = "SELECT namaBarang FROM jenis_barang WHERE id_jenisbarang='$b'";
            $execute = $konek->query($query);
            $data = $execute->fetch_array(MYSQLI_ASSOC);
            ?>
            <div class="group-input">
                <label for="jenisbarang">Jenis Barang</label>
                <input class="form-custom" value="<?php echo $data['namaBarang']; ?>" disabled type="text" autocomplete="off" required name="jenisbarang" id="jenisbarang" placeholder="jenisbarang">
            </div>
        </div>
        <?php
        $query = "SELECT namaKriteria, id_nilaisupplier, id_kriteria FROM nilai_supplier INNER JOIN kriteria USING(id_kriteria) WHERE id_supplier='$a'";
        $execute = $konek->query($query);
        if ($execute->num_rows > 0) {
            while ($data = $execute->fetch_array(MYSQLI_ASSOC)) {
                echo "<div class=\"group-input\">";
                echo "<label for=\"nilai\">$data[namaKriteria]</label>";
                echo "<input type='hidden' value=\"$data[id_nilaisupplier]\" name=\"id[]\">";
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