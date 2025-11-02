<?php include "config/koneksi.php"; ?>
<?php include "components/main.php"; ?>
<?php include "components/sidebar.php"; ?>
<style>
.grid-container {
  display: grid;
  grid-template-columns: auto auto auto auto;
  background-color: #2196F3;
  padding: 10px;
}
.grid-item {
  background-color: rgba(255, 255, 255, 0.8);
  border: 1px solid rgba(0, 0, 0, 0.8);
  padding: 20px;
  font-size: 30px;
  text-align: center;
}
</style>
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
				<h4>DAFTAR ITEMS IDOLSCAN</h4>
			</div>
			<div class="card-body">
			<div class="tables">			
				<div class="table-responsive bs-example widget-shadow">	
				<p id="notif1" style="color: red; font-weight: bold"></p>		

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
                    foreach ($connec->query($sql) as $row) {
                        $count_items = 0;
                        $stmt = $connec->prepare("SELECT COUNT(*) as item_count FROM price_tag_items WHERE header_id = :hid");
                        $stmt->execute(['hid' => $row['id']]);
                        $count_items = $stmt->fetchColumn();

                        $row['total_items'] = $count_items;
                        ?>
                        <tr>
                            <td>
                                <?= $no++; ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($row['header_number']); ?>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'DRAFT') { ?>
                                    <span class="badge bg-warning">DRAFT</span>
                                <?php } else { ?>
                                    <span class="badge bg-success">
                                        <?= htmlspecialchars($row['status']); ?>
                                    </span>
                                <?php } ?>
                            </td>
                            <td>
                                <?= $row['total_items']; ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($row['created_by']); ?>
                            </td>
                            <td>
                                <?= date('d-m-Y H:i', strtotime($row['created_at'])); ?>
                            </td>
                            <td>
                                <?= nl2br(htmlspecialchars($row['keterangan'])); ?>
                            </td>
                            <td>
                                <a href="price_tag_list_detail.php?header_id=<?= $row['id']; ?>" target="_blank" class="btn btn-sm btn-danger">
                                    <i class="bi bi-printer"></i> Cetak Promo
                                </a>
                                <a href="price_tag_list_detail_reguler.php?header_id=<?= $row['id']; ?>" target="_blank" class="btn btn-sm btn-danger">
                                    <i class="bi bi-printer"></i> Cetak Reguler
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
				

					
					
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>

<script src="styles/js/price-promo.js?id=321"></script>
<script type="text/javascript">
$(document).ready( function () {
    $('#example').DataTable({
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],
    });
} );
</script>
</div>
<?php include "components/fff.php"; ?>