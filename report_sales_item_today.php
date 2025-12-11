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
						<h4>REPORT SALES ITEM TODAY</h4>
					</div>
					<div class="card-body">
						<div class="tables">
							<div class="table-responsive bs-example widget-shadow">
								<p id="notif1" style="color: red; font-weight: bold"></p>

								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label for="date">From Date</label>
											<input type="date" class="form-control" id="date" name="date"
												value="<?php echo date('Y-m-d'); ?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="date">To Date</label>
											<input type="date" class="form-control" id="todate" name="date"
												value="<?php echo date('Y-m-d'); ?>">
										</div>
									</div>
									<div class="col-md-6" style="padding-top: 28px;">
										<button type="button" class="btn btn-primary" onclick="search()">
											<i class="bi bi-search"></i> Search
										</button>
										<button type="button" class="btn btn-success" onclick="exportExcel()">
											<i class="bi bi-file-earmark-excel"></i> Export Excel
										</button>
									</div>
								</div>

								<br>

								<table class="table table-bordered table-hover" id="example">
									<thead>
										<tr>
											<th>No</th>
											<th>SKU</th>
											<th>Name</th>
											<th>QTY</th>
											<th>Amount</th>
										</tr>
									</thead>
									<tbody id="data">


									</tbody>
								</table>



							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<script type="text/javascript">
		//create datatable sumber data from json api/cyber/report_sales_item.php
		$(document).ready(function () {
			$('#example').DataTable({
				"ajax": {
					"url": "api/cyber/report_sales_item.php",
					"dataSrc": ""
				},
				"columns": [{
					"data": "no"
				},
				{
					"data": "sku"
				},
				{
					"data": "name"
				},
				{
					"data": "qty"
				},
				{
					"data": "amount"
				}
				]
			});
		});

		function search() {
			var date = $('#date').val();
			var todate = $('#todate').val();
			$('#example').DataTable().ajax.url('api/cyber/report_sales_item.php?date_awal=' + date + '&date_akhir=' + todate).load();
		}

		function exportExcel() {
			var date = $('#date').val();
			var todate = $('#todate').val();

			// Tampilkan loading
			$('#overlay').show();

			// Redirect ke halaman export
			window.location.href = 'api/cyber/export_sales_item.php?date_awal=' + date + '&date_akhir=' + todate;

			// Sembunyikan loading setelah 2 detik (fallback)
			setTimeout(function () {
				$('#overlay').hide();
			}, 2000);
		}

	</script>
</div>
<?php include "components/fff.php"; ?>