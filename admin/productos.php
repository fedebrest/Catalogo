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

$query_productos = "SELECT * FROM tblproductos ORDER BY tblproductos.strNombre ASC";
$productos = mysqli_query($conex, $query_productos) or die(mysqli_error());
$row_productos = mysqli_fetch_assoc($productos);
$totalRows_productos = mysqli_num_rows($productos);
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
<script>
function eliminar()
{
	rc = confirm("Seguro desea eliminar?");
	return rc;
}

</script>
</head>

<body>

<div class="container">

<?php include("includes/menu.php"); ?>
<?php include("includes/header.php"); ?>
  <div class="content">
  
    <h1>Lista de productos</h1>
     <?php if ($totalRows_productos == 0) { // Show if recordset not empty ?>
    <h2>No ha productos disponibles </h2>
    <?php }?>
    <?php if ($totalRows_productos > 0) { // Show if recordset not empty ?>
  <table width="100%" border="1">
    <tr>
      <td align="center" valign="middle"><strong>Nombre producto</strong></td>
      <td align="center" valign="middle"><strong>Precio</strong></td>
      <td align="center" valign="middle"><strong>Estado</strong></td>
      <td align="center" valign="middle"><strong>Stock</strong></td
    ><td align="center" valign="middle"><strong>Acciones</strong></td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_productos['strNombre']; ?></td>
        <td><?php echo $row_productos['dblPrecio']; ?></td>
        <td><?php echo ObtenerEstado($row_productos['intActivo']); ?></td>
        <td><?php echo $row_productos['intStock']; ?></td>
        <td><a href="producto_edit.php?id=<?php echo $row_productos['idProducto']; ?>">Editar</a> - <a href="producto_delete.php?id=<?php echo $row_productos['idProducto']; ?>" onClick="javascript:return eliminar();"

 >Eliminar</a></td>
      </tr>
      <?php } while ($row_productos = mysqli_fetch_assoc($productos)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<p>&nbsp;</p>
    
<!-- end .content --></div>
  <!-- end .container --></div>
</body>
</html>
<?php
mysqli_free_result($productos);
?>
