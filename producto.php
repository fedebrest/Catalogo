<?php require_once('Connections/conex.php'); ?>
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
$varProducto_producto = "0";
if (isset($_GET["producto"])) {
  $varProducto_producto = $_GET["producto"];
}

$query_producto = sprintf("SELECT * FROM tblproductos WHERE tblproductos.idProducto = %s", GetSQLValueString($varProducto_producto, "int"));
$producto = mysqli_query($conex, $query_producto) or die(mysqli_error($conex));
$row_producto = mysqli_fetch_assoc($producto);
$totalRows_producto = mysqli_num_rows($producto);


$query_relacionados = "SELECT * FROM tblproductos WHERE intCategoria = ".$row_producto["intCategoria"]." AND idProducto NOT IN (".$_GET["producto"].") ";
$relacionados = mysqli_query($conex,$query_relacionados) or die(mysqli_error($conex));
$row_relacionados = mysqli_fetch_assoc($relacionados);
$totalRows_relacionados = mysqli_num_rows($relacionados);

//echo $totalRows_relacionados;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>Mi Shop - Tienda de muestra perteneciente a www.tutoriales-dreamweaver.com</title>
<link href="image/favicon.png" rel="icon" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<!-- CSS Part Start-->
<link rel="stylesheet" type="text/css" href="css/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="css/slideshow.css" media="screen" />
<link rel="stylesheet" type="text/css" href="js/colorbox/colorbox.css" media="screen" />
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
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/validar.js"></script>
<!-- JS Part End-->
</head>
<body>
<div class="main-wrapper">
  <!-- Header Parts Start-->
<?php include("includes/header.php"); ?>
<?php include("includes/menu.php"); ?>
  <!--Top Navigation Start-->
  <div id="container">
    <!--Left Part-->
    <?php include("includes/sidebar.php"); ?>
    <!--Left End-->
    <!--Middle Part Start-->
    <div id="content">
      <!--Breadcrumb Part Start-->
      <div class="breadcrumb"> <a href="index.php">Home</a> » <a href="#"><?php echo $row_producto['strNombre']; ?></a></div>
      <!--Breadcrumb Part End-->
      <?php if ($totalRows_producto > 0) { // Show if recordset not empty ?>
  <div class="product-info">
    <div class="left">
      <div class="image"> <a href="imagenes/productos/<?php echo $row_producto['strImagen']; ?>" title="<?php echo $row_producto['strNombre']; ?>" class="cloud-zoom colorbox" id='zoom1' rel="adjustX: 0, adjustY:0, tint:'#000000',tintOpacity:0.2, zoomWidth:360, position:'inside', showTitle:false"> <img src="imagenes/productos/<?php echo $row_producto['strImagen']; ?>" title="#" alt="#" id="image" /><span id="zoom-image"><i class="zoom_bttn"></i> Zoom</span></a> </div>
      
      </div>
    <div class="right">
      <h1><?php echo $row_producto['strNombre']; ?></h1>
      <div class="description"> <span>Categoría:</span> <a href="#"><?php echo ObtenerNombreCategoria($row_producto['intCategoria']); ?></a><br>
        
        <span>Stock:</span> <?php echo $row_producto['intStock']; ?></div>
      <div class="price">Precio: 
        <div class="price-tag">$<?php echo number_format($row_producto['dblPrecio'],2); ?></div>
        <br>
        
        </div>
      
      
      <!-- AddThis Button BEGIN -->
      
     
      <!-- AddThis Button END -->
      
      </div>
  </div>

<!-- Tabs Start -->
      <div id="tabs" class="htabs">  <a href="#tab-review">Comentarios</a> </div>
      
      <div class="tab-content" id="tab-review">
        
        <h2 id="review-title">Enviar su consulta</h2>
        <br>
        <div id="formularioproducto">
<form action="" method="get" id="formprod" name="formprod">
  <b>Su nombre:</b><br>
        <input type="text" value="" name="name" id="name">
        <br>
        <br>
        <b>Su email:</b><br>
        <input type="text" value="" name="mail" id="mail">
        <br>
        <br>
        <b>Su comentario:</b>
        <textarea style="width: 98%;" rows="8" cols="40" name="comentario" id="comentario"></textarea>
        <br>
        <br>
        <input name="producto" type="hidden" id="producto" value="<?php echo $row_producto['idProducto']; ?>">
       
        <br>
        <br>
        <div class="buttons">
        <input name="enviar" type="submit" id="enviar" value="Enviar" class="button">
        
        </div>
      </form>
      </div>
        <div id="ok">
          <h2>Su consulta se ha enviado correctamente</h2>
        </div>
      </div>
      <!-- Tabs End -->
      <!-- Related Products Start -->
      <?php if ($totalRows_relacionados > 0) { // Show if recordset not empty ?>
  <div class="box">
    <div class="box-heading">Productos relacionados (<?php echo $totalRows_relacionados; ?>)</div>
    <div class="box-content">
      <div class="box-product">
        
        
        
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
	$imgSizer->image     = '/imagenes/productos/'.$row_relacionados['strImagen'];
	$imgSizer->resize();
			
	?>
          <div>
            <div class="image"><a href="producto.php?producto=<?php echo $row_relacionados['idProducto']; ?>"><img src="imagenes/productos/_min/miniatura_<?php echo $row_relacionados['strImagen']; ?>" alt="<?php echo $row_relacionados['strNombre']; ?>" /></a></div>
            <div class="name"><a href="product.html"><?php echo $row_relacionados['strNombre']; ?></a></div>
            <div class="price">$ <?php echo number_format($row_relacionados['dblPrecio'], 2); ?></div>
            <input type="button" value="Ver producto" onClick=location.href='producto.php?producto=<?php echo $row_relacionados['idProducto']; ?>' class="button" />
            
          </div>
          <?php } while ($row_relacionados = mysqli_fetch_assoc($relacionados)); ?>
        
        
      </div>
    </div>
  </div>
  <?php } // Show if recordset not empty ?>
<!-- Related Products End -->
        <?php } // Show if recordset not empty ?>
        
      
    </div>
    <!--Middle Part End-->
    <div class="clear"></div>
    <?php if ($totalRows_producto == 0) { // Show if recordset empty ?>
  <h1>No existe ese producto </h1>
  <?php } // Show if recordset empty ?>
  </div>
</div>
<!--Footer Part Start-->
<?php include("includes/footer.php"); ?>
<!--Footer Part End-->
</body>
</html>
<?php
mysqli_free_result($producto);

mysqli_free_result($relacionados);
?>
