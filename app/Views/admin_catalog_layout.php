<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="/css/bulma.min.css">
    <title>Terembeosztás</title>
    <link href="/css/app.0bfef050.css" rel="preload" as="style">
    <link href="/js/app.4dafa1c5.js" rel="preload" as="script">
    <link href="/js/chunk-vendors.d964c9d6.js" rel="preload" as="script">
    <link href="/css/app.0bfef050.css" rel="stylesheet">
</head>
<body>
<noscript><strong>We're sorry but terembeosztas-front-end doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
</noscript>

<?php if (session()->getFlashdata('system_message') != null): ?>
<div class="alert alert-warning" role="alert">
    <?= session()->getFlashdata('system_message'); ?>
</div>
<?php endif; ?>

<section class="hero is-small has-background-info-light mb-3">
    <div class="hero-body">
        <nav class="level">
          <div class="level-left">
            <div class="level-item mx-2"><a class="nav-link" href="/admin/assignment/edit">Szerkesztés</a></div>
            <div class="level-item mx-2"><a class="nav-link" href="/admin/assignment/pdf" target="_blank">Nyomtatás (pdf)</a></div>
            <div class="level-item mx-2"><a class="nav-link" href="/admin/rooms/empty/pdf" target="_blank">Üres termek (pdf)</a></div>
            <div class="level-item mx-2"><a class="nav-link" href="/rooms/index">Termek</a></div>
            <div class="level-item mx-2"><a class="nav-link" href="/schoolclass/index">Osztályok</a></div>
            <div class="level-item mx-2"><a class="nav-link" href="/admin/logout">Kilépés</a></div>
          </div>
        </nav>
    </div>
</section>

<div id="app"></div>

<script src="/js/chunk-vendors.d964c9d6.js">
</script>
<script src="/js/app.4dafa1c5.js">
</script>
</body>
</html>