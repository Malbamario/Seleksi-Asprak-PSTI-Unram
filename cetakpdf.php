<?php
require __DIR__.'/class/vendor/autoload.php';
use Dompdf\Dompdf;

session_start();
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Laporan Harian</title>
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
            padding: 0 0 10px 0;
            margin-bottom: 10px;
            border-bottom:solid 2px black;
            text-align: center;
        }
        .text-underline{
            text-decoration: underline;
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
        <h4>Sistem Pendukung Keputusan Pemilihan Supplier</h4>
        <h2>CV. BIMANTARA</h2>
    </div>
    <div id="judul" class="text-center">
        <p class="text-underline">Hasil Perhitungan</p>
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
// print_r(gettype($content));
$pdf = new Dompdf();
$pdf->loadHtml($content);
$pdf->setPaper('A4');
$pdf->render();
$pdf->stream();