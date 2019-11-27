<?php

class MARIAReferenciaClienteDAO {

    public function insertDireccion(dto_referencia_cliente $dtoReferenciaCliente, dto_direccion $dtoDireccion) {
        $sql = " INSERT INTO ca_referencia_cliente (idorigen,idclase,idtipo_referencia,idcliente,estado,observacion,fecha_creacion,usuario_creacion ) 
				VALUES ( ?,?,?,?,1,?,NOW(),? ) ";

        $origen = $dtoReferenciaCliente->getIdOrigen();
        $clase = $dtoReferenciaCliente->getIdClase();
        $TipoReferencia = $dtoReferenciaCliente->getIdTipoReferencia();
        $cliente = $dtoReferenciaCliente->getIdCliente();
        $observacion = $dtoReferenciaCliente->getObservacion();
        $UsuarioCreacion = $dtoReferenciaCliente->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $origen);
        $pr->bindParam(2, $clase);
        $pr->bindParam(3, $TipoReferencia);
        $pr->bindParam(4, $cliente);
        $pr->bindParam(5, $observacion);
        $pr->bindParam(6, $UsuarioCreacion);
        if ($pr->execute()) {

            $sqlD = " INSERT INTO ca_direccion(idreferencia_cliente,direccion,referencia,ubigeo,departamento,provincia,distrito,fecha_creacion,usuario_creacion) 
					VALUES (?,?,?,?,?,?,?,NOW(),?)";

            $ReferenciaCliente = $connection->lastInsertId();
            $direccion = $dtoDireccion->getDireccion();
            $referencia = $dtoDireccion->getReferencia();
            $ubigeo = $dtoDireccion->getUbigeo();
            $departamento = $dtoDireccion->getDepartamento();
            $provincia = $dtoDireccion->getProvincia();
            $distrito = $dtoDireccion->getDistrito();
            $UsuarioCreacionDireccion = $dtoDireccion->getUsuarioCreacion();

            $prD = $connection->prepare($sqlD);
            $prD->bindParam(1, $ReferenciaCliente);
            $prD->bindParam(2, $direccion);
            $prD->bindParam(3, $referencia);
            $prD->bindParam(4, $ubigeo);
            $prD->bindParam(5, $departamento);
            $prD->bindParam(6, $provincia);
            $prD->bindParam(7, $distrito);
            $prD->bindParam(8, $UsuarioCreacionDireccion);

            if ($prD->execute()) {
                //$connection->commit();
                return true;
            } else {
                //$connection->rollBack();
                return false;
            }
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function insertTelefono(dto_referencia_cliente $dtoReferenciaCliente, dto_telefono $dtoTelefono) {

        $sql = " INSERT INTO ca_referencia_cliente (idorigen,idclase,idtipo_referencia,idcliente,estado,observacion,fecha_creacion,usuario_creacion ) 
				VALUES ( ?,?,?,?,1,?,NOW(),? ) ";

        $origen = $dtoReferenciaCliente->getIdOrigen();
        $clase = $dtoReferenciaCliente->getIdClase();
        $TipoReferencia = $dtoReferenciaCliente->getIdTipoReferencia();
        $cliente = $dtoReferenciaCliente->getIdCliente();
        $observacion = $dtoReferenciaCliente->getObservacion();
        $UsuarioCreacion = $dtoReferenciaCliente->getUsuarioCreacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $origen);
        $pr->bindParam(2, $clase);
        $pr->bindParam(3, $TipoReferencia);
        $pr->bindParam(4, $cliente);
        $pr->bindParam(5, $observacion);
        $pr->bindParam(6, $UsuarioCreacion);
        if ($pr->execute()) {

            $sqlT = " INSERT INTO ca_telefono(idtipo_telefono,idreferencia_cliente,numero,anexo,fecha_creacion,usuario_creacion) 
					VALUES (?,?,?,?,NOW(),?)";

            $ReferenciaCliente = $connection->lastInsertId();
            $TipoTelefono = $dtoTelefono->getIdTipoTelefono();
            $numero = $dtoTelefono->getNumero();
            $anexo = $dtoTelefono->getAnexo();
            $UsuarioCreacionT = $dtoTelefono->getUsuarioCreacion();
            //echo $TipoTelefono.'<br>';
            $prT = $connection->prepare($sqlT);
            $prT->bindParam(1, $TipoTelefono);
            $prT->bindParam(2, $ReferenciaCliente);
            $prT->bindParam(3, $numero);
            $prT->bindParam(4, $anexo);
            $prT->bindParam(5, $UsuarioCreacionT);

            if ($prT->execute()) {
                //$connection->commit();
                return true;
            } else {
                //$connection->rollBack();
                return false;
            }
        } else {
            //$connection->rollBack();
            return false;
        }
    }

}

?>