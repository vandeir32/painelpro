<?php

date_default_timezone_set("America/Sao_Paulo");
session_start();
include "./config/conexao.php";
$conexao = mysqli_connect($server, $user, $pass, $db);
if ($conexao->connect_error) {
    exit("Connection failed: " . $conexao->connect_error);
}
if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION["login"])) {
    header("Location: nivel.php");
    exit;
}
if (!file_exists("./config/conexao.php")) {
    echo "<script>window.location.href = \"install.php\";</script>";
}
if (isset($_POST["login"]) && isset($_POST["senha"])) {
    include "login.php";
}
$query = "SHOW COLUMNS FROM config LIKE 'logo'";
$result = mysqli_query($conexao, $query);
if (mysqli_num_rows($result) == 0) {
    $query = "ALTER TABLE config ADD logo VARCHAR(300) DEFAULT 'https://i.imgur.com/mqpTJPZ.png'";
    if (!mysqli_query($conexao, $query)) {
        echo "Error adding column: " . mysqli_error($conexao);
    }
} else {
    $query = "SELECT logo FROM config";
    $result = mysqli_query($conexao, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $logo = $row["logo"];
        mysqli_free_result($result);
        if (empty($logo)) {
            $query = "UPDATE config SET logo = 'https://i.imgur.com/mqpTJPZ.png'";
            if (!mysqli_query($conexao, $query)) {
                echo "Error updating logo: " . mysqli_error($conexao);
            }
        }
    }
}
$sql = "CREATE TABLE IF NOT EXISTS miracle_deviceid (\n    id INT(11) NOT NULL AUTO_INCREMENT,\n    userid INT(11) NOT NULL,\n    byid INT(11) NOT NULL,\n    device VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,\n    PRIMARY KEY (id)\n)";
if ($conexao->query($sql) !== true) {
    echo "Error creating table: " . $conexao->error;
}
$stmt = $conexao->prepare("SELECT logo, title FROM config");
$stmt->execute();
$stmt->bind_result($logo, $title);
$stmt->fetch();
$stmt->close();
$titulo = $title;
$logo = $logo;
date_default_timezone_set("America/Sao_Paulo");
$dataAtual = date("d");
$MesAtual = date("m");
$HoraAtual = date("H:i");
$diaSemana = date("w");
$nomesDias = ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"];
$nomeDiaAtual = $nomesDias[$diaSemana];


echo '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <title>'.$titulo.'  </title>
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.13/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.13/dist/sweetalert2.all.min.js"></script>
</head>
<body>
    <div class="content">
        <div class="text">
            <a class="navbar-brand m-0">
                <img src="'.$logo.'" alt="Logo" width="auto" height="150">
            </a>
        </div>
                <form class="login100-form validate-form" action="verificar.php" method="post">
            <div class="field">
                <input type="text" class="form-control" id="login" name="login" placeholder="Digite seu usuário" />
                <span class="fas fa-user"></span>
                <label>Usuario</label>
            </div>
            <div class="field">
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" />
                <span class="fas fa-lock"></span>
                <label>Senha</label>
            </div>
            <button>Entrar</button>
        </form>
    </div>
</body>
</html>'

?>