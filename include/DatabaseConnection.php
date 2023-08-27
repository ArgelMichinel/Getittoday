<?php

$pdo = new PDO('mysql:host=localhost;dbname=Nombre de BD;charset=utf8', 'USUARIO DE LA BD', 'CAMBIO DE CLAVE PARA BD');

$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
