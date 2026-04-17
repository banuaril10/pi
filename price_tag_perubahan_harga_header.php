<?php include "config/koneksi.php"; ?>
<?php include "components/main.php"; ?>
<?php include "components/sidebar.php"; ?>

<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>
<div id="app">
<div id="main">

<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
<?php include "components/hhh.php"; ?>

<!------ CONTENT AREA ------->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>DAFTAR SKU PERUBAHAN HARGA - CETAK PRICE TAG</h4>
            </div>
            <div class="card-body">

            <!-- FILTER TANGGAL RANGE -->
            <form method="get" class="row mb-4" id="filterForm">
                <div class="col-md-3">
                    <label for="start" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="start" name="start" value="<?= isset($_GET['start']) ? $_GET['start'] : date('Y-m-d') ?>">
                </div>
                <div class="col-md-3">
                    <label for="end" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="end" name="end" value="<?= isset($_GET['end']) ? $_GET['end'] : date('Y-m-d') ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">FILTER</button>
                </div>
            </form>

            <?php
                // Full URL API
                $api_base_url = "http://localhost/pi/api/cyber/sync_perubahan_harga.php";
                
                // Fungsi ambil data dari API
                function getApiData($tanggal, $api_base_url) {
                    $url = $api_base_url . "?tanggal=" . $tanggal;
                    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                    
                    $response = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    
                    if ($response && $httpCode == 200) {
                        return json_decode($response, true);
                    }
                    return null;
                }
                
                // Ambil tanggal dari parameter GET start & end
                $tanggal_mulai = isset($_GET['start']) ? $_GET['start'] : date('Y-m-d');
                $tanggal_akhir = isset($_GET['end']) ? $_GET['end'] : date('Y-m-d');
                $selected_tanggal = isset($_GET['tgl']) ? $_GET['tgl'] : '';
                
                // Panggil API untuk mendapatkan data SKU dan HARGA
                $all_sku_data = [];
                $detail_per_tanggal = [];
                
                // Buat array tanggal range
                $start = new DateTime($tanggal_mulai);
                $end = new DateTime($tanggal_akhir);
                $end->modify('+1 day');
                
                $interval = new DateInterval('P1D');
                $daterange = new DatePeriod($start, $interval, $end);
                
                // Ambil data SKU dari setiap tanggal dalam range
                foreach ($daterange as $date) {
                    $tgl = $date->format('Y-m-d');
                    $data = getApiData($tgl, $api_base_url);
                    
                    if ($data && $data['status'] == 'OK' && !empty($data['data'])) {
                        $detail_per_tanggal[$tgl] = $data['data'];
                        foreach ($data['data'] as $item) {
                            $parts = explode('|', $item);
                            $sku = $parts[0];
                            $harga = isset($parts[1]) ? $parts[1] : 0;
                            
                            if (!isset($all_sku_data[$sku])) {
                                $all_sku_data[$sku] = $harga;
                            }
                        }
                    }
                }
            ?>
            
            <!-- TAMPILAN GROUP BY TANGGAL (TABEL) -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-hover" id="tanggalTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jumlah SKU</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($detail_per_tanggal)): ?>
                            <?php 
                            $no_tgl = 1;
                            foreach($detail_per_tanggal as $tgl => $items): 
                                $tgl_format = date('d-m-Y', strtotime($tgl));
                                $count_sku = count($items);
                                $is_active = ($selected_tanggal == $tgl) ? 'style="background-color: #e3f2fd;"' : '';
                            ?>
                            <tr <?= $is_active; ?>>
                                <td><?= $no_tgl++; ?></td>
                                <td><?= $tgl_format; ?></td>
                                <td><span class="badge bg-primary"><?= $count_sku; ?> SKU</span></td>
                                <td>
                                    <a href="?start=<?= $tanggal_mulai; ?>&end=<?= $tanggal_akhir; ?>&tgl=<?= $tgl; ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Lihat Detail
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-danger">
                                    Tidak ada data SKU dari API pada range tanggal tersebut
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- DETAIL TABEL PER TANGGAL (muncul jika ada yang diklik) -->
            <?php if (!empty($selected_tanggal) && isset($detail_per_tanggal[$selected_tanggal])): 
                $selected_items = $detail_per_tanggal[$selected_tanggal];
                $selected_sku_list = [];
                $selected_price_data = [];
                
                foreach ($selected_items as $item) {
                    $parts = explode('|', $item);
                    $sku = $parts[0];
                    $harga = isset($parts[1]) ? $parts[1] : 0;
                    $selected_sku_list[] = $sku;
                    $selected_price_data[$sku] = $harga;
                }
                
                $sku_in_selected = !empty($selected_sku_list) ? "'" . implode("','", $selected_sku_list) . "'" : "''";
            ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                Detail SKU - <?= date('d-m-Y', strtotime($selected_tanggal)); ?>
                                <button class="btn btn-sm btn-danger float-end" id="btn-cetak-tinta"><b>CETAK HARGA</b></button>
                                <button class="btn btn-sm btn-warning float-end me-2" id="btn-cetak-promo"><b>CETAK PROMO</b></button>
                                <button class="btn btn-sm btn-success float-end me-2" id="btn-cetak-planogram"><b>CETAK PLANOGRAM</b></button>
                            </h5>
                        </div>
                        <div class="card-body">
                            <p style="color: red; font-weight: bold">Satu kertas terdiri dari 32 tag</p>

                            <button class="btn btn-sm btn-secondary mb-3" id="checkall">CHECK ALL</button>
                            <button class="btn btn-sm btn-secondary mb-3" id="checkallpromo">CHECK ALL PROMO</button>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th>Pilih</th>
                                            <th>No</th>
                                            <th>SKU</th>
                                            <th>Barcode</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Price Discount</th>
                                            <th>Rack Name</th>
                                            <th>Tag</th>
                                            <th>Copy</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($selected_sku_list)) {
                                        $sql_list = "SELECT DISTINCT DATE(NOW()) as tgl_sekarang, a.sku, a.name, a.rack, a.barcode, a.tag 
                                                    FROM pos_mproduct a 
                                                    WHERE a.sku IN (" . $sku_in_selected . ") OR a.barcode IN (" . $sku_in_selected . ") 
                                                    ORDER BY a.name";
                                        
                                        $no = 1;
                                        $query_result = $connec->query($sql_list);
                                        
                                        if ($query_result && $query_result->rowCount() > 0) {
                                            foreach ($query_result as $row) {
                                                $harga_normal = isset($selected_price_data[$row['sku']]) ? $selected_price_data[$row['sku']] : 0;
                                                $harga_discount = $harga_normal;
                                                $tgl_fromdate = date('d/m/Y');
                                                $tgl_todate = date('d/m/Y', strtotime('+30 days'));
                                                $disk = 0;
                                                $ada_diskon = false;
                                                
                                                // CEK DISKON
                                                $cek_disc = "SELECT fromdate, todate, discount FROM pos_mproductdiscount 
                                                            WHERE fromdate <= '" . date('Y-m-d') . "' 
                                                            AND todate >= '" . date('Y-m-d') . "' 
                                                            AND sku = '" . $row['sku'] . "' 
                                                            AND discount > 0 
                                                            ORDER BY discount DESC LIMIT 1";
                                                $disc_result = $connec->query($cek_disc);
                                                
                                                if ($disc_result && $disc_result->rowCount() > 0) {
                                                    foreach ($disc_result as $row_dis) {
                                                        $disk = $row_dis['discount'];
                                                        $harga_discount = $harga_normal - $disk;
                                                        $tgl_todate = date('d/m/Y', strtotime($row_dis['todate']));
                                                        $tgl_fromdate = date('d/m/Y', strtotime($row_dis['fromdate']));
                                                        $ada_diskon = true;
                                                    }
                                                }
                                                
                                                $rack_name = isset($row['rack']) ? $row['rack'] : '';
                                                
                                                $checkbox_value_reguler = $row['sku'] . '|' . $row['name'] . '|' . $harga_normal . '|' . $row['tgl_sekarang'] . '|' . $rack_name . '|' . $row['tag'] . '|' . $harga_normal . '|' . $tgl_todate . '|' . $row['tag'] . '|' . $row['barcode'] . '|' . $tgl_fromdate;
                                                $checkbox_value_promo = $row['sku'] . '|' . $row['name'] . '|' . $harga_normal . '|' . $row['tgl_sekarang'] . '|' . $rack_name . '|' . $row['tag'] . '|' . $harga_discount . '|' . $tgl_todate . '|' . $row['tag'] . '|' . $row['barcode'] . '|' . $tgl_fromdate;
                                                ?>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="checkbox-reguler" value="<?php echo $checkbox_value_reguler; ?>">
                                                        <?php if ($ada_diskon): ?>
                                                            <br>
                                                            <input type="checkbox" class="checkbox-promo" value="<?php echo $checkbox_value_promo; ?>">
                                                            <small class="text-success">Promo</small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <td><?php echo $no++; ?></div>
                                                    <td><?php echo $row['sku']; ?></div>
                                                    <td><?php echo $row['barcode']; ?></div>
                                                    <td><?php echo htmlspecialchars($row['name']); ?></div>
                                                    <td><?php echo number_format($harga_normal, 0, ',', '.'); ?></div>
                                                    <td>
                                                        <?php 
                                                        if ($ada_diskon) {
                                                            echo '<span class="badge bg-danger">' . number_format($harga_discount, 0, ',', '.') . '</span>';
                                                            echo '<br><small class="text-muted">Diskon: ' . $disk . '</small>';
                                                        } else {
                                                            echo '<span class="badge bg-secondary">Tidak Ada Promo</span>';
                                                        }
                                                        ?>
                                                     </div>
                                                    <td><?php echo $rack_name; ?></div>
                                                    <td><?php echo $row['tag']; ?></div>
                                                    <td><input type="number" id="copy_<?php echo $row['sku']; ?>" value="1" style="width:60px" class="form-control copy-input" data-sku="<?php echo $row['sku']; ?>"></div>
                                                    <td><?php echo date('d-m-Y', strtotime($selected_tanggal)); ?></div>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="11" class="text-center text-warning">
                                                    SKU tidak ditemukan di database pos_mproduct
                                                </div>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            </div>
        </div>
    </div>
</div>

</div>
</div>

<script src="styles/js/jsbarcode.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<script>
// Fungsi format Rupiah
function formatRupiah(angka) {
    if (!angka) return '0';
    var number_string = angka.toString().replace(/[^,\d]/g, ""),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);
    
    if (ribuan) {
        var separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }
    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return rupiah;
}

// Generate Barcode
function generateBarcodeDataURL(text) {
    const canvas = document.createElement("canvas");
    JsBarcode(canvas, text, {
        format: "CODE128",
        width: 1.5,
        height: 20,
        displayValue: false,
        margin: 0,
    });
    return canvas.toDataURL("image/png");
}

// Fungsi Cetak Reguler dengan COPY
function cetakPriceTagWithCopy(selectedData) {
    let text = "<table border='0'>";
    let x = 0;
    
    for (let i = 0; i < selectedData.length; i++) {
        let copyCount = parseInt(selectedData[i].split('|')[selectedData[i].split('|').length - 1]) || 1;
        let originalValue = selectedData[i].substring(0, selectedData[i].lastIndexOf('|'));
        let res = originalValue.split("|");
        
        for (let c = 0; c < copyCount; c++) {
            let sku = res[0];
            let name = res[1];
            let normal = res[2];
            let dates = res[3];
            let rack = res[4];
            let shortcut = res[5];
            let afterdiscount = res[6];
            let tag = res[7];
            let kodetoko_tgl = res[8];
            let barcode = res[9];
            
            if (shortcut === "undefined" || shortcut === "null" || shortcut === "") {
                var sc = "";
            } else {
                var sc = "/" + shortcut;
            }
            
            let panjangharga = parseInt(normal);
            let sizeprice = "49px";
            let margin_bot = "0";
            if (panjangharga > 999999) {
                sizeprice = "40px";
                margin_bot = "9px";
            }
            
            let newStr = "";
            if (sku != "") newStr += sku;
            if (barcode != "") newStr += "/" + barcode;
            if (rack != "") newStr += "/" + rack.replace("-", "_");
            
            let barcodeDataURL = generateBarcodeDataURL(sku);
            let barcodeImage = "<div style='display:flex; justify-content:flex-end; margin-top:4px;'>" +
                "<img src='" + barcodeDataURL + "' style='width:60px; height:15px;' />" +
                "</div>";
            
            text += "<td style='border: 0.5px solid #000'><div style='margin:5px 5px 0 5px; color: black; width: 177px; height: 121px; font-family: Calibri; '>" +
                "<div style='height:30px; text-align: left; font-size: 10px'><b>" + name.toUpperCase() + "</b></div>" +
                "<label style='color: #000; margin: -10px 0 " + margin_bot + " 0; float: right; font-size: " + sizeprice + "'>" +
                "<label style='font-size: 10px'><b>Rp </b></label><b>" + formatRupiah(normal) + "</b></label>" +
                "<hr style='width: 100%;border-top: solid 1px #000 !important; background-color:black; border:none; height:1px; margin:15px 0 0 0;'>" +
                "<label style='text-align: center; font-size: 9px;'>" + newStr + "</label>" +
                barcodeImage +
                "</div></td>";
            
            if ((x + 1) % 4 == 0 && x !== 0) {
                text += "</tr><tr>";
            }
            x++;
        }
    }
    
    text += "</tr></table>";
    
    let mywindow = window.open("", "my div", "height=600,width=800");
    mywindow.document.write("<style>@media print{@page {size: portrait; width: 216mm;height: 280mm;margin-top: 15;margin-right: 2;margin-left: 2; padding: 0;} margin: 0; padding: 0;} table { page-break-inside:auto }tr{ page-break-inside:avoid; page-break-after:auto }</style>");
    mywindow.document.write(text);
    setTimeout(function() {
        mywindow.print();
        mywindow.close();
    }, 500);
    return true;
}

// Fungsi Cetak Promo dengan COPY
function cetakPromoWithCopy(selectedData) {
    let text = "<table border='0'>";
    let x = 0;
    
    for (let i = 0; i < selectedData.length; i++) {
        let copyCount = parseInt(selectedData[i].split('|')[selectedData[i].split('|').length - 1]) || 1;
        let originalValue = selectedData[i].substring(0, selectedData[i].lastIndexOf('|'));
        let res = originalValue.split("|");
        
        for (let c = 0; c < copyCount; c++) {
            let sku = res[0];
            let name = res[1];
            let normal = res[2];
            let dates = res[3];
            let rack = res[4];
            let shortcut = res[5];
            let afterdiscount = res[6];
            let todate = res[7];
            let tag = res[8];
            let barcode = res[9];
            let fromdate = res[10];
            
            // Hanya cetak jika ada diskon (afterdiscount < normal)
            if (parseInt(afterdiscount) >= parseInt(normal)) continue;
            
            let panjangharga = parseInt(afterdiscount);
            let sizeprice = "48px";
            if (panjangharga > 999999) sizeprice = "43px";
            
            let newStr = "";
            if (sku != "") newStr += sku;
            if (barcode != "") newStr += "/" + barcode;
            if (rack != "") newStr += "/" + rack.replace("-", "_");
            
            let barcodeDataURL = generateBarcodeDataURL(sku);
            let barcodeImage = "<div style='position:absolute; bottom:2px; right:2px;'>" +
                "<img src='" + barcodeDataURL + "' width='60' height='15' />" +
                "</div>";
            
            text += "<td style='border: 0.5px solid #000; position: relative;'><div style='margin:5px 5px 0 5px; color: black; width: 177px; height: 121px; font-family: Calibri; position: relative;'>" +
                "<div style='height:25px; text-align: left; font-size: 10px'><b>" + name.toUpperCase() + "</b></div>" +
                "<label style='text-align: left; font-size: 10px'><b>Rp </b></label>" +
                "<label style='text-align: left; font-size: 20px; text-decoration: line-through;'><b>" + formatRupiah(normal) + "</b></label>" +
                "<label style='float: right !important; font-size: 7px;'>" + fromdate + " s.d. " + todate + "</label>" +
                "<label style='margin: -10px 0 0 0; float: right; font-size: " + sizeprice + "'><label style='font-size: 10px'><b>Rp </b></label><b>" + formatRupiah(afterdiscount) + "</b></label>" +
                "<hr style='width: 100%;border-top: solid 1px #000 !important; background-color:black; border:none; height:1px; margin:1.5px 0 0 0;'>" +
                "<label style='text-align: center; font-size: 9px; margin-top: -10px'>" + newStr + "</label>" +
                barcodeImage +
                "</div></td>";
            
            if ((x + 1) % 4 == 0 && x !== 0) {
                text += "</tr><tr>";
            }
            x++;
        }
    }
    
    text += "</tr></table>";
    
    if (text === "<table border='0'></tr></table>") {
        alert("Tidak ada item promo yang valid untuk dicetak!");
        return false;
    }
    
    let mywindow = window.open("", "my div", "height=600,width=800");
    mywindow.document.write("<style>@media print{@page {size: portrait; width: 216mm;height: 280mm;margin-top: 15;margin-right: 2;margin-left: 2; padding: 0;} margin: 0; padding: 0;} table { page-break-inside:auto }tr{ page-break-inside:avoid; page-break-after:auto }</style>");
    mywindow.document.write(text);
    setTimeout(function() {
        mywindow.print();
        mywindow.close();
    }, 500);
    return true;
}

$(document).ready(function() {
    // Inisialisasi DataTable untuk tabel tanggal
    $('#tanggalTable').DataTable({
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
        pageLength: 10,
        order: [[1, 'asc']]
    });
    
    // Inisialisasi DataTable untuk detail table
    if ($('#example').length) {
        $('#example').DataTable({
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
            pageLength: 10,
            order: [[1, 'asc']],
            destroy: true
        });
    }
    
    // Check all button
    $("#checkall").on("click", function() {
        var checkboxes = document.querySelectorAll('.checkbox-reguler');
        var allChecked = true;
        for (var i = 0; i < checkboxes.length; i++) {
            if (!checkboxes[i].checked) {
                allChecked = false;
                break;
            }
        }
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = !allChecked;
        }
    });

        $("#checkallpromo").on("click", function() {
        var checkboxes = document.querySelectorAll('.checkbox-promo');
        var allChecked = true;
        for (var i = 0; i < checkboxes.length; i++) {
            if (!checkboxes[i].checked) {
                allChecked = false;
                break;
            }
        }
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = !allChecked;
        }
    });
    
    // Cetak harga reguler (dengan copy)
    $('#btn-cetak-tinta').on('click', function() {
        var selected = [];
        $('.checkbox-reguler:checked').each(function() {
            var val = $(this).val();
            var skuValue = val.split('|')[0];
            var copyValue = $('#copy_' + skuValue).length ? $('#copy_' + skuValue).val() : 1;
            selected.push(val + '|' + copyValue);
        });
        
        if(selected.length === 0) {
            alert("Pilih minimal 1 item untuk dicetak!");
            return false;
        }
        
        cetakPriceTagWithCopy(selected);
    });
    
    // Cetak harga promo (dengan copy)
    $('#btn-cetak-promo').on('click', function() {
        var selected = [];
        $('.checkbox-promo:checked').each(function() {
            var val = $(this).val();
            var skuValue = val.split('|')[0];
            var copyValue = $('#copy_' + skuValue).length ? $('#copy_' + skuValue).val() : 1;
            selected.push(val + '|' + copyValue);
        });
        
        if(selected.length === 0) {
            alert("Tidak ada item promo yang dipilih!\n\nCentang checklist Promo pada item yang memiliki diskon.");
            return false;
        }
        
        cetakPromoWithCopy(selected);
    });
    
    // Cetak planogram
    $('#btn-cetak-planogram').on('click', function() {
        var selected = [];
        $('.checkbox-reguler:checked').each(function() {
            selected.push($(this).val());
        });
        
        if(selected.length === 0) {
            alert("Pilih minimal 1 item untuk dicetak planogram!");
            return false;
        }
        
        alert("Cetak planogram: " + selected.length + " item dipilih (fungsi menyusul)");
    });
});
</script>

</div>
<?php include "components/fff.php"; ?>