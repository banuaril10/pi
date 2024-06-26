


<div id="sidebar" class="active">
	<div class="sidebar-wrapper active">
		<div class="sidebar-header">
			<div class="d-flex justify-content-between">
				<div class="logo">
					<a style="font-size: 20px" href="content.php">Store App <?php echo $_SESSION['kode_toko']; ?></a>
					<p style="font-size: 20px"><?php echo $_SESSION['username']; ?> (<?php echo $_SESSION['name']; ?>)</p>
				</div>
				<div class="toggler">
					<a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
				</div>
			</div>
		</div>
		<div class="sidebar-menu">
			<ul class="menu">
				<li class="sidebar-title">Menu</li>
							
				<li class="sidebar-item">
					<a href="monitoring.php" class='sidebar-link'>
						<i class="bi bi-speedometer"></i>
						<span>Dashboard Stock</span>
					</a>
				</li>
				
				<li class="sidebar-item">
					<a href="cashier.php" class='sidebar-link'>
						<i class="bi bi-wallet2"></i>
						<span>Pickup Cash In</span>
					</a>
				</li>
				
				
				<?php if($_SESSION['username'] != 'akunglobalit'){ ?>
					
					<li class="sidebar-item">
					<a href="morder.php" class='sidebar-link'>
						<i class="bi bi-cash-stack"></i>
						<span>D Order</span>
					</a>
				</li>
				<?php } ?>
				
				
			<?php if($_SESSION['org_key'] != '112233445566' && $_SESSION['name'] != 'Promo' && $_SESSION['name'] != 'Marketing' && $_SESSION['name'] != 'BAC'){ ?>
			
				<?php if($_SESSION['name'] != 'Cashier'){ ?>
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
						<li class="submenu-item ">
							<a href="invscanheader.php">Inventory All</a>
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
						<i class="bi bi-archive-fill"></i>
						<span>PI Nasional</span>
					</a>
					<ul class="submenu ">
						<li class="submenu-item ">
							<a href="content_nasional.php">Inventory List</a>
						</li>
						<li class="submenu-item ">
							<a href="verify_nasional.php">Inventory Verify</a>
						</li>
						<li class="submenu-item ">
							<a href="invscan.php">Importer</a>
						</li>
					</ul>
				</li>
				
				
			<?php } ?>	
			
			
				<li class="sidebar-item  has-sub">
					<a href="#" class='sidebar-link'>
						<i class="bi bi-gift"></i>
						<span>Promo</span>
					</a>
					<ul class="submenu ">
						<li class="submenu-item ">
							<a href="cek_promo.php">Reguler & Code</a>
						</li>
						<li class="submenu-item ">
							<a href="cek_promo_grosir.php">Grosir</a>
						</li>
						<li class="submenu-item ">
							<a href="cek_promo_buyget.php">Buy & Get</a>
						</li>
					</ul>
				</li>
			
			
							<li class="sidebar-item  has-sub">
					<a href="#" class='sidebar-link'>
						<i class="bi bi-stack"></i>
						<span>Master Data</span>
					</a>
					<ul class="submenu ">
						<li class="submenu-item ">
							<a href="mcategory.php">Master Category</a>
						</li>
					
						<li class="submenu-item ">
							<a href="muser.php">Master Users</a>
						</li>
						<li class="submenu-item ">
							<a href="muser_hris.php">Master Users HRIS</a>
						</li>
						
						<li class="submenu-item ">
							<a href="mproduct.php">Master Rack Price Tag</a>
						</li>
						<li class="submenu-item ">
							<a href="mplano.php">Master Planogram</a>
						</li>
						
						<li class="submenu-item ">
							<a href="mrack.php">Master Rack Double</a>
						</li>
						<li class="submenu-item ">
							<a href="mmember.php">Master Member</a>
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
							<a href="sync_peritems_name.php">Sync SKU by Name</a>
						</li>
						<li class="submenu-item ">
							<a href="sync_barcode.php">Sync Barcode</a>
						</li>
						<li class="submenu-item ">
							<a href="manage_stock.php">Manage Stock</a>
						</li>

						<li class="submenu-item ">
							<a href="sync_sku.php">Sync SKU</a>
						</li>
						<li class="submenu-item ">
							<a href="sync_grab.php">Sync Grab</a>
						</li>
						<li class="submenu-item ">
							<a href="sync_sales.php">Sync Sales Line</a>
						</li>
						<li class="submenu-item ">
							<a href="sync_edc.php">Sync EDC</a>
						</li>
						<li class="submenu-item ">
							<a href="sync_function.php">Sync Function</a>
						</li>
						<li class="submenu-item ">
							<a href="sync_items_nonaktif.php">Sync Items Nonaktif</a>
						</li>
						<li class="submenu-item ">
							<a href="sync_deleted_sales_old.php">Reset Penjualan</a>
						</li>
					</ul>
				</li>
				
				<li class="sidebar-item  has-sub">
					<a href="#" class='sidebar-link'>
						<i class="bi bi-tags-fill"></i>
						<span>Price Tag</span>
					</a>
					<ul class="submenu ">
						<li class="submenu-item ">
							<a href="mitems.php">Harga Reguler</a>
						</li>
						<li class="submenu-item ">
							<a href="mitemsold.php">Harga Reguler Format Lama</a>
						</li>
						<li class="submenu-item ">
							<a href="mitems_alt.php">Harga Reguler Per Rack</a>
						</li>
						<li class="submenu-item ">
							<a href="mitemspromo.php">Harga Promo</a>
						</li>
						<li class="submenu-item ">
							<a href="mitemsspecial.php">Harga Khusus</a>
						</li>
						<li class="submenu-item ">
							<a href="mitemspromocode_live.php">Harga Bertingkat</a>
						</li>
					</ul>
				</li>
			
				<li class="sidebar-item">
					<a href="cek_harga.php" class='sidebar-link'>
						<i class="bi bi-cash-stack"></i>
						<span>Cek Harga</span>
					</a>
				</li>
			
				<li class="sidebar-item">
					<a href="capture_sku.php" class='sidebar-link'>
						<i class="bi bi-camera"></i>
						<span>Capture Price Tag</span>
					</a>
				</li>
				
				<li class="sidebar-item">
					<a href="capture_jsm.php" class='sidebar-link'>
						<i class="bi bi-camera"></i>
						<span>Capture Price Tag JSM</span>
					</a>
				</li>
			
				<li class="sidebar-item">
					<a href="mlomba.php" class='sidebar-link'>
						<i class="bi bi-cash-stack"></i>
						<span>Pendaftaran Lomba</span>
					</a>
				</li>
				<li class="sidebar-item">
					<a href="cekperubahanharga.php" class='sidebar-link'>
						<i class="bi bi-tags-fill"></i>
						<span>Perubahan Harga</span>
					</a>
				</li>
				
				<li class="sidebar-item">
					<a href="mitemspromo_live.php" class='sidebar-link'>
						<i class="bi bi-tags-fill"></i>
						<span>Perubahan Harga Promo</span>
					</a>
				</li>
			
				<li class="sidebar-item">
					<a href="document.php" class='sidebar-link'>
						<i class="bi bi-box-seam"></i>
						<span>Receiving Items</span>
					</a>
				</li>
			
				
				<li class="sidebar-item  has-sub">
					<a href="#" class='sidebar-link'>
						<i class="bi bi-tags-fill"></i>
						<span>Price Tag Baby Doll</span>
					</a>
					<ul class="submenu ">
						<li class="submenu-item ">
							<a href="mitems_baby.php">Harga Reguler</a>
						</li>
						<li class="submenu-item ">
							<a href="mitemsold_baby.php">Harga Reguler Format Lama</a>
						</li>
						<li class="submenu-item ">
							<a href="mitems_alt_baby.php">Harga Reguler Per Rack</a>
						</li>
						<!--<li class="submenu-item ">
							<a href="mitemspromo_baby.php">Harga Promo</a>
						</li>
						<li class="submenu-item ">
							<a href="mitemspromocode_live_baby.php">Harga Bertingkat</a>
						</li>-->
					</ul>
				</li>
				
				<li class="sidebar-item  has-sub">
					<a href="#" class='sidebar-link'>
						<i class="bi bi-shop"></i>
						<span>Sewa Tenant</span>
					</a>
					<ul class="submenu ">
						<li class="submenu-item ">
							<a href="instore.php">In Store</a>
						</li>
						<li class="submenu-item ">
							<a href="outstore.php">Out Store</a>
						</li>
					</ul>
				</li>
				
				<li class="sidebar-item">
					<a href="cashier_balance.php" class='sidebar-link'>
						<i class="bi bi-box-seam"></i>
						<span>Cashier Balance</span>
					</a>
				</li>

				
				

				
				
			<?php }else if($_SESSION['name'] == 'Marketing'){ ?>
				<li class="sidebar-item">
					<a href="cekperubahanharga.php" class='sidebar-link'>
						<i class="bi bi-tags-fill"></i>
						<span>Perubahan Harga</span>
					</a>
				</li>
				
				
				<li class="sidebar-item">
					<a href="mitemspromo_live.php" class='sidebar-link'>
						<i class="bi bi-tags-fill"></i>
						<span>Perubahan Harga Promo</span>
					</a>
				</li>
				
				
				
				<li class="sidebar-item  has-sub">
					<a href="#" class='sidebar-link'>
						<i class="bi bi-stack"></i>
						<span>Master Data</span>
					</a>
					<ul class="submenu ">
						<li class="submenu-item ">
							<a href="mplano.php">Master Planogram</a>
						</li>
					</ul>
				</li>
				
				<li class="sidebar-item  has-sub">
					<a href="#" class='sidebar-link'>
						<i class="bi bi-tags-fill"></i>
						<span>Price Tag</span>
					</a>
					<ul class="submenu ">
						<li class="submenu-item ">
							<a href="mitems.php">Harga Reguler</a>
						</li>
						<li class="submenu-item ">
							<a href="mitemsold.php">Harga Reguler Format Lama</a>
						</li>
						<li class="submenu-item ">
							<a href="mitems_alt.php">Harga Reguler Per Rack</a>
						</li>
						<li class="submenu-item ">
							<a href="mitemspromo.php">Harga Promo</a>
						</li>
						<li class="submenu-item ">
							<a href="mitemspromocode_live.php">Harga Bertingkat</a>
						</li>
					</ul>
				</li>
				
				
				<li class="sidebar-item  has-sub">
					<a href="#" class='sidebar-link'>
						<i class="bi bi-tags-fill"></i>
						<span>Price Tag Baby Doll</span>
					</a>
					<ul class="submenu ">
						<li class="submenu-item ">
							<a href="mitems_baby.php">Harga Reguler</a>
						</li>
						<li class="submenu-item ">
							<a href="mitemsold_baby.php">Harga Reguler Format Lama</a>
						</li>
						<li class="submenu-item ">
							<a href="mitems_alt_baby.php">Harga Reguler Per Rack</a>
						</li>
						<!--<li class="submenu-item ">
							<a href="mitemspromo_baby.php">Harga Promo</a>
						</li>
						<li class="submenu-item ">
							<a href="mitemspromocode_live_baby.php">Harga Bertingkat</a>
						</li>-->
					</ul>
				</li>
				
				
				<li class="sidebar-item  has-sub">
					<a href="#" class='sidebar-link'>
						<i class="bi bi-shop"></i>
						<span>Sewa Tenant</span>
					</a>
					<ul class="submenu ">
						<li class="submenu-item ">
							<a href="instore.php">In Store</a>
						</li>
						<li class="submenu-item ">
							<a href="outstore.php">Out Store</a>
						</li>
					</ul>
				</li>
				
			
			
				
			<?php }else if($_SESSION['name'] == 'Promo'){ ?>
			
				<li class="sidebar-item">
					<a href="mitemspromo_live.php" class='sidebar-link'>
						<i class="bi bi-tags-fill"></i>
						<span>Perubahan Harga Promo</span>
					</a>
				</li>
			
				<li class="sidebar-item">
					<a href="cek_harga.php" class='sidebar-link'>
						<i class="bi bi-cash-stack"></i>
						<span>Cek Harga</span>
					</a>
				</li>
				
				<li class="sidebar-item  has-sub">
					<a href="#" class='sidebar-link'>
						<i class="bi bi-gift"></i>
						<span>Promo</span>
					</a>
					<ul class="submenu ">
						<li class="submenu-item ">
							<a href="cek_promo.php">Reguler & Code</a>
						</li>
						<li class="submenu-item ">
							<a href="cek_promo_grosir.php">Grosir</a>
						</li>
						<li class="submenu-item ">
							<a href="cek_promo_buyget.php">Buy & Get</a>
						</li>
					</ul>
				</li>
				
			<?php }else if($_SESSION['name'] == 'BAC'){ ?>	
			
				<li class="sidebar-item">
					<a href="cek_harga.php" class='sidebar-link'>
						<i class="bi bi-cash-stack"></i>
						<span>Cek Harga</span>
					</a>
				</li>
				
				
			<?php } ?>	
				
				
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