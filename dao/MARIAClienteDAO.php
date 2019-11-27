<?php

class MARIAClienteDAO {

    public function queryGlobal(dto_cliente_cartera $dtoClienteCartera) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idservicio, cli.idcliente,clicar.idcartera,clicar.estado,clicar.retiro,clicar.reclamo,clicar.motivo_retiro,
				cli.codigo,CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'nombre',
				IFNULL(cli.numero_documento,'') AS 'numero_documento',
				IFNULL(cli.tipo_documento,'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
				FROM ca_cliente_cartera clicar INNER JOIN ca_cliente cli ON cli.codigo=clicar.codigo_cliente 
				WHERE clicar.idcliente_cartera = ? ";

        $ClienteCartera = $dtoClienteCartera->getId();
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $ClienteCartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryByIdClienteCartera(dto_cliente_cartera $dtoClienteCartera, dto_cliente $dtoCliente) {

        $sql = " SELECT 
                (select IF(car.fecha_creacion is null,'',DATEDIFF(DATE(now()) , DATE(car.fecha_creacion))) from ca_cartera car where car.idcartera = clicar.idcartera LIMIT 1)AS 'fecha_creacion',
                (select IF(car.fecha_modificacion is null,'',DATEDIFF(DATE(now()) ,DATE(car.fecha_modificacion))) from ca_cartera car where car.idcartera = clicar.idcartera LIMIT 1)AS 'fecha_modificacion',

        		clicar.idcliente_cartera,
        		cli.idservicio, 
        		cli.idcliente,
        		clicar.idcartera,
        		clicar.estado,
        		clicar.retiro,
        		clicar.reclamo,
        		clicar.motivo_retiro,
                IFNULL(clicar.estado_cliente,'') as estado_cliente,
				ROUND((
				( IFNULL(deuda,0) - ROUND((IFNULL(deuda,0)*descuento_ref)/100) ) + 
				ROUND(( IFNULL(deuda,0) - ROUND((IFNULL(deuda,0)*descuento_ref)/100) )*interes_ref/100)+
				ROUND(( IFNULL(deuda,0) - ROUND((IFNULL(deuda,0)*descuento_ref)/100) )*comision_ref/100)+
				IFNULL(clicar.gastos_cobranza_ref,0)
				)/clicar.n_cuotas_ref) AS monto_cuota,
				IF( is_ref = 0 ,'','REFINANCIAMIENTO') AS status,
				cli.codigo,CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'nombre',
				IFNULL(cli.numero_documento,'') AS 'numero_documento',
				IFNULL(cli.tipo_documento,'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor',
                (select flag_provincia from ca_cartera where idcartera=clicar.idcartera) AS 'flag_provincia',clicar.recibio_eexx,
                IFNULL((select nombre from ca_motivo_no_pago where idmotivo_no_pago=clicar.ul_motivo_no_pago),'') as 'ul_motivo_no_pago',IFNULL(clicar.deuda,'0') as 'provision',
                IFNULL(clicar.observacion_ref,'') as 'situacion',
                IFNULL((select con.nombre from ca_ultimo_contacto ult inner join ca_contacto con on ult.idcontacto=con.idcontacto where ult.codigo_cliente=clicar.codigo_cliente limit 1),'') as 'estadocontacto',
                clicar.ul_motivo_no_pago as idmotivo_no_pago,
                IFNULL((select lla.idparentesco from ca_llamada lla where lla.idllamada=clicar.id_ultima_llamada),'0') as idparentesco, 
                                IFNULL(clicar.idsituacion_laboral,'0') as idsituacion_laboral,  
                                IFNULL(clicar.iddisposicion_refinanciar,'0') as iddisposicion_refinanciar,
                                IFNULL(clicar.idestado_cliente,'0') as idestado_cliente  
				FROM ca_cliente_cartera clicar INNER JOIN ca_cliente cli ON cli.idcliente=clicar.idcliente 
				WHERE cli.idservicio = ? AND clicar.idcliente_cartera = ? ";

        // echo $sql;

        $ClienteCartera = $dtoClienteCartera->getId();
        $servicio = $dtoCliente->getIdServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * ***** */
        $pr->bindParam(1, $servicio);
        /*         * ***** */
        //$pr->bindParam(1,$ClienteCartera);
        $pr->bindParam(2, $ClienteCartera);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }
    public function buscarUsuario($usuario,$idservicio){
            $sql="SELECT ususer.idusuario,ususer.idusuario_servicio from ca_usuario_servicio ususer
                inner join ca_usuario usu on usu.idusuario=ususer.idusuario
                where ususer.idservicio=$idservicio and ususer.estado=1 and usu.dni='$usuario'";

echo $sql;
exit();
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();  
        $pr = $connection->prepare($sql);              
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }        
    }      

}

?>