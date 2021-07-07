<div class="container">

<h2>Termek</h2>
<p><a href="<?= site_url('/rooms/new');?>">Új terem</a></p>
<table class="table table-sm table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Név</th>
      <th scope="col">Rövid név</th>
      <th scope="col">Megjegyzés</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($termek as $terem): ?>
    <tr>
        <td>#<?= $terem['id']; ?></td>
        <td><?= $terem['nev']; ?></td>
        <td><?= $terem['rovid_nev']; ?></td>
        <td><?= $terem['megjegyzes']; ?></td>
        <td><a href="<?= site_url('/rooms/edit/' . $terem['id']);?>">Szerkesztés</a> <a href="javascript:void(0);" onClick="confirmDelete(<?= $terem['id']?>)">Törlés</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

</div>

<script>
let delurl="<?= site_url('rooms/delete/');?>";
function confirmDelete(id) {
    if (confirm("Biztos törölni szeretnéd?"))
        window.location = delurl + id;
    else
        return false;    
}
</script>
