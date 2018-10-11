<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <?php $this->load->helper('url'); ?>
        <title><?php if (isset($page_title)) { echo $page_title; } else { echo "No Title"; }; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- Vendor JS -->
        <script src="<?php echo base_url('/static/js/jquery.min.js'); ?>"></script>
        <script src="<?php echo base_url('/static/js/bootstrap.min.js'); ?>"></script>

<?php if (!empty($javascript_jsgrid) && ($javascript_jsgrid == true)): ?>
        <script src="<?php echo base_url('/static/js/jsgrid.min.js'); ?>"></script>
<?php endif; ?>

<?php if (!empty($javascript_uppy) && ($javascript_uppy == true)): ?>
        <script src="<?php echo base_url('/static/js/uppy.min.js'); ?>"></script>
<?php endif; ?>

<?php if (!empty($javascript_inputmask) && ($javascript_inputmask == true)): ?>
        <script src="<?php echo base_url('/static/js/inputmask.min.js'); ?>"></script>
        <script src="<?php echo base_url('/static/js/inputmask.extensions.min.js'); ?>"></script>
        <script src="<?php echo base_url('/static/js/inputmask.numeric.extensions.min.js'); ?>"></script>
        <script src="<?php echo base_url('/static/js/inputmask.date.extensions.min.js'); ?>"></script>
        <script src="<?php echo base_url('/static/js/jquery.inputmask.min.js'); ?>"></script>
<?php endif; ?>

        <!-- App JS -->
        <!-- <script src="<?php echo base_url('/static/js/homepage.js'); ?>"></script> -->


        <!-- Vendor CSS -->
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=EB+Garamond:400,600">
        <link rel="stylesheet" href="<?php echo base_url('/static/css/vendor.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('/static/css/claim.min.css'); ?>">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">

        <!-- App CSS -->
        <link rel="stylesheet" href="<?php echo base_url('/static/css/main.css'); ?>" type="text/css">

        <!-- Icon -->
        <!-- <link rel="icon" type="image/png" href="<?php echo base_url('/static/images/favicon-32x32.png'); ?>" sizes="32x32"> -->
    </head>

    <body>
        <!-- Webpage -->
        <div id="page">

            <nav class="navbar navbar-expand-md bg-dark navbar-dark fixed-top">
                <img src="<?php echo base_url('/static/images/uc-crest.svg'); ?>" height="40px" class="pr-2" style="filter: brightness(0) invert(1);">
                <a class="navbar-brand" href="<?php echo site_url('/home'); ?>">UC Finances</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                    <ul class="navbar-nav mr-auto">
                        <?php if (empty($hide_links) || ($hide_links == FALSE)): ?>
                            <li class="nav-item<?php if ($active == "home") { echo " active"; }; ?>">
                                <a class="nav-link" href="<?php echo site_url('/home'); ?>">Home</a>
                            </li>
                            <li class="nav-item<?php if ($active == "expenses") { echo " active"; }; ?>">
                                <a class="nav-link" href="<?php echo site_url('/expenses/my'); ?>">My Expenses</a>
                            </li>
                            <?php if ((!empty($userAccount['is_treasurer']) && ($userAccount['is_treasurer'] == TRUE))
                                   || (!empty($userAccount['is_CostCentreManager']) && ($userAccount['is_CostCentreManager'] == TRUE))): ?>
                                <li class="nav-item<?php if ($active == "expenses_review") { echo " active"; }; ?>">
                                    <a class="nav-link" href="<?php echo site_url('/expenses/review'); ?>">Expenses Review</a>
                                </li>
                            <?php endif; ?>
                            <?php if ((!empty($userAccount['is_treasurer']) && ($userAccount['is_treasurer'] == TRUE))): ?>
                                <li class="nav-item<?php if ($active == "expenses_all") { echo " active"; }; ?>">
                                    <a class="nav-link" href="<?php echo site_url('/expenses/all'); ?>">All Expenses</a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo site_url('/expenses/claim/new'); ?>">New Claim</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="navbar-nav">
                        <?php if (!empty($userAccount['is_admin']) && ($userAccount['is_admin'] == TRUE)): ?>
                            <li class="nav-item<?php if ($active == "admin") { echo " active"; }; ?>">
                                <a class="nav-link" href="<?php echo site_url('/admin'); ?>">Admin</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item<?php if ($active == "settings") { echo " active"; }; ?>">
                            <a class="nav-link" href="<?php echo site_url('/settings'); ?>">Settings</a>
                        </li>
                    </ul>
                </div>
            </nav>
