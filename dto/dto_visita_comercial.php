<?php


class dto_visita_comercial {
    private $idClienteCartera;
    private $idtipoGestion;
    private $idDireccion;
    private $idNotificador;
    private $idCuenta;
    private $idUsuarioServicio;
    private $fechaCP;
    private $fechaVisita;
    private $horaVisita;
    private $tipo;
    private $estado;
    private $fechaCreacion;
    private $idGiroNegocio;
    private $detalleGiroExtraNegocio;
    private $idCaracteristicaNegocio;
    private $detalleCaracteristicaNegocio;
    private $idmotivoAtrasoNegocio;
    private $detalleMotivoAtrasoNegocio;
    private $idAfrontarPagoNegocio;
    private $detalleAfrontarPagoNegocio;
    private $idCuestionacobranzaNegocio;
    private $idObservacionEspecialistaNegocio;
    private $caracteristicaNegocioEnActividad;
    private $caracteristicaNegocioTieneExistencias;
    private $caracteristicaNegocioLaborArtesanal;
    private $caracteristicaNegocioLocalPropio;
    private $caracteristicaNegocioOficinaAdministrativa;
    private $menorigual10personas;
    private $mayor10personas;
    private $caracteristicaNegocioPlantaIndustrial;
    private $caracteristicaNegocioCasaNegocio;
    private $caracteristicaNegocioPuertaCalle;
    private $caracteristicaNegocioActividadAdicional;
    private $tipoVisita;
    private $numerovisita;
    private $nuevaDireccion;
    private $nuevoTelefono;
    private $direccionVisita2;
    
    public function getNuevaDireccion() {
        return $this->nuevaDireccion;
    }

    public function getNuevoTelefono() {
        return $this->nuevoTelefono;
    }

    public function getDireccionVisita2() {
        return $this->direccionVisita2;
    }

    public function setNuevaDireccion($nuevaDireccion) {
        $this->nuevaDireccion = $nuevaDireccion;
    }

    public function setNuevoTelefono($nuevoTelefono) {
        $this->nuevoTelefono = $nuevoTelefono;
    }

    public function setDireccionVisita2($direccionVisita2) {
        $this->direccionVisita2 = $direccionVisita2;
    }

        public function getTipoVisita() {
        return $this->tipoVisita;
    }

    public function getNumerovisita() {
        return $this->numerovisita;
    }

    public function setTipoVisita($tipoVisita) {
        $this->tipoVisita = $tipoVisita;
    }

    public function setNumerovisita($numerovisita) {
        $this->numerovisita = $numerovisita;
    }

        
    public function getCaracteristicaNegocioActividadAdicional() {
        return $this->caracteristicaNegocioActividadAdicional;
    }

    public function setCaracteristicaNegocioActividadAdicional($caracteristicaNegocioActividadAdicional) {
        $this->caracteristicaNegocioActividadAdicional = $caracteristicaNegocioActividadAdicional;
    }

        
    
    public function getCaracteristicaNegocioEnActividad() {
        return $this->caracteristicaNegocioEnActividad;
    }

    public function getCaracteristicaNegocioTieneExistencias() {
        return $this->caracteristicaNegocioTieneExistencias;
    }

    public function getCaracteristicaNegocioLaborArtesanal() {
        return $this->caracteristicaNegocioLaborArtesanal;
    }

    public function getCaracteristicaNegocioLocalPropio() {
        return $this->caracteristicaNegocioLocalPropio;
    }

    public function getCaracteristicaNegocioOficinaAdministrativa() {
        return $this->caracteristicaNegocioOficinaAdministrativa;
    }

    public function getMenorigual10personas() {
        return $this->menorigual10personas;
    }

    public function getMayor10personas() {
        return $this->mayor10personas;
    }

    public function getCaracteristicaNegocioPlantaIndustrial() {
        return $this->caracteristicaNegocioPlantaIndustrial;
    }

    public function getCaracteristicaNegocioCasaNegocio() {
        return $this->caracteristicaNegocioCasaNegocio;
    }

    public function getCaracteristicaNegocioPuertaCalle() {
        return $this->caracteristicaNegocioPuertaCalle;
    }

    public function setCaracteristicaNegocioEnActividad($caracteristicaNegocioEnActividad) {
        $this->caracteristicaNegocioEnActividad = $caracteristicaNegocioEnActividad;
    }

    public function setCaracteristicaNegocioTieneExistencias($caracteristicaNegocioTieneExistencias) {
        $this->caracteristicaNegocioTieneExistencias = $caracteristicaNegocioTieneExistencias;
    }

    public function setCaracteristicaNegocioLaborArtesanal($caracteristicaNegocioLaborArtesanal) {
        $this->caracteristicaNegocioLaborArtesanal = $caracteristicaNegocioLaborArtesanal;
    }

    public function setCaracteristicaNegocioLocalPropio($caracteristicaNegocioLocalPropio) {
        $this->caracteristicaNegocioLocalPropio = $caracteristicaNegocioLocalPropio;
    }

    public function setCaracteristicaNegocioOficinaAdministrativa($caracteristicaNegocioOficinaAdministrativa) {
        $this->caracteristicaNegocioOficinaAdministrativa = $caracteristicaNegocioOficinaAdministrativa;
    }

    public function setMenorigual10personas($menorigual10personas) {
        $this->menorigual10personas = $menorigual10personas;
    }

    public function setMayor10personas($mayor10personas) {
        $this->mayor10personas = $mayor10personas;
    }

