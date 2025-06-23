<?php

function mensajeExito($mensaje) {
    return '<div class="alert alert-success" role="alert">' . $mensaje . '</div>';
}

function mensajeError($mensaje) {
    return '<div class="alert alert-danger" role="alert">' . $mensaje . '</div>';
}

function mensajeAdvertencia($mensaje) {
    return '<div class="alert alert-warning" role="alert">' . $mensaje . '</div>';
}

function mensajeInfo($mensaje) {
    return '<div class="alert alert-info" role="alert">' . $mensaje . '</div>';
}

?>
