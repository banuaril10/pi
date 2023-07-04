<?php include "config/koneksi.php"; ?>
<?php include "components/main.php"; ?>
<?php include "components/sidebar.php"; ?>
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
				<h4>LIST IMPORTER</h4>
			</div>
			<div class="card-body">
			<div class="tables">			
				<div class="table-responsive bs-example widget-shadow">	
				
				<div class="pesan " id="pesan"></div>
                <div class="sebentar" id="sebentar"></div>
				
				<form method="post" enctype="multipart/form-data" action="import.php">

                  <div class="form-group">
                    <label><b>Upload File</b></label>
                    <input type="file" name="import" id="import" class="form-control">
                  </div> 

                  <div id="process" style="display:none;">
                    <div class="progress mt-3">
                      <div class="progress-bar progress-bar-striped" role="progressbar" style=""  aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                  <button class="btn btn-primary" id="btn-form" type="submit">Import</button>
				  <!--<button type="button" class="btn btn-success" type="button" id="checkallinv">Check All</button>
				<button type="button" class="btn btn-danger" id="prosesall" onclick="prosesAll();" >Process Selected</button>-->
				
				<br>
				<select id="filename">
					<?php $fn = "select filename from inv_temp where date(tanggal) = date(now()) and status = '0' group by filename,status ";
						$no = 1;
						foreach ($connec->query($fn) as $rows) { ?>
							
							<option value="<?php echo $rows['filename']; ?>"><?php echo $rows['filename']; ?></option>
							
						<?php } ?>
						
					 </select>
				
					 <button class="btn btn-danger" onclick="prosesInv();" id="button-proses" type="button">Proses File</button>
				
				
                </form>
				
			

			
				
				
				<p id="notif1" style="color: red; font-weight: bold"></p>	
							
					<table class="table table-bordered" id="example">
						<thead>
							<tr>
								<th></th>
								<th>No</th>
								<th>SKU</th>
								<th>Name</th>
								<th>Quantity</th>
								<th>Tgl</th>
								<th>Nama File</th>
							</tr>
						</thead>
						<tbody>
					
						
						<?php 
						$sql_list = "select a.filename, a.sku, b.name, sum(a.qty) as quantity, date(a.tanggal) as tgl from inv_temp a left join pos_mproduct b on a.sku = b.sku where a.status = '0' and date(a.tanggal) = date(now()) group by a.sku, b.name, date(a.tanggal), filename";
						$no = 1;
						foreach ($connec->query($sql_list) as $row) {
						
						?>
						
						
							<tr>
								<th scope="row"><input type="checkbox" id="checkbox1" name="checkbox1[]" value="<?php echo $row['sku']; ?>|<?php echo $row['quantity']; ?>|<?php echo $row['tgl']; ?>|<?php echo $row['filename']; ?>"></th>
								<td ><?php echo $no; ?></td>
								<td><?php echo $row['sku']; ?></td>
								<td><?php echo $row['name']; ?> </td>
								<td><?php echo $row['quantity']; ?> </td>
								<td><?php echo $row['tgl']; ?> </td>
								<td><?php echo $row['filename']; ?> </td>
								
				
								
							</tr>
							
							
							
							
						<?php unset($elements); $no++;} ?>
   
   
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
// localStorage.removeItem('count');
function prosesInvtemp(sku, qty, tgl, jumlahpi, filename){

	// alert(sku+' '+qty+ ' '+tgl+' '+jumlahpi);
	$.ajax({
		url: "api/action.php?modul=inventory&act=proses_inv_temp",
		type: "POST",
		data: {sku:sku ,qty:qty, tgl:tgl, jumlahpi:jumlahpi, filename:filename},
		beforeSend:function(){
              $('#sebentar').html("<div class='alert alert-danger mb-3' role='alert'>Proses...</div>");
           
        },  
		success: function(dataResult){
			// console.log(dataResult);
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result=='1'){
				
				 $('#sebentar').html("<div class='alert alert-danger mb-3' role='alert'>Proses "+dataResult.sku+" </div>");
				// example
			}
			// else {
				// $('#notif').html(dataResult.msg);
			// }
			
		}
	});
		
}



function prosesInv(){
	var filename = document.getElementById("filename").value;
	// alert(sku+' '+qty+ ' '+tgl+' '+jumlahpi);
	$.ajax({
		url: "api/action.php?modul=inventory&act=proses_inv_temps",
		type: "POST",
		data: {filename:filename},
		beforeSend:function(){
              $('#sebentar').html("<div class='alert alert-danger mb-3' role='alert'>Sedang memproses file...</div>");
				document.getElementById("button-proses").disabled = true;
        },  
		success: function(dataResult){
			// console.log(dataResult);
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result=='1'){
				document.getElementById("button-proses").disabled = false;
				$('#sebentar').html("<div class='alert alert-danger mb-3' role='alert'>"+dataResult.msg+"</div>");
				$('#example').load(" #example");
			}
			
			document.getElementById("button-proses").disabled = false;
			// else {
				// $('#notif').html(dataResult.msg);
			// }
			
		}
	});
		
}



