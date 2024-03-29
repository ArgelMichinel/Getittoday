<?php


///////////////////////////////////////////////////////

function query($pdo, $sql, $parameters =[]) {
	$query = $pdo->prepare($sql);
	$query->execute($parameters);
	
	return $query;
}

///////////////////////////////////////////////////////
function findById($pdo,$table,$primaryKey,$id) {
	$parameters=['id' => $id];
	
	$query=query($pdo,'SELECT * FROM `' .$table. '` WHERE ' .$primaryKey. '= :id', $parameters);
	
	return $query -> fetch();
}

///////////////////////////////////////////////////////
function findAll($pdo,$table) {
    $result = query($pdo, 'SELECT * FROM `' .$table. '`');
    
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

///////////////////////////////////////////////////////
function find300($pdo,$table) {
    $result = query($pdo, 'SELECT * FROM `' .$table. '` ORDER BY date_in DESC LIMIT 0, 300');
    
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

///////////////////////////////////////////////////////
function findSeveral($pdo,$table,$primaryKey,$id) {
	$parameters=['id' => $id];
	
	$query=query($pdo,'SELECT * FROM `' .$table. '` WHERE ' .$primaryKey. '= :id', $parameters);
	
	return $query->fetchAll();
}

///////////////////////////////////////////////////////
function IsLogged($pdo) {
        
    $admintrador = findByID($pdo,'administ','email',$_SESSION['user']);

    if ($admintrador['password'] != $_SESSION['password']) {

        session_destroy();
        header('location: index.php');

    }
}

///////////////////////////////////////////////////////
function IsLogged_client($pdo) {
        
    $administrador = findByID($pdo,'Access','email',$_SESSION['user']);

    if ($administrador['password'] != $_SESSION['password']) {
        
        session_destroy();
        header('location: index.php');

    }
}

///////////////////////////////////////////////////////
function Authentification($pdo, $user, $contra) {
    $num_errores = 0;
    $errores = [];
    
    if (empty($user)) {
            $num_errores += 1;
            $errores[] = 'El campo de email no puede quedar en blanco';
        } else {
            if (filter_var($user, FILTER_VALIDATE_EMAIL) == false) {
                $num_errores += 1;
                $errores[] = 'Dirección inválida de email';
            } else {
                // convert the email to lowercase
                $user = strtolower($user);
                // Search for the lowercase version of $author['email']
                $administra = findByID($pdo,'administ','email',$user);

                if (empty($administra)) {
                    $num_errores += 1;
                    $errores[] = 'Credenciales inválidas';
                } else {
                    if (!empty($contra)) {
                        if (!password_verify($contra,$administra['password'])) {
                            $num_errores += 1;
                            $errores[] = 'Credenciales inválidas';
                        } elseif (password_verify($contra,$administra['password'])) {
                            $_SESSION['user'] = $user;
                            $_SESSION['password'] = $administra['password'];
                            header('location: query_packets.php');
                            die();
                        }
                    } else {
                        $num_errores += 1;
                        $errores[] = 'Credenciales inválidas';
                    }
                }

            }
        }
    return $errores;
}

///////////////////////////////////////////////////////
function Authentification_cl($pdo, $user, $contra) {
    $num_errores = 0;
    $errores = [];
    
    if (empty($user)) {
            $num_errores += 1;
            $errores[] = 'El campo de email no puede quedar en blanco';
        } else {
            if (filter_var($user, FILTER_VALIDATE_EMAIL) == false) {
                $num_errores += 1;
                $errores[] = 'Dirección inválida de email';
            } else {
                // convert the email to lowercase
                $user = strtolower($user);
                // Search for the lowercase version of $author['email']
                $administra = findByID($pdo,'Access','email',$user);

                if (empty($administra)) {
                    $num_errores += 1;
                    $errores[] = 'Credenciales inválidas';
                } else {
                    if (!empty($contra)) {
                        if (!password_verify($contra,$administra['password'])) {
                            $num_errores += 1;
                            $errores[] = 'Credenciales inválidas';
                        } elseif (password_verify($contra,$administra['password'])) {
                            $_SESSION['user'] = $user;
                            $_SESSION['password'] = $administra['password'];
                            header('location: desk_client.php');
                            die();
                        }
                    } else {
                        $num_errores += 1;
                        $errores[] = 'Credenciales inválidas';
                    }
                }

            }
        }
    return $errores;
}