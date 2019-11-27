<?php

	class PGSQL_PDODatosAdicionalesClienteDAO {
		
		public function queryByCliente ( dto_cliente $dtoCliente , dto_cartera $dtoCartera , dto_servicio $dtoServicio  ) {
			$sql=" SELECT dato1,dato2,dato3,dato4,dato5,dato6,dato7,dato8,dato9,dato10,dato11,dato12,dato13,dato14,dato15,
				dato16,dato17,dato18,dato19,dato20,dato21,dato22,dato23,dato24,dato25,dato26,dato27,dato28,dato29,dato30,dato31,
				dato32,dato33,dato34,dato35,dato36,dato37,dato38,dato39,dato40,dato41,dato42,dato43,dato44,dato45,dato46,dato47,
				dato48,dato49,dato50
				FROM ca_cabeceras WHERE idservicio = ? AND idtipo_datos_adicionales=1 AND idcartera = ? 
				UNION
				SELECT IFNULL(dato1,'') AS 'dato1',IFNULL(dato2,'') AS 'dato2',IFNULL(dato3,'') AS 'dato3',
				IFNULL(dato4,'') AS 'dato4',IFNULL(dato5,'') AS 'dato5',IFNULL(dato6,'') AS 'dato6',IFNULL(dato7,'') AS 'dato7',
				IFNULL(dato8,'') AS 'dato8',IFNULL(dato9,'') AS 'dato9',IFNULL(dato10,'') AS 'dato10',IFNULL(dato11,'') AS 'dato11',
				IFNULL(dato12,'') AS 'dato12',IFNULL(dato13,'') AS 'dato13',IFNULL(dato14,'') AS 'dato14',
				IFNULL(dato15,'') AS 'dato15',IFNULL(dato16,'') AS 'dato16',IFNULL(dato17,'') AS 'dato17',
				IFNULL(dato18,'') AS 'dato18',IFNULL(dato19,'') AS 'dato19',IFNULL(dato20,'') AS 'dato20',
				IFNULL(dato21,'') AS 'dato21',IFNULL(dato22,'') AS 'dato22',IFNULL(dato23,'') AS 'dato23',
				IFNULL(dato24,'') AS 'dato24',IFNULL(dato25,'') AS 'dato25',IFNULL(dato26,'') AS 'dato26',
				IFNULL(dato27,'') AS 'dato27',IFNULL(dato28,'') AS 'dato28',IFNULL(dato29,'') AS 'dato29',
				IFNULL(dato30,'') AS 'dato30',IFNULL(dato31,'') AS 'dato31',IFNULL(dato32,'') AS 'dato32',
				IFNULL(dato33,'') AS 'dato33',IFNULL(dato34,'') AS 'dato34',IFNULL(dato35,'') AS 'dato35',
				IFNULL(dato36,'') AS 'dato36',IFNULL(dato37,'') AS 'dato37',IFNULL(dato38,'') AS 'dato38',
				IFNULL(dato39,'') AS 'dato39',IFNULL(dato40,'') AS 'dato40',IFNULL(dato41,'') AS 'dato41',
				IFNULL(dato42,'') AS 'dato42',IFNULL(dato43,'') AS 'dato43',IFNULL(dato44,'') AS 'dato44',
				IFNULL(dato45,'') AS 'dato45',IFNULL(dato46,'') AS 'dato46',IFNULL(dato47,'') AS 'dato47',
				IFNULL(dato48,'') AS 'dato48',IFNULL(dato49,'') AS 'dato49',IFNULL(dato50,'') AS 'dato50'
				FROM ca_datos_adicionales_cliente WHERE codigo_cliente = ? AND idcartera = ? ";
				
			$servicio=$dtoServicio->getId();
			//$cliente=$dtoCliente->getId();
			$codigo_cliente=$dtoCliente->getCodigo();
			$cartera=$dtoCartera->getId();
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');	
			$connection = $factoryConnection->getConnection();
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$servicio);
			$pr->bindParam(2,$cartera);
			$pr->bindParam(3,$codigo_cliente);
			$pr->bindParam(4,$cartera);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);		
			
		}
		
	}	
	
?>