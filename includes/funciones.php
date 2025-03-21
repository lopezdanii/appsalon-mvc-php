<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//Funcion que revisa que el usuario está autenticado
function isAuth() : void{
    if(!isset($_SESSION['login'])){
        header('Location: /');
    }
}

//Devuelve true o false en funcion de si el elemento actual es distinto del siguiente, es decir es el ultimo elemento
function esUltimo(string $actual, string $proximo) : bool{
    if($actual !== $proximo){
        return true;
    } else{
        return false;
    }
}

//Funcion comprueba si el usuario es administrador
function isAdmin(){
    if(!isset($_SESSION['admin'])){
        header('Location: /');
    }
}
