<main class="container claim-form pb-3 mb-5">
    <div class="py-5 col-lg-10 offset-xl-1">
        <h1 class="mb-4 text-center">JCR Expenses Claim</h1>
        <p class="lead text-muted">This form is intended for members of the JCR who require reimbursement for services and goods purchased for JCR purposes. This form must be co-signed by the relevant Cost Centre manager. If you are unsure who this person is, please contact the Treasurer. If you would like to be paid by bank transfer please fill in the relevant boxes.</p>
        <p class="lead text-muted">No reimbursement can be made without the correct receipts.</p>
    </div>

    <div class="row">
        <div class="col-lg-8 order-md-1">
            <h4 class="mb-3">Claim details</h4>
            <form>
                <div class="mb-3">
                    <label class="bold-label" for="input_id_claim">Claim №</label>
                    <input type="text" class="form-control" id="input_id_claim" value="" required="" style="background-color: transparent; border: 0; color: #212529;">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="bold-label" for="input_claimant_name">Claimant Name</label>
                        <input type="text" class="form-control" id="input_claimant_name" value="" required="" style="background-color: transparent; border: 0; color: #212529;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bold-label" for="input_claimant_id">CIS Username</label>
                        <input type="text" class="form-control" id="input_claimant_id" value="" required="" style="background-color: transparent; border: 0; color: #212529;">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="bold-label" for="input_date">Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="input_date" placeholder="Date" required="" value="<?php echo $claim['date'] ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="bold-label" for="input_of_id_cost_centre">Cost Centre</label>
                    <input type="text" class="form-control" id="input_of_id_cost_centre" placeholder="Cost Centre" value="">
                </div>

                <div class="mb-3">
                    <label class="bold-label" for="input_description">Description of expense</label>
                    <input type="text" class="form-control" id="input_description" placeholder="Description" value="">
                </div>


                <hr class="my-5">

                <h4 class="mb-3">Details of Expenditure</h4>

                <div id="jsGrid"></div>
                <table class="jsgrid-table"><tr class="jsgrid-row"><td style="width: 150px;"></td><td class="currency" id="currency-sum" style="width: 35px;">50.00</td><td style="width: 20px;"></td></tr></table>

            </form>
        </div>
        <div class="col-lg-4 order-md-2 mb-4">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Attachments</span>
            <!-- <span class="badge badge-secondary badge-pill">0</span> -->
          </h4>
          <ul class="list-group mb-3" id="attachments-list">

          </ul>

          <form class="card p-2">
            <div id="drag-drop-area"></div>
          </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 order-md-1">
            <hr class="mt-5">

            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="same-address">
                <label class="custom-control-label" for="same-address">Shipping address is the same as my billing address</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="save-info">
                <label class="custom-control-label" for="save-info">Save this information for next time</label>
            </div>

            <hr class="mb-5">

            <div class="row form-group">
                <div class="col-6">
                    <a href="#" class="btn btn-dark btn-lg btn-block" role="button">Save</a>
                </div>
                <div class="col-6">
                    <a href="#" class="btn btn-primary btn-lg btn-block" role="button">Claim this expense</a>
                </div>
            </div>
        </div>
    </div>

</main>

<style type="text/css">
    .currency {
        text-align: right;
        width: 100%;
        padding: 0.5em;
    }
    .currency:before {
        content: "£";
        float: left;
    }

    #currency-sum {
        border-top-style: solid;
        border-top-width: 1px;
        border-bottom-style: double;
    }
</style>

