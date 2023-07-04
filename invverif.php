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
			<div style="font-size: 15px" class="card-header">
				<font><b>INVENTORY VERIFICATION</b></font><br>
				<?php 
				function rupiah($angka){
	
							$hasil_rupiah = "Rp " . number_format($angka,0,',','.');
							return $hasil_rupiah;
 
						}
				$sql_list = "select m_pi_key, name ,insertdate, rack_name from m_pi where status = '2' and m_pi_key = '".$_GET['m_pi']."' order by insertdate desc"; 
				foreach ($connec->query($sql_list) as $tot) {
					$rack_name = $tot['rack_name'];
					$dn = $tot['name'];

					?>
						<font>RACK : <b><?php echo $tot['rack_name']; ?></b></font><br>
						<font>DOCUMENT NO : <b><?php echo $tot['name']; ?></b></font><br>
					
					
				<?php } ?>
				
				<div id="info">
				<?php $sql_amount = "select SUM(CASE WHEN issync=1 THEN 1 ELSE 0 END) jumsync,  sum(qtysales * price) hargasales, sum(qtysalesout * price) hargagantung,  sum(qtyerp * price) hargaerp, sum(qtycount * price) hargafisik, count(sku) jumline
						from m_piline where m_pi_key = '".$_GET['m_pi']."'";
						foreach ($connec->query($sql_amount) as $tot) {
							
							$qtyerp = $tot['hargaerp'] - $tot['hargagantung'] - $tot['hargasales'];
							$qtycount = $tot['hargafisik'];

							$jumline = $tot['jumline'];
							$jumsync = $tot['jumsync'];
							$selisih = $qtycount - $qtyerp;
							echo "<font>SELISIH : <b>".rupiah($selisih)."</b></font>";
						}
						
				?>
				<table>
				<tr>
					<td style="width: 40px; background-color: #ffa597"></td>
					<td> : </td>
					<td>Sudah verifikasi</td>
				
				</tr>
				</table>
				<font style="color: red; font-weight: bold">Data diurutkan dari selisih terbesar</font>
				</div>
				
				
			</div>
		
			<div class="card-body">
			<div class="tables">
						
				<div class="table-responsive bs-example widget-shadow">				

				

					<div class="form-group">
					<p id="notif" style="color: red; font-weight: bold"></p>
					</div>
					<div class="form-inline"> 
					
					
					<div class="form-group"> 
					<table>
					<tr><td>Sort By : </td><td><select id="sort">
						<option value="1">Nama</option>
						<option value="2">Variant</option>
						<option value="3">SKU</option>
					</select></td></tr>
					
					</table>
					<br>
					
					<button onclick="cetakGeneric('<?php echo $_GET['m_pi']; ?>', '<?php echo $rack_name; ?>','<?php echo $dn; ?>');" class="btn btn-primary">Cetak Selisih</button>	
					<button onclick="cetakPdf('<?php echo $_GET['m_pi']; ?>', '<?php echo $rack_name; ?>','<?php echo $dn; ?>');" class="btn btn-warning">Cetak Selisih PDF</button>	
					<button onclick="testPrint();" class="btn btn-success">Test Print</button>	
					<br>
					<br>
					<input type="text" id="search" class="form-control" id="exampleInputName2" placeholder="Search" autofocus>
					<input type="hidden" id="search1">
					</div> 

					</div>
					  
					
					<table class="table table-bordered " id="example1" style="font-size: 13px">
						<thead>
							
								
						</thead>
						<tbody>
			
		<?php $list_line = "select distinct ((m_piline.qtycount + m_piline.qtysales) - (m_piline.qtyerp - m_piline.qtysalesout)) variant, m_piline.sku, m_piline.barcode ,m_piline.qtyerp, m_piline.qtysales, m_piline.qtycount, m_piline.qtysalesout, pos_mproduct.name, m_pi.status, m_piline.verifiedcount from m_pi inner join m_piline on m_pi.m_pi_key = m_piline.m_pi_key left join pos_mproduct on m_piline.sku = pos_mproduct.sku 
		where m_pi.m_pi_key = '".$_GET['m_pi']."' and m_pi.status = '2' order by variant asc";
		$no = 1;
		foreach ($connec->query($list_line) as $row1) {	
		// $variant = ($row1['qtycount'] + $row1['qtysales']) - ($row1['qtyerp'] - $row1['qtysalesout']);
		$variant = (int)$row1['variant'];
		$qtyerpreal = $row1['qtyerp'] - $row1['qtysalesout'];
		if($row1['verifiedcount'] == ''){
			
			$vc = 0;
		}else{
			
			$vc = $row1['verifiedcount'];
		}
		
		
		if($vc > 0){
			
			$color = 'style="background-color: #ffa597"';
			
		}else{
			$color = '';
			
		}
		
		if($row1['barcode'] != ""){
			
			$barc = '('.$row1['barcode'].')';
		}else{
			$barc = "";
			
		}
		
		
		?>
		
		
							
				
							<tr class="header" style="background: #e1e5fa">
				
								<td colspan="5"><font style="font-weight: bold"><?php echo $row1['sku']; ?> <?php echo $barc; ?></font> (<?php echo $row1['name']; ?>)</td>
							</tr>
	
							<tr class="header1" style="background: #e1e5fa">
								<td style="width: 150px">Counter</td>
								<td>ERP</td>
								<td>Sales</td>
								<td>Varian</td>
								<td>Verif</td>
								
								

							</tr>
							<tr class="header2" <?php echo $color; ?>>
	
								<td>
								
								<div class="form-inline"> 
								<input type="number" onkeydown="enterKey(this, event, '<?php echo $row1['sku']; ?>', '<?php echo $row1['name']; ?>','<?php echo $_GET['m_pi']; ?>');" name="qtycount<?php echo $row1['sku']; ?>" id="qtycount<?php echo $row1['sku']; ?>" class="form-control" value="<?php echo $row1['qtycount']; ?>"> 
								<!--<button type="button" id="btn-verifikasi" style="background: green; color: white" onclick="changeQty('<?php echo $row1['sku']; ?>', '<?php echo $row1['name']; ?>');" class="">Verifikasi</button>-->
										
								</div>		
										
								
								</td>
								<td><?php echo $qtyerpreal; ?></td>
								<td><?php echo $row1['qtysales']; ?></td>
								<td><?php echo $variant; ?></td>
								<td><?php echo $vc; ?></td>
							</tr>
					
				
					
		<?php $no++;} ?>
			
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
$(window).bind('beforeunload', function(){
  myFunction();
  return 'Apakah kamu yakin?';
});

