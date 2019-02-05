<?php
$this->load->model('Claim_model');
?>

<html>
<head>
<style>
body {
	font-family: Garamond;
	font-size: 10pt;
}
p {	margin: 0pt; }
table.items {
	border: 0.1mm solid #000000;
}
td {
	font-family: Garamond;
	vertical-align: top;
}
.items td {
	border-left: 0.1mm solid #000000;
	border-right: 0.1mm solid #000000;
	border-bottom: 0.1mm solid #000000;
}
.info-table td {
	border: 0.1mm solid #000000;
}
table thead td {
	background-color: #EEEEEE;
	text-align: center;
	border: 0.1mm solid #000000;
	font-variant: small-caps;
}
.items td.blanktotal {
	background-color: #EEEEEE;
	border: 0.1mm solid #000000;
	background-color: #FFFFFF;
	border: 0mm none #000000;
	border-top: 0.1mm solid #000000;
	border-right: 0.1mm solid #000000;
}
.items td.totals {
	text-align: right;
	border: 0.1mm solid #000000;
}
.items td.cost {
	text-align: "." center;
}
</style>
</head>
<body>



<table width="100%"><tr>
<td width="50%">
	<img src="static/images/uc-master.png" height="65px" style="padding-bottom: 10px;" />
	<div style="margin-top: 100pt;">
		<span style="font-weight: normal; font-size: 14pt;">Claim No.</span>
		<br />
		<span style="font-weight: bold; font-size: 12pt;"><?php echo $claim['id_claim'] ?></span>
	</div>
</td>
<td width="50%" style="text-align: right; padding-top: 10px;">
	<?php
	// Find the treasurer that approved it.
	$approvingTreasurer = null;
	foreach ($claim['activities'] as $activity) {
		if ($activity['activity_type'] == 'change_status'
		 && $activity['activity_value_before'] == '3'
		 && ($activity['activity_value'] == '5' || $activity['activity_value'] == '8')) {
		 	// This is the approving treasurer
		 	$approvingTreasurer = $activity['by_id_cis_user'];
		 }
	}
	?>
	<p><strong><span style="font-size: 12.0pt; color:#98002e;"><?php echo $approvingTreasurer['fullname']; ?></span></strong></p>
	<p><strong><span style="font-size: 9.0pt; color:#98002e;">TREASURER</span></strong></p>
	<p><em><span style="font-size: 9.0pt;"><?php echo $approvingTreasurer['email']; ?></span></em></p>
	<p>University College JCR</p>
	<p>The Castle</p>
	<p>Palace Green</p>
	<p>Durham</p>
	<p>DH1 3RW</p>
</td>
</tr></table>

<div style="text-align: center; padding-top: 30px;">
	<span style="font-weight: bold; font-size: 14pt;">JCR Expense Form</span>
</div>

<div style="padding-top: 20px;">
This form is intended for members of the JCR who require reimbursement for services and goods purchased for JCR purposes. This form must be co-signed by the relevant Cost Centre manager. If you are unsure who this person is, please contact the Treasurer. If you would like to be paid by bank transfer please fill in the relevant boxes.
</div>
<br />
<div>
No reimbursement can be made without the correct receipts.
</div>

<!--mpdf
<htmlpagefooter name="myfooter">
<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
Page {PAGENO} of {nb}
</div>
</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->

<table class="info-table" width="100%" style="font-size: 9pt; border-collapse: collapse; margin-top: 20px; margin-bottom:" cellpadding="8">
<tbody>
<tr>
	<td width="20%" align="left">Name</td>
	<td width="30%" align="center"><?php echo $claim['id_claim'] ?></td>
	<td width="20%" align="left">CIS Username</td>
	<td width="30%" align="center"><?php echo $claim['claimant_id'] ?></td>
</tr>
<tr>
	<td align="left">Cost Centre</td>
	<td align="center"><?php echo $claim['cost_centre'] ?></td>
	<td align="left">Date</td>
	<td align="center"><?php echo(DateTime::createFromFormat('Y-m-d', $claim['date'])->format('d/m/Y')); ?></td>
</tr>
<tr>
	<td align="left">Account Number</td>
	<td align="center"><?php echo($payment['account_number']); ?></td>
	<td align="left">Sort Code</td>
	<td align="center"><?php echo($payment['sort_code']); ?></td>
</tr>
</tbody>
</table>

<br />
<br />

<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
<thead>
<tr>
<td width="100%" colspan="2">Details of Expenditure</td>
</tr>
<tr>
<td width="75%">Item</td>
<td width="15%">Cost</td>
</tr>
</thead>
<tbody>
<!-- ITEMS HERE -->
<?php
$items = json_decode(str_replace('\\', '', $claim['expenditure_items']), true);
$itemsTotal = 0;
foreach ($items as $item):
?>
<tr>
<td><?php echo($item['Description']); ?></td>
<td class="cost">&pound;<?php echo($item['Price']); $itemsTotal = $itemsTotal + $item['Price']; ?></td>
</tr>
<?php endforeach; ?>

<tr>
<td class="totals"><b>TOTAL:</b></td>
<td class="totals cost"><b>&pound;<?php echo($itemsTotal); ?></b></td>
</tr>

</tbody>
</table>

<br />
<br />
<br />

<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
<thead>
<tr>
<td width="100%" colspan="3">Activity Log</td>
</tr>
<tr>
<td width="20%">User</td>
<td width="20%">Date</td>
<td width="60%">Details</td>
</tr>
</thead>
<tbody>

<!-- COMMENTS HERE -->
<?php foreach ($claim['activities'] as $activity): ?>
<tr>
<td><?php echo($activity['by_id_cis_user']['fullname']); ?></td>
<?php $datestring = DateTime::createFromFormat(DateTime::ISO8601, $activity['activity_datetime']); ?>
<td><?php echo($datestring->format('d/m/Y H:m')); ?></td>
<td><?php
$activityInsert = '';

switch ($activity['activity_type']) {
	case 'create':
		$activityInsert = 'Created this claim.';
		break;

	case 'comment':
		$activityInsert = 'Commented "' . $activity['activity_value'] . '"';
		break;

	case 'change_status':
		$activityInsert = 'Changed status from ' . ClaimStatus::statusIntToString((int)$activity['activity_value_before']) . ' to ' . ClaimStatus::statusIntToString((int)$activity['activity_value']) . '.';
		break;
	
	default:
		break;
}



echo($activityInsert); ?></td>
</tr>
<?php endforeach; ?>


</tbody>
</table>

</body>
</html>