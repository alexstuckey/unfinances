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
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="bold-label" for="input_id_claim">Claim №</label>
                    <input type="text" class="form-control" id="input_id_claim" value="" required="" style="background-color: transparent; border: 0; color: #212529;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bold-label" for="field_status">Status</label>
                        <div><span class="badge my-badge-status" id="input_status">DRAFT</span></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="bold-label" for="input_claimant_name">Claimant Name</label>
                        <input disabled type="text" class="form-control" id="input_claimant_name" value="" required="" style="color: #212529; -webkit-text-fill-color: #212529;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bold-label" for="input_claimant_id">CIS Username</label>
                        <input disabled type="text" class="form-control" id="input_claimant_id" value="" required="" style="color: #212529; -webkit-text-fill-color: #212529;">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="bold-label" for="input_date">Date</label>
                        <input disabled type="text" class="form-control" id="input_date" placeholder="Date" value="<?php echo $claim['date'] ?>" required="" style="color: #212529; -webkit-text-fill-color: #212529;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bold-label" for="input_cost_centre">Cost Centre</label>
                        <select id="input_cost_centre" class="custom-select detectStateInput">
                            <?php foreach ($cost_centres as $cost_centre): ?>
                            <option value="<?php echo $cost_centre['cost_centre']; ?>"><?php echo $cost_centre['cost_centre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="bold-label" for="input_description">Description of expense</label>
                    <input type="text" class="form-control detectStateInput" id="input_description" placeholder="Description" value="">
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
            <div id="drag-drop-area" class="progress-bar-fix-height"></div>
            <div id="drag-drop-progress"></div>
          </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 order-md-1">
            <hr class="mt-5">

            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="declaration-checkbox">
                <label class="custom-control-label" for="declaration-checkbox" id="declaration-label"></label>
            </div>

            <hr class="mb-4">

            <div class="row" id="action_button_row_1">
                <div class="col-6">
                </div>
                <div class="col-6">
                </div>
            </div>
            <div class="row" id="action_button_row_2">
                <div class="col-12">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mt-4">
            <ol class="list-group" id="activity-feed">
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="form-row mx-3 my-2">
                <div class="col-9">
                    <input class="form-control" type="text" placeholder="Your comments" id="comment_field">
                </div>
                <div class="col-3">
                    <button class="btn btn-default btn-block" id="comment_button">Comment</button>
                </div>
            </div>
        </div>
    </div>

</main>

<style type="text/css">
    #activity-feed .avatar {
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 50%;
        margin-right: 0.75rem;
    }

    #activity-feed i {
        font-size: 2rem;
        margin-right: 0.75rem;
        width: 1.25em; /* Set a fixed-width, without centre aligning */
    }

    #activity-feed span.activity_user_name {
        margin: 0;
        font-size: 1rem;
        font-weight: 500;
    }

    #activity-feed .time-text {
        font-size: .875rem;
        line-height: 1.3125rem;
    }


    .my-badge-status {
        margin-top: 3px;
        font-size: 1.25rem;
    }

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

    #drag-drop-area.progress-bar-fix-height {
        margin-bottom: -3px;
    }

    #attachments-list {
        overflow-wrap: break-word;
    }
</style>