<script>
    var claimFromServerRender = jQuery.parseJSON(<?php echo json_encode($claimJSON); ?>)

    $(document).ready(function() {
        // Gets run once on page load (at bootom), then used to reload server data
        function loadClaim(claim) {
            claim.expenditure_items = jQuery.parseJSON(claim.expenditure_items)
            $("#jsGrid").jsGrid('option', 'data', claim.expenditure_items)

        // Attachments
            // Clear existing
            $("#attachments-list").empty()
            
            if (claim.attachments.length === 0) {
                $("<li>")
                .addClass("list-group-item d-flex justify-content-between bg-light")
                .append(
                    $("<h6>")
                    .addClass("my-0 text-muted")
                    .text("No attachments added")
                    )
                .appendTo("#attachments-list")
            }

            claim.attachments.forEach((attachment) => {
                $("<li>")
                .addClass("list-group-item d-flex justify-content-between lh-condensed")
                .append(
                    $("<div>")
                    .append(
                        $("<a>")
                        .attr("href", "../../../uploads/"+attachment.id_filename)
                        .append(
                            $("<h6>")
                            .addClass("my-0")
                            .text(attachment.client_name)
                            )
                        )
                    .append(
                        $("<small>")
                        .addClass("text-muted")
                        .text(attachment.filesize_human)
                        )
                    )
                // COMMENTED UNTIL DELETE FEATURE ADDED
                // .append(
                //     $("<span>")
                //     .addClass("text-muted")
                //     .text("X")
                //     )
                .appendTo("#attachments-list")
            })

        // Fields
        $("#input_id_claim").val(claim.id_claim)
        $("#input_claimant_id").val(claim.claimant_id)
        $("#input_claimant_name").val(claim.claimant_name)
        $("#input_date").val(claim.date)
            $("#input_of_id_cost_centre").val(claim.of_id_cost_centre)
            $("#input_description").val(claim.description)

        }


        // jsGrid config
        function insert_on_enter(field) {
          field.on("keydown", function(e) {
            if(e.keyCode === 13) {
              $("#jsGrid").jsGrid("insertItem")
              $("#jsGrid").jsGrid("clearInsert")
              return false
            }
          })
        }

        function update_on_enter(field) {
          field.on("keydown", function(e) {
            if(e.keyCode === 13) {
              $("#jsGrid").jsGrid("updateItem")
              return false
            }
          })
        }

        function calculate_sum(args) {
            $('#currency-sum').text(args.grid.data.reduce((accumulator, row) => accumulator + Number(row.Price), 0).toFixed(2))
        }
     
        $("#jsGrid").jsGrid({
            width: "100%",
     
            inserting: true,
            editing: true,
            sorting: false,
            paging: false,
     
            data: [],
     
            fields: [
                {
                    name: "Description",
                    type: "text",
                    width: 145,
                    validate: "required",
                    insertTemplate: function(value, item) {
                        this.insertControl = $('<input type="text">').val(value)
                        insert_on_enter(this.insertControl)
                        return this.insertControl
                    },
                    editTemplate: function(value, item) {
                        this.editControl = $('<input type="text">').val(value)
                        update_on_enter(this.editControl)
                        return this.editControl
                    },
                },
                {
                    name: "Price",
                    type: "number",
                    width: 35,
                    validate: "required",
                    insertTemplate: function(value, item) {
                        this.insertControl = $('<input type="number">').attr('min', 0).attr('step', 0.01).val(value)
                        insert_on_enter(this.insertControl)
                        return this.insertControl
                    },
                    editTemplate: function(value, item) {
                        this.editControl = $('<input type="number">').attr('min', 0).attr('step', 0.01).val(value)
                        update_on_enter(this.editControl)
                        return this.editControl
                    },
                    cellRenderer: function(value, item) {
                        return $('<td>').addClass('currency').text(value)
                    },
                    insertValue: function() {
                        return Number(this.insertControl.val())
                    },
                    editValue: function() {
                        return Number(this.editControl.val())
                    },
                },
                {
                    type: "control",
                    width: 20
                }
            ],
            onInit: calculate_sum,
            onItemDeleted: calculate_sum,
            onItemInserted: calculate_sum,
            onItemUpdated: calculate_sum,
            onOptionChanged: calculate_sum,
        })

        $('body').click(function(e) {
            if ($(e.target).closest('#jsGrid').length === 0) {
                $('#jsGrid').jsGrid('cancelEdit')
            }
        })


        var uppy = Uppy.Core({
            autoProceed: true,
            debug: true,
            restrictions: {
                allowedFileTypes: ['image/gif', 'image/png', 'image/jpeg', 'image/jpg', 'application/pdf']
            },

        })
        uppy.on('file-added', (file) => {
            uppy.setFileMeta(file.id, {
                invoice_id: <?php echo $claim['id_claim'] ?>
            })
        })
        uppy.use(Uppy.DragDrop, { target: '#drag-drop-area' })
        uppy.use(Uppy.XHRUpload, {
            endpoint: '<?php echo site_url('/file/upload'); ?>',
            fieldName: 'userfile',
            getResponseError(responseText, xhr) {
                alert('File failed to upload')
                return new Error(JSON.parse(responseText).message)
            },
        })

        loadClaim(claimFromServerRender)
    })

</script>
