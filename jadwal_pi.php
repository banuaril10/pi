<?php include "config/koneksi.php"; ?>
<?php include "components/main.php"; ?>
<?php include "components/sidebar.php"; ?>

<?php 
//get idstore select ad_org_id from m_profile
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
						<h4>SCHEDULE PI LIST</h4>
						<p>Daftar jadwal Physical Inventory (PI) dari API</p>
						<!-- Filter Form -->
						<div class="row mt-3">
							<div class="col-md-8">
								<form id="filterForm" class="row g-3">
									<div class="col-md-4">
										<label for="startDate" class="form-label">Dari Tanggal</label>
										<input type="date" class="form-control" id="startDate" name="startDate" 
											   value="<?php echo date('Y-m-d'); ?>">
									</div>
									<div class="col-md-4">
										<label for="endDate" class="form-label">Sampai Tanggal</label>
										<input type="date" class="form-control" id="endDate" name="endDate" 
											   value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>">
									</div>
									<div class="col-md-4 d-flex align-items-end">
										<button type="button" onclick="applyFilter()" class="btn btn-primary me-2">
											<i class="bi bi-funnel"></i> Filter
										</button>
										<button type="button" onclick="resetFilter()" class="btn btn-secondary">
											<i class="bi bi-x-circle"></i> Reset
										</button>
									</div>
								</form>
							</div>
							<div class="col-md-4 text-end">
								<button type="button" onclick="refreshSchedule()" class="btn btn-primary">
									<i class="bi bi-arrow-clockwise"></i> Refresh Data
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

								// URL API untuk schedule dengan parameter filter
								$api_url_schedule = "https://api.idolmartidolaku.com/apiidolmart/store/pi/get_schedule_pi.php?idstore=" . urlencode($store_id) . "&startDate=" . urlencode($start_date) . "&endDate=" . urlencode($end_date);

								// Mengambil data dari API dengan CURL
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_URL, $api_url_schedule);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
								curl_setopt($ch, CURLOPT_TIMEOUT, 10);
								$api_response_schedule = curl_exec($ch);
								$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
								curl_close($ch);

								$schedule_data = [];
								if ($api_response_schedule !== false && $http_code === 200) {
									$api_data = json_decode($api_response_schedule, true);

									// Periksa struktur respons yang baru
									if (isset($api_data['success']) && $api_data['success'] === true && isset($api_data['data'])) {
										$schedule_data = $api_data['data'];
									} else {
										// Jika format lama (array langsung)
										$schedule_data = $api_data;
									}
								}
								?>
								
								<!-- Tampilkan Range Tanggal yang sedang difilter -->
								<div class="alert alert-info mb-3">
									<i class="bi bi-calendar-range"></i> 
									Menampilkan data dari <strong><?php echo date('d-m-Y', strtotime($start_date)); ?></strong> 
									sampai <strong><?php echo date('d-m-Y', strtotime($end_date)); ?></strong>
									<?php if (!empty($schedule_data)): ?>
											| <strong><?php echo count($schedule_data); ?></strong> schedule ditemukan
									<?php endif; ?>
								</div>
								
								<table class="table table-bordered" id="scheduleTable">
									<thead>
										<tr>
											<th>No</th>
											<th>PI Date</th>
											<th>Subcategory</th>
											<th>Notes</th>
											<th>Inserted At</th>
											<th>Inserted By</th>
											<th>ID Import</th>
											<th>Status</th>
											<!-- <th>Aksi</th> -->
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
												foreach ($schedule_data as $schedule):
													// Tentukan status berdasarkan tanggal
													$pi_date = $schedule['pi_date'] ?? '';
													$today = date('Y-m-d');
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
																<strong><?php echo htmlspecialchars($pi_date); ?></strong>
																<?php if ($pi_date == $today): ?>
																		<span class="badge bg-danger">NOW</span>
																<?php endif; ?>
															</td>
															<td>
																<strong><?php echo htmlspecialchars($schedule['subcategory'] ?? '-'); ?></strong>
																<br>
																<small class="text-muted">ID: <?php echo substr($schedule['id'] ?? '-', 0, 8); ?>...</small>
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
															<!-- <td>
																<?php if ($pi_date == $today): ?>
																		<button class="btn btn-sm btn-success" onclick="createPIToday('<?php echo htmlspecialchars($schedule['subcategory'] ?? ''); ?>')">
																			<i class="bi bi-plus-circle"></i> Buat PI
																		</button>
																<?php elseif (strtotime($pi_date) > strtotime($today)): ?>
																		<button class="btn btn-sm btn-outline-info" title="Schedule akan datang">
																			<i class="bi bi-clock"></i> Jadwal
																		</button>
																<?php else: ?>
																		<button class="btn btn-sm btn-outline-secondary" title="Schedule sudah lewat">
																			<i class="bi bi-check-circle"></i> Selesai
																		</button>
																<?php endif; ?>
															</td> -->
														</tr>
												<?php endforeach; ?>
										<?php else: ?>
												<tr>
													<td colspan="9" class="text-center">
														<div class="alert alert-warning">
															<i class="bi bi-exclamation-triangle"></i>
															<?php if ($http_code !== 200): ?>
																	Gagal mengambil data dari API. HTTP Code: <?php echo $http_code; ?>
															<?php else: ?>
																	Tidak ada data schedule yang ditemukan untuk periode ini.
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
													<h6>Statistik Schedule (Filtered):</h6>
													<?php
													$today_count = 0;
													$upcoming_count = 0;
													$completed_count = 0;

													foreach ($schedule_data as $schedule) {
														$pi_date = $schedule['pi_date'] ?? '';
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

<div class="modal fade" id="createPIModal" tabindex="-1" aria-labelledby="createPIModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="createPIModalLabel">Buat Physical Inventory</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="createPINotif" class="alert alert-info">
					Memproses pembuatan PI...
				</div>
				<div id="piDetails" style="display: none;">
					<p><strong>Subcategory:</strong> <span id="piSubcategory"></span></p>
					<p><strong>Tanggal:</strong> <span id="piDate"></span></p>
					<p>Silakan lanjutkan ke halaman inventory untuk melakukan counting.</p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
				<button type="button" id="btnGoToInventory" class="btn btn-primary" style="display: none;">
					<i class="bi bi-arrow-right"></i> Ke Halaman Inventory
				</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	// Set nilai default filter
	$(document).ready(function() {
		// Set tanggal default
		$('#startDate').val('<?php echo date("Y-m-d"); ?>');
		$('#endDate').val('<?php echo date("Y-m-d", strtotime("+30 days")); ?>');
		
		// Inisialisasi DataTable
		$('#scheduleTable').DataTable({
			"pageLength": 25,
			"order": [[1, 'asc']], // Urutkan berdasarkan tanggal ascending
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
		
		if (!startDate || !endDate) {
			alert('Mohon isi tanggal mulai dan tanggal akhir');
			return;
		}
		
		if (startDate > endDate) {
			alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
			return;
		}
		
		// Reload halaman dengan parameter filter
		window.location.href = '?startDate=' + startDate + '&endDate=' + endDate;
	}
	
	function resetFilter() {
		var today = new Date().toISOString().split('T')[0];
		var next30Days = new Date();
		next30Days.setDate(next30Days.getDate() + 30);
		var next30DaysStr = next30Days.toISOString().split('T')[0];
		
		$('#startDate').val(today);
		$('#endDate').val(next30DaysStr);
		
		// Reload halaman tanpa parameter
		window.location.href = window.location.pathname;
	}
	
	// Quick filter functions
	function setFilterToday() {
		var today = new Date().toISOString().split('T')[0];
		$('#startDate').val(today);
		$('#endDate').val(today);
		applyFilter();
	}
	
	function setFilterThisWeek() {
		var today = new Date();
		var startOfWeek = new Date(today);
		startOfWeek.setDate(today.getDate() - today.getDay()); // Start from Sunday
		var endOfWeek = new Date(today);
		endOfWeek.setDate(today.getDate() + (6 - today.getDay())); // End on Saturday
		
		$('#startDate').val(startOfWeek.toISOString().split('T')[0]);
		$('#endDate').val(endOfWeek.toISOString().split('T')[0]);
		applyFilter();
	}
	
	function setFilterNext30Days() {
		var today = new Date().toISOString().split('T')[0];
		var next30Days = new Date();
		next30Days.setDate(next30Days.getDate() + 30);
		var next30DaysStr = next30Days.toISOString().split('T')[0];
		
		$('#startDate').val(today);
		$('#endDate').val(next30DaysStr);
		applyFilter();
	}
	
	function setFilterAll() {
		// Set range yang sangat luas
		$('#startDate').val('2020-01-01');
		$('#endDate').val('2030-12-31');
		applyFilter();
	}
	
	function refreshSchedule() {
		$("#overlay").fadeIn(300);
		setTimeout(function() {
			location.reload();
		}, 1000);
	}
	
	function createPIToday(subcategory) {
		// Tampilkan modal
		$('#createPIModal').modal('show');
		$('#piDetails').hide();
		$('#btnGoToInventory').hide();
		$('#createPINotif').removeClass('alert-danger alert-success').addClass('alert-info')
			.html('<i class="bi bi-hourglass-split"></i> Memproses pembuatan PI untuk: <strong>' + subcategory + '</strong>...');
		
		// Simulasi proses pembuatan PI
		setTimeout(function() {
			$('#createPINotif').removeClass('alert-info').addClass('alert-success')
				.html('<i class="bi bi-check-circle"></i> Berhasil membuat PI untuk: <strong>' + subcategory + '</strong>');
			
			// Tampilkan detail
			$('#piSubcategory').text(subcategory);
			$('#piDate').text('<?php echo date("Y-m-d"); ?>');
			$('#piDetails').show();
			$('#btnGoToInventory').show();
		}, 2000);
	}
	
	// Event listener untuk tombol "Ke Halaman Inventory"
	$('#btnGoToInventory').on('click', function() {
		window.location.href = 'inventory.php';
	});
</script>

<?php include "components/fff.php"; ?>