<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sincronizar extends MY_Controller
{
    public $Sincronizar_model;
	public function __construct()
	{	
		parent::__construct();
        //$this->load->model("Almacen_model");
        $this->load->model('siat/Sincronizar_model');

	}
    public function sincronizarCatalogos()
    {
        $this->accesoCheck(71);
		$this->titles('SincronizarDatos','Sincronizar Catálogos','');
		$this->datos['foot_script'][]=base_url('assets/hergo/siat/sincronizar/sincronizarCatalogos.js') .'?'.rand();
		$this->setView('siat/sincronizar/sincronizarCatalogos');
    }
    public function actividades()
    {
        $this->accesoCheck(71);
		$this->titles('SincronizarActividades','Sincronizar Actividades Económicas','');
		$this->datos['foot_script'][]=base_url('assets/hergo/siat/sincronizar/actividades.js') .'?'.rand();
		$this->setView('siat/sincronizar/actividades');
    }
    public function sincronizacionesSiat(string $tabla)
    {
        $this->Sincronizar_model->sincronizacionesSiat($tabla);
    }
    public function sincronizarActividades()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getActividades();
        $res = $siat == $local;
        if ($res) {
            $this->sincronizacionesSiat('1');
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeActividades($siat);
            $local = $this->Sincronizar_model->getActividades();
            $res = $siat == $local;
            $this->sincronizacionesSiat('1');
            echo json_encode($res);
        }
    }
    public function actividadesDocumentoSector()
    {
        $this->accesoCheck(71);
		$this->titles('ActividadesDocumentoSector','Sincronizar Actividades Documento Sector','');
		$this->datos['foot_script'][]=base_url('assets/hergo/siat/sincronizar/actividadesDocumentoSector.js') .'?'.rand();
		$this->setView('siat/sincronizar/actividadesDocumentoSector');
    }
    public function sincronizarParametros()
    {
        $siat = $this->input->post('dataSiat');
        $get = $this->input->post('get');
        $store = $this->input->post('store');
        $local = $this->Sincronizar_model->$get();
        $res = $siat == $local;
        
        if ($res) {
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->$store($siat);
            $local = $this->Sincronizar_model->$get();
            $res = $siat == $local;
            echo json_encode($res);
        }
    }
    public function sincronizarActividadesDocumentoSector()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getActividadesDocumentoSector();
        $res = $siat == $local;
        if ($res) {
            $this->sincronizacionesSiat('2');
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeActividadesDocumentoSector($siat);
            $local = $this->Sincronizar_model->getActividadesDocumentoSector();
            $res = $siat == $local;
            $this->sincronizacionesSiat('2');
            echo json_encode($res);
        }
    }
    public function listaLeyendasFactura()
    {
        $this->accesoCheck(71);
		$this->titles('LeyendasFactura','Sincronizar Lista de Leyendas Factura','');
		$this->datos['foot_script'][]=base_url('assets/hergo/siat/sincronizar/listaLeyendasFactura.js') .'?'.rand();
		$this->setView('siat/sincronizar/listaLeyendasFactura');
    }
    public function sincronizarListaLeyendasFactura()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaLeyendasFactura();
        $res = $siat === $local;
        if ($res) {
            $this->sincronizacionesSiat('3');
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeListaLeyendasFactura($siat);
            $local = $this->Sincronizar_model->getListaLeyendasFactura();
            $res = $siat === $local;
            $this->sincronizacionesSiat('3');
            echo json_encode($res);
        }
    }
    public function listaMensajesServicios()
    {
        $this->accesoCheck(71);
		$this->titles('LeyendasFactura','Sincronizar Lista Mensajes de Servicio','');
		$this->datos['foot_script'][]=base_url('assets/hergo/siat/sincronizar/listaMensajesServicios.js') .'?'.rand();
		$this->setView('siat/sincronizar/listaMensajesServicios');
    }
    public function sincronizarMensajesServicios()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaMensajesServicios();
        $res = $siat == $local;
        if ($res) {
            $this->sincronizacionesSiat('4');
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeMensajesServicios($siat);
            $local = $this->Sincronizar_model->getListaMensajesServicios();
            $res = $siat == $local;
            $this->sincronizacionesSiat('4');
            echo json_encode($res);
        }
    }
    public function listaProductosServicios()
    {
        $this->accesoCheck(71);
		$this->titles('SincroProductosServicios','Sincronizar Lista Productos y Servicios','');
		$this->datos['foot_script'][]=base_url('assets/hergo/siat/sincronizar/listaProductosServicios.js') .'?'.rand();
		$this->setView('siat/sincronizar/listaProductosServicios');
    }
    public function sincronizarListaProductosServicios()
    {
        $siat =  $this->input->post('dataSiat');
        $siat = json_decode($siat,TRUE);
        $local = $this->Sincronizar_model->getlistaProductosServicios();
        $res = $siat ==  $local;
        if ($res) {
            $this->sincronizacionesSiat('5');
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeListaProductosServicios($siat);
            $local = $this->Sincronizar_model->getlistaProductosServicios();
            $res = $siat ==  $local;
            $this->sincronizacionesSiat('5');
            echo json_encode($res);
        }
    }
    public function parametricas()
    {
        $this->accesoCheck(71);
		$this->titles('SincroParametricas','Sincronizar Listado Paramétricas','');
		$this->datos['foot_script'][]=base_url('assets/hergo/siat/sincronizar/parametricas.js') .'?'.rand();
		$this->setView('siat/sincronizar/parametricas');
    }
    public function sincronizarParametricas()
    {
        $siat = $this->input->post('dataSiat');
        $table = $this->input->post('table');

        $local = $this->Sincronizar_model->getListaParametricas($table);
        $res = $siat == $local;
        if ($res) {
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeParametricas($siat, $table);
            $local = $this->Sincronizar_model->getListaParametricas($table);
            $res = $siat == $local;
            echo json_encode($res);
        }
    }
    public function sincronizarParametricaEventosSignificativos()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_eventos_significativos');
        $res = $siat == $local;
        if ($res) {
            $this->sincronizacionesSiat('13');
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_eventos_significativos');
            $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_eventos_significativos');
            $res = $siat == $local;
            $this->sincronizacionesSiat('13');
            echo json_encode($res);
        }
    }
    public function sincronizarParametricaMotivoAnulacion()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_motivo_anulacion');
        $res = $siat == $local;
        if ($res) {
            $this->sincronizacionesSiat('6');
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_motivo_anulacion');
            $this->sincronizacionesSiat('6');
            echo json_encode($res);
        }
    }
    public function sincronizarParametricaPaisOrigen()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_paises');
        $res = $siat == $local;
        if ($res) {
            $this->sincronizacionesSiat('16');
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_paises');
            $this->sincronizacionesSiat('16');
            echo json_encode($res);
        }
    }
    public function sincronizarParametricaTipoDocumentoIdentidad()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_tipo_documento_identidad');
        $res = $siat == $local;
        if ($res) {
            $this->sincronizacionesSiat('8');
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_tipo_documento_identidad');
            $this->sincronizacionesSiat('8');
            echo json_encode($res);
        }
    }
    public function sincronizarParametricaTipoDocumentoSector()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_tipo_doc_sector');
        $res = $siat == $local;
        if ($res) {
            $this->sincronizacionesSiat('10');
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_tipo_doc_sector');
            $this->sincronizacionesSiat('10');
            echo json_encode($res);
        }
    }
    public function sincronizarParametricaTipoEmision()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_tipo_emision');
        $res = $siat == $local;
        if ($res) {
            $this->sincronizacionesSiat('15');
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_tipo_emision');
            $this->sincronizacionesSiat('15');
            echo json_encode($res);
        }
    }
    public function sincronizarParametricaTipoHabitacion()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_tipo_habitacion');
        $res = $siat == $local;
        if ($res) {
            $this->sincronizacionesSiat('7');
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_tipo_habitacion');
            $this->sincronizacionesSiat('7');
            echo json_encode($res);
        }
    }
    public function sincronizarParametricaTipoMetodoPago()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_tipo_metodo_pago');
        $res = $siat == $local;
        if ($res) {
            $this->sincronizacionesSiat('12');
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_tipo_metodo_pago');
            $this->sincronizacionesSiat('12');
            echo json_encode($res);
        }
    }
    public function sincronizarParametricaTipoMoneda()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_tipo_moneda');
        $res = $siat == $local;
        if ($res) {
            $this->sincronizacionesSiat('17');
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_tipo_moneda');
            $this->sincronizacionesSiat('17');
            echo json_encode($res);
        }
    }
    public function sincronizarParametricaTipoPuntoVenta()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_tipo_punto_venta');
        $res = $siat == $local;
        if ($res) {
            $this->sincronizacionesSiat('14');
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_tipo_punto_venta');
            $this->sincronizacionesSiat('14');
            echo json_encode($res);
        }
    }
    public function tipoPuntoVenta()
    {
        $tipos = $this->Sincronizar_model->getListaParametricas('siat_sincro_tipo_punto_venta');
        echo json_encode($tipos);
    }
    public function sincronizarParametricaTiposFactura()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_tipo_factura');
        $res = $siat == $local;
        if ($res) {
            $this->sincronizacionesSiat('11');
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_tipo_factura');
            $this->sincronizacionesSiat('11');
            echo json_encode($res);
        }
    }
    public function sincronizarParametricaUnidadMedida()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_unidad_medida');
        $res = $siat == $local;
        if ($res) {
            $this->sincronizacionesSiat('9');
            echo json_encode($res);
        } else {
            $res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_unidad_medida');
            $this->sincronizacionesSiat('9');
            echo json_encode($res);
        }
    }
    public function sincroCatalogosUltimas24Horas()
    {
        $res = $this->Sincronizar_model->sincroCatalogosUltimas24Horas();
        echo json_encode($res);
    }
}

