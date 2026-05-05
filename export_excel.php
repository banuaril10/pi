<?php
require 'vendor/autoload.php';
include "config/koneksi.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;

if(isset($_GET['export_excel']) && $_GET['export_excel'] == 1 && isset($_GET['m_pi'])) {
    
    $m_pi = $_GET['m_pi'];
    
    // Ambil data header dari m_pi (name dan rack_name)
    $query_header = "select name, rack_name from m_pi where m_pi_key = '$m_pi' and status = '1'";
    $header_data = $connec->query($query_header)->fetch(PDO::FETCH_ASSOC);
    $doc_number = $header_data['name'];
    $rack_name = $header_data['rack_name'];
    
    // Query ambil data detail
    $query = "select 
                m_piline.sku, 
                m_piline.barcode, 
                pos_mproduct.name as product_name, 
                m_piline.qtycount 
              from m_pi 
              inner join m_piline on m_pi.m_pi_key = m_piline.m_pi_key 
              left join pos_mproduct on m_piline.sku = pos_mproduct.sku
              where m_pi.m_pi_key = '$m_pi' and m_pi.status = '1'
              order by m_piline.qtyerp desc";
    
    $result = $connec->query($query);
    
    // Buat spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // === HEADER SECTION ===
    // Title
    $sheet->setCellValue('A1', 'INVENTORY COUNTING REPORT');
    $sheet->mergeCells('A1:D1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    // Document Number
    $sheet->setCellValue('A2', 'Document No:');
    $sheet->setCellValue('B2', $doc_number);
    $sheet->mergeCells('B2:D2');
    $sheet->getStyle('A2')->getFont()->setBold(true);
    
    // Rack Name
    $sheet->setCellValue('A3', 'Rack Name:');
    $sheet->setCellValue('B3', $rack_name);
    $sheet->mergeCells('B3:D3');
    $sheet->getStyle('A3')->getFont()->setBold(true);
    
    // Export Date
    $sheet->setCellValue('A4', 'Export Date:');
    $sheet->setCellValue('B4', date('Y-m-d H:i:s'));
    $sheet->mergeCells('B4:D4');
    $sheet->getStyle('A4')->getFont()->setBold(true);
    
    // Spacer row
    $sheet->setCellValue('A5', '');
    
    // === TABLE HEADER ===
    $sheet->setCellValue('A6', 'NO');
    $sheet->setCellValue('B6', 'SKU');
    $sheet->setCellValue('C6', 'NAMA PRODUCT');
    $sheet->setCellValue('D6', 'COUNTER');
    
    // Style table header
    $headerStyle = $sheet->getStyle('A6:D6');
    $headerStyle->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));
    $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('4CAF50');
    $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    // === DATA ===
    $row = 7;
    $no = 1;
    while ($data = $result->fetch(PDO::FETCH_ASSOC)) {
        $barcode = !empty($data['barcode']) ? " (" . $data['barcode'] . ")" : "";
        $sheet->setCellValue('A' . $row, $no);
        $sheet->setCellValue('B' . $row, $data['sku'] . $barcode);
        $sheet->setCellValue('C' . $row, $data['product_name']);
        $sheet->setCellValue('D' . $row, $data['qtycount']);
        
        // Center align untuk kolom NO dan COUNTER
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $row++;
        $no++;
    }
    
    // === BORDER ===
    $borderStyle = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000']
            ]
        ]
    ];
    $sheet->getStyle('A6:D' . ($row-1))->applyFromArray($borderStyle);
    
    // Auto size kolom
    foreach(range('A','D') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    
    // Export file
    $filename = 'Inventory_Counting_' . $rack_name . '_' . date('Ymd_His') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
?>