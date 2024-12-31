<?php
// $html = "PRINT PRINT
// PRINT
// PRINTPRINT
// PRINT
// PRINT
// PRINT";



$html = $_POST['html'];
$ip_printer = $_POST['ip_printer'];


require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

try {
	
	$connector = new FilePrintConnector("//localhost/pos");
	// $connector = new FilePrintConnector("//10.0.47.2/pos");

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