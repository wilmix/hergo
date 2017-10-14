SELECT e.idingdetalle,e.idegreso,e1.fechamov, e.articulo,e1.almacen,NULL cantidadIngreso, NULL punitarioIngreso, NULL totalIngreso, e.cantFact cantidadFactura, e.cantidad-e.cantFact cantidadEgreso,e.punitario punitarioEgreso, ROUND(e.cantidad*e.punitario,3) totalEgreso
              FROM egredetalle e
              INNER JOIN egresos e1
              ON  e.idegreso=e1.idegresos
              WHERE YEAR(e1.fechamov)=YEAR(NOW())
              AND e.cantFact
              AND e1.anulado=0
              AND e.articulo=436
              AND e1.almacen=1
            UNION
              SELECT i.idIngdetalle,i.idIngreso,i1.fechamov, i.articulo,i1.almacen, i.cantidad cantidadIngreso,i.punitario punitarioIngreso, ROUND(i.cantidad*i.punitario,3) totalIngreso,NULL cantidadFactura,NULL cantidadEgreso, NULL punitarioEgreso, NULL totalEgreso
              FROM ingdetalle i
              INNER JOIN ingresos i1 
              ON  i.idIngreso=i1.idIngresos
              WHERE YEAR(i1.fechamov)=YEAR(NOW())              
              AND i1.anulado=0
              AND i.articulo=436
              AND i1.almacen=1
              ORDER BY  fechamov