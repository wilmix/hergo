<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PrecioArticulos extends CI_Controller
{
	private $datos;
	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logeado'))
		redirect('auth', 'refresh');
		$this->load->library('LibAcceso');
		$this->load->library('email');
		$this->load->helper('url');
		$this->load->model("Ingresos_model");
		$this->load->model("Egresos_model");
		$this->load->model("Admin_model");
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
		$this->cabeceras_css=array(
				base_url('assets/bootstrap/css/bootstrap.min.css'),
				base_url("assets/fa/css/font-awesome.min.css"),
				base_url("assets/dist/css/AdminLTE.min.css"),
				base_url("assets/dist/css/skins/skin-blue.min.css"),
				base_url("assets/hergo/estilos.css"),
				base_url('assets/plugins/table-boot/css/bootstrap-table.css'),
				base_url('assets/plugins/table-boot/plugin/select2.min.css'),				
				base_url('assets/sweetalert/sweetalert2.min.css'),
				base_url('assets/plugins/table-boot/plugin/bootstrap-table-sticky-header.css'),
				base_url('assets/plugins/daterangepicker/daterangepicker.css')	

		);
		$this->cabecera_script=array(
				base_url('assets/plugins/jQuery/jquery-2.2.3.min.js'),
				base_url('assets/bootstrap/js/bootstrap.min.js'),
				base_url('assets/dist/js/app.min.js'),
				base_url('assets/plugins/validator/bootstrapvalidator.min.js'),
				base_url('assets/plugins/table-boot/js/bootstrap-table.js'),
				base_url('assets/plugins/table-boot/js/bootstrap-table-es-MX.js'),
				base_url('assets/plugins/table-boot/js/bootstrap-table-export.js'),
				base_url('assets/plugins/table-boot/js/tableExport.js'),
				base_url('assets/plugins/table-boot/js/bootstrap-table-filter.js'),
				base_url('assets/plugins/table-boot/plugin/select2.min.js'),
				base_url('assets/plugins/table-boot/plugin/bootstrap-table-select2-filter.js'),
        		base_url('assets/plugins/daterangepicker/moment.min.js'),
        		base_url('assets/plugins/slimscroll/slimscroll.min.js'),        		
        		base_url('assets/sweetalert/sweetalert2.min.js'),
				base_url('assets/busqueda/underscore-min.js'),
				base_url('assets/plugins/table-boot/plugin/bootstrap-table-sticky-header.js'),
				base_url('assets/plugins/daterangepicker/daterangepicker.js'),
				base_url('assets/plugins/daterangepicker/locale/es.js')


		);
		$this->foot_script=array(				
			'https://cdn.jsdelivr.net/npm/vue/dist/vue.js',								
			base_url('assets/vue/vue-resource.min.js'),	
			'https://unpkg.com/vue-select@3.10.3/dist/vue-select.js',
			'https://unpkg.com/vuejs-datepicker',
			'https://unpkg.com/vuejs-datepicker/dist/locale/translations/es.js'
		);
		$this->datos['nombre_usuario']= $this->session->userdata('nombre');
		$this->datos['almacen_usuario']= $this->session->userdata['datosAlmacen']->almacen;

		$this->datos['user_id_actual']=$this->session->userdata['user_id'];
		$this->datos['nombre_actual']=$this->session->userdata['nombre'];
		$this->datos['almacen_actual']=$this->session->userdata['datosAlmacen']->almacen;
		$this->datos['id_Almacen_actual']=$this->session->userdata['datosAlmacen']->idalmacen;
		$this->datos['grupsOfUser'] = $this->ion_auth->in_group('Nacional') ? 'Nacional' : false;
		$hoy = date('Y-m-d');
		$tipoCambio = $this->Ingresos_model->getTipoCambio($hoy);
		if ($tipoCambio) {
			$tipoCambio = $tipoCambio->tipocambio;
			$this->datos['tipoCambio'] = $tipoCambio;
		} else {
			$this->datos['tipoCambio'] = 'No se tiene tipo de cambio para la fecha';
		}
		
			if($this->session->userdata('foto')==NULL)
				$this->datos['foto']=base_url('assets/imagenes/ninguno.png');
			else
				$this->datos['foto']=base_url('assets/imagenes/').$this->session->userdata('foto');
	}
	
	public function index()
	{
		//$this->libacceso->acceso(15);
		if(!$this->session->userdata('logeado'))
			redirect('auth', 'refresh');
			$this->datos['menu']="Precio";
			$this->datos['opcion']="Articulos";
			$this->datos['titulo']="PrecioArticulos";
			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
			$this->datos['foot_script']= $this->foot_script;
			/**************FUNCION***************/
			$this->datos['cabeceras_script'][]=base_url('assets/hergo/funciones.js');
			$this->datos['foot_script'][]=base_url('assets/hergo/precioArticulos.js');
            
			
			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->load->view('plantilla/headercontainer.php',$this->datos);
			$this->load->view('administracion/precioArticulos.php',$this->datos);
			$this->load->view('plantilla/footcontainer.php',$this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);
			$this->load->view('plantilla/footerscript.php',$this->datos);
			
	}
    public function showPrecioArticulos()
	{
		if($this->input->is_ajax_request())
        {
			$res=$this->Admin_model->showPrecioArticulos();
			$res=$res->result_array();
			echo json_encode($res);
		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function update()
	{
		if($this->input->is_ajax_request())
		{
			
			$id = $this->input->post('id');
			$item = new stdclass();
			$item->costo = $this->input->post('costo');
			$item->porcentaje = $this->input->post('porcentaje');
			$item->precio = $this->input->post('precioBol');
			$item->precioDol = $this->input->post('precioDolares');
			$item->updatedPrecio_by = $this->session->userdata('user_id');
			$item->updatedPrecio_at = date('Y-m-d H:i:s');

			//echo json_encode($item);die();
			$id = $this->Admin_model->update($id , $item);

			if($id)
			{
				$res = new stdclass();
				$res->status = true;
				$res->id = $id;
				$res->item = $item;
				echo json_encode($res);
			} else {
				echo json_encode($id);
			}

		}
		else
		{
			die("PAGINA NO ENCONTRADA");
		}
	}
	public function sendEmail(){
		//SMTP & mail configuration
		$config = array(
			'protocol'  => 'smtp',
			'smtp_host' => 'hergo.com.bo',
			'smtp_port' => 25,
			'smtp_user' => 'willy@hergo.com.bo',
			'smtp_pass' => 'hergoCWS1',
			'mailtype'  => 'html',
			'charset'   => 'utf-8'
		);
		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("rn");

		//Email content
		$htmlContent = '<h1>Sending email via SMTP server</h1>';
		$htmlContent .= '<p>This email has sent via SMTP server from application by Willy.</p>';

		$this->email->to('wmx.seo@gmail.com');
		$this->email->from('willy@hergo.com.bo','Willy Salas');
		$this->email->subject('Email send via SMTP server by Willy');
		$this->email->message($htmlContent);

		//Send email
		$emailSend = ($this->email->send()) ? 'Correo Enviado' : 'No se pudo enviar el correo';
		echo $emailSend;
	}

}