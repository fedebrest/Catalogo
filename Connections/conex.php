<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"


$hostname_conex = "p:localhost";
$database_conex = "u140305696_base";
$username_conex = "u140305696_base";
$password_conex = "GX3CJvXuRN";
$conex = mysqli_connect($hostname_conex, $username_conex, $password_conex, $database_conex); 
error_reporting(E_ALL);
ini_set("display_errors", 1);
mysqli_set_charset($conex, 'utf8');
?>

<?php
if (is_file("includes/funciones.php")){
	include("includes/funciones.php");
}
else
{
	include("../includes/funciones.php");
	}
?>