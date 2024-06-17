<!-- judul -->
<div class="panel-top">
    <b class="text-green"><i class="fa fa-plus-circle text-green"></i> Tambah data</b>
</div>
<form id="form" action="./proses/prosestambah.php" method="POST">
    <input type="hidden" value="nilai" name="op">
    <div class="panel-middle">
        <div class="group-input">
            <label for="barang">Jenis Mata Kuliah</label>
            <select class="form-custom" required name="barang" id="pilihBarang">
                <option value="0" selected disabled>--Pilih Mata Kuliah--</option>
                <?php
                $query = "SELECT * FROM jenis_barang";
                $execute = $konek->query($query);
                if ($execute->num_rows > 0) {
                    while ($data = $execute->fetch_array(MYSQLI_ASSOC)) {
                        echo "<option value=\"$data[id_jenisbarang]\">$data[namaBarang]</option>";
                    }
                } else {
                    echo "<option disabled value=\"\">Belum ada Jenis Barang</option>";
                }
                ?>
            </select>
        </div>
        <div class="group-input">
            <label for="barang">Mahasiswa</label>
            <select class="form-custom" required name="barang" id="pilihMahasiswa" disabled>
                <option value="0" selected disabled>--Pilih Mahasiswa--</option>
            </select>
        </div>
        <?php
        $query = "SELECT * FROM kriteria";
        $execute = $konek->query($query);
        if ($execute->num_rows > 0) {
            while ($data = $execute->fetch_array(MYSQLI_ASSOC)) {
                echo "<div class=\"group-input\">";
                echo "<label for=\"nilai\">$data[namaKriteria]</label>";
                echo "<input type='hidden' value=\"$data[id_kriteria]\" name='kriteria[]'>";
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