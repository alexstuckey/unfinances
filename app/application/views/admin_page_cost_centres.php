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
                      <th scope="col">Cost Centre</th>
                      <th scope="col">Manager</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $count = 1;
                    foreach ($cost_centres as $cost_centre): ?>
                    <tr>
                      <th scope="row"><?php echo $count; $count++; ?></th>
                      <td><?php echo $cost_centre['cost_centre'] ?></td>
                      <td><?php echo $cost_centre['manager_id_cis'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
                <form action="<?php echo site_url('/admin/cost_centres/add'); ?>" method="post">
                    <div class="form-row align-items-center">
                        <div class="col-md-7">
                            <input type="text" class="form-control mb-2" id="newCostCentreName" name="newCostCentreName" placeholder="Cost Centre name">
                        </div>
                        <div class="col-md-5">
                            <button type="submit" class="btn btn-primary btn-block mb-2">Create Cost Centre</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-block">
                <h4 class="pb-3">Assign Cost Centres Managers</h4>
                <p>Appointing a new manager will replace the previous one.</p>

                <form action="<?php echo site_url('/admin/cost_centres/changeManager'); ?>" method="post">
                    <div class="form-row align-items-center">
                        <div class="col-md">
                            <select name="changeCostCentre" class="custom-select form-control mb-2">
                                <?php foreach ($cost_centres as $cost_centre): ?>
                                <option value="<?php echo $cost_centre['cost_centre']; ?>"><?php echo $cost_centre['cost_centre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg">
                            <input type="text" class="form-control mb-2" id="changeCostCentreManager" name="changeCostCentreManager" placeholder="Manager username">
                        </div>
                        <div class="col-lg">
                            <button type="submit" class="btn btn-primary btn-block mb-2">Appoint manager</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
