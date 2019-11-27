<?php

	header('content-type:text/html;charset=UTF-8');
	header('content-type:application/vnd.ms-excel;charset=latin');
	header('content-disposition:atachment;filename=telefonos_vertical.xls');
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
	$singestion=$_REQUEST['singestion'];

	$w_idfinal= $idfinal==""?"":" and lla.idfinal in ($idfinal) ";
	$w_idfinal_where=$idfinal==""?"":" WHERE A.COUNT=1 ";
	if($singestion=="true") {
		$w_singestion=" and clicar.id_ultima_llamada=0 ";
	} else {
		$w_singestion=" ";
	}
	$sql="SELECT concat(A.idcliente_cartera,',') AS idclicar 
			FROM (
				SELECT clicar.idcliente_cartera,(select count(*) from ca_llamada lla where lla.idllamada=clicar.id_ultima_llamada $w_idfinal) AS 'COUNT' 
				FROM ca_cliente_cartera clicar 
				WHERE clicar.estado=1 and clicar.idcartera in ($idcartera) $w_singestion 
			) A $w_idfinal_where";

	$pr=$connection->prepare($sql);
	$pr->execute();
	$idclicar="";
	while($row=$pr->fetch(PDO::FETCH_ASSOC)) {
		$idclicar.=$row['idclicar'];
	}

	$idclicar=substr($idclicar,0,strlen($idclicar)-1);
	if($idclicar==''){ echo('No se encontraron registros'); exit; }

	$sql="SELECT clicar.idcliente_cartera, cli.codigo, A.numeros 
			from ca_cliente cli
				inner join ca_cliente_cartera clicar on cli.idcliente=clicar.idcliente
				inner join (
					SELECT t1.idcliente_cartera, t1.numero AS numeros 
					from (
						select DISTINCT idcliente_cartera , 
							concat( '=\"', if(numero_act is null, numero, numero_act), '\"' ) as numero 
							,IF(numero_act IS NULL, numero, numero_act) AS numero_v 
						from ca_telefono 
						WHERE idcartera in ($idcartera)
								AND CAST(numero AS SIGNED)!=0 
								and length(CAST(numero AS SIGNED))>5 
								and not (length(numero)=9 and SUBSTR(numero, 1,1)=0 and SUBSTR(numero, 2,1)=9) 
								and (numero!='999999999' and numero!='0999999999' and numero!='9999999')
								and idcliente_cartera in ( $idclicar ) and idtipo_referencia!=3
					) t1 
					WHERE (t1.numero_v NOT REGEXP '^0.' AND (t1.numero_v REGEXP '^9........$' OR t1.numero_v REGEXP '^........$' OR t1.numero_v REGEXP '^.......$' )) 
				) A on A.idcliente_cartera=clicar.idcliente_cartera
			where clicar.idcartera in ($idcartera) ";

	$pr=$connection->prepare($sql);
	$pr->execute();

	$table = "DATA \t";
	$table .= "CODIGO_CLIENTE \t";
	$table .= "NUMEROS \t";
	$table .= "\n";
	while($row=$pr->fetch(PDO::FETCH_ASSOC)){
		$table .= $row['idcliente_cartera']."\t";
		$table .= '="'.($row['codigo']).'"'."\t";
		$table .= $row['numeros']."\t";
		$table .= "\n";
	}
	echo $table;
?>
