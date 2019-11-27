<?php
/**
 * Description of ControllerPermisos
 *
 * @author Davis
 */
require_once('../controller/CommandController.php'); #Chek
require_once('../controller/servletPermisoDetalle.php');#Chek
require_once('../dto/dto_permisos_detalle.php'); #Chek
require_once('../dto/dto_niveles_permisos.php'); #Chek
require_once('../dao/MYSQLPermisoDetalleDAO.php');
require_once('../dao/MYSQLNivelesPermisos.php');
require_once('../factory/DAOFactory.php');
require_once('../factory/FactoryConnection.php');
require_once('../conexion/MYSQLConnectionPDO.php');

$cn=CommandController::getCommand();
$cn->process();
?>
