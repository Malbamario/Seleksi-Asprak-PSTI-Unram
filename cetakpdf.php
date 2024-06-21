<?php
require __DIR__.'/class/vendor/autoload.php';
require 'connect.php';
use Dompdf\Dompdf;

session_start();
$cookiePilih=@$_COOKIE['pilih'];
$query="SELECT*FROM _matkul WHERE id_matkul=$cookiePilih";
$execute=$konek->query($query);
$matkul="";
while ($data=$execute->fetch_array(MYSQLI_ASSOC)){
    $matkul = $data['namaMatkul'];
}
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Hasil Seleksi</title>
    <style>
        *{
            padding: 0;
            margin: 0;
        }
        body{
            margin: 1cm;
        }
        #header{
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
            text-align: center;
        }
        #logofakultas img{
            height: 80px;
        }
        #isi{
            margin-left: 5%;
        }
        .text-center{
            text-align: center;
        }
        #judul{
            margin: 10px 0px 30px 0px;
        }
table {
  text-align: center;
  border-collapse: collapse;
  margin: 7px 0px 15px 0px;}
  table tr{
  border: none;
  }
  table th {
    padding: 5px 8px;
    border: solid 1px #e9ecef;
    border-bottom: solid 2px #e9ecef;
    font-weight: bold; }
  table td {
    border: solid 1px #e9ecef;
    padding: 5px; }
  table tr#data:nth-child(odd) {
    background-color: #f2f2f2; }
    </style>
    </head>
    <body>
    <div id="header">
        <div id="logofakultas">
            <!-- <img src="asset\image\cropped-LOGO-UNRAM.png"> -->
        </div>
        <div id="isi" align="center">
            <b>
                <span>
                    KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI<br>
                    UNIVERSITAS MATARAM
                </span><br>
                <h4>FAKULTAS TEKNIK</h4>
            </b>
            <small>
                <i>Jln. Majapahit No. 62 Mataram NTB, phone: (0370) 631712 web: if.unram.ac.id</i>
            </small>
        </div>
    </div>
    <div style="clear:both"></div>
    <hr style="margin: 10px 0 10px 0;">
    <div id="judul" class="text-center">
        <p>Hasil Perhitungan Seleksi Asisten Praktikum <?= $matkul ?></p>
    </div>
    <div id="body">
        <?php
        include 'hasil.php';
        ?>
    </div>
    <page_footer>
        <i style="font-size:9pt;">dicetak oleh <b><?php echo $_SESSION['user'];  ?></b></i>
    </page_footer>
    </body>
</html>
<?php
$content=ob_get_clean();
// print_r($content);
$pdf = new Dompdf();
$pdf->loadHtml($content);
$pdf->setPaper('A4');
$pdf->render();
$date = date('d-m-Y');
$pdf->stream("Hasil Perhitungan Seleksi Asisten Praktikum $matkul - $date");