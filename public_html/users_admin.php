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
    
    if (isset($_POST['del_admin'])) {
    
        $del_admin = $_POST['del_admin'];
        
        delete($pdo, 'administ', 'email', $del_admin );
        
        $administra = findAll($pdo,'administ');
        
        $administra = array_slice($administra,1);

        $title='Usuarios Administradores';

        ///////////////////////////////////////////////////////////////////////////////////
        ob_start();
        ?>

        <link rel="stylesheet" type="text/css" href="./Styles/users_admin.css">
        <link rel="stylesheet" type="text/css" href="./Styles/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="./Styles/buttons.dataTables.min.css">
        <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
        <link rel="stylesheet" href="fonts/font-awesome.min.css" type="text/css" media="screen">
        <script type="text/javascript" language="javascript" src="./JS/jquery-3.5.1.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/dataTables.buttons.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/jszip.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/vfs_fonts.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/buttons.html5.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/buttons.print.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/users_admin.js"></script>
        <script type="text/javascript" class="init">
            $(document).ready(function() {
                $('#example').DataTable( {
                    dom: 'Blfrtip',
                    buttons: [
                        'copy', 'csv', 'excel',  'print' //,'pdf'
                    ],
                    "lengthMenu": [ [5 ,10, 25, 50, -1], [5, 10, 25, 50, "Todos"] ],
                    "pageLength": 5,
                    "scrollX": true
                } );
            } );
        </script>

        <?php
        $inc_head = ob_get_clean();
        ///////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////
        ob_start();

        include __DIR__ . '/../Templ/out_users_admin.html.php';

        $output = ob_get_clean();
        ///////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////
        $inc_script_end = '<!*******************************>';
        ///////////////////////////////////////////////////////////////////////////////////

        
    } else {
        
        $administra = findAll($pdo,'administ');
        
        $administra = array_slice($administra,1);

        $title='Usuarios Administradores';

        ///////////////////////////////////////////////////////////////////////////////////
        ob_start();
        ?>

        <link rel="stylesheet" type="text/css" href="./Styles/users_admin.css">
        <link rel="stylesheet" type="text/css" href="./Styles/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="./Styles/buttons.dataTables.min.css">
        <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
        <link rel="stylesheet" href="fonts/font-awesome.min.css" type="text/css" media="screen">
        <script type="text/javascript" language="javascript" src="./JS/jquery-3.5.1.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/dataTables.buttons.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/jszip.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/vfs_fonts.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/buttons.html5.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/buttons.print.min.js"></script>
        <script type="text/javascript" language="javascript" src="./JS/users_admin.js"></script>
        <script type="text/javascript" class="init">
            $(document).ready(function() {
                $('#example').DataTable( {
                    dom: 'Blfrtip',
                    buttons: [
                        'copy', 'csv', 'excel',  'print' //,'pdf'
                    ],
                    "lengthMenu": [ [5 ,10, 25, 50, -1], [5, 10, 25, 50, "Todos"] ],
                    "pageLength": 5,
                    "scrollX": true
                } );
            } );
        </script>

        <?php
        $inc_head = ob_get_clean();
        ///////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////
        ob_start();

        include __DIR__ . '/../Templ/out_users_admin.html.php';

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