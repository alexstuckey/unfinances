<!-- Content Block (Contains Sidebars and central columns) -->
<main class="container-fluid" id="content">
    <div class="jumbotron" id="welcome-jumo">
        <h1>Welcome to UC Finances</h1>
        <p class="lead">
            Follow the buttons below to file a claim or review your expenses.
        </p>
    </div>
    <div class="jumbotron">
        <div class="row justify-content-center">
            <div class="col-md-4 pb-3 pb-md-0">
                <a href="<?php echo site_url('/expenses/claim/new'); ?>" class="btn btn-primary btn-block" role="button">New Claim</a>
            </div>
            <div class="col-md-4 pb-3 pb-md-0">
                <a href="<?php echo site_url('/expenses/my'); ?>" class="btn btn-primary btn-block" role="button">My Expenses</a>
            </div>
            <?php if ((!empty($userAccount['is_treasurer']) && ($userAccount['is_treasurer'] == TRUE))
                   || (!empty($userAccount['is_CostCentreManager']) && ($userAccount['is_CostCentreManager'] == TRUE))): ?>
            <div class="col-md-4">
                <a href="<?php echo site_url('/expenses/review'); ?>" class="btn btn-primary btn-block" role="button">Expenses Review</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<style type="text/css">
   #welcome-jumo {
        position: relative;
    }
    #welcome-jumo:after {
        content: "";
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        position: absolute;

        z-index: 1;

        background-image: url('<?php echo base_url('/static/images/uc-crest.svg'); ?>');
        background-repeat: no-repeat;
        background-position: right 10px top 50%;
        background-size: auto 85%;
        opacity: 0.5;
    }
    #welcome-jumo h1 {
        z-index: 2;
    }
</style>
