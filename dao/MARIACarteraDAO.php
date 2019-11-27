<?php

class MARIACarteraDAO {

	public function queryUniqueSegment ( dto_servicio $dtoServicio ) {
		
		$sql = " SELECT DISTINCT car.segmento AS segmento 
				FROM ca_cartera car INNER JOIN ca_campania cam ON cam.idcampania = car.idcampania 
				WHERE ISNULL(car.segmento) = 0 AND TRIM(car.segmento) != '' AND cam.idservicio = ? ";
	   

       // echo $sql;
       // exit();
		$idservicio = $dtoServicio->getId();
				
		$factoryConnection = FactoryConnection::create('mysql');
                $connection = $factoryConnection->getConnection();
		
		$pr = $connection->prepare($sql);
		$pr->bindParam(1,$idservicio,PDO::PARAM_INT);
		if( $pr->execute() ) {
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}else{
			return array();
		}
		
	}
	
	public function queryUniqueCluster ( dto_servicio $dtoServicio ) {
		
		$sql = " SELECT DISTINCT car.cluster AS cluster 
				FROM ca_cartera car INNER JOIN ca_campania cam ON cam.idcampania = car.idcampania 
				WHERE ISNULL(car.cluster) = 0 AND TRIM(car.cluster) != '' AND cam.idservicio = ? ";
				
		$idservicio = $dtoServicio->getId();
				
		$factoryConnection = FactoryConnection::create('mysql');
                $connection = $factoryConnection->getConnection();
		
		$pr = $connection->prepare($sql);
		$pr->bindParam(1,$idservicio,PDO::PARAM_INT);
		if( $pr->execute() ) {
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}else{
			return array();
		}
		
	}
	
	public function queryUniqueEvent ( dto_servicio $dtoServicio ) {
		
		$sql = " SELECT DISTINCT car.evento AS evento
				FROM ca_cartera car INNER JOIN ca_campania cam ON cam.idcampania = car.idcampania
				WHERE ISNULL(car.evento) = 0 AND TRIM(car.evento) != '' AND cam.idservicio = ? ";
				
		$idservicio = $dtoServicio->getId();
				
		$factoryConnection = FactoryConnection::create('mysql');
                $connection = $factoryConnection->getConnection();
		
		$pr = $connection->prepare($sql);
		$pr->bindParam(1,$idservicio,PDO::PARAM_INT);
		if( $pr->execute() ) {
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}else{
			return array();
		}
	
	}
	
