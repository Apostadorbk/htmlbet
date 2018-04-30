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

        </style>

    </head>

    <body ng-controller="homeCtrl">