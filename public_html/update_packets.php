<?php
session_start();
try {

    include __DIR__ . '/../include/DatabaseConnection.php';
	
    if (isset($_SESSION['user']) && isset($_SESSION['password'])) {
        
        include __DIR__ . '/../include/Access_functions.php';
        
        IsLogged($pdo);
        
	} else {
        header('location: index.php');
    }
    
    include __DIR__ . '/../include/Meli_functions.php';
	
    if (isset($_POST['new_query'])) {
        
        $title='Paquetes filtrados';
        $packets = query_customized($pdo,$_POST['new_query']);
        $clients = findAll($pdo,"Access");
        $cadetes = findAll($pdo,"cadetes");
        
        ///////////////////////////////////////////////////////////////////////////////////
        ob_start();
        ?>

        <link rel="stylesheet" type="text/css" href="./Styles/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="./Styles/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="./Styles/query_packets.css">
        <script type="text/javascript" language="javascript" src="./JS/jquery-3.5.1.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/dataTables.buttons.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/jszip.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/vfs_fonts.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/buttons.html5.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/buttons.print.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/update_packets.js"></script>
        <script type="text/javascript" class="init">
            $(document).ready(function() {
                var table = $('#example').DataTable( {
                    dom: 'Blfrtip',
                    buttons: [
                        'copyHtml5', 'csvHtml5', 'excelHtml5',  'print' //,'pdf'
                    ],
                    "lengthMenu": [ [5 ,10, 25, 50, -1], [5, 10, 25, 50, "Todos"] ],
                    "pageLength": 10,
                    "scrollX": true
                } );

                $('.switch-1').on( 'click', function (e) {
                    e.preventDefault();

                    // Get the column API object
                    var column = table.column( $(this).attr('data-column') );
                    //$(this).prop("checked", ! $(this).prop("checked"));

                    // Toggle the visibility
                    column.visible( ! column.visible() );
                } );

            } );
        </script>

        <?php
        $inc_head = ob_get_clean();
        ///////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////
        ob_start();

        include __DIR__ . '/../Templ/out_update_packets.html.php';

        $output = ob_get_clean();
        ///////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////
        $inc_script_end = '<!*******************************>';
        ///////////////////////////////////////////////////////////////////////////////////
        
	}  else if (isset($_POST['new_list'])) {
        $cambios = $_POST['new_list'];
        $valores = $_POST['values'];

        $list_values = json_decode($valores,true);
        
        $parameters = [];
        
        for ($i=0; $i < count($list_values); $i++) {
            $parameters[$i] = $cambios;
            $parameters[$i]['id_ship'] = $list_values[$i];
        }
        
        update_by_lots ($pdo, 'envios', 'id_ship', $parameters);
        
        $title='Envíos Actualizados';
        
        ob_start();
        
        ?>

        <div class="formu">
          <div id="respuesta"><h2>Se Actualizaron los envíos con éxito</h2></div>
        </div>
        
        <?php
            
        $output = ob_get_clean();
        ///////////////////////////////////////////////////////////////////////////////////
        $inc_head = '<link rel="stylesheet" type="text/css" href="./Styles/query_packets.css">';
        ///////////////////////////////////////////////////////////////////////////////////
        $inc_script_end = '<!*******************************>';
        ///////////////////////////////////////////////////////////////////////////////////
        
	} else {
        $title='Actualizar Paquetes';
        $packets = find300($pdo,"envios");
        $clients = findAll($pdo,"Access");
        $cadetes = findAll($pdo,"cadetes");
        
        ///////////////////////////////////////////////////////////////////////////////////
        ob_start();
        ?>
        
        <link rel="stylesheet" type="text/css" href="./Styles/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="./Styles/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="./Styles/query_packets.css">
        <script type="text/javascript" language="javascript" src="./JS/jquery-3.5.1.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/dataTables.buttons.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/jszip.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/vfs_fonts.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/buttons.html5.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/buttons.print.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/update_packets.js"></script>
        <script type="text/javascript" class="init">
            $(document).ready(function() {
                var table = $('#example').DataTable( {
                    dom: 'Blfrtip',
                    buttons: [
                        'copyHtml5', 'csvHtml5', 'excelHtml5',  'print' //,'pdf'
                    ],
                    "lengthMenu": [ [5 ,10, 25, 50, -1], [5, 10, 25, 50, "Todos"] ],
                    "pageLength": 10,
                    "scrollX": true
                } );
                
                $('.switch-1').on( 'click', function (e) {
                    e.preventDefault();

                    // Get the column API object
                    var column = table.column( $(this).attr('data-column') );
                    //$(this).prop("checked", ! $(this).prop("checked"));

                    // Toggle the visibility
                    column.visible( ! column.visible() );
                } );

            } );
        </script>

        <?php
        $inc_head = ob_get_clean();
        ///////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////
        ob_start();

        include __DIR__ . '/../Templ/out_update_packets.html.php';

        $output = ob_get_clean();
        ///////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////
        $inc_script_end = '<!*******************************>';
        ///////////////////////////////////////////////////////////////////////////////////

		
	}
} catch (PDOException $e) {

	echo ('Problema durante la consulta ' . $e);
}

include __DIR__ . '/../Templ/layout2.html.php';