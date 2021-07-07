<div class="container">
    <div class="row">
        <div class="col-sm"><h1>Ferenczi <small>terembeosztás</small></h1></div>
        <div class="col-sm text-right enterdiv"><a href="<?=site_url('admin/index');?>">Belépés</a></div>
    </div>

    <div class="dropdown topGap">
        <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="/img/ic_assignment_white_24dp_1x.png" width="24" height="24" /> Válassz osztályt</button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <?php foreach($osztalyok as $osztaly): ?>
                <a href="/view/<?= $osztaly['id']?>" class="dropdown-item"><?= $osztaly['nev']?></a>
            <?php endforeach; ?>
      </div>
    </div>
    
</div>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>