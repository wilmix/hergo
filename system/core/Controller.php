<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2016, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public $datos;
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}
		$this->datos['skin']= ($this->config->item('skin')) ? $this->config->item('skin') : 'skin-blue';
		
		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

	public function getDatos()
	{
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
		} 
		else {
			$this->datos['tipoCambio'] = 'No se tiene tipo de cambio para la fecha';
		}
		
			if($this->session->userdata('foto')==NULL){
				$this->datos['foto']=base_url('assets/imagenes/ninguno.png');
			}
			else{
				$this->datos['foto']=base_url('assets/imagenes/').$this->session->userdata('foto');
			}
	}
	public function getAssets()
	{
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
			base_url('assets/plugins/daterangepicker/daterangepicker.css'),
			base_url('assets/plugins/jQueryUI/jquery-ui.min.css'),//autocomplete
			base_url('assets/plugins/table-boot/plugin/bootstrap-editable.css'),
			base_url('assets/BootstrapToggle/bootstrap-toggle.min.css'),
			base_url('assets/plugins/select/bootstrap-select.min.css'),//select
			'https://unpkg.com/vue-select@3.10.3/dist/vue-select.css',//select	

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
			base_url('assets/plugins/daterangepicker/locale/es.js'),
			base_url('assets/plugins/jQueryUI/jquery-ui.min.js'),//autocomplete
			/**************INPUT MASK***************/
			base_url('assets/plugins/inputmask/inputmask.js'),
			base_url('assets/plugins/inputmask/inputmask.numeric.extensions.js'),
			base_url('assets/plugins/inputmask/jquery.inputmask.js'),
			base_url('assets/plugins/table-boot/plugin/bootstrap-table-editable.js'),
			base_url('assets/plugins/table-boot/plugin/bootstrap-editable.js'),
			base_url('assets/BootstrapToggle/bootstrap-toggle.min.js'),
			base_url('assets/plugins/select/bootstrap-select.min.js'),//select
			base_url('assets/plugins/numeral/numeral.min.js'),


		);
		$this->foot_script=array(				
			'https://cdn.jsdelivr.net/npm/vue/dist/vue.js',								
			base_url('assets/vue/vue-resource.min.js'),	
			'https://unpkg.com/vue-select@3.10.3/dist/vue-select.js',
			'https://unpkg.com/vuejs-datepicker',
			'https://unpkg.com/vuejs-datepicker/dist/locale/translations/es.js',
		);
	}

}
