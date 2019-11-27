<?php
/**
 * Description of dto_cliente_cartera
 *
 * @author Davis
 */
class dto_cliente_cartera {
    private $id;
    private $idCliente;
    private $idCartera;
    private $idUsuarioServicio;
    private $idUltimaLlamada;
    private $idUltimaVisita;
	private $idcampania;
	private $codigo_cliente;
    
    private $fechaCreacion;
    private $fechaModificacion;
    private $usuarioCreacion;
    private $usuarioModificacion;
    
    private $deuda;
    private $descuento_ref;
    private $interes_ref;
    private $comision_ref;
    private $mora_ref;
    private $gastos_cobranza_ref;
    private $numero_cuotas_ref;
    private $tipo_pago_ref;
    private $fecha_primer_pago_ref;
    private $observacion_ref;

    public function setId($valor){
        $this->id=$valor;
    }
    public function getId(){
        return $this->id;
    }
    
    public function setDeuda( $valor ) {
    	$this->deuda = $valor;
    }
    public function getDeuda( ) {
    	return $this->deuda;
    }
    
    public function setCodigoCliente( $valor ) {
    	$this->codigo_cliente = $valor;
    }
    public function getCodigoCliente( ) {
    	return $this->codigo_cliente;
    }
    
    public function setObservacionRef( $valor ) {
    	$this->observacion_ref = $valor;
    }
    public function getObservacionRef( ) {
    	return $this->observacion_ref;
    }
    
    public function setFechaPrimerPagoRef( $valor ) {
    	$this->fecha_primer_pago_ref = $valor;
    }
    public function getFechaPrimerPagoRef( ) {
    	return $this->fecha_primer_pago_ref;
    }
    
    public function setTipoPagoRef( $valor ) {
    	$this->tipo_pago_ref = $valor;
    }
    public function getTipoPagoRef( ) {
    	return $this->tipo_pago_ref;
    }
    
    public function setNumeroCuotasRef( $valor ) {
    	$this->setNumeroCuotasRef = $valor;
    }
    public function getNumeroCuotasRef( ) {
    	return $this->setNumeroCuotasRef;
    }
    
    public function setGastosCobranzaRef( $valor ) {
    	$this->gastos_cobranza_ref = $valor;
    }
    public function getGastosCobranzaRef( ) {
    	return $this->gastos_cobranza_ref;
    }
    
    public function setMoraRef( $valor ) {
    	$this->mora_ref = $valor;
    }
    public function getMoraRef( ) {
    	return $this->mora_ref;
    }
    
    public function setComisionRef( $valor ) {
    	$this->comision_ref = $valor;
    }
    public function getComisionRef( ) {
    	return $this->comision_ref;
    }
    
    public function setInteresRef( $valor ) {
    	$this->interes_ref = $valor;
    }
    public function getInteresRef( ) {
    	return $this->interes_ref;
    }
    
    public function setDescuentoRef( $valor ) {
    	$this->descuento_ref = $valor;
    }
    public function getDescuentoRef( ) {
    	return $this->descuento_ref;
    }
	
	public function setIdCampania ( $valor ) {
		$this->idcampania=$valor;
	}
	public function getIdCampania ( ) {
		return $this->idcampania;
	}	

    public function setIdCliente($valor){
        $this->idCliente=$valor;
    }
    public function getIdCliente(){
        return $this->idCliente;
    }

    public function setIdCartera($valor){
        $this->idCartera=$valor;
    }
    public function getIdCartera(){
        return $this->idCartera;
    }

    public function setIdUsuarioServicio($valor){
        $this->idUsuarioServicio=$valor;
    }
    public function getIdUsuarioServicio(){
        return $this->idUsuarioServicio;
    }
    
    public function setIdUltimaLlamada($valor){
        $this->idUltimaLlamada=$valor;
    }
    public function getIdUltimaLlamada(){
        return $this->idUltimaLlamada;
    }

    public function setIdUltimaVisita($valor){
        $this->idUltimaVisita=$valor;
    }
    public function getIdUltimaVisita(){
        return $this->idUltimaVisita;
    }

    public function setFechaCreacion($valor){
        $this->fechaCreacion=$valor;
    }
    public function getFechaCreacion(){
        return $this->fechaCreacion;
    }

    public function setFechaModificacion($valor){
        $this->fechaModificacion=$valor;
    }
    public function getFechaModificacioin(){
        return $this->fechaModificacion;
    }

    public function setUsuarioCreacion($valor){
        $this->usuarioCreacion=$valor;
    }
    public function getUsuarioCreacion(){
        return $this->usuarioCreacion;
    }

    public function setUsuarioModificacion($valor){
        $this->usuarioModificacion=$valor;
    }
    public function getUsuarioModificacion(){
        return $this->usuarioModificacion;
    }
}
?>
