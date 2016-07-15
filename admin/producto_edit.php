<?php require_once('../Connections/conex.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	
	$imagen = $_FILES["imagen"]["name"];
	move_uploaded_file($_FILES["imagen"]["tmp_name"], "../imagenes/productos/".$imagen);
	
	if(isset($imagen)&&($imagen!=""))
	{
		$nuevaimagen = $imagen;
	}
	else {
		$nuevaimagen = $_POST["imagen2"];
	}
	
  $updateSQL = sprintf("UPDATE tblproductos SET strNombre=%s, intCategoria=%s, strImagen=%s, dblPrecio=%s, intStock=%s, intActivo=%s, intOferta=%s WHERE idProducto=%s",
                       GetSQLValueString($_POST['strNombre'], "text"),
                       GetSQLValueString($_POST['intCategoria'], "int"),
                       GetSQLValueString($nuevaimagen, "text"),
                       GetSQLValueString($_POST['dblPrecio'], "double"),
                       GetSQLValueString($_POST['intStock'], "int"),
                       GetSQLValueString($_POST['intActivo'], "int"),
					   GetSQLValueString($_POST['intOferta'], "int"),
                       GetSQLValueString($_POST['idProducto'], "int"));


  $Result1 = mysqli_query($conex, $updateSQL) or die(mysqli_error());

  $updateGoTo = "productos.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$varID_producto = "0";
if (isset($_GET["id"])) {
  $varID_producto = $_GET["id"];
}

$query_producto = sprintf("SELECT * FROM tblproductos WHERE tblproductos.idProducto = %s", GetSQLValueString($varID_producto, "int"));
$producto = mysqli_query($conex, $query_producto) or die(mysqli_error());
$row_producto = mysqli_fetch_assoc($producto);
$totalRows_producto = mysqli_num_rows($producto);


$query_categoria = "SELECT * FROM tblcategorias ORDER BY tblcategorias.strNombre ASC";
$categoria = mysqli_query($conex, $query_categoria) or die(mysqli_error());
$row_categoria = mysqli_fetch_assoc($categoria);
$totalRows_categoria = mysqli_num_rows($categoria);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
<link href="css/estilosadmin.css" rel="stylesheet" type="text/css"><!--[if lte IE 7]>
<style>
.content { margin-right: -1px; } /* este margen negativo de 1 px puede situarse en cualquiera de las columnas de este diseño con el mismo efecto corrector. */
ul.nav a { zoom: 1; }  /* la propiedad de zoom da a IE el desencadenante hasLayout que necesita para corregir el espacio en blanco extra existente entre los vínculos */
</style>
<![endif]-->
</head>

<body>

<div class="container">

<?php include("includes/menu.php"); ?>
<?php include("includes/header.php"); ?>
  <div class="content">
  
    <h1>Editar producto</h1>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data">
  <table align="center" cellpadding="5">
    <tr valign="baseline">
      <td nowrap align="right"><strong>Nombre:</strong></td>
      <td><input type="text" name="strNombre" value="<?php echo htmlentities($row_producto['strNombre'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap><strong>Categoria:</strong></td>
      <td><select name="intCategoria">
        <?php 
do {  
?>
        <option value="<?php echo $row_categoria['idCategoria']?>" <?php if (!(strcmp($row_categoria['idCategoria'], htmlentities($row_producto['intCategoria'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_categoria['strNombre']?></option>
        <?php
} while ($row_categoria = mysqli_fetch_assoc($categoria));
?>
      </select></td>
    <tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap><strong>Imagen:</strong></td>
      <td><img src="../imagenes/productos/<?php echo $row_producto['strImagen']; ?>" width="50"><input name="imagen" type="file"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap><strong>Precio:</strong></td>
      <td><input type="text" name="dblPrecio" value="<?php echo htmlentities($row_producto['dblPrecio'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap><strong>Stock:</strong></td>
      <td><input type="text" name="intStock" value="<?php echo htmlentities($row_producto['intStock'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap><strong>Activo:</strong></td>
      <td><select name="intActivo">
        <option value="1" <?php if (!(strcmp(1, htmlentities($row_producto['intActivo'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Activo</option>
        <option value="0" <?php if (!(strcmp(0, htmlentities($row_producto['intActivo'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Inactivo</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap><strong>Oferta:</strong></td>
      <td><select name="intOferta">
        <option value="1" <?php if (!(strcmp(1, htmlentities($row_producto['intOferta'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Es oferta</option>
        <option value="0" <?php if (!(strcmp(0, htmlentities($row_producto['intOferta'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No es oferta</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="Actualizar registro"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="idProducto" value="<?php echo $row_producto['idProducto']; ?>">
  <input name="imagen2" type="hidden" value="<?php echo $row_producto['strImagen']; ?>">
</form>
<p>&nbsp;</p>
    
<!-- end .content --></div>
  <!-- end .container --></div>
</body>
</html>
<?php
mysqli_free_result($producto);

mysqli_free_result($categoria);
?>
