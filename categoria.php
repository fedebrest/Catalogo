<?php require_once('Connections/conex.php'); 
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

global $conex;
$theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($conex, $theValue) : mysqli_escape_string($conex,$theValue);
  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
$varCat_categoria = "0";
if (isset($_GET["categoria"])) {
  $varCat_categoria = $_GET["categoria"];
}

$query_categoria = sprintf("SELECT * FROM tblproductos WHERE tblproductos.intCategoria = %s", GetSQLValueString($varCat_categoria, "int"));
$categoria = mysqli_query($conex, $query_categoria) or die(mysqli_error($conex));
$row_categoria = mysqli_fetch_assoc($categoria);
$totalRows_categoria = mysqli_num_rows($categoria);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title><?php echo utf8_decode(ObtenerNombreCategoria($_GET["categoria"])); ?></title>
<link href="image/favicon.png" rel="icon" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<!-- CSS Part Start-->
<link rel="stylesheet" type="text/css" href="css/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="css/slideshow.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/colorbox.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/carousel.css" media="screen" />
<!-- CSS Part End-->
<!-- JS Part Start-->
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery.nivo.slider.pack.js"></script>
<script type="text/javascript" src="js/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="js/colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="js/tabs.js"></script>
<script type="text/javascript" src="js/jquery.easing-1.3.min.js"></script>
<script type="text/javascript" src="js/cloud_zoom.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.dcjqaccordion.js"></script>
<!-- JS Part End-->
</head>
<body>
<div class="main-wrapper">
  <!-- Header Parts Start-->
  <!--Top Navigation Start-->
  <?php include("includes/header.php"); ?>
  <?php include("includes/menu.php"); ?>
  <!--Top Navigation Start-->
  <div id="container">
    <!--Left Part-->
    <?php include("includes/sidebar.php"); ?>
    <!--Left End-->
    <!--Middle Part Start-->
    
    
      <div id="content">
       <?php if ($totalRows_categoria == 0) { // Show if recordset not empty ?>
        <h1>No hay productos en esta categoría</h1>
      <?php  }?>
      <?php if ($totalRows_categoria > 0) { // Show if recordset not empty ?>
      
      <!--Breadcrumb Part Start-->
      <div class="breadcrumb"> <a href="index.php">Portada</a> » <a href="#"><?php echo utf8_decode(ObtenerNombreCategoria($row_categoria['intCategoria'])); ?></a></div>
      <!--Breadcrumb Part End-->
      <h1>Ver productos en <?php echo utf8_decode(ObtenerNombreCategoria($row_categoria['intCategoria'])); ?></h1>
      <!--Product Grid Start-->
      <div class="product-grid">
      <?php do { 
		require_once('class.imgsizer.php');
		$imgSizer = new imgSizer();
		$imgSizer->type      = "width";
	$imgSizer->max       = 165;
	$imgSizer->quality   = 8;
	$imgSizer->square    = true;
	$imgSizer->prefix    = "miniatura_";
	$imgSizer->folder    = "_min/";
	// Single image ##################################################
	$imgSizer->image     = '/imagenes/productos/'.$row_categoria['strImagen'];
	$imgSizer->resize();
	?>
      <div>
        <div class="image"><a href="producto.php?producto=<?php echo $row_categoria['idProducto']; ?>"><img src="imagenes/productos/_min/miniatura_<?php echo $row_categoria['strImagen']; ?>" alt="<?php echo utf8_decode($row_categoria['strNombre']); ?>" /></a></div>
        <div class="name"><a href="producto.php?producto=<?php echo $row_categoria['idProducto']; ?>"><?php echo utf8_decode($row_categoria['strNombre']); ?></a></div>
        <div class="price"> $<?php echo number_format($row_categoria['dblPrecio'],2); ?></div>
        <div class="cart">
          <input type="button" value="Ver producto" onClick=location.href='producto.php?producto=<?php echo $row_categoria['idProducto']; ?>' class="button" />
        </div>
      </div>
      <?php } while ($row_categoria = mysqli_fetch_assoc($categoria)); ?>
  </div>
  <!--Product Grid End-->
  <?php } // Show if recordset not empty ?>
</div>

<!--Middle Part End-->
<div class="clear"></div>
</div>
</div>
<!--Footer Part Start-->
<?php include("includes/footer.php"); ?>
<!--Footer Part End-->
</body>
</html>
<?php
mysqli_free_result($categoria);
?>
