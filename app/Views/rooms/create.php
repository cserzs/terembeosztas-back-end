
<div class="container">

<h2>Új terem</h2>

<?php if ( !empty($errors)): ?>
<div class="alert alert-danger" role="alert">
    <ul>
    <?php foreach ($errors as $error) : ?>
        <li><?= esc($error) ?></li>
    <?php endforeach ?>
    </ul>
</div>
<?php endif; ?>

<form method="post" action="<?=site_url('rooms/new');?>">
<div class="form-group">
  <label for="nev">Név</label> <input type="text" name="nev" value="<?= $terem['nev'];?>">
</div>

<div class="form-group">
  <label for="rovid_nev">Rövid név</label> <input type="text" name="rovid_nev" value="<?= $terem['rovid_nev'];?>" >
</div>

<div class="form-group">
  <label for="megjegyzes">Megjegyzés</label> <input type="text" name="megjegyzes" value="<?= $terem['megjegyzes'];?>"/> (max 50 karakter)
</div>

<button type="submit" class="btn btn-primary mb-2">Mentés</button>
</form>

</div>

