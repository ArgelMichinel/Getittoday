<?php

function info_shipping($shipnumb,$ACCESS_TOK) {

    $cliente = curl_init();
    curl_setopt($cliente, CURLOPT_URL, 'https://api.mercadolibre.com/shipments/'.$shipnumb);
    curl_setopt($cliente, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($cliente, CURLOPT_HEADER, false);
    curl_setopt($cliente, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$ACCESS_TOK));
    curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($cliente);
    curl_close($cliente);

    //$datos=json_decode($result,true);

    return $result;
}

////////////////////////////////////////////////////////////

function info_user($pdo,$id_client) {
    
    $client = checkValdTok($pdo,$id_client);
    
    $cliente = curl_init();
    curl_setopt($cliente, CURLOPT_URL, 'https://api.mercadolibre.com/users/'.$id_client);
    curl_setopt($cliente, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($cliente, CURLOPT_HEADER, false);
    curl_setopt($cliente, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$client['access_tok']));
    curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($cliente);
    curl_close($cliente);

    $datos=json_decode($result,true);

    return $datos;
}

///////////////////////////////////////////////////////////////

function refresh_tok($APP_ID,$SECRET_KEY,$REFRESH_TOK) {

    $body = array(
                    'grant_type' => 'refresh_token',
                    'client_id' => $APP_ID,
                    'client_secret' => $SECRET_KEY,
                    'refresh_token' => $REFRESH_TOK
                );

    $headers_req =array(
                        'POST' => 'application/json',
                        'Content-type' => 'application/x-www-form-urlencoded'
                     );

    $cliente = curl_init();
    curl_setopt($cliente, CURLOPT_URL, 'https://api.mercadolibre.com/oauth/token');
    curl_setopt($cliente, CURLOPT_POST, TRUE);
    curl_setopt($cliente, CURLOPT_HEADER, false);
    curl_setopt($cliente, CURLOPT_HTTPHEADER, $headers_req);
    curl_setopt($cliente, CURLOPT_POSTFIELDS, $body);
    curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($cliente);
    curl_close($cliente);


    //print_r($result);

    $datos=json_decode($result,true);
    
    return $datos;
    
}

////////////////////////////////////////////////////////////////

function request_tok($code,$state,$APP_ID,$SECRET_KEY,$URL) {

    $body = array(
                    'grant_type' => 'authorization_code',
                    'client_id' => $APP_ID,
                    'client_secret' => $SECRET_KEY,
                    'code' => $code,
                    'redirect_uri' => $URL
                );

    $headers_req =array(
                        'POST' => 'application/json',
                        'Content-Type' => 'application/x-www-form-urlencoded'
                     );

    $cliente = curl_init();
    curl_setopt($cliente, CURLOPT_URL, "https://api.mercadolibre.com/oauth/token");
    curl_setopt($cliente, CURLOPT_POST, TRUE);
    curl_setopt($cliente, CURLOPT_HEADER, false);
    curl_setopt($cliente, CURLOPT_HTTPHEADER, $headers_req);
    curl_setopt($cliente, CURLOPT_POSTFIELDS, $body);
    curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($cliente);
    curl_close($cliente);

    //print_r($result);

    $datos=json_decode($result,true);
    
    return $datos;
}


///////////////////////////////////////////////////////
function insert($pdo, $table, $fields) {
    $query = 'INSERT INTO `' .$table. '` (';

    foreach ($fields as $key => $value) {

        $query .= '`' . $key . '`,';

    }

    $query = rtrim($query, ',');
    $query .= ') VALUES (';

    foreach ($fields as $key => $value) {

        $query .= ':' . $key . ',';

    }

    $query = rtrim($query, ',');
    $query .= ')';
    
    $fields = processDates($fields);
    
    query($pdo, $query, $fields);
}

///////////////////////////////////////////////////////
function delete($pdo, $table, $primaryKey, $id ) {
    $parameters = [':id' => $id];

    query($pdo, 'DELETE FROM `' . $table . '`WHERE `' . $primaryKey . '` = :id', $parameters);
}

