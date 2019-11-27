<?php

class  DAOFactory {
	public static function getDAONeotel(){
        return new WSNeotelDAO ;
    }
    public static function getDAOServicio ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLServicioDAO ;
			break;
			case 'maria':
				$rs = new MARIAServicioDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOServicioDAO ;
			break;
		endswitch;
        return $rs ;
    }
    public static function getDAOUsuario ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLUsuarioDAO ;
			break;
			case 'maria':
				$rs = new MARIAUsuarioDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOUsuarioDAO ;
			break;
		endswitch;
        return $rs ;
    }
    public static function getDAOCampania ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLCampaniaDAO ;
			break;
			case 'maria':
				$rs = new MARIACampaniaDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOCampaniaDAO ;
			break;
		endswitch;
        return $rs ;
    }
    public static function getDAOPermisosDetalle( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLPermisosDetalleDAO();
			break;
			case 'maria':
				$rs = new MARIAPermisosDetalleDAO();
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOPermisosDetalleDAO();
			break;
		endswitch;
        return $rs;
    }
    public static function getDAONivelesPermisos( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLNivelesPermisos();
			break;
			case 'maria':
				$rs = new MARIANivelesPermisos();
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDONivelesPermisos();
			break;
		endswitch;
        return $rs;
    }
    public static function getDAOMenu( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLMenuDAO();
			break;
			case 'maria':
				$rs = new MARIAMenuDAO();
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOMenuDAO();
			break;
		endswitch;
        return $rs;
    }
    public static function getDAOUrl( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLUrlDAO();
			break;
			case 'maria':
				$rs = new MARIAUrlDAO();
			break;
			case 'pgsql_pdo':
				$rs = new MYSQLUrlDAO();
			break;
		endswitch;
        return $rs ;
    }
    public static function getDAOUsuarioServicio ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLUsuarioServicioDAO ;
			break;
			case 'maria':
				$rs = new MARIAUsuarioServicioDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOUsuarioServicioDAO ;
			break;
		endswitch;
        return $rs ;
    }
	public static function getDAOPrivilegio ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLPrivilegioDAO ;
			break;
			case 'maria':
				$rs = new MARIAPrivilegioDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOPrivilegioDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAODatosAdicionalesCliente ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLDatosAdicionalesClienteDAO ;
			break;
			case 'maria':
				$rs = new MARIADatosAdicionalesClienteDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDODatosAdicionalesClienteDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAODatosAdicionalesDetalleCuenta ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLDatosAdicionalesDetalleCuentaDAO ;
			break;
			case 'maria':
				$rs = new MARIADatosAdicionalesDetalleCuentaDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDODatosAdicionalesDetalleCuentaDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAODatosAdicionalesCuenta ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLDatosAdicionalesCuentaDAO ;
			break;
			case 'maria':
				$rs = new MARIADatosAdicionalesCuentaDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDODatosAdicionalesCuentaDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOCliente ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLClienteDAO ;
			break;
			case 'maria':
				$rs = new MARIAClienteDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOClienteDAO ;
			break;
		endswitch;
		return $rs;
	}
	public static function getDAODetalleCuenta ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLDetalleCuentaDAO ;
			break;
			case 'maria':
				$rs = new MARIADetalleCuentaDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDODetalleCuentaDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOAyudaGestion ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLAyudaGestionDAO ;
			break;
			case 'maria':
				$rs = new MARIAAyudaGestionDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOAyudaGestionDAO ;
			break;
		endswitch;
		return $rs ;
	}
    public static function getDAOTipoUsuario ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLTipoUsuarioDAO ;
			break;
			case 'maria':
				$rs = new MARIATipoUsuarioDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOTipoUsuarioDAO ;
			break;
		endswitch;
        return $rs ;
    }
    public static function getDAOClienteCartera ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLClienteCarteraDAO ;
			break;
			case 'maria':
				$rs = new MARIAClienteCarteraDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOClienteCarteraDAO ;
			break;
		endswitch;
        return $rs ;
    }
    public static function getDAOCargaCartera( $tipo ){
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLCargaCarteraDAO();
			break;
			case 'maria':
				$rs = new MARIACargaCarteraDAO();
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOCargaCarteraDAO();
			break;
		endswitch;
        return $rs ;
    }
	public static function getDAOCargaFinal ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLCargaFinalDAO ;
			break;
			case 'maria':
				$rs = new MARIACargaFinalDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOCargaFinalDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOClaseFinal ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLClaseFinalDAO ;
			break;
			case 'maria':
				$rs = new MARIAClaseFinalDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOClaseFinalDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOTipoGestion ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLTipoGestionDAO ;
			break;
			case 'maria':
				$rs = new MARIATipoGestionDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOTipoGestionDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAONivel ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLNivelDAO ;
			break;
			case 'maria':
				$rs = new MARIANivelDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDONivelDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOTipoFinal ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLTipoFinalDAO ;
			break;
			case 'maria':
				$rs = new MARIATipoFinalDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOTipoFinalDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOAlerta ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLAlertaDAO ;
			break;
			case 'maria':
				$rs = new MARIAAlertaDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOAlertaDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOFinalServicio ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLFinalServicioDAO ;
			break;
			case 'maria':
				$rs = new MARIAFinalServicioDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOFinalServicioDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOFinal ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLFinalDAO ;
			break;
			case 'maria':
				$rs = new MARIAFinalDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOFinalDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOTransaccion ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLTransaccionDAO ;
			break;
			case 'maria':
				$rs = new MARIATransaccionDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOTransaccionDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOOrigen ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLOrigenDAO ;
			break;
			case 'maria':
				$rs = new MARIAOrigenDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOOrigenDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOTipoTelefono ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLTipoTelefonoDAO ;
			break;
			case 'maria':
				$rs = new MARIATipoTelefonoDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOTipoTelefonoDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOTipoReferencia ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLTipoReferenciaDAO ;
			break;
			case 'maria':
				$rs = new MARIATipoReferenciaDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOTipoReferenciaDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOJqgrid ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLJqgridDAO ;
			break;
			case 'maria':
				$rs = new MARIAJqgridDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOJqgridDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOReferenciaCliente ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLReferenciaClienteDAO ;
			break;
			case 'maria':
				$rs = new MARIAReferenciaClienteDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOReferenciaClienteDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOFiltros ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLFiltrosDAO ;
			break;
			case 'maria':
				$rs = new MARIAFiltrosDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOFiltrosDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOTipoAyudaGestion ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLTipoAyudaGestionDAO ;
			break;
			case 'maria':
				$rs = new MARIATipoAyudaGestionDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOTipoAyudaGestionDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAODireccion ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLDireccionDAO ;
			break;
			case 'maria':
				$rs = new MARIADireccionDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDODireccionDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOTelefono ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLTelefonoDAO ;
			break;
			case 'maria':
				$rs = new MARIATelefonoDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOTelefonoDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAONota ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLNotaDAO ;
			break;
			case 'maria':
				$rs = new MARIANotaDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDONotaDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOEstadoTransaccion ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLEstadoTransaccionDAO ;	
			break;
			case 'maria':
				$rs = new MARIAEstadoTransaccionDAO ;	
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOEstadoTransaccionDAO ;	
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOPesoTransaccion ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLPesoTransaccionDAO ;	
			break;
			case 'maria':
				$rs = new MARIAPesoTransaccionDAO ;	
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOPesoTransaccionDAO ;	
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOEvento ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLEventoDAO ;	
			break;
			case 'maria':
				$rs = new MARIAEventoDAO ;	
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOEventoDAO ;	
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOTarea ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLTareaDAO ;
			break;
			case 'maria':
				$rs = new MARIATareaDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOTareaDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOCalendar ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLCalendarDAO ;
			break;
			case 'maria':
				$rs = new MARIACalendarDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOCalendarDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOCartera ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLCarteraDAO ;
			break;
			case 'maria':
				$rs = new MARIACarteraDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOCarteraDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOProvincia ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLProvinciaDAO ;
			break;
			case 'maria':
				$rs = new MARIAProvinciaDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOProvinciaDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOLineaTelefono ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLLineaTelefonoDAO ; 
			break;
			case 'maria':
				$rs = new MARIALineaTelefonoDAO ; 
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOLineaTelefonoDAO ; 
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOAyudaGestionUsuario ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLAyudaGestionUsuarioDAO ; 
			break;
			case 'maria':
				$rs = new MARIAAyudaGestionUsuarioDAO ; 
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOAyudaGestionUsuarioDAO ; 
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOTipoTransaccion ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLTipoTransaccionDAO ;
			break;
			case 'maria':
				$rs = new MARIATipoTransaccionDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOTipoTransaccionDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOTramo ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLTramoDAO ;
			break;
			case 'maria':
				$rs = new MARIATramoDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOTramoDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOConsulta ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLConsultaDAO ;
			break;
			case 'maria':
				$rs = new MARIAConsultaDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOConsultaDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAODetalleConsulta ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLDetalleConsultaDAO ;
			break;
			case 'maria':
				$rs = new MARIADetalleConsultaDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDODetalleConsultaDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOProcedure ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLProcedureDAO ;
			break;
			case 'maria':
				$rs = new MARIAProcedureDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOProcedureDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOTipoEstado ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLTipoEstadoDAO ;
			break;
			case 'maria':
				$rs = new MARIATipoEstadoDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOTipoEstadoDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOEstado ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLEstadoDAO ;
			break;
			case 'maria':
				$rs = new MARIAEstadoDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOEstadoDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOCuenta ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLCuentaDAO ;
			break;
			case 'maria':
				$rs = new MARIACuentaDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOCuentaDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOEtiqueta ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLEtiquetaDAO ;
			break;
			case 'maria':
				$rs = new MARIAEtiquetaDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOEtiquetaDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAONotificador ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLNotificadorDAO ;
			break;
			case 'maria':
				$rs = new MARIANotificadorDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDONotificadorDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOCarteraPago ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLCarteraPagoDAO ;
			break;
			case 'maria':
				$rs = new MARIACarteraPagoDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOCarteraPagoDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOObservacion ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLObservacionDAO ;
			break;
			case 'maria':
				$rs = new MARIAObservacionDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOObservacionDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOCorreo ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLCorreoDAO ;
			break;
			case 'maria':
				$rs = new MARIACorreoDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOCorreoDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOHorarioAtencion ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLHorarioAtencionDAO ;
			break;
			case 'maria':
				$rs = new MARIAHorarioAtencionDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOHorarioAtencionDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOContacto ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLContactoDAO ; 
			break;
			case 'maria':
				$rs = new MARIAContactoDAO ; 
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOContactoDAO ; 
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOMotivoNoPago ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLMotivoNoPagoDAO ;
			break;
			case 'maria':
				$rs = new MARIAMotivoNoPagoDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOMotivoNoPagoDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAONotice ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLNoticeDAO ;
			break;
			case 'maria':
				$rs = new MARIANoticeDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDONoticeDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAORanking ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLRankingDAO ;
			break;
			case 'maria':
				$rs = new MARIARankingDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDORankingDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOZona ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLZonaDAO ;
			break;
			case 'maria':
				$rs = new MARIAZonaDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOZonaDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getDAOPago ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLPagoDAO ;
			break;
			case 'maria':
				$rs = new MARIAPagoDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOPagoDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getCabecerasCarteraDAO ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLCabecerasCarteraDAO ;
			break;
			case 'maria':
				$rs = new MARIACabecerasCarteraDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOCabecerasCarteraDAO ;
			break;
		endswitch;
		return $rs ;
	}
	public static function getFacturaDigitalDAO ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLFacturaDigitalDAO ;
			break;
			case 'maria':
				$rs = new MARIAFacturaDigitalDAO ;
			break;
			case 'pgsql_pdo':
				$rs = new PGSQL_PDOFacturaDigitalDAO ;
			break;
		endswitch;
		return $rs ;
	}
	
	public static function getCarteraRP3DAO ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLCarteraRP3DAO ;
			break;
		endswitch;
		return $rs ;
	}
	
	public static function getPagoRP3DAO ( $tipo ) {
		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLPagoRP3DAO ;
			break;
		endswitch;
		return $rs ;
	}
	
	public static function getUbigeoDAO ( $tipo ) {
		
		$rs = NULL ;
		switch ($tipo) :
			case 'maria':
				$rs = new MARIAUbigeoDAO ;
			break;
		endswitch;
		return $rs ;
		
	}
	
	public static function getParentescoDAO ( $tipo ) {

		$rs = NULL ;
		switch ($tipo) :
			case 'maria':
				$rs = new MARIAParentescoDAO ;
			break;
		endswitch;
		return $rs ;

	}
	
	public static function getRefinanciamientoDAO ( $tipo ) {

		$rs = NULL ;
		switch ($tipo) :
			case 'maria':
				$rs = new MARIARefinanciamientoDAO ;
			break;
		endswitch;
		return $rs ;

	}
	
	public static function getRespuestaRP3DAO ( $tipo ) {

		$rs = NULL ;
		switch ($tipo) :
			case 'mysql':
				$rs = new MYSQLRespuestaRP3DAO ;
			break;
		endswitch;
		return $rs ;

	}
        
        public static function getDAOGestionComercial ( $tipo ) {
            $rs = NULL ;
		switch ($tipo) :
			case 'maria':
				$rs = new MARIAGestionComercialDAO;
			break;
		endswitch;
		return $rs ;
        }
	
}
?>
