<div class="container">
    <div class="row">
        <div class="col-sm"><h1>Ferenczi <small class="text-muted">terembeosztás</small></h1></div>
        <div class="col-sm text-right enterdiv"><a href="/admin/index">Belépés</a></div>
    </div>

    <div class="dropdown topGap">
        <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="/img/ic_assignment_white_24dp_1x.png" width="24" height="24" /> Válassz osztályt</button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <?php foreach($osztalyok as $o): ?>
                <a href="/view/<?= $o['id']?>" class="dropdown-item"><?= $o['nev']?></a>
            <?php endforeach; ?>
      </div>
    </div>


    <h2 class="text-center font-weight-bold"><?= $osztaly['nev'] ?></h2>
    <div class="topGap">    
    <table class="table table-bordered table-hover">
        <thead class="thead-light">
        <tr>
            <td></td>
            <?php foreach($napok as $nap): ?>
                <td class="font-weight-bold text-center"><?= $nap ?></td>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php for($idopont = 0; $idopont < 9; $idopont++): ?>
            <tr>
            <td class=" font-weight-bold text-center"><?= $idopont ?>.</td>
            <?php for($napid = 0; $napid < 5; $napid++): ?>

                <td class="text-center">
                <?php if (count($beosztas[$napid][$idopont]) == 1): ?>
                    <?= $beosztas[$napid][$idopont][0] ?>
                <?php elseif (count($beosztas[$napid][$idopont]) > 1): ?>
                    <div class="row">
                    <?php foreach($beosztas[$napid][$idopont] as $terem): ?>
                        <div class="col-sm text-center hatarolo"><?= $terem ?></div>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                </td>

            <?php endfor; ?>
            </tr>

        <?php endfor; ?>
        </tbody>


    </table>
    </div>
   
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>