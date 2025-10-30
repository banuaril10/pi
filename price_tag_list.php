<?php 
include "config/koneksi.php"; 
include "components/main.php"; 
include "components/sidebar.php"; 
?>

<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <h3>Daftar Price Tag Header</h3>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5>List Price Tag Headers</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="headerTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Header Number</th>
                            <th>Status</th>
                            <th>Total Items</th>
                            <th>Dibuat Oleh</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sql = "SELECT * FROM price_tag_headers ORDER BY id DESC";
                        $no = 1;
                        foreach ($connec->query($sql) as $row) { ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($row['header_number']); ?></td>
                                <td>
                                    <?php if ($row['status'] == 'DRAFT') { ?>
                                        <span class="badge bg-warning">DRAFT</span>
                                    <?php } else { ?>
                                        <span class="badge bg-success"><?= htmlspecialchars($row['status']); ?></span>
                                    <?php } ?>
                                </td>
                                <td><?= $row['total_items']; ?></td>
                                <td><?= htmlspecialchars($row['created_by']); ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>
                                <td><?= nl2br(htmlspecialchars($row['keterangan'])); ?></td>
                                <td>
                                    <a href="print_promo.php?id=<?= $row['id']; ?>" target="_blank" class="btn btn-sm btn-danger">
                                        <i class="bi bi-printer"></i> Cetak Promo
                                    </a>
                                    <a href="print_reguler.php?id=<?= $row['id']; ?>" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="bi bi-printer"></i> Cetak Reguler
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function () {
    $('#headerTable').DataTable({
        pageLength: 25,
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'Semua']
        ]
    });
});
</script>
