<div class="container-fluid" id="content">
    <div class="row content justify-content-center">

        <div class="col-lg-8" id="centre">

            <div id="settings">
                <h1 class="pb-2">Settings</h1>

                <?php if (isset($message)) {
                    echo '<p class="alert alert-success">'.$message.'</p>';
                } elseif (isset($error)) {
                    echo '<p class="alert alert-danger">'.$error.'</p>';
                }?>

                <?php echo validation_errors(); ?>


<style type="text/css">
    .form-control.uneditable {
        color: #212529;
        -webkit-text-fill-color: #212529;
    }
</style>

                <div class="card">
                    <div class="card-block">
                        <h4 class="pb-3">User Details</h4>

                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-3 col-form-label">Full name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control uneditable" disabled value="<?php echo $userAccount['fullname']; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control uneditable" disabled value="<?php echo $userAccount['email']; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-3 col-form-label">Username</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control uneditable" disabled value="<?php echo $userAccount['username']; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control uneditable" disabled value="<?php echo $userAccount['fullname']; ?>">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-block">
                        <h4 class="pb-3">Account Details</h4>

                        <form action="<?php echo site_url('/settings'); ?>" method="post">
                            <div class="form-group row">
                                <label for="settings_input_dob" class="col-md-3 col-form-label">Date of Birth</label>
                                <div class="col-md-9">
                                    <?php
                                    // Convert ISO date to UK format for the inputmask
                                    $day = substr($userAccount['dob'], 8, 2);
                                    $month = substr($userAccount['dob'], 5, 2);
                                    $year = substr($userAccount['dob'], 0, 4);
                                    ?>
                                    <input type="date" class="form-control" id="settings_input_dob" name="settings_input_dob" value="<?php echo $day . "/" . $month . "/" . $year; ?>">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label for="settings_input_account_number" class="col-md-3 col-form-label">Bank Account number</label>
                                <div class="col-md-9">
                                    <input type="date" class="form-control" id="settings_input_account_number" name="settings_input_account_number" value="<?php echo $userAccount['bank_account_number']; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="settings_input_sort_code" class="col-md-3 col-form-label">Bank Sort Code</label>
                                <div class="col-md-9">
                                    <input type="date" class="form-control" id="settings_input_sort_code" name="settings_input_sort_code" value="<?php echo $userAccount['bank_sort_code']; ?>">
                                </div>
                            </div>
                            <small id="emailHelp" class="form-text text-muted mb-4">The account you enter here will be used by the Treasurer repay you for expense claims. If you do not wish to enter this information, please contact the Treasurer or your Cost Centre manager.</small>
                            
                            <input type="submit" class="btn btn-primary btn-lg">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    let now = new Date()
    let current_year = now.getFullYear()
    let earliest_year = current_year - 16
    $("#settings_input_dob").inputmask('datetime', {
        clearMaskOnLostFocus: false,
        inputFormat: 'dd/mm/yyyy',
        outputFormat: 'yyyy-mm-dd',
        max: ('dd/mm/' + earliest_year),
        removeMaskOnSubmit: true,
    })
    $('#settings_input_account_number').inputmask({
        mask: "99999999",
        clearMaskOnLostFocus: false,
        removeMaskOnSubmit: true,
    })
    $('#settings_input_sort_code').inputmask({
        mask: "99-99-99",
        clearMaskOnLostFocus: false,
        removeMaskOnSubmit: true,
    })
})
</script>
