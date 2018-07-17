<div class="col-sm-8 text-left" id="centre">

    <div id="settings">
        <h1 class="pb-2">Settings</h1>

        <?php if (isset($message)) {
            echo '<p class="alert alert-success">'.$message.'</p>';
        } elseif (isset($error)) {
            echo '<p class="alert alert-danger">'.$error.'</p>';
        }?>

        <?php echo validation_errors(); ?>

        <div class="card">
            <div class="card-block">
                <h4 class="pb-3">Admins</h4>
                <table class="table table-responsive">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Username</th>
                      <th scope="col">Full name</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $count = 1;
                    foreach ($admins as $admin): ?>
                    <tr>
                      <th scope="row"><?php echo $count; $count++; ?></th>
                      <td><?php echo $admin['id_cis'] ?></td>
                      <td><?php echo $admin['fullname'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
                <form action="<?php echo site_url('/admin/settings/addAdmin'); ?>" method="post">
                    <div class="form-row align-items-center">
                        <div class="col-auto">
                            <input type="text" class="form-control mb-2" id="adminUsernameAdd" name="usernameAdd" placeholder="Username">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary mb-2">Add admin</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="card">
            <div class="card-block">
                <h4 class="pb-3">Treasurers</h4>
                <table class="table table-responsive">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Username</th>
                      <th scope="col">Full name</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $count = 1;
                    foreach ($treasurers as $treasurer): ?>
                    <tr>
                      <th scope="row"><?php echo $count; $count++; ?></th>
                      <td><?php echo $treasurer['id_cis'] ?></td>
                      <td><?php echo $treasurer['fullname'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
                <form action="<?php echo site_url('/admin/settings/addTreasurer'); ?>" method="post">
                    <div class="form-row align-items-center">
                        <div class="col-auto">
                            <input type="text" class="form-control mb-2" id="treasurerUsernameAdd" name="usernameAdd" placeholder="Username">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary mb-2">Add treasurer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>
</div>
