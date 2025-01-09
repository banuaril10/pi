<?php include "config/koneksi.php"; ?>
<?php include "components/main.php"; ?>
<?php include "components/sidebar.php";

?>

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
            <h4>SYNC DATA</h4>
          </div>



          <div class="card-body">

            <!--<button type="button" onclick="syncPromo();" class="btn btn-primary">Sync Promo</button>-->

            <div class="tables">
              <p id="notif" style="color: red; font-weight: bold"></p>
              <!--<button onclick="turnOn();" class="switch">On</button>
            <button onclick="turnOff();" class="switch1">Off</button>
            
            
            <div id="qr-reader" style="width: 100%"></div>-->

              <div class="table-responsive bs-example widget-shadow">

                <div class="container-fluid">


                  <?php

                  $total_kategori = 0;
                  $sql = "SELECT count(*) as total FROM in_master_category";
                  foreach ($connec->query($sql) as $row) {
                    $total_kategori = $row['total'];
                  }

                  $total_product = 0;
                  $sql = "SELECT count(*) as total FROM pos_mproduct";
                  foreach ($connec->query($sql) as $row) {
                    $total_product = $row['total'];
                  }

                  $total_stock = 0;
                  $sql = "SELECT count(*) as total FROM pos_mproduct where stockqty > 0";
                  foreach ($connec->query($sql) as $row) {
                    $total_stock = $row['total'];
                  }

                  $total_price = 0;
                  $sql = "SELECT count(*) as total FROM pos_mproduct where price > 0";
                  foreach ($connec->query($sql) as $row) {
                    $total_price = $row['total'];
                  }

                  $total_barcode = 0;
                  $sql = "SELECT count(*) as total FROM pos_mproduct where barcode != ''";
                  foreach ($connec->query($sql) as $row) {
                    $total_barcode = $row['total'];
                  }

                  $total_shortcut = 0;
                  $sql = "SELECT count(*) as total FROM pos_mproduct where shortcut != ''";
                  foreach ($connec->query($sql) as $row) {
                    $total_shortcut = $row['total'];
                  }

                  $total_reguler = 0;
                  $sql = "SELECT count(*) as total FROM pos_mproductdiscount ";
                  foreach ($connec->query($sql) as $row) {
                    $total_reguler = $row['total'];
                  }

                  $total_buyget = 0;
                  $sql = "SELECT count(*) as total FROM pos_mproductbuyget ";
                  foreach ($connec->query($sql) as $row) {
                    $total_buyget = $row['total'];
                  }

                  $total_grosir = 0;
                  $sql = "SELECT count(*) as total FROM pos_mproductdiscountgrosir_new ";
                  foreach ($connec->query($sql) as $row) {
                    $total_grosir = $row['total'];
                  }

                  $total_code = 0;
                  $sql = "SELECT count(*) as total FROM pos_mproductdiscountmember ";
                  foreach ($connec->query($sql) as $row) {
                    $total_code = $row['total'];
                  }

                  $total_bank = 0;
                  $sql = "SELECT count(*) as total FROM pos_mbank ";
                  foreach ($connec->query($sql) as $row) {
                    $total_bank = $row['total'];
                  }

                  $total_edc = 0;
                  $sql = "SELECT count(*) as total FROM pos_medc ";
                  foreach ($connec->query($sql) as $row) {
                    $total_edc = $row['total'];
                  }

                  $total_profile = 0;
                  $sql = "SELECT count(*) as total FROM ad_morg ";
                  foreach ($connec->query($sql) as $row) {
                    $total_profile = $row['total'];
                  }

                  $total_user = 0;
                  $sql = "SELECT count(*) as total FROM ad_muser ";
                  foreach ($connec->query($sql) as $row) {
                    $total_user = $row['total'];
                  }

                  $total_user_hris = 0;
                  $sql = "SELECT count(*) as total FROM m_pi_hris ";
                  foreach ($connec->query($sql) as $row) {
                    $total_user_hris = $row['total'];
                  }

                  $total_rack = 0;
                  $sql = "SELECT count(*) as total FROM pos_mproduct where rack != ''";
                  foreach ($connec->query($sql) as $row) {
                    $total_rack = $row['total'];
                  }

                  ?>


                  <br>
                  <div class="row">
                    <div class="col-md-2">
                      <button type="button" id="btnproductcategorysync" class="btn btn-success btn-lg btn-block"><i
                          class="fa fa-list"></i>
                        Kategori <br>
                        Total : <?php echo $total_kategori; ?></button>
                    </div>
                    <div class="col-md-2">
                      <button type="button" id="btnsyncitems" class="btn btn-success btn-lg btn-block"><i
                          class="fa fa-cubes"></i>
                        Product<br>
                        Total : <?php echo $total_product; ?></button>
                    </div>
                    <div class="col-md-2">
                      <button type="button" id="btnproductstocksync" class="btn btn-success btn-lg btn-block"><i
                          class="fa fa-cubes"></i>
                        Stock<br>
                        Total : <?php echo $total_stock; ?></button>
                    </div>
                    <div class="col-md-2">
                      <button type="button" id="btnproductpricesync" class="btn btn-success btn-lg btn-block"><i
                          class="fa fa-money"></i>
                        Price<br>
                        Total : <?php echo $total_price; ?></button>
                    </div>
                    <div class="col-md-2">
                      <button type="button" id="btnproductbarcode" class="btn btn-success btn-lg btn-block"><i
                          class="fa fa-barcode"></i>
                        Barcode<br>
                        Total : <?php echo $total_barcode; ?></button>
                    </div>
                    <div class="col-md-2">
                      <button type="button" id="btnproductshortcut" class="btn btn-success btn-lg btn-block"><i
                          class="fa fa-barcode"></i>
                        Shortcut<br>
                        Total : <?php echo $total_shortcut; ?></button>
                    </div>
                  </div>

                  <br>

                  <div class="row">
                    <div class="col-md-2">
                      <button type="button" id="btnpromoreguler" class="btn btn-success btn-lg btn-block"><i
                          class="fa fa-tags"></i>
                        Promo Reguler<br>
                        Total : <?php echo $total_reguler; ?></button>
                    </div>
                    <div class="col-md-2">
                      <button type="button" id="btnpromobuyget" class="btn btn-success btn-lg btn-block"><i
                          class="fa fa-tags"></i>
                        Promo Buy & Get<br>
                        Total : <?php echo $total_buyget; ?></button>
                    </div>
                    <div class="col-md-2">
                      <button type="button" id="btnpromogrosir" class="btn btn-success btn-lg btn-block"><i
                          class="fa fa-tags"></i>
                        Promo Grosir<br>
                        Total : <?php echo $total_grosir; ?></button>
                    </div>
                    <div class="col-md-2">
                      <button type="button" id="btnpromocode" class="btn btn-success btn-lg btn-block"><i
                          class="fa fa-tags"></i>
                        Promo Code<br>
                        Total : <?php echo $total_code; ?></button>
                    </div>
                    <div class="col-md-2">
                      <button type="button" id="btnbanksync" class="btn btn-success btn-lg btn-block"><i
                          class="fa fa-money"></i>
                        Bank<br>
                        Total : <?php echo $total_bank; ?></button>
                    </div>
                    <div class="col-md-2">
                      <button type="button" id="btnedcsync" class="btn btn-success btn-lg btn-block"><i
                          class="fa fa-money"></i>
                        EDC<br>
                        Total : <?php echo $total_edc; ?></button>
                    </div>

                  </div>

                  <br>
                  <div class="row">

                    <div class="col-md-2">
                      <button type="button" id="btnprofilesync" class="btn btn-success btn-lg btn-block"><i
                          class="fa fa-user"></i>
                        Profile<br>
                        Total : <?php echo $total_profile; ?></button>
                    </div>
                    <div class="col-md-2">
                      <button type="button" id="btnusersync" class="btn btn-success btn-lg btn-block"><i
                          class="fa fa-user"></i>
                        User<br>
                        Total : <?php echo $total_user; ?></button>
                    </div>
                    <div class="col-md-2">
                      <button type="button" id="btnuserhrissync" class="btn btn-success btn-lg btn-block"><i
                          class="fa fa-user"></i> User
                        HRIS<br>
                        Total : <?php echo $total_user_hris; ?></button>
                    </div>
                    <div class="col-md-2">
                      <button type="button" id="btnracksync" class="btn btn-success btn-lg btn-block"><i
                          class="fa fa-user"></i>
                        Rack<br>
                        Total : <?php echo $total_rack; ?></button>
                    </div>
                  </div>
                  <br>


                </div>
               
                <script src="styles/js/product.js"></script>

    




              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


  </div>
</div>

</div>
<?php include "components/fff.php"; ?>