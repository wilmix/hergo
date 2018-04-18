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