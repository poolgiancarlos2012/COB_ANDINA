<?php
/**
 * Description of MYSQLMenuDAO
 *
 * @author Davis
 */
class MARIAMenuDAO {
    public function insert (dto_menu $obj) {
        $sql="INSERT INTO ca_menu (menu,estado) VALUES(?,?)";
        $cn = FactoryConnection::create('mysql')->getConnection();
        $cn->beginTransaction();
        $stm = $cn->prepare($sql);
        $stm->bindParam(1,$obj->getNombreMenu(),PDO::PARAM_STR);
        $stm->bindParam(2,$obj->getEstado(),PDO::PARAM_STR);
        if(!$stm->execute()) {
            return $cn->rollback();
        }else {
            return $cn->commit();
        }
    }
    public function update(dto_menu $obj) {
        $sql="UPDATE ca_menu SET menu=? , estado = ? WHERE id=?";
        $cn=FactoryConnection::create('mysql')->getConnection();
        $stm=$cn->prepare($sql);
        $cn->beginTransaction();
        $stm->bindParam(1,$obj->getNombreMenu(),PDO::PARAM_STR);
        $stm->bindParam(2,$obj->getEstado(),PDO::PARAM_STR);
        $stm->bindParam(3,$obj->getId(),PDO::PARAM_INT);
        if(!$stm->execute()) {
            return $cn->rollback();
        }else {
            return $cn->commit();
        }
    }
    public function delete(dto_menu $obj) {
        $sql="DELETE FROM ca_menu WHERE id=?";
        $cn = FactoryConnection::create('mysql')->getConnection();
        $stm = $cn->prepare($sql);
        $cn->beginTransaction();
        $stm->bindParam(1,$obj->getId());
        if(!$stm->execute()) {
            return $cn->rollback();
        }else {
            return $cn->commit();
        }
    }
    public function listarMenus() {
        $sql="SELECT id,menu,estado FROM ca_menu";
        $cn = FactoryConnection::create('mysql')->getConnection();
        $stm=$cn->prepare($sql);
        $stm->execute();
        var_dump($stm->fetchAll(PDO::FETCH_ASSOC));
    }
    public function count() {
        $sql=" SELECT COUNT(*) AS 'COUNT' FROM ca_menu";
        $factoryConnection= FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr=$connection->prepare($sql);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listJqGrid($sql, $header) {
        $factoryConnection= FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr=$connection->prepare($sql);
        $pr->execute();
        $i=0;
        $response->page=$header['page'];
        $response->total=$header['total'];
        $response->records=$header['records'];

        while($row=$pr->fetch(PDO::FETCH_ASSOC)) {
            $response->rows[$i]['id']=$row['id'];
            $response->rows[$i]['cell']=array($row["id"],$row['menu'],$row['estado'],"<img src='../img/pencil.png' onclick='_showMenu(this)'/>");
            $i++;
        }
        return json_encode($response);
    }
}
?>
