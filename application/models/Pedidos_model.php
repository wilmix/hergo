<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedidos_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}


    public function storePedido($id, $pedido)
	{	
        if ($id) {
            $this->db->trans_start();
                $this->db->where('id', $id);
                $this->db->update('pedidos', $pedido);
                
                $this->db->where('idPedido', $id);
                $this->db->delete('pedidos_items');
                $this->storeItems($id, $pedido);

            $this->db->trans_complete();
            return ( $this->db->trans_status() === FALSE ) ? false : $id;
        } else {
            $this->db->trans_start();
                $this->db->insert("pedidos", $pedido);
                $id=$this->db->insert_id();

                $this->storeItems($id, $pedido);
            $this->db->trans_complete();
            return ( $this->db->trans_status() === FALSE ) ? false : $id;
        }
    }
    public function storeItems($id, $pedido)
    {
        $itemsArray = array();
        foreach ($pedido->items as $fila) {
            $item=new stdclass();
            $item->idPedido = $id;
            $item->articulo = $fila->id;
            $item->cantidad = $fila->cantidad;
            $item->precioFabrica = $fila->precioFabrica;
            $item->saldo = $fila->saldo;
            $item->rotacion = $fila->rotacion;
            $item->precio = $fila->precio;
            array_push($itemsArray,$item);	
        }
        $this->db->insert_batch("pedidos_items", $itemsArray);
    }
    public function aprobar($aprobar)
	{	
        $this->db->trans_start();
            $this->db->insert("pedidos_aprobado", $aprobar);
            $id=$this->db->insert_id();
        $this->db->trans_complete();
        return ( $this->db->trans_status() === FALSE ) ? false : $id;
    }
    public function getPedidos($ini, $fin)
	{ 
    	$sql="SELECT ped.id, ped.`n`, ped.`fecha`, ped.`recepcion`, ped.proveedor, ped.formaPago, ped.pedidoPor, ped.created_at, ped.total$, ped.totalBOB, ped.autor, COUNT(pa.`id_user`) nAprobados 
                FROM
                (
                    SELECT p.id, p.`n`, p.`fecha`, p.`recepcion`, pro.`nombreproveedor` proveedor, p.`pedidoPor`, IF(p.`formaPago`,'CREDITO','EFECTIVO') formaPago, p.`created_at`,
                    SUM(pit.`cantidad` * pit.`precioFabrica`) total$, SUM(pit.`cantidad` * pit.`precioFabrica` *tc.`tipocambio`) totalBOB,
                    CONCAT(u.`first_name`,' ',u.`last_name`) autor
                    FROM pedidos p
                        INNER JOIN provedores pro ON pro.`idproveedor` = p.`proveedor`
                        INNER JOIN users u ON u.`id` = p.`autor`
                        INNER JOIN pedidos_items pit ON pit.`idPedido` = p.`id`
                        INNER JOIN tipocambio tc ON tc.`fecha` = p.`fecha`
                    WHERE p.`fecha` BETWEEN '$ini' AND '$fin'
                    GROUP BY p.`id`
                )ped
                    LEFT JOIN pedidos_aprobado pa ON pa.`id_pedido` = ped.id
                    GROUP BY ped.id
                    ORDER BY  ped.n DESC
            ";

        $query=$this->db->query($sql);	
		return $query->result_array();
    }
    public function getPedido($id)
	{ 
    	$sql="SELECT p.id, p.`n`, p.`fecha`, p.`recepcion`,pro.`idproveedor` idProv,  pro.`nombreproveedor` proveedor, p.`pedidoPor`, p.`cotizacion`, p.`formaPago` idFP,  IF(p.`formaPago`,'CREDITO','EFECTIVO') formaPago, p.`glosa`
        FROM pedidos p
        INNER JOIN provedores pro ON pro.`idproveedor` = p.`proveedor`
        WHERE p.`id` = '$id'";

        $query=$this->db->query($sql)->row();		
		return $query;
    }
    public function getPedidoItems($id)
	{ 
    	$sql="SELECT a.`idArticulos` id, a.`CodigoArticulo` codigo, a.`NumParte` numParte, a.`detalleLargo` descripFabrica, a.`Descripcion` descripcion,
        u.`Unidad` unidad, pit.`saldo`, pit.`rotacion`, pit.`precio`, pit.`cantidad`, pit.`precioFabrica`,
        (pit.`cantidad`* pit.`precioFabrica`) total
        FROM pedidos_items pit
        INNER JOIN articulos a ON a.`idArticulos` = pit.`articulo`
        INNER JOIN unidad u ON a.`idUnidad` = u.`idUnidad`
        WHERE pit.`idPedido` = '$id'";

        $query=$this->db->query($sql)->result_array();		
		return $query;
    }
    public function getAprobadoPor($id)
	{ 
    	$sql="SELECT  pa.`id_pedido`, pa.`id_user`, CONCAT(upa.`first_name`, ' ',upa.`last_name`) aprobado_por, pa.`created_at`
        FROM pedidos_aprobado pa
        INNER JOIN users upa ON upa.`id` = pa.`id_user`
        INNER JOIN pedidos ppa	ON ppa.`id` = pa.`id_pedido`
        WHERE pa.`id_pedido` = '$id'";

        $query=$this->db->query($sql)->result_array();		
		return $query;
    }
    public function getAprobadoUser($id, $user)
    {
        $sql="SELECT *
        FROM pedidos_aprobado pa
        WHERE pa.`id_pedido` = '$id'
        AND pa.`id_user` = '$user' ";

        $query=$this->db->query($sql)->row();		
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
    public function getPermisos($user)
	{ 
    	$sql="SELECT au.`subMenu` id_sub, sub.`subMenu`
        FROM acceso_usuario au
        INNER JOIN acceso_submenu sub ON sub.`id` = au.`subMenu`
        INNER JOIN acceso_menu menu ON menu.`id` = sub.`idMenu`
        WHERE au.`idUsuario` = $user
        ORDER BY au.`subMenu`";

        $query=$this->db->query($sql)->result_array();		
		return $query;
    }
    
}
