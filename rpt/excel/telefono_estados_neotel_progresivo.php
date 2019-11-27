<?php
	header('content-type:text/html;charset=UTF-8');
	header('content-type:application/vnd.ms-excel;charset=latin');
	header('content-disposition:atachment;filename=telefonos_estados_neotel.xls');
	header('Pragma:no-cache');
	header('Expires:0');

	require_once '../../conexion/config.php';
	require_once '../../conexion/MYSQLConnectionMYSQLI.php';
	require_once '../../conexion/MYSQLConnectionPDO.php';

	require_once '../../factory/DAOFactory.php';
	require_once '../../factory/FactoryConnection.php';

	$confcobrast=  parse_ini_file('../../conf/cobrast.ini',true);
	$user=$confcobrast['user_db']['user_rpt'];
	$password=$confcobrast['user_db']['password_rpt'];

	date_default_timezone_set('America/Lima');

	$factoryConnection=FactoryConnection::create('mysql');
	$connection=$factoryConnection->getConnection($user,$password);

	$idcartera=$_REQUEST['Cartera'];
	$servicio=$_REQUEST['Servicio'];
	$idfinal=$_REQUEST['idfinal'];
	$fecha_inicio=$_REQUEST['FechaInicio'];
	$fecha_fin=$_REQUEST['FechaFin'];

	$sql = "
	SELECT clicar.idcliente_cartera,cli.codigo, A.numeros 
	FROM ca_cliente cli
		INNER JOIN ca_cliente_cartera clicar ON cli.idcliente=clicar.idcliente
		INNER JOIN (
			SELECT t1.idcliente_cartera,t1.numero AS numeros 
			FROM (
				SELECT fonos.idcliente_cartera, fonos.numero, fonos.numero_v, estados.idfinal
				FROM (
							SELECT DISTINCT idcliente_cartera, 
								IF(numero_act IS NULL, numero, numero_act) AS numero  
								,IF(numero_act IS NULL, numero, numero_act) AS numero_v 
							FROM ca_telefono 
							WHERE idcartera IN ($idcartera)
								AND CAST(IFNULL(numero_act,numero) AS SIGNED)!=0
								AND LENGTH(CAST(IFNULL(numero_act,numero) AS SIGNED))>5
								AND NOT (LENGTH(IFNULL(numero_act,numero))=9 AND SUBSTR(IFNULL(numero_act,numero), 1,1)=0 AND SUBSTR(IFNULL(numero_act,numero), 2,1)=9)
								AND (IFNULL(numero_act,numero)!='999999999' AND IFNULL(numero_act,numero)!='0999999999' AND IFNULL(numero_act,numero)!='9999999')
								AND idtipo_referencia!=3 AND estado=1
				) fonos	INNER JOIN (
					SELECT * FROM (
						SELECT lla.idcliente_cartera,
							(SELECT IF(numero_act IS NULL,numero,numero_act) AS numero FROM ca_telefono WHERE idtelefono=lla.idtelefono) AS numero,
							(SELECT peso FROM ca_final_servicio finser 
								INNER JOIN ca_final fin ON fin.idfinal=finser.idfinal WHERE finser.idservicio=6 AND fin.idfinal = lla.idfinal) AS peso,
							(SELECT fin.nombre FROM ca_final_servicio finser 
								INNER JOIN ca_final fin ON fin.idfinal=finser.idfinal WHERE finser.idservicio=6 AND fin.idfinal = lla.idfinal) AS estado,
							lla.idfinal
						FROM ca_llamada lla 
							INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente_cartera=lla.idcliente_cartera
						WHERE lla.idusuario_servicio!=1 AND clicar.idcartera IN ($idcartera) AND clicar.estado=1 AND DATE(lla.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin'
					ORDER BY peso DESC) A
					GROUP BY A.idcliente_cartera,numero
				) estados ON fonos.idcliente_cartera=estados.idcliente_cartera AND fonos.numero=estados.numero
				WHERE estados.idfinal IN ($idfinal)
			) t1 
				WHERE (t1.numero_v NOT REGEXP '^0.' AND (t1.numero_v REGEXP '^9........$' OR t1.numero_v REGEXP '^[2-8].......$' OR t1.numero_v REGEXP '^[2-8]......$' )) 
	   ) A
	ON A.idcliente_cartera=clicar.idcliente_cartera
	WHERE clicar.idcartera IN ($idcartera) AND clicar.estado=1";

	// echo $sql;
	//	exit();
	$pr=$connection->prepare($sql);
	$pr->execute();

	$mayor=0;
	$table="DATA \t";
	$table.="CODIGO_CLIENTE \t";
	$contenido="";
	while($row=$pr->fetch(PDO::FETCH_ASSOC)){
		$cadena=array();
		$cadena=explode('@@', $row['numeros']);
		$contenido.=utf8_decode($row['idcliente_cartera'])."\t";
		$contenido.='="'.($row['codigo']).'"'."\t";
		for($i=0;$i<count($cadena);$i++) {
			if (strlen($cadena[$i]) == 8 AND substr($cadena[$i],0,1) != 9 ) {
				$contenido.='="0'.($cadena[$i]).'"'."\t";
			} else {
				$contenido.='="'.($cadena[$i]).'"'."\t";
			}
		}
		$contenido.="\n";
		if($mayor<count($cadena)){
			$mayor=count($cadena);
		}
	}
	for($j=0;$j<$mayor;$j++){
		$table.="TELEFONO ".($j+1)."\t";
	}
	$table.="\n";
	echo $table;
	echo $contenido;
?>
