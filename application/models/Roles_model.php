<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Roles_model extends CI_Model  ////////////***** nombre del modelo 
{
	public function __construct()
	{	
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
	public function retornar_tabla($tabla)
	{
		$sql="SELECT * from $tabla";
		
		$query=$this->db->query($sql);		
		return $query;
    }
    public function retornar_users()
	{
		$sql="SELECT u.id, u.username, CONCAT(u.`first_name`, ' ', u.`last_name`) nombre, u.`almacen`, u.`foto`
        FROM users u";
		$query=$this->db->query($sql);		
		return $query;
	}
	public function mostrarRoles( $idUser) 
	{ 
		$sql="SELECT menu.`menu`, sub.`id` idSubMenu, sub.`subMenu`, IFNULL(usuario.idUsuario,'') activo
        FROM ACCESO_SUBMENU sub
        INNER JOIN ACCESO_MENU menu ON menu.`id` = sub.`idMenu`
        LEFT JOIN 	(SELECT au.subMenu idSubMenu, idUsuario
                FROM ACCESO_USUARIO au 
                WHERE au.idUsuario = $idUser) usuario
        ON usuario.idSubMenu = sub.`id`";
		$query=$this->db->query($sql);		
		return $query;
    }
    public function activar( $idUser, $idSub) 
	{ 
		$sql="INSERT INTO ACCESO_USUARIO (subMenu, idUsuario)
        VALUES ($idSub,$idUser)";
		$this->db->query($sql);		
    }
    public function desActivar( $idUser, $idSub) 
	{ 
		$sql="DELETE FROM ACCESO_USUARIO
        WHERE subMenu=$idSub AND idUsuario=$idUser";
		$this->db->query($sql);		
	}
}
