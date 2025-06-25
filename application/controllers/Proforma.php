<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proforma extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model("Proforma_model");
        $this->load->model("General_model");
        $this->load->library('form_validation');
    }
	public function index()
	{
			$this->accesoCheck(67);
			$this->titles('Proformas','Consulta Proformas','Proformas');
			
			$this->datos['foot_script'][]=base_url('assets/hergo/proforma/proformas.js') .'?'.rand();
			$this->setView('proforma/consultaProformas');
	}
	public function formProforma($id='crear')
	{
		$this->accesoCheck(67);
		$this->datos['id'] = ($id =='crear') ? 0 : $id;
		$this->titles('CrearProforma','Crear Proforma','Proformas',);
		if ($this->datos['id']>0) {
			$this->titles('EditarProforma','Editar Proforma','Proformas',);
		}
		$this->datos['foot_script'][]=base_url('assets/hergo/fileutils.js') .'?'.rand();
		$this->datos['foot_script'][]=base_url('assets/hergo/proforma/formProforma.js') .'?'.rand();
		$this->setView('proforma/formProforma');
	}

	public function getProformas()  
	{
		if(!$this->input->is_ajax_request())
		die("PAGINA NO ENCONTRADA");
 
		$ini=$this->security->xss_clean($this->input->post("ini"));
		$fin=$this->security->xss_clean($this->input->post("fin"));
		$alm= $this->input->post("alm");
		$res=$this->Proforma_model->getProformas($ini, $fin, $alm); 
		echo json_encode($res);
	}
	public function store() {
        if (!$this->input->is_ajax_request()) {
            show_error('No se permite el acceso directo a esta página', 403);
            return;
        }

        header('Content-Type: application/json');

        // Set validation rules
        $this->form_validation->set_rules([
            [
                'field' => 'almacen',
                'label' => 'Almacén',
                'rules' => 'required|integer|callback_check_almacen'
            ],
            [
                'field' => 'fecha',
                'label' => 'Fecha',
                'rules' => 'required|callback_check_date'
            ],
            [
                'field' => 'clienteDato',
                'label' => 'Cliente',
                'rules' => 'required|trim|max_length[150]'
            ],
            [
                'field' => 'moneda',
                'label' => 'Moneda',
                'rules' => 'required|integer|callback_check_moneda'
            ],
            [
                'field' => 'tipo',
                'label' => 'Tipo',
                'rules' => 'required|integer|callback_check_tipo'
            ],
            [
                'field' => 'items',
                'label' => 'Items',
                'rules' => 'required|callback_check_items'
            ]
        ]);

        // Optional fields
        $this->form_validation->set_rules('complemento', 'Complemento', 'trim|max_length[200]');
        $this->form_validation->set_rules('condicionesPago', 'Condiciones de Pago', 'trim|max_length[150]');
        $this->form_validation->set_rules('validez', 'Validez de Oferta', 'trim|max_length[150]');
        $this->form_validation->set_rules('lugarEntrega', 'Lugar de Entrega', 'trim|max_length[150]');
        $this->form_validation->set_rules('porcentajeDescuento', 'Porcentaje de Descuento', 'trim|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('descuento', 'Descuento', 'trim|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('tiempoEntregaC', 'Tiempo de Entrega', 'trim|max_length[50]');
        $this->form_validation->set_rules('garantia', 'Garantía', 'trim|max_length[100]');
        $this->form_validation->set_rules('glosa', 'Glosa', 'trim|max_length[1000]');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_status_header(422);
            echo json_encode([
                'status' => false,
                'message' => 'Error de validación',
                'errors' => $this->form_validation->error_array()
            ]);
            return;
        }

        try {
            $id = $this->input->post('id');
            $gestion = $this->General_model->getGestionActual()->gestionActual;
            
            $proforma = new stdclass();
            $proforma->almacen = $this->input->post('almacen');
            $proforma->num = $id ? $this->input->post('n') : $this->Proforma_model->getNumMov($gestion, $proforma->almacen);
            $proforma->fecha = $this->input->post('fecha');
            $proforma->clienteDatos = strtoupper(trim($this->input->post('clienteDato')));
            $proforma->complemento = strtoupper(trim($this->input->post('complemento')));
            $proforma->cliente = 1; // TODO: Validate client exists
            $proforma->moneda = $this->input->post('moneda');
            $proforma->condicionesPago = strtoupper($this->input->post('condicionesPago'));
            $proforma->porcentajeDescuento = $this->input->post('porcentajeDescuento') ?: 0;
            $proforma->descuento = round($this->input->post('descuento') ?: 0, 2);
            $proforma->total = round($this->input->post('totalFin'), 2);
            $proforma->validezOferta = strtoupper($this->input->post('validez'));
            $proforma->lugarEntrega = strtoupper($this->input->post('lugarEntrega'));
            $proforma->tiempoEntrega = strtoupper($this->input->post('tiempoEntregaC'));
            $proforma->garantia = strtoupper($this->input->post('garantia'));
            $proforma->tipo = strtoupper($this->input->post('tipo'));
            $proforma->glosa = nl2br(strtoupper($this->input->post('glosa')));
            $proforma->gestion = $gestion;
            $proforma->autor = $this->session->userdata('user_id');
            $proforma->updated_at = $id ? date('Y-m-d H:i:s') : null;
            $proforma->items = json_decode($this->input->post('items'));

            $stored_id = $this->Proforma_model->storeProforma($id, $proforma);

            if (!$stored_id) {
                throw new Exception('Error al guardar la proforma');
            }

            echo json_encode([
                'status' => true,
                'message' => $id ? 'Proforma actualizada correctamente' : 'Proforma creada correctamente',
                'id' => $stored_id
            ]);

        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            $this->output->set_status_header(500);
            echo json_encode([
                'status' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ]);
        }
    }

    // Validation callbacks
    public function check_date($date) {
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
            $this->form_validation->set_message('check_date', 'El campo {field} debe tener formato YYYY-MM-DD');
            return FALSE;
        }
        return TRUE;
    }

    public function check_almacen($almacen) {
        if (!$this->Proforma_model->checkAlmacenExists($almacen)) {
            $this->form_validation->set_message('check_almacen', 'El {field} seleccionado no existe');
            return FALSE;
        }
        return TRUE;
    }

    public function check_moneda($moneda) {
        if (!$this->Proforma_model->checkMonedaExists($moneda)) {
            $this->form_validation->set_message('check_moneda', 'La {field} seleccionada no existe');
            return FALSE;
        }
        return TRUE;
    }

    public function check_tipo($tipo) {
        if (!$this->Proforma_model->checkTipoExists($tipo)) {
            $this->form_validation->set_message('check_tipo', 'El {field} seleccionado no existe');
            return FALSE;
        }
        return TRUE;
    }

    public function check_items($items) {
        $items = json_decode($items);
        
        if (!is_array($items) || empty($items)) {
            $this->form_validation->set_message('check_items', 'Debe agregar al menos un ítem a la proforma');
            return FALSE;
        }

        foreach ($items as $key => $item) {
            $item_num = $key + 1;
            if (!isset($item->id) || !isset($item->cantidad) || !isset($item->precioLista)) {
                $this->form_validation->set_message('check_items', "El ítem {$item_num} es inválido.");
                return FALSE;
            }
            
            if (!is_numeric($item->cantidad) || $item->cantidad <= 0) {
                $this->form_validation->set_message('check_items', "La cantidad del ítem {$item_num} debe ser mayor a cero.");
                return FALSE;
            }

            if (!is_numeric($item->precioLista) || $item->precioLista < 0) {
                $this->form_validation->set_message('check_items', "El precio del ítem {$item_num} debe ser mayor o igual a cero.");
                return FALSE;
            }

            if (!$this->Proforma_model->checkArticuloExists($item->id)) {
                $this->form_validation->set_message('check_items', "El artículo del ítem {$item_num} no existe.");
                return FALSE;
            }

            // Validation for item description and other fields
            if (isset($item->descrip) && strlen($item->descrip) > 1000) {
                $this->form_validation->set_message('check_items', "La descripción del ítem {$item_num} no puede exceder los 1000 caracteres.");
                return FALSE;
            }

            if (isset($item->marcaSigla) && strlen($item->marcaSigla) > 50) {
                $this->form_validation->set_message('check_items', "La marca del ítem {$item_num} no puede exceder los 50 caracteres.");
                return FALSE;
            }

            if (isset($item->industria) && strlen($item->industria) > 50) {
                $this->form_validation->set_message('check_items', "La industria del ítem {$item_num} no puede exceder los 50 caracteres.");
                return FALSE;
            }

            if (isset($item->tiempoEntrega) && strlen($item->tiempoEntrega) > 50) {
                $this->form_validation->set_message('check_items', "El tiempo de entrega del ítem {$item_num} no puede exceder los 50 caracteres.");
                return FALSE;
            }
        }
        return TRUE;
    }
	public function searchItem()
    {
        if($this->input->is_ajax_request() && $this->input->post('item'))
        {
			$item = $this->security->xss_clean($this->input->post('item'));
			$alm = $this->security->xss_clean($this->input->post('alm'));
        	$dato=$this->Proforma_model->searchItem($item,$alm);        	
			echo json_encode($dato->result_array());
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function searchCliente()
    {
        if($this->input->is_ajax_request() && $this->input->post('search'))
        {
			$search = $this->security->xss_clean($this->input->post('search'));
        	$dato=$this->Proforma_model->searchClientes($search)->result_array();        	
			echo json_encode($dato);
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
    }
	public function getInfoProformaForm()  
	{
		if($this->input->is_ajax_request())
        {
			$datosProforma = new stdclass();
			$datosProforma->tipos = $this->Proforma_model->getTipos(); 
			$datosProforma->almacenes = $this->Proforma_model->getAlmacenes(); 
			$datosProforma->monedas = $this->Proforma_model->getMonedas(); 
			$datosProforma->articulos = $this->Proforma_model->getArticulos(); 

			echo json_encode($datosProforma);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function getArticulo()
    {
        if($this->input->is_ajax_request() && $this->input->post('id'))
        {
			$id = $this->security->xss_clean($this->input->post('id'));
			$alm = $this->security->xss_clean($this->input->post('alm'));
        	$dato=$this->Proforma_model->getArticulo($id,$alm);        	
			echo json_encode($dato);
		}
        else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function getProforma(){
		if($this->input->is_ajax_request())
        {
			$id=$this->security->xss_clean($this->input->post("id"));
			$user =$this->session->userdata('user_id');
			$proforma = new stdclass();
			$proforma->proforma = $this->Proforma_model->getProforma($id); 
			$proforma->items = $this->Proforma_model->getProformaItems($id);
			echo json_encode($proforma);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
}