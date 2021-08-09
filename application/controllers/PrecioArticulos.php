<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PrecioArticulos extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('email');
		$this->load->model("Ingresos_model");
		$this->load->model("Egresos_model");
		$this->load->model("Admin_model");
	}
	
	public function index()
	{
		$this->accesoCheck(66);
		$this->titles('PrecioArticulos','Precio Articulos','Administracion');
			
		$this->datos['foot_script'][]=base_url('assets/hergo/precioArticulos.js') .'?'.rand();
		$this->setView('administracion/precioArticulos');


			
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

			$info = new stdclass();
			$info->codigo = $this->input->post('codigo');
			$info->uni = $this->input->post('uni');
			$info->descrip = $this->input->post('descrip');
			$info->precioDol = number_format($this->input->post('precioDolares'));
			$info->precio = number_format($this->input->post('precioBol'),2);
			
			//echo json_encode($info);die();
			$mail = $this->sendEmail($info);

			if($id)
			{
				$res = new stdclass();
				$res->status = true;
				$res->id = $id;
				$res->item = $item;
				//$res->mail = $mail;
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
	public function sendEmail($info){
		$group = array('wmx.seo@gmail.com');
		$config = $this->Admin_model->smtpConfig()->row_array();
		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("rn");

		$htmlContent = "<h1>Cambio de precio {$info->codigo} </h1>";
		$htmlContent .= "<p>El precio del artículo <b>{$info->codigo} | {$info->uni} | {$info->descrip}</b>
						 cambió de precio a <b>{$info->precio}</b> bolivianos.</p>
						 <p>Saludos...</p>";

		$this->email->to($group);
		//$this->email->cc('willy.salas@hotmail.com'); 
		$this->email->from($config['smtp_user'],'Willy Salas');
		$this->email->subject("CAMBIO DE PRECIO {$info->codigo}");
		$this->email->message($htmlContent);

		//Send email
		$emailSend = ($this->email->send()) ? 'Correo Enviado' : $this->email->print_debugger();
		return $emailSend;
	}

	public function sendEmailPrueba(){
		//SMTP & mail configuration
		$group = array('wmx.seo@gmail.com');
		$config = $this->Admin_model->smtpConfig()->row_array();
		//print_r($config['smtp_user']); die();
		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("rn");

		//Email content
		$htmlContent = "<h1>Cambio de precio  </h1>";
		$htmlContent .= "<p>El precio del artículo <b></b>
						 cambió de precio a <b></b> bolivianos.</p>
						 <p>Saludos...</p>";

		$this->email->to($group);
		//$this->email->cc('willy.salas@hotmail.com'); 
		$this->email->from($config['smtp_user'],'Willy Salas');
		$this->email->subject("CAMBIO DE PRECIO");
		$this->email->message($htmlContent);

		//Send email
		$emailSend = ($this->email->send()) ? 'Correo Enviado' : $this->email->print_debugger();
		echo $emailSend;
	}

}