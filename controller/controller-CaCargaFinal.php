<?php
require '../bo/include_dao.php';
require_once '../dao/CaCargaFinalDAO....php';
require_once '../dto/CaCargaFinal....php';
require_once '../mysql/CaCargaFinalMySqlDAO....php';
require_once '../mysql/ext/CaCargaFinalMySqlExtDAO....php';
require_once '../bo/bo_CaCargaFinal.php';

switch ($_POST["action"]):   
    case 'crear':
        $dtoCargaFinal = new CaCargaFinal();
        $boAlerta = new bo_CaCargaFinal();

        break;
    endswitch;

?>
