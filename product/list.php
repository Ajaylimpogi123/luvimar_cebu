<?php
if (!defined('WEB_ROOT')) {
	exit;
}

$userId = $_SESSION['user_id'];

$user = $conn->prepare("SELECT * FROM bs_user
			WHERE user_id = '$userId'");
$user->execute();
$user_data = $user->fetch();

/* Select books from database */
$sql = $conn->prepare("SELECT * FROM tbl_product WHERE is_deleted != '1' AND cat_parent_id != '2' ORDER BY pd_name");
$sql->execute();

$errorMessage = (isset($_GET['error']) && $_GET['error'] != '') ? $_GET['error'] : '&nbsp;'
?>
<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-th"></i> List of Products</h2>
			<?php if ($user_data['is_prod_a_access'] == 1) { ?>
				&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:add();" class="btn btn-success">Add New</a>
			
			<?php } else {
			} ?>
			<div class="box-icon">
				<a href="../" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
			</div>
		</div>
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
			}

			?>
			<table class="table table-striped table-bordered bootstrap-datatable datatable">
				<thead>
					<tr>
						<th>Name</th>
						<th>Type</th>
						<th>Serial #</th>
						<th>Picture</th>
						<th>Qty</th>
						<th style="text-align: center;">Status</th>
						<th>Expiration</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ($sql->rowCount() > 0) {
						while ($sql_data = $sql->fetch()) {


							

							$expiration = $sql_data['pd_expiration'] ?? '';
							if ($sql_data['pd_thumbnail']) {
								$image = WEB_ROOT . 'images/product/' . $sql_data['pd_thumbnail'];
							} else {
								$image = WEB_ROOT . 'images/product/noimagelarge.png';
							}


							$cat = $conn->prepare("SELECT * FROM tbl_category WHERE cat_id = '$sql_data[cat_id]'");
							$cat->execute();
							$cat_data = $cat->fetch();

							if ($sql_data['cat_id'] == 0) {
								$cat_data['cat_name'] = 'please Select category';
							} else {
								$cat_data['cat_name'] = $cat_data['cat_name'];
							}

							/*$pdbarcode = mysqli_real_escape_string($link, $sql_data['pd_barcode']);
										$pdname = mysqli_real_escape_string($link, $sql_data['pd_name']);
										$pdkeyword = mysqli_real_escape_string($link, $sql_data['pd_keyword']);
										
										$in = $conn->prepare("INSERT INTO tbl_product (cat_id, cat_parent_id, pd_barcode, pd_name, pd_name7, pd_keyword, pc_price)
																VALUES('$sql_data[cat_id]', '$sql_data[cat_parent_id]', '$pdbarcode', '$pdname', '$pdname', '$pdkeyword', '$sql_data[pd_price]')");
										$in->execute();*/

					?>
							<!-- Start display list of products !-->
							<tr>
								<td><?php echo $sql_data['pd_name']; ?><?php echo $sql_data['pd_description']; ?></td>
								<td><?php echo $sql_data['pd_keyword']; ?></td>
								<td><?php echo $sql_data['pd_barcode']; ?></td>
								<td><img class="dashboard-avatar" alt="<?php echo $sql_data['pd_name']; ?>" src="<?php echo $image; ?>" /></td>
								<td><?php echo number_format($sql_data['pc_qty']); ?></td>
								<td style="text-align:center;">
									<span 
										style="
											display:inline-block;
											padding:6px 14px;
											border-radius:20px;
											font-size:13px;
											font-weight:600;
											color:#fff;
											background: <?= $sql_data['pc_qty'] > 0 ? '#28a745' : '#dc3545' ?>;
										">
										<?= ucfirst($sql_data['pc_qty'] > 0 ? 'available' : 'Sold') ?>
									</span>
								</td>
								<td><?php echo !empty($expiration) ? date("F j, Y", strtotime($expiration)) : ''; ?></td>
								<td class="center">
									<?php if ($user_data['is_prod_e_access'] == 1) { ?>
										<a class="btn btn-primary" href="javascript:mod(<?php echo $sql_data['pd_id']; ?>);">
											<i class="icon-edit icon-white"></i>
											Edit
										</a>
									<?php } else {
										echo "-- --";
									} ?>
									<?php if ($user_data['is_prod_d_access'] == 1) { ?>
										<a class="btn btn-danger" href="javascript:del(<?php echo $sql_data['pd_id']; ?>);">
											<i class="icon-trash icon-white"></i>
											Delete
										</a>
									<?php } else {
										echo "-- --";
									} ?>
								</td>
							</tr>
							<!-- End display list of products !-->
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