///////////////////////////////////////////////////////
function update_access($pdo, $table, $fields) {
    
    $query = 'UPDATE `' .$table. '` SET ';
    foreach ($fields as $indi => $valor){
        $query .= "`". $indi ."` = :". $indi .",";
    }
    
    $query = rtrim($query, ',');
    $query .= ' WHERE `user_id` = :primaryKey';
    
    $fields = processDates($fields);
    
    // Set the :primaryKey variable
    $fields['primaryKey'] = $fields['user_id'];
    
    query($pdo, $query, $fields);
}

////////////////////////////////////////////////////////

function update($pdo, $table, $primaryKey, $fields) {

    $query = ' UPDATE `' . $table .'` SET ';
    foreach ($fields as $key => $value) {
        $query .= '`' . $key . '` = :' . $key . ',';
    }

    $query = rtrim($query, ',');
    $query .= ' WHERE `' . $primaryKey . '` = :primaryKey';
    
    // Set the :primaryKey variable
    
    $fields['primaryKey'] = $fields[$primaryKey];
    $fields = processDates($fields);
    query($pdo, $query, $fields);
}

////////////////////////////////////////////////////////

function processDates($fields) {
    
    foreach ($fields as $key => $value) {
        if ($value instanceof DateTime) {
            $fields[$key] = $value->format('Y-m-d H:i:s');
        }
    }
    
    return $fields;
}

////////////////////////////////////////////////////////

function save($pdo, $table, $primaryKey, $record) {
    try {
        
        insert($pdo, $table, $record);
        
    }
    catch (PDOException $e) {
        update($pdo, $table, $primaryKey, $record);
        
    }
}

////////////////////////////////////////////////////////

function ask_client($pdo,$id_client) {
    
    $dat_user = findById($pdo,'Access','user_id',$id_client);
    
    return $dat_user;
}

///////////////////////////////////////////////////////

function checkValdTok($pdo,$id_client) {
	$timeNow =  new DateTime();
	
	$dat_user = ask_client($pdo,$id_client);
    $time_access = new DateTime($dat_user['fec_hora']);
    
    $interva = $time_access -> diff($timeNow);
    $interva = Diff_On_Sec ($interva);
    
    if ($interva > 648000) {
            $message = 'Usuario con credenciales expiradas';
    } elseif (($interva > 21600) and ($interva < 648000)) {
            include 'Datosprogram.php';
            $datos = refresh_tok($APP_ID,$SECRET_KEY,$dat_user['refresh_tok']);
            update($pdo, 'Access', 'user_id', ['user_id' => $datos["user_id"],
                                       'access_tok' => $datos["access_token"],
                                       'refresh_tok' => $datos["refresh_token"],
                                       'fec_hora' => new DateTime()]);
            /*update_access($pdo, 'Access', ['user_id' => $datos["user_id"],
                                       'access_tok' => $datos["access_token"],
                                       'refresh_tok' => $datos["refresh_token"],
                                       'fec_hora' => new DateTime()]);*/
            $dat_user = ['user_id' => $datos["user_id"],
                         'access_tok' => $datos["access_token"],
                         'refresh_tok' => $datos["refresh_token"],
                         'fec_hora' => new DateTime()];
    } 
    
    return $dat_user;
	
}

///////////////////////////////////////////////////////

function Diff_On_Sec ($interva){
$interva = ($interva -> format('%Y'))*365*24*60*60 +
           ($interva -> format('%m'))*30*24*60*60 +
           ($interva -> format('%d'))*24*60*60 +
           ($interva -> format('%H'))*60*60 +
           ($interva -> format('%i'))*60 +
           ($interva -> format('%s'));
    
return $interva;
    }

///////////////////////////////////////////////////////

