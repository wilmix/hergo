/******** SALDO ************/
CREATE DEFINER=`root`@`%` PROCEDURE `pruebaSaldo`(IN `_idArticulo` INT, IN `_idAlmacen` INT)
BEGIN
SET @idArt = _idArticulo;
SET @alm = _idAlmacen;
set @saldo = (SELECT (
(SELECT SUM(id.`cantidad`) /* INGRESOS */
	FROM ingdetalle id
	INNER JOIN ingresos i ON i.`idIngresos`=id.`idIngreso`
    WHERE YEAR(i.`fechamov`)=YEAR(NOW())
		AND i.`almacen`=@alm
		AND i.`anulado`=0
		AND id.`articulo`=@idArt)
-
(SELECT IFNULL(SUM(fd.`facturaCantidad`),0) /* FACTURA */
	FROM    facturadetalle fd 
	INNER JOIN factura f ON f.`idFactura`=fd.`idFactura`
    WHERE YEAR(f.`fechaFac`)=YEAR(NOW())
		AND f.`almacen`=@alm
		AND f.`anulada`=0
		AND fd.`articulo`=@idArt )
-
(SELECT IFNULL(SUM(ed.`cantidad`-ed.`cantFact`),0) /* NOTA DE ENTREGA */
	FROM 	egredetalle ed
		INNER JOIN egresos e ON e.`idegresos`=ed.`idegreso`
	WHERE 	e.`almacen`=@alm
		AND e.`anulado`=0
		AND e.`tipomov`= 7
		AND e.`estado`<>1
		AND ed.`articulo`=@idArt )
-
(SELECT IFNULL(SUM(ed.`cantidad`),0) /* TRASPASOS OTROS */

	FROM egredetalle ed
		INNER JOIN egresos e ON e.`idegresos`=ed.`idegreso`
        WHERE YEAR(e.`fechamov`)=YEAR(NOW())
		AND e.`almacen`=@alm
		AND e.`anulado`=0
		AND e.`tipomov` BETWEEN 8 AND 9
		AND ed.`articulo`=@idArt)
)
);
	CASE _idAlmacen
		WHEN 1 THEN
			UPDATE prueba SET saldoLP = @saldo WHERE id = _idArticulo;
		WHEN 2 THEN
			UPDATE prueba SET saldoEA = @saldo WHERE id = _idArticulo;
		WHEN 3 THEN
			UPDATE prueba SET saldoPTS = @saldo WHERE id = _idArticulo;
		WHEN 4 THEN 
			UPDATE prueba SET saldoSCZ = @saldo WHERE id = _idArticulo;
    END CASE;

END

/******** CPP ************/
CREATE DEFINER=`root`@`%` PROCEDURE `pruebaCosto`(IN `_idArticulo` INT)
BEGIN
SET @idArt = _idArticulo;
SET @cpp = (SELECT SUM(id.`total`)/SUM(id.`cantidad`) AS cpp
FROM ingdetalle id
INNER JOIN ingresos i ON i.`idIngresos`= id.`idIngreso`
WHERE YEAR(i.`fechamov`)=YEAR(NOW())
AND i.`anulado`= 0 
and i.estado = 1
AND id.articulo= @idArt);
UPDATE prueba SET costo=@cpp WHERE id=_idArticulo;
END



/************* kardex *****************/
/** ingresos **/
	SELECT 	 i.`almacen`,
		 p.`nombreproveedor`,
		 IF(i.`tipomov`=1,0,i.`fechamov`) AS fecha,
		 tm.`sigla` tipo,
		 i.`nmov` AS numMov,
		 id.`punitario`,
		 id.`cantidad`,
		 tm.`operacion`
	FROM ingdetalle id
		INNER JOIN ingresos i
		    ON i.`idIngresos`=id.`idIngreso`
		INNER JOIN tmovimiento tm
		    ON tm.`id`=i.`tipomov`
		INNER JOIN provedores p
		    ON p.`idproveedor`=i.`proveedor`
	WHERE 	i.`fechamov`
		BETWEEN "2018-01-01" AND "2018-12-31" /*gestion actual*/
		AND i.`almacen`=1
		AND i.`anulado`=0
		AND id.`articulo`=465
UNION ALL 
/** FACTURA **/
	SELECT  f.almacen,
		c.nombreCliente,
		`fechaFac`,
		"FAC" ,
		f.`nFactura`,
		fd.`facturaPUnitario`,
		fd.`facturaCantidad`,
		"-"
	FROM    facturadetalle fd
		INNER JOIN factura f
		    ON f.`idFactura`=fd.`idFactura`
		INNER JOIN clientes c
		    ON c.idCliente=f.cliente
	WHERE f.`fechaFac`
		BETWEEN "2018-01-01" AND "2018-12-31" /*gestion actual*/
		AND f.`almacen`=1
		AND f.`anulada`=0
		AND fd.`articulo`=465
UNION ALL 
/** NOTA ENTREGA **/
	SELECT 	 e.almacen,
		 c.nombreCliente,
		 `fechamov`,
		 tm.`sigla`,
		 e.`nmov`,
		 ed.`punitario`,
		 ed.`cantidad`-ed.`cantFact` candidadNE,
		 tm.`operacion`
	FROM 	egredetalle ed
		INNER JOIN egresos e
		    ON e.`idegresos`=ed.`idegreso`
		INNER JOIN tmovimiento tm
		    ON tm.`id`=e.tipomov
		INNER JOIN clientes c
		    ON c.idCliente=e.cliente
	WHERE 	e.`almacen`=1
		AND e.`anulado`=0
		AND e.`tipomov`= 7
		AND e.`estado`<>1
		AND ed.`articulo`=465
