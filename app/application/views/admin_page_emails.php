<div data-spy="scroll" data-target=".navbar" class="col-sm-8 text-left" id="centre">

    <!-- Emails Div -->
    <div id="email-templates">
        <h1>Email Templates</h1>

        <?php if (isset($message)) {

            echo '<p class="alert alert-info">'.$message.'</p>';
        }?>

        <?php echo validation_errors(); ?>

        <div id="accordion" role="tablist" aria-multiselectable="true">

            <?php foreach ($email_templates as $template): ?>
                <div class="card">
                    <div class="card-header" role="tab" id="EmailListHeading-<?php echo $template['email_name'] ?>">
                        <p class="mb-0 alignleft">
                            <a data-toggle="collapse" data-parent="#accordion" href="#EmailListCollapse-<?php echo $template['email_name'] ?>" aria-expanded="true" aria-controls="EmailListCollapse-<?php echo $template['email_name'] ?>">
                                <?php echo $template['email_name'] ?>
                            </a>
                        </p>
                    </div>
                    <div id="EmailListCollapse-<?php echo $template['email_name'] ?>" class="collapse" role="tabpanel" aria-labelledby="EmailListHeading-<?php echo $template['email_name'] ?>">
                        <div class="card-block">
                            <form action="<?php echo site_url('/admin/emails/edit'); ?>" method="post">
                                <input type="hidden" class="form-control" name="email_name" value="<?php echo $template['email_name'] ?>">

                                <div class="form-group">
                                    <label for="email_description"><h5>Description</h5></label>
                                    <p><?php echo $template['email_description'] ?></p>
                                </div>
                                <br>

                                <div class="form-group">
                                    <label for="email_subject"><h5>Subject</h5></label>
                                    <input type="text" class="form-control" name="email_subject" id="email_subject" value="<?php echo $template['email_subject'] ?>">
                                </div>
                                <br>

                                <div class="form-group">
                                    <label for="email_body"><h5>Email body</h5></label>
                                    <textarea class="form-control" id="email_body" rows="15" name="email_body"><?php echo $template['email_body'] ?></textarea>
                                </div>
                                <button class="btn btn-primary" type="submit">Edit</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- End of Emails Div -->
</div>
