<!DOCTYPE html>
<html lang="en" ng-app="homeApp">
    <head>
        <!-- Basic -->
        <meta charset="utf-8">
        <title><?php echo $title ?></title>
        <meta name="keywords" content="HTML5 Template" />
        <meta name="description" content="SportsCup - Bootstrap 4 Theme for Soccer And Sports">
        <meta name="author" content="iwthemes.com">

        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Theme CSS -->
        <link href="<?php echo base_url('assets/css/main.css') ?>" rel="stylesheet" media="screen">

        <!-- Favicons -->
        <link rel="shortcut icon" href="<?php echo base_url('assets/img/icons/favicon-2.jpg') ?>">
        <link rel="apple-touch-icon" href="<?php echo base_url('assets/img/icons/apple-touch-icon.png') ?>">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url('assets/img/icons/apple-touch-icon-72x72.png') ?>">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url('assets/img/icons/apple-touch-icon-114x114.png') ?>">

        <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

        <style type="text/css">
            
            .instagram-btn {
                margin-top: 70px;
            }


            body {
                background: url("../../../assets/img/background/background.jpg") no-repeat center fixed;
                background-size: cover;
                z-index: 0;
            }
            
            .hero-video {
                background: transparent;
            }

            #footer {
                background: rgb(16, 16, 16, .8);
            }

            .dark-home {
                background: rgb(16, 16, 16, .8);
            }

            .instagram-btn .btn-instagram {
                z-index: 1;
            }

            .logo-header {
                display: flex;
            }

            .logo-header h2 {
                color: #fff;
                font-weight: bold;
                text-transform: uppercase;
            }

            /* ================== MAIN CONTENT ================== */

            .main-content {
                flex-direction: column;
            }

            .main-content .nav {
                justify-content: center;
            }

            .main-content .nav-tabs {
                margin-top: -40px;
                border-bottom: 0;
                margin-bottom: 0; 
            }

            .main-content .nav-item {
                background-color: black;
                color: #4EDEB8;
            }

            .main-content .nav-item.active {
                background-color: #4EDEB8;
                color: black;
                border: 1px solid black;
                font-weight: bold;
            }

            .main-content .nav-tabs {
                min-height: 0 !important;
            }

            .main-content .nav-item:hover {
                opacity: 1;
            }

            .main-content .tab-pane {
                display: flex;
                padding: 20px 0;
            }

            #main-tab-content .single-result {
                background-color: transparent;
                width: 100%;
            }

            /* Lista de países */
            .panel-default>.panel-heading {
                padding: 0;
            }

            .panel-default>.panel-heading a {
                display: inline-block;
                height: 32px;
                width: 100%;
                line-height: 32px;
                padding-left: 12px;
            }

            .panel-open {
                border-color: #0055ff;
            }

            .panel-open > .panel-heading {
                background-color: #0000ff;
                color: #ff8c1a;
            }

            .hero-video {
                height: 27vh;
            }

            #nav-games > div > aside {
                display: none;
            }

            #nav-games > div > div {
                width: 100%;
                flex: 0 0 100%;
                max-width: 100%;        
            }

            @media screen and (min-width: 1199px) {
                
                #nav-games > div > aside {
                    display: block;
                }

                #nav-games > div > div {
                    flex: 0 0 75%;
                    max-width: 75%;
                }
                
            }

            .titles {
                margin-bottom: 0;
            }

            #head-matches {
                width: 100%;
                height: 20px;
                font-size: 1.1rem;
            }

            #list-matches {
                width: 100%;
                height: 500px;
            }

            /*----------------------------------------*/ 
            /* ODDS */

            .match {
                display: flex;
                height: 30px;
                border-left: 5px solid #01d099;
                border-bottom: 1px solid #ccc;
                font-size: 1.1rem;
                justify-content: center;
            }

            .hometeam {
                text-align: right;
            }
        
            .time {
                width: 12%;
            }

            .teams, .odds {
                width: 44%;
            }

            .teams div, .time div {
                text-align: center;
            }

            .odds {
                display: flex;
                justify-content: space-around;
            }

            .odds div {
                position: relative;
                float: left;
                width: 37px;
                height: 18px;
                color: #000;
                font-size: 11px;
                font-family: 'Roboto', Arial, Helvetica, sans-serif;
                line-height: 18px;
                text-align: center;
                background: #fdfdfd;
                cursor: pointer;
                margin: 1px;
                margin-top: 5px;
                border: 1px solid #01d099;
                box-sizing: border-box
            }

            .odds span {
                width: 100%;
                height: 100%;
                text-align: center;
                vertical-align: middle;
            }
            
            .plus-odds {
                margin: 7px;
                width: 10%;
            }

            .odd-selected {
                background: #ef8107 !important;
                color: white !important;
            }

            @media screen and (min-width: 576px) {
                
                .teams > .awayteam:before {
                    content: "x";
                    display: block;
                    width: 12px;
                    height: 28px;
                    text-align: center;
                    float: left;
                }

                .plus-odds {
                    width: 30px;
                }

                .odds {
                    width: 170px;
                }

                .time {
                    width: 20%;
                    display: flex;
                    justify-content: space-around;
                }

                .teams {
                    width: 50%;
                    display: flex;
                    justify-content: center;
                }

                .content-info {
                    width: 80%;
                    margin: 0 auto;
                }

                .day, .hour, .teams {
                    margin: 7px;
                }

            }

            @media screen and (min-width: 768px) {
                .content-info {
                    width: 70%;
                    margin: 0 auto;
                }
            }

            @media screen and (min-width: 992px) {
                .content-info {
                    width: 60%;
                    margin: 0 auto;
                }
            }

            @media screen and (min-width: 1199px) {
                .content-info {
                    width: 80%;
                    margin: 0 auto;
                }
            }

        </style>

    </head>

    <body ng-controller="homeCtrl">