function insert_by_lots ($pdo, $table, $fields) {
    
    $pdo->beginTransaction();
    
    $n_data = count($fields);
    for ($i = 0; $i < $n_data; $i++) {
        
        $colum = $fields[$i];
        
        $query = 'INSERT INTO `' .$table. '` (';

        foreach ($colum as $key => $value) {

            $query .= '`' . $key . '`,';

        }

        $query = rtrim($query, ',');
        $query .= ') VALUES ';
        
        
        $query .= '(';
        
        foreach ($colum as $key => $value) {

            $query .= ':' . $key . ',';

        }
        $query = rtrim($query, ',');
        $query .= '), ';
        
        $colum = processDates($colum);
        
        $query = rtrim($query, ', ');
        query($pdo, $query, $colum);
        
    }
    
    $pdo->commit();    
    
}

///////////////////////////////////////////////////////

function query_customized ($pdo,$parameters) {
    
    $query='SELECT * FROM `envios` WHERE ';
    
    $datos = [];
        
    if (isset($parameters['incl_client'])) {
            $query.= '`sender_id` = :client AND';
            $datos['client'] = $parameters['client'];
    }
    if (isset($parameters['incl_cadete'])) {
            if ($parameters['cadete'] == 'none') {
                $query.= '`cadete` IS NULL AND';
            } else {
                $query.= '`cadete` = :cadete AND';
                $datos['cadete'] = $parameters['cadete'];
            }
    }
    if (isset($parameters['incl_ayer'])) {
        $parameters['incl_date'] = false;
        $parameters['end_date'] = date("Y-m-d");
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $dia_ayer = strtotime('-1 day', strtotime($parameters['end_date']));
        $dia_ayer = date('Y-m-d', $dia_ayer);
        $parameters['begin_date'] = $dia_ayer;
            
        $query.= '`date_in` >= :begin_date AND `date_in` < :end_date AND';
        $datos['begin_date'] = $parameters['begin_date'];
        $datos['end_date'] = $parameters['end_date'];
            
    }
    if (isset($parameters['incl_hoy'])) {
        $parameters['incl_date'] = false;
        $parameters['incl_ayer'] = false;
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $parameters['begin_date'] = date("Y-m-d");
        $dia_manana = strtotime('+1 day', strtotime($parameters['begin_date']));
        $dia_manana = date('Y-m-d', $dia_manana);
        $parameters['end_date'] = $dia_manana;
            
        $query.= '`date_in` >= :begin_date AND `date_in` < :end_date AND';
        $datos['begin_date'] = $parameters['begin_date'];
        $datos['end_date'] = $parameters['end_date'];
            
    }
    if (isset($parameters['incl_date'])) {
            if (isset($parameters['begin_date']) && isset($parameters['end_date'])) {
                $query.= '`date_in` >= :begin_date AND `date_in` < :end_date AND';
                $datos['begin_date'] = $parameters['begin_date'];
                $datos['end_date'] = $parameters['end_date'];
            }
    }
    if (isset($parameters['incl_zona'])) {
        switch ($parameters['zona']) {
          case "Zona 1":
            $query.= '`zip_code` >= :zip_min AND `zip_code` < :zip_max AND';
            $datos['zip_min'] = 0;
            $datos['zip_max'] = 1500;
            break;
            case "Zona 2":
                $query.= '`zip_code` IN (
                    1602, 1603, 1604, 1605, 1606, 1607, 1609, 1636, 1637, 1637, 1638, 1639, 1640, 1641, 1642, 1643, 1644, 1645, 1649, 
                    1650, 1651, 1652, 1653, 1654, 1655, 1657, 1672, 1674, 1675, 1676, 1678, 1682, 1683, 1684, 1685, 1686, 1687, 1688, 
                    1689, 1690, 1691, 1692, 1701, 1702, 1703, 1704, 1706, 1707, 1708, 1710, 1712, 1713, 1714, 1715, 1721, 1751, 1752, 
                    1753, 1754, 1766, 1768, 1770, 1771, 1772, 1773, 1774, 1785, 1809, 1821, 1822, 1823, 1824, 1825, 1826, 1827, 1828, 
                    1829, 1831, 1832, 1833, 1834, 1835, 1836, 1868, 1869, 1870, 1871, 1872, 1873, 1874, 1875
                                        ) AND';
            break;
            case "Zona 3":
                $query.= '`zip_code` IN (
                    1608, 1610, 1611, 1612, 1613, 1614, 1615, 1616, 1617, 1618, 1621, 1622, 1624, 1646, 1648, 1659, 1660, 1661, 1662, 
                    1663, 1664, 1665, 1666, 1670, 1716, 1718, 1722, 1723, 1724, 1736, 1738, 1740, 1742, 1743, 1744, 1745, 1746, 1750, 
                    1755, 1756, 1757, 1758, 1759, 1761, 1763, 1764, 1765, 1776, 1778, 1780, 1781, 1786, 1789, 1801, 1802, 1803, 1804, 
                    1805, 1806, 1807, 1812, 1813, 1837, 1838, 1839, 1840, 1841, 1842, 1843, 1844, 1845, 1846, 1847, 1848, 1849, 1850, 
                    1851, 1852, 1853, 1854, 1855, 1856, 1857, 1858, 1859, 1860, 1861, 1862, 1863, 1867, 1876, 1877, 1878, 1879, 1880, 
                    1881, 1882, 1883, 1884, 1885, 1886, 1887, 1888, 1889, 1890, 1891, 1892, 1893, 1916
                                        ) AND';
            break;
          default:
            $packets[$i]['country'] = "Argentina";
        }
    }
    if (isset($parameters['incl_comercial'])) {
            
        $query.= '`delivery_preference` = 1 AND';
        $datos['begin_date'] = $parameters['begin_date'];
        $datos['end_date'] = $parameters['end_date'];
            
    }
    
    $query = rtrim($query, ' AND');
    
    $result = query($pdo, $query, $datos);
    
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

