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
    
    $data = json_decode(file_get_contents('php://input'), true);
    //print_r( $data);
    
    $n_data = count($data);
    
    $fields=[];
    
    for ($i = 0; $i < $n_data; $i++) {
        
        switch ($data[$i][1][6]) {
          case "Argentina":
            $pais = 1;
            break;
          case "Brasil":
            $pais = 2;
            break;
          case "Chile":
            $pais = 3;
            break;
          case "Perú":
            $pais = 4;
            break;
          case "Venezuela":
            $pais = 5;
            break;
          default:
            $pais = 1;
        }
        
        if ($data[$i][4][0]==NULL) {
            $f_first_visita = NULL;
        } else {
            $f_first_visita = new DateTime(substr($data[$i][4][0], 0, 19));
        }

        if ($data[$i][4][1]==NULL) {
            $f_delivered = NULL;
        } else {
            $f_delivered = new DateTime(substr($data[$i][4][1], 0, 19));
        }

        if ($data[$i][4][2]==NULL) {
            $f_not_delivered = NULL;
        } else {
            $f_not_delivered = new DateTime(substr($data[$i][4][2], 0, 19));
        }
        
        $colum = [];
        $colum['id_ship'] = $data[$i][0][0];
        $colum['status'] = $data[$i][0][2];
        $colum['sender_id'] = $data[$i][0][3];
        $colum['order_id'] = $data[$i][0][4];
        $colum['street_name'] = $data[$i][1][0];
        $colum['street_number'] = intval ($data[$i][1][1]);
        $colum['comment'] = $data[$i][1][2];
        $colum['zip_code'] = intval ($data[$i][1][3]);
        $colum['city'] = $data[$i][1][4];
        $colum['state'] = $data[$i][1][5];
        $colum['country'] = $pais;
        
        if ($data[$i][1][10]=='business') {
            $colum['delivery_preference'] = 1;
        } else {
            $colum['delivery_preference'] = 0;
        }
        $colum['receiver_name'] = $data[$i][2][0];
        $colum['receiver_phone'] = $data[$i][2][1];
        $colum['description'] = $data[$i][3][0];
        $colum['date_first_visit'] = $f_first_visita;
        $colum['date_delivered'] = $f_delivered;
        $colum['date_not_delivered'] = $f_not_delivered;
        
        $fields[$i] = $colum;
    }
    
    update_by_lots ($pdo, 'envios', 'id_ship', $fields);
    
    echo("Envíos actualizados con éxito");
    
} catch (PDOException $e) {

    echo("Ocurrió un error. " . $e);
}
?>