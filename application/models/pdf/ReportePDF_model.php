<?php
    require_once APPPATH."/third_party/fpdf/fpdf.php";
class ReportePDF_model extends CI_Model {         //**********cambiar al mismo nombre de archivo
 
    function __construct()
    {
        parent::__construct();
    }

    public function comprasPdf($month,$year)
    {
        
        $sql=" SELECT
                '1' e,
                c.fecha,
                c.nit,
                c.razonSocial,
                c.nFactura,
                c.nDUI,
                c.nAut,
                c.total,
                c.noSujetoCF,
                (c.total-c.noSujetoCF) subtotal,
                c.descuento,
                (c.total-c.noSujetoCF-c.descuento) base,
                round((c.total-c.noSujetoCF-c.descuento)*13/100,2)credito,
                c.codigoControl,
                c.tipo
        from
            compras c 
        where
            year(c.fecha) = '$year'
            and MONTH(c.fecha) = '$month'";
        
        $lineas =$this->db->query($sql);      
        return $lineas->result();
    }
    public function ventasNotario($alm,$month,$year)
    {
        $sql="  SELECT
                    *,
                    round(vn.totalVenta*13/100,2) debito
                FROM
                    ventasNotario vn 
                WHERE
                    year(vn.fecha) = '$year'
                    and month(vn.fecha) = '$month'
                    and vn.almacen = '$alm'
                ";

        $lineas =$this->db->query($sql);      
        return $lineas->result();
    }
    public function getAlmacen($alm)
    {
        $sql="  SELECT
                    a.sucursal,
                    a.address,
                    a.ciudad
                from
                    almacenes a
                where
                    a.idalmacen = '$alm'
                ";

        $lineas =$this->db->query($sql);      
        return $lineas->row();
    }
 

}

?>