///////////////////////////////////////////////////////

function update_by_lots ($pdo, $table, $primaryKey, $fields) {
    
    $pdo->beginTransaction();
    
    $n_data = count($fields);
    for ($i = 0; $i < $n_data; $i++) {
        
        $colum = $fields[$i];
        /////////////////////////////////////////////////
        $query = ' UPDATE `' . $table .'` SET ';
        foreach ($colum as $key => $value) {
            $query .= '`' . $key . '` = :' . $key . ',';
        }

        $query = rtrim($query, ',');
        $query .= ' WHERE `' . $primaryKey . '` = :primaryKey';

        // Set the :primaryKey variable

        $colum['primaryKey'] = $colum[$primaryKey];
        //////////////////////////////////////////////////
        
        $colum = processDates($colum);
        
        query($pdo, $query, $colum);
        
    }
    
    $pdo->commit();    
    
}

//////////////////////////////////////////////////////
function print_answer ($shipnum,$ACCESS_TOK,$sender_id,$sticker) {
    $shipping = info_shipping($shipnum,$ACCESS_TOK);

    $hash_code = $sticker;

    $matriz_QR = [];
    $matriz_QR['id']= $shipnum;
    $matriz_QR['sender_id']= $sender_id;   
    $matriz_QR['hash_code']= substr($hash_code, 0, -1);  
    $matriz_QR['security_digit']= substr($hash_code, -1);;

    $sticker = json_encode($matriz_QR);

    //////////////////////////////////////
    $shipping_res = json_decode($shipping,true);
    
    $shipping = [];
    $shipping[0] = $shipnum;                   //'id_ship'
    $shipping[1] = new DateTime();             //'date_in'
    $shipping[2] = $shipping_res['status'];     //'status'
    $shipping[3] = $sender_id;                  //'sender_id'
    $shipping[4] = $shipping_res['order_id'];        //'order_id'
    $shipping[5] = $sticker;                      //'Etiqueta'
    
    $address = [];
    $address[0] = $shipping_res['receiver_address']['street_name'];        //'street_name'
    $address[1] = $shipping_res['receiver_address']['street_number'];      //'street_number'
    $address[2] = $shipping_res['receiver_address']['comment'];            //'comment'
    $address[3] = $shipping_res['receiver_address']['zip_code'];           //'zip_code'
    $address[4] = $shipping_res['receiver_address']['city']['name'];       //'city'
    $address[5] = $shipping_res['receiver_address']['state']['name'];      //'state'
    $address[6] = $shipping_res['receiver_address']['country']['name'];    //'country'
    $address[7] = $shipping_res['receiver_address']['latitude'];           //'latitude'
    $address[8] = $shipping_res['receiver_address']['longitude'];          //'longitude'
    $address[9] = $shipping_res['receiver_address']['geolocation_last_updated'];    //'geolocation_last_updated'
    $address[10] = $shipping_res['receiver_address']['delivery_preference'];        //'delivery_preference'

    $receiver_per = [];
    $receiver_per[0] = $shipping_res['receiver_address']['receiver_name'];      //'receiver_name'
    $receiver_per[1] = $shipping_res['receiver_address']['receiver_phone'];      //'receiver_phone'
    
    $shipping_items = [];
    $shipping_items[0] = $shipping_res['shipping_items'][0]['description'];          //'description'
    $shipping_items[1] = $shipping_res['shipping_items'][0]['dimensions'];            //'dimensions'
    
    $delivery = [];
    $delivery[0] = $shipping_res['status_history']['date_first_visit'];     //'date_first_visit'
    $delivery[1] = $shipping_res['status_history']['date_delivered'];       //'date_delivered'
    $delivery[2] = $shipping_res['status_history']['date_not_delivered'];    //'date_not_delivered'

    $ship_mat = [$shipping, $address, $receiver_per, $shipping_items, $delivery];
    
    //////////////////////////////////////
    
    return $ship_mat;
}

