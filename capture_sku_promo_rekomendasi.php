<?php include "config/koneksi.php"; ?>
<?php session_start();
if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
	$org_key = $_SESSION['org_key'];
	$username = $_SESSION['username'];
}else{
	header("Location: index.php");
}

$get_nama_toko = "select * from ad_morg where postby = 'SYSTEM'";
$resultss = $connec->query($get_nama_toko);
foreach ($resultss as $r) {
	$storecode = $r["value"];	
	$org_key = $r['ad_morg_key'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Store Apps</title>
    <link rel="stylesheet" href="styles/css/bootstrap.css">
    <link rel="stylesheet" href="styles/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="styles/css/selectize4.css">
    <link rel="stylesheet" href="styles/css/font-awesome.css">
	
	<style>
		.selectize {
			
			border-color: #000;
			margin-bottom: 10px;
			
		}
	</style>

    <link rel="stylesheet" href="assets/vendors/iconly/bold.css">

    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/css/app.css">
	<script src="styles/js/jquery-3.5.1.js"></script>	
</head>
<body>


<?php include "components/sidebar.php"; ?>

<style>
.progress {
  height: 20px;
  border-radius: 4px;
  margin: 10px 0;
  background-color: #e6e8ec;
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
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4>REQUEST HARGA PROMO</h4>
			</div>
<div class="card-body">

  <!-- FORM UPLOAD -->
  <form id="formPromo" enctype="multipart/form-data">
    <div class="row mb-3">
		<input type="hidden" name="org_key" value="<?php echo $org_key; ?>">
      <div class="col-md-3">
        <label>SKU</label>
        <input type="text" name="sku" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label>Stock</label>
        <input type="number" name="stock" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label>Harga Promo Rekomendasi</label>
        <input type="number" name="harga_promo_rekomendasi" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label>Upload Foto (maks 2)</label>
        <input type="file" name="foto[]" id="foto" class="form-control" accept="image/*" multiple required>
        <small class="text-muted">*Maksimum 2 foto</small>
      </div>
    </div>

    <button type="submit" class="btn btn-primary mt-2">
      <i class="bi bi-upload"></i> Upload Promo
    </button>
  </form>

  <hr>

  <!-- TABEL LIST -->
  <div class="table-responsive mt-3">
    <table class="table table-bordered table-striped" id="promoTable" style="width: 100%">
      <thead>
        <tr>
          <th>No</th>
          <th>SKU</th>
          <th>Stock</th>
          <th>Harga Promo Rekomendasi</th>
          <th>Harga Promo Approved</th>
          <th>Foto</th>
          <th>Tanggal</th>
          <th>Status Approval</th>
        </tr>
      </thead>
      <tbody id="promoList">
        <!-- data akan diisi lewat JS -->
      </tbody>
    </table>
  </div>

</div>

	</div>
</div>
</div>
</div>


<script type="text/javascript">
$(document).ready( function () {
    $('#example').DataTable({
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],
    });
} );



</script>
</div>



<script>
$(document).ready(function () {
  loadList();

  // === Submit form ===
  $("#formPromo").on("submit", function (e) {
    e.preventDefault();

    let files = $("#foto")[0].files;
    if (files.length > 2) {
      alert("Maksimum hanya boleh 2 foto!");
      return;
    }

    let formData = new FormData(this);
	var url = "<?php echo $base_url.'/store/rekomendasi_promo/upload_promo.php'; ?>";
    $.ajax({
      url: url,
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $("#formPromo button").prop("disabled", true).text("Uploading...");
      },
      success: function (response) {
        console.log(response);
        alert(response.message);
        $("#formPromo")[0].reset();
        loadList();
      },
      error: function (xhr) {
        console.error(xhr.responseText);
        alert("Gagal upload promo!");
      },
      complete: function() {
        $("#formPromo button").prop("disabled", false).text("Upload Promo");
      }
    });
  });

  // === Ambil data list dari API ===
  function loadList() {
	var org_key = "<?php echo $org_key; ?>";
	var url = "<?php echo $base_url . '/store/rekomendasi_promo/get_promo_list.php?org_key='; ?>" + org_key;
    $.ajax({
      url: url,
      type: "GET",
      dataType: "json",
      success: function (data) {
        let html = "";
        if (data.length === 0) {
          html = `<tr><td colspan="8" class="text-center text-muted">Belum ada data promo</td></tr>`;
        } else {
          data.forEach((row, i) => {
            let fotoHTML = "";
            if (row.foto && row.foto.length > 0) {
              row.foto.forEach(f => {
                fotoHTML += `<img src="${f}" alt="foto" width="60" class="me-1 rounded">`;
              });
            }
            html += `
              <tr>
                <td>${i + 1}</td>
                <td>${row.sku}</td>
                <td>${row.stock}</td>
                <td>${row.harga_promo_rekomendasi}</td>
                <td>${row.harga_promo_approved}</td>
                <td>${fotoHTML}</td>
                <td>${row.tanggal ?? '-'}</td>
                <td>${row.status_approval ?? '-'}</td>
              </tr>
            `;
          });
        }
        $("#promoList").html(html);
      },
      error: function (xhr) {
        console.error(xhr.responseText);
      }
    });
  }
});
</script>




<?php include "components/fff.php"; ?>