UNION ALL 
/** TRASPASO Y OTROS **/
	SELECT 	 e.almacen,
		 c.nombreCliente,
		 e.`fechamov`,
		 tm.`sigla`,
		 e.`nmov`,
		 ed.`punitario`,
		 ed.`cantidad`,
		 tm.`operacion`
	FROM egredetalle ed
		INNER JOIN egresos e
		    ON e.`idegresos`=ed.`idegreso`
		INNER JOIN tmovimiento tm
		    ON tm.`id`=e.tipomov
		INNER JOIN clientes c
		    ON c.idCliente=e.cliente
	WHERE 	e.`fechamov`
		BETWEEN "2018-01-01" AND "2018-12-31" /*gestion actual*/
		AND e.`almacen`=1
		AND e.`anulado`=0
		AND e.`tipomov` BETWEEN 8 AND 9
		AND ed.`articulo`=465
	ORDER BY  fecha, numMov;

  /*******************************************/
  /*******************************************/
  /************* kardex FInal*****************/
set @idArticulo=2140;
set @cant=0.0;
set @totalAux=0.0;
set @total=0.0;
set @cpp=0.0;
DROP TABLE IF EXISTS tmp_tabla;
CREATE TEMPORARY TABLE tmp_tabla AS(
  SELECT * FROM
  (
    /** ingresos **/
    	SELECT 	 i.`almacen`,
    		 p.`nombreproveedor`,
    		 IF(i.`tipomov`=1,0,i.`fechamov`) AS fecha,
    		 tm.`sigla` tipo,
    		 i.`nmov` AS numMov,
    		 id.`punitario`,
    		 id.`cantidad`,
    		 tm.`operacion`
    	FROM ingdetalle id
    		INNER JOIN ingresos i
    		    ON i.`idIngresos`=id.`idIngreso`
    		INNER JOIN tmovimiento tm
    		    ON tm.`id`=i.`tipomov`
    		INNER JOIN provedores p
    		    ON p.`idproveedor`=i.`proveedor`
    	WHERE 	i.`fechamov`
    		BETWEEN "2018-01-01" AND "2018-12-31" /*gestion actual*/
    		AND i.`almacen`=1
    		AND i.`anulado`=0
    		AND id.`articulo`=@idArticulo
    UNION ALL 
    /** FACTURA **/
    	SELECT  f.almacen,
    		c.nombreCliente,
    		fechaFac,
    		"FAC" ,
    		f.nFactura,
    		fd.facturaPUnitario,
    		fd.facturaCantidad,
    		"-"
    	FROM    facturadetalle fd
    		INNER JOIN factura f
    		    ON f.`idFactura`=fd.`idFactura`
    		INNER JOIN clientes c
    		    ON c.idCliente=f.cliente
    	WHERE f.`fechaFac`
    		BETWEEN "2018-01-01" AND "2018-12-31" /*gestion actual*/
    		AND f.`almacen`=1
    		AND f.`anulada`=0
    		AND fd.`articulo`=@idArticulo
    UNION ALL 
    /** NOTA ENTREGA **/
    	SELECT 	 e.almacen,
    		 c.nombreCliente,
    		 `fechamov`,
    		 tm.`sigla`,
    		 e.`nmov`,
    		 ed.`punitario`,
    		 ed.`cantidad`-ed.`cantFact` candidadNE,
    		 tm.`operacion`
    	FROM 	egredetalle ed
    		INNER JOIN egresos e
    		    ON e.`idegresos`=ed.`idegreso`
    		INNER JOIN tmovimiento tm
    		    ON tm.`id`=e.tipomov
    		INNER JOIN clientes c
    		    ON c.idCliente=e.cliente
    	WHERE 	e.`almacen`=1
    		AND e.`anulado`=0
    		AND e.`tipomov`= 7
    		AND e.`estado`<>1
    		AND ed.`articulo`=@idArticulo
    UNION ALL 
    /** TRASPASO Y OTROS **/
    	SELECT 	 e.almacen,
    		 c.nombreCliente,
    		 e.`fechamov`,
    		 tm.`sigla`,
    		 e.`nmov`,
    		 ed.`punitario`,
    		 ed.`cantidad`,
    		 tm.`operacion`
    	FROM egredetalle ed
    		INNER JOIN egresos e
    		    ON e.`idegresos`=ed.`idegreso`
    		INNER JOIN tmovimiento tm
    		    ON tm.`id`=e.tipomov
    		INNER JOIN clientes c
    		    ON c.idCliente=e.cliente
    	WHERE 	e.`fechamov`
    		BETWEEN "2018-01-01" AND "2018-12-31" /*gestion actual*/
    		AND e.`almacen`=1
    		AND e.`anulado`=0
    		AND e.`tipomov` BETWEEN 8 AND 9
    		AND ed.`articulo`=@idArticulo
    	ORDER BY  fecha, numMov
  ) AS tmp
);
SELECT *, 
  @cant:=IF(operacion='+', @cant+cantidad, @cant-cantidad) AS _cantidad,
  @totalAux:=IF(operacion='+',ROUND(cantidad*punitario,4),ROUND(cantidad*@cpp,4)) AS _totalAux,
  @total:=IF(operacion='+',@total+@totalAux,@total-@totalAux) AS _total,
  @cpp:=ROUND(@total/@cant,4) as _cpp
  FROM tmp_tabla 
