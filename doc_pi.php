<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
.tooltip {
  position: relative;
  display: inline-block;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 140px;
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px;
  position: absolute;
  z-index: 1;
  bottom: 150%;
  left: 50%;
  margin-left: -75px;
  opacity: 0;
  transition: opacity 0.3s;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}
</style>
</head>
<body>

<p>Tutorial cara install aplikasi PI Idolmart</p>

<?php 

$array = array('sudo apt-get update',
'sudo apt-get install apache2',
'sudo apt-get install php libapache2-mod-php php-pgsql',
'sudo apt-get install php-curl',
'sudo service apache2 restart',
'sudo systemctl enable apache2');
$no = 0;
foreach ($array as $value) {
	echo '<input type="text" value="'.$value.'" id="myInput'.$no.'" readonly>

	<div class="tooltip">
	<button onclick="myFunction('.$no.')" onmouseout="outFunc('.$no.')">
	<span class="tooltiptext" id="myTooltip'.$no.'">Copy to clipboard</span>
	Copy text
	</button>
	</div><br>';
	
	$no = $no + 1;
}


?>





<script>
function myFunction(id) {
  var copyText = document.getElementById("myInput"+id);
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  navigator.clipboard.writeText(copyText.value);
  
  var tooltip = document.getElementById("myTooltip"+id);
  tooltip.innerHTML = "Copied: " + copyText.value;
}

function outFunc(id) {
  var tooltip = document.getElementById("myTooltip"+id);
  tooltip.innerHTML = "Copy to clipboard";
}
</script>
</body>
</html>
