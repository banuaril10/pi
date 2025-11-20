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
				<h4>DETAIL ITEMS IDOLSCAN</h4>
			</div>
			<div class="card-body">

            <?php
                $header_id = $_GET['header_id'];
                $sql_header = "select * from price_tag_headers where id = '" . $header_id . "'";
                foreach ($connec->query($sql_header) as $row_header) {
                    ?>
                    <p><b>Header Number : </b> <?= $row_header['header_number']; ?><br>
                    <b>Status : </b> <?= $row_header['status']; ?><br>
                    <b>Total Items : </b> <?= $row_header['total_items']; ?><br>
                    <b>Dibuat Oleh : </b> <?= $row_header['created_by']; ?><br>
                    <b>Tanggal : </b> <?= $row_header['created_at']; ?><br>
                    <b>Keterangan : </b> <?= nl2br(htmlspecialchars($row_header['description'])); ?></p>
                    <?php
                }
            ?>

			<div class="tables">			
				<div class="table-responsive bs-example widget-shadow">	
				<p id="notif1" style="color: red; font-weight: bold"></p>		
                <p style="color: red; font-weight: bold">Satu kertas terdiri dari 32 tag</p>
				<button class="btn btn-danger" type="button" id="btn-cetak-tinta"><div class="item1"><b>CETAK HARGA</b></div></button>
					<button class="btn btn-success" type="button" id="btn-cetak-tinta-pdf"><div class="item1"><b>CETAK PLANOGRAM</b></div></button>
					<table class="table table-bordered" id="example">
						<thead>
							<tr>
								<th><input type="button" id="checkall" value="✔"/></th>
								<th>No</th>
								<th>SKU</th>
								<th>Barcode</th>
								<th>Name</th>
								<th>Price</th>
								<th>Price Discount</th>
								<th>Rack Name</th>
								<th>Tag</th>
								<th>Copy</th>
								<th>Periode</th>
							</tr>
						</thead>
						<tbody>
						
						<?php

                            //himpun sku dari price_tag_items where header_id = ...
                            $sku_in = "";
                            $header_id = $_GET['header_id'];
                            $sql_sku = "select sku from price_tag_items where header_id = '" . $header_id . "'";
                            $first = true;
                            foreach ($connec->query($sql_sku) as $row_sku) {
                                if ($first) {
                                    $sku_in .= "'" . $row_sku['sku'] . "'";
                                    $first = false;
                                } else {
                                    $sku_in .= ",'" . $row_sku['sku'] . "'";
                                }
                            }
                            $sku_in = rtrim($sku_in, ",");
                            // echo $sku_in;



                            $sql_list = "select date(now()) as tgl_sekarang, a.sku, a.name ,a.rack, a.price, a.barcode, a.tag from pos_mproduct a where (a.sku in (" . $sku_in . ") or a.barcode in (" . $sku_in . ")) order by a.name";


                            $no = 1;
                            foreach ($connec->query($sql_list) as $row) {

                                    $tgl_todate = date('d/m/Y', strtotime($row_dis['todate']));
                                    $tgl_fromdate = date('d/m/Y', strtotime($row_dis['fromdate']));

                                    $harga_last = $row['price'];

                                    ?>
                    
                    
                                    <tr>
                                        <td><input type="checkbox" id="checkbox" name="checkbox[]"
                                                value="<?php echo $row['sku']; ?>|<?php echo $row['name']; ?>|<?php echo $row['price']; ?>|<?php echo $row['tgl_sekarang']; ?>|<?php echo $row['rack_name']; ?>|<?php echo $row['shortcut']; ?>|<?php echo $harga_last; ?>|<?php echo $tgl_todate; ?>|<?php echo $row['tag']; ?>|<?php echo $row['barcode']; ?>|<?php echo $tgl_fromdate; ?>">
                                        </td>
                                        <td scope="row">
                                            <?php echo $no; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['sku']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['barcode']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['price']; ?>
                                        </td>
                                        <td>
                                            <?php echo $harga_last; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['rack']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['tag']; ?>
                                        </td>
                                        <td><input type="number" id="copy<?php echo $row['sku']; ?>" value="1"></td>
                                        <td>
                                            <?php echo $tgl_fromdate; ?> - <?php echo $tgl_todate; ?>
                                        </td>
                    
                                    </tr>
                    
                    
                    
                    
                                    <?php $no++;
                                
                            } ?>
                    
                    
                        </tbody>
                    </table>
				

					
					
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>

 <script src="styles/js/jsbarcode.js"></script>
 <script src="https://intransit.idolmartidolaku.com/apiidolmart/pricetag/price-reguler-store-apps.js?id=dwa"></script>
<script type="text/javascript">
$(document).ready( function () {
    $('#example').DataTable({
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],
    });
} );

            document.getElementById("checkall").addEventListener("click", function() {	

				var checkboxes = document.querySelectorAll('input[type="checkbox"]');
				
		
				for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox'){
                        if(checkboxes[i].checked == true){
                            
                            checkboxes[i].checked = false;
                        }else if(checkboxes[i].checked == false){
                            checkboxes[i].checked = true;
                            
                        }
					}	
				}
			});


</script>
</div>
<?php include "components/fff.php"; ?>