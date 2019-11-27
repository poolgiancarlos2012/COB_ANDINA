<?php
//include all DAO files
require_once('../sql/Connection....php');
require_once('../sql/ConnectionFactory....php');
require_once('../sql/ConnectionProperty....php');
require_once('../sql/QueryExecutor....php');
require_once('../sql/Transaction....php');
require_once('../sql/SqlQuery....php');
require_once('../core/ArrayList....php');
require_once('../dao/DAOFactory....php');
/*
	require_once('../dao/CaAlertaDAO....php');
	require_once('../dto/CaAlerta....php');
	require_once('../mysql/CaAlertaMySqlDAO....php');
	require_once('../mysql/ext/CaAlertaMySqlExtDAO....php');

	require_once('../dao/CaAyudaGestionDAO....php');
	require_once('../dto/CaAyudaGestion....php');
	require_once('../mysql/CaAyudaGestionMySqlDAO....php');
	require_once('../mysql/ext/CaAyudaGestionMySqlExtDAO....php');

	require_once('../dao/CaCabecerasDAO....php');
	require_once('../dto/CaCabecera....php');
	require_once('../mysql/CaCabecerasMySqlDAO....php');
	require_once('../mysql/ext/CaCabecerasMySqlExtDAO....php');
        
	require_once('../dao/CaCampaniaDAO....php');
	require_once('../dto/CaCampania....php');
	require_once('../mysql/CaCampaniaMySqlDAO....php');
	require_once('../mysql/ext/CaCampaniaMySqlExtDAO....php');
        
	require_once('../dao/CaCargaFinalDAO....php');
	require_once('../dto/CaCargaFinal....php');
	require_once('../mysql/CaCargaFinalMySqlDAO....php');
	require_once('../mysql/ext/CaCargaFinalMySqlExtDAO....php');

	require_once('../dao/CaCarteraDAO....php');
	require_once('../dto/CaCartera....php');
	require_once('../mysql/CaCarteraMySqlDAO....php');
	require_once('../mysql/ext/CaCarteraMySqlExtDAO....php');
        
	require_once('../dao/CaClaseDAO....php');
	require_once('../dto/CaClase....php');
	require_once('../mysql/CaClaseMySqlDAO....php');
	require_once('../mysql/ext/CaClaseMySqlExtDAO....php');
        
	require_once('../dao/CaClaseFinalDAO....php');
	require_once('../dto/CaClaseFinal....php');
	require_once('../mysql/CaClaseFinalMySqlDAO....php');
	require_once('../mysql/ext/CaClaseFinalMySqlExtDAO....php');

	require_once('../dao/CaClienteDAO....php');
	require_once('../dto/CaCliente....php');
	require_once('../mysql/CaClienteMySqlDAO....php');
	require_once('../mysql/ext/CaClienteMySqlExtDAO....php');

	require_once('../dao/CaClienteCarteraDAO....php');
	require_once('../dto/CaClienteCartera....php');
	require_once('../mysql/CaClienteCarteraMySqlDAO....php');
	require_once('../mysql/ext/CaClienteCarteraMySqlExtDAO....php');

	require_once('../dao/CaCompromisoPagoDAO....php');
	require_once('../dto/CaCompromisoPago....php');
	require_once('../mysql/CaCompromisoPagoMySqlDAO....php');
	require_once('../mysql/ext/CaCompromisoPagoMySqlExtDAO....php');

	require_once('../dao/CaCuentaDAO....php');
	require_once('../dto/CaCuenta....php');
	require_once('../mysql/CaCuentaMySqlDAO....php');
	require_once('../mysql/ext/CaCuentaMySqlExtDAO....php');

	require_once('../dao/CaDatosAdicionalesClienteDAO....php');
	require_once('../dto/CaDatosAdicionalesCliente....php');
	require_once('../mysql/CaDatosAdicionalesClienteMySqlDAO....php');
	require_once('../mysql/ext/CaDatosAdicionalesClienteMySqlExtDAO....php');

	require_once('../dao/CaDatosAdicionalesCuentaDAO....php');
	require_once('../dto/CaDatosAdicionalesCuenta....php');
	require_once('../mysql/CaDatosAdicionalesCuentaMySqlDAO....php');
	require_once('../mysql/ext/CaDatosAdicionalesCuentaMySqlExtDAO....php');

	require_once('../dao/CaDatosAdicionalesDetalleCuentaDAO....php');
	require_once('../dto/CaDatosAdicionalesDetalleCuenta....php');
	require_once('../mysql/CaDatosAdicionalesDetalleCuentaMySqlDAO....php');
	require_once('../mysql/ext/CaDatosAdicionalesDetalleCuentaMySqlExtDAO....php');

	require_once('../dao/CaDetalleCuentaDAO....php');
	require_once('../dto/CaDetalleCuenta....php');
	require_once('../mysql/CaDetalleCuentaMySqlDAO....php');
	require_once('../mysql/ext/CaDetalleCuentaMySqlExtDAO....php');

	require_once('../dao/CaDireccionDAO....php');
	require_once('../dto/CaDireccion....php');
	require_once('../mysql/CaDireccionMySqlDAO....php');
	require_once('../mysql/ext/CaDireccionMySqlExtDAO....php');

	require_once('../dao/CaFiltrosDAO....php');
	require_once('../dto/CaFiltro....php');
	require_once('../mysql/CaFiltrosMySqlDAO....php');
	require_once('../mysql/ext/CaFiltrosMySqlExtDAO....php');

	require_once('../dao/CaFinalDAO....php');
	require_once('../dto/CaFinal....php');
	require_once('../mysql/CaFinalMySqlDAO....php');
	require_once('../mysql/ext/CaFinalMySqlExtDAO....php');

	require_once('../dao/CaFinalServicioDAO....php');
	require_once('../dto/CaFinalServicio....php');
	require_once('../mysql/CaFinalServicioMySqlDAO....php');
	require_once('../mysql/ext/CaFinalServicioMySqlExtDAO....php');

	require_once('../dao/CaNivelDAO....php');
	require_once('../dto/CaNivel....php');
	require_once('../mysql/CaNivelMySqlDAO....php');
	require_once('../mysql/ext/CaNivelMySqlExtDAO....php');

	require_once('../dao/CaNotaDAO....php');
	require_once('../dto/CaNota....php');
	require_once('../mysql/CaNotaMySqlDAO....php');
	require_once('../mysql/ext/CaNotaMySqlExtDAO....php');

	require_once('../dao/CaOrigenDAO....php');
	require_once('../dto/CaOrigen....php');
	require_once('../mysql/CaOrigenMySqlDAO....php');
	require_once('../mysql/ext/CaOrigenMySqlExtDAO....php');

	require_once('../dao/CaPagoDAO....php');
	require_once('../dto/CaPago....php');
	require_once('../mysql/CaPagoMySqlDAO....php');
	require_once('../mysql/ext/CaPagoMySqlExtDAO....php');

	require_once('../dao/CaPrivilegioDAO....php');
	require_once('../dto/CaPrivilegio....php');
	require_once('../mysql/CaPrivilegioMySqlDAO....php');
	require_once('../mysql/ext/CaPrivilegioMySqlExtDAO....php');

	require_once('../dao/CaReferenciaClienteDAO....php');
	require_once('../dto/CaReferenciaCliente....php');
	require_once('../mysql/CaReferenciaClienteMySqlDAO....php');
	require_once('../mysql/ext/CaReferenciaClienteMySqlExtDAO....php');

	require_once('../dao/CaServicioDAO....php');
	require_once('../dto/CaServicio....php');
	require_once('../mysql/CaServicioMySqlDAO....php');
	require_once('../mysql/ext/CaServicioMySqlExtDAO....php');

	require_once('../dao/CaTelefonoDAO....php');
	require_once('../dto/CaTelefono....php');
	require_once('../mysql/CaTelefonoMySqlDAO....php');
	require_once('../mysql/ext/CaTelefonoMySqlExtDAO....php');

	require_once('../dao/CaTipoAyudaGestionDAO....php');
	require_once('../dto/CaTipoAyudaGestion....php');
	require_once('../mysql/CaTipoAyudaGestionMySqlDAO....php');
	require_once('../mysql/ext/CaTipoAyudaGestionMySqlExtDAO....php');

	require_once('../dao/CaTipoDatosAdicionalesDAO....php');
	require_once('../dto/CaTipoDatosAdicionale....php');
	require_once('../mysql/CaTipoDatosAdicionalesMySqlDAO....php');
	require_once('../mysql/ext/CaTipoDatosAdicionalesMySqlExtDAO....php');

	require_once('../dao/CaTipoFiltroDAO....php');
	require_once('../dto/CaTipoFiltro....php');
	require_once('../mysql/CaTipoFiltroMySqlDAO....php');
	require_once('../mysql/ext/CaTipoFiltroMySqlExtDAO....php');

	require_once('../dao/CaTipoFinalDAO....php');
	require_once('../dto/CaTipoFinal....php');
	require_once('../mysql/CaTipoFinalMySqlDAO....php');
	require_once('../mysql/ext/CaTipoFinalMySqlExtDAO....php');

	require_once('../dao/CaTipoGestionDAO....php');
	require_once('../dto/CaTipoGestion....php');
	require_once('../mysql/CaTipoGestionMySqlDAO....php');
	require_once('../mysql/ext/CaTipoGestionMySqlExtDAO....php');

	require_once('../dao/CaTipoReferenciaDAO....php');
	require_once('../dto/CaTipoReferencia....php');
	require_once('../mysql/CaTipoReferenciaMySqlDAO....php');
	require_once('../mysql/ext/CaTipoReferenciaMySqlExtDAO....php');

	require_once('../dao/CaTipoTelefonoDAO....php');
	require_once('../dto/CaTipoTelefono....php');
	require_once('../mysql/CaTipoTelefonoMySqlDAO....php');
	require_once('../mysql/ext/CaTipoTelefonoMySqlExtDAO....php');

	require_once('../dao/CaTipoUsuarioDAO....php');
	require_once('../dto/CaTipoUsuario....php');
	require_once('../mysql/CaTipoUsuarioMySqlDAO....php');
	require_once('../mysql/ext/CaTipoUsuarioMySqlExtDAO....php');

	require_once('../dao/CaTransaccionDAO....php');
	require_once('../dto/CaTransaccion....php');
	require_once('../mysql/CaTransaccionMySqlDAO....php');
	require_once('../mysql/ext/CaTransaccionMySqlExtDAO....php');

	require_once('../dao/CaUsuarioDAO....php');
	require_once('../dto/CaUsuario....php');
	require_once('../mysql/CaUsuarioMySqlDAO....php');
	require_once('../mysql/ext/CaUsuarioMySqlExtDAO....php');
        
	require_once('../dao/CaUsuarioServicioDAO....php');
	require_once('../dto/CaUsuarioServicio....php');
	require_once('../mysql/CaUsuarioServicioMySqlDAO....php');
	require_once('../mysql/ext/CaUsuarioServicioMySqlExtDAO....php');
*/
?>