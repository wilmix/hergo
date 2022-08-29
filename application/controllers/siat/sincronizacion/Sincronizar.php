<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sincronizar extends CI_Controller
{
	public function __construct()
	{	
		parent::__construct();
        //$this->load->model("Almacen_model");
        $this->load->model('siat/Sincronizar_model');

	}
    public function actividades()
    {
        //$this->accesoCheck(57);
		$this->titles('SincronizarActividades','Sincronizar Actividades Económicas','');
		$this->datos['foot_script'][]=base_url('assets/hergo/siat/sincronizar/actividades.js') .'?'.rand();
		$this->setView('siat/sincronizar/actividades');
    }
    public function sincronizarActividades()
    {
        $actividades = $this->input->post('dataSiat');
        $actividadesLocal = $this->Sincronizar_model->getActividades();
        //$res = $actividades === $actividadesLocal;
        sleep(5);
        //$res = $this->Sincronizar_model->storeActividades($actividades);
        echo json_encode($actividades);
    }
    public function actividadesDocumentoSector()
    {
        //$this->accesoCheck(57);
		$this->titles('ActividadesDocumentoSector','Sincronizar Actividades Documento Sector','');
		$this->datos['foot_script'][]=base_url('assets/hergo/siat/sincronizar/actividadesDocumentoSector.js') .'?'.rand();
		$this->setView('siat/sincronizar/actividadesDocumentoSector');
    }
    public function sincronizarActividadesDocumentoSector()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getActividadesDocumentoSector();
        $res = $siat === $local;
        sleep(4);
        //$res = $this->Sincronizar_model->storeActividadesDocumentoSector($siat);
        //echo json_encode(['local'=>$local, 'siat'=>$siat]);
        echo json_encode($res);
    }
    public function listaLeyendasFactura()
    {
        //$this->accesoCheck(57);
		$this->titles('LeyendasFactura','Sincronizar Lista de Leyendas Factura','');
		$this->datos['foot_script'][]=base_url('assets/hergo/siat/sincronizar/listaLeyendasFactura.js') .'?'.rand();
		$this->setView('siat/sincronizar/listaLeyendasFactura');
    }
    public function sincronizarListaLeyendasFactura()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaLeyendasFactura();
        $res = $siat === $local;
        sleep(5);
        //$res = $this->Sincronizar_model->storeListaLeyendasFactura($siat);
        //echo json_encode(['local'=>$local, 'siat'=>$siat]);
        echo json_encode($res);
    }
    public function listaMensajesServicios()
    {
        //$this->accesoCheck(57);
		$this->titles('LeyendasFactura','Sincronizar Lista Mensajes de Servicio','');
		$this->datos['foot_script'][]=base_url('assets/hergo/siat/sincronizar/listaMensajesServicios.js') .'?'.rand();
		$this->setView('siat/sincronizar/listaMensajesServicios');
    }
    public function sincronizarMensajesServicios()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaMensajesServicios();
        $res = $siat === $local;
        sleep(12);
        //$res = $this->Sincronizar_model->storeMensajesServicios($siat);
        //echo json_encode(['local'=>$local, 'siat'=>$siat]);
        echo json_encode($res);
    }
    public function listaProductosServicios()
    {
        //$this->accesoCheck(57);
		$this->titles('SincroProductosServicios','Sincronizar Lista Productos y Servicios','');
		$this->datos['foot_script'][]=base_url('assets/hergo/siat/sincronizar/listaProductosServicios.js') .'?'.rand();
		$this->setView('siat/sincronizar/listaProductosServicios');
    }
    public function sincronizarListaProductosServicios()
    {
        $siat = $this->input->post('dataSiat');
        /* $siat = json_encode($siat);
        $siat = json_decode($siat); */
        $siatNew = [];
        $local = $this->Sincronizar_model->getlistaProductosServicios();
        
        foreach ($siat as $value) {
            unset($value['nandina']);
            array_push($siatNew, $value);
        }
        /* foreach ($siat as $value) {
            unset($value->nandina);
            $this->Sincronizar_model->storeListaProductosServicios($value);
        } */
        $res = $siatNew === $local;
        sleep(5);
        $resp = ['local'=>$local, 'siat'=>$siatNew, 'res'=>$res];
        echo json_encode($resp);
    }
    public function parametricas()
    {
        //$this->accesoCheck(57);
		$this->titles('SincroParametricas','Sincronizar Listado Paramétricas','');
		$this->datos['foot_script'][]=base_url('assets/hergo/siat/sincronizar/parametricas.js') .'?'.rand();
		$this->setView('siat/sincronizar/parametricas');
    }
    public function sincronizarParametricaEventosSignificativos()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_eventos_significativos');
        $res = $siat === $local;
        sleep(1);
        //$res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_eventos_significativos');
        //echo json_encode(['local'=>$local, 'siat'=>$siat]);
        echo json_encode($res);
    }
    public function sincronizarParametricaMotivoAnulacion()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_motivo_anulacion');
        $res = $siat === $local;
        sleep(1);
        //$res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_motivo_anulacion');
        //echo json_encode(['local'=>$local, 'siat'=>$siat]);
        echo json_encode($res);
    }
    public function sincronizarParametricaPaisOrigen()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_paises');
        $res = $siat === $local;
        sleep(1);
        //$res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_paises');
        //echo json_encode(['local'=>$local, 'siat'=>$siat]);
        echo json_encode($res);
    }
    public function sincronizarParametricaTipoDocumentoIdentidad()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_tipo_documento_identidad');
        $res = $siat === $local;
        sleep(1);
        //$res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_tipo_documento_identidad');
        //echo json_encode(['local'=>$local, 'siat'=>$siat]);
        echo json_encode($res);
    }
    public function sincronizarParametricaTipoDocumentoSector()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_tipo_doc_sector');
        $res = $siat === $local;
        sleep(3);
        //$res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_tipo_doc_sector');
        //echo json_encode(['local'=>$local, 'siat'=>$siat]);
        echo json_encode($res);
    }
    public function sincronizarParametricaTipoEmision()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_tipo_emision');
        $res = $siat === $local;
        //$res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_tipo_emision');
        echo json_encode($res);
    }
    public function sincronizarParametricaTipoHabitacion()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_tipo_habitacion');
        $res = $siat === $local;
        //$res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_tipo_habitacion');
        echo json_encode($res);
    }
    public function sincronizarParametricaTipoMetodoPago()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_tipo_metodo_pago');
        $res = $siat === $local;
        //$res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_tipo_metodo_pago');
        echo json_encode($res);
    }
    public function sincronizarParametricaTipoMoneda()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_tipo_moneda');
        $res = $siat === $local;
        //$res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_tipo_moneda');
        echo json_encode($res);
    }
    public function sincronizarParametricaTipoPuntoVenta()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_tipo_punto_venta');
        $res = $siat === $local;
        //$res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_tipo_punto_venta');
        echo json_encode($res);
    }
    public function sincronizarParametricaTiposFactura()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_tipo_factura');
        $res = $siat === $local;
        //$res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_tipo_factura');
        echo json_encode($res);
    }
    public function sincronizarParametricaUnidadMedida()
    {
        $siat = $this->input->post('dataSiat');
        $local = $this->Sincronizar_model->getListaParametricas('siat_sincro_unidad_medida');
        $res = $siat === $local;
        //$res = $this->Sincronizar_model->storeParametricas($siat, 'siat_sincro_unidad_medida');
        echo json_encode($res);
    }
}