function myFunction(){
     // Write your business logic here
     alert('Bye');
}


function enterKey(obj, e, sku, name, mpi) {
 var key = document.all ? window.event.keyCode : e.which;	
    if(key == 13) {         
       changeQty(sku, name, mpi);
       // I will forward to a new page here. Not an issue.
    }
}
					

// $(document).ready(function () {
	// $('#example1').DataTable();
	// $('#select').selectize({
		// sortField: 'text'
	// });	
// });

window.onbeforeunload = function () {
    return 'Are you sure? Your work will be lost. ';
};


function textbyline(str,intmax,stralign){
    var strresult='';
  if (stralign=='right'){
    strresult=str.padStart(intmax);
  } else if (stralign=='center'){
    var l = str.length;
    var w2 = Math.floor(intmax / 2);
    var l2 = Math.floor(l / 2);
    var s = new Array(w2 - l2 + 1).join(" ");
    str = s + str + s;
    if (str.length < intmax)
    {
        str += new Array(intmax - str.length + 1).join(" ");
    }
    strresult=str;
  } else {
    strresult=str;
  }
  return strresult;
};


function print_text(html){
	// console.log(html);
	$.ajax({
		url: "print.php",
		type: "POST",
		data : {html: html},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);

			$('#notif').html("Proses print");
			
			
		}
	});
}



document.getElementById("search").addEventListener("change", function() {
var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  table = document.getElementById("example1");
  tr = table.getElementsByClassName("header");
  trr = table.getElementsByClassName("header1");
  trrr = table.getElementsByClassName("header2");
   // tr.style.display = "none";
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
  
    if (td) {
      txtValue = td.textContent || td.innerText;
     
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        trr[i].style.display = "";
        trrr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
        trr[i].style.display = "none";
        trrr[i].style.display = "none";
      }
    }       
  }
	
	
	
});


