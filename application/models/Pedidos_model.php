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
    public function storeOrden($id, $orden)
	{	
        if ($id) {
            $this->db->trans_start();
                $this->db->where('id', $id);
                $this->db->update('ordenescompra', $orden);
            $this->db->trans_complete();
            return ( $this->db->trans_status() === FALSE ) ? false : $id;
        } else {
            $this->db->trans_start();
                $this->db->insert("ordenescompra", $orden);
                $id=$this->db->insert_id();
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
    public function getPedidos($ini, $fin, $condicion)
	{ 
    	$sql=" SELECT p.`id` id_pedido, p.`n` nPedido, p.`fecha`, p.`recepcion`, p.`formaPago`, p.`pedidoPor`,pro.`nombreproveedor` proveedor, CONCAT(u.`first_name`, ' ', u.`last_name`) autor, oc.`id` id_ordenCompra, items.total$, items.totalBOB,
                COUNT(*) nAprobados, p.`created_at` created_at_pedido,
                oc.`id`id_ordenCompra, oc.`n` nOrden,  oc.`fecha` fechaOC, pro.`direccion`, pro.`telefono` fono, pro.`fax` fax, oc.created_at created_at_orden,
		        oc.`atencion`, oc.`referencia`, oc.`condicion`, oc.`formaEnvio`, p.`formaPago`, CONCAT(u.`first_name`, ' ', u.`last_name`) autorOC
            FROM pedidos_aprobado pa
            RIGHT JOIN pedidos p ON p.`id` = pa.`id_pedido`
                LEFT JOIN ordenescompra oc ON oc.`id_pedido` = p.`id`
                INNER JOIN provedores pro ON pro.`idproveedor` = p.`proveedor`
                INNER JOIN users u ON u.`id` = p.`autor`
                INNER JOIN (SELECT pit.`idPedido`, SUM(pit.`cantidad` * pit.`precioFabrica`) total$, SUM(pit.`cantidad` * pit.`precioFabrica` * tc.`tipocambio`) totalBOB
                        FROM pedidos_items pit
                        INNER JOIN pedidos p ON p.`id` = pit.`idPedido`
                        INNER JOIN `tipocambio` tc ON tc.`fecha` = p.`fecha`
                        GROUP BY pit.`idPedido`
                        )items 
                ON items.idPedido = p.`id`
            GROUP BY p.`id`
            HAVING  
                CASE
                    WHEN '$condicion' = 'todos' THEN p.`fecha` BETWEEN '$ini' AND '$fin' 
                    WHEN '$condicion' = 'aprobadosNoOrden' THEN p.`fecha` BETWEEN '$ini' AND '$fin' AND COUNT(*) > 2  AND oc.`id` IS  NULL
                    WHEN '$condicion' = 'ordenProcess' THEN p.`fecha` BETWEEN '$ini' AND '$fin' AND COUNT(*) > 2  AND oc.`id` IS NOT NULL
                END                
            ORDER BY  p.`n` DESC
            ";

        $query=$this->db->query($sql);	
		return $query->result_array();
    }
    public function getPedido($id)
	{ 
    	$sql="SELECT p.id, p.`n`, p.`fecha`, p.`recepcion`,pro.`idproveedor` idProv,  pro.`nombreproveedor` proveedor, p.`pedidoPor`, p.`cotizacion`, p.`formaPago` idFP,  p.`formaPago`, p.`glosa`,
        pro.`direccion`, pro.`telefono`, pro.`fax`
        FROM pedidos p
        INNER JOIN provedores pro ON pro.`idproveedor` = p.`proveedor`
        WHERE p.`id` = '$id'";

        $query=$this->db->query($sql)->row();		
		return $query;
    }
    public function getOrden($id)
	{ 
    	$sql="SELECT oc.`id`, oc.`n`, oc.`fecha`, pro.`nombreproveedor`, pro.`telefono`, pro.`fax`, oc.`atencion`, pro.`direccion`, oc.`referencia`, 
        oc.`condicion`, oc.`formaEnvio`, p.`formaPago`,oc.`glosa`, CONCAT(u.`first_name`, ' ',u.`last_name`) autor
        FROM ordenescompra oc 
        INNER JOIN pedidos p ON p.`id` = oc.`id_pedido`
        INNER JOIN provedores pro ON pro.`idproveedor` = p.`proveedor`
        INNER JOIN users u ON u.`id` = oc.`created_by`
        WHERE oc.`id` ='$id'";

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
    public function getOrdenItems($id)
	{ 
    	$sql="SELECT a.`idArticulos` id, a.`CodigoArticulo` codigo, a.`NumParte` numParte, a.`detalleLargo` descripFabrica, a.`Descripcion` descripcion,
        u.`Unidad` unidad, pit.`saldo`, pit.`rotacion`, pit.`precio`, pit.`cantidad`, pit.`precioFabrica`,
        (pit.`cantidad`* pit.`precioFabrica`) total
        FROM pedidos_items pit
        INNER JOIN articulos a ON a.`idArticulos` = pit.`articulo`
        INNER JOIN unidad u ON a.`idUnidad` = u.`idUnidad`
        INNER JOIN ordenescompra oc ON oc.`id_pedido` = pit.`idPedido`
        WHERE oc.`id` = '$id'";

        $query=$this->db->query($sql)->result();		
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
        FROM ACCESO_USUARIO au
        INNER JOIN ACCESO_SUBMENU sub ON sub.`id` = au.`subMenu`
        INNER JOIN ACCESO_MENU menu ON menu.`id` = sub.`idMenu`
        WHERE au.`idUsuario` = $user
        ORDER BY au.`subMenu`";

        $query=$this->db->query($sql)->result_array();		
		return $query;
    }
    public function getNumMovOrden($gestion)
    {
        $sql="SELECT oc.`n`+1 AS numDoc
		FROM ordenescompra oc
		WHERE YEAR(oc.fecha) = '$gestion'
		ORDER BY oc.n DESC LIMIT 1";
        
		$numDoc=$this->db->query($sql);
		
		return $numDoc->row() ? $numDoc->row()->numDoc : 1;
    }
    
}
