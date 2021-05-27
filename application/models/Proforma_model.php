<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proforma_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}


    public function storeProforma($id, $proforma)
	{	
        if ($id) {
            $this->db->trans_start();
                $this->db->where('id', $id);
                $this->db->update('proforma', $proforma);
                
                $this->db->where('proforma_id', $id);
                $this->db->delete('proforma_items');
                $this->storeItems($id, $proforma);

            $this->db->trans_complete();
            return ( $this->db->trans_status() === FALSE ) ? false : $id;
        } else {
            $this->db->trans_start();
                $this->db->insert("proforma", $proforma);
                $id=$this->db->insert_id();

                $this->storeItems($id, $proforma);
            $this->db->trans_complete();
            return ( $this->db->trans_status() === FALSE ) ? false : $id;
        }
    }
   
  
    public function storeItems($id, $proforma)
    {
        $itemsArray = array();
        foreach ($proforma->items as $fila) {
            $item=new stdclass();
            $item->proforma_id = $id;
            $item->articulo_id = $fila->id;
            $item->descripcion = strtoupper($fila->descrip);
            $item->cantidad = round($fila->cantidad,2);
            $item->precio = round($fila->precioLista,2);
            $item->total = round($fila->total,2);

            array_push($itemsArray,$item);	
        }
        $this->db->insert_batch("proforma_items", $itemsArray);
    }
    


    public function getNumMov($gestion)
    {
        $sql="  SELECT
                    p.num + 1 AS numDoc
                FROM
                    proforma p
                WHERE
                    YEAR(p.fecha) = $gestion
                ORDER BY
                    p.num DESC
                LIMIT 1";
        
		$numDoc=$this->db->query($sql);
		
		return $numDoc->row() ? $numDoc->row()->numDoc : 1;
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
    public function searchItem($item,$alm)
    {
		$sql="  SELECT
                    a.idArticulos id,
                    CONCAT(a.CodigoArticulo, ' | ' , a.Descripcion) label,
                    a.CodigoArticulo codigo,
                    a.Descripcion descrip,
                    m.Marca marca,
                    u.Unidad uni,
                    a.precio,
                    a.precioDol,
                    sa.saldo,
                    a.Imagen img,
                    l.Linea linea 
                FROM
                    articulos a
                INNER JOIN unidad u on u.idUnidad = a.idUnidad
                inner join marca m on m.idMarca = a.idMarca
                inner join linea l on l.idLinea = a.idLinea
                left join saldoarticulos sa on sa.idArticulo = a.idArticulos and sa.idAlmacen = '$alm'
                where
                    a.CodigoArticulo like '%$item%'
                    or a.Descripcion like '%$item%'
                    AND a.EnUso = 1
                ORDER By
                    a.CodigoArticulo
                LIMIT
                    50";
		$query=$this->db->query($sql);
		return $query;
    }
    public function searchClientes($search)
    {
		$sql="  SELECT
                    c.`idCliente` id ,
                    CONCAT(c.`nombreCliente`, ' | ' , c.`documento`) label
                    
                FROM
                    clientes c
                where
                    nombreCliente LIKE '%$search%'
                    OR documento LIKE '%$search%'
                ORDER By nombreCliente asc
                LIMIT 50";
		$query=$this->db->query($sql);
		return $query;
    }
}
