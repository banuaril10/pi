


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
						<i class="bi bi-stack"></i>
						<span>Main Menu</span>
					</a>
					<ul class="submenu ">
						<li class="submenu-item ">
							<a href="content.php">Inventory List</a>
						</li>
						<li class="submenu-item ">
							<a href="verify.php">Inventory Verify</a>
						</li>
						<li class="submenu-item ">
							<a href="mproduct.php">Sync Master Rack</a>
						</li>
						<li class="submenu-item ">
							<a href="mrack.php">Master Rack Double</a>
						</li>
						<li class="submenu-item ">
							<a href="muser.php">Master Users</a>
						</li>
					</ul>
				</li>
				
				<li class="sidebar-item">
					<a href="logout.php" class='sidebar-link'>
						<i class="bi bi-arrow-bar-left"></i>
						<span>Logout</spaan>
					</a>
				</li>
			</ul>
		</div>
		<button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
	</div>
</div>