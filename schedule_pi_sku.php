<?php include "config/koneksi.php"; ?>
<?php include "components/main.php"; ?>
<?php include "components/sidebar.php"; ?>

<?php 
// Get idstore select ad_org_id from m_profile
$store_query = "SELECT ad_morg_key FROM ad_morg LIMIT 1";
$store_result = $connec->query($store_query);
$store_row = $store_result->fetch(PDO::FETCH_ASSOC);
$store_id = $store_row['ad_morg_key'];
?>

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
						<h4>SCHEDULE PI PER SKU</h4>
						<p>Daftar jadwal Physical Inventory (PI) per SKU</p>
						
						<!-- Filter Form -->
						<div class="row mt-3">
							<div class="col-md-10">
								<form id="filterForm" class="row g-3">
									<div class="col-md-3">
										<label for="startDate" class="form-label">Dari Tanggal</label>
										<input type="date" class="form-control" id="startDate" name="startDate" 
											   value="<?php echo date('Y-m-d'); ?>">
									</div>
									<div class="col-md-3">
										<label for="endDate" class="form-label">Sampai Tanggal</label>
										<input type="date" class="form-control" id="endDate" name="endDate" 
											   value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>">
									</div>
									<div class="col-md-3">
										<label for="skuFilter" class="form-label">Cari SKU</label>
										<input type="text" class="form-control" id="skuFilter" name="sku" 
											   placeholder="Masukkan SKU...">
									</div>
									<div class="col-md-3 d-flex align-items-end">
										<button type="button" onclick="applyFilter()" class="btn btn-primary me-2">
											<i class="bi bi-funnel"></i> Filter
										</button>
										<button type="button" onclick="resetFilter()" class="btn btn-secondary">
											<i class="bi bi-x-circle"></i> Reset
										</button>
									</div>
								</form>
							</div>
							<div class="col-md-2 text-end">
								<button type="button" onclick="refreshSchedule()" class="btn btn-primary">
									<i class="bi bi-arrow-clockwise"></i> Refresh
								</button>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="tables">
							<div class="table-responsive bs-example widget-shadow">
								<?php
								// Ambil parameter filter dari URL jika ada
								$start_date = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d');
								$end_date = isset($_GET['endDate']) ? $_GET['endDate'] : date('Y-m-d', strtotime('+30 days'));
								$sku_filter = isset($_GET['sku']) ? $_GET['sku'] : '';

								// URL API untuk schedule dengan parameter filter
								$api_url_schedule = "https://api.idolmartidolaku.com/apiidolmart/store/pi/get_pi_schedule_sku.php?startDate=" . urlencode($start_date) . "&endDate=" . urlencode($end_date);
								if (!empty($sku_filter)) {
									$api_url_schedule .= "&sku=" . urlencode($sku_filter);
								}

								// Mengambil data dari API dengan CURL
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_URL, $api_url_schedule);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
								curl_setopt($ch, CURLOPT_TIMEOUT, 10);
								$api_response = curl_exec($ch);
								$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
								curl_close($ch);

								$schedule_data = [];
								$filter_info = [];
								
								if ($api_response !== false && $http_code === 200) {
									$api_data = json_decode($api_response, true);
									
									if (isset($api_data['success']) && $api_data['success'] === true && isset($api_data['data'])) {
										$schedule_data = $api_data['data'];
										$filter_info = $api_data['filter'] ?? [];
									}
								}
								?>
								
								<!-- Tampilkan Info Filter -->
								<div class="alert alert-info mb-3">
									<i class="bi bi-calendar-range"></i> 
									Menampilkan data dari <strong><?php echo date('d-m-Y', strtotime($start_date)); ?></strong> 
									sampai <strong><?php echo date('d-m-Y', strtotime($end_date)); ?></strong>
									<?php if (!empty($sku_filter)): ?>
										| SKU: <strong><?php echo htmlspecialchars($sku_filter); ?></strong>
									<?php endif; ?>
									<?php if (!empty($schedule_data)): ?>
										| <strong><?php echo count($schedule_data); ?></strong> schedule ditemukan
									<?php endif; ?>
								</div>
								
								<table class="table table-bordered" id="scheduleTable">
									<thead>
										<tr>
											<th>No</th>
											<th>SKU</th>
											<th>PI Date</th>
											<th>Notes</th>
											<th>Inserted At</th>
											<th>Inserted By</th>
											<th>ID Import</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($schedule_data) && is_array($schedule_data)): ?>
											<?php
											// Urutkan data berdasarkan PI Date
											usort($schedule_data, function ($a, $b) {
												return strcmp($a['pi_date'] ?? '', $b['pi_date'] ?? '');
											});

											$no = 1;
											$today = date('Y-m-d');
											
											foreach ($schedule_data as $schedule):
												// Tentukan status berdasarkan tanggal
												$pi_date = $schedule['pi_date'] ?? '';
												$status = '';
												$status_class = '';

												if ($pi_date == $today) {
													$status = 'HARI INI';
													$status_class = 'badge bg-warning';
												} elseif (strtotime($pi_date) > strtotime($today)) {
													$status = 'AKAN DATANG';
													$status_class = 'badge bg-info';
												} else {
													$status = 'SELESAI';
													$status_class = 'badge bg-secondary';
												}
												?>
												<tr>
													<td><?php echo $no++; ?></td>
													<td>
														<strong><?php echo htmlspecialchars($schedule['sku'] ?? '-'); ?></strong>
													</td>
													<td>
														<strong><?php echo htmlspecialchars($pi_date); ?></strong>
														<?php if ($pi_date == $today): ?>
															<span class="badge bg-danger">NOW</span>
														<?php endif; ?>
													</td>
													<td><?php echo htmlspecialchars($schedule['notes'] ?? '-'); ?></td>
													<td><?php echo htmlspecialchars($schedule['inserted_at'] ?? '-'); ?></td>
													<td>
														<span class="badge bg-primary">
															<?php echo htmlspecialchars($schedule['inserted_by'] ?? '-'); ?>
														</span>
													</td>
													<td>
														<small class="text-muted">
															<?php echo substr($schedule['id_import'] ?? '-', 0, 12); ?>...
														</small>
													</td>
													<td>
														<span class="<?php echo $status_class; ?>">
															<?php echo $status; ?>
														</span>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td colspan="8" class="text-center">
													<div class="alert alert-warning">
														<i class="bi bi-exclamation-triangle"></i>
														<?php if ($http_code !== 200): ?>
															Gagal mengambil data dari API. HTTP Code: <?php echo $http_code; ?>
														<?php else: ?>
															Tidak ada data schedule SKU yang ditemukan untuk periode ini.
														<?php endif; ?>
													</div>
												</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
								
								<?php if (!empty($schedule_data)): ?>
									<div class="row mt-3">
										<div class="col-md-6">
											<div class="card">
												<div class="card-body">
													<h6>Statistik Schedule SKU (Filtered):</h6>
													<?php
													$today_count = 0;
													$upcoming_count = 0;
													$completed_count = 0;
													$unique_sku = [];

													foreach ($schedule_data as $schedule) {
														$pi_date = $schedule['pi_date'] ?? '';
														$sku = $schedule['sku'] ?? '';
														
														if (!empty($sku)) {
															$unique_sku[$sku] = true;
														}
														
														if ($pi_date == $today) {
															$today_count++;
														} elseif (strtotime($pi_date) > strtotime($today)) {
															$upcoming_count++;
														} else {
															$completed_count++;
														}
													}
													?>
													<ul class="list-unstyled">
														<li><span class="badge bg-warning">HARI INI</span>: <?php echo $today_count; ?> schedule</li>
														<li><span class="badge bg-info">AKAN DATANG</span>: <?php echo $upcoming_count; ?> schedule</li>
														<li><span class="badge bg-secondary">SELESAI</span>: <?php echo $completed_count; ?> schedule</li>
														<li><strong>Unique SKU</strong>: <?php echo count($unique_sku); ?> SKU</li>
														<li><strong>TOTAL (Filtered)</strong>: <?php echo count($schedule_data); ?> schedule</li>
													</ul>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="card">
												<div class="card-body">
													<h6>Quick Actions:</h6>
													<div class="d-grid gap-2">
														<button class="btn btn-outline-primary" onclick="setFilterToday()">
															<i class="bi bi-calendar-day"></i> Lihat Hari Ini
														</button>
														<button class="btn btn-outline-success" onclick="setFilterThisWeek()">
															<i class="bi bi-calendar-week"></i> Minggu Ini
														</button>
														<button class="btn btn-outline-info" onclick="setFilterNext30Days()">
															<i class="bi bi-calendar-month"></i> 30 Hari Kedepan
														</button>
														<button class="btn btn-outline-secondary" onclick="setFilterAll()">
															<i class="bi bi-calendar3"></i> Semua Data
														</button>
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
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		// Inisialisasi DataTable
		$('#scheduleTable').DataTable({
			"pageLength": 25,
			"order": [[2, 'asc']], // Urutkan berdasarkan tanggal (kolom ke-3)
			"language": {
				"search": "Cari:",
				"lengthMenu": "Tampilkan _MENU_ data per halaman",
				"zeroRecords": "Tidak ada data yang ditemukan",
				"info": "Menampilkan halaman _PAGE_ dari _PAGES_",
				"infoEmpty": "Tidak ada data",
				"infoFiltered": "(disaring dari _MAX_ total data)",
				"paginate": {
					"first": "Pertama",
					"last": "Terakhir",
					"next": "Berikutnya",
					"previous": "Sebelumnya"
				}
			}
		});
	});
	
	function applyFilter() {
		var startDate = $('#startDate').val();
		var endDate = $('#endDate').val();
		var sku = $('#skuFilter').val();
		
		if (!startDate || !endDate) {
			alert('Mohon isi tanggal mulai dan tanggal akhir');
			return;
		}
		
		if (startDate > endDate) {
			alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
			return;
		}
		
		// Build URL dengan parameter
		var url = '?startDate=' + startDate + '&endDate=' + endDate;
		if (sku) {
			url += '&sku=' + encodeURIComponent(sku);
		}
		
		window.location.href = url;
	}
	
	function resetFilter() {
		window.location.href = window.location.pathname;
	}
	
	function setFilterToday() {
		var today = new Date().toISOString().split('T')[0];
		$('#startDate').val(today);
		$('#endDate').val(today);
		$('#skuFilter').val('');
		applyFilter();
	}
	
	function setFilterThisWeek() {
		var today = new Date();
		var startOfWeek = new Date(today);
		startOfWeek.setDate(today.getDate() - today.getDay());
		var endOfWeek = new Date(today);
		endOfWeek.setDate(today.getDate() + (6 - today.getDay()));
		
		$('#startDate').val(startOfWeek.toISOString().split('T')[0]);
		$('#endDate').val(endOfWeek.toISOString().split('T')[0]);
		$('#skuFilter').val('');
		applyFilter();
	}
	
	function setFilterNext30Days() {
		var today = new Date().toISOString().split('T')[0];
		var next30Days = new Date();
		next30Days.setDate(next30Days.getDate() + 30);
		
		$('#startDate').val(today);
		$('#endDate').val(next30Days.toISOString().split('T')[0]);
		$('#skuFilter').val('');
		applyFilter();
	}
	
	function setFilterAll() {
		$('#startDate').val('2020-01-01');
		$('#endDate').val('2030-12-31');
		$('#skuFilter').val('');
		applyFilter();
	}
	
	function refreshSchedule() {
		$("#overlay").fadeIn(300);
		setTimeout(function() {
			location.reload();
		}, 1000);
	}
</script>

<?php include "components/fff.php"; ?>