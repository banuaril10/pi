
// get cashier information
$("#loaderpos").html(
  "<img id='loading-image' src='./img/loading2.gif' alt='Loading...' />"
);


const btnproductcategorysync = document.getElementById(
  "btnproductcategorysync"
);
const btnpromoreguler = document.getElementById("btnpromoreguler");
const btnpromobuyget = document.getElementById("btnpromobuyget");
const btnpromogrosir = document.getElementById("btnpromogrosir");
const btnpromocode = document.getElementById("btnpromocode");
const btnbanksync = document.getElementById("btnbanksync");
const btnedcsync = document.getElementById("btnedcsync");
const btnusersync = document.getElementById("btnusersync");
const btnuserhrissync = document.getElementById("btnuserhrissync");
const btnprofilesync = document.getElementById("btnprofilesync");
const btnsyncitems = document.getElementById("btnsyncitems");
const btnproductstocksync = document.getElementById("btnproductstocksync");
const btnproductbarcode = document.getElementById("btnproductbarcode");
const btnproductshortcut = document.getElementById("btnproductshortcut");
const btnproductpricesync = document.getElementById("btnproductpricesync");
const btnracksync = document.getElementById("btnracksync");
const btnsynclpk = document.getElementById("btnsynclpk");

btnproductpricesync.addEventListener("click", async function (event) {
  $("#loaderpos").show();
  sync_price();
  
});

btnracksync.addEventListener("click", async function (event) {
  $("#loaderpos").show();
  sync_rack();

});

btnproductbarcode.addEventListener("click", async function (event) {
  $("#loaderpos").show();
  sync_barcode();

});

btnproductshortcut.addEventListener("click", async function (event) {
  $("#loaderpos").show();
  sync_shortcut();

});

btnproductcategorysync.addEventListener("click", async function (event) {
  $("#loaderpos").show();
  sync_category();
  sync_category_sub();
  sync_category_subitem();

});

btnpromoreguler.addEventListener("click", async function (event) {
  $("#loaderpos").show();
  sync_promo_reguler();

});

btnpromobuyget.addEventListener("click", async function (event) {
  $("#loaderpos").show();
  sync_promo_buyget();

});

btnpromogrosir.addEventListener("click", async function (event) {
  $("#loaderpos").show();
  sync_promo_grosir();
});

btnpromocode.addEventListener("click", async function (event) {
  $("#loaderpos").show();
  sync_promo_code();
});

btnbanksync.addEventListener("click", async function (event) {
  $("#loaderpos").show();
  sync_bank();
});

btnedcsync.addEventListener("click", async function (event) {
  $("#loaderpos").show();
  sync_edc();
});

btnusersync.addEventListener("click", async function (event) {
  $("#loaderpos").show();
  sync_users();
});

btnuserhrissync.addEventListener("click", async function (event) {
  $("#loaderpos").show();
  sync_users_hris();
});



btnprofilesync.addEventListener("click", async function (event) {
  $("#loaderpos").show();
  sync_profile();
});

btnsyncitems.addEventListener("click", async function (event) {
  $("#loaderpos").show();
  sync_items();
});

btnproductstocksync.addEventListener("click", async function (event) {
  $("#loaderpos").show();
  sync_stock();
});


function sync_price() {
  $.ajax({
    url: "api/cyber/sync_price.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);
      var dataResult = JSON.parse(dataResult);
      // alert(dataResult.message);
      $("#loaderpos").hide();
      location.reload();
    },
  });
}

function sync_rack() {
  $.ajax({
    url: "api/cyber/sync_rack.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);
      var dataResult = JSON.parse(dataResult);
      $("#loaderpos").hide();
    },
  });
}

function sync_barcode() {
  $.ajax({
    url: "api/cyber/sync_barcode.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);
      var dataResult = JSON.parse(dataResult);
      $("#loaderpos").hide();
    },
  });
}

function sync_shortcut() {
  $.ajax({
    url: "api/cyber/sync_shortcut.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);
      var dataResult = JSON.parse(dataResult);
      $("#loaderpos").hide();
    },
  });
}

function sync_stock() {
  $("#statussync").html("proses sync stock...");
  $.ajax({
    url: "api/cyber/sync_stock.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);
      var dataResult = JSON.parse(dataResult);
      $("#loaderpos").hide();
    },
  });
}

function sync_category() {
  $("#statussync").html("proses sync category...");
  $.ajax({
    url: "api/cyber/sync_category.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);
    },
  });
}

