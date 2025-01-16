<?php
// Conexión a la base de datos

function generarImagenBolivia($mysqli,$ruta,$pais,$anio,$mes){

    $sqlMn = "SELECT
    	vcv.familia as 'Familia',
    	(CASE WHEN vcv.familia = 'HIT RATE' THEN ''
    		ELSE FORMAT(vcv.monto_facturado, 0)
    	END ) as 'Facturacion bruta MN' ,
    	(CASE WHEN vcv.familia = 'HIT RATE' THEN ''
    		ELSE FORMAT(vcv.monto_objetivo, 0)
    	END ) as 'Cuota bruto MN' ,
    	CONCAT(FORMAT(vcv.alcance * 100, 0), '%') as 'Alcance' ,
    	(CASE WHEN vcv.familia = 'HIT RATE' THEN ''
    		ELSE FORMAT(vcv.monto_proy, 0)
    	END ) as 'Facturacion Proy' ,
    	(CASE WHEN vcv.familia = 'HIT RATE' THEN ''
    		ELSE CONCAT(FORMAT(vcv.alcance_proy * 100, 0), '%')
    	END ) as 'Alcance proyectado'
    FROM
    	v_comisiones_vendedor vcv

    WHERE vcv.pais = 'BO' and vcv.cod_ruta = ?
    ";

    generarReportes($mysqli,$ruta,$sqlMn);

    $mysqli->close();
}

function generarImagenColombia($mysqli,$ruta,$pais,$anio,$mes){

    $sqlMn = "SELECT
    t1.familia AS FAMILIA,
    (CASE
        WHEN t1.tipodecomision='Monetaria' THEN FORMAT(t1.valor, 3)
    END) as 'FACTOR POR CF',
    FORMAT(t1.presupuesto, 0) AS 'Objetivo',
    FORMAT(t1.acumulado, 0) AS 'Avance',
    FORMAT(t1.venta_proyectada, 0) AS 'Proy Cierre',
    CONCAT(FORMAT(t1.alcance*100, 1), '%') AS '% Proy Cierre',
    CONCAT(FORMAT(t1.porcent_pago*100, 0), '%') AS '%',
    CONCAT('$ ',FORMAT(t1.pago_comision_final,2)) AS 'Proy Pago',
    FORMAT(t1.pago_comision_final,2) AS 'Compensacion_num'
    FROM v_comisiones_co_m1 AS t1
    WHERE t1.pais='CO' AND t1.cod_ruta = ?
    order by cod_ruta,tipodecomision desc, t1.valor asc
    ";

    generarReportes($mysqli,$ruta,$sqlMn);

    $sqlEfect = "SELECT Items as Items,  objetivo as Objetivo,avance as Avance,
                                       Compensacion as Compensacion from ajetron.v_efectividad_m1 g
                                       where `pais`='CO' and cod_ruta = ?";

    generarReportes($mysqli,$ruta,$sqlEfect);

    $mysqli->close();
}

function generarImagenCostaRica($mysqli,$ruta,$pais,$anio,$mes){

    $sqlMn = "SELECT t1.tipodecomision,
              (CASE
                  WHEN t1.tipodecomision IN ('Monetaria','Cobertura') THEN CONCAT('C. ',FORMAT(t1.valor, 2))
              END) as PAGO,
              t1.familia AS FAMILIA,
              FORMAT(t1.meta, 0) AS META,
              FORMAT(t1.acumulado, 0) AS ACUMULADO,
              CONCAT(FORMAT(t1.alcance*100, 0), '%') AS ALCANCE,
              (CASE
                  WHEN t1.familia='TOTAL' THEN '-'
                  ELSE CONCAT(FORMAT(t1.porcent_pago*100, 0), '%')
              END) AS '% PAGO',
              (CASE
                  WHEN t1.tipodecomision IN ('Monetaria','Cobertura') THEN CONCAT('C. ',FORMAT(t1.pago_comision_final, 0))
                  WHEN t1.familia='TOTAL' THEN CONCAT('C. ',FORMAT(t1.pago_comision_final, 0))
              END)  AS 'BONO'
              FROM v_comisiones_cob_gt AS t1

              inner join (SELECT DISTINCT pais,cod_ruta  FROM v_comisiones_cob_gt  WHERE  `pais`='CR' and cod_ruta = ? order by cod_ruta) t2
              on t1.pais=t2.pais and t1.cod_ruta=t2.cod_ruta
              order by t1.cod_ruta,tipodecomision desc,CASE
                      WHEN t1.tipodecomision = 'Monetaria' THEN FIELD(
                          t1.familia,
                          'BIG + CIELO',
                          'CIFRUT + BIO',
                          'VOLT + SPORADE',
                          'D GUSSTO + PULP'
                      )
                      ELSE t1.familia -- Para otros casos, se usa el orden natural de familia
                  END, t1.valor asc
    ";

    generarReportes($mysqli,$ruta,$sqlMn);

    $mysqli->close();
}

