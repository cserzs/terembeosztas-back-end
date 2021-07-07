<div class="container">

<h2>Osztályok</h2>
<p><a href="<?= site_url('/schoolclass/new');?>">Új osztály</a></p>
<table class="table table-sm table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Név</th>
      <th scope="col">Rövid név</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($osztalyok as $osztaly): ?>
    <tr>
        <td>#<?= $osztaly['id']; ?></td>
        <td><?= $osztaly['nev']; ?></td>
        <td><?= $osztaly['rovid_nev']; ?></td>
        <td><a href="<?= site_url('/schoolclass/edit/' . $osztaly['id']);?>">Szerkesztés</a> <a href="javascript:void(0);" onClick="confirmDelete(<?= $osztaly['id']?>)">Törlés</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

</div>

<script>
let delurl="<?= site_url('schoolclass/delete/');?>";
function confirmDelete(id) {
    if (confirm("Biztos törölni szeretnéd?"))
        window.location = delurl + id;
    else
        return false;    
}
</script>
