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

    public function getPedidos($ini, $fin)
	{ 
    	$sql="SELECT p.id, p.`n`, p.`fecha`, p.`recepcion`, pro.`nombreproveedor` proveedor, p.`pedidoPor`, IF(p.`formaPago`,'CREDITO','EFECTIVO') formaPago, p.`created_at`,
        SUM(pit.`cantidad` * pit.`precioFabrica`) total$, SUM(pit.`cantidad` * pit.`precioFabrica` *tc.`tipocambio`) totalBOB,
            CONCAT(u.`first_name`,' ',U.`last_name`) autor
            FROM pedidos p
            INNER JOIN provedores pro ON pro.`idproveedor` = p.`proveedor`
            INNER JOIN users u ON u.`id` = p.`autor`
            INNER JOIN pedidos_items pit ON pit.`idPedido` = p.`id`
            INNER JOIN tipocambio tc ON tc.`fecha` = p.`fecha`
            WHERE p.`fecha` BETWEEN '$ini' AND '$fin'
            GROUP BY p.`id`
            ORDER BY P.`n` DESC
            ";

        $query=$this->db->query($sql);		
		return $query;
    }
    public function getNumMov($gestion)
    {
        $sql="SELECT p.`n`+1 AS numDoc
		FROM pedidos p
		WHERE YEAR(p.`fecha`) = $gestion
		ORDER BY p.`n` DESC LIMIT 1";
        
		$numDoc=$this->db->query($sql);
		
		return $numDoc->row() ? $numDoc->row()->numDoc : 1;
    }
    
}
