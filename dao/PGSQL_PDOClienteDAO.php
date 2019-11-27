<?php

class PGSQL_PDOClienteDAO {

    public function queryGlobal(dto_cliente_cartera $dtoClienteCartera) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idservicio, cli.idcliente,clicar.idcartera,clicar.estado,clicar.retiro,clicar.reclamo,clicar.motivo_retiro,
				cli.codigo,CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'nombre',
				IFNULL(cli.numero_documento,'') AS 'numero_documento',
				IFNULL(cli.tipo_documento,'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
				FROM ca_cliente_cartera clicar INNER JOIN ca_cliente cli ON cli.codigo=clicar.codigo_cliente 
				WHERE clicar.idcliente_cartera = ? ";

        $ClienteCartera = $dtoClienteCartera->getId();
        $factoryConnection = FactoryConnection::create('postgres_pdo');
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
//			$sql=" SELECT clicar.idcliente_cartera,clicar.idcliente,cli.codigo,
//				CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'nombre',cli.dni,IFNULL(cli.ruc,'') AS 'ruc'
//				FROM ca_cliente_cartera clicar INNER JOIN ca_cliente cli ON cli.idcliente=clicar.idcliente 
//				WHERE clicar.idcliente_cartera=? ";
//			$sql=" SELECT clicar.idcliente_cartera,cli.idcliente,clicar.idcartera,
//				cli.codigo,CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'nombre',
//				IFNULL(cli.numero_documento,'') AS 'numero_documento',IFNULL(cli.tipo_documento,'') AS 'tipo_documento'
//				FROM ca_cliente_cartera clicar INNER JOIN ca_cliente cli ON cli.idcliente=clicar.idcliente 
//				WHERE clicar.estado = 1 AND clicar.idcliente_cartera = ? ";

        $sql = " SELECT clicar.idcliente_cartera,cli.idservicio, cli.idcliente,clicar.idcartera,clicar.estado,clicar.retiro,clicar.reclamo,clicar.motivo_retiro,
				cli.codigo,CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'nombre',
				IFNULL(cli.numero_documento,'') AS 'numero_documento',
				IFNULL(cli.tipo_documento,'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
				FROM ca_cliente_cartera clicar INNER JOIN ca_cliente cli ON cli.codigo=clicar.codigo_cliente 
				WHERE cli.idservicio = ? AND clicar.idcliente_cartera = ? ";

        $ClienteCartera = $dtoClienteCartera->getId();
        $servicio = $dtoCliente->getIdServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
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

}

?>