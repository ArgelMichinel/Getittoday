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

    include __DIR__ . '/../include/phpqrcode/qrlib.php';

    if (isset($_GET['id'])) {

        $codeText = $_GET['id']; // remember to sanitize that - it is user input!
        $codeText = str_replace(" ", "+", $codeText);

        // we need to be sure ours script does not output anything!!!
        // otherwise it will break up PNG binary!

        ob_start("callback");

        // end of processing here
        $debugLog = ob_get_contents();
        ob_end_clean();

        // outputs image directly into browser, as PNG stream
        QRcode::png($codeText);
    }

} catch (PDOException $e) {

	echo ('Problema durante la consulta ' . $e);
}

include __DIR__ . '/../Templ/layout2.html.php';