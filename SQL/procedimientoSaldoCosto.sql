set @_cantidadSaldo=0.0;
set @_punitarioSaldo=0.0;
set @_totalSaldo=0.0;

DROP TABLE IF EXISTS tmp_tabla;
CREATE TEMPORARY TABLE tmp_tabla AS(
 SELECT *,@_cantidadSaldo:=
   IF(cantidadIngreso, @_cantidadSaldo+cantidadIngreso, @_cantidadSaldo-cantidadEgreso) AS cantidadSaldo,
   @_punitarioSaldo:=
   ROUND(
   IF (totalIngreso,
        IF(@_totalSaldo,
            (@_totalSaldo+totalIngreso)/@_cantidadSaldo
            ,punitarioIngreso            
          )        
        ,@_punitarioSaldo
      )
    ,3 )AS punitarioSaldo,
    @_totalSaldo:=
   ROUND(@_cantidadSaldo*@_punitarioSaldo,3) AS totalSaldo 
      FROM (
          SELECT e.idingdetalle,e.idegreso,e1.fechamov, e.articulo,e1.almacen,NULL cantidadIngreso, NULL punitarioIngreso, NULL totalIngreso, e.cantidad cantidadEgreso,e.punitario punitarioEgreso, ROUND(e.cantidad*e.punitario,3) totalEgreso
          FROM egredetalle e
          INNER JOIN egresos e1
          ON  e.idegreso=e1.idegresos
          WHERE year(e1.fechamov)=year(NOW())
          AND e1.anulado=0
          AND e.articulo=59
        UNION
          SELECT i.idIngdetalle,i.idIngreso,i1.fechamov, i.articulo,i1.almacen, i.cantidad cantidadIngreso,i.punitario punitarioIngreso, ROUND(i.cantidad*i.punitario,3) totalIngreso, NULL cantidadEgreso, NULL punitarioEgreso, NULL totalEgreso
          FROM ingdetalle i
          INNER JOIN ingresos i1 
          ON  i.idIngreso=i1.idIngresos
          WHERE year(i1.fechamov)=year(NOW())
          AND i1.anulado=0
          AND i.articulo=59
          ORDER BY  fechamov
       ) AS CONSULTA
);
SELECT * FROM tmp_tabla


/************kardex********************/
SET @idArt := 979;
SET @alm := 1;
/** ingresos **/
	SELECT 	 i.`almacen`,
		 p.`nombreproveedor`,
		 IF(i.`tipomov`=1,0,i.`fechamov`) AS fecha,
		 tm.`sigla` tipo,
		 i.`nmov` AS numMov,
		 id.`punitario`,
		 id.`cantidad`,
		 tm.`operacion`,
		 tm.`id`
	FROM ingdetalle id
		INNER JOIN ingresos i
		    ON i.`idIngresos`=id.`idIngreso`
		INNER JOIN tmovimiento tm
		    ON tm.`id`=i.`tipomov`
		INNER JOIN provedores p
		    ON p.`idproveedor`=i.`proveedor`
	WHERE YEAR(i.`fechamov`)=YEAR(NOW()) /*gestion actual*/
		AND i.`almacen`=@alm
		AND i.`anulado`=0
		and i.`estado`=1
		AND id.`articulo`=@idArt
UNION ALL 
/** FACTURA **/
	SELECT  f.almacen,
		c.nombreCliente,
		`fechaFac`,
		"FAC" ,
		f.`nFactura`,
		fd.`facturaPUnitario`,
		fd.`facturaCantidad`,
		"-",
		10
	FROM    facturadetalle fd
		INNER JOIN factura f
		    ON f.`idFactura`=fd.`idFactura`
		INNER JOIN clientes c
		    ON c.idCliente=f.cliente
	WHERE YEAR(f.`fechaFac`)=YEAR(NOW())/*gestion actual*/
		AND f.`almacen`=@alm
		AND f.`anulada`=0
		AND fd.`articulo`=@idArt
UNION ALL 
/** NOTA ENTREGA **/
	SELECT 	 e.almacen,
		 c.nombreCliente,
		 `fechamov`,
		 tm.`sigla`,
		 e.`nmov`,
		 ed.`punitario`,
		 ed.`cantidad`-ed.`cantFact` candidadNE,
		 tm.`operacion`,
		 tm.`id`
	FROM 	egredetalle ed
		INNER JOIN egresos e
		    ON e.`idegresos`=ed.`idegreso`
		INNER JOIN tmovimiento tm
		    ON tm.`id`=e.tipomov
		INNER JOIN clientes c
		    ON c.idCliente=e.cliente
	WHERE 	e.`almacen`=@alm
		AND e.`anulado`=0
		AND e.`tipomov`= 7
		AND e.`estado`<>1
		AND ed.`articulo`=@idArt
UNION ALL 
/** TRASPASO Y OTROS **/
	SELECT 	 e.almacen,
		 c.nombreCliente,
		 e.`fechamov`,
		 tm.`sigla`,
		 e.`nmov`,
		 ed.`punitario`,
		 ed.`cantidad`,
		 tm.`operacion`,
		 tm.`id`
	FROM egredetalle ed
		INNER JOIN egresos e
		    ON e.`idegresos`=ed.`idegreso`
		INNER JOIN tmovimiento tm
		    ON tm.`id`=e.tipomov
		INNER JOIN clientes c
		    ON c.idCliente=e.cliente
		WHERE YEAR(e.`fechamov`)=YEAR(NOW()) /*gestion actual*/
		AND e.`almacen`=@alm
		AND e.`anulado`=0
		AND e.`tipomov` BETWEEN 8 AND 9
		AND ed.`articulo`=@idArt
	ORDER BY  fecha, numMov;
