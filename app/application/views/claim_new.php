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
                    <label class="bold-label" for="username">Claim №</label>
                    <input type="text" class="form-control" id="firstName" placeholder="" value="17" required="" style="background-color: transparent; border: 0; color: #212529;">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="bold-label" for="firstName">Claimant Name</label>
                        <input type="text" class="form-control" id="firstName" placeholder="" value="" required="" style="background-color: transparent; border: 0; color: #212529;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="bold-label" for="lastName">CIS Username</label>
                        <input type="text" class="form-control" id="lastName" placeholder="" value="" required="" style="background-color: transparent; border: 0; color: #212529;">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="bold-label" for="username">Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="username" placeholder="Username" required="">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="bold-label" for="email">Cost Centre</label>
                    <input type="email" class="form-control" id="email" placeholder="you@example.com">
                </div>

                <div class="mb-3">
                    <label class="bold-label" for="username">Description of expense</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="username" placeholder="Username" required="">
                    </div>
                </div>


                <hr class="my-5">

                <h4 class="mb-3">Details of Expenditure</h4>

                <div id="jsGrid"></div>
                <table class="jsgrid-table"><tr class="jsgrid-row"><td style="width: 150px;"></td><td class="currency" id="currency-sum" style="width: 30px; border-top-style: solid; border-top-width: 1px; border-bottom-style: double;">50.00</td><td style="width: 20px;"></td></tr></table>

            </form>
        </div>
        <div class="col-lg-4 order-md-2 mb-4">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Attachments</span>
            <!-- <span class="badge badge-secondary badge-pill">0</span> -->
          </h4>
          <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Product name</h6>
                <small class="text-muted">Brief description</small>
              </div>
              <span class="text-muted">$12</span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Second product</h6>
                <small class="text-muted">Brief description</small>
              </div>
              <span class="text-muted">$8</span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Third item</h6>
                <small class="text-muted">Brief description</small>
              </div>
              <span class="text-muted">$5</span>
            </li>
          </ul>

          <form class="card p-2">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Promo code">
              <div class="input-group-append">
                <button type="submit" class="btn btn-secondary">Redeem</button>
              </div>
            </div>
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
</style>

<script>
    var data = [
        { "Description": "Coca-Cola", "Price": 1.39},
        { "Description": "Pringles", "Price": 4.73},
        { "Description": "Meal Deal", "Price": 3.00},
        { "Description": "Bananas", "Price": 2.03},
        { "Description": "Batteries", "Price": 17.03},
    ]

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
 
        data: data,
 
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
    })

    $('body').click(function(e) {
        if ($(e.target).closest('#jsGrid').length === 0) {
            $('#jsGrid').jsGrid('cancelEdit')
        }
    })

</script>
