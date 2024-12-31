<?php
$html = "               Idolmart               
              Jalan Caman              
                Bekasi                
STRUK   :BOSOL-00010I4DUQ      10:18:47
TANGGAL :22-Jul-22                 asp
=======================================
Nama Barang Qty  Harga  Disc      Total
=======================================
Paket Hanasui Flawless Glow 10 Set + Pouch
            1  99,500      0     99,500
=======================================
TOTAL                            99,500
DISKON                                0
GRAND TOTAL                      99,500
BAYAR D/C                             0
BAYAR CASH                      100,000
INFAK                                 0
KEMBALI                             500
=======================================
DPP :     90,455    PPN :      9,045
=======================================
    SELAMAT ANDA MENDAPATKAN POINT    
MEMBER                          Irham
POINT                             498
***************************************
      NPWP :31.188.370.6-407.000      
            IDOLMART Group            
***************************************
            #TERIMA KASIH#            
 BARANG YANG SUDAH DIBELI TIDAK DAPAT 
         DITUKAR/DIKEMBALIKAN         
***************************************
";


$html = "
            #TERIMA KASIH#            
 BARANG YANG SUDAH DIBELI TIDAK DAPAT 
         DITUKAR/DIKEMBALIKAN         
***************************************
";

// $str = file_get_contents('http://example.com/example.json/');


// $html = $_POST['html'];

$data = file_get_contents('http://localhost/pi/api/cek_printer.php');
// echo $data;
if($data == 'vsc'){
	
	$address1 = file_get_contents('http://localhost/pi/api/cek_address1.php');
	$address2 = file_get_contents('http://localhost/pi/api/cek_address2.php');
	
	
	$html = str_replace("       Idolmart", "Idolmart", $html);
	$html = str_replace("       IDOLMART", "IDOLMART", $html);
	
	
	
	$html = str_replace("       ".$address1, $address1, $html);
	$html = str_replace("       ".$address2, $address2, $html);
	
	$html = str_replace("Nama Barang", "Nm Brg", $html);
	$html = str_replace("Disc  ", "Disc", $html);
	
	for ($x = 0; $x <= 30; $x++) {
	$html = str_replace("     ".$x." ", $x." ", $html);
	}
	
	
	$html = str_replace("TOTAL       ", "TOTAL", $html);
	$html = str_replace("DISKON       ", "DISKON", $html);
	$html = str_replace("GRAND TOTAL", "GRAND TOTAL", $html);
	$html = str_replace("BAYAR D/C       ", "BAYAR D/C", $html);
	$html = str_replace("BAYAR CASH       ", "BAYAR CASH", $html);
	$html = str_replace("INFAK       ", "INFAK", $html);
	$html = str_replace("KEMBALI       ", "KEMBALI", $html);
	$html = str_replace("MEMBER       ", "MEMBER", $html);
	$html = str_replace("POINT       ", "POINT", $html);
	$html = str_replace("     NPWP", "NPWP", $html);
	$html = str_replace(":31.188.370.6-407.000    ", ":31.188.370.6-407.000", $html);
	$html = str_replace("     IDOLMART Group   ", "IDOLMART Group", $html);
	$html = str_replace("     #TERIMA KASIH#   ", "  #TERIMA KASIH#", $html);
	$html = str_replace("     DITUKAR/DIKEMBALIKAN   ", "  DITUKAR/DIKEMBALIKAN", $html);
	$html = str_replace("BARANG YANG SUDAH DIBELI TIDAK DAPAT", "   BARANG YANG SUDAH DIBELI 
          TIDAK DAPAT", $html);
	$html = str_replace("=======================================", "================================", $html);
	$html = str_replace("***************************************", "********************************", $html);
	
}

$ip_printer = $_POST['ip_printer'];


require '../vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

try {
	
	
	
	
	
	
	$connector = new FilePrintConnector("//localhost/pos");

    $printer = new Printer($connector);
	$printer -> initialize();


	$printer -> setFont(Printer::FONT_B);
	$printer -> setTextSize(1, 1);
	$printer -> text($html);
	

    $printer -> cut();
    
    $printer -> close();
	
	
	 echo "Proses Print\n";
} catch (Exception $e) {
    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
}

?>