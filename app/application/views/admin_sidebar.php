<!-- Content Block (Contains Sidebars and central columns) -->
<div class="container-fluid text-center" id="content">
    <div class="row content">
        <div class="col-sm-3 sidenav" id="leftSide">
            <div class="card">
                <div class="card-header">
                    <h5>Admin</h5>
                </div>
                <div class="list-group">
                    <a href="<?php echo base_url('index.php/admin/emails'); ?>" class="list-group-item<?php if ($active_admin == "email_templates") { echo " active"; }; ?>">Email Templates</a>
                    <a href="<?php echo base_url('index.php/admin/settings'); ?>" class="list-group-item<?php if ($active_admin == "settings") { echo " active"; }; ?>">Settings</a>
                </div>
            </div>
        </div>