function generarImagenMexico($mysqli,$ruta,$pais,$anio,$mes){

    $sqlMn = "SELECT
              t2.tipodecomision,
              cat.PLATAFORMA AS FAMILIA,
              FORMAT(COALESCE(t1.meta, 0), 0) AS META,
              FORMAT(COALESCE(t1.acumulado, 0), 0) AS ACUMULADO,
              FORMAT(COALESCE(t1.venta_proyectada, 0), 0) AS 'VENTA PROYECTADA',
              CONCAT(FORMAT(COALESCE(t1.alcance, 0) * 100, 0), '%') AS ALCANCE,
              CASE
                      WHEN t1.marca = 'TOTAL' THEN '-'
                      ELSE CONCAT(FORMAT(COALESCE(t1.porcent_pago, 0) * 100, 0), '%')
              END AS '% PAGO',
              CONCAT(FORMAT(COALESCE(t1.pago_comision_final, 0), 0), '%') AS 'PAGO FINAL COMISION',
              CONCAT('$ ', FORMAT(COALESCE(t1.bono_volumen, 0), 0)) AS 'BONO VOLUMEN',
              CONCAT('$ ', FORMAT(COALESCE(t1.bono_ingresos, 0), 0)) AS 'BONO INGRESOS',
              CONCAT('$ ', FORMAT(COALESCE(t1.pago_incentivo, 0), 0)) AS 'BONO IMPER.'
              FROM
              (SELECT DISTINCT pais, cod_ruta, tipodecomision FROM v_comisiones_nuevo_mx WHERE pais='MX' and cod_ruta = ?) AS t2
              CROSS JOIN
               (SELECT DISTINCT 'BIG' AS PLATAFORMA UNION ALL
                   SELECT DISTINCT 'SPORADE' UNION ALL
                   SELECT DISTINCT 'CIFRUT' UNION ALL
                   SELECT DISTINCT 'VOLT' UNION ALL
                   SELECT DISTINCT 'AMAYU' UNION ALL
                   SELECT DISTINCT 'PULP' UNION ALL
                   SELECT DISTINCT 'ROMPE' UNION ALL
                   SELECT DISTINCT 'VIDA' UNION ALL
                   SELECT DISTINCT 'TOTAL') AS cat
              LEFT JOIN
               v_comisiones_nuevo_mx AS t1
              on  t1.cod_ruta = t2.cod_ruta
              AND t1.tipodecomision = t2.tipodecomision and
              t1.marca = cat.PLATAFORMA
              order by t1.cod_ruta,tipodecomision desc, t1.valor asc
    ";

    generarReportes($mysqli,$ruta,$sqlMn);

    $mysqli->close();
}


function generarReportes($conn,$ruta,$sql){
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $ruta);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result ->num_rows>0){
            generarImagenes($result,$ruta);
        }

}

function generarImagenes($result,$codigo){

        $encabezados = array();
        while ($columna = $result->fetch_field()) {
             $encabezados[] = $columna->name;
        }

        $dataRutas = array();

        $dataRutas[]=$encabezados;

        while ($fila = $result->fetch_assoc()) {
            $dataRutas[] = array_values($fila);
        }

        $num_filas = count($dataRutas);

        $ancho_grafico = 600; // Ancho estimado por columna
        $alto_grafico = $num_filas * 25; // Alto estimado por fila

        header("Content-Type: application/png");
        header("Content-Disposition:attachment; filename=grafica_".$codigo.".png");
        header("Pragma: no-cache");
        header("Expires:0");


        // Setup graph context
        $graph = new CanvasGraph($ancho_grafico, $alto_grafico);
        //$graph = new CanvasGraph(430,150);

        // Setup a basic table
        $table = new GTextTable();
        $table->Set($dataRutas);

        // Setup fonts
        $table->SetFont(FF_FONT1,FF_FONT2,16);
        //  $table->SetColFont(0,FF_FONT1,FF_FONT2,11);
        //  $table->SetRowFont(0,FF_FONT1,FF_FONT2,11);
        //  $table->SetRowFont(4,FF_FONT1,FF_FONT2,14);

        // Turn off the grid
        $table->SetGrid(0);

        // Setup color
        $table->SetRowFillColor(0,'lightgreen@0.5');
        //$table->SetRowFillColor(4,'lightgray@0.5');
        $table->SetColFillColor(0,'lightgreen@0.5');
        //$table->SetFillColor(0,0,4,0,'lightgray@0.5');

        // Set default minimum column width
        $table->SetMinColWidth(45);

        // Set default table alignment
        $table->SetAlign('center');

        // Add table to the graph
        $graph->Add($table);

        // and send it back to the client
        $img = $graph->Stroke(_IMG_HANDLER);
        ImagePNG($img);

}

?>