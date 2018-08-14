-- MOVIMIENTO ITEMES CLIENTES --> variables fecha y id articulo
SELECT a.`CodigoArticulo`, f.`fechaFac`, f.`nFactura`,c.`documento`,  c.`nombreCliente` , ROUND(fd.`facturaPUnitario`,2) precioUnitario, 
ROUND(fd.`facturaCantidad`,2) cantidad, ROUND(fd.`facturaPUnitario` * fd.`facturaCantidad`,2) totalVentas
FROM facturadetalle fd
    INNER JOIN factura f ON f.`idFactura` = fd.`idFactura` AND f.`anulada` = 0
    INNER JOIN clientes c ON c.`idCliente` = f.`cliente`
    INNER JOIN articulos a ON a.`idArticulos` = fd.`articulo`
WHERE  f.`fechaFac` BETWEEN '2018-01-01' AND '2018-12-31'
AND fd.`articulo` = 2176
ORDER BY c.`nombreCliente`, f.`fechaFac`
