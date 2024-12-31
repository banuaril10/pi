<?php
require  '../vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
// $html = "PRINT PRINT
// PRINT
// PRINTPRINT
// PRINT
// PRINT
// PRINT";



$html = $_POST['html'];
// $data = file_get_contents('http://localhost/pi/api/cek_printer.php');
// echo $data;

$ip_printer = $_POST['ip_printer'];




try {
	
	
	$connector = new FilePrintConnector("//".$ip_printer."/pos");

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