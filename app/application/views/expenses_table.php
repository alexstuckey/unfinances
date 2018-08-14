<!-- Content Block (Contains Sidebars and central columns) -->
<main class="container-fluid" id="content">
    <div class="jumbotron">
        <h1><?php echo $subtitle; ?></h1>
        <p class="lead">
            <?php echo $page_lead_text; ?>
        </p>
    </div>

    <?php
        function generateStatusCell($statusInt)
        {
            $cellInfo = null;
            switch($statusInt)
            {
                case 0:
                    $cellInfo = array( 'text' => "Draft", 'backgroundColour' => "#6c757d", 'textColour' => "#fff" );
                    break;
                case 1:
                    $cellInfo = array( 'text' => "Cost Centre Review", 'backgroundColour' => "#007bff", 'textColour' => "#fff" );
                    break;
                case 2:
                    $cellInfo = array( 'text' => "Bounced", 'backgroundColour' => "#ffc107", 'textColour' => "#212529" );
                    break;
                case 3:
                    $cellInfo = array( 'text' => "Treasurer Review", 'backgroundColour' => "#17a2b8", 'textColour' => "#fff" );
                    break;
                case 4:
                    $cellInfo = array( 'text' => "Rejected", 'backgroundColour' => "#dc3545", 'textColour' => "#fff" );
                    break;
                case 5:
                    $cellInfo = array( 'text' => "Approved", 'backgroundColour' => "#28a745", 'textColour' => "#fff" );
                    break;
                case 6:
                    $cellInfo = array( 'text' => "Paid", 'backgroundColour' => "#28a745", 'textColour' => "#fff" );
                    break;
                case 7:
                    $cellInfo = array( 'text' => "Deleted", 'backgroundColour' => "#dc3545", 'textColour' => "#fff" );
                    break;
                default:
                    $cellInfo = array();
                    break;
            }
            return $cellInfo;
        }
    ?>

    <style type="text/css">
        .my-badge-status {
            margin-top: 3px;
            font-size: 1.0rem;
        }
        .cell_description {
            width: 40%;
        }
        .no-content-row td {
          text-align: center;
          font-style: italic;
        }
        .link-row:hover {
          cursor: pointer;
        }
        .fa-icon-float-right {
          float: right;
          line-height: inherit;
          color: grey;
          visibility: hidden;
        }
        .link-row:hover .fa-icon-float-right {
          visibility: visible;
        }
    </style>

    <table class="table table-striped table-bordered table-hover">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Status</th>
          <?php if ($page_show_claimant_column): ?>
          <th scope="col">By Claimant</th>
          <?php endif; ?>
          <th scope="col">Date</th>
          <th scope="col">Cost Centre</th>
          <th scope="col">Description</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($claims as $claim): ?>
        <tr class="link-row" data-href="<?php echo site_url('/expenses/claim/' . $claim['id_claim']); ?>">
          <th scope="row"><?php echo $claim['id_claim']; ?></th>
          <?php $claim['statusCellInfo'] = generateStatusCell($claim['status']); ?>
          <td>
            <span class="badge my-badge-status" id="input_status" style="background-color: <?php echo $claim['statusCellInfo']['backgroundColour']; ?>; color: <?php echo $claim['statusCellInfo']['textColour']; ?>;"><?php echo $claim['statusCellInfo']['text']; ?></span>
            <?php if ($claim['statusCellInfo']['text'] == 'Draft'): ?>
            <i class="fas fa-trash fa-icon-float-right row-button-delete"></i>
            <?php endif; ?>
          </td>
          <?php if ($page_show_claimant_column): ?>
          <td><?php echo $claim['claimant_name']; ?></td>
          <?php endif; ?>
          <td><?php echo $claim['date']; ?></td>
          <td><?php echo $claim['cost_centre']; ?></td>
          <td class="cell_description"><?php echo $claim['description']; ?></td>
        </tr>
        <?php endforeach;
              if (count($claims) == 0):
        ?>
        <tr class="table-secondary no-content-row">
          <td colspan="<?php if ($page_show_claimant_column) { echo '6'; } else { echo '5'; } ?>">There are no claims to show.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
</main>

<script type="text/javascript">
  $(document).ready(function() {
    $('.link-row').on('click', function() {
        var href = $(this).attr("data-href");
        if(href) {
            window.location = href;
        }
    })

    $(".row-button-delete").on('click', function(e) {
      e.stopPropagation()
      row_claim_id = $(this).parent().parent().children().first().text()
      jQuery.ajax({
          url: `../api/expenses/deleteClaim/${row_claim_id}`,
          type: "POST",
          data: {}
      }).done((data) => {
          console.log('claim deleted')
          location.reload()
      }).fail((jqXHR, textStatus, errorThrown) => {
          if (jqXHR.status == 400) {
              console.error('Request failed: ' + textStatus)
              alert('You do not have permission to delete this claim.')
          } else if (jqXHR.status == 403) {
              console.error('Request failed: ' + textStatus)
              alert('You do not have permission to delete this claim.')
          } else if (jqXHR.status == 404) {
              console.error('Request failed: ' + textStatus)
              alert('Delete failed, this claim does not exist.')
          } else {
              console.error(errorThrown)
              alert('Delete failed, please check your connection and try again.')
          }
          
      })
    })
  })
</script>