function sync_category_sub() {
  $("#statussync").html("proses sync sub category...");
  $.ajax({
    url:
      "api/cyber/sync_category_sub.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);
    },
  });
}

function sync_category_subitem() {
  $("#statussync").html("proses sync sub category items...");
  $.ajax({
    url:
      "http://" +
      api_storeapps +
      "/pi/api/cyber/sync_category_subitem.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);

      var dataResult = JSON.parse(dataResult);
      $("#loaderpos").hide();
      //$('#statussync').html(dataResult.message);
    },
  });
}

function sync_promo_reguler() {
  $.ajax({
    url:
      "api/cyber/sync_promo_reguler.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);

      var dataResult = JSON.parse(dataResult);
      $("#loaderpos").hide();
    },
  });
}

function sync_promo_buyget() {
  $.ajax({
    url:
      "api/cyber/sync_promo_buyget.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);

      var dataResult = JSON.parse(dataResult);
      $("#loaderpos").hide();
    },
  });
}

function sync_promo_grosir() {
  $.ajax({
    url:
      "api/cyber/sync_promo_grosir.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);

      var dataResult = JSON.parse(dataResult);
      $("#loaderpos").hide();
    },
  });
}

function sync_promo_code() {
  $.ajax({
    url: "api/cyber/sync_promo_code.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);

      var dataResult = JSON.parse(dataResult);
      $("#loaderpos").hide();
    },
  });
}

function sync_bank() {
  $.ajax({
    url: "api/cyber/sync_bank.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);

      var dataResult = JSON.parse(dataResult);
      $("#loaderpos").hide();
    },
  });
}

function sync_edc() {
  $.ajax({
    url: "api/cyber/sync_edc.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);

      var dataResult = JSON.parse(dataResult);
      $("#loaderpos").hide();
    },
  });
}

function sync_users() {
  $.ajax({
    url: "api/cyber/sync_users.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);

      var dataResult = JSON.parse(dataResult);
      $("#loaderpos").hide();
    },
  });
}

function sync_users_hris() {
  $.ajax({
    url: "api/cyber/sync_users_hris.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);

      var dataResult = JSON.parse(dataResult);
      $("#loaderpos").hide();
    },
  });
}

function sync_profile() {
  $("#statussync").html("proses sync category...");
  $.ajax({
    url: "api/cyber/sync_profile.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);

      var dataResult = JSON.parse(dataResult);
      $("#loaderpos").hide();
    },
  });
}

function sync_items() {
  $("#statussync").html("proses sync category...");
  $.ajax({
    url: "api/cyber/sync_items.php",
    type: "GET",
    success: function (dataResult) {
      console.log(dataResult);

      var dataResult = JSON.parse(dataResult);
      $("#loaderpos").hide();
      location.reload();
    },
  });
}






function syncSalesHeader() {
  var date = $("#date").val();
  var url = "api/cyber/sync_sales_header.php?date=" + date;
  $.get(url, function (data, status) {
    console.log(data);
    // alert("Proses sync, klik tombol refresh setelah beberapa saat..");
  });
}

function syncSalesLine() {
  var date = $("#date").val();
  var url = "api/cyber/sync_sales_line.php?date=" + date;
  $.get(url, function (data, status) {
    console.log(data);
    // alert("Proses sync, klik tombol refresh setelah beberapa saat..");
  });
}

function syncSalesCashierBalance() {
  var date = $("#date").val();
  var url =
    "api/cyber/sync_sales_cashierbalance.php?date=" + date;
  $.get(url, function (data, status) {
    console.log(data);
    // alert("Proses sync, klik tombol refresh setelah beberapa saat..");
  });
}

function syncSalesDeleted() {
  var date = $("#date").val();
  var url =
    "api/cyber/sync_sales_deleted.php?date=" + date;
  $.get(url, function (data, status) {
    console.log(data);
    // alert("Proses sync, klik tombol refresh setelah beberapa saat..");
  });
}

function syncSalesShopSales() {
  var date = $("#date").val();
  var url =
    "api/cyber/sync_sales_shopsales.php?date=" + date;
  $.get(url, function (data, status) {
    console.log(data);
    // alert("Proses sync, klik tombol refresh setelah beberapa saat..");
  });
}


function syncSalesAuto() {
  syncSalesHeader();
  syncSalesLine();
  syncSalesCashierBalance();
  syncSalesDeleted();
  syncSalesShopSales();
}





