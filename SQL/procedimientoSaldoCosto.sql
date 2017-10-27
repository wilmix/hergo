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