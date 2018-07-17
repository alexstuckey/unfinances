<!-- Content Block (Contains Sidebars and central columns) -->
<main class="container-fluid" id="content">
    <div class="jumbotron">
        <h1>Welcome to UC Finances</h1>
        <p class="lead">
            Currently unfunctional.
        </p>
    </div>
</main>

<style type="text/css">
   .jumbotron {
        position: relative;
    }
    .jumbotron:after {
        content: "";
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        position: absolute;

        background-image: url('<?php echo base_url('/static/images/uc-crest.svg'); ?>');
        background-repeat: no-repeat;
        background-position: right 10px top 50%;
        background-size: auto 85%;
        opacity: 0.5;
    }
</style>