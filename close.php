<?php

session_start();
$_SESSION['cobrast']['activo']=0;
//$_SESSION['cobrast']['activo']='no';
session_unset();
session_destroy();
unset($_SESSION['cobrast']);
//echo $_SESSION['cobrast']['activo'];
header('Location: index.php');

?>
