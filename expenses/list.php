<?php
if (!defined('WEB_ROOT')) {
	exit;
}
$userId = $_SESSION['user_id'];

$user = $conn->prepare("SELECT * FROM bs_user
			WHERE user_id = '$userId'");
$user->execute();
$user_data = $user->fetch();

$sql = $conn->prepare("SELECT *
        FROM tr_expense
		WHERE is_deleted != '1'
		ORDER BY exp_date_added DESC");
$sql->execute();
$errorMessage = (isset($_GET['error']) && $_GET['error'] != '') ? $_GET['error'] : '&nbsp;';

// Budget
$bg = $conn->prepare("SELECT SUM(amount) as totalbg FROM bs_beginning_balance WHERE is_deleted != '1'");
$bg->execute();
$bg_data = $bg->fetch();

// Expenses
$ex = $conn->prepare("SELECT SUM(amount) as totalex FROM tr_expense WHERE is_deleted != '1'");
$ex->execute();
$ex_data = $ex->fetch();

$balance = $bg_data['totalbg'] - $ex_data['totalex'];
?>
<style>
	/* Open Button */
	.open-btn {
		display: inline-block;
		padding: 10px 18px;
		background: #0d6efd;
		color: #fff;
		text-decoration: none;
		border-radius: 6px;
		font-size: 14px;
	}

	/* Modal Background */
	.modal {
		position: fixed;
		top: 0;
		left: 0;
		width: 140%;
		height: 160%;
		background: rgba(0, 0, 0, 0.5);
		display: flex;
		align-items: center;
		justify-content: center;
		opacity: 0;
		pointer-events: none;
		transition: opacity 0.3s ease;
		z-index: 999;
	}

	/* Show Modal */
	.modal:target {
		opacity: 1;
		pointer-events: auto;
	}

	/* Modal Box */
	.modal-content {
		background: #fff;
		padding: 25px;
		width: 350px;
		border-radius: 10px;
		position: relative;
		text-align: center;
		animation: scaleIn 0.3s ease;
	}

	/* Animation */
	@keyframes scaleIn {
		from {
			transform: scale(0.9);
			opacity: 0;
		}

		to {
			transform: scale(1);
			opacity: 1;
		}
	}

	/* Close Button */
	.close-btn {
		position: absolute;
		top: 10px;
		right: 15px;
		font-size: 22px;
		text-decoration: none;
		color: #555;
	}

	.close-btn:hover {
		color: #000;
	}

	/* Action Button */
	.action-btn {
		margin-top: 20px;
		padding: 10px 18px;
		border: none;
		background: #198754;
		color: #fff;
		border-radius: 6px;
		cursor: pointer;
		font-size: 15px;
	}

	.action-btn:hover {
		background: #157347;
	}
</style>

<!-- Open Modal Button -->
<!-- <a href="#modal" class="open-btn">Add Expense Name</a> -->
<!-- Dummy target to close modal -->
<!-- <div id="close"></div> -->

<?php
// include 'add_category.php';
?>


<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-tag"></i> List of Expenses</h2>
			<?php if ($user_data['is_exp_a_access'] == 1) { ?>
				&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?view=add" class="btn btn-success">Add Expense</a>
				<!-- &nbsp;&nbsp;&nbsp;&nbsp;<a href="beginning.php" class="btn btn-warning nyroModal">Beginning Balance</a> -->
			
			<?php } else {
			} ?>
			<div class="box-icon">
				<a href="../" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
			</div>
		</div>
		<div class="box-content">
			<?php
			if ($errorMessage == 'Beginning balance updated successfully') {
			?>
				<div class="valid_box">
					<b><?php echo $errorMessage; ?></b>
				</div>
			<?php
			} else {
			}
			?>

			<!-- <h5>Budget: <?php echo number_format($bg_data['totalbg'], 2); ?></h5>
			<h5>Expenses: <?php echo number_format($ex_data['totalex'], 2); ?></h5>
			<h4>Balance: <?php echo number_format($balance, 2); ?></h4> -->
			<hr style="border: 0; height: 1px; background-image: -webkit-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); background-image: -moz-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); background-image: -ms-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));  background-image: -o-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); " />

			<table class="table table-striped table-bordered bootstrap-datatable datatable">
				<thead>
					<tr>
						<th>Expense Name</th>
						<th>Date</th>
						<th>Amount</th>
						<th>Details</th>
						<th>VAT</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ($sql->rowCount() > 0) {
						while ($sql_data = $sql->fetch()) {
							$ec_id = $sql_data['ec_id'];
							$cat = $conn->prepare("SELECT * FROM tr_expense_category WHERE ec_id = '$ec_id'");
							$cat->execute();
							$cat_data = $cat->fetch();
							$addeddate = date("F d, Y", strtotime($sql_data['exp_date_added']));
					?>
							<!-- Start display list of expenses !-->
							<tr>
								<td><?php echo $cat_data['expense_category_name'] ?? ''; ?></td>


								<td><?php echo $addeddate; ?></td>
								<td><?php echo number_format($sql_data['amount'], 2); ?></td>
								<td><?php echo $sql_data['details']; ?></td>
								<td><?php echo $sql_data['vat']; ?></td>
								<td class="center">
									<?php if ($user_data['is_exp_e_access'] == 1) { ?>
										<a class="btn btn-primary" href="javascript:mod(<?php echo $sql_data['exp_id']; ?>);">
											<i class="icon-edit icon-white"></i>
											Edit
										</a>
									<?php } else {
										echo "-- --";
									} ?>
									<?php if ($user_data['is_exp_d_access'] == 1) { ?>
										<a class="btn btn-danger" href="javascript:del(<?php echo $sql_data['exp_id']; ?>);">
											<i class="icon-trash icon-white"></i>
											Delete
										</a>
									<?php } else {
										echo "-- --";
									} ?>
								</td>
							</tr>
							<!-- End display list of expenses !-->
					<?php
						}
					} else {
					}
					?>

				</tbody>
			</table>
		</div>
	</div><!--/span-->
</div><!--/row-->