document.getElementById("search").addEventListener("keyup", function() {
var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  table = document.getElementById("example1");
  tr = table.getElementsByClassName("header");
  trr = table.getElementsByClassName("header1");
  trrr = table.getElementsByClassName("header2");
   // tr.style.display = "none";
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
  
    if (td) {
      txtValue = td.textContent || td.innerText;
     
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        trr[i].style.display = "";
        trrr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
        trr[i].style.display = "none";
        trrr[i].style.display = "none";
      }
    }       
  }
	
	
	
});


function filterTable(sku){
	var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search1");
  filter = sku.toUpperCase();
  table = document.getElementById("example1");
  tr = table.getElementsByClassName("header");
  trr = table.getElementsByClassName("header1");
  trrr = table.getElementsByClassName("header2");
   // tr.style.display = "none";
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
  
    if (td) {
      txtValue = td.textContent || td.innerText;
     
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        trr[i].style.display = "";
        trrr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
        trr[i].style.display = "none";
        trrr[i].style.display = "none";
      }
    }       
  }
	
}


function syncErp(mpi){
	
	
	$.ajax({
		url: "api/action.php?modul=inventory&act=sync_erp",
		type: "POST",
		data : {mpi: mpi},
		beforeSend: function(){
			$('#notif'+mpi).html("Sistem sedang melakukan sync, jangan refresh halaman sebelum selesai..");
		},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);
			console.log(dataResult);
			if(dataResult.result=='2'){
				$('#notif'+mpi).html(dataResult.msg);
				$("#example").load(" #example");
			}else if(dataResult.result=='1'){
				$('#notif'+mpi).html("<font style='color: green'>"+dataResult.msg+"</font>");
				$("#example1"+mpi).load(" #example1"+mpi);
			}
			else {
				$('#notif'+mpi).html("Gagal sync coba lagi nanti!");
			}
			
		}
	});
	
	
}



function changeQty(sku, nama, mpi){
	var quan = document.getElementById("qtycount"+sku).value;
	var search = document.getElementById("search");
	
	if(parseInt(quan) >= 0){
		$.ajax({
		url: "api/action.php?modul=inventory&act=updateverifikasi&mpi="+mpi,
		type: "POST",
		data : {sku: sku, quan: quan, nama: nama},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);
			console.log(dataResult);
			if(dataResult.result=='0'){
				$('#notif').html(dataResult.msg);
				// $("#example").load(" #example");
			}else if(dataResult.result=='1'){
				
				
				
				$('#notif').html("<font style='color: green'>"+dataResult.msg+"</font>");
				$("#example1").load(" #example1");
				
				$("#info").load(location.href + " #info");
				search.value = '';
				search.focus();
				// $('#search1').val(sku);
				filterTable(sku);
			}
			else {
				$('#notif').html("Gagal sync coba lagi nanti!");
			}
			
		}
	});
		
	}else{
		$('#notif').html("Quantity tidak boleh kurang dari 0");
		
		
	}
	
	
}




function cetakGeneric(mpi, rn, dn){
		
		// alert(mpi+'<br>'+rn+'<br>'+dn);		
		var number = 0;	
		var no = 1;	
				
		var sort = document.getElementById("sort").value;
		// alert(html);
		$.ajax({
			url: "api/action.php?modul=inventory&act=cetak_generic",
			type: "POST",
			data : {mpi: mpi, sort: sort},
			success: function(dataResult){
			
				
				
var html = 'No Document  : '+dn+' \n\r';
   html += 'Rack         : '+rn+' \n\r \n\r';
html += 'No | Nama / SKU | '+textbyline('Count',6,'right')+' | '+textbyline('Varian',6,'right')+' \n\r';
			
				
				var dataResult = JSON.parse(dataResult);
				
				var panjang = dataResult.length;
				$('#notif').html("Proses print");
				
				for(let i = 0; i < dataResult.length; i++) {
						let data = dataResult[i];

						var sku = data.sku;
						var name = data.name;
						var qtyvariant = data.qtyvariant;
						var qtycount = data.qtycount;
						var barcode = data.barcode;
							
							
							html += no+'. '+name+'\n\r';
							html +=textbyline(sku,1,'left')+' '+textbyline(''+qtycount+'',19-sku.length,'right')+' '+textbyline(''+qtyvariant+'',10,'right');
							// html += "\n\r";
							// html += barcode;
							html += "\n\r";
							html += "\n\r";
							
						

								
			
							number++;
							no++;
							
							
							
							if(number == panjang){
							
								html+='\n\r';
								html+='\n\r';
								html+='\n\r';
								html+='\n\r';
								html+='\n\r';
								html+='\n\r';
								print_text(html);
								console.log(html);
							}
					
				
				}
				
				
				
				
				
				
			}
		});

				
}



