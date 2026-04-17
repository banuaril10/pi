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
            <!-- <form method="get" class="row mb-4" id="filterForm">
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
            </form> -->

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
                                <td>
                                    <span class="badge bg-primary"><?= $count_sku; ?> SKU</span>
                                </td>
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
                                <button class="btn btn-sm btn-danger float-end" id="btn-cetak-tinta-selected"><b>CETAK HARGA</b></button>
                                <button class="btn btn-sm btn-warning float-end me-2" id="btn-cetak-promo-selected"><b>CETAK PROMO</b></button>
                                <button class="btn btn-sm btn-success float-end me-2" id="btn-cetak-planogram-selected"><b>CETAK PLANOGRAM</b></button>
                            </h5>
                        </div>
                        <div class="card-body">
                            <p style="color: red; font-weight: bold">Satu kertas terdiri dari 32 tag</p>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="detailTable">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkall-detail"></th>
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
                                                        <input type="checkbox" class="checkbox-reguler-detail" value="<?php echo $checkbox_value_reguler; ?>">
                                                        <?php if ($ada_diskon): ?>
                                                            <br>
                                                            <input type="checkbox" class="checkbox-promo-detail" value="<?php echo $checkbox_value_promo; ?>">
                                                            <small class="text-success">Promo</small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo $no++; ?></td>
                                                    <td><?php echo $row['sku']; ?></td>
                                                    <td><?php echo $row['barcode']; ?></td>
                                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                    <td><?php echo number_format($harga_normal, 0, ',', '.'); ?></td>
                                                    <td>
                                                        <?php 
                                                        if ($ada_diskon) {
                                                            echo '<span class="badge bg-danger">' . number_format($harga_discount, 0, ',', '.') . '</span>';
                                                            echo '<br><small class="text-muted">Diskon: ' . $disk . '</small>';
                                                        } else {
                                                            echo '<span class="badge bg-secondary">Tidak Ada Promo</span>';
                                                        }
                                                        ?>
                                                     </td>
                                                    <td><?php echo $rack_name; ?></td>
                                                    <td><?php echo $row['tag']; ?></td>
                                                    <td><input type="number" id="copy_detail_<?php echo $row['sku']; ?>" value="1" style="width:60px" class="form-control"></td>
                                                    <td><?php echo date('d-m-Y', strtotime($selected_tanggal)); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="11" class="text-center text-warning">
                                                    SKU tidak ditemukan di database pos_mproduct
                                                </td>
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
<script src="https://intransit.idolmartidolaku.com/apiidolmart/pricetag/price-reguler-store-apps.js?id=dwa"></script>
<script src="https://intransit.idolmartidolaku.com/apiidolmart/pricetag/price-promo-perubahan-harga.js?id=dwa"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<script type="text/javascript">
$(document).ready(function() {
    // Inisialisasi DataTable untuk tabel tanggal
    $('#tanggalTable').DataTable({
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
        pageLength: 10,
        order: [[1, 'asc']]
    });
    
    // Inisialisasi DataTable untuk detail table (jika ada)
    if ($('#detailTable').length) {
        $('#detailTable').DataTable({
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
            pageLength: 10,
            order: [[1, 'asc']]
        });
    }
    
    // Check all untuk detail table
    $("#checkall-detail").on("click", function() {
        var checkboxes = document.querySelectorAll('.checkbox-reguler-detail');
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
    
    // Cetak harga reguler (selected)
    $('#btn-cetak-tinta-selected').on('click', function() {
        var selected = [];
        $('.checkbox-reguler-detail:checked').each(function() {
            var val = $(this).val();
            var skuValue = val.split('|')[0];
            var copyValue = $('#copy_detail_' + skuValue).length ? $('#copy_detail_' + skuValue).val() : 1;
            selected.push(val + '|' + copyValue);
        });
        
        if(selected.length === 0) {
            alert("Pilih minimal 1 item untuk dicetak!");
            return false;
        }
        
        if(typeof cetakPriceTag === 'function') {
            cetakPriceTag(selected);
        } else {
            console.log("Data yang akan dicetak:", selected);
            alert("Cetak reguler: " + selected.length + " item dipilih");
        }
    });
    
    // Cetak harga promo (selected)
    $('#btn-cetak-promo-selected').on('click', function() {
        var selected = [];
        $('.checkbox-promo-detail:checked').each(function() {
            var val = $(this).val();
            var skuValue = val.split('|')[0];
            var copyValue = $('#copy_detail_' + skuValue).length ? $('#copy_detail_' + skuValue).val() : 1;
            selected.push(val + '|' + copyValue);
        });
        
        if(selected.length === 0) {
            alert("Tidak ada item promo yang dipilih!");
            return false;
        }
        
        if(typeof cetakPriceTagPromo === 'function') {
            cetakPriceTagPromo(selected);
        } else if(typeof cetakPromo === 'function') {
            cetakPromo(selected);
        } else {
            alert("Cetak Promo: " + selected.length + " item dipilih");
        }
    });
    
    // Cetak planogram
    $('#btn-cetak-planogram-selected').on('click', function() {
        var selected = [];
        $('.checkbox-reguler-detail:checked').each(function() {
            selected.push($(this).val());
        });
        
        if(selected.length === 0) {
            alert("Pilih minimal 1 item untuk dicetak planogram!");
            return false;
        }
        
        if(typeof cetakPlanogram === 'function') {
            cetakPlanogram(selected);
        } else {
            alert("Cetak planogram: " + selected.length + " item dipilih");
        }
    });
});
</script>

</div>
<?php include "components/fff.php"; ?>