<?php
    require_once APPPATH."/third_party/fpdf/fpdf.php";
class ingresopdf_model extends CI_Model {         //**********cambiar al mismo nombre de archivo
 
    function __construct()
    {
        parent::__construct();
    }

    public function mostrarIngresos()
    {
        
        $sql="SELECT i.nmov n,i.idIngresos,t.sigla,t.tipomov, DATE_FORMAT(i.fechamov,'%d/%m/%Y') fechamov, p.nombreproveedor, i.nfact,
                (SELECT FORMAT(SUM(d.total),2) from ingdetalle d where  d.idIngreso=i.idIngresos) total, i.estado,i.fecha, CONCAT(u.last_name,' ', u.first_name) autor, i.moneda, a.almacen, m.sigla monedasigla, i.ordcomp,i.ningalm
            FROM ingresos i
            INNER JOIN tmovimiento  t
            ON i.tipomov = t.id
            INNER JOIN provedores p
            ON i.proveedor=p.idproveedor
            INNER JOIN users u
            ON u.id=i.autor
            INNER JOIN almacenes a
            ON a.idalmacen=i.almacen
            INNER JOIN moneda m
            ON i.moneda=m.id
            ORDER BY i.idIngresos DESC";
        
        $lineas =$this->db->query($sql);      
        return $lineas->result();
    }
 

}

?>



