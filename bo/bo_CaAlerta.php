<?php

/**
 * Description of bo_CaAlerta
 *
 * @author Davis
 */

class bo_CaAlerta {
    public function cargarXid($id){
        $daoMysql = new CaAlertaMySqlDAO();
        $daoMysql->load($id);
    }
    public function insertar(CaAlerta $dtoAlerta){
        $daoMysql = new CaAlertaMySqlDAO();
        $daoMysql->insert($dtoAlerta);
    }
}


?>
