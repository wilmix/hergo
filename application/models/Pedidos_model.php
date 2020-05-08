<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedidos_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}


    public function storePedido($pedido)
	{	
        $this->db->trans_start();
            $this->db->insert("pedidos", $pedido);
            $idPedido=$this->db->insert_id();
            $itemsArray = array();
                foreach ($pedido->items as $fila) {
                    $item=new stdclass();
                    $item->idPedido = $idPedido;
                    $item->articulo = $fila->id;
                    $item->cantidad = $fila->cantidad;
                    $item->precioFabrica = $fila->precioFabrica;
                    array_push($itemsArray,$item);	
                }
            $this->db->insert_batch("pedidos_items", $itemsArray);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        } else {
            
            return $idPedido;
        }
    }
    
}
