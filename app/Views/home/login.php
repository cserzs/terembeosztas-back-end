<div class="container">
<div class="row">
    <div class="col-sm"><h1>Ferenczi <small>terembeosztás</small></h1></div>
</div> 

<?php if ($loginError > 0): ?>
    <div class="bg-danger" style="padding: 10px;">Érvénytelen felhasználónév vagy jelszó!</div>
<?php endif; ?>

<div class="row justify-content-md-center topGap">
    <div class="col-md-4 text-center">
    <form method="post" action="/login">
        <div class="form-group">
            <label for="inputUser">Felhasználónév</label>
            <input type="text" class="form-control" id="inputUser" placeholder="" name="username">
        </div>
        <div class="form-group">
            <label for="inputPassword">Jelszó</label>
            <input type="password" class="form-control" id="inputPassword" placeholder="" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Belépés</button>
    </form>
    </div>
</div>

<div class="topGap">
    <a href="/">Vissza</a>
</div>
</div>