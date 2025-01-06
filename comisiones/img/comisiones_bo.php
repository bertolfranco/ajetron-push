<?php
// Conexión a la base de datos

function generarImagenBolivia($mysqli,$ruta,$pais,$anio,$mes){
    $path= "/var/www/html/ajetron-push/comisiones/img";

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


function generarReportes($conn,$ruta,$sql){
    $keep_asking_for_data = true;
    do{

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $ruta);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result ->num_rows>0){
            generarImagenes($result);
        }else{
            $keep_asking_for_data=false;
        }
    }while($keep_asking_for_data);

}

function generarImagenes($result){

        $num_filas = ($result ->num_rows);

        $ancho_grafico = 600; // Ancho estimado por columna
        $alto_grafico = $num_filas * 25; // Alto estimado por fila

        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');

        // Setup graph context
        $graph = new CanvasGraph($ancho_grafico, $alto_grafico);
        //$graph = new CanvasGraph(430,150);

        // Setup a basic table
        $table = new GTextTable();
        $table->Set($result);

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
        $graph->Stroke(_IMG_HANDLER);
        $fileName = "$path/grafica_" . $codigo . ".png";
        $graph->img->Stream($fileName);
}

?>