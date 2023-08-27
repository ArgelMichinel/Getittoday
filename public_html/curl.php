<?php

$bina = openssl_random_pseudo_bytes ( 4, $crypto_strong);
$randSecu = bin2hex($bina);

include __DIR__ . '/../include/Meli_functions.php';
include __DIR__ . '/../include/Access_functions.php';
include __DIR__ . '/../include/Datosprogram.php';
include __DIR__ . '/../include/DatabaseConnection.php';

if (isset($_POST['new_user'])) {
    $new_user = $_POST['new_user'];
    $num_errores = 0;
    $dummy = info_user($pdo,$new_user['user_id']);
    $new_user["nombre"] = $dummy['nickname'];

    if (empty($new_user['email'])) {
        $num_errores += 1;
        $errores[] = 'El campo de email no puede quedar en blanco';
    }
    else {
        if (filter_var($new_user['email'], FILTER_VALIDATE_EMAIL) == false) {
            $num_errores += 1;
            $errores[] = 'Dirección inválida de email';
        }
        // convert the email to lowercase
        $new_user['email'] = strtolower($new_user['email']);
        // Search for the lowercase version of $author['email']
        if (count(findSeveral($pdo,'Access','user_id',$new_user['user_id'])) === 0) {
            if (count(findSeveral($pdo,'Access','email',$new_user['email'])) > 0) {
                $num_errores += 1;
                $errores[] = 'Este email ya ha sido registrado';
            }
        }
    }
    
    if ($num_errores == 0) {
        try {
            $new_user['password'] = password_hash($new_user['password'], PASSWORD_DEFAULT);
            
            insert($pdo, 'Access', ['user_id' => $new_user["user_id"],
                                    'access_tok' => $new_user["access_tok"],
                                    'refresh_tok' => $new_user["refresh_tok"],
                                    'Nombre' => $new_user["nombre"],
                                    'email' => $new_user["email"],
                                    'password' => $new_user["password"],
                                    'fec_hora' => new DateTime()]);

            $title='Registro de nuevo usuario';
            
            /////////////////////////////////////////////////////////////
            ob_start();

            ?>

            <div class="formu">
              <div id="respuesta"><h2>Se registró un nuevo usuario</h2></div>
              <a class="underH" href="access_client.php">Ingresar al sistema</a>
            </div>

            <?php
            $output = ob_get_clean();
            
        } catch (PDOException $e) {
	        update_access($pdo, 'Access', ['user_id' => $new_user["user_id"],
                                           'access_tok' => $new_user["access_tok"],
                                           'refresh_tok' => $new_user["refresh_tok"],
                                           'Nombre' => $new_user["nombre"],
                                           'email' => $new_user["email"],
                                           'password' => $new_user["password"],
                                           'fec_hora' => new DateTime()]);
            
            
            $title='Actualización de usuario';
            /////////////////////////////////////////////////////////////
            ob_start();

            ?>

            <div class="formu">
              <div id="respuesta"><h2>Se actualizó usuario existente</h2></div>
              <a class="underH" href="access_client.php">Ingresar al sistema</a>
            </div>

            <?php
            $output = ob_get_clean();
	    }
        
        ///////////////////////////////////////
        $inc_script_end = '<!****************>';
        ob_start();
        ?>

        <link rel="stylesheet" href="./Styles/curl.css">
        <script type="text/javascript" language="javascript" src="./JS/access.js"></script>

        <?php
        $inc_head = ob_get_clean();
        
    } else {
        header('location: https://auth.mercadolibre.com.ar/authorization?response_type=code&client_id='.$APP_ID.'&state='.$randSecu.'&redirect_uri='.$URL);
        die();
    }
    
} elseif (isset($_GET['code'])) {
	try {
		$code = $_GET['code'];
		$state = $_GET['state'];
		$datos = request_tok($code,$state,$APP_ID,$SECRET_KEY,$URL);
        //print_r($datos);
        
        $registro = findById($pdo,'Access','user_id',$datos["user_id"]);
        
        $title='Registro de usuario';
        /////////////////////////////////////////////////////////////
        ob_start();
        include __DIR__ . '/../Templ/out_curl.html.php';
        $output = ob_get_clean();
        ///////////////////////////////////////
        $inc_script_end = '<!****************>';
        $inc_head = '<link rel="stylesheet" href="./Styles/curl.css">';
        
		
	} catch (PDOException $e) {
	
		$title = 'An error has occurred';
		$output = 'Database error: ' . $e->getMessage() . ' in '. $e->getFile() . ':' . $e->getLine();
	}
} else {

	header('location: https://auth.mercadolibre.com.ar/authorization?response_type=code&client_id='.$APP_ID.'&state='.$randSecu.'&redirect_uri='.$URL);
    die();
	
}
  
include __DIR__ . '/../Templ/layout1.html.php';
