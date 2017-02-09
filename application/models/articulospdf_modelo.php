<?php
class articulospdf_modelo extends CI_Model {         //**********cambiar al mismo nombre de archivo
 
    function __construct()
    {
        parent::__construct();
    }
 
    /* Devuelve la lista de articulos que se encuentran en la tabla articulos */
    function obtenerListaArticulos()     //********cambiar nombre de funcion
    {
     $this->load->database(); //ok
     $sql="SELECT a.idArticulos, a.CodigoArticulo, a.Descripcion, a.NumParte, u.Unidad, m.Marca, l.Linea, a.PosicionArancelaria, r.Requisito, a.ProductoServicio, a.detalleLargo, a.EnUso,a.Imagen
            FROM articulos a
            INNER JOIN unidad u
            ON a.idUnidad = u.idUnidad
            INNER JOIN marca m
            ON a.idMarca = m.idMarca
            INNER JOIN linea l
            ON a.idLinea = l.idLinea
            INNER JOIN requisito r
            ON a.idRequisito = r.idRequisito
            ORDER BY a.CodigoArticulo asc ";
        
        $lineas=$this->db->query($sql);
   
     return $lineas->result();//ok
    }
}

?>



