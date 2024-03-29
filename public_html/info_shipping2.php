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
    
    
    if (isset($_GET['shipnum']) and isset($_GET['sender_id'])) {

        include __DIR__ . '/../include/Meli_functions.php';
        include __DIR__ . '/../include/DatabaseConnection.php';

        $sender_id = (int)$_GET['sender_id'];
        $client_info = checkValdTok($pdo, $sender_id);
        $ACCESS_TOK = $client_info['access_tok'];

        $shipnum = (int)$_GET['shipnum'];

        $ship_mat = info_shipping ($shipnum, $ACCESS_TOK);

        echo "<pre>";
        print_r( json_decode($ship_mat));
        echo "</pre>";

        /*$ship_mat = rawurlencode( json_encode($ship_mat));

        echo ($ship_mat);*/

        //////////////////////////////////////////////////

    } elseif (isset($_POST['shipnum']) and isset($_POST['sender_id'])) {

        include __DIR__ . '/../include/Meli_functions.php';
        include __DIR__ . '/../include/DatabaseConnection.php';

        $sender_id = (int)$_POST['sender_id'];
        $client_info = checkValdTok($pdo, $sender_id);
        $ACCESS_TOK = $client_info['access_tok'];

        $shipnum = (int)$_POST['shipnum'];

        $ship_mat = print_answer ($shipnum, $ACCESS_TOK, $sender_id,"(vacio)");

        $packets = [];
        $packets['id_ship'] = $ship_mat[0][0];
        $packets['date_in'] = $ship_mat[0][1];
        $packets['status'] = $ship_mat[0][2];
        $packets['sender_id'] = $ship_mat[0][3];
        $packets['order_id'] = $ship_mat[0][4];
        $packets['street_name'] = $ship_mat[1][0];
        $packets['street_number'] = $ship_mat[1][1];
        $packets['comment'] = $ship_mat[1][2];
        $packets['zip_code'] = $ship_mat[1][3];
        $packets['city'] = $ship_mat[1][4];
        $packets['state'] = $ship_mat[1][5];
        $packets['country'] = $ship_mat[1][6];
        $packets['receiver_name'] = $ship_mat[2][0];
        $packets['receiver_phone'] = $ship_mat[2][1];
        $packets['description'] = $ship_mat[3][0];
        $packets['dimensions'] = $ship_mat[3][1];
        $packets['date_first_visit'] = $ship_mat[4][0];
        $packets['date_delivered'] = $ship_mat[4][1];
        $packets['date_not_delivered'] = $ship_mat[4][2];

        $title='Consultar paquetes sin registrar';

        ///////////////////////////////////////////////////////////////////////////////////
        ob_start();
        ?>

        <link rel="stylesheet" type="text/css" href="./Styles/info_shipping.css">

            <link rel="stylesheet" type="text/css" href="./Styles/jquery.dataTables.min.css">
            <link rel="stylesheet" type="text/css" href="./Styles/buttons.dataTables.min.css">
            <script type="text/javascript" language="javascript" src="./JS/jquery-3.5.1.js"></script>
            <script type="text/javascript" language="javascript" src="./JS/jquery.dataTables.min.js"></script>
            <script type="text/javascript" language="javascript" src="./JS/dataTables.buttons.min.js"></script>
            <script type="text/javascript" language="javascript" src="./JS/jszip.min.js"></script>
            <script type="text/javascript" language="javascript" src="./JS/vfs_fonts.js"></script>
            <script type="text/javascript" language="javascript" src="./JS/buttons.html5.min.js"></script>
            <script type="text/javascript" language="javascript" src="./JS/buttons.print.min.js"></script>
            <script type="text/javascript" class="init">
                $(document).ready(function() {
                    $('#example').DataTable( {
                        dom: 'Blfrtip',
                        buttons: [
                            'copy', 'csv', 'excel',  'print' //,'pdf'
                        ],
                        //"lengthMenu": [ [5 ,10, 25, 50, -1], [5, 10, 25, 50, "Todos"] ],
                        "pageLength": 25,
                        "ordering": false
                    } );
                } );
            </script>

        <?php
        $inc_head = ob_get_clean();
        ///////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////
        ob_start();
        include __DIR__ . '/../Templ/out_info_shipping.html.php';
        $output = ob_get_clean();
        ///////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////
        $inc_script_end = '<!*******************************>';
        ///////////////////////////////////////////////////////////////////////////////////
        include __DIR__ . '/../Templ/layout2.html.php';

    } else  {
        require_once __DIR__ . '/../include/Meli_functions.php';
        $title='Consultar paquetes sin registrar';
        $clients = findAll($pdo,"Access");

        ///////////////////////////////////////////////////////////////////////////////////
        ob_start();
        ?>

        <link rel="stylesheet" type="text/css" href="./Styles/info_shipping.css">

        <?php
        $inc_head = ob_get_clean();
        ///////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////
        ob_start();
        ?>

        <div class="presentacion">

            <form action="" method="post" class="container-reg">
                <h1>Ingrese los datos del envío</h1>

                <label for="shipnum"><b>Escribe el numero de envío:</b></label>
                <input type="text" placeholder="Introduzca el número" name="shipnum" required>

                <label for="sender_id"><b>Seleccione el código del vendedor:</b></label>
                <select name="sender_id" required>
                    <?php
                        for ($i=0; $i < count($clients); $i++) {
                            echo ('<option value="'. $clients[$i]['user_id'] . '">' . $clients[$i]['Nombre'] . '</option>' . PHP_EOL);
                        }
                    ?>
                </select>

                <button type="submit" class="btn">Consultar</button>
            </form>

        </div>

        <?php
        $output = ob_get_clean();
        ///////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////
        $inc_script_end = '<!*******************************>';
        ///////////////////////////////////////////////////////////////////////////////////

        include __DIR__ . '/../Templ/layout2.html.php';
    }
} catch (PDOException $e) {

	echo ('Problema con la BD o la clave ' . $e);
}
