
<?php include "config/koneksi.php"; ?>
<?php include "components/main.php"; ?>
<?php include "components/sidebar.php"; ?>

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
				<h4>RESET CASHIER USER</h4>
				<font id="notif1" style="color: red; font-weight: bold"></font>	
			</div>
			<div class="card-body">
				<div class="tables">			
					<div class="table-responsive bs-example widget-shadow">
						
						<?php 
						// Get filter parameters
						$filter_code = isset($_GET['code']) ? $_GET['code'] : '';
						$filter_name = isset($_GET['name']) ? $_GET['name'] : '';
						$filter_user_key = isset($_GET['user_key']) ? $_GET['user_key'] : '';
						?>
						
						<!-- Filter Form -->
						<form action="" method="GET">
							<div class="row mb-3">
								<div class="col-md-3 mb-2">
									<label for="code" class="form-label">Kode Cashier</label>
									<input type="text" name="code" class="form-control" value="<?php echo htmlspecialchars($filter_code); ?>" placeholder="Filter kode...">
								</div>
								<div class="col-md-3 mb-2">
									<label for="name" class="form-label">Nama Cashier</label>
									<input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($filter_name); ?>" placeholder="Filter nama...">
								</div>
								<div class="col-md-3 mb-2">
									<label for="user_key" class="form-label">User Key</label>
									<input type="text" name="user_key" class="form-control" value="<?php echo htmlspecialchars($filter_user_key); ?>" placeholder="Filter user key...">
								</div>
								<div class="col-md-3 mb-2 d-flex align-items-end">
									<div class="d-grid gap-2">
										<input type="submit" class="btn btn-primary" value="Filter">
										<a href="?" class="btn btn-secondary">Reset</a>
									</div>
								</div>
							</div>
						</form>
						
						<!-- Action Buttons -->
						<div class="row mb-3">
							<div class="col-md-12">
								<button class="btn btn-danger" onclick="resetAllCashiers()">Reset Semua User Cashier</button>
								<button class="btn btn-warning" onclick="resetSelected()">Reset User Key Terpilih</button>
							</div>
						</div>
						
						<!-- Data Table -->
						<table class="table table-bordered">
							<thead>
								<tr>
									<th width="50px">
										<input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
									</th>
									<th>No</th>
									<th>Kode Cashier</th>
									<th>Nama Cashier</th>
									<th>User Key</th>
									<th>Nama User</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								// Build query with filters
								$sql = "SELECT pm.pos_mcashier_key, pm.name, pm.code, pm.ad_muser_key, am.username 
										FROM pos_mcashier pm 
										INNER JOIN ad_muser am ON pm.ad_muser_key = am.ad_muser_key 
										WHERE pm.ad_muser_key IS NOT NULL";
								
								// Add filters
								if(!empty($filter_code)) {
									$sql .= " AND pm.code LIKE '%" . $connec->real_escape_string($filter_code) . "%'";
								}
								if(!empty($filter_name)) {
									$sql .= " AND pm.name LIKE '%" . $connec->real_escape_string($filter_name) . "%'";
								}
								if(!empty($filter_user_key)) {
									$sql .= " AND pm.ad_muser_key LIKE '%" . $connec->real_escape_string($filter_user_key) . "%'";
								}
								
								$sql .= " ORDER BY pm.code";
								
								$no = 1;
								$result = $connec->query($sql);
								
						
									foreach ($result as $row) {
								?>
								<tr id="row_<?php echo $row['pos_mcashier_key']; ?>">
									<td>
										<input type="checkbox" class="cashierCheckbox" value="<?php echo $row['pos_mcashier_key']; ?>">
									</td>
									<td><?php echo $no; ?></td>
									<td><?php echo htmlspecialchars($row['code']); ?></td>
									<td><?php echo htmlspecialchars($row['name']); ?></td>
									<td><?php echo htmlspecialchars($row['ad_muser_key']); ?></td>
									<td><?php echo htmlspecialchars($row['username']); ?></td>
									<td>
										<button class="btn btn-sm btn-danger" onclick="resetCashier('<?php echo $row['pos_mcashier_key']; ?>')">
											Reset User
										</button>
									</td>
								</tr>
								<?php 
										$no++;
									}
									?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>