<script>
    const statusesLookup = {
        0: { text: "Draft", backgroundColour: "#6c757d", textColour: "#fff" },
        1: { text: "Cost Centre Review", backgroundColour: "#007bff", textColour: "#fff" },
        2: { text: "Bounced", backgroundColour: "#ffc107", textColour: "#212529" },
        3: { text: "Treasurer Review", backgroundColour: "#17a2b8", textColour: "#fff" },
        4: { text: "Rejected", backgroundColour: "#dc3545", textColour: "#fff" },
        5: { text: "Approved", backgroundColour: "#28a745", textColour: "#fff" },
        6: { text: "Paid", backgroundColour: "#28a745", textColour: "#fff" },
    }

    userAccount = jQuery.parseJSON(<?php echo "\"" . str_replace("\"", "\\\"", json_encode($userAccount)) . "\""; ?>)
    window.claimState = jQuery.parseJSON(<?php echo json_encode($claimJSON); ?>)
    window.stateChanged = false

    // Gets run once on page load (at bootom), then used to reload server data
    window.loadClaim = function(claim) {

    // Attachments
        // Clear existing
        $("#attachments-list").empty()
        
        // No attachments message
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

        // Sort
        window.claimState.attachments.sort((a, b) => {
            return new Date(a.uploaded_datetime) - new Date(b.uploaded_datetime);
        })

        // Render list
        claim.attachments.forEach((attachment) => {
            $("<li>")
            .addClass("list-group-item")
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

    // Activity feed
        // Clear existing
        $("#activity-feed").empty()

        // Render list
        claim.activities.forEach((activity) => {
            let icon, activityInsert

            switch (activity.activity_type) {
                case 'create':
                    icon = 'fas fa-plus-square'
                    activityInsert = $("<span>")
                                     .text(" created this claim.")
                    break;
                case 'comment':
                    icon = 'fas fa-comment-alt'
                    activityInsert = $("<span>")
                                     .text(" commented.")
                                     .add(
                                        $("<span>")
                                        .addClass("d-block text-muted")
                                        .text(activity.activity_value)
                                     )
                    break;
                case 'change_status':
                    icon = 'fas fa-edit'
                    activityInsert = $("<span>")
                                     .text(" changed status from ")
                                     .add(
                                        $("<span>")
                                        .addClass("badge")
                                        .css('background-color', statusesLookup[activity.activity_value_before].backgroundColour)
                                        .css('color', statusesLookup[activity.activity_value_before].textColour)
                                        .text(statusesLookup[activity.activity_value_before].text)
                                     )
                                     .add(
                                        $("<span>")
                                        .text(" to ")
                                     )
                                     .add(
                                        $("<span>")
                                        .addClass("badge")
                                        .css('background-color', statusesLookup[activity.activity_value].backgroundColour)
                                        .css('color', statusesLookup[activity.activity_value].textColour)
                                        .text(statusesLookup[activity.activity_value].text)
                                     )
                    break;
                default:
                    icon = 'fas fa-question-circle'
                    break;
            }

            $("<li>")
            .addClass("list-group-item")
            .append(
                $("<div>")
                .addClass("media align-items-center")
                .append(
                    $("<i>")
                    .addClass(icon)
                )
                .append(
                    $("<div>")
                    .addClass("media-body")
                    .append(
                        $("<div>")
                        .append(
                            $("<span>")
                            .addClass("activity_user_name")
                            .text(activity.by_id_cis_user.fullname)
                        )
                        .append(activityInsert) // from the above switch block
                        )
                    .append(
                        $("<span>")
                        .addClass("time-text")
                        .text(new Date(activity.activity_datetime).toLocaleString('en-GB'))
                        )

                    )
                )
            .appendTo("#activity-feed")
        })

    // Fields
        $("#input_id_claim").val(claim.id_claim)
        $("#input_claimant_id").val(claim.claimant_id)
        $("#input_claimant_name").val(claim.claimant_name)
        $("#input_date").val(() => {
            const [year, month, day] = claim.date.split("-")
            return `${day}/${month}/${year}`
        })
        $("#input_cost_centre").val(claim.cost_centre)
        $("#input_description").val(claim.description)

        if (claim.isEditable) {
            $("#input_cost_centre").prop('disabled', false)
            $("#input_description").prop('disabled', false)
        } else {    
            $("#input_cost_centre").prop('disabled', true)
            $("#input_description").prop('disabled', true)
        }

    // Statuses
        $("#input_status").text(statusesLookup[claim.status].text)
        $("#input_status").css('background-color', statusesLookup[claim.status].backgroundColour)
        $("#input_status").css('color', statusesLookup[claim.status].textColour)

    // Action buttons
        $("#action_button_row_1").children().empty()
        $("#action_button_row_2").children().empty()
        if (claim.isEditable
         && claim.claimant_id === userAccount.username) {
            $("<button>")
            .appendTo($("#action_button_row_1 div:first"))
            .addClass("btn btn-dark btn-lg btn-block")
            .attr("id", "button_save")
            .text("Save as Draft")
            .on("click", window.saveStateToServer)

            $("<button>")
            .appendTo($("#action_button_row_1 div:last"))
            .addClass("btn btn-primary btn-lg btn-block")
            .attr("id", "button_claim")
            .text("Claim this expense")
            .on("click", window.submitClaimToServer)

            $("#declaration-label")
            .text("By checking this box, I confirm that the contents of this form is correct and that I am asking to be reinbursed for the amount displayed above. The expenditure listed above was bought for the purposes of the JCR. I am also aware that the bank details listed in my user profile will be used to reinburse this claim.")
            .parent().show()
            .after().show()
        } else if (statusesLookup[claim.status].text == "Cost Centre Review"
                && userAccount.managerOfCostCentres.reduce((accumulator, row) => accumulator || (row.cost_centre == claim.cost_centre), false)) {
            $("<button>")
            .appendTo($("#action_button_row_1 div:first"))
            .addClass("btn btn-warning btn-lg btn-block")
            .attr("id", "button_cost_centre_bounce")
            .text("Bounce")
            .on("click", window.reviewClaimToServer)

            $("<button>")
            .appendTo($("#action_button_row_1 div:last"))
            .addClass("btn btn-success btn-lg btn-block")
            .attr("id", "button_cost_centre_approve")
            .text("Approve to Treasurer")
            .on("click", window.reviewClaimToServer)

            $("#declaration-label")
            .text("By checking this box, I am confirming that I have read this claim and, as the manager of its cost centre, have decided to either approve or bounce it.")
            .parent().show()
            .next().show()
        } else if (statusesLookup[claim.status].text == "Treasurer Review" && userAccount.is_treasurer) {
            $("<button>")
            .appendTo($("#action_button_row_1 div:first"))
            .addClass("btn btn-warning btn-lg btn-block")
            .attr("id", "button_treasurer_bounce")
            .text("Bounce")
            .on("click", window.reviewClaimToServer)

            $("<button>")
            .appendTo($("#action_button_row_1 div:last"))
            .addClass("btn btn-danger btn-lg btn-block")
            .attr("id", "button_treasurer_reject")
            .text("Reject")
            .on("click", window.reviewClaimToServer)

            $("<button>")
            .appendTo($("#action_button_row_2 div:first"))
            .addClass("btn btn-success btn-lg btn-block mt-3")
            .attr("id", "button_treasurer_approve")
            .text("Approve")
            .on("click", window.reviewClaimToServer)

            $("#declaration-label")
            .text("By checking this box, I am confirming that I have read this claim and, as the treasurer, have decided to either approve, bounce, or reject it.")
            .parent().show()
            .next().show()
        } else if (statusesLookup[claim.status].text == "Approved" && userAccount.is_treasurer) {
            $("<button>")
            .appendTo($("#action_button_row_1 div:last"))
            .addClass("btn btn-success btn-lg btn-block")
            .attr("id", "button_treasurer_paid")
            .text("Paid")
            .on("click", window.reviewClaimToServer)

            $("#declaration-label")
            .text("By checking this box, I am confirming that I have paid this claim as the treasurer.")
            .parent().show()
            .next().show()
        } else {
            $("#declaration-label")
            .parent().hide()
            .next().hide()
        }

    // The grid
        $("#jsGrid").jsGrid('option', 'data', jQuery.parseJSON(claim.expenditure_items))
        $("#jsGrid").jsGrid('option', 'editing', claim.isEditable)
    }

    window.refreshClaimStateFromServer = function() {
        jQuery.ajax({
            url: "../../api/expenses/claim/"+window.claimState.id_claim,
        }).done((data) => {
            window.claimState = data
            loadClaim(window.claimState)
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.error(errorThrown)
        })
    }

    window.checkStateChange = function() {
        if (
            $("#input_description").val() === window.claimState.description &&
            $("#input_cost_centre").val() === window.claimState.cost_centre &&
            JSON.stringify($("#jsGrid").jsGrid('option', 'data')) == window.claimState.expenditure_items
            ) {
            window.stateChanged = false
        } else {
            window.stateChanged = true
            if (!window.claimState.isEditable) {
                alert('Error: you cannot edit this claim')
            }
        }
        $("#button_save").prop('disabled', !window.stateChanged)
        $("#button_claim").prop('disabled', window.stateChanged)
        console.log("Has local state been changed: ", window.stateChanged)
    }

    // to send any changes to be committed to the server
    window.saveStateToServer = function() {
        // Prevent saving when nothing has changed
        if (window.stateChanged) {
            jQuery.ajax({
                url: "../../api/expenses/saveClaim/"+window.claimState.id_claim,
                type: "POST",
                data: {
                    description: $("#input_description").val(),
                    cost_centre: $("#input_cost_centre").val(),
                    expenditure_items: JSON.stringify($("#jsGrid").jsGrid('option', 'data')),
                }
            }).done((data) => {
                console.log('saved state to server', data)
                refreshClaimStateFromServer()
            }).fail((jqXHR, textStatus, errorThrown) => {
                if (jqXHR.status == 403) {
                    console.error('Attempted to save a claim not owned by user.')
                    alert('You do not own this claim, and so cannot modify it.')
                } else if (jqXHR.status == 404) {
                    console.error('This claim does not exist')
                    alert('Save failed, this claim does not exist.')
                } else {
                    console.error(errorThrown)
                    alert('Save failed, please check your connection and try again.')
                }
                
            })
        }
    }

    // to progress the claim, by submitting it to Treasurer
    window.submitClaimToServer = function() {
        // Prevent claiming when something has changed
        if (window.stateChanged) {
            alert('You have changed the claim form without saving. Please save or reload the page.')
        } else {
            if (!$('#declaration-checkbox').is(':checked')) {
                alert('You must confirm the declaration before claiming an expense.')
            } else {

                // Check that the form has been sufficiently filled out
                if (
                    window.claimState.description.length == 0
                 || JSON.parse(window.claimState.expenditure_items).length == 0
                 || window.claimState.attachments.length == 0
                    ) {
                    alert('Please complete the form by including a description, items of expenditure and at least one attachment.')
                } else {
                    jQuery.ajax({
                        url: "../../api/expenses/submitClaim/"+window.claimState.id_claim,
                        type: "POST",
                        data: {}
                    }).done((data) => {
                        console.log('Successfully claimed.', data)
                        $("#save_claim_button_row").remove()
                        refreshClaimStateFromServer()
                    }).fail((jqXHR, textStatus, errorThrown) => {
                        if (jqXHR.status == 403) {
                            console.error('Claim not editable, or you are not the owner.')
                            alert('Claim not editable, or you are not the owner.')
                        } else if (jqXHR.status == 404) {
                            console.error('This claim does not exist')
                            alert('Claim failed, this claim does not exist.')
                        } else {
                            console.error(errorThrown)
                            alert('Save failed, please check your connection and try again.')
                        }
                        
                    })
                }

            }
        }
    }

    window.submitCommentToServer = function() {
        // check for value in field
        if ($("#comment_field").val() === '') {
            console.log('empty comment')
        } else {
            jQuery.ajax({
                url: "../../api/expenses/commentClaim/"+window.claimState.id_claim,
                type: "POST",
                data: {
                    comment_field: $("#comment_field").val(),
                }
            }).done((data) => {
                console.log('submitted comment to server', data)
                $("#comment_field").val("")
                refreshClaimStateFromServer()
            }).fail((jqXHR, textStatus, errorThrown) => {
                if (jqXHR.status == 403) {
                    console.error('Attempted to comment on a claim not owned by user.')
                    alert('You do not own this claim or have permission to write to it, and so cannot modify it.')
                } else if (jqXHR.status == 404) {
                    console.error('This claim does not exist')
                    alert('Save failed, this claim does not exist.')
                } else {
                    console.error(errorThrown)
                    alert('Save failed, please check your connection and try again.')
                }
                
            })
        }
    }

    window.reviewClaimToServer = function() {
        // check for value in field
        if (!$('#declaration-checkbox').is(':checked')) {
            alert('You must confirm the declaration before approving an expense.')
        } else {
            let reviewType = 'none'
            let reviewDecision = 'none'
            switch (this.id) {
                case 'button_cost_centre_bounce':
                reviewType = 'cost_centre'
                reviewDecision = 'bounce'
                break;

                case 'button_cost_centre_approve':
                reviewType = 'cost_centre'
                reviewDecision = 'approve'
                break;

                case 'button_treasurer_bounce':
                reviewType = 'treasurer'
                reviewDecision = 'bounce'
                break;

                case 'button_treasurer_reject':
                reviewType = 'treasurer'
                reviewDecision = 'reject'
                break;

                case 'button_treasurer_approve':
                reviewType = 'treasurer'
                reviewDecision = 'approve'
                break;

                case 'button_treasurer_paid':
                reviewType = 'treasurer'
                reviewDecision = 'pay'
                break;
            }
            jQuery.ajax({
                url: `../../api/expenses/reviewClaim/${reviewType}/${reviewDecision}/${window.claimState.id_claim}`,
                type: "POST",
                data: {}
            }).done((data) => {
                console.log('submitted comment to server', data)
                refreshClaimStateFromServer()
            }).fail((jqXHR, textStatus, errorThrown) => {
                if (jqXHR.status == 400) {
                    console.error('Request failed: ' + textStatus)
                    alert('You do not have permission to review this claim.')
                } else if (jqXHR.status == 403) {
                    console.error('Request failed: ' + textStatus)
                    alert('You do not have permission to review this claim.')
                } else if (jqXHR.status == 404) {
                    console.error('Request failed: ' + textStatus)
                    alert('Review failed, this claim does not exist.')
                } else {
                    console.error(errorThrown)
                    alert('Review failed, please check your connection and try again.')
                }
                
            })
        }
    }

    // Detect when fields are altered
    $(".detectStateInput").change(window.checkStateChange)
    // Comment button / enter
    $("#comment_button").on("click", window.submitCommentToServer)
    $("#comment_field").keypress(function(e) {
        if (e.which === 13) {
            e.preventDefault()
            window.submitCommentToServer()
        }
    })

    $(document).ready(function() {
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
            // then re-check state
            window.checkStateChange()
        }
     
        $("#jsGrid").jsGrid({
            width: "100%",
     
            inserting: true,
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
            // onInit: calculate_sum,
            onItemDeleted: calculate_sum,
            onItemInserted: calculate_sum,
            onItemUpdated: calculate_sum,
            onOptionChanged: calculate_sum,
        })

        $('body').click(function(e) {
            if ($(e.target).closest('#jsGrid').length === 0) {
                $('#jsGrid').jsGrid('cancelEdit')
                if ($('.jsgrid').jsGrid('option', 'inserting') === true) {
                    $(".jsgrid-insert-mode-button").click()
                }
            }
        })


        var uppy = Uppy.Core({
            autoProceed: true,
            debug: true,
            restrictions: {
                allowedFileTypes: ['image/gif', 'image/png', 'image/jpeg', 'image/jpg', 'application/pdf']
            },
            onBeforeFileAdded: (currentFile, files) => {
                if (
                    window.claimState.isEditable
                 || userAccount.is_admin
                 || userAccount.is_treasurer
                 || userAccount.managerOfCostCentres.reduce((accumulator, row) => accumulator || (row.cost_centre == claim.cost_centre), false)
                   ) {
                    return true
                } else {
                  // log to console
                  uppy.log(`Skipping file because you are not permitted to upload to this claim.`)
                  // show error message to the user
                  uppy.info(`Skipping file because you are not permitted to upload to this claim.`, 'error', 500)
                  return false
                }
            }

        })
        uppy
        .on('file-added', (file) => {
            uppy.setFileMeta(file.id, {
                of_id_claim: <?php echo $claim['id_claim'] ?>
            })
            // Remove the negative margin on the drop-zone to make room for the progressbar
            $("#drag-drop-area").removeClass('progress-bar-fix-height')
            window.saveStateToServer()
        })
        .on('upload-success', (file, resp, uploadURL) => {
            window.refreshClaimStateFromServer()
        })
        .use(Uppy.DragDrop, { target: '#drag-drop-area' })
        .use(Uppy.ProgressBar, {
            target: '#drag-drop-progress',
            hideAfterFinish: true,
        })
        .use(Uppy.XHRUpload, {
            endpoint: '<?php echo site_url('/file/upload'); ?>',
            fieldName: 'userfile',
            getResponseError(responseText, xhr) {
                erorr_text = JSON.parse(responseText).error
                alert(erorr_text)
                return new Error(erorr_text)
            },
        })

        loadClaim(window.claimState)

    })

</script>
