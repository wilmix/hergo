<?php 
	
	class provedores_model extends CI_Model
	{
		
		function __construct()
		{
			parent::__construct();
		}

		//metodos de consulta a la base de datos que utilizara active record
		public function getTodos()
		{
			$query=$this->db
						->select("*")
						->from("provedores")
						->order_by("idproveedor","desc")
						->get();
			//***para ver esquema de la consulta
			//echo $this->db->last_query();exit;
			return $query->result();

		}
	


	}




 ?>