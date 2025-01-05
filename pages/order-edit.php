<?php
if(isset($paths[2])) {
   $check = mysqli_query($con, "SELECT * FROM orders WHERE orderId='".$paths[2]."'");
   $f = mysqli_fetch_assoc($check);
}
if($f['comment'] == "") {
    $f['comment'] = "Nincs megjegyzés";
}
$invoice = json_decode($f['invoiceDatas'], true);
$shipping = json_decode($f['shippingDatas'], true);
?>
<div class="col-md-12">
    <h1 class="mg-bottom-30" data-info="name">Rendelés Szerkesztése</h1>
    <div class="inputs row">
        <div class="input-group edit col-md-4">
			<label>
			    <span>Rendelés száma</span>
			    <input type="text" class="pd-side-15 pd-vertical-10" name="orderNumber" value="<?php echo $f['orderNumber']?>" />
			</label>
        </div>
        <div class="input-group edit col-md-4">
			<label>
			    <span>Rendelés dátuma</span>
			    <input type="text" class="pd-side-15 pd-vertical-10" name="orderDate" value="<?php echo $f['created']?>" disabled />
			</label>
        </div>
        <div class="input-group edit col-md-4">
			<label>
			    <span>Fizetési mód</span>
			    <select class="pd-side-15 pd-vertical-10" name="paymentId">
			    <?php
			        foreach ($paymentMethod as $key => $value) {
			            echo '<option value="'.$key.'" '.($f['paymentId'] == $key ? 'selected' : '').'>
			                '.$value.'
			                </option>';
			        }
			   ?>
			    </select>
			</label>
        </div>
        <div class="input-group edit col-md-4">
			<label>
			    <span>Számla</span>
			    <a class="btn" target="_blank" href="<?php echo $f['invoice']; ?>">Számla Megtekintése</a>
			</label>
        </div>
        <div class="input-group edit col-md-4">
        <label>
			    <span>Van háziállat?</span>
			    <select class="pd-side-15 pd-vertical-10" name="ispet">
			    <?php
			        $statusQuery = mysqli_query($con, "SELECT isPet FROM orders WHERE orderId='" . $paths[2] . "'");
			        $result = mysqli_fetch_assoc($statusQuery);

			        foreach ($pets as $key => $value) {
			            echo '<option value="'.$key.'" '.($result['isPet'] == $key ? 'selected' : '').'>
			                    '.$value.'
			                </option>';
			        }

			    ?>
			    </select>
			</label>
        </div>
        <div class="input-group edit col-md-4">
			<label>
			    <span>Státusz</span>
			    <select class="pd-side-15 pd-vertical-10" name="status">
			    <?php 
			        $statusQuery = mysqli_query($con, "SELECT status FROM orders WHERE orderId='" . $paths[2] . "'");
			        $result = mysqli_fetch_assoc($statusQuery);
			        
			        foreach ($statuses as $key => $value) {
			            echo '<option value="'.$key.'" '.($result['status'] == $key ? 'selected' : '').'>
			                    '.$value.'
			                </option>';
			        }
			    ?>
			    </select>
			</label>
        </div>
        <h2 class="col-md-12 mg-vertical-20">Számlázási adatok</h2>
		
        <div class="input-group edit col-md-4">
			<label>
			    <span>Megrendelő neve</span>
			    <input type="text" class="pd-side-15 pd-vertical-10" name="invoice_last_name" value="<?php echo $invoice['last_name']; ?>" />
			    <input type="text" class="pd-side-15 pd-vertical-10" name="invoice_first_name" value="<?php echo $invoice['first_name'];?>" />
			</label>
        </div>
        <div class="input-group edit col-md-4">
			<label>
			    <span>Irányítószám</span>
			    <input class="pd-side-15 pd-vertical-10 " type="text" name="invoice_postcode" value="<?php echo $invoice['postcode'];?>" />
			</label>
        </div>
        <div class="input-group edit col-md-4">
			<label>
			    <span>Település</span>
			    <input class="pd-side-15 pd-vertical-10 " type="text" name="invoice_city" value="<?php echo $invoice['city'];?>" />
			</label>
        </div>
        <div class="input-group edit col-md-4">
			<label>
			    <span>Házszám</span>
			    <input class="pd-side-15 pd-vertical-10 " type="text" name="invoice_houseno" value="<?php echo $invoice['houseno'];?>" />
			</label>
        </div>
        <div class="input-group edit col-md-4">
			<label>
			    <span>Telefonszám</span>
			    <input class="pd-side-15 pd-vertical-10 " type="text" name="invoice_phone" value="<?php echo $invoice['phone'];?>" />
			</label>
        </div>
        <div class="input-group edit col-md-4">
			<label>
			    <span>Email cím</span>
			    <input class="pd-side-15 pd-vertical-10 " type="text" name="invoice_email" value="<?php echo $invoice['email'];?>" />
			</label>
        </div>
        <h2 class="col-md-12 mg-vertical-20">Szállítási adatok</h2>
		<div class="input-group edit col-md-4">
			<label>
			    <span>Megrendelő neve</span>
			    <input type="text" class="pd-side-15 pd-vertical-10" name="shipping_last_name" value="<?php echo $shipping['last_name']; ?>" />
			    <input type="text" class="pd-side-15 pd-vertical-10" name="shipping_first_name" value="<?php echo $shipping['first_name'];?>" />
			</label>
        </div>
        <div class="input-group edit col-md-4">
			<label>
			    <span>Szállítási mód</span>
			    <select class="pd-side-15 pd-vertical-10" name="shipmentId">
        <?php 
			foreach ($shippingMethod as $key => $value) {
			    echo '<option value="'.$key.'" '.($f['shipmentId'] == $key ? 'selected' : '').'>
			            '.$value.'
			        </option>';
			}
        ?>
			</select>
			</label>
        </div>
        <div class="input-group edit col-md-4">
			<label>
			    <span>Irányítószám</span>
			    <input class="pd-side-15 pd-vertical-10 " type="text" name="shipping_postcode" value="<?php echo $shipping['postcode'];?>" />
			</label>
        </div>
        <div class="input-group edit col-md-4">
			<label>
			    <span>Település</span>
			    <input class="pd-side-15 pd-vertical-10 " type="text" name="shipping_city" value="<?php echo $shipping['city'];?>" />
			</label>
        </div>
        <div class="input-group edit col-md-4">
			<label>
			    <span>Házszám</span>
			    <input class="pd-side-15 pd-vertical-10 " type="text" name="shipping_houseno" value="<?php echo $shipping['houseno'];?>" />
			</label>
        </div>
        <div class="input-group edit col-md-4">
			<label>
			    <span>Telefonszám</span>
			    <input class="pd-side-15 pd-vertical-10 " type="text" name="shipping_phone" value="<?php echo $shipping['phone'];?>" />
			</label>
        </div>
        <div class="input-group edit col-md-4">
			<label>
			    <span>Email cím</span>
			    <input class="pd-side-15 pd-vertical-10 " type="text" name="shipping_email" value="<?php echo $invoice['email'];?>" />
			</label>
        </div>
        <div class="input-group edit col-md-12">
			<label>
			    <span>Megjegyzés</span>
			    <textarea class="pd-side-15 pd-vertical-10" name="description"><?php echo $f['comment']; ?></textarea>
			</label>
        </div>
    </div>
    <h2 class="col-md-12 mg-vertival-20">Rendelt tételek</h2>
	<div class="table-list">
		<table data-table="single-order" class="mg-vertical-20 col-md-12" data-productsAmount="<?php echo $f['productsAmount']; ?>">
			<thead>
				<tr>
					<th class="name">Termék neve</th>
					<th class="quantity">Mennyiség</th>
					<th class="price">Ár fogy.</th>
					<th class="price">Ár Beszerz.</th>
				</tr>
			</thead>
			<tbody>
	<?php
		$check = mysqli_query($con, "SELECT * FROM orders_items WHERE orderId='".$paths[2]."'");
		while($result = mysqli_fetch_assoc($check)) {
			echo '<tr data-info="'.$result['orderId'].'" data-orderitemid="'.$result['orderItemId'].'">
					<td data-info="ordered_product_name" class="product-name"><div>'.$result['name'].'</div></td>
					<td data-info="ordered_product_quantity"><input type="number" id="quantity" name="quantity" value="'.$result['quantity'].'"></td>
					<td data-info="ordered_product_sellPrice">'.formatmoney($result['sellPrice']).'</td>
					<td data-info="ordered_product_buyPrice">'.formatmoney($result['buyPrice']).'</td>
				</tr>';
		}
			echo '<tr data-info="paymentcosts">
					<td data-info="ordered_product_name" class="product-name"><div>Fizetési költség</div></td>
					<td data-info="ordered_product_quantity">1</td>
					<td data-info="ordered_product_sellPrice"><input type="number" name="paymentCost" value="'.$paymentCosts[$f['paymentId']].'"> Ft</td>
					<td data-info="ordered_product_buyPrice"></td>
				</tr>
				 <tr data-info="shipmentcosts">
					<td data-info="ordered_product_name" class="product-name"><div>Szállítási költség</div></td>
					<td data-info="ordered_product_quantity">1</td>
					<td data-info="ordered_product_sellPrice"><input type="number" name="shipmentCost" value="'.($shippingCosts[$f['shipmentId']] == $f['shipmentCost'] ? $shippingCosts[$f['shipmentId']] : $f['shipmentCost']).'"> Ft</td>
					<td data-info="ordered_product_buyPrice"></td>
				</tr>';
	?>
			</tbody>
		</table>
	</div>
    <div class="input-group edit">
		<label>
		    <span>Végösszeg</span>
		    <h2 data-name="totalAmount" class="allprice"><?php echo formatmoney($f['totalAmount']);?></h2>
			<span class="profit mg-left-20">P: <?php echo formatmoney($f['totalAmount'] - $f['productsAmount']);?></span>
		</label>
    </div>
    <div class="options">
        <button data-func="save.order" class="float-r">Mentés</button>
        <button class="float-r mg-right-20">Mégsem</button>
    </div>
</div>