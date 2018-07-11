<!-- Content Block (Contains Sidebars and central columns) -->
<main class="container-fluid" id="content">
    <div class="jumbotron">
        <h1>My Expenses</h1>
        <p class="lead">
            Here are your expense claims.
        </p>
    </div>

    <table class="table table-striped table-bordered table-hover">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Status</th>
          <th scope="col">Date</th>
          <th scope="col">Cost Centre</th>
          <th scope="col">Description</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($claims as $claim): ?>
        <tr>
          <th scope="row"><?php echo $claim['id_claim']; ?></th>
          <td><?php echo $claim['status']; ?></td>
          <td><?php echo $claim['date']; ?></td>
          <td><?php echo $claim['cost_centre']; ?></td>
          <td><?php echo $claim['description']; ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
</main>