<script type="text/javascript">
// Toggle select all checkboxes
function toggleSelectAll() {
	var selectAll = document.getElementById('selectAll');
	var checkboxes = document.getElementsByClassName('cashierCheckbox');
	
	for(var i = 0; i < checkboxes.length; i++) {
		checkboxes[i].checked = selectAll.checked;
	}
}

// Reset single cashier
function resetCashier(cashierKey) {
	if(confirm("Apakah Anda yakin akan me-reset user untuk cashier ini?")) {
		$.ajax({
			url: "api/action.php?modul=cashier&act=reset_user",
			type: "POST",
			data: {
				pos_mcashier_key: cashierKey
			},
			beforeSend: function() {
				$('#notif1').html("<font style='color: blue'>Sedang memproses...</font>");
			},
			success: function(response) {
				try {
					var dataResult = JSON.parse(response);
					if(dataResult.result == '1') {
						$('#notif1').html("<font style='color: green'>Berhasil reset user cashier!</font>");
						// Hide or update the row
						$('#row_' + cashierKey).fadeOut(300);
					} else {
						$('#notif1').html("<font style='color: red'>" + dataResult.msg + "</font>");
					}
				} catch(e) {
					$('#notif1').html("<font style='color: red'>Error parsing response</font>");
				}
			},
			error: function() {
				$('#notif1').html("<font style='color: red'>Terjadi kesalahan pada server</font>");
			}
		});
	}
}

// Reset selected cashiers
function resetSelected() {
	var selected = [];
	var checkboxes = document.getElementsByClassName('cashierCheckbox');
	
	for(var i = 0; i < checkboxes.length; i++) {
		if(checkboxes[i].checked) {
			selected.push(checkboxes[i].value);
		}
	}
	
	if(selected.length === 0) {
		alert("Pilih minimal satu cashier untuk di-reset");
		return;
	}
	
	if(confirm("Apakah Anda yakin akan me-reset user untuk " + selected.length + " cashier yang dipilih?")) {
		$.ajax({
			url: "api/action.php?modul=cashier&act=reset_user_batch",
			type: "POST",
			data: {
				cashier_keys: JSON.stringify(selected)
			},
			beforeSend: function() {
				$('#notif1').html("<font style='color: blue'>Sedang memproses " + selected.length + " cashier...</font>");
			},
			success: function(response) {
				try {
					var dataResult = JSON.parse(response);
					if(dataResult.result == '1') {
						$('#notif1').html("<font style='color: green'>Berhasil reset " + dataResult.count + " cashier!</font>");
						// Reload page after 1 second
						setTimeout(function() {
							location.reload();
						}, 1000);
					} else {
						$('#notif1').html("<font style='color: red'>" + dataResult.msg + "</font>");
					}
				} catch(e) {
					$('#notif1').html("<font style='color: red'>Error parsing response</font>");
				}
			},
			error: function() {
				$('#notif1').html("<font style='color: red'>Terjadi kesalahan pada server</font>");
			}
		});
	}
}

// Reset all cashiers
function resetAllCashiers() {
	if(confirm("PERINGATAN: Ini akan mereset SEMUA user key cashier menjadi NULL. Apakah Anda yakin?")) {
		$.ajax({
			url: "api/action.php?modul=cashier&act=reset_all_users",
			type: "POST",
			beforeSend: function() {
				$('#notif1').html("<font style='color: blue'>Sedang memproses semua cashier...</font>");
			},
			success: function(response) {
				try {
					var dataResult = JSON.parse(response);
					if(dataResult.result == '1') {
						$('#notif1').html("<font style='color: green'>Berhasil reset semua cashier! Total: " + dataResult.count + "</font>");
						// Reload page after 1 second
						setTimeout(function() {
							location.reload();
						}, 1000);
					} else {
						$('#notif1').html("<font style='color: red'>" + dataResult.msg + "</font>");
					}
				} catch(e) {
					$('#notif1').html("<font style='color: red'>Error parsing response</font>");
				}
			},
			error: function() {
				$('#notif1').html("<font style='color: red'>Terjadi kesalahan pada server</font>");
			}
		});
	}
}

// Initialize DataTable

</script>

<?php include "components/fff.php"; ?>