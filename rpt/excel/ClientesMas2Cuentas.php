<?php

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=cliente_mas_2_cuentas.xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    
    require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';
    
    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
    
    $confCobrast=parse_ini_file('../../conf/cobrast.ini',true);
    $user = $confCobrast['user_db']['user_rpt'];
    $password = $confCobrast['user_db']['password_rpt'];
    
    date_default_timezone_set('America/Lima');
    
    $factoryConnection= FactoryConnection::create('mysql');	
    $connection = $factoryConnection->getConnection($user,$password);
    
    $countRow = 5;
    
    $servicio = $_REQUEST['Servicio'];
    $cartera = $_REQUEST['Cartera'];
    

    ?>
    
        <table>
          <tr>
            <td colspan="2" style="font-weight:bold;font-size:24px;">REPORTE DE CLIENTES CON MAS DE 1 CUENTA</td>
          </tr>
          <tr>
            <td>Reporte generado:</td>
            <td><?php echo date("Y-m-d"); ?></td>
          </tr>
          <tr>
            <td style="height:40px;"></td>
          </tr>
        </table>
      <?php
    
    $sqlDataCartera = " SELECT idcartera,cliente,cuenta,detalle_cuenta,telefono,direccion,adicionales FROM ca_cartera WHERE idcartera IN ($cartera) ";
	
	$prData = $connection->prepare($sqlDataCartera);
	$prData->execute();
	$dataCartera = $prData->fetchAll(PDO::FETCH_ASSOC);
	
	$dataCuenta = str_replace("\\","",$dataCartera[0]['cuenta']);
	$arrayCuenta = json_decode($dataCuenta,true);
	
	$dataAdicionalCuenta = str_replace("\\","",$dataCartera[0]['adicionales']);
	$arrayAdicionalesCuenta = json_decode($dataAdicionalCuenta,true);
	
	$field = array();
	
	for( $i=0;$i<count($arrayCuenta);$i++ ) {
		array_push($field," ".$arrayCuenta[$i]['campoT']." AS '".$arrayCuenta[$i]['label']."' ");
	}
	
	for( $i=0;$i<count($arrayAdicionalesCuenta['ca_datos_adicionales_cuenta']);$i++ ) {
		array_push($field," ".$arrayAdicionalesCuenta['ca_datos_adicionales_cuenta'][$i]['campoT']." AS '".$arrayAdicionalesCuenta['ca_datos_adicionales_cuenta'][$i]['label']."' ");
	}
	 
    $data_idcli = array();
      
    $sqlC = " SELECT idcliente_cartera FROM ca_cuenta WHERE idcartera = ?  
		GROUP BY idcliente_cartera HAVING COUNT(*) > 1 ";
			
	$prC = $connection->prepare( $sqlC );
	$prC->bindParam(1,$cartera,PDO::PARAM_INT);
	$prC->execute();
	while( $row = $prC->fetch(PDO::FETCH_ASSOC) ) {
		array_push( $data_idcli, $row['idcliente_cartera'] );
	}
    
    $sql = " SELECT 
    		codigo_cliente AS CODIGO_CLIENTE, 
    		".implode(",",$field)."  
    		FROM ca_cuenta 
    		WHERE idcartera = ? AND idcliente_cartera IN ( ".implode(",",$data_idcli)." ) ";

    $prData = $connection->prepare($sql);
    $prData->bindParam(1,$cartera,PDO::PARAM_INT); 
    $prData->execute();
    $i = 0;
    echo '<table>';
    while( $row = $prData->fetch(PDO::FETCH_ASSOC) ) {
      if( $i == 0 ) {
          echo '<tr>';
          foreach( $row as $index => $value ) {
            echo '<td style="background-color:#4F81BD;color:white;border-color:white;" align="center" >'.$index.'</td>';
          }
          echo '</tr>';
      }
    
        $style="";
        ( $i%2 == 0 )?$style="background-color:#DBE5F1;border-color:white;":$style="background-color:#B8CCE4;border-color:white;";
        echo '<tr>';
        foreach( $row as $key => $value )
        {
          echo '<td style="'.$style.'" align="center">="'.utf8_decode($value).'"</td>';
        }
        echo '</tr>';
    
      $i++;
    }
    echo '</table>';

?>
