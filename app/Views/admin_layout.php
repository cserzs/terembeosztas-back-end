<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/angular_material/1.1.0/angular-material.min.css">
    <link rel="stylesheet" href="/css/stilus.css">

    <title>Terembeosztás</title>
</head>
<body>
<nav class="navbar navbar-light" style="background-color: #e3f2fd;">
    <ul class="nav">
        <li class="nav-item"><a class="nav-link" href="/admin/assignment/edit">Szerkesztés</a></li>
        <li class="nav-item"><a class="nav-link" href="/admin/assignment/pdf" target="_blank">Nyomtatás (pdf)</a></li>
        <li class="nav-item"><a class="nav-link" href="/admin/rooms/empty/pdf" target="_blank">Üres termek (pdf)</a></li>
        <!--<li class="nav-item"><a class="nav-link" href="/admin/bindings/pdfA3" target="_blank">Nyomtatás A3</a></li>-->
        <li class="nav-item"><a class="nav-link" href="/rooms/index">Termek</a></li>        
        <li class="nav-item"><a class="nav-link" href="/schoolclass/index">Osztályok</a></li>        
        <li class="nav-item"><a class="nav-link" href="/admin/logout">Kilépés</a></li>
    </ul>
</nav>

<?php if (session()->getFlashdata('system_message') != null): ?>
<div class="alert alert-warning" role="alert">
    <?= session()->getFlashdata('system_message'); ?>
</div>
<?php endif; ?>

<?php echo $_page; ?>

</body>
</html>