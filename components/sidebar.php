


<div id="sidebar" class="active">
	<div class="sidebar-wrapper active">
		<div class="sidebar-header">
			<div class="d-flex justify-content-between">
				<div class="logo">
					<a href="content.php">PI <?php echo $_SESSION['kode_toko']; ?></a>
					<p style="font-size: 20px"><?php echo $_SESSION['username']; ?></p>
				</div>
				<div class="toggler">
					<a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
				</div>
			</div>
		</div>
		<div class="sidebar-menu">
			<ul class="menu">
				<li class="sidebar-title">Menu</li>

				<li class="sidebar-item  has-sub">
					<a href="#" class='sidebar-link'>
						<i class="bi bi-archive-fill"></i>
						<span>Physical Inventory</span>
					</a>
					<ul class="submenu ">
						<li class="submenu-item ">
							<a href="content.php">Inventory List</a>
						</li>
						<li class="submenu-item ">
							<a href="verify.php">Inventory Verify</a>
						</li>
						<li class="submenu-item ">
							<a href="pigantung.php">List PI Expired</a>
						</li>
					<?php if($_SESSION['role'] == 'Global'){ ?>
						
						<li class="submenu-item ">
							<a href="importer.php">Importer</a>
						</li>
						
					<?php } ?>	
						
					</ul>
				</li>
				
				<li class="sidebar-item  has-sub">
					<a href="#" class='sidebar-link'>
						<i class="bi bi-stack"></i>
						<span>Master Data</span>
					</a>
					<ul class="submenu ">
						<li class="submenu-item ">
							<a href="muser.php">Master Users</a>
						</li>
						
						<li class="submenu-item ">
							<a href="mproduct.php">Master Rack</a>
						</li>
						<li class="submenu-item ">
							<a href="mrack.php">Master Rack Double</a>
						</li>
						
						
						
					</ul>
				</li>
				
				<li class="sidebar-item  has-sub">
					<a href="#" class='sidebar-link'>
						<i class="bi bi-cart3"></i>
						<span>Sync POS</span>
					</a>
					<ul class="submenu ">
						<li class="submenu-item ">
							<a href="sync_peritems.php">Sync Per Items</a>
						</li>
						<li class="submenu-item ">
							<a href="manage_stock.php">Manage Stock</a>
						</li>
					</ul>
				</li>
				
				<li class="sidebar-item">
					<a href="mitems.php" class='sidebar-link'>
						<i class="bi bi-tags-fill"></i>
						<span>Price Tag</span>
					</a>
				</li>
				
				<li class="sidebar-item">
					<a href="cek_harga.php" class='sidebar-link'>
						<i class="bi bi-tags-fill"></i>
						<span>Cek Harga</span>
					</a>
				</li>
				
				<li class="sidebar-item">
					<a href="logout.php" class='sidebar-link'>
						<i class="bi bi-arrow-bar-left"></i>
						<span>Logout</span>
					</a>
				</li>
				

				
			</ul>
		</div>
		<button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
	</div>
</div>