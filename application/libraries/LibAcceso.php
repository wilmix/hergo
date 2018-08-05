<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');

class LibAcceso {
	
    public function acceso($SubMenu)
    {
     
	    $aux=$this->retornarSubMenus($_SESSION['accesoMenu']);
	    if(in_array($SubMenu, $aux))
	    {
	 
			return true;
	    }	    	
		else	
		{			
			show_error('No tiene permiso para ingresar a esta opción','500','Atención!');
			return false;		

		}
    }

   public function retornarSubMenus($submenues)
	{
		$submenu = array();
		foreach ($submenues as $row ) {
			array_push($submenu,$row['subMenu']);
		}
		return $submenu;
	}

}

?>