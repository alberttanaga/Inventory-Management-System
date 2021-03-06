<?php 
require_once 'php_action/db_connect.php'; 
require_once 'includes/header.php'; 

if($_GET['o'] == 'add'){ 
	echo "<div class='div-request div-hide'>add</div>";
}else if($_GET['o'] == 'manage'){ 
	echo "<div class='div-request div-hide'>manage</div>";
}

?>

<ol class="breadcrumb">
  <li><a href="home.php">Home</a></li>
  <li>Purchase</li>
  <li class="active">
  	<?php if($_GET['o'] == 'add'){ ?>
  		Add Purchases
	<?php } else if($_GET['o'] == 'manage'){ ?>
		Manage Purchases
	<?php } ?>
  </li>
</ol>

<div class="panel panel-default">
	<div class="panel-heading">

		<?php if($_GET['o'] == 'add'){ ?>
  		<i class="glyphicon glyphicon-plus-sign"></i> Add Purchases
		<?php } else if($_GET['o'] == 'manage'){ ?>
			<i class="glyphicon glyphicon-edit"></i> Manage Purchases
		<?php } ?>

	</div>
	<div class="panel-body">
			
		<?php if($_GET['o'] == 'add'){ ?>			

		<div class="success-messages"></div>
  		<form class="form-horizontal" method="POST" action="php_action/createPurchase.php" id="createPurchaseForm">

			  <div class="form-group">
			    <label for="purchaseDate" class="col-sm-2 control-label">Purchase Date</label>
			    <div class="col-sm-10">
			    	<input type="text" class="form-control" id="purchaseDate" name="purchaseDate" autocomplete="off" />
			    </div>
			  </div> 
			  <div class="form-group">
			    <label for="vendorName" class="col-sm-2 control-label">Vendor Name</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="vendorName" name="vendorName" placeholder="Vendor Name" autocomplete="off" />
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="referenceNo" class="col-sm-2 control-label">Reference Number</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="referenceNo" name="referenceNo" placeholder="Reference Number" autocomplete="off" />
			    </div>
			  </div>			  

			  <table class="table" id="productTable">
			  	<thead>
			  		<tr>			  			
			  			<th style="width:40%;">Product</th>
			  			<th style="width:20%;">Price</th>
			  			<th style="width:15%;">Quantity</th>			  			
			  			<th style="width:15%;">Total</th>			  			
			  			<th style="width:10%;"></th>
			  		</tr>
			  	</thead>
			  	<tbody>
			  		<?php
			  		$arrayNumber = 0;
			  		for($x = 1; $x < 2; $x++) { ?>
			  			<tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">			  				
			  				<td style="margin-left:20px;">
			  					<div class="form-group">
			  					<select class="form-control" name="productName[]" id="productName<?php echo $x; ?>" onchange="getProductData(<?php echo $x; ?>)" >
			  						<option value="">---SELECT---</option>
			  						<?php
			  							$productSql = "SELECT * FROM ms_product WHERE is_active = 1 AND quantity != 0";
			  							$productData = $connect->query($productSql);
			  							while($row = $productData->fetch_array()) {									 		
			  								echo "<option value='".$row['product_id']."' id='changeProduct".$row['product_id']."'>".$row['product_name']."</option>";
										 	}
			  						?>
		  						</select>
			  					</div>
			  				</td>
			  				<td style="padding-left:20px;">		  					
			  					<input type="text" name="priceValue[]" id="priceValue<?php echo $x; ?>" autocomplete="off" class="form-control" />			  					
			  				</td>
			  				<td style="padding-left:20px;">
			  					<div class="form-group">
			  					<input type="number" name="quantity[]" id="quantity<?php echo $x; ?>" onkeyup="getTotal(<?php echo $x ?>)" autocomplete="off" class="form-control" min="1" />
			  					</div>
			  				</td>
			  				<td style="padding-left:20px;">			  					
			  					<input type="text" name="total[]" id="total<?php echo $x; ?>" autocomplete="off" class="form-control" disabled="true" />			  					
			  					<input type="hidden" name="totalValue[]" id="totalValue<?php echo $x; ?>" autocomplete="off" class="form-control" />			  					
			  				</td>
			  				<td>
			  					<button class="btn btn-default removeProductRowBtn" type="button" id="removeProductRowBtn" onclick="removeProductRow(<?php echo $x; ?>)"><i class="glyphicon glyphicon-trash"></i></button>
			  				</td>
			  			</tr>
		  			<?php
		  			$arrayNumber++;
			  		}
			  		?>
			  	</tbody>			  	
			  </table>

			  <div class="col-md-6">		  		  	  
				  <div class="form-group">
				    <label for="grandTotal" class="col-sm-3 control-label">Grand Total</label>
				    <div class="col-sm-9">
				      <input type="text" class="form-control" id="grandTotal" name="grandTotal" disabled="true" />
				      <input type="hidden" class="form-control" id="grandTotalValue" name="grandTotalValue" />
				    </div>
				  </div>		  		  
			  </div>

			  <div class="col-md-6">	  
				  <div class="form-group">
				    <label for="paymentStatus" class="col-sm-3 control-label">Payment Status</label>
				    <div class="col-sm-9">
				      <select class="form-control" name="paymentStatus" id="paymentStatus">
				      	<option value="">---SELECT---</option>
				      	<option value="Paid">Full Payment</option>
				      	<option value="Pending">No Payment</option>
				      </select>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="purchaseStatus" class="col-sm-3 control-label">Purchase Status</label>
				    <div class="col-sm-9">
				      <select class="form-control" name="purchaseStatus" id="purchaseStatus">
				      	<option value="">---SELECT---</option>
				      	<option value="Received">Received</option>
				      	<option value="Pending">Pending</option>
				      </select>
				    </div>
				  </div>						  
			  </div>

			  <div class="form-group submitButtonFooter">
			    <div class="col-sm-offset-2 col-sm-10">
			    	<button type="button" class="btn btn-default" onclick="addRow()" id="addRowBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-plus-sign"></i> Add Row</button>
		      		<button type="submit" id="createPurchaseBtn" data-loading-text="Loading..." class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
			    	<button type="reset" class="btn btn-default" onclick="resetPurchaseForm()"><i class="glyphicon glyphicon-erase"></i> Reset</button>
			    </div>
			  </div>
			</form>

		<?php } else if($_GET['o'] == 'manage'){ 
			?>

			<div id="success-messages"></div>	
			<table class="table" id="managePurchaseTable">
				<thead>
					<tr>
						<th>#</th>
						<th>Purchase Date</th>
						<th>Vendor Name</th>
						<th>Purchase No</th>
						<th>Reference No</th>
						<th>Grand Total</th>
						<th>Payment Status</th>
						<th>Purchase Status</th>
						<th>Option</th>
					</tr>
				</thead>
			</table>
		<?php } ?>
	</div>
</div>

<!-- remove purchase -->
<div class="modal fade" tabindex="-1" role="dialog" id="removePurchaseModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Remove Purchase</h4>
      </div>
      <div class="modal-body">
      	<div class="removePurchaseMessages"></div>
        <p>Do you really want to remove ?</p>
      </div>
      <div class="modal-footer removeProductFooter">
        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
        <button type="button" class="btn btn-primary" id="removePurchaseBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> Save changes</button>
      </div>
    </div>
  </div>
</div>

<script src="custom/js/purchase.js"></script>

<?php require_once 'includes/footer.php'; ?>


	