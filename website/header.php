<?php
require_once 'config.php';
session_start();
ob_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,  minimum-scale=1.0">
    <meta name="author" content="m1xawy">
    <meta name="title" content="SilkroadR">
    <meta name="description" content="Silkroad Online is a World's first blockbuster Free to play MMORPG. Silkroad Olnine puts players deep into ancient Chinese, Islamic, and European civilization. Enjoy Silkroad's hardcore PvP, personal dungeon system, never ending fortress war and be the top of the highest heroes!">
    <meta name="keywords" content="silkroad, MMORPG, Free to play, f2p, hardcore mmorpg, Online game, free online mmorpg, Free game, joymax, pc game, free download, download">
    <title><?= $serverName ?><?= !empty($title) ? ' - ' . $title : '' ?></title>
    <link rel="shortcut icon" href="/assets/img/favicon.ico">

    <link rel="stylesheet" type="text/css" href="/assets/css/index_style.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/page_style.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/footer.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/slick.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/sign_style.css">
    <script src="https://kit.fontawesome.com/933b0b2941.js"></script>

    <!-- Coded by m1xawy -->
    <link rel="stylesheet" type="text/css" href="/assets/css/m1xawy.css">
</head>
<body class="webp">
<div class="container">
    <header>
        <div class="topbar">
            <a class="digeam-top-logo" href="/" style="background: url(/assets/img/logo.png) no-repeat center center;background-size: contain;"></a>
            <div class="left-nav">
                <div class="nav-mobile">
                    <a id="nav-toggle" href="#!"><i class="fas fa-bars"></i></a>
                </div>
                <div class="header-menu" id="header-menu">
                    <button class="menu-close menu-close-r">
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 31.112 31.112" style="enable-background:new 0 0 31.112 31.112;" xml:space="preserve">
                        <polygon points="31.112,1.414 29.698,0 15.556,14.142 1.414,0 0,1.414 14.142,15.556 0,29.698 1.414,31.112 15.556,16.97 29.698,31.112 31.112,29.698 16.97,15.556 " fill="#fff">
                    </button>
                    <a id="digeam-top-logo-w" href="/"></a>
                    <a href="/">NEWS</a>
                    <a href="/download.php">DOWNLOAD</a>
                    <a href="/ranking/ranking.php">RANKING</a>
                    <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true){ ?>
                    <a href="/account/donate.php">DONATE</a>
                    <?php } ?>
                    <a href="https://discord.gg/HuJPdPSKA5" target="_blank">DISCORD</a>
                </div>
            </div>
            <div class="right-nav">
                <div class="nav-mobile-r">
                    <a id="nav-toggle-r" href="#!"><i class="fas fa-user"></i></a>
                </div>
                <div class="member-menu" id="member-menu">
                    <button class="menu-close menu-close-r">
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 31.112 31.112" style="enable-background:new 0 0 31.112 31.112;" xml:space="preserve">
                        <polygon points="31.112,1.414 29.698,0 15.556,14.142 1.414,0 0,1.414 14.142,15.556 0,29.698 1.414,31.112 15.556,16.97 29.698,31.112 31.112,29.698 16.97,15.556 " fill="#fff">
                    </button>
                    <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true){ ?>
                        <a id="signin-btn-pc" href="/account/panel.php" title="Account"><span class="menu-icn"><i class="fa fa-user"></i></span>ACCOUNT</a>
                    <?php }else { ?>
                        <div id="signin-btn-m">
                            <a href="/login.php" title="Login"><i class="fas fa-sign-in-alt"></i>Login</a>
                        </div>
                        <a id="signin-btn-pc" href="/login.php" title="Login"><span class="menu-icn"><i class="fas fa-sign-in-alt"></i></span>LOGIN</a>
                        <a href="/register.php" title="Register"><span class="menu-icn"><i class="fas fa-user-plus"></i></span>REGISTER</a>
                    <?php } ?>
                </div>
            </div>
            <div class="navbar-overlay"></div>
        </div>
    </header>
    <button class="gototop"><i class="fas fa-angle-up"></i></button>
