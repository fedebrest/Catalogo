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
// Varios destinatarios
$para  = 'info@tutoriales-dreamweaver.com'; // agregar su propio correo
//$para .= 'wez@example.com';

// título
$asunto = 'Mensaje desde www.mishop.besaba.com'; //agregar el nombre de su sitio

$nombre = $_POST["name"];
$email = $_POST["email"];
$comentario = $_POST["consulta"];
$producto = $_POST["producto"];

$contenido = 'Nombre:' .$nombre.'<br>
				Email:' .$email.'<br>
				Consulta:' .$comentario.'<br>
				Pregunta por el producto:' .ObtenerNombreProducto($producto).'<br>';

// mensaje
$mensaje = '
<html>
<head>
  <title>Mensaje desde www.mishop.besaba.com</title>
</head>
<body>

  <table>
    <tr>
      <td>'.$contenido.'</td>
    </tr>
   
  </table>
</body>
</html>
';

// Para enviar un correo HTML, debe establecerse la cabecera Content-type
$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Cabeceras adicionales
//$cabeceras .= 'To: '.$nombre.' <'.$email.'>' . "\r\n";
$cabeceras .= 'From: '.$nombre.' <'.$email.'>'. "\r\n";


// Enviarlo
mail($para, $asunto, $mensaje, $cabeceras);
?>