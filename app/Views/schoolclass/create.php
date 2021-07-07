
<div class="container">

<h2>Új osztáy</h2>

<?php if ( !empty($errors)): ?>
<div class="alert alert-danger" role="alert">
    <ul>
    <?php foreach ($errors as $error) : ?>
        <li><?= esc($error) ?></li>
    <?php endforeach ?>
    </ul>
</div>
<?php endif; ?>

<form method="post" action="<?=site_url('schoolclass/new');?>">
<div class="form-group">
  <label for="nev">Név</label> <input type="text" name="nev" value="<?= $osztaly['nev'];?>">
</div>

<div class="form-group">
  <label for="rovid_nev">Rövid név</label> <input type="text" name="rovid_nev" value="<?= $osztaly['rovid_nev'];?>" >
</div>

<div class="form-group">
  <label for="evfolyam">Évfolyam</label> <input type="text" name="evfolyam" value="<?= $osztaly['evfolyam'];?>"/> (csak egy szám)
</div>

<div class="form-group">
  <label for="betujel">Betűjel</label> <input type="text" name="betujel" value="<?= $osztaly['betujel'];?>"/> (egy karakter, a sorbarendezés miatt)
</div>

<button type="submit" class="btn btn-primary mb-2">Mentés</button>
</form>

</div>

