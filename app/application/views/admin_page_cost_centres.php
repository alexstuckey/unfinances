<div class="col-sm-8 text-left" id="centre">

    <div id="settings">
        <h1 class="pb-2">Cost Centres</h1>

        <?php if (isset($message)) {
            echo '<p class="alert alert-success">'.$message.'</p>';
        } elseif (isset($error)) {
            echo '<p class="alert alert-danger">'.$error.'</p>';
        }?>

        <?php echo validation_errors(); ?>

        <div class="card">
            <div class="card-block">
                <h4 class="pb-3">List of Cost Centres</h4>
                <p>Cost Centres will be disabled until they have a manager appointed to them.</p>
                <table class="table table-responsive">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Name</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $count = 1;
                    foreach ($cost_centres as $cost_centre): ?>
                    <tr>
                      <th scope="row"><?php echo $count; $count++; ?></th>
                      <td><?php echo $cost_centre['cost_centre'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
                <form action="<?php echo site_url('/admin/cost_centres/add'); ?>" method="post">
                    <div class="form-row align-items-center">
                        <div class="col-auto">
                            <input type="text" class="form-control mb-2" id="newCostCentreName" name="newCostCentreName" placeholder="Cost Centre name">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary mb-2">Create new Cost Centre</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
