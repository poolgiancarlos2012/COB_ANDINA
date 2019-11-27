<?php
class WSNeotelDAO {
    
    public function getPosition($usu_neotel){
        $position=$this->Position($usu_neotel);
        //$position=array('STATUS'=>'OK','DEVICE'=>'SIP/prueba1','USUARIO'=>'9996','SAL_CAMPANA_DEFAULT'=>'7','SUBTIPO_DESCANSO'=>'0','ESTADO_CRM'=>'GetContact','BASE'=>'0','IDCONTACTO'=>'0','DATA'=>'361907','TELEFONO'=>'991047810','IDLLAMADA'=>'12345689');
        //$position=array('STATUS'=>'OK','DEVICE'=>'SIP/prueba1','USUARIO'=>'9996','SAL_CAMPANA_DEFAULT'=>'7','SUBTIPO_DESCANSO'=>'0','ESTADO_CRM'=>'SHOWINGCONTACT','BASE'=>'0','IDCONTACTO'=>'0','DATA'=>'361907','TELEFONO'=>'991047810','IDLLAMADA'=>'12345689');
        if(isset($position['STATUS'])){
            return array('rst'=>true,'msg'=>'ok','data'=>$position);
        }else{
            return array('rst'=>false,'msg'=>' Verifique <b>Login - Usuario</b>, NEOTEL','data'=>array());
        };
    }
    public function getStatus($usu_neotel){
        $position=$this->Position($usu_neotel);
        if(isset($position['STATUS'])){
            if($position['STATUS']=='OK'){
                return array('rst'=>true,'msg'=>'OK');
            }else{
                return array('rst'=>false,'msg'=>' Verifique <b>Login - Usuario</b>, NEOTEL');
            }
        }else{
            return array('rst'=>false,'msg'=>' Verifique <b>Login - Usuario</b>, NEOTEL');
        };
    }
    public function resumenPosition($usuario){
        $data=Position($usuario);
        $return='';
        if(count($data)>0){
            $return='STATUS = '.$data['STATUS'].' - USUARIO = '.$data['USUARIO'].' - SAL_CAMPAÑA_DEFAULT = '.$data['SAL_CAMPAÑA_DEFAULT'].' - SUBTIPO_DESCANSO = '.$data['SUBTIPO_DESCANSO'].' - ESTADO_CRM = '.$data['ESTADO_CRM'];    
        }
        return $return; 
    }
/**************metodos neotel***************/
    public function getWSNeotel(){//conecta a webservices
        $client = new nusoap_client('http://192.168.20.18/NEOAPI/webservice.asmx?wsdl',true);
        return $client;
    }
    public function Unpause($usuario){//quita estado de PAUSA
        $client = $this->getWSNeotel();
        //~ Funcion de la Web Services
        $result = $client->call( 'Unpause', array('USUARIO'=>$usuario) );
        if($result==''){return true;}else{return false;}
    }
    public function Pause($usuario,$subtipo_descanso){//envia estado de PAUSA a servidor
        $client = $this->getWSNeotel();
        //~ Funcion de la Web Services
        $result = $client->call( 'Pause', array('USUARIO'=>$usuario,'SUBTIPO_DESCANSO'=>$subtipo_descanso) );
        if($result==''){return true;}else{return false;}
    }
    public function Logout($usuario){//desloguea de campaña y de servidor
        $client = $this->getWSNeotel();
        //~ Funcion de la Web Services
        $result = $client->call( 'Logout', array('USUARIO'=>$usuario) );
        //return $result;
        if($result==''){return true;}else{return false;}
    }
    public function CRM_Unavailable($usuario){//pon en estado NO_DISPONIBLE
        $client = $this->getWSNeotel();
        //~ Funcion de la Web Services
        $result = $client->call( 'CRM_Unavailable', array('USUARIO'=>$usuario) );
        if(isset($result['CRM_UnavailableResult'])){return true;}else{return false;}
    }
    public function CRM_Available($usuario){//pone en estado disponible
        $client = $this->getWSNeotel();
        //~ Funcion de la Web Services
        $result = $client->call( 'CRM_Available', array('USUARIO'=>$usuario) );
        //return $result;
        if($result==''){return true;}else{return false;}
    }
    public function AddScheduleCall($usuario,$base,$idcontacto,$data,$telefono,$fecha_agendax){//agrega un agenda
        $client = $this->getWSNeotel();
        //~ Funcion de la Web Services
        $fecha_agenda=str_replace(' ', 'T', $fecha_agendax);
        $result = $client->call( 'AddScheduleCall', array('USUARIO'=>$usuario,'BASE'=>$base,'IDCONTACTO'=>$idcontacto,'DATA'=>$data,'TELEFONO'=>$telefono,'FECHA_AGENDA'=>$fecha_agenda) );
        //var_dump($result);
        if( isset($result['AddScheduleCallResult']) ){return true;}else{return false;}
    }
    public function CloseContact($base,$idcontacto){//cierra llamada de un contacto
        $client = $this->getWSNeotel();
        //~ Funcion de la Web Services
        $result = $client->call( 'CloseContact', array('BASE'=>$base,'IDCONTACTO'=>$idcontacto) );
        //return $result;
        if($result==''){return true;}else{return false;}
    }
    public function CRM_ShowingContact($usuario,$base,$idcontacto,$data){//pone en servidor estado de mostrando llamada en el cliente, pa q no le lance llamadas mientras
        $client = $this->getWSNeotel();
        //~ Funcion de la Web Services
        $result = $client->call( 'CRM_ShowingContact', array('USUARIO'=>$usuario,'BASE'=>$base,'IDCONTACTO'=>$idcontacto,'DATA'=>$data) );
        //return $result;
        if($result==''){return true;}else{return false;}
    }
    public function Logout_Campaign($usuario){//desloguea campaña
        $client = $this->getWSNeotel();
        //~ Funcion de la Web Services
        $result = $client->call( 'Logout_Campaign', array('USUARIO'=>$usuario) );
        if($result==''){return true;}else{return false;}
    }
    public function Login_Campaign2($usuario,$campania){//loguea campaña
        $client = $this->getWSNeotel();
        //~ Funcion de la Web Services
        $result = $client->call( 'Login_Campaign2', array('USUARIO'=>$usuario,'CAMPANA'=>$campania) );
        if($result==''){return true;}else{return false;}
    }
    public function Position($usuario){//trae un resumen de datos de la posicion
        $client = $this->getWSNeotel();
        //~ Funcion de la Web Services
        $result = $client->call( 'Position', array('USUARIO'=>$usuario) );
        //result viene en string
        if(isset($result['PositionResult'])){
            $data=$this->resultToArray($result['PositionResult']);
            return $data;   
        }else{
            return array();
        }
    }
    public function Inicia_LLamada($usuario,$numero){//desloguea campaña
        $client = $this->getWSNeotel();
        //~ Funcion de la Web Services
        $result = $client->call( 'Dial', array('USUARIO'=>$usuario,'TELEFONO'=>$numero) );
        if($result){return true;}else{return false;}
    }    
    public function Parar_LLamada($usuario){//desloguea campaña
        $client = $this->getWSNeotel();
        //~ Funcion de la Web Services
        $result = $client->call( 'Hangup', array('USUARIO'=>$usuario) );
        if($result==''){return true;}else{return false;}
    }    
    public function resultToArray($txt){
        $datax=utf8_encode($txt);
        $data=explode('|', $datax); //valores viene separados por barra "|"
        $final=array();
        foreach ($data as $key => $value) {
            if($value!=''){//algunos valores vienen vacios
                $x=explode('=', $value);
                $final[str_replace('Ñ', 'N', $x[0])]=$x[1];
            }
        }
        return $final;
    }
}
?>