<!-- Content Block (Contains Sidebars and central columns) -->
<main class="container-fluid" id="content">
    <div class="jumbotron">
        <h1>Welcome to UC Finances</h1>
        <p class="lead">
            You are registering as <?php echo $user['fullname'] ?>, with username <?php echo $user['username'] ?>.
        </p>
        <p class="lead">
            Thank you for signing up to University College's Finance app. With this app you will file claims for expenses, set your bank details and track hours worked.
        </p>
        <p class="lead mt-5">
            Please enter the following details:
        </p>
        <div class="container">
            <form action="<?php echo site_url('/onboarding/submit'); ?>" method="post">
                <div class="form-group row">
                    <label for="onboarding_input_dob" class="col-md-3 col-form-label">Date of Birth</label>
                    <div class="col-md-9">
                        <input type="date" class="form-control" id="onboarding_input_dob" name="onboarding_input_dob">
                    </div>
                </div>

                <hr>

                <div class="form-group row">
                    <label for="onboarding_input_account_number" class="col-md-3 col-form-label">Bank Account number</label>
                    <div class="col-md-9">
                        <input type="date" class="form-control" id="onboarding_input_account_number" name="onboarding_input_account_number">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="onboarding_input_sort_code" class="col-md-3 col-form-label">Bank Sort Code</label>
                    <div class="col-md-9">
                        <input type="date" class="form-control" id="onboarding_input_sort_code" name="onboarding_input_sort_code">
                    </div>
                </div>
                <small id="emailHelp" class="form-text text-muted mb-4">The account you enter here will be used by the Treasurer repay you for expense claims. If you do not wish to enter this information, please contact the Treasurer or your Cost Centre manager.</small>
                
                <input type="submit" class="btn btn-primary btn-lg">
            </form>
        </div>
    </div>
</main>


<script type="text/javascript">
$(document).ready(function(){
    let now = new Date()
    let current_year = now.getFullYear()
    let earliest_year = current_year - 16
    $("#onboarding_input_dob").inputmask('datetime', {
        clearMaskOnLostFocus: false,
        inputFormat: 'dd/mm/yyyy',
        outputFormat: 'yyyy-mm-dd',
        max: ('dd/mm/' + earliest_year),
        removeMaskOnSubmit: true,
    })
    $('#onboarding_input_account_number').inputmask({
        mask: "99999999",
        clearMaskOnLostFocus: false,
    })
    $('#onboarding_input_sort_code').inputmask({
        mask: "99-99-99",
        clearMaskOnLostFocus: false,
        removeMaskOnSubmit: true,
    })
})
</script>