function cetakPdf(mpi, rn, dn){
		
		// alert(mpi+'<br>'+rn+'<br>'+dn);		
		var number = 0;	
		var no = 1;	
				
		var sort = document.getElementById("sort").value;
		// alert(html);
		$.ajax({
			url: "api/action.php?modul=inventory&act=cetak_generic",
			type: "POST",
			data : {mpi: mpi, sort: sort},
			success: function(dataResult){
			
				
				
var html = 'No Document  : '+dn+' <br>';
   html += 'Rack         : '+rn+' <br>';
   
   
html += '<table><tr><td>No</td><td>SKU</td><td>Nama</td><td>'+textbyline('Count',6,'right')+'</td><td>'+textbyline('Varian',6,'right')+'</td></tr>';
			
				
				var dataResult = JSON.parse(dataResult);
				
				var panjang = dataResult.length;
				$('#notif').html("Proses print");
				
				for(let i = 0; i < dataResult.length; i++) {
						let data = dataResult[i];

						var sku = data.sku;
						var name = data.name;
						var qtyvariant = parseInt(data.qtyvariant);
						var qtycount = data.qtycount;
						var barcode = data.barcode;
							
							html += '<tr>';
							html += '<td>'+no+'</td><td>'+sku+'</td>';
							html +='<td>'+textbyline(name,1,'left')+'</td><td style="text-align: center"> '+textbyline(''+qtycount+'',19-sku.length,'right')+'</td><td style="text-align: center"> '+textbyline(''+qtyvariant+'',10,'right')+'</td>';
							// html += "\n\r";
							// html += barcode;
	
							html += '</tr>';
						

								
			
							number++;
							no++;
							
							
							
							if(number == panjang){
							
								html+='<br>';
								html+='<br>';

								var mywindow = window.open('', 'my div', 'height=600,width=800');
							/*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
							mywindow.document.write('<style>table, th, td {border: 1px solid black;border-collapse: collapse;}@media print{@page {size: potrait; width: 216mm;height: 280mm;}}table { page-break-inside:auto }tr{ page-break-inside:avoid; page-break-after:auto }</style>');
							mywindow.document.write(html);

					
							mywindow.print();
								
							}
					
				
				}
				
				
				
				html += '</table>';
				
				
			}
		});

				
}






function testPrint(){
// const process = require('child_process');
var sku = '0877878330032';
var sku1 = '011827364';
var sku2 = '1200098';
var count = '5';
var count1 = '22';
var html = 'Tes Print Generic Text\n\r';
html+='kiri';
html+=textbyline('tengah', 10, 'center')+'\n\r'; //rumus 22 - sku.length
html+=textbyline(sku,1,'left')+''+textbyline(count,22-sku.length,'right')+' '+textbyline('15',9,'right')+'\n';
html+=textbyline(sku2,1,'left')+''+textbyline(count,22-sku2.length,'right')+' '+textbyline('15',9,'right')+'\n';
html+=textbyline(sku1,1,'left')+''+textbyline(count1,22-sku1.length,'right')+' '+textbyline('15',9,'right')+'\n';
html+='\n';
html+='\n';
html+='\n';
html+='\n';
html+='\n';
html+='\n';

print_text(html);
console.log(html);

// $.ajax({
		// url: "print.php",
		// type: "POST",
		// data : {html: html},
		// success: function(dataResult){
			// var dataResult = JSON.parse(dataResult);

			// $('#notif').html("Proses print");
			
			
		// }
	// });



}




</script>







</div>
<?php include "components/fff.php"; ?>