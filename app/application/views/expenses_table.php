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
    </style>

    <table class="table table-striped table-bordered table-hover">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Status</th>
          <th scope="col">Date</th>
          <th scope="col">Cost Centre</th>
          <th scope="col">Description</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($claims as $claim): ?>
        <tr>
          <th scope="row"><a href="<?php echo site_url('/expenses/claim/' . $claim['id_claim']); ?>"><?php echo $claim['id_claim']; ?></a></th>
          <?php $claim['statusCellInfo'] = generateStatusCell($claim['status']); ?>
          <td>
            <span class="badge my-badge-status" id="input_status" style="background-color: <?php echo $claim['statusCellInfo']['backgroundColour']; ?>; color: <?php echo $claim['statusCellInfo']['textColour']; ?>;"><?php echo $claim['statusCellInfo']['text']; ?></span>
          </td>
          <td><?php echo $claim['date']; ?></td>
          <td><?php echo $claim['cost_centre']; ?></td>
          <td class="cell_description"><?php echo $claim['description']; ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
</main>
