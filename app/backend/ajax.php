<?php
ini_set('max_execution_time', '3600'); //300 seconds = 5 minutes
ini_set('memory_limit', '1G');
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
define('DUCKBILL_RIGHT', '<');
define('DUCKBILL_LEFT', '>');
include("../../_/global.php");
if(isset($_POST['do'])) {
	$do = $_POST['do'];
	foreach($_POST as $k => $v) {
		$post[$k] = $v;
	}
	if(isset($_SESSION['token'])) $token = $_SESSION['token'];
	if(isset($token)) {
		$user_q = mysqli_query($con, "SELECT * FROM users WHERE token='".$token."'");
		if(mysqli_num_rows($user_q) > 0) {
			$user = mysqli_fetch_assoc($user_q);
		} else $ret['dontexisttoken'] = true;
	}
	if(!isset($user) && $do != "login.check") {
		echo json_encode(array('msg' => 'access_denied'), JSON_UNESCAPED_UNICODE);
		die();
	}
	if($do == "get.order-data") {
		$check = mysqli_query($con, "SELECT * FROM orders WHERE orderId='".$post['orderId']."'");
		while($f = mysqli_fetch_assoc($check)) {
			$ret = $f;
		} 
		$getOrderProduct = mysqli_query($con, "SELECT * FROM orders_items WHERE orderId='".$post['orderId']."'");	 
		while($f = mysqli_fetch_assoc($getOrderProduct)) {
			$ret['orderedProducts'][] = $f;
		} 
	} else if($do == "get.product-data") {
		$check = mysqli_query($con, "SELECT * FROM products WHERE productId='".$post['productId']."'");
		$ret = mysqli_fetch_assoc($check);
		$ret['description'] = nl2br(html_entity_decode($ret['description']));
		$checkCateg = mysqli_query($con, "SELECT name FROM categories WHERE categoryId='".$ret['categoryId']."' ");
		$ret['categoryName'] = mysqli_fetch_assoc($checkCateg)['name'];
	} else if($do == "change.status") {
		$overwrite = mysqli_query($con, "UPDATE orders SET status='".$post['newStatus']."' WHERE orderId='".$post['order_Id']."'");
		$ret['msg'] = "ok";
	} else if($do == "delete.product") {
		$deleteProduct = mysqli_query($con, "DELETE FROM products WHERE productId='".$post['deleteId']."'");
		$ret['msg'] = "ok";
	} else if($do == "get.deleted-orders") {
		$deletedOrder = mysqli_query($con, "SELECT * FROM orders WHERE status='deleted' ORDER BY orderId DESC");
		while($f = mysqli_fetch_assoc($deletedOrder)) {
			$ret[] = $f;
		}
	} else if($do == "save.product") {
		$datas = json_decode($post['datas'], true);
		$get = mysqli_query($con, "SELECT imgs FROM products WHERE productId='".$post['id']."'");
		$f = mysqli_fetch_assoc($get);
		$f['imgs'] = json_decode($f['imgs'], true);
		if(isset($post['removePhotos'])) {
			foreach(json_decode($post['removePhotos'], true) as $k => $v) {
				unlink("../../uploads/products/".$f['imgs'][$v]);
				unset($f['imgs'][$v]);
				$updatedImg = json_encode($f['imgs'], JSON_UNESCAPED_UNICODE);
				$update = mysqli_query($con, "UPDATE products SET imgs = '".$updatedImg."' WHERE productId = '".$post['id']."'");
			}
			
		}
		if(isset($_FILES['file']) && is_array($_FILES['file']['name']) && count($_FILES['file']['name']) > 0) {
			for($i = 0; $i < count($_FILES['file']['name']); $i++) {
				move_uploaded_file($_FILES['file']['tmp_name'][$i], "../../uploads/products/".$_FILES['file']['name'][$i]);
				$f['imgs'][] = $_FILES['file']['name'][$i];
			}
		}
		$datas['imgs'] = json_encode($f['imgs'], JSON_UNESCAPED_UNICODE);
		$datas['description'] = htmlentities($datas['description']);
		$datas['url'] = strtolower(str_replace(array_keys($stiliz), array_values($stiliz), $datas['name']));
		$up = "UPDATE products SET ";
		foreach($datas as $k => $v) {
			$up .= "`".$k."`='".$v."',";
		}
		$up = rtrim($up, ",");
		$up .= " WHERE productId='".$post['id']."'";
		$q = mysqli_query($con, $up);
		$ret['msg'] = 'ok';
	} else if($do == "create.product") {
		$datas = json_decode($post['datas'], true);
		if(isset($_FILES['file']) && is_array($_FILES['file']['name']) && count($_FILES['file']['name']) > 0) {
			for($i = 0; $i < count($_FILES['file']['name']); $i++) {
				move_uploaded_file($_FILES['file']['tmp_name'][$i], "../../uploads/products/".$_FILES['file']['name'][$i]);
				$f['imgs'][] = $_FILES['file']['name'][$i];
			}
		}
		$datas['imgs'] = json_encode($f['imgs'], JSON_UNESCAPED_UNICODE);
		$datas['description'] = htmlentities($datas['description']);
		$datas['url'] = strtolower(str_replace(array_keys($stiliz), array_values($stiliz), $datas['name']));
		$up = "INSERT INTO products SET ";
		$up .= "categoryId='".$datas["categoryId"]."', "; 
		$up .= "name='".$datas["name"]."', "; 
		$up .= "description='".$datas["description"]."', "; 
		$up .= "url='".$datas["url"]."', "; 
		$up .= "sku='".$datas["sku"]."', "; 
		$up .= "supplier='".$datas["supplier"]."', "; 
		$up .= "sellPrice='".$datas["sellPrice"]."', "; 
		$up .= "buyPrice='".$datas["buyPrice"]."', "; 
		$up .= "discountPrice=-1, "; 
		$up .= "imgs='".$datas["imgs"]."', "; 
		$up .= "promo=0, ";
		$up .= "created='".date("Y-m-d H:i:s")."'";
		$q = mysqli_query($con, $up);
		$ret['msg'] = "ok";
	} else if($do == "search") {
		if($post['searchable'] == "products") {
			$searchable = "SELECT productId,name,sku,sellPrice,buyPrice,supplier FROM products WHERE name LIKE '%".$post['value']."%' OR sku LIKE '%".$post['value']."%' OR supplier LIKE '%".$post['value']."%'";
		} else if($post['searchable' == "orders"]) {
			$searchable = "SELECT orderId,userId,orderNumber,created FROM orders WHERE orderId LIKE '%".$post['value']."%' OR userId LIKE '%".$post['value']."%' OR orderNumber LIKE '%".$post['value']."%' OR created LIKE '%".$post['value']."%'";
		} else if($post['searchable' == "users"]) {
			$searchable = "SELECT userId,firstName,lastName,email,phone FROM users WHERE userId LIKE '%".$post['value']."%' OR firstName LIKE '%".$post['value']."%' OR lastName LIKE '%".$post['value']."%' OR email LIKE '%".$post['value']."%' OR phone LIKE '%".$post['value']."%'";
		}
		$search = mysqli_query($con, $searchable);
		while($f = mysqli_fetch_assoc($search)) {
			$ret[] = $f;
		}
	} else if($do == "delete.coupon") {
		$delete = mysqli_query($con, "DELETE FROM coupons WHERE couponId='".$post['delete_coupon']."'");
		$ret['msg'] = "ok";
	} else if($do == "edit.coupon") {
		$editableC = mysqli_query($con, "SELECT * FROM coupons WHERE couponId='".$post['couponId']."'");
		$ret = mysqli_fetch_assoc($editableC);
	} else if($do == "update.coupon" || $do == "create.coupon") {
		if($post['coupon']['couponDate'] == "") {
			$post['coupon']['startDate'] = -1;
			$post['coupon']['stopDate'] = -1;
		}
		if(isset($user)) {
			$post['coupon']['userId'] = -1;
		}
		if($do == "create.coupon")
			$up = "INSERT INTO coupons SET ";
		else
			$up = "UPDATE coupons SET ";
		$up .= "userId='".(isset($user) ? $user['id'] : -1)."',";
		$up .= "code='".$post['coupon']['code']."',";
		$up .= "amount='".$post['coupon']['amount']."',";
		$up .= "type='".$post['coupon']['type']."',";
		$up .= "usability='".$post['coupon']['usability']."',";
		$up .= "startDate='".($post['coupon']['startDate'] == '' ? -1 : $post['coupon']['startDate'])."',";
		$up .= "stopDate='".($post['coupon']['stopDate'] == '' ? -1 : $post['coupon']['stopDate'])."',";
		if($do == "create.coupon")
			$up .= "created='".date("Y-m-d H:i:s")."'";
		else {
			$up = rtrim($up, ",");
			$up .= "WHERE couponId=".$post['couponId'];
		}

		$q = mysqli_query($con, $up);
		$ret['msg'] = "ok";
	} else if($do == "get.user-data") {
		$getuser = mysqli_query($con, "SELECT * FROM users WHERE userid='".$post['userid']."'");
		$ret = mysqli_fetch_assoc($getuser);
		$getorder = mysqli_query($con, "SELECT * FROM orders WHERE userid='".$post['userid']."'");
		while($f = mysqli_fetch_assoc($getorder)) {
			$ret['orders'][] = $f;
		}
	} else if($do == "send.email") {
		include('../../phpmailer/class.phpmailer.php');
		include('../../phpmailer/class.smtp.php');
		$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$result = '';
		for ($i = 0; $i < 16; $i++) {
			$result .= $characters[rand(0, strlen($characters))];
		}
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->CharSet = 'UTF-8';
		$mail->SMTPDebug = 0;
		$mail->Port = 465;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = 'vroomvroomtuning.hu';
		$mail->SMTPAuth = true;
		$mail->Username = 'nevalaszolj@vroomvroomtuning.hu';
		$mail->Password = 'VlTr$cZcjSEH';
		$mail->setFrom('nevalaszolj@vroomvroomtuning.hu', 'VroomVroomTuning Webshop');
		$mail->addReplyTo("info@vroomvroomtuning.hu", "VroomVroomTuning Webshop");
		$mail->AltBody = '...';
		$mail->addAddress($post['datas']['email'], $post['datas']['name']);
		$mail->Subject = $post['datas']['subject'];
		$text = $post['datas']['content'];
		$text .= '<img src="https://vvtadmin.andristyak.hu/mail_trigger.php?email='.$result.'" width="1" height="1" />';
		$mail->msgHTML($text, dirname(__FILE__));
		$mail->send();
		$ret['msg'] = 'ok';
		$up = "INSERT INTO emails SET ";
		$up .= "name='".$post['datas']['name']."',";
		$up .= "address='".$post['datas']['email']."',";
		$up .= "subject='".$post['datas']['subject']."',";
		$up .= "content='".$post['datas']['content']."',";
		$up .= "created='".date("Y-m-d H:i:s")."',";
		$up .= "open='".$result."'";
		$q = mysqli_query($con, $up);
	} else if($do == "get.emails") {
		$getCurrEmail = mysqli_query($con, "SELECT * FROM emails WHERE id='".$post['emailid']."'");
		$ret = mysqli_fetch_assoc($getCurrEmail);
	} else if($do == "save.order") {
		$up = "UPDATE orders SET ";
		$datas = $post['datas'];
		foreach($datas as $k => $v) {
			if($k == "orderId") continue;
			if(is_string($v)) {
				$up .= "`".$k."`='".$v."',";
			}
		}
		$up .= "`paymentCost`='".$datas['costs']['paymentCost']."',";
		$up .= "`shipmentCost`='".$datas['costs']['shipmentCost']."' ";
		$up .= "WHERE orderId='".$datas['orderId']."'";
		$q = mysqli_query($con, $up);
		foreach ($datas['product'] as $product) {
			$updateProd = mysqli_query($con, "UPDATE orders_items SET quantity='".$product['quantity']."' WHERE orderItemId='".$product['orderItemId']."'");
		}
		$ret['msg'] = "ok";
	} else if($do == "create.slide") {
		$datas = json_decode($post['datas'], true);
		if(isset($_FILES['file']) && is_array($_FILES['file']['name']) && count($_FILES['file']['name']) > 0) {
			for($i = 0; $i < count($_FILES['file']['name']); $i++) {
				move_uploaded_file($_FILES['file']['tmp_name'][$i], "../../uploads/slider/".$_FILES['file']['name'][$i]);
				$f['imgs'][] = $_FILES['file']['name'][$i];
			}
		}
		$datas['imgs'] = json_encode($_FILES['file']['name'][0], JSON_UNESCAPED_UNICODE);
		$datas['content'] = htmlentities($datas['content']);
		$up = "INSERT INTO sliders SET ";
		$up .= "image='".$datas["imgs"]."', "; 
		$up .= "title='".$datas["title"]."', "; 
		$up .= "content='".$datas["content"]."', "; 
		$up .= "button='".(isset($datas["button"]) ? $datas["button"] : "")."', "; 
		$up .= "link='".(isset($datas["url"]) ? $datas["url"] : "")."'"; 
		$q = mysqli_query($con, $up);
		$ret['msg'] = "ok";
	} else if($do == "delete.slide") {
		$check = mysqli_query($con, "SELECT * FROM sliders WHERE sliderId='".$post["deleteid"]."'");
		$f = mysqli_fetch_assoc($check);
		$f['image'] = str_replace('"', '', trim($f['image']));
		if(file_exists("../../uploads/slider/" . $f['image'])) {
			unlink("../../uploads/slider/" . $f['image']);
		}
		$dSlide = mysqli_query($con, "DELETE FROM sliders WHERE sliderId='".$post["deleteid"]."'");
		$ret['msg'] = "ok";
	} else if($do == "update.slider-sort") {
		foreach($post['datas'] as $k => $v) {
			$update = mysqli_query($con, "UPDATE sliders SET sort='".$v."' WHERE sliderId='".$k."'");
		}
		$ret['msg'] = "ok";
	} else if($do == "save.slide") {
		$datas = json_decode($post['datas'], true);
		$get = mysqli_query($con, "SELECT image FROM sliders WHERE sliderId='" . $post['id'] . "'");
		$f = mysqli_fetch_assoc($get);
		if(isset($_FILES['file']['name'][0]) && !empty($_FILES['file']['name'][0]) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
			move_uploaded_file($_FILES['file']['tmp_name'][0], "../../uploads/slider/".$_FILES['file']['name'][0]);
			$datas['image'] = $_FILES['file']['name'][0];
		} else $datas['image'] = "";
		$datas['content'] = htmlentities($datas['content']);
		
		$up = "UPDATE sliders SET ";
		foreach ($datas as $k => $v) {
			$up .= "`" . $k . "`='" . $v . "',";
		}
		$up = rtrim($up, ",");
		$up .= " WHERE sliderId='".$post['id']."'";
		$q = mysqli_query($con, $up);
		$ret['msg'] = 'ok';
	} else if($do == "login.check") {
		$password = hash('sha256', $post['password']);
        $check = mysqli_query($con, "SELECT * FROM admins WHERE username='".mysqli_real_escape_string($con, $post['username'])."' AND password='".mysqli_real_escape_string($con, $password)."'");
		if(mysqli_num_rows($check) > 0) {
            $f = mysqli_fetch_assoc($check);
            $token = md5(str_shuffle(date("Y-m-d H:i:s").$f["created"].$f['username']));
            $update = mysqli_query($con, "UPDATE admins SET token='".$token."' AND lastLogin='".date("Y-m-d")."' WHERE id='".$f['id']."'");
			$_SESSION['token'] = $token;
			if(isset($_SESSION['token'])) {
				$ret['token'] = $token;
				$ret['msg'] = "ok";
			} else {
				$ret['msg'] = "not-ok";
			}
        } else {
            $ret['msg'] = "not-ok";
        } 
	}

	//do end
}
if(isset($ret)) echo json_encode($ret, JSON_UNESCAPED_UNICODE);