    public function setCaracteristicaNegocioPlantaIndustrial($caracteristicaNegocioPlantaIndustrial) {
        $this->caracteristicaNegocioPlantaIndustrial = $caracteristicaNegocioPlantaIndustrial;
    }

    public function setCaracteristicaNegocioCasaNegocio($caracteristicaNegocioCasaNegocio) {
        $this->caracteristicaNegocioCasaNegocio = $caracteristicaNegocioCasaNegocio;
    }

    public function setCaracteristicaNegocioPuertaCalle($caracteristicaNegocioPuertaCalle) {
        $this->caracteristicaNegocioPuertaCalle = $caracteristicaNegocioPuertaCalle;
    }

        
    
    
    
    
    
    
    public function getIdObservacionEspecialistaNegocio() {
        return $this->idObservacionEspecialistaNegocio;
    }

    public function setIdObservacionEspecialistaNegocio($idObservacionEspecialistaNegocio) {
        $this->idObservacionEspecialistaNegocio = $idObservacionEspecialistaNegocio;
    }

        
    
    
    public function getIdClienteCartera() {
        return $this->idClienteCartera;
    }

    public function getIdtipoGestion() {
        return $this->idtipoGestion;
    }

    public function getIdDireccion() {
        return $this->idDireccion;
    }

    public function getIdNotificador() {
        return $this->idNotificador;
    }

    public function getIdCuenta() {
        return $this->idCuenta;
    }

    public function getIdUsuarioServicio() {
        return $this->idUsuarioServicio;
    }

    public function getFechaCP() {
        return $this->fechaCP;
    }

    public function getFechaVisita() {
        return $this->fechaVisita;
    }

    public function getHoraVisita() {
        return $this->horaVisita;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    public function getIdGiroNegocio() {
        return $this->idGiroNegocio;
    }

    public function getDetalleGiroExtraNegocio() {
        return $this->detalleGiroExtraNegocio;
    }

    public function getIdCaracteristicaNegocio() {
        return $this->idCaracteristicaNegocio;
    }

    public function getDetalleCaracteristicaNegocio() {
        return $this->detalleCaracteristicaNegocio;
    }

    public function getIdmotivoAtrasoNegocio() {
        return $this->idmotivoAtrasoNegocio;
    }

    public function getDetalleMotivoAtrasoNegocio() {
        return $this->detalleMotivoAtrasoNegocio;
    }

    public function getIdAfrontarPagoNegocio() {
        return $this->idAfrontarPagoNegocio;
    }

    public function getDetalleAfrontarPagoNegocio() {
        return $this->detalleAfrontarPagoNegocio;
    }

    public function getIdCuestionacobranzaNegocio() {
        return $this->idCuestionacobranzaNegocio;
    }

    public function setIdClienteCartera($idClienteCartera) {
        $this->idClienteCartera = $idClienteCartera;
    }

    public function setIdtipoGestion($idtipoGestion) {
        $this->idtipoGestion = $idtipoGestion;
    }

    public function setIdDireccion($idDireccion) {
        $this->idDireccion = $idDireccion;
    }

    public function setIdNotificador($idNotificador) {
        $this->idNotificador = $idNotificador;
    }

    public function setIdCuenta($idCuenta) {
        $this->idCuenta = $idCuenta;
    }

    public function setIdUsuarioServicio($idUsuarioServicio) {
        $this->idUsuarioServicio = $idUsuarioServicio;
    }

    public function setFechaCP($fechaCP) {
        $this->fechaCP = $fechaCP;
    }

    public function setFechaVisita($fechaVisita) {
        $this->fechaVisita = $fechaVisita;
    }

    public function setHoraVisita($horaVisita) {
        $this->horaVisita = $horaVisita;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }

    public function setIdGiroNegocio($idGiroNegocio) {
        $this->idGiroNegocio = $idGiroNegocio;
    }

    public function setDetalleGiroExtraNegocio($detalleGiroExtraNegocio) {
        $this->detalleGiroExtraNegocio = $detalleGiroExtraNegocio;
    }

    public function setIdCaracteristicaNegocio($idCaracteristicaNegocio) {
        $this->idCaracteristicaNegocio = $idCaracteristicaNegocio;
    }

    public function setDetalleCaracteristicaNegocio($detalleCaracteristicaNegocio) {
        $this->detalleCaracteristicaNegocio = $detalleCaracteristicaNegocio;
    }

    public function setIdmotivoAtrasoNegocio($idmotivoAtrasoNegocio) {
        $this->idmotivoAtrasoNegocio = $idmotivoAtrasoNegocio;
    }

    public function setDetalleMotivoAtrasoNegocio($detalleMotivoAtrasoNegocio) {
        $this->detalleMotivoAtrasoNegocio = $detalleMotivoAtrasoNegocio;
    }

    public function setIdAfrontarPagoNegocio($idAfrontarPagoNegocio) {
        $this->idAfrontarPagoNegocio = $idAfrontarPagoNegocio;
    }

    public function setDetalleAfrontarPagoNegocio($detalleAfrontarPagoNegocio) {
        $this->detalleAfrontarPagoNegocio = $detalleAfrontarPagoNegocio;
    }

    public function setIdCuestionacobranzaNegocio($idCuestionacobranzaNegocio) {
        $this->idCuestionacobranzaNegocio = $idCuestionacobranzaNegocio;
    }


    
    


} 