function prosesAll(){
				// localStorage.setItem('count', 0);
				document.getElementById("prosesall").disabled = true;

			<!-- alert("approve cuy"); -->
				localStorage.removeItem('no');
				var notif = "";
				var no = 1;
				var arraycuy = [];
				var checkboxes = document.querySelectorAll('input[id=checkbox1]:checked');
				var jumlahpi = checkboxes.length;
				if(checkboxes.length == 0){
					alert("Checklist minimal 1");
					document.getElementById("prosesall").disabled = false;
				}else{
					for (var i = 0; i < checkboxes.length; i++) {
					<!-- arraycuy.push(checkboxes[i].value) -->
					var res = checkboxes[i].value.split("|");
					
					var sku = res[0];
					var qty = res[1];
					var tgl = res[2];
					var filename = res[3];
					
					<!-- alert(res[0]); -->
					
					
					prosesInvtemp(sku, qty, tgl, jumlahpi, filename);
					
					
				}
				}
				// location.reload();
}

// document.getElementById("checkallinv").addEventListener("click", function() {
				// <!-- alert("Muncul"); -->
				
				// var checkboxes = document.querySelectorAll('input[id="checkbox1"]');

				// for (var i = 0; i < checkboxes.length; i++) {
				// if (checkboxes[i].type == 'checkbox'){
					// if(checkboxes[i].checked == true){
						
						// checkboxes[i].checked = false;
					// }else if(checkboxes[i].checked == false){
						// checkboxes[i].checked = true;
						
					// }
					
					
					// }	
				// }	

			// });
    $(document).ready(function(){  

      $('#import_excel').on('submit', function(event){  
       event.preventDefault(); 

          // Memulai AJAX 
          $.ajax({  
            url:"import.php",  
            method:"POST",  
            data:new FormData(this),  
            contentType:false,  
            processData:false,
            beforeSend:function(){
              $('#sebentar').html("<div class='alert alert-danger mb-3' role='alert'>Sebentar ya...</div>");
              $('#btn-form').hide();
              $('#process').css('display', 'block');
            },  
            success:function(data){
			console.log(data);
			var dataResult = JSON.parse(data);
			
			if(dataResult.result == 0){
				
				$('#sebentar').html("<div class='alert alert-danger mb-3' role='alert'>"+dataResult.msg+"</div>");
			}else{
				
				location.reload();	
			}
			
				
				
             // var percentage = 0;

             // var timer = setInterval(function(){
               // percentage = percentage + 20;
               // progress_bar_process(percentage, timer, dataResult.msg);
             // }, 1000);
			 
			 
			 

             // console.log(data);
           },

           error:function(data){

            $('#sebentar').html("<div class='alert alert-danger mb-3' role='alert'>Server Error!</div>");

            console.log(data);

          }  
        });  
        });  

      // function progress_bar_process(percentage, timer, msg){
       // $('.progress-bar').css('width', percentage + '%');
       // if(percentage > 100){
        // clearInterval(timer);
        // $('#import_excel')[0].reset();
        // $('#process').css('display', 'none');
        // $('.progress-bar').css('width', '0%');
        // $('#pesan').html("<div class='alert alert-primary mb-3' role='alert'>"+msg+"</div>");
		
        // $('#sebentar').html("");
        // $('#example').load(" #example");
        // setTimeout(function(){
         // $('#pesan').html('');
         // $('#btn-form').show();
       // }, 3000);
      // }
    // }

  });

function syncMaster(){
	

	
	$.ajax({
		url: "api/action.php?modul=inventory&act=sync_user",
		type: "GET",
		beforeSend: function(){
			 $('#sync').prop('disabled', true);
			$('#notif1').html("<font style='color: red'>Sedang melakukan sync, sabar ya..</font>");
			$("#overlay").fadeIn(300);
		},
		success: function(dataResult){
			// console.log(dataResult);
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result=='1'){
				$('#notif1').html("<font style='color: green'>"+dataResult.msg+"</font>");
				$("#example").load(" #example");
				 $('#sync').prop('disabled', false);
				 $("#overlay").fadeOut(300);
			}
			// else {
				// $('#notif').html(dataResult.msg);
			// }
			
		}
	});
	
}

</script>
</div>
<?php include "components/fff.php"; ?>