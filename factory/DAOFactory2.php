<?php

class  DAOFactory {

    public static function getDAOServicio ( ) {
        return new MYSQLServicioDAO ;
    }
    public static function getDAOUsuario ( ) {
        return new MYSQLUsuarioDAO ;
    }
    public static function getDAOCampania ( ) {
        return new MYSQLCampaniaDAO ;
    }
    public static function getDAOPermisosDetalle() {
        return new MYSQLPermisosDetalleDAO();
    }
    public static function getDAONivelesPermisos() {
        return new MYSQLNivelesPermisos();
    }
    public static function getDAOMenu() {
        return new MYSQLMenuDAO();
    }
    public static function getDAOUrl() {
        return new MYSQLUrlDAO();
    }
    public static function getDAOUsuarioServicio ( ) {
        return new MYSQLUsuarioServicioDAO ;
    }
	public static function getDAOPrivilegio ( ) {
		return new MYSQLPrivilegioDAO ;
	}
	public static function getDAODatosAdicionalesCliente ( ) {
		return new MYSQLDatosAdicionalesClienteDAO ;
	}
	public static function getDAODatosAdicionalesDetalleCuenta ( ) {
		return new MYSQLDatosAdicionalesDetalleCuentaDAO ;
	}
	public static function getDAODatosAdicionalesCuenta ( ) {
		return new MYSQLDatosAdicionalesCuentaDAO ;
	}
	public static function getDAOCliente ( ) {
		return new MYSQLClienteDAO ;
	}
	public static function getDAODetalleCuenta ( ) {
		return new MYSQLDetalleCuentaDAO ;
	}
	public static function getDAOAyudaGestion ( ) {
		return new MYSQLAyudaGestionDAO ;
	}
    public static function getDAOTipoUsuario ( ) {
        return new MYSQLTipoUsuarioDAO ;
    }
    public static function getDAOClienteCartera ( ) {
        return new MYSQLClienteCarteraDAO ;
    }
    public static function getDAOCargaCartera(){
        return new MYSQLCargaCarteraDAO();
    }
	public static function getDAOCargaFinal ( ) {
		return new MYSQLCargaFinalDAO ;
	}
	public static function getDAOClaseFinal ( ) {
		return new MYSQLClaseFinalDAO ;
	}
	public static function getDAOTipoGestion ( ) {
		return new MYSQLTipoGestionDAO ;
	}
	public static function getDAONivel ( ) {
		return new MYSQLNivelDAO ;
	}
	public static function getDAOTipoFinal ( ) {
		return new MYSQLTipoFinalDAO ;
	}
	public static function getDAOAlerta ( ) {
		return new MYSQLAlertaDAO ;
	}
	public static function getDAOFinalServicio ( ) {
		return new MYSQLFinalServicioDAO ;
	}
	public static function getDAOFinal ( ) {
		return new MYSQLFinalDAO ;
	}
	public static function getDAOTransaccion ( ) {
		return new MYSQLTransaccionDAO ;
	}
	public static function getDAOOrigen ( ) {
		return new MYSQLOrigenDAO ;
	}
	public static function getDAOTipoTelefono ( ) {
		return new MYSQLTipoTelefonoDAO ;
	}
	public static function getDAOTipoReferencia ( ) {
		return new MYSQLTipoReferenciaDAO ;
	}
	public static function getDAOJqgrid ( ) {
		return new MYSQLJqgridDAO ;
	}
	public static function getDAOReferenciaCliente ( ) {
		return new MYSQLReferenciaClienteDAO ;
	}
	public static function getDAOFiltros ( ) {
		return new MYSQLFiltrosDAO ;
	}
	public static function getDAOTipoAyudaGestion ( ) {
		return new MYSQLTipoAyudaGestionDAO ;
	}
	public static function getDAODireccion ( ) {
		return new MYSQLDireccionDAO ;
	}
	public static function getDAOTelefono ( ) {
		return new MYSQLTelefonoDAO ;
	}
	public static function getDAONota ( ) {
		return new MYSQLNotaDAO ;
	}
	public static function getDAOEstadoTransaccion ( ) {
		return new MYSQLEstadoTransaccionDAO ;	
	}
	public static function getDAOPesoTransaccion ( ) {
		return new MYSQLPesoTransaccionDAO ;	
	}
	public static function getDAOEvento ( ) {
		return new MYSQLEventoDAO ;	
	}
	public static function getDAOTarea ( ) {
		return new MYSQLTareaDAO ;
	}
	public static function getDAOCalendar ( ) {
		return new MYSQLCalendarDAO ;
	}
	public static function getDAOCartera ( ) {
		return new MYSQLCarteraDAO ;
	}
	public static function getDAOProvincia ( ) {
		return new MYSQLProvinciaDAO ;
	}
	public static function getDAOLineaTelefono ( ) {
		return new MYSQLLineaTelefonoDAO ; 
	}
	public static function getDAOAyudaGestionUsuario ( ) {
		return new MYSQLAyudaGestionUsuarioDAO ; 
	}
	public static function getDAOTipoTransaccion ( ) {
		return new MYSQLTipoTransaccionDAO ;
	}
	public static function getDAOTramo ( ) {
		return new MYSQLTramoDAO ;
	}
	public static function getDAOConsulta ( ) {
		return new MYSQLConsultaDAO ;
	}
	public static function getDAODetalleConsulta ( ) {
		return new MYSQLDetalleConsultaDAO ;
	}
	public static function getDAOProcedure ( ) {
		return new MYSQLProcedureDAO ;
	}
	public static function getDAOTipoEstado ( ) {
		return new MYSQLTipoEstadoDAO ;
	}
	public static function getDAOEstado ( ) {
		return new MYSQLEstadoDAO ;
	}
	public static function getDAOCuenta ( ) {
		return new MYSQLCuentaDAO ;
	}
	public static function getDAOEtiqueta ( ) {
		return new MYSQLEtiquetaDAO ;
	}
	public static function getDAONotificador ( ) {
		return new MYSQLNotificadorDAO ;
	}
	public static function getDAOCarteraPago ( ) {
		return new MYSQLCarteraPagoDAO ;
	}
	public static function getDAOObservacion ( ) {
		return new MYSQLObservacionDAO ;
	}
	public static function getDAOCorreo ( ) {
		return new MYSQLCorreoDAO ;
	}
	public static function getDAOHorarioAtencion ( ) {
		return new MYSQLHorarioAtencionDAO ;
	}
	public static function getDAOContacto ( ) {
		return new MYSQLContactoDAO ; 
	}
	public static function getDAOMotivoNoPago ( ) {
		return new MYSQLMotivoNoPagoDAO ;
	}
	public static function getDAONotice ( ) {
		return new MYSQLNoticeDAO ;
	}
	public static function getDAORanking ( ) {
		return new MYSQLRankingDAO ;
	}
	public static function getDAOZona ( ) {
		return new MYSQLZonaDAO ;
	}
	public static function getDAOPago ( ) {
		return new MYSQLPagoDAO ;
	}
	public static function getCabecerasCarteraDAO ( ) {
		return new MYSQLCabecerasCarteraDAO ;
	}
	
}
?>