//////////////////////////////////////////////////////
function basico ($shipnum,$ACCESS_TOK,$sender_id) {
    $shipping = info_shipping($shipnum,$ACCESS_TOK);

    //////////////////////////////////////
    $shipping_res = json_decode($shipping,true);
    
    $shipping = [];
    $shipping[0] = $shipnum;                   //'id_ship'
    $shipping[1] = new DateTime();             //'date_in'
    $shipping[2] = $shipping_res['status'];     //'status'
    $shipping[3] = $sender_id;                  //'sender_id'
    $shipping[4] = $shipping_res['order_id'];        //'order_id'
    
    $address = [];
    $address[0] = $shipping_res['receiver_address']['street_name'];        //'street_name'
    $address[1] = $shipping_res['receiver_address']['street_number'];      //'street_number'
    $address[2] = $shipping_res['receiver_address']['comment'];            //'comment'
    $address[3] = $shipping_res['receiver_address']['zip_code'];           //'zip_code'
    $address[4] = $shipping_res['receiver_address']['city']['name'];       //'city'
    $address[5] = $shipping_res['receiver_address']['state']['name'];      //'state'
    $address[6] = $shipping_res['receiver_address']['country']['name'];    //'country'
    $address[7] = $shipping_res['receiver_address']['latitude'];           //'latitude'
    $address[8] = $shipping_res['receiver_address']['longitude'];          //'longitude'
    $address[9] = $shipping_res['receiver_address']['geolocation_last_updated'];    //'geolocation_last_updated'

    $receiver_per = [];
    $receiver_per[0] = $shipping_res['receiver_address']['receiver_name'];      //'receiver_name'
    $receiver_per[1] = $shipping_res['receiver_address']['receiver_phone'];      //'receiver_phone'
    
    $shipping_items = [];
    $shipping_items[0] = $shipping_res['shipping_items'][0]['description'];          //'description'
    $shipping_items[1] = $shipping_res['shipping_items'][0]['dimensions'];            //'dimensions'
    
    $delivery = [];
    $delivery[0] = $shipping_res['status_history']['date_first_visit'];     //'date_first_visit'
    $delivery[1] = $shipping_res['status_history']['date_delivered'];       //'date_delivered'
    $delivery[2] = $shipping_res['status_history']['date_not_delivered'];    //'date_not_delivered'

    $ship_mat = [$shipping, $address, $receiver_per, $shipping_items, $delivery];
    
    //////////////////////////////////////
    
    return $ship_mat;
}