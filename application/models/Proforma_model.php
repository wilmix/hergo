<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proforma_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('date');
		date_default_timezone_set("America/La_Paz");
	}
    public function getProformas($ini, $fin, $alm)
	{ 
    	$sql="      SELECT
                        p.id,
                        a.almacen,
                        p.fecha,
                        p.num,
                        c.nombreCliente,
                        p.clienteDatos,
                        m.sigla,
                        p.condicionesPago,
                        p.validezOferta,
                        p.lugarEntrega,
                        p.total,
                        pt.tipo,
                        p.porcentajeDescuento,
                        concat(u.first_name, ' ', u.last_name) autor,
                        u.email,
                        p.created_at
                    FROM
                        proforma p
                        INNER JOIN almacenes a ON a.idalmacen = p.almacen
                        INNER JOIN clientes c on c.idCliente = p.cliente
                        inner join moneda m on m.id = p.moneda
                        inner join users u on u.id = p.autor
                        INNER JOIN 	proforma_tipo pt on pt.id = p.tipo
                    WHERE
                    p.fecha BETWEEN '$ini' and '$fin'
                    and p.almacen = '$alm'
                    ORDER BY p.id DESC
        ";

        $query=$this->db->query($sql);	
		return $query->result_array();
    }
    public function getProforma($id)
	{ 
    	$sql="      SELECT
                        p.id,
                        a.almacen,
                        a.idalmacen idAlm,
                        a.address,
                        a.phone ,
                        a.ciudad,
                        p.fecha,
                        p.num,
                        p.clienteDatos,
                        p.complemento,
                        c.idCliente,
                        c.nombreCliente clienteNombre,
                        p.complemento,
                        c.direccion clienteDirec,
                        c.telefono clienteTelefono,
                        c.email clienteEmail,
                        m.sigla,
                        p.condicionesPago,
                        p.validezOferta,
                        p.lugarEntrega,
                        p.tiempoEntrega tiempoEntregaC,
                        p.garantia,
                        p.total,
                        m.moneda,
                        m.id idMoneda,
                        pt.tipo,
                        pt.id idTipo,
                        p.porcentajeDescuento,
                        p.descuento,
                        concat(u.first_name, ' ', u.last_name) autorNombre,
                        u.email autorEmail,
                        u.phone autorPhone,
                        u.firma firmaAutor,
                        p.glosa,
                        p.created_at
                    FROM
                        proforma p
                        INNER JOIN almacenes a ON a.idalmacen = p.almacen
                        INNER JOIN clientes c on c.idCliente = p.cliente
                        inner join moneda m on m.id = p.moneda
                        inner join users u on u.id = p.autor
                        INNER JOIN 	proforma_tipo pt on pt.id = p.tipo
                    WHERE
                    p.id = '$id'
            ";

        $query=$this->db->query($sql);	
		return $query->row();
    }
    public function getProformaItems($id)
	{ 
    	$sql="      SELECT
                        a.idArticulos id,
                        a.CodigoArticulo codigo,
                        u.Unidad uni,
                        pit.marca marca,
                        pit.descripcion descrip,
                        pit.cantidad,
                        pit.marca marcaSigla,
                        pit.tiempoEntrega,
                        pit.industria,
                        pit.precio precioLista,
                        pit.total,
                        a.Imagen img,
                        a.ImagenUrl imgUrl
                        
                    FROM
                        proforma_items pit
                        INNER join articulos a on a.idArticulos = pit.articulo_id
                        inner join unidad u on u.idUnidad = a.idUnidad
                        INNER join marca m on m.idMarca = a.idMarca
                        
                    WHERE
                        pit.proforma_id = '$id'
            ";

        $query=$this->db->query($sql);	
		return $query->result();
    }


    public function checkAlmacenExists($id) {
        return $this->db->where('idalmacen', $id)
                       ->get('almacenes')
                       ->num_rows() > 0;
    }

    public function checkMonedaExists($id) {
        return $this->db->where('id', $id)
                       ->get('moneda')
                       ->num_rows() > 0;
    }

    public function checkTipoExists($id) {
        return $this->db->where('id', $id)
                       ->get('proforma_tipo')
                       ->num_rows() > 0;
    }

    public function checkArticuloExists($id) {
        return $this->db->where('idArticulos', $id)
                       ->get('articulos')
                       ->num_rows() > 0;
    }

    public function storeProforma($id, $proforma) {
        try {
            $this->db->trans_start();

            $items = $proforma->items;
            unset($proforma->items); // Remove items before inserting main record

            if ($id > 0) {
                $this->db->where('id', $id);
                $this->db->update('proforma', $proforma);
                
                // Delete existing items
                $this->db->where('proforma_id', $id);
                $this->db->delete('proforma_items');
            } else {
                $this->db->insert('proforma', $proforma);
                $id = $this->db->insert_id();
            }

            // Store items
            if (!empty($items)) {
                $this->storeItems($id, (object)['items' => $items]);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Error en la transacción de base de datos');
            }

            return $id;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Error al guardar proforma: ' . $e->getMessage());
            return false;
        }
    }
    public function updateProforma($id, $proforma){
        $this->db->trans_start();
            $this->db->where('id', $id);
            $this->db->update('proforma', $proforma);
        $this->db->trans_complete();
        return ( $this->db->trans_status() === FALSE ) ? false : $id;
    }
  
    public function storeItems($id, $proforma)
    {
        $itemsArray = array();
        foreach ($proforma->items as $fila) {
            $item=new stdclass();
            $item->proforma_id = $id;
            $item->articulo_id = $fila->id;
            $item->descripcion = strtoupper($fila->descrip);
            $item->tiempoEntrega = strtoupper($fila->tiempoEntrega);
            $item->marca = strtoupper($fila->marcaSigla);
            $item->industria = strtoupper($fila->industria);
            $item->cantidad = round($fila->cantidad,2);
            $item->precio = round($fila->precioLista,2);
            $item->total = round($fila->total,2);

            array_push($itemsArray,$item);	
        }
        $this->db->insert_batch("proforma_items", $itemsArray);
    }
    


    public function getNumMov($gestion,$alm)
    {
        $sql="  SELECT
                    p.num + 1 AS numDoc
                FROM
                    proforma p
                WHERE
                    YEAR(p.fecha) = '$gestion'
                    AND p.almacen = '$alm'
                ORDER BY
                    p.num DESC
                LIMIT 1";
        
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

    public function getTipos()
	{ 
    	$sql="      SELECT
                        pt.id value,
                        pt.tipo tipo 
                    from
                        proforma_tipo pt
            ";
        $query=$this->db->query($sql);	
		return $query->result_array();
    }
    public function getAlmacenes()
	{ 
    	$sql="      SELECT
                    a.idalmacen value,
                    a.almacen alm 
                    from almacenes a 
                        ";
        $query=$this->db->query($sql);	
		return $query->result_array();
    }
    public function getMonedas()
	{ 
    	$sql="      SELECT
                    m.id 'value',
                    m.moneda 
                    from moneda m";
        $query=$this->db->query($sql);	
		return $query->result_array();
    }
    public function getArticulos()
	{ 
    	$sql="      SELECT
                        a.idArticulos value,
                        a.CodigoArticulo codigo,
                        a.Descripcion descp,
                        concat(a.CodigoArticulo, ' | ', a.Descripcion) label
                    FROM
                        articulos a
                    WHERE
                        a.EnUso = 1";
        $query=$this->db->query($sql);	
		return $query->result_array();
    }
    public function getArticulo($id, $alm)
	{ 
    	$sql="      SELECT
                        a.idArticulos id,
                        a.CodigoArticulo codigo,
                        CONCAT(a.CodigoArticulo, ' | ' , a.Descripcion) label,
                        a.Descripcion descrip,
                        m.Marca marca,
                        m.Sigla marcaSigla,
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
                        left join saldoarticulos sa on sa.idArticulo = a.idArticulos
                        and sa.idAlmacen = '$alm'
                    where
                        a.idArticulos = '$id'";
        $query=$this->db->query($sql);	
		return $query->row();
    }


}
