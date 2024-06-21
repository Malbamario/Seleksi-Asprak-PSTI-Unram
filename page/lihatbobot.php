<?php
$id = htmlspecialchars(@$_GET['id']);
$querylihat = "SELECT id_matkul, bobot, id_bobotkriteria, kriteria.namaKriteria AS namaKriteria FROM bobot_kriteria INNER JOIN kriteria USING(id_kriteria) WHERE id_matkul='$id'";
$execute2 = $konek->query($querylihat);
if ($execute2->num_rows == 0) {
    header('location:./?page=bobot');
}
?>
<!-- judul -->
<div class="panel-top">
    <b class="text-green">Detail data</b>
</div>
<form>
    <div class="panel-middle">
        <div class="group-input">
            <?php
            $query = "SELECT namaMatkul FROM _matkul WHERE id_matkul='$id'";
            $execute = $konek->query($query);
            $data = $execute->fetch_array(MYSQLI_ASSOC);
            ?>
            <div class="group-input">
                <label for="matkul">Matkul</label>
                <input class="form-custom" value="<?php echo $data['namaMatkul']; ?>" disabled type="text" autocomplete="off" required name="matkul" id="matkul" placeholder="matkul">
            </div>
        </div>
        <?php
        $execute2 = $konek->query($querylihat);
        while ($data = $execute2->fetch_array(MYSQLI_ASSOC)) {
            echo "<div class=\"group-input\">
                    <label for=\"$data[namaKriteria]\">$data[namaKriteria]</label>
                    <input type='hidden' value=\"$data[id_bobotkriteria]\" name=\"id[]\">
                    <input type=\"number\" class=\"form-custom\" step=\"0.01\" disabled required name=\"bobot[]\" id=\"$data[namaKriteria]\" value=\"$data[bobot]\" min=\"0\" max=\"1\">
                  </div>
            ";
        }
        ?>
    </div>
    <div class="panel-bottom">
    </div>
</form>