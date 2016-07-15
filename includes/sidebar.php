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

$query_ultimos = "SELECT * FROM tblproductos ORDER BY tblproductos.idProducto DESC LIMIT 5";
$ultimos = mysqli_query($conex, $query_ultimos) or die(mysqli_error($conex));
$row_ultimos = mysqli_fetch_assoc($ultimos);
$totalRows_ultimos = mysqli_num_rows($ultimos);


$query_oferta = "SELECT * FROM tblproductos where tblproductos.intOferta = 1 ORDER BY tblproductos.strNombre ASC LIMIT 3";
$oferta = mysqli_query($conex,$query_oferta) or die(mysqli_error($conex));
$row_oferta = mysqli_fetch_assoc($oferta);
$totalRows_oferta = mysqli_num_rows($oferta);

$query_categorias = "SELECT * FROM tblcategorias ORDER BY tblcategorias.strNombre ASC";
$categorias = mysqli_query($conex, $query_categorias) or die(mysqli_error($conex));
$row_categorias = mysqli_fetch_assoc($categorias);
$totalRows_categorias = mysqli_num_rows($categorias);
?>
<div id="column-left">
      <!--Categories Part Start-->
      <div class="box">
        <div class="box-heading">Categorías</div>
        <div class="box-content box-category">
          <ul id="custom_accordion">
            <?php do { ?>
            <li class="category57"><a class="nochild " href="categoria.php?categoria=<?php echo $row_categorias['idCategoria']; ?>"><?php echo utf8_decode($row_categorias['strNombre']); ?></a></li>
              <?php } while ($row_categorias = mysqli_fetch_assoc($categorias)); ?>
            
            
          </ul>
        </div>
      </div>
      <!--Categories Part End-->
      <!--Latest Product Start-->
      <div class="box">
        <div class="box-heading">Últimos productos</div>
        <div class="box-content">
          <div class="box-product">
            
            
            
            
            <?php do { 
			require_once('class.imgsizer.php');
		$imgSizer = new imgSizer();
		$imgSizer->type      = "width";
	$imgSizer->max       = 50;
	$imgSizer->quality   = 8;
	$imgSizer->square    = true;
	$imgSizer->prefix    = "miniatura_";
	$imgSizer->folder    = "_min50/";
	// Single image ##################################################
	$imgSizer->image     = '/imagenes/productos/'.$row_ultimos['strImagen'];
	$imgSizer->resize();
			
			?>
            <div>
              <div class="image"><a href="producto.php?producto=<?php echo $row_ultimos['idProducto']; ?>"><img src="imagenes/productos/_min50/miniatura_<?php echo $row_ultimos['strImagen']; ?>" alt="<?php echo utf8_decode($row_ultimos['strNombre']); ?>" /></a></div>
              <div class="name"><a href="producto.php?producto=<?php echo $row_ultimos['idProducto']; ?>"><?php echo utf8_decode($row_ultimos['strNombre']); ?></a></div>
              <div class="price">$ <?php echo number_format($row_ultimos['dblPrecio'], 2); ?></div>
              
            </div>
              <?php } while ($row_ultimos = mysqli_fetch_assoc($ultimos)); ?>
            
            
          </div>
        </div>
      </div>
      <!--Latest Product End-->
      <!--Specials Product Start-->
<div class="box">
        <div class="box-heading">Ofertas</div>
        <div class="box-content">
          <div class="box-product">
            <?php do { 
			require_once('class.imgsizer.php');
		$imgSizer = new imgSizer();
		$imgSizer->type      = "width";
	$imgSizer->max       = 50;
	$imgSizer->quality   = 8;
	$imgSizer->square    = true;
	$imgSizer->prefix    = "miniatura_";
	$imgSizer->folder    = "_min50/";
	// Single image ##################################################
	$imgSizer->image     = '/imagenes/productos/'.$row_oferta['strImagen'];
	$imgSizer->resize();
			
			?>
            <div>
              <div class="image"><a href="producto.php?producto=<?php echo $row_oferta['idProducto']; ?>"><img src="imagenes/productos/_min50/miniatura_<?php echo $row_oferta['strImagen']; ?>" alt="<?php echo utf8_decode($row_oferta['strNombre']); ?>" /></a></div>
              <div class="name"><a href="producto.php?producto=<?php echo $row_oferta['idProducto']; ?>"><?php echo utf8_decode($row_oferta['strNombre']); ?></a></div>
              <div class="price">$ <?php echo number_format($row_oferta['dblPrecio'], 2); ?></div>
              
            </div>
              <?php } while ($row_oferta = mysqli_fetch_assoc($oferta)); ?>
            
            
          </div>
        </div>
      </div>      
      <!--Specials Product End-->
    </div>
<?php
mysqli_free_result($ultimos);

mysqli_free_result($categorias);

mysqli_free_result($oferta);
?>
