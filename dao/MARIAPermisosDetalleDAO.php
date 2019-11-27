<?php
/**
 * Description of MYSQLPermisosDetalleDAO
 *
 * @author Davis
 */
class MARIAPermisosDetalleDAO {
    public function insert(dto_permisos_detalle $obj) {
        $sql ="INSERT INTO ca_detalle_permiso(idpermiso,idurl,estado) VALUES(?,?,?)";
        $cn = FactoryConnection::create("mysql")->getConnection();
        $stm  = $cn->prepare($sql);
        $stm->bindParam(1,$obj->getIdPermiso(),PDO::PARAM_INT);
        $stm->bindParam(2,$obj->getIdUrl(),PDO::PARAM_INT);
        $stm->bindParam(3,$obj->getEstado(),PDO::PARAM_STR);
        return $stm->execute();
    }
    public function update(dto_permisos_detalle $obj){
        $sql ="UPDATE ca_detalle_permiso SET idurl=? , estado=? , idpermiso=? WHERE iddetallepermiso=?";
        $cn = FactoryConnection::create("mysql")->getConnection();
        $stm = $cn->prepare($sql);
        $stm->bindParam(1,$obj->getIdUrl(),PDO::PARAM_INT);
        $stm->bindParam(2,$obj->getEstado(),PDO::PARAM_STR);
        $stm->bindParam(3,$obj->getIdPermiso(),PDO::PARAM_INT);
        $stm->bindParam(4,$obj->getId(),PDO::PARAM_INT);
        return $stm->execute();
    }
    public function delete(dto_permisos_detalle $obj){
        $sql="DELETE FROM ca_detalle_permiso WHERE iddetallepermiso=?";
        $cn=FactoryConnection::create('mysql')->getConnection();
        $stm = $cn->prepare($sql);
        $stm->bindParam(1,$obj->getId(),PDO::PARAM_INT);
        return $stm->execute();
    }
    public function buscarPermisos(dto_permisos_detalle $obj){
        $sql="SELECT COUNT(*) as cantidad FROM ca_detall_permiso WHERE idpermiso=?";
        $cn=FactoryConnection::create('mysql')->getConnection();
        $stm = $cn->prepare($sql);
        $stm->bindParam(1,$obj->getIdPermiso(),PDO::PARAM_INT);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarPermisos(dto_permisos_detalle $obj){
        $sql="
            SELECT
                m.menu,
                GROUP_CONCAT(CONCAT_WS('@',u.nombre,u.url) SEPARATOR '#') as urls
            FROM
                ca_detalle_permiso dtp
            INNER JOIN
                ca_url u
            ON
                dtp.idurl=u.idurl
            INNER JOIN
                ca_menu m
            ON
                u.idmenu=m.idmenu
            INNER JOIN
                ca_permiso p
            ON
                dtp.idpermiso=p.idpermiso
            WHERE dtp.idpermiso=?
            GROUP BY m.menu";
        $cn=FactoryConnection::create('mysql')->getConnection();
        $stm=$cn->prepare($sql);
        $stm->bindParam(1,$obj->getIdPermiso(),PDO::PARAM_INT);
        return $stm->execute();
    }    
}
?>
