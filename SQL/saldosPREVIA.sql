SELECT * /*SUM(cantidadIngreso),SUM(cantidadFactura),SUM(cantidadEgreso) *//*as Saldo*/
FROM (
	SELECT i.idIngdetalle,i.idIngreso,i1.fechamov, i.articulo,i1.almacen, i.cantidad cantidadIngreso , NULL cantidadFactura,NULL cantidadEgresoNE, NULL cantidadTraspaso
	FROM ingdetalle i
	INNER JOIN ingresos i1 
	ON  i.idIngreso=i1.idIngresos
	WHERE YEAR(i1.fechamov)=YEAR(NOW())              
	AND i1.anulado=0
	AND i.articulo=2364
	AND i1.almacen=1
	UNION
	
	SELECT e.idingdetalle,e.idegreso,e1.fechamov, e.articulo,e1.almacen,NULL cantidadIngreso, e.cantFact cantidadFactura, NULL cantidadEgresoNE, NULL cantidadTraspaso
	FROM egredetalle e
	INNER JOIN egresos e1
	ON  e.idegreso=e1.idegresos
	INNER JOIN factura_egresos fe
	ON fe.idegresos=e.idegreso
	INNER JOIN factura f
	ON f.idFactura=fe.idFactura
	WHERE YEAR(f.fechaFac)=YEAR(NOW())	
	AND e.cantFact>0
	AND e1.anulado=0
	AND e.articulo=2364
	AND e1.almacen=1
	UNION
	
	SELECT e.idingdetalle,e.idegreso,e1.fechamov, e.articulo,e1.almacen,NULL cantidadIngreso, NULL cantidadFactura, e.cantidad-e.cantFact cantidadEgresoNE, NULL cantidadTraspaso
	FROM egredetalle e
	INNER JOIN egresos e1
	ON  e.idegreso=e1.idegresos
	WHERE e1.tipomov=7 /*Nota de entrega*/	
	AND e1.estado<>1 /*distinto de facturado*/
	AND e1.anulado=0
	AND e.articulo=2364
	AND e1.almacen=1

	UNION
	
	SELECT e.idingdetalle,e.idegreso,e1.fechamov, e.articulo,e1.almacen,NULL cantidadIngreso, NULL cantidadFactura, NULL cantidadEgresoNE, e.cantidad cantidadTraspaso
	FROM egredetalle e
	INNER JOIN egresos e1
	ON  e.idegreso=e1.idegresos
	WHERE e1.tipomov=8 /*Traspaso*/	
	AND e1.estado<>1 /*distinto de facturado*/
	AND YEAR(e1.fechamov)=YEAR(NOW())
	AND e1.anulado=0
	AND e.articulo=2364
	AND e1.almacen=1
	
	ORDER BY fechamov ASC
	) AS CONSULTA