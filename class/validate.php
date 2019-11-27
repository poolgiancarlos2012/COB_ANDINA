<?php
    require_once '../phpincludes/inspekt-0.4.0/Inspekt.php';
    class validate {

        public static function isValidate ( $data ) {
            for ( $i=0 ; $i<count($data) ; $i++  ) {
                switch ($data[$i]['type']):
                    case 'Email':
                        if(!Inspekt::isEmail($data[$i]['value'])){
                            return array('rst'=>false,'msg'=>'Formato de email incorrecto');
                            break;
                        }
                    break;
                    case 'Date':
                        if(!Inspekt::isDate($data[$i]['value'])){
                            return array('rst'=> false,'msg'=>'Formato de fecha incorrecto');
                            break;
                        }
                    break;
                    case 'Int':
                         if(!Inspekt::isInt($data[$i]['value'])){
                            return array('rst'=>false,'msg'=>'Valor ingresado no es variable numerica');
                             break;
                         }
                    break;
                    case 'Between':
                             if(!Inspekt::isBetween($data[$i]['value'],$data[$i]['min'],$data[$i]['max'])){
                                 return array('rst'=>false,'msg'=>'No esta dentro del rango indicado');
                                 break;
                             }
                    break;
                endswitch;
                
                
            }
            return array('rst'=>true,'msg'=>'Correcto');
        }

    }

?>
