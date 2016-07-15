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
$query_categorias = "SELECT * FROM tblcategorias ORDER BY tblcategorias.strNombre ASC";
$categorias = mysqli_query($conex, $query_categorias) or die(mysqli_error());
$row_categorias = mysqli_fetch_assoc($categorias);
$totalRows_categorias = mysqli_num_rows($categorias);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	$imagen = $_FILES["imagen"]["name"];
	move_uploaded_file($_FILES["imagen"]["tmp_name"], "../imagenes/productos/".$imagen);
	
	
  $insertSQL = sprintf("INSERT INTO tblproductos (strNombre, intCategoria, strImagen, dblPrecio, intStock, intActivo, intOferta) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['strNombre'], "text"),
                       GetSQLValueString($_POST['intCategoria'], "int"),
                       GetSQLValueString($imagen, "text"),
                       GetSQLValueString($_POST['dblPrecio'], "double"),
                       GetSQLValueString($_POST['intStock'], "int"),
					    GetSQLValueString($_POST['intOferta'], "int"),
                       GetSQLValueString($_POST['intActivo'], "int"));


  $Result1 = mysqli_query($conex, $insertSQL) or die(mysqli_error());

  $insertGoTo = "productos.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
<link href="css/estilosadmin.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<!--[if lte IE 7]>
<style>
.content { margin-right: -1px; } /* este margen negativo de 1 px puede situarse en cualquiera de las columnas de este diseño con el mismo efecto corrector. */
ul.nav a { zoom: 1; }  /* la propiedad de zoom da a IE el desencadenante hasLayout que necesita para corregir el espacio en blanco extra existente entre los vínculos */
</style>
<![endif]-->
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</head>

<body>

<div class="container">

<?php include("includes/menu.php"); ?>
<?php include("includes/header.php"); ?>
  <div class="content">
  
    <h1>Agregar producto</h1>
 
    <form method="post" name="form1" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data">
  <table align="center">
    <tr valign="baseline">
      <td nowrap align="right"><strong>Nombre:</strong></td>
      <td><span id="sprytextfield1">
        <input type="text" name="strNombre" value="" size="32">
        <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right"><strong>Categoria:</strong></td>
      <td><select name="intCategoria">
        <?php 
do {  
?>
        <option value="<?php echo $row_categorias['idCategoria']?>" ><?php echo $row_categorias['strNombre']?></option>
        <?php
} while ($row_categorias = mysqli_fetch_assoc($categorias));
?>
      </select></td>
    <tr>
    <tr valign="baseline">
      <td nowrap align="right"><strong>Imagen:</strong></td>
      <td><input name="imagen" type="file" id="imagen"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right"><strong>Precio:</strong></td>
      <td><span id="sprytextfield3">
      <input type="text" name="dblPrecio" value="" size="32">
      <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Ingrese sólo numeros</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right"><strong>Stock:</strong></td>
      <td><span id="sprytextfield4">
      <input type="text" name="intStock" value="" size="32">
      <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right"><strong>Activo:</strong></td>
      <td><select name="intActivo">
        <option value="1" <?php if (!(strcmp(1, ""))) {echo "SELECTED";} ?>>Activo</option>
        <option value="0" <?php if (!(strcmp(0, ""))) {echo "SELECTED";} ?>>Inactivo</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right"><strong>Oferta:</strong></td>
      <td><select name="intOferta">
       <option value="">Seleccionar</option>
        <option value="1" <?php if (!(strcmp(1, ""))) {echo "SELECTED";} ?>>Es oferta</option>
        <option value="0" <?php if (!(strcmp(0, ""))) {echo "SELECTED";} ?>>No es oferta</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="Agregar producto"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
    <p>&nbsp;</p>
    
<!-- end .content --></div>
  <!-- end .container --></div>

<p>&nbsp;</p>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");

var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "integer", {validateOn:["change"]});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "integer");
</script>
</body>
</html>
<?php
mysqli_free_result($categorias);
?>
