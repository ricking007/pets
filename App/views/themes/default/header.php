<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title><?php echo !empty($this->getTitle()) ? $this->getTitle() : DEFAULT_PAGE_TITLE; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo DEFAULT_PAGE_DESCRIPTION; ?>">
    <meta name="keywords" content="">
    <meta property="og:site_name" content="" />
    <meta property="og:title" content="" />
    <base href="<?= BASE_URL; ?>" />

    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Favicons -->
    <!-- <link href="img/favicon.png" rel="icon"> -->
    <!-- Main Stylesheet File -->
    <link href="css/style.css" rel="stylesheet">
    <?php
    if (isset($this->css) && is_array($this->css)) {
        foreach ($this->css as $css) {
            echo '<link href="' . $css . '"rel="stylesheet"/>' . PHP_EOL;
        }
    }
    ?>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index">PET´S</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item <?php echo $this->getActive('index'); ?>">
                    <a class="nav-link" href="index">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item <?php echo $this->getActive('cor'); ?>">
                    <a class="nav-link" href="cor">Cores</a>
                </li>
            </ul>
        </div>
    </nav>