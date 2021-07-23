
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $titulo?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js" integrity="sha256-JG6hsuMjFnQ2spWq0UiaDRJBaarzhFbUxiUTxQDA9Lk=" crossorigin="anonymous"></script>
  <?php 
    foreach ($cabeceras_css as $fila) {?>
    <link type="text/css" rel="stylesheet" href="<?php echo $fila; ?>" />
    <?php 
  }
  ?>
    <link type="text/css" rel="stylesheet" href="<?php echo base_url("assets/hergo/print.css") ?>" media="print">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.20/af-2.3.4/b-1.6.1/b-colvis-1.6.1/b-flash-1.6.1/b-html5-1.6.1/b-print-1.6.1/cr-1.5.2/fc-3.3.0/fh-3.1.6/kt-2.5.1/r-2.2.3/rg-1.1.1/rr-1.2.6/sc-2.0.1/sl-1.3.1/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css"  href="<?php echo base_url("assets/dist/css/skins/skin-green.min.css") ?>">
    <link rel="stylesheet" type="text/css"  href="<?php echo base_url("assets/dist/css/skins/skin-red.min.css") ?>">
    <link rel="stylesheet" type="text/css"  href="<?php echo base_url("assets/dist/css/skins/skin-purple.min.css") ?>">
    <link rel="stylesheet" type="text/css"  href="<?php echo base_url("assets/dist/css/skins/skin-yellow.min.css") ?>">

  <?php 
    foreach ($cabeceras_script as $fila)
  {?>
    <script src="<?php echo $fila ?>"></script>
  <?php 
  }
  ?>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>