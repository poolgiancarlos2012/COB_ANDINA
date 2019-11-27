<?php
function conectar(){	
	$localhost = "localhost";
	$usuario_BD="root";
	$clave_BD="";
	$basedatos="testCobrast";	
	mysql_connect($localhost,$usuario_BD,$clave_BD) or die("Error al conectar :".mysql_error());
	mysql_select_db($basedatos) or die("Error al elegir la BBDD :".mysql_error());
}
function desconectar(){
	mysql_close() or die("Error al intentar desconectar del servidor de BBDD : ".mysql_error());	
}
?>
