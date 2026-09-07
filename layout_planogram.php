<?php 
include "config/koneksi.php"; 
?>
<?php 
session_start();
if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    $org_key = $_SESSION['org_key'];
    $username = $_SESSION['username'];
}else{
    header("Location: index.php");
}

// Ambil data toko
$toko = '';
$value = '';
$cek_brand = "select * from ad_morg where postby = 'SYSTEM'";
foreach ($connec->query($cek_brand) as $row) {
    $toko = $row['name'];
    $value = $row['value'];
}

// Get store code
$storeCode = isset($_GET['store']) ? $_GET['store'] : $value;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Store Apps - Planogram Display</title>
    <link rel="stylesheet" href="styles/css/bootstrap.css">
    <link rel="stylesheet" href="styles/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="styles/css/selectize4.css">
    <link rel="stylesheet" href="styles/css/font-awesome.css">
    <link rel="stylesheet" href="assets/vendors/iconly/bold.css">
    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/css/app.css">
    <script src="styles/js/jquery-3.5.1.js"></script>
    
    <style>
        .planogram-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .planogram-title {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
            border-bottom: 3px solid #629584;
            padding-bottom: 15px;
        }
        .planogram-image-wrapper {
            text-align: center;
            padding: 20px;
            background: #fafafa;
            border-radius: 8px;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .planogram-image-wrapper img {
            max-width: 100%;
            max-height: 80vh;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .planogram-image-wrapper img:hover {
            transform: scale(1.02);
        }
        .store-info {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background: #e8f0fe;
            border-radius: 8px;
            color: #333;
        }
        .store-info strong {
            color: #629584;
        }
        .btn-refresh {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 30px;
            background: #6c757d;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            transition: background 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn-refresh:hover {
            background: #5a6268;
            color: white;
        }
        .no-image {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        .no-image i {
            font-size: 60px;
            display: block;
            margin-bottom: 20px;
            color: #ddd;
        }
        .loading-spinner {
            display: inline-block;
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #629584;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 30px;
            background: #629584;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            transition: background 0.3s;
            border: none;
            margin-left: 10px;
        }
        .btn-back:hover {
            background: #4a7a6b;
            color: white;
        }
        #overlay {
            display: none;
        }
    </style>
</head>
<body>

<?php include "components/sidebar.php"; ?>

<div id="app">
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>

        <?php include "components/hhh.php"; ?>

        <!-- CONTENT AREA -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="bi bi-image"></i> PLANOGRAM DISPLAY</h4>
                    </div>
                    <div class="card-body">
                        <div class="planogram-container">
                            <div class="planogram-title">
                                <h2><i class="bi bi-shop"></i> <?php echo htmlspecialchars($toko); ?></h2>
                                <p class="text-muted">Store Code: <?php echo htmlspecialchars($storeCode); ?></p>
                            </div>
                            
                            <div id="planogramContent">
                                <!-- Loading -->
                                <div class="text-center" id="loadingIndicator">
                                    <div class="loading-spinner"></div>
                                    <p class="mt-3">Loading planogram...</p>
                                </div>
                            </div>
                            
                            <div style="text-align: center; margin-top: 30px;">
                                <button onclick="loadPlanogram()" class="btn-refresh">
                                    <i class="bi bi-arrow-clockwise"></i> Refresh
                                </button>
                                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn-back">
                                    <i class="bi bi-house"></i> Home
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END CONTENT AREA -->
        
    </div>
</div>

<!-- Modal untuk fullscreen gambar -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content" style="background: rgba(0,0,0,0.9);">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center justify-content-center">
                <img id="fullscreenImage" src="" alt="Planogram Fullscreen" style="max-width: 100%; max-height: 90vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<script src="styles/js/bootstrap.bundle.min.js"></script>
<script>
var currentStore = '<?php echo $storeCode; ?>';

function loadPlanogram() {
    $('#loadingIndicator').show();
    $('#planogramContent').html(`
        <div class="text-center">
            <div class="loading-spinner"></div>
            <p class="mt-3">Loading planogram...</p>
        </div>
    `);
    
    $.ajax({
        url: 'https://mkt.idolmartidolaku.com/api/api_get_planogram.php',
        type: 'GET',
        data: {
            store_code: currentStore
        },
        dataType: 'json',
        success: function(response) {
			console.log(response); // Tambahkan ini untuk debugging response dari API
            if (response.success && response.image_url) {
                var html = `
                    <div class="planogram-image-wrapper">
                        <img src="${response.image_url}" 
                             alt="Planogram Display" 
                             class="img-clickable"
                             onclick="openFullscreen('${response.image_url}')"
                             onerror="this.onerror=null; this.src='images/no-image.png';">
                    </div>
                    
                `;
                $('#planogramContent').html(html);
            } 
        },
        error: function(xhr, status, error) {
            var html = `
                <div class="no-image">
                    <i class="bi bi-exclamation-triangle" style="color: #dc3545;"></i>
                    <h4 style="color: #dc3545;">Error Loading Image</h4>
                    <p class="text-danger">${error || 'Failed to load planogram'}</p>
                    <p><small>Status: ${status}</small></p>
                    <button onclick="loadPlanogram()" class="btn btn-primary mt-3">
                        <i class="bi bi-arrow-clockwise"></i> Try Again
                    </button>
                </div>
            `;
            $('#planogramContent').html(html);
        }
    });
}

function openFullscreen(src) {
    window.open(src, '_blank');
}

// Load on page ready
$(document).ready(function() {
    loadPlanogram();
});

// Auto refresh every 5 minutes
setInterval(function() {
    loadPlanogram();
}, 300000);
</script>

<?php include "components/fff.php"; ?>
</body>
</html>