<?php
if (!defined('WEB_ROOT')) {
	exit;
}
$errorMessage = (isset($_GET['error']) && $_GET['error'] != '') ? $_GET['error'] : '&nbsp;';

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
<!-- <a href="#modal" class="open-btn">Add Category Expense</a> -->

<!-- Dummy target to close modal -->
<div id="close"></div>

<?php
include 'add_category.php';
?>
<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-plus-sign"></i> Add Expense</h2>
		</div>

		<form class="form-horizontal" method="post" action="process.php?action=add" enctype="multipart/form-data" name="form" id="form">
			<div class="box-content">
				<?php
				if ($errorMessage == 'Added successfully') {
				?>
					<div class="valid_box">
						<b><?php echo $errorMessage; ?></b>
					</div>
				<?php
				} else if ($errorMessage == 'Expense already exist! Data entry failed.') {
				?>
					<div class="error_box">
						<b><?php echo $errorMessage; ?></b>
					</div>
				<?php
				} else {
				}
				?>
				<fieldset>
					
					<div class="control-group" >
						<label class="control-label" for="focusedInput">Expense Name</label>
						<div class="controls" style="display: flex; align-items: left;">
							<select id="selectError4" name="exp_cat" id="pid" data-rel="chosen" required>
								<option value="">-SELECT-</b></option>
								<?php
								$prd = $conn->prepare("SELECT * FROM tr_expense_category WHERE is_deleted != '1'");
								$prd->execute();
								if ($prd->rowCount() > 0) {
									while ($prd_data = $prd->fetch()) {
								?>
										<option value="<?php echo $prd_data['ec_id']; ?>"><?php echo $prd_data['expense_category_name']; ?></option>
								<?php
									} // End While
								} else {
								}
								?>
							</select>

							<div id="status"></div>
												&nbsp;&nbsp;
						<a href="index.php?view=cat" class="btn btn-success">Add Expense Name -></a>
						</div>
		
					</div>


					<div class="control-group">
						<label class="control-label" for="focusedInput">Amount</label>
						<div class="controls">
							<input class="input-xlarge focused" id="amount" name="amount" type="number" required autocomplete=off />
							<div id="status"></div>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="focusedInput">VAT/non-VAT</label>
						<div class="controls">
						<select name="vat" required id="">
							<option value="">-SELECT-</option>
							<option value="VAT">VAT</option>
							<option value="non-VAT">non-VAT</option>
						</select>

							<div id="status"></div>
						</div>
					</div>

					
					<div class="control-group">
						<label class="control-label" for="focusedInput">TIN Number</label>
						<div class="controls">
							<input class="input-xlarge focused" id="amount" name="tin" type="number" required autocomplete=off />
							<div id="status"></div>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="focusedInput">OR Number</label>
						<div class="controls">
							<input class="input-xlarge focused" id="amount" name="orno" type="number" required autocomplete=off />
							<div id="status"></div>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="focusedInput">Date</label>
						<div class="controls">
							<input class="input-xlarge focused" id="amount" name="exp_date" type="date" required autocomplete=off />
							<div id="status"></div>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="focusedInput">Details</label>
						<div class="controls">
							<textarea name="details" rows="20" cols="20"></textarea>
							<div id="status"></div>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-success">Save</button>
				<input type="button" value="Cancel" onclick="window.location.href='index.php';" class="btn btn-danger">
			</div>
		</form>

	</div>
</div><!--/span-->