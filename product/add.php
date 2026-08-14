<?php
if (!defined('WEB_ROOT')) {
	exit;
}

$num_str = sprintf("%06d", mt_rand(1, 999999));

$errorMessage = (isset($_GET['error']) && $_GET['error'] != '') ? $_GET['error'] : '&nbsp;';
?>


<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-plus-sign"></i> Add Product</h2>
		</div>

		<form class="form-horizontal" method="post" action="processAdd.php" enctype="multipart/form-data" name="form" id="form">
			<div class="box-content">
				<?php
				if ($errorMessage == 'Added successfully') {
				?>
					<div class="valid_box">
						<b><?php echo $errorMessage; ?></b>
					</div>
				<?php
				} else if ($errorMessage == 'Serial # Already Taken! Data entry failed.') {
				?>
					<div class="error_box">
						<b><?php echo $errorMessage; ?></b>
					</div>
				<?php
				} elseif ($errorMessage == 'Deleted successfully') {
				?>
					<div class="valid_box">
						<b><?php echo $errorMessage; ?></b>
					</div>
				<?php
				} else {
				} ?>
				<fieldset>
					<div class="control-group">
						<label class="control-label" for="focusedInput">Brand</label>
						<div class="controls">
							<select name="category" id="category" required>
								<option value="0">-- Select --</option>
								<?php
								$cat = $conn->prepare("SELECT * FROM tbl_category WHERE cat_parent_id = '0' AND is_deleted != '1' AND cat_id != '2' ORDER BY cat_name");
								$cat->execute();
								while ($cat_data = $cat->fetch()) {
									$categoryname = $cat_data['cat_name'];
									$categoryid = $cat_data['cat_id'];
								?>
									<optgroup label="<?php echo $categoryname; ?>">
										<?php
										$slp = $conn->prepare("SELECT * FROM tbl_category WHERE cat_parent_id = '$categoryid' AND is_deleted != '1' AND cat_id != '2' ORDER BY cat_name");
										$slp->execute();
										while ($slp_data = $slp->fetch()) {
										?>
	
									<option value="<?php echo $slp_data['cat_id']; ?>" data-parent="<?php echo $slp_data['cat_parent_id']; ?>"> <?php echo $slp_data['cat_name']; ?></option>
									<?php
										} // End While
										?>
									</optgroup>
								<?php
								} // End While
								?>
							</select>
							<div id="status"></div>
						</div>
					</div>

					<?php
					$bar = $conn->prepare("SELECT MAX(CAST(pd_barcode AS UNSIGNED)) AS last_barcode FROM tbl_product WHERE is_deleted != '1'");
					$bar->execute();
					$bar_data = $bar->fetch();

					$lastBarcode = $bar_data['last_barcode'];
					// Increment
					$lastBarcode++;

					// Format barcode to 7 digits (e.g. 2026001)
					$serial_inc = str_pad($lastBarcode, 7, STR_PAD_LEFT);
					?>
					<div class="control-group">
						<label class="control-label" for="focusedInput">Serial Number</label>
						<div class="controls">
							<input class="input-xlarge focused" id="barcode"  name="barcode" type="text" />
							<div id="status"></div>
						</div>
					</div>

						<div class="control-group">
						<label class="control-label" for="focusedInput">Product Name</label>
						<div class="controls" style="display: flex; align-items: left;">
							<?php 
							$name = $conn->prepare("SELECT * FROM tr_name WHERE is_deleted != '1' ORDER BY prod_name");
							$name->execute();

							?>
								<select name="pdname" id="selectError" data-rel="chosen" required>
										<option value="0">-- Select --</option>
										<?php
										while ($name_data = $name->fetch()) {
											$prodname = $name_data['prod_name'];
											$prodid = $name_data['n_id'];
										?>
											<option value="<?php echo $prodname; ?>"><?php echo $prodname; ?></option>
										<?php
										} // End While
										?>
								</select>
							<div id="status"></div>
								&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?view=addnamelist" class="btn btn-success">Add Product Name</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="focusedInput">Description</label>
						<div class="controls">
							<textarea id="description" name="description"></textarea>
							<div id="status"></div>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="focusedInput">Cost</label>
						<div class="controls">
							<input class="input-xlarge focused" id="cost" name="cost" type="text" required autocomplete=off />
							<div id="status"></div>
						</div>
					</div>

					<div class="control-group">
						<div class="controls">
							<!-- <input class="input-xlarge focused" id="" name="" type="text" autocomplete=off disabled style="width:77px; border:0px; text-align:center; background-color:#ffffff;" placeholder="Formula" />
							&nbsp; -->
							<input class="input-xlarge focused" id="" name="" type="text" autocomplete=off disabled style="width:77px; border:0px; text-align:center; background-color:#ffffff;" placeholder="Price" />
							&nbsp;
							<input class="input-xlarge focused" id="" name="" type="text" autocomplete=off disabled style="width:77px; border:0px; text-align:center; background-color:#ffffff;" placeholder="Qty" />
							<div id="status"></div>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="focusedInput">Piece</label>
						<div class="controls">
							<!-- <input class="input-xlarge focused" id="pc_frm" name="pc_frm" type="text" autocomplete=off style="width:77px;" placeholder="Formula" required />
							&nbsp; -->
							<input class="input-xlarge focused" id="pc_prc" name="pc_prc" type="text" autocomplete=off style="width:77px;" placeholder="Price" required />
							&nbsp;
							<input class="input-xlarge focused" id="pc_qty" name="pc_qty" type="text" autocomplete=off style="width:77px;" placeholder="Qty" required />
							<div id="status"></div>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="focusedInput">Minimum Qty</label>
						<div class="controls">
							<input class="input-xlarge focused" id="pc_frm" name="mqty" type="number" autocomplete=off style="width:100px;" placeholder="Minimum Qty" required />

							<div id="status"></div>
						</div>
					</div>



					<!-- <div class="control-group">
						<label class="control-label" for="date01">Expiration Date</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="txtFromDate" name="expdate" onkeypress="return isNumberKey(event)" autocomplete=off />
						</div>
					</div> -->

					<div class="control-group">
						<label class="control-label" for="fileInput">Picture</label>
						<div class="controls">
							<input class="input-file uniform_on" name="fileImage" id="fileInput" type="file" />
						</div>
					</div>
				</fieldset>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-success">Save</button>
				<input type="button" value="Cancel" onclick="window.location.href='index.php'" class="btn btn-danger">
			</div>
		</form>

	</div>
</div><!--/span-->

<script>
document.addEventListener("DOMContentLoaded", function () {

    var category = document.getElementById("category");
    var qty = document.getElementById("pc_qty");

    function toggleQty() {

        var option = category.options[category.selectedIndex];

        if (!option) return;

        var parent = option.getAttribute("data-parent");

        // Finish Product (Parent ID = 1)
        if (parent == "1") {

            qty.value = "1";
            qty.readOnly = true;
            qty.style.backgroundColor = "#eeeeee";
            qty.style.cursor = "not-allowed";

        }

        // Other Safety Products (Parent ID = 5)
        else if (parent == "5") {

            qty.readOnly = false;
            qty.style.backgroundColor = "";
            qty.style.cursor = "";

            if (qty.value == "1") {
                qty.value = "";
            }

        }

        // All other categories
        else {

            qty.readOnly = false;
            qty.style.backgroundColor = "";
            qty.style.cursor = "";

        }

    }

    category.addEventListener("change", toggleQty);

    // Run on page load
    toggleQty();

});
</script>