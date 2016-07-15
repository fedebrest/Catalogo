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
$maxRows_productos = 10;
$pageNum_productos = 0;
if (isset($_GET['pageNum_productos'])) {
  $pageNum_productos = $_GET['pageNum_productos'];
}
$startRow_productos = $pageNum_productos * $maxRows_productos;


$query_productos = "SELECT * FROM tblproductos WHERE tblproductos.intActivo = 1 order by RAND()";
$query_limit_productos = sprintf("%s LIMIT %d, %d", $query_productos, $startRow_productos, $maxRows_productos);
$productos = mysqli_query($conex, $query_limit_productos) or die(mysqli_error($conex));
$row_productos = mysqli_fetch_assoc($productos);

if (isset($_GET['totalRows_productos'])) {
  $totalRows_productos = $_GET['totalRows_productos'];
} else {
  $all_productos = mysqli_query($conex,$query_productos);
  $totalRows_productos = mysqli_num_rows($all_productos);
}
$totalPages_productos = ceil($totalRows_productos/$maxRows_productos)-1;
?>

<div class="box">
        <div class="box-heading">Productos</div>
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
	$imgSizer->image     = '/imagenes/productos/'.$row_productos['strImagen'];
	$imgSizer->resize();
	?> 
			
	
            <div>
              <div class="image"><a href="producto.php?producto=<?php echo $row_productos['idProducto']; ?>"><img src="imagenes/productos/_min/miniatura_<?php echo $row_productos['strImagen']; ?>" alt="<?php echo $row_productos['strNombre']; ?>" /></a></div>
              <div class="name"><a href="producto.php?producto=<?php echo $row_productos['idProducto']; ?>"><?php echo $row_productos['strNombre']; ?></a></div>
              <div class="price">$ <?php echo number_format($row_productos['dblPrecio'], 2); ?></div>
            
              <div class="cart">
                <input type="button" value="Ver producto" onClick=location.href='producto.php?producto=<?php echo $row_productos['idProducto']; ?>' class="button" />
              </div>
            </div>
              <?php } while ($row_productos = mysqli_fetch_assoc($productos)); ?>
            
            
            
          </div>
        </div>
      </div>
<?php
mysqli_free_result($productos);
?>
