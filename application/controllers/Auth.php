<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

			/*******/
			$this->load->library('LibAcceso');
		
			/*******/
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language'));
		$this->load->model('ion_auth_model');//solo para guardar fotos
		$this->load->model("Ingresos_model");
		date_default_timezone_set("America/La_Paz");


		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
	}

	// redirect if needed, otherwise display the user list
	public function index()
	{

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		//elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		//{
			// redirect them to the home page because they must be an administrator to view this
		//	return show_error('You must be an administrator to view this page.');
		//}
		else
		{
			// set the flash datos error message if there is one
			$this->datos['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			//list the users
			$this->datos['users'] = $this->ion_auth->users()->result();
			foreach ($this->datos['users'] as $k => $user)
			{
				$this->datos['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}

			//$this->_render_page('auth/index', $this->datos);
			redirect("principal", 'refresh');			
		}
	}
	//editar credenciales
	public function usuarios()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		/*elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}*/
		else
		{
			$this->accesoCheck(35);
			$this->titles('Usuarios','Usuarios','Configuración');
			// set the flash datos error message if there is one
			$this->datos['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			//list the users
			$this->datos['users'] = $this->ion_auth->users()->result();
			foreach ($this->datos['users'] as $k => $user)
			{
				$this->datos['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}
			$this->setView('auth/index');
		}
	}

	// log the user in
	public function login()
	{
		$this->datos['cabeceras_css']= $this->cabeceras_css;
		$this->datos['cabeceras_script']= $this->cabecera_script;
		$this->datos['cabeceras_css'][]=base_url('assets/login/css/form-elements.css');
		$this->datos['cabeceras_css'][]=base_url('assets/login/css/style.css');
		$this->datos['cabeceras_script'][]=base_url('assets/login/js/jquery.backstretch.min.js');
		$this->datos['cabeceras_script'][]=base_url('assets/login/js/scripts.js');


		$this->datos['title'] = $this->lang->line('login_heading');

		//validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

		if($this->session->userdata('logeado'))
			redirect('principal', 'refresh');
		if ($this->form_validation->run() == true)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('/', 'refresh');
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash datos error message if there is one
			$this->datos['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->datos['identity'] = array('name' => 'identity',
				'id'    => 'identity',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->datos['password'] = array('name' => 'password',
				'id'   => 'password',
				'type' => 'password',
			);

			$this->datos['titulo']="Iniciar Sesion";
			$this->load->view('plantilla/headLogin',$this->datos);
			$this->_render_page('login/login', $this->datos);
			$this->load->view('login/footerlogin',$this->datos);
		}
	}

	// log the user out
	public function logout()
	{
		$this->datos['title'] = "Logout";

		// log the user out
		$logout = $this->ion_auth->logout();

		// redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('auth/login', 'refresh');
	}

	// change password
	public function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false)
		{
			// display the form
			// set the flash datos error message if there is one
			$this->datos['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->datos['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->datos['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->datos['new_password'] = array(
				'name'    => 'new',
				'id'      => 'new',
				'type'    => 'password',
				'pattern' => '^.{'.$this->datos['min_password_length'].'}.*$',
			);
			$this->datos['new_password_confirm'] = array(
				'name'    => 'new_confirm',
				'id'      => 'new_confirm',
				'type'    => 'password',
				'pattern' => '^.{'.$this->datos['min_password_length'].'}.*$',
			);
			$this->datos['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			);

			// render
			$this->_render_page('auth/change_password', $this->datos);
		}
		else
		{
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/change_password', 'refresh');
			}
		}
	}

	// forgot password
	public function forgot_password()
	{
		// setting validation rules by checking whether identity is username or email
		if($this->config->item('identity', 'ion_auth') != 'email' )
		{
		   $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		}
		else
		{
		   $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() == false)
		{
			$this->datos['type'] = $this->config->item('identity','ion_auth');
			// setup the input
			$this->datos['identity'] = array('name' => 'identity',
				'id' => 'identity',
			);

			if ( $this->config->item('identity', 'ion_auth') != 'email' ){
				$this->datos['identity_label'] = $this->lang->line('forgot_password_identity_label');
			}
			else
			{
				$this->datos['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->datos['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('auth/forgot_password', $this->datos);
		}
		else
		{
			$identity_column = $this->config->item('identity','ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if(empty($identity)) {

	            		if($this->config->item('identity', 'ion_auth') != 'email')
		            	{
		            		$this->ion_auth->set_error('forgot_password_identity_not_found');
		            	}
		            	else
		            	{
		            	   $this->ion_auth->set_error('forgot_password_email_not_found');
		            	}

		                $this->session->set_flashdata('message', $this->ion_auth->errors());
                		redirect("auth/forgot_password", 'refresh');
            		}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				// if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	// reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false)
			{
				// display the form

				// set the flash datos error message if there is one
				$this->datos['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->datos['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->datos['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
					'type' => 'password',
					'pattern' => '^.{'.$this->datos['min_password_length'].'}.*$',
				);
				$this->datos['new_password_confirm'] = array(
					'name'    => 'new_confirm',
					'id'      => 'new_confirm',
					'type'    => 'password',
					'pattern' => '^.{'.$this->datos['min_password_length'].'}.*$',
				);
				$this->datos['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->datos['csrf'] = $this->_get_csrf_nonce();
				$this->datos['code'] = $code;

				// render
				$this->_render_page('auth/reset_password', $this->datos);
			}
			else
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						// if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("auth/login", 'refresh');
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}


	// activate the user
	public function activate($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			// redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth/usuarios");
		//	$this->usuarios();
		}
		else
		{
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	// deactivate the user
	public function deactivate($id = NULL)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}
		$this->titles('DesactivarUsuario','Desactivar Usuario','Configuración');
		$id = (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->datos['csrf'] = $this->_get_csrf_nonce();
			$this->datos['user'] = $this->ion_auth->user($id)->row();

			$this->setView('auth/deactivate_user');
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				/* if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					show_error($this->lang->line('error_csrf'));
				} */

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
				}
			}

			// redirect them back to the auth page
			redirect('auth/usuarios');
			//$this->usuarios();
		}
	}

	// create a new user
	public function create_user()
    {
		//$this->libacceso->acceso(36);
		$this->accesoCheck(36);
		$this->titles('CrearUsuario','Crear Usuario','Configuración');
		//if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())

        if (!$this->ion_auth->logged_in())
        {
            redirect('auth', 'refresh');
        }

        $tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
		$this->datos['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
        if($identity_column!=='email')
        {
            $this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        }
        else
        {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');
		$this->form_validation->set_rules('almacen', $this->lang->line('edit_user_validation_almacen_label'), 'required');
		

        if ($this->form_validation->run() == true)
        {
            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'company'    => $this->input->post('company'),
				'phone'      => $this->input->post('phone'),
				'almacen'      => $this->input->post('almacen'),
            );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($identity, $password, $email, $additional_data))
        {
            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth", 'refresh');
        }
        else
        {
            // display the create user form
            // set the flash datos error message if there is one
            $this->datos['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->datos['first_name'] = array(
                'name'  => 'first_name',
                'id'    => 'first_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('first_name'),
                'placeholder' => 'Nombre',//modificado para bootstrap
                'class' => 'form-control',//modificado para bootstrap
            );
            $this->datos['last_name'] = array(
                'name'  => 'last_name',
                'id'    => 'last_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('last_name'),
                'placeholder' => 'Apellido',//modificado para bootstrap
                'class' => 'form-control',//modificado para bootstrap
            );
            $this->datos['identity'] = array(
                'name'  => 'identity',
                'id'    => 'identity',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('identity'),
                'placeholder' => 'identity',//modificado para bootstrap
                'class' => 'form-control',//modificado para bootstrap
            );
            $this->datos['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('email'),
                'placeholder' => 'Correo',//modificado para bootstrap
                'class' => 'form-control',//modificado para bootstrap
            );
            $this->datos['company'] = array(
                'name'  => 'company',
                'id'    => 'company',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('company'),
                'placeholder' => 'Cargo',//modificado para bootstrap
                'class' => 'form-control',//modificado para bootstrap
            );
            $this->datos['phone'] = array(
                'name'  => 'phone',
                'id'    => 'phone',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('phone'),
                'placeholder' => 'Telefono',//modificado para bootstrap
                'class' => 'form-control',//modificado para bootstrap
            );
            $this->datos['password'] = array(
                'name'  => 'password',
                'id'    => 'password',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password'),
                'placeholder' => 'Contraseña',//modificado para bootstrap
                'class' => 'form-control',//modificado para bootstrap
            );
            $this->datos['password_confirm'] = array(
                'name'  => 'password_confirm',
                'id'    => 'password_confirm',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
                'placeholder' => 'Cofirmar contraseña',//modificado para bootstrap
                'class' => 'form-control',//modificado para bootstrap
			);
			$this->datos['almacen'] = array(
                'name'  => 'almacen',
                'id'    => 'almacen',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('almacen'),
                'placeholder' => 'Almacen',//modificado para bootstrap
                'class' => 'form-control',//modificado para bootstrap
            );
			$this->setView('auth/create_user');
        }
    }

	// edit a user
	public function edit_user($id)
	{
		$this->datos['title'] = $this->lang->line('edit_user_heading');
		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
		{
			redirect('auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$groups=$this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required');
		$this->form_validation->set_rules('almacen', $this->lang->line('edit_user_validation_almacen_label'), 'required');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			/* if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			} */

			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$datos = array(
					'first_name' => $this->input->post('first_name'),
					'last_name'  => $this->input->post('last_name'),
					'company'    => $this->input->post('company'),
					'phone'      => $this->input->post('phone'),
					'almacen'    => $this->input->post('almacen'),
				);

				// update the password if it was posted
				if ($this->input->post('password'))
				{
					$datos['password'] = $this->input->post('password');
				}
				$this->subir_imagen($id);
				$this->session->set_userdata("nombre",$datos['first_name']." ".$datos['last_name']);

				// NOT Only allow updating groups if user is admin
				if ($this->ion_auth->is_admin())
				{
					
					
					//Update the groups user belongs to
					$groupData = $this->input->post('groups');

					if (isset($groupData) && !empty($groupData)) {

						$this->ion_auth->remove_from_group('', $id);

						foreach ($groupData as $grp) {
							$this->ion_auth->add_to_group($grp, $id);
						}

					}
				}

			// check to see if we are updating the user
			   if($this->ion_auth->update($user->id, $datos))
			    {
			    	// redirect them back to the admin page if admin, or to the base url if non admin
				    $this->session->set_flashdata('message', $this->ion_auth->messages() );
				    if ($this->ion_auth->is_admin())
					{
						redirect('auth/usuarios');
					}
					else
					{
						
						redirect('/', 'refresh');
					}

			    }
			    else
			    {
			    	// redirect them back to the admin page if admin, or to the base url if non admin
				    $this->session->set_flashdata('message', $this->ion_auth->errors() );
				    if ($this->ion_auth->is_admin())
					{
						redirect('auth/usuarios');
					}
					else
					{
						
						redirect('/', 'refresh');
					}

			    }

			}
		}

		// display the edit user form
		$this->datos['csrf'] = $this->_get_csrf_nonce();

		// set the flash datos error message if there is one
		$this->datos['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->datos['user'] = $user;
		$this->datos['groups'] = $groups;
		$this->datos['currentGroups'] = $currentGroups;

		$this->datos['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
			'placeholder' => 'Nombre',//modificado para bootstrap
            'class' => 'form-control',//modificado para bootstrap
		);
		$this->datos['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
			'placeholder' => 'Apellido',//modificado para bootstrap
            'class' => 'form-control',//modificado para bootstrap
		);
		$this->datos['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
			'placeholder' => 'Cargo',//modificado para bootstrap
            'class' => 'form-control',//modificado para bootstrap
		);
		$this->datos['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
			'placeholder' => 'Telefono',//modificado para bootstrap
            'class' => 'form-control',//modificado para bootstrap
		);
		$this->datos['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password',
			'placeholder' => 'Contraseña',//modificado para bootstrap
            'class' => 'form-control',//modificado para bootstrap
		);
		$this->datos['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password',
			'placeholder' => 'Confirmar contraseña',//modificado para bootstrap
            'class' => 'form-control',//modificado para bootstrap
		);
		$this->datos['almacen'] = array(
			'name'  => 'almacen',
			'id'    => 'almacen',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('almacen'),
			'value' => $this->form_validation->set_value('almacen', $user->almacen),
			'placeholder' => 'Almacen',//modificado para bootstrap
			'class' => 'form-control',//modificado para bootstrap
		);
		        
		$this->titles('EditarUsuarios','Editar Usuario','Configuración');
		$this->setView('auth/edit_user');
	}

	// create a new group
	public function create_group()
	{
		$this->libacceso->acceso(37);
		$this->datos['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash');

		if ($this->form_validation->run() == TRUE)
		{
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if($new_group_id)
			{
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth", 'refresh');
			}
		}
		else
		{
			// display the create group form
			// set the flash datos error message if there is one
			$this->datos['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->datos['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
				'placeholder' => 'Nombre de grupo',//modificado para bootstrap
                'class' => 'form-control',//modificado para bootstrap
			);
			$this->datos['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
				'placeholder' => 'Descripcion',//modificado para bootstrap
                'class' => 'form-control',//modificado para bootstrap
			);
			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;

			$this->load->view('plantilla/head.php',$this->datos);
			$this->load->view('plantilla/header.php',$this->datos);
			$this->load->view('plantilla/menu.php',$this->datos);
			$this->_render_page('auth/create_group', $this->datos);
			$this->load->view('plantilla/footer.php',$this->datos);

			
		}
	}

	// edit a group
	public function edit_group($id)
	{
		// bail if no group id given
		if(!$id || empty($id))
		{
			redirect('auth', 'refresh');
		}

		$this->datos['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if($group_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
				redirect("auth", 'refresh');
			}
		}

		// set the flash datos error message if there is one
		$this->datos['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->datos['group'] = $group;

		$readonly = $this->config->item('admin_group', 'ion_auth') === $group->name ? 'readonly' : '';

		$this->datos['group_name'] = array(
			'name'    => 'group_name',
			'id'      => 'group_name',
			'type'    => 'text',
			'value'   => $this->form_validation->set_value('group_name', $group->name),
			$readonly => $readonly,
			'placeholder' => 'Nombre de grupo',//modificado para bootstrap
            'class' => 'form-control',//modificado para bootstrap
		);
		$this->datos['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
			'placeholder' => 'Descripcion',//modificado para bootstrap
            'class' => 'form-control',//modificado para bootstrap
		);

			$this->datos['cabeceras_css']= $this->cabeceras_css;
			$this->datos['cabeceras_script']= $this->cabecera_script;
            
           
			$this->setView('auth/edit_group');
	}


	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	public function _valid_csrf_nonce()
	{
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function _render_page($view, $datos=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($datos)) ? $this->datos: $datos;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
	/*******************************FIN*************************/
	public function subir_imagen($id)
	{

		//$ruta= dirname(getcwd()) . PHP_EOL; //ruta de la carpeta en el servidor
		//$carpetaAdjunta="assets/\imagenes//\\";
		$ruta= getcwd();
		$ruta=trim($ruta);
		$carpetaAdjunta=$ruta."/assets/imagenes/";
		// Contar envían por el plugin
		$Imagenes =count(isset($_FILES['imagenes']['name'])?$_FILES['imagenes']['name']:0);
		$infoImagenesSubidas = array();
		$nombreArchivo="";
		
		for($i = 0; $i < $Imagenes; $i++) {


		  // El nombre y nombre temporal del archivo que vamos para adjuntar
		  $nombreArchivo=isset($_FILES['imagenes']['name'][$i])?$_FILES['imagenes']['name'][$i]:null;
		  $nombreTemporal=isset($_FILES['imagenes']['tmp_name'][$i])?$_FILES['imagenes']['tmp_name'][$i]:null;
		  
		  $rutaArchivo=$carpetaAdjunta.$nombreArchivo;

		  move_uploaded_file($nombreTemporal,$rutaArchivo);
		  
		  //$infoImagenesSubidas[$i]=array("caption"=>"$nombreArchivo","height"=>"120px","url"=>"http://localhost/hergo/up/borrar.php","key"=>$nombreArchivo);
		  //$ImagenesSubidas[$i]="<img  height='120px'  src='http://localhost/hergo/imagenes/$rutaArchivo' class='file-preview-image'>";
		  }
		/*$arr = array("file_id"=>0,"overwriteInitial"=>true,"initialPreviewConfig"=>$infoImagenesSubidas,
		       "initialPreview"=>$ImagenesSubidas);*/
		
		$this->ion_auth_model->guardar_foto_model($nombreArchivo,$id);
		return($nombreArchivo);

		//echo json_encode($arr);
	}

}
