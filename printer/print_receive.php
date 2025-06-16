<?php
require '../vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;


header('Content-Type: application/json');

// Mengambil data yang akan dicetak
$html = $_POST['html'];
$ip_printer = $_POST['ip_printer']; // jika windows

// Deteksi OS
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $os = 'windows';
} else {
    $os = 'linux';
}

$response = ['result' => 0, 'msg' => 'Gagal'];

if ($os == 'windows') {
    // Windows Printing

    try {
        $connector = new FilePrintConnector("//" . $ip_printer . "/pos");

        $printer = new Printer($connector);
        $printer->initialize();

        $printer->setFont(Printer::FONT_B);
        $printer->setTextSize(1, 1);
        $printer->text($html);

        $printer->cut();
        $printer->close();

        $response = ['result' => 1, 'msg' => 'Cetakan sukses (Windows)'];
    } catch (Exception $e) {
        $response = ['result' => 0, 'msg' => $e->getMessage()];
    }
} else {
    // Linux Printing
    // Mengirim perintah raw ke lpr
    $html = chr(27) . chr(33) . chr(1) . $html;
    $html .= '\r\n';
    $html .= '\r\n';
    $html .= '\r\n';
    $html .= chr(29) . "V" . 0;

    $cmd = 'echo "' . $html . '" | lpr -o raw';
    $child = shell_exec($cmd);
    $response = ['result' => 1, 'msg' => 'Cetakan sukses (Linux)', 'child' => $child];
}

echo json_encode($response);
?>