<?php require_once 'config.php'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="robots" content="index, follow" />
    <meta name="keywords" content="silkroad, Silkroad Online, MMORPG, Free to play, GameGami, online oyun, oyun, silk, gold, SRO, Bot, f2p, hardcore mmorpg, Online game, free online mmorpg, Free game, joymax, pc game, free download, download" />
    <meta name="Description" content="Silkroad Online dünyanın en çok oynanan ücretsiz MMORPG oyunlarının başında gelmektedir. Silkroad Online'da eski Çin, İslam ve Avrupa medeniyetlerinin derinliklerine gidecek ve PvP, zindan sistemleri, sonsuz kale savaşları ile en iyi kahramanlardan biri olmak için çarpışacaksınız!" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Silkroad Online - Ranking</title>

    <!-- Coded by m1xawy -->
    <link href="images/favicon.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <style>
        body,html {
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            color: #ffffff;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color:#FF0000;
            background: url('images/bg.png') 0 0 repeat;
        }
        #rankmain {
            width: 800px;
            height: 569px;
            background: url('images/rankbg.png') 0 0 no-repeat;
            position: absolute;
            top: 0;
            left: 0;
            overflow: hidden;
        }
        #rankmain #rankmenu_container {
            width: 800px;
            height: 44px;
            background: url('images/menubg.png') 0 0 no-repeat;
            margin: 5px auto 0 auto;
        }

        #rankmain #rankmenu_container #rankmenu_label {
            width: 100px;
            height: 36px;
            float: left;
            font-size: 16px;
            color: #c2e1a4;
            margin-top: 4px;
            text-align: center;
            line-height: 36px;
        }
        #rankmain #rankmenu_container ul {
            list-style-type: none;
            padding: 0;
            margin: 4px 0 0 3px;
            float: left;
            display: flex;
        }

        #rankmain #rankmenu_container ul li {
            display: inline;
            width: 116px;
            height: 34px;
            background: url('images/button_nonselected.png') 0 0 no-repeat;
        }
        #rankmain #rankmenu_container ul li.selected {
            background: url('images/button_selected.png') 0 0 no-repeat;
        }
        #rankmain #rankmenu_container ul li.selected a {
            color: #c2e1a4;
        }
        #rankmain #rankmenu_container ul li a {
            display: inline-block;
            color: #8c7c63;
            text-decoration: none;
            font-size: 12px;
            width: 116px;
            height: 34px;
            line-height: 34px;
            text-align: center;

        }
        #rankmain #rankmenu_container ul li a:hover {
            text-decoration: none;
        }
        .table_rank {
            margin: 0 auto;
            width: 792px;
            border-top: 1px solid #564e41;
            border-left: 1px solid #564e41;
            border-right: 1px solid #564e41;
        }
        .table_rank th {
            background-color: #211c17;
            font-size: 12px;

            color: #e8cf9a;
            border-bottom: 1px solid #564e41;
        }
        .table_rank .th1 { /* icon */
            width: 50px;
            height: 24px;
        }
        .table_rank .th2 { /* sÄ±ralama */
            width: 70px;
            height: 24px;
        }
        .table_rank .th3 { /* Ä±rk */
            width: 80px;
            height: 24px;
        }
        .table_rank .th4 { /* karakter adÄ± */
            width: 304px;
            height: 24px;
            text-align: left;
        }
        .table_rank .th5 { /* puan */
            width: 96px;
            height: 24px;
        }
        .table_rank .th6 { /* sÄ±ra deÄŸiÅŸimi */
            width: 100px;
            height: 24px;
        }
        .table_rank td {
            /*background-color: #000000;*/
            color: #8a834e;
            font-size: 12px;
            border-bottom: 1px solid #564e41;
        }
        .table_rank .td1 {
            width: 50px;
            height: 21px;
            text-align: center;
        }
        .table_rank .td2 {
            width: 70px;
            height: 21px;
            text-align: center;
        }
        .table_rank .td3 {
            width: 80px;
            height: 21px;
            text-align: center;
        }
        .table_rank .td4 {
            width: 304px;
            height: 21px;
            text-align: left;

        }
        .table_rank .td5 {
            width: 96px;
            height: 21px;
            text-align: center;

        }
        .table_rank .td6 {
            width: 100px;
            height: 21px;
            text-align: center;
        }

        #button_website {
            width: 133px;
            height: 30px;
            text-align: center;
            color: #a9985d;
            line-height: 30px;
            font-size: 12px;
            cursor: pointer;
            margin: 15px auto 0 auto;
            background: url(images/button_webpage.png) 0 0 no-repeat;
        }
        #button_website:hover {
            color: #d7c791;
        }
    </style>
</head>
<body>
<script type="text/javascript">
    function blankurl(val)
    {
        window.open(val, '_blank');
    }
</script>
