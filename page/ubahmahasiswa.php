<?php
$id = htmlspecialchars(@$_GET['id']);
$query = "SELECT * FROM mahasiswa WHERE id_mahasiswa='$id'";
$execute = $konek->query($query);
if ($execute->num_rows > 0) {
    $data = $execute->fetch_array(MYSQLI_ASSOC);
} else {
    header('location:./?page=mahasiswa');
}
?>
<div class="panel-top panel-top-edit">
    <b><i class="fa fa-pencil-alt"></i> Ubah data</b>
</div>
<form id="form" method="POST" action="./proses/prosesubah.php">
    <input type="hidden" name="op" value="mahasiswa">
    <input type="hidden" name="id" value="<?php echo $data['id_mahasiswa']; ?>">
    <div class="panel-middle">
        <div class="group-input">
            <label for="mahasiswa">Nama Mahasiswa :</label>
            <input type="text" value="<?php echo $data['namaMahasiswa']; ?>" class="form-custom" required autocomplete="off" placeholder="Nama Mahasiswa" id="mahasiswa" name="mahasiswa">
        </div>
    </div>
    <div class="panel-bottom">
        <button type="submit" id="buttonsimpan" class="btn btn-green"><i class="fa fa-save"></i> Simpan</button>
        <button type="reset" id="buttonreset" class="btn btn-second">Reset</button>
    </div>
</form>