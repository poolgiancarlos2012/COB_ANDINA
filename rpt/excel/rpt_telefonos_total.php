<?php

	header('Content-Type: text/html; charset=UTF-8');
	header("Content-type:application/vnd.ms-excel;charset=latin");
	header("Content-Disposition:atachment;filename=reporte_telefonos_total.xls");
	header("Pragma:no-cache");
	header("Expires:0");

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';


	date_default_timezone_set('America/Lima');

	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();

	$carteras = $_REQUEST['Cartera'];
	$servicio = $_REQUEST['Servicio'];
	$randon = date("Y_m_d_H_i_s") . rand(100,1000);
	$nombre_servicio = $_GET['NombreServicio'];
	
?>

		<table>
			<tr>
				<td colspan="2" style="font-weight:bold;font-size:24px;">REPORTE DE TELEFONOS</td>
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

	$sqlCreateTMPIdTelefono = "CREATE TEMPORARY TABLE tmp_telefono_".$randon." ( idtelefono int(11) )";
    $prCreateTMPIdTelefono = $connection->prepare($sqlCreateTMPIdTelefono);
        if( $prCreateTMPIdTelefono->execute() ){
            $sqlAlterTMPIdTelefono = "ALTER TABLE tmp_telefono_".$randon." add unique index (idtelefono)";
            $prAlterTMPIdTelefono = $connection->prepare($sqlAlterTMPIdTelefono);
            if( $prAlterTMPIdTelefono->execute() ){
                $sqlInsertTMPIdTelefono = "INSERT INTO tmp_telefono_".$randon."
                            SELECT idtelefono from ca_telefono 
                                        WHERE idcartera IN (". $carteras .")";
                $prInsertTMPIdTelefono=$connection->prepare($sqlInsertTMPIdTelefono);
                if( $prInsertTMPIdTelefono->execute() ){

                	$sqlReporte = "select * from (select 
					CONCAT('=\"',tele.codigo_cliente,'\"') AS 'CODIGO_CLIENTE',
					(SELECT cli.tipo_documento from ca_cliente cli where cli.codigo=tele.codigo_cliente LIMIT 1) AS 'TIPO_DOCUMENTO_CLIENTE',
					(SELECT cli.nombre from ca_cliente cli where cli.codigo=tele.codigo_cliente LIMIT 1) AS 'NOMBRE_CLIENTE',
					'".$nombre_servicio."' AS 'SERVICIO',
					if(tele.is_new=1,'INGRESADO EN GESTION','TELEFONO DE CARTERA') AS 'TELEFONO_INGRESADO_EN' ,
					(SELECT CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre) from ca_usuario usu where usu.idusuario = tele.usuario_creacion limit 1) AS 'OPERADOR_O_SUPERVISOR',
					tele.fecha_creacion AS 'FECHA_CREACION',
					IF(tele.numero_act IS NOT NULL ,'SI','NO') as 'TELEFONO_NORMALIZADO',
					IFNULL(tele.numero_act,numero) AS 'TELEFONO',
					CASE
					WHEN tele.idtipo_referencia=3 AND tele.idorigen=1 THEN 'NO - NO APARECERA POR EL ORIGEN Q ES DE CARTERA Y PREDETERMINADO'
					WHEN !(IFNULL(tele.numero_act,numero) NOT REGEXP '^0.' AND (IFNULL(tele.numero_act,numero) REGEXP '^9........$' OR IFNULL(tele.numero_act,numero) REGEXP '^[2-8].......$' OR IFNULL(tele.numero_act,numero) REGEXP '^[2-8]......$' )) THEN 'NO - FORMATO INCORRECTO'
					WHEN (IFNULL(tele.numero_act,numero) NOT REGEXP '^0.' AND (IFNULL(tele.numero_act,numero) REGEXP '^9........$' OR IFNULL(tele.numero_act,numero) REGEXP '^[2-8].......$' OR IFNULL(tele.numero_act,numero) REGEXP '^[2-8]......$' )) THEN 'OK'
					END 'APARECE_EN_ATENCION_A_CLIENTE',
					tele.referencia AS 'PREFIJO',
					(select dir.departamento from ca_direccion dir where dir.idcuenta = tele.idcuenta and dir.departamento is not null LIMIT 1) AS 'DEPARTAMENTO_CLIENTE_X_DIRECCION',
					(select pre.departamento from ca_prefijo pre inner join ca_direccion dir on dir.departamento = pre.departamento where dir.departamento is not null and dir.idcuenta=tele.idcuenta  limit 1)AS 'DEPARTAMENTO_POR_PREFIJO',
					(select pre.codigo from ca_prefijo pre inner join ca_direccion dir on dir.departamento = pre.departamento where dir.idcuenta=tele.idcuenta and dir.departamento is not null limit 1)AS 'CODIGO_POR_PREFIJO'
					from ca_telefono tele WHERE tele.idcartera IN 
					(". $carteras .")  AND tele.estado=1
					and tele.idtelefono 
					IN (SELECT tel.idtelefono from tmp_telefono_".$randon." tel ) order by tele.is_new desc)A   group by A.TELEFONO,A.CODIGO_CLIENTE";

					$pr = $connection->prepare($sqlReporte);
					$pr->execute();
					$i = 0;
					echo '<table>';
					while ($row = $pr->fetch(PDO::FETCH_ASSOC)) {
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
							echo '<td style="'.$style.'" align="center">'.utf8_decode($value).'</td>';
						}
						echo '</tr>';

						$i++;
					}
					echo '</table>';
                }
            }
        }


	

?>