    public function UpdateMeta(dto_cartera $dtoCartera) {

        $usuario_modificacion = $dtoCartera->getUsuarioModificacion();
        $meta_cliente = $dtoCartera->getMetaCliente();
        $meta_cuenta = $dtoCartera->getMetaCuenta();
        $idcartera = $dtoCartera->getId();

        $sql = " UPDATE ca_cartera SET usuario_modificacion = ? , fecha_modificacion = NOW(), meta_cliente = ? , meta_cuenta = ?  
				WHERE idcartera = ? ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        $pr->bindParam(2, $meta_cliente, PDO::PARAM_INT);
        $pr->bindParam(3, $meta_cuenta, PDO::PARAM_INT);
        $pr->bindParam(4, $idcartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }
	
	 public function UpdateMetaFecha(dto_cartera $dtoCartera,$flag_provincia) {

        $usuario_modificacion = $dtoCartera->getUsuarioModificacion();
	$fecha_inicio = $dtoCartera->getFechaInicio();
	$fecha_fin = $dtoCartera->getFechaFin();
        $meta_cliente = $dtoCartera->getMetaCliente();
        $meta_cuenta = $dtoCartera->getMetaCuenta();
        $meta_monto = $dtoCartera->getMetaMonto();
	$nombre_cartera = $dtoCartera->getNombreCartera();
        $idcartera = $dtoCartera->getId();

        $sql = " UPDATE ca_cartera 
				SET usuario_modificacion = ? , fecha_modificacion = NOW(), 
				fecha_inicio = ?, fecha_fin = ?,
				meta_cliente = ? , meta_cuenta = ?  , meta_monto = ? ,
				nombre_cartera = ? ,flag_provincia=$flag_provincia
				WHERE idcartera = ? ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
	$pr->bindParam(2, $fecha_inicio, PDO::PARAM_STR);
        $pr->bindParam(3, $fecha_fin, PDO::PARAM_STR);
        $pr->bindParam(4, $meta_cliente, PDO::PARAM_INT);
        $pr->bindParam(5, $meta_cuenta, PDO::PARAM_INT);
        $pr->bindParam(6, $meta_monto, PDO::PARAM_INT);
	$pr->bindParam(7, $nombre_cartera, PDO::PARAM_INT);
        $pr->bindParam(8, $idcartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function active(dto_cartera $dtoCartera) {

        $usuario_modificacion = $dtoCartera->getUsuarioModificacion();
        $idcartera = $dtoCartera->getId();

        $sql = " UPDATE ca_cartera SET usuario_modificacion = ? , fecha_modificacion = NOW(), status = 'ACTIVO' 
			WHERE idcartera IN ( " . $idcartera . " ) ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        //$pr->bindParam(2,$idcartera);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function desactive(dto_cartera $dtoCartera) {

        $usuario_modificacion = $dtoCartera->getUsuarioModificacion();
        $idcartera = $dtoCartera->getId();

        $sql = " UPDATE ca_cartera SET usuario_modificacion = ? , fecha_modificacion = NOW() ,  status = 'DESACTIVO'
			WHERE idcartera IN ( " . $idcartera . " ) ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        //$pr->bindParam(2,$idcartera);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function delete(dto_cartera $dtoCartera) {

        $usuario_modificacion = $dtoCartera->getUsuarioModificacion();
        $idcartera = $dtoCartera->getId();

        $sql = " UPDATE ca_cartera SET estado = 0, usuario_modificacion = ? , fecha_modificacion = NOW() 
			WHERE idcartera IN ( " . $idcartera . " ) ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_modificacion, PDO::PARAM_INT);
        //$pr->bindParam(2,$idcartera);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function queryAllCarteraByCamp(dto_cartera $dtoCartera) {

        $sql = " SELECT idcartera,codigo_cliente, tabla, numero_cuenta, moneda_cuenta, moneda_operacion , codigo_operacion, 
			cliente, cuenta, detalle_cuenta, telefono, direccion, adicionales, cabeceras 
			FROM ca_cartera 
			WHERE idcampania = ? AND estado = 1 ";

        $idcampania = $dtoCartera->getIdCampania();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idcampania, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryAllByService(dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " SELECT car.idcartera , car.nombre_cartera , 
                cam.nombre AS campania , IFNULL(car.fecha_inicio,'') AS fecha_inicio , 
                IFNULL(car.fecha_fin,'') AS fecha_fin,car.flag_provincia 
		FROM ca_campania cam INNER JOIN ca_cartera car ON car.idcampania = cam.idcampania 
		WHERE cam.idservicio = ? AND car.estado = 1 AND cam.estado = 1 and car.status='ACTIVO' and cam.status='ACTIVO'
                ORDER BY idcartera DESC ";

        $idservicio = $dtoUsuarioServicio->getIdServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idservicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryCarteraMetaData(dto_cartera $dtoCartera) {
        $idcartera = $dtoCartera->getId();
        $sql = " SELECT idcartera,codigo_cliente, tabla, numero_cuenta, moneda_cuenta, moneda_operacion , codigo_operacion, 
			cliente, cartera, cuenta, detalle_cuenta, telefono, direccion, adicionales, cabeceras 
			FROM ca_cartera 
			WHERE idcartera IN (" . $idcartera . ") ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$idcartera,PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function queryIdNombreActivos(dto_campanias $dtoCampania) {

        $sql = "SELECT idcartera,
				nombre_cartera as nombre_cartera, 
				IFNULL(fecha_inicio,'') AS 'fecha_inicio', 
				IFNULL(fecha_fin,'') AS 'fecha_fin',
				if( date(fecha_fin)<date(now()), 1 , 0) as 'vencido'
			FROM ca_cartera WHERE estado=1 AND idcampania=? AND status = 'ACTIVO' ";

        $campania = $dtoCampania->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        $pr->bindParam(1, $campania);

        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }
    /*jmore*/
    public function updateNormalizacionTelefono(dto_cartera $dtoCartera){

        $cartera = $dtoCartera->getId();
        $usuario_modificacion = $dtoCartera->getUsuarioModificacion();
        
        $factoryConnection=FactoryConnection::create('mysql');
        $connection=$factoryConnection->getConnection();


        $sqlidtelefono="SELECT idtelefono from ca_telefono 
            WHERE idcartera IN ( ".$cartera.") AND DATE(fecha_creacion)=DATE(now())";
        $pridtelefono=$connection->prepare($sqlidtelefono);
        $pridtelefono->execute();

        $idtelefono="";
        while($row_idtelefono=$pridtelefono->fetch(PDO::FETCH_ASSOC)){
            $idtelefono.=$row_idtelefono['idtelefono'].",";
        }        

        $idtelefono=substr($idtelefono,0,strlen($idtelefono)-1);
        
        /*LETRAS*/
        $mayuscula=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','W','V','X','Y','Z','-',')','(','*','#','/',' ','.','_',',',':','+','@','?','·');
        /*borrando caractares especiales*/
        $str_rpl = " REPLACE(UPPER(TRIM(numero)),'".$mayuscula[0]."','') ";
        for($i=1;$i<count($mayuscula);$i++){
        
            $str_rpl = " REPLACE( ( ".$str_rpl." ) , '".$mayuscula[$i]."' ,'' ) ";
        
        }
        
       /*borrando ceros y unos a la izquierda aquellos que son difrerentes que 9 digitos*/
        $sql="UPDATE ca_telefono 
                SET 
                numero_act = REPLACE( ( REPLACE( (REPLACE( (TRIM(LEADING '0' FROM ( TRIM(LEADING '1' FROM ( TRIM(LEADING '0' FROM ( ".$str_rpl." ) ) ) ) ) ) ),'-','') ),' ','' ) ),'/','' ) 
                WHERE idcartera IN ( ".$cartera." ) AND LENGTH(numero) BETWEEN 3 AND 30 AND estado=1 AND numero_act IS NULL and DATE(fecha_creacion)=DATE(now()) and idtelefono IN ($idtelefono)";
        $pr=$connection->prepare($sql);
        if($pr->execute()){
        
        }else{
            return array("rst"=>false,"msg"=>"Error en la Normalizacion de los Telefonos");
            exit();
        }
        
        /*si el celular tiene 9 digistos y a pesar de eso tiene uno o otro numero adelant LO SACAMOSe*/
        /*$sqlnueve="UPDATE ca_telefono 
                SET 
                numero_act= SUBSTR(numero_act,LENGTH(numero_act)-8,LENGTH(numero_act)) 
                WHERE idcartera IN ( ".$cartera." ) AND SUBSTR(numero_act,LENGTH(numero_act)-8,1)=9 
                AND LENGTH(numero_act)<=12 AND LENGTH(numero_act)>9 AND SUBSTR(numero_act,1,1)!=0 AND estado=1";
                
        $prnueve=$connection->prepare($sqlnueve);
        if($prnueve->execute()){
            
        }else{
            return array("rst"=>false,"msg"=>"Error en la Normalizacion de los Telefonos");
            exit();
        }*/
        //PREFIJO PROVINCIAS
        /*$sqlPrefijo = " UPDATE ca_telefono tel
                        SET
                        tel.numero_act = IF( LENGTH(tel.numero_act) = 8, 
                                    CONCAT('0',tel.numero_act),
                                    IFNULL(
                                        ( 
                                        SELECT 
                                        CASE
                                        WHEN SOUNDEX(departamento) = SOUNDEX('AMAZONAS') THEN CONCAT('041',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('ANCASH') THEN CONCAT('043',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('APURIMAC') THEN CONCAT('083',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('AREQUIPA') THEN CONCAT('054',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('AYACUCHO') THEN CONCAT('066',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('CAJAMARCA') THEN CONCAT('076',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('CUSCO') THEN CONCAT('084',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('HUANCAVELICA') THEN CONCAT('067',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('HUANUCO') THEN CONCAT('062',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('ICA') THEN CONCAT('056',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('JUNIN') THEN CONCAT('064',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('LA LIBERTAD') THEN CONCAT('044',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('LAMBAYEQUE') THEN CONCAT('074',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('LORETO') THEN CONCAT('065',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('MADRE DE DIOS') THEN CONCAT('082',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('MOQUEGUA') THEN CONCAT('053',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('PASCO') THEN CONCAT('063',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('PIURA') THEN CONCAT('073',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('PUNO') THEN CONCAT('051',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('SAN MARTIN') THEN CONCAT('042',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('TACNA') THEN CONCAT('052',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('TUMBES') THEN CONCAT('072',tel.numero_act)
                                        WHEN SOUNDEX(departamento) = SOUNDEX('UCAYALI') THEN CONCAT('061',tel.numero_act)
                                        ELSE tel.numero_act END
                                        FROM ca_direccion 
                                        WHERE idcartera = tel.idcartera AND idcuenta = tel.idcuenta 
                                        AND TRIM(departamento)!='' AND TRIM(departamento) NOT IN ('LIMA','LIM','CALLAO')
                                        AND LENGTH(departamento) BETWEEN 2 AND 20
                                        LIMIT 1
                                        ),
                                    tel.numero_act
                                    )
                                     )
                        WHERE tel.idcartera IN ( ".$cartera." ) AND LENGTH(tel.numero_act) IN (6,8) ";
        
        $prPrefijo = $connection->prepare( $sqlPrefijo );
        $prPrefijo->bindParam(1,$usuario_modificacion,PDO::PARAM_INT);
        if( $prPrefijo->execute() ){

        }else{
            return array("rst"=>false,"msg"=>"Error en la Normalizacion de los Telefonos");
            exit();
        }*/

        /*PONER NULLO NUMEROS MAYORES A 9 DIGITOS Y MENORES A 7 LO SACAMOS*/
/*        $sqlfiltrando=" UPDATE ca_telefono
                        set numero_act=NULL
                        where idcartera=$cartera and LENGTH(numero_act)>9 or LENGTH(numero_act)<7";

        $prfiltrando=$connection->prepare($sqlfiltrando);

        if( $prfiltrando->execute() ){

        }else{
            return array("rst"=>false,"msg"=>"Error en la Normalizacion de los Telefonos");
            exit();
        }*/

        /*ACTUALIZANDO A NULLO LOS CELULARES QUE COMIENZAN EN 9 Y A LA VEZ QUE NO SEAN 9 DIGITOS LO SACAMOS*/
        /*$sqlfiltrandocelulares=" UPDATE ca_telefono
                                set numero_act=NULL
                                where idcartera=$cartera and SUBSTR(numero_act,1,1)=9 and LENGTH(numero_act)<9 and estado=1";

        $prfiltrandocelulares=$connection->prepare($sqlfiltrandocelulares);

        if( $prfiltrandocelulares->execute() ){
        }else{
            return array("rst"=>false,"msg"=>"Error en la Normalizacion de los Telefonos");
            exit();
        }*/

        /*ACTUALIZANDO A NULO LOS TELEFONOS QUE NO FUERON NORMALIZADOS*/
        $sqlnormalizado=" UPDATE ca_telefono
                            set numero_act=NULL
                            where idcartera=$cartera and numero=numero_act and estado=1 AND numero_act IS NOT NULL and DATE(fecha_creacion)=DATE(now()) and idtelefono in ($idtelefono)";

        $prnormalizado=$connection->prepare($sqlnormalizado);

        if( $prnormalizado->execute() ){

        }else{
            return array("rst"=>false,"msg"=>"Error en la Normalizacion de los Telefonos");
            exit();
        }

        /*ACTUALIZANDO NUMERO DE CARTERA SI TIENE 7, 8 DIGITOS Y ES CELULAR*/
        $sqlfiltrandocelularescartera=" UPDATE ca_telefono
                                set numero_act=NULL
                                where idcartera=$cartera and SUBSTR(numero,1,1)=9 and LENGTH(numero)<9 and estado=1 and numero_act IS NOT NULL and DATE(fecha_creacion)=DATE(now()) and idtelefono in ($idtelefono)";

        $prfiltrandocelularescartera=$connection->prepare($sqlfiltrandocelularescartera);            

        if( $prfiltrandocelularescartera->execute() ){
        }else{
            return array("rst"=>false,"msg"=>"Error en la Normalizacion de los Telefonos");
        }            

        ///////////VALIDACION BBVA////////////////////////
        //////////////////////////////////////////////////      
        //quita cero delante de CELULARES, vienen 10 caracteres un cero adelante, quedan 9 digitos
        /*$sql="UPDATE ca_telefono
            set numero_act=SUBSTR(numero, 2)
            where idcartera=$cartera and LENGTH(numero)='10' and SUBSTR(numero,2,1)='9' and SUBSTR(numero,1,1)=0 and numero!='0999999999'
                and estado=1 and numero_act IS NULL AND DATE(fecha_creacion)=DATE(NOW())";

        $pr=$connection->prepare($sql);            

        if( $pr->execute() ){
        }else{
            return array("rst"=>false,"msg"=>"Error en la Normalizacion de los Telefonos");
        }
        //quita cero delante de FIJOS provincia, vienen 7 digitos un cero delante, quedan 6 digitos
        $sql=" UPDATE ca_telefono
                set numero_act=SUBSTR(numero, 2)
                where idcartera=$cartera and LENGTH(numero)='7' and SUBSTR(numero,1,1)='0' and estado=1 AND numero_act IS NULL AND DATE(fecha_creacion)=DATE(NOW())";

        $pr=$connection->prepare($sql);            

        if( $pr->execute() ){
        }else{
            return array("rst"=>false,"msg"=>"Error en la Normalizacion de los Telefonos");
        }

        //quita ceros delante de FIJOS provincia, vienen 10 digitos 4 ceros delante
        $sql=" UPDATE ca_telefono
                set numero_act=SUBSTR(numero, 5)
                where idcartera=$cartera and LENGTH(numero)='10' and SUBSTR(numero,1,4)='0000' and SUBSTR(numero,5,1)!=0 and estado=1 and numero_act IS NULL AND DATE(fecha_creacion)=DATE(NOW())";

        $pr=$connection->prepare($sql);            

        if( $pr->execute() ){
        }else{
            return array("rst"=>false,"msg"=>"Error en la Normalizacion de los Telefonos");
        }

        //quita ceros delante de FIJOS provincia, vienen 9 digitos 3 ceros delante
        $sql=" UPDATE ca_telefono
                set numero_act=SUBSTR(numero, 4)
                where idcartera=$cartera and LENGTH(numero)='9' and SUBSTR(numero,1,3)='000' and SUBSTR(numero,4,1)!=0 and estado=1 AND numero_act IS NULL AND DATE(fecha_creacion)=DATE(NOW())";

        $pr=$connection->prepare($sql);            

        if( $pr->execute() ){
        }else{
            return array("rst"=>false,"msg"=>"Error en la Normalizacion de los Telefonos");
        }
        */
        //pone referencia (prefijo) a los YA NORMALIZADOS, y q referencia sea de provicncia osea diferente a 
        //referencia='001' or referencia='000' or referencia='1' or referencia='' or referencia is null or LENGTH(numero)=9
        $sql=" UPDATE ca_telefono
            set numero_act=concat(cast(referencia as signed),numero_act)
            where idcartera=$cartera and 
            (referencia!='001' and CAST(referencia AS SIGNED)!='0' and referencia!='1' and referencia!='' and referencia is not null and LENGTH(numero_act)!=9) 
            and estado=1 and numero_act is not null 
            and LENGTH(CAST(referencia AS signed))=2 -- solo prefijos de dos digitos
            and LENGTH(numero_act)=6 and DATE(fecha_creacion)=DATE(now()) and idtelefono in ($idtelefono)";

        $pr=$connection->prepare($sql);            

        if( $pr->execute() ){
        }else{
            return array("rst"=>false,"msg"=>"Error en la Normalizacion de los Telefonos");
        }  

        /*SI tienen 6 digitos y */
        $sql=" UPDATE ca_telefono
            set numero_act=concat(cast(referencia as signed),numero)
            where idcartera=$cartera and 
            (referencia!='001' and CAST(referencia AS SIGNED)!='0' and referencia!='1' and referencia!='' and referencia is not null and LENGTH(numero)=6) 
            and estado=1 and numero_act IS null 
            and LENGTH(CAST(referencia AS signed))=2 and DATE(fecha_creacion)=DATE(now()) and idtelefono in ($idtelefono)";

        $pr=$connection->prepare($sql);            

        if( $pr->execute() ){
        }else{
            return array("rst"=>false,"msg"=>"Error en la Normalizacion de los Telefonos");
        }          

        //finalizando
        return array("rst"=>true,"msg"=>"Normalizacion Satisfactoriamente!");

    }
    /*jmore*/    

    public function queryIdNombreActivosRpteRank(dto_campanias $dtoCampania, $estado) {
        $campania = $dtoCampania->getId();

        $sql = "SELECT idcartera,
	if( date(fecha_fin)<date(now()), concat('<font color=red>',nombre_cartera,'</font>') , nombre_cartera) as nombre_cartera, 
	IFNULL(fecha_inicio,'') AS 'fecha_inicio', 
	IFNULL(fecha_fin,'') AS 'fecha_fin',
	if( date(fecha_fin)<date(now()), 1 , 0) as 'vencido'
FROM ca_cartera WHERE estado=1 AND idcampania=" . $campania . " and (if( date(fecha_fin)<date(now()), 1 , 0)) in (" . $estado . ") ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        //$pr->bindParam(1,$campania);
        //$pr->bindParam(2,$estado,PDO::PARAM_STR);

        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryIdNombreActivosOperador(dto_campanias $dtoCampania, $usuario) {

        $sql = "SELECT distinct car.idcartera,
				if( date(car.fecha_fin)<date(now()), concat('<font color=red>',car.nombre_cartera,'</font>') , car.nombre_cartera) as 'nombre_cartera', 
				IFNULL(car.fecha_inicio,'') AS 'fecha_inicio', 
				IFNULL(car.fecha_fin,'') AS 'fecha_fin',
				if( date(car.fecha_fin)<date(now()), 1 , 0) as 'vencido'
			FROM ca_cartera car
			left join ca_cliente_cartera clicar on clicar.idcartera=car.idcartera
			WHERE car.estado=1 AND car.idcampania=?  and clicar.idusuario_servicio=?";

        $campania = $dtoCampania->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        $pr->bindParam(1, $campania);
        $pr->bindParam(2, $usuario);

        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function queryListarDistritoCartera($cartera){

		$sql="select DISTINCT dato4 as distrito from ca_cliente_cartera where idcartera=$cartera and estado=1 order by distrito";
		//$sql="SELECT DISTINCT dato9 AS distrito FROM ca_cuenta WHERE idcartera=".$cartera." AND estado=1 ORDER BY distrito ";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr= $connection->prepare($sql);

        if($pr->execute()){
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return array();
        }
    }
    public function queryListarFproceso($cartera){
        $sql="select fproceso as fecha,CONCAT(SUBSTR(fecha,7,2),SUBSTR(fecha,4,2),SUBSTR(fecha,1,2)) as orden_fecha from (
                select DISTINCT his.Fproceso,CASE SUBSTR(his.fproceso,4,3)  WHEN 'Ene' THEN REPLACE(his.fproceso,'Ene','01') 
                WHEN 'Feb' THEN REPLACE(his.fproceso,'Feb','02') WHEN 'Mar' THEN REPLACE(his.fproceso,'Mar','03')                                               
                WHEN 'Abr' THEN REPLACE(his.fproceso,'Abr','04') WHEN 'May' THEN REPLACE(his.fproceso,'May','05') 
                WHEN 'Jun' THEN REPLACE(his.fproceso,'Jun','06') WHEN 'Jul' THEN REPLACE(his.fproceso,'Jul','07') 
                WHEN 'Ago' THEN REPLACE(his.fproceso,'Ago','08') WHEN 'Set' THEN REPLACE(his.fproceso,'Set','09') 
                WHEN 'Oct' THEN REPLACE(his.fproceso,'Oct','10') WHEN 'Nov' THEN REPLACE(his.fproceso,'Nov','11') 
                WHEN 'Dic' THEN REPLACE(his.fproceso,'Dic','12') END fecha
                from ca_historial his
                inner join ca_cliente_cartera clicar on clicar.idcliente_cartera = his.idcliente_cartera
                where clicar.idcartera in($cartera) ORDER BY his.datesys DESC)A";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr= $connection->prepare($sql);

        if($pr->execute()){
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return array();
        }
    }

    public function queryListarFprocesomultiple($cartera){
        $sql="select fproceso as fecha,CONCAT(SUBSTR(fecha,7,2),SUBSTR(fecha,4,2),SUBSTR(fecha,1,2)) as orden_fecha from (
                select DISTINCT his.Fproceso,CASE SUBSTR(his.fproceso,4,3)  WHEN 'Ene' THEN REPLACE(his.fproceso,'Ene','01') 
                WHEN 'Feb' THEN REPLACE(his.fproceso,'Feb','02') WHEN 'Mar' THEN REPLACE(his.fproceso,'Mar','03')                                               
                WHEN 'Abr' THEN REPLACE(his.fproceso,'Abr','04') WHEN 'May' THEN REPLACE(his.fproceso,'May','05') 
                WHEN 'Jun' THEN REPLACE(his.fproceso,'Jun','06') WHEN 'Jul' THEN REPLACE(his.fproceso,'Jul','07') 
                WHEN 'Ago' THEN REPLACE(his.fproceso,'Ago','08') WHEN 'Set' THEN REPLACE(his.fproceso,'Set','09') 
                WHEN 'Oct' THEN REPLACE(his.fproceso,'Oct','10') WHEN 'Nov' THEN REPLACE(his.fproceso,'Nov','11') 
                WHEN 'Dic' THEN REPLACE(his.fproceso,'Dic','12') END fecha
                from ca_historial his
                inner join ca_cliente_cartera clicar on clicar.idcliente_cartera = his.idcliente_cartera
                where clicar.idcartera in($cartera) ORDER BY his.datesys DESC)A";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr= $connection->prepare($sql);

        if($pr->execute()){
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return array();
        }
    }    

    public function queryListarTerritorio($cartera,$fproceso){
        $sql="select DISTINCT his.Territorio from ca_historial his
                inner join ca_cliente_cartera clicar on clicar.idcliente_cartera = his.idcliente_cartera
                where clicar.idcartera=$cartera and his.Fproceso in ($fproceso) order by Territorio";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr= $connection->prepare($sql);

        if($pr->execute()){
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return array();
        }
    }
    public function queryCantidadDiasMora($idcartera,$idusuario_servicio,$filtrousuario){
        $sql="SELECT detcu.dias_mora,COUNT(*) AS 'CANTIDAD' FROM ca_cliente_cartera clicar
            INNER JOIN ca_cuenta cu ON cu.idcliente_cartera=clicar.idcliente_cartera
            INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta 
            WHERE clicar.idcartera IN ($idcartera) AND cu.retirado=0 $filtrousuario
            GROUP BY detcu.dias_mora ORDER BY CAST(detcu.dias_mora AS SIGNED)";

            $factoryConnection=FactoryConnection::create('mysql');
            $connection=$factoryConnection->getConnection();
            $pr=$connection->prepare($sql);
            if($pr->execute()){
                return $pr->fetchAll(PDO::FETCH_ASSOC);
            }else{
                return array();
            }
    }
    public function queryCantidadTerritorio($idcartera,$idusuario_servicio,$filtrousuario){
        $sql="SELECT cu.dato9 AS 'TERRITORIO',COUNT(*) AS 'CANTIDAD' FROM ca_cliente_cartera clicar
            INNER JOIN ca_cuenta cu ON cu.idcliente_cartera=clicar.idcliente_cartera
            WHERE clicar.idcartera IN ($idcartera) AND cu.retirado=0 $filtrousuario
            GROUP BY cu.dato9";

            $factoryConnection=FactoryConnection::create('mysql');
            $connection=$factoryConnection->getConnection();
            $pr=$connection->prepare($sql);
            if($pr->execute()){
                return $pr->fetchAll(PDO::FETCH_ASSOC);
            }else{
                return array();
            }
    }        

}

?>
