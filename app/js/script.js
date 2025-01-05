var mychart;
var gets = {};
window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(str,key,value) { gets[key] = (isset(value) ? value : true); });
var hash = window.location.hash.replace('#', '');
var paths = {};
var wtf = "teszt";
var statuses = {
	'wait-to-pay': 'Fizetésre vár',
	'paid': 'Fizetve',
	'processing': 'Folyamatban',
	'in-transit': 'Szállítás alatt',
	'done': 'Kiszállítva',
	'deleted': 'Törölt'
};
var paymentMethod = {
	's' : 'Stripe',
	'a' : 'Átutalás'
};
var shippingMethod = {
	'm' : 'MPL házhozszállítás',
	'g' : 'GLS házhozszállítás'
};
var pets = {
	'dog': 'Gugyus',
	'cat': 'Macska',
	'': 'Nincs',
	'0': 'Nincs',
	'-1': 'Nincs',
	'none': 'Nincs'
};
var paymentCosts = {
	's': 0,
	'a': 450
};
var shippingCosts = {
	'm': 6500,
	'g': 5500
}
var debounceTimer;
var files = {};
var removePhotos = {};
function callTemp() {
	$('[data-temp]').each(function() {
		_.temp[$(this).attr('data-temp')] = $(this);
		_.temp[$(this).attr('data-temp')].removeAttr('data-temp');
		$(this).remove();
	});
}
var _ = {"temp": {}};
refresh_paths();
$(function() {
    callTemp();
    $(document).on('click', '[data-func]:not(input)', function(e) {
		data_func($(this),e);
	}).on('keyup', 'input[data-func]', function(e) {
		data_func($(this),e);
    }).on('click', '.overlay', function() {
        $('.modal').remove();
        $(this).addClass("hidden");
    }).on('click', '.open-hamburger' , function() {
        $('[data-menu="hamburger"]').toggleClass('hidden');
    }).on('keyup', function(event) {
		if(event.key == "Escape") {
			$('.modal').remove();
			$(".overlay").addClass("hidden");
			$('html').removeClass('overflow-h');
		}
	}).on('keyup', '[name="search"]', function() {
		let value = $(this).val().toLowerCase();
		let searchable = $(this).attr("data-searchable");
		if(value !== "" && value.length > 3) {
			clearInterval(debounceTimer);
			debounceTimer = setTimeout(function() {
			
			"search".backend({value: value, searchable: searchable})
			.done(function(r) {
					r = $.parseJSON(r);
					$.each(r, function(k, v) {
						
						let clone = _.temp['search-item'].clone();
						$.each(v, function(k2, v2) {
							console.log(k2, v2)
								if(k2 == "productId") {
									clone.attr("data-productid", v2);
								} else if(k2 == "categoryId") {
									clone.attr("data-category", v2);
								} else {
									clone.find('[data-search="'+k2+'"]').html(v2);
								}
							})	
						curr_page().find("tbody").append(clone);
					})
				}, 2000);
			})
		}
		$('[data-table]').find('tbody').find('tr').filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		})
	}).on('change', '[name="categories-select"]', function()  {
		let selected = $(this).val();
		$('[data-table="products"]').find('tbody').find('tr').each(function() {
			let categ = $(this).attr('data-category');
			if (selected == 'all' || categ == selected) {
				$(this).show();
			  } else {
				$(this).hide();
			  }
		})
	}).on('change', '[name="show.deleted-orders"]', function() {
		$('[data-table="order"]').find('tbody').find('tr').toggleClass("hidden");		
	}).on('change', '[name="upload-photos-to-product"]',function(e) {
		let t = $(this);
		$.each(e.target.files, function(k, v) {
			let clone = _.temp['product-photo'].clone();
			let reader = new FileReader();
			reader.onload = function (event) {
				clone.find('.pics-content').find('img').attr("src", event.target.result);
				$('.manage-pics').append(clone);
			};
			reader.readAsDataURL(v);
			files[count(files)] = v;
		})
	}).on('change', '[name="upload-photo-to-slider"]',function(e) {
		let t = $(this);
		let file = e.target.files[0];
		if (file) {
			let reader = new FileReader();
			reader.onload = function (event) {
				$('[data-pics="new_pics"]').find('.pics-content').find('img').attr("src", event.target.result);
			};
			reader.readAsDataURL(file);
			files[count(files)] = file;
		}
	}).on('change', '[name="extra-taxes"]', function() {
		let getPrice = $('.input-group').find('label').find('.allprice').html().replace(/\D/g, '');
		$('.input-group').find('label').find('.allprice').html(formatMoney(parseInt(getPrice) - $(this).val()));
	}).on('click', '[name="costumCode"]', function() {
		if($(this).is(':checked')) {
			$('[data-btn="generate.code"]').attr("disabled", true);
			$('[data-btn="generate.code"]').toggleClass("inactive");
		} else {
			$('[data-btn="generate.code"]').removeAttr("disabled");
			$('[data-btn="generate.code"]').toggleClass("inactive");
		}
	}).on('click', '[name="couponDate"]', function() {
		if($(this).is(':checked')) {
			$('[name="startDate"], [name="stopDate"]').prop("disabled", true);
		} else {
			$('[name="startDate"], [name="stopDate"]').prop("disabled", false);
		}
	}).on('click', '[name="userId"]', function() {
		if($(this).is(':checked')) {
			$('[name="select.user"]').prop("disabled", true);
			$('[name="select.user"]').toggleClass('inactive');
		} else {
			$('[name="select.user"]').prop("disabled", false);
			$('[name="select.user"]').toggleClass('inactive');
		}
	}).on('keyup', '[data-info="ordered-product_orderId"] [name="paymentCost"],[data-info="ordered-product_orderId"] [name="shipmentCost"]', function() {
		let productsAmount = parseInt($(this).closest("table").attr("data-productsAmount"));
		let paymentCost = ($(this).closest("table").find('[name="paymentCost"]').val() == '' ? 0 : parseInt($(this).closest("table").find('[name="paymentCost"]').val()));
		let shipmentCost = ($(this).closest("table").find('[name="shipmentCost"]').val() == '' ? 0 : parseInt($(this).closest("table").find('[name="shipmentCost"]').val()));
		let totalAmount = productsAmount + paymentCost + shipmentCost;
		curr_page().find('[data-name="totalAmount"]').html(formatMoney(totalAmount));
	}).on('click', '[name="need_btn"]', function() {
		if($(this).is(':checked')) {
			$('[name="button"], [name="url"]').prop("disabled", true);
		} else {
			$('[name="button"], [name="url"]').prop("disabled", false);
		}
	}).on('click', '.hamburger a', function() { 
		$('.hamburger').toggleClass('hidden');
	}).on('click', '.floating-options button', function() {
		$('.floating-options').toggleClass("hidden");
	});
    // Document end
    let page;
    if(!isset(paths[1]) || paths[1] == '' || paths[1] == "index.php") page = 'main';
    if(paths[1] !== "main" && paths[1] !== "orders" && paths[1] !== "sliders" && paths[1] !== "product-edit" && paths[1] !== "order-edit" && paths[1] !== "login" && paths[1] !== "coupons" && paths[1] !== "users" && paths[1] !== "create-product" && paths[1] !== "emails" && paths[1] !== "email-send" && paths[1] !== "slider-create" && paths[1] !== "slider-edit") {
        
        page = "main";
    }
	page = paths[1];
	if(page == '') page = 'main';
    $('[data-page="'+page+'"]').removeClass("hidden");
    $('[data-page].hidden').remove();
    if(paths[1] == "main") {

    } else if(paths[1] == "product-edit") {
		editor();
    } else if(paths[1] == "product-create") {
		editor();
	} else if(paths[1] == "email-send") {
		editor();
	} else if(paths[1] == "slider-create") {
		editor();
	} else if(paths[1] == "slider-edit") {
		editor();
	}
	if(isset(paths[2])) {
		if(paths[1] == "products") {
			$('tr[data-productid="'+paths[2]+'"]').find('[data-func="open.product-modal"]').trigger("click");
		} else if(paths[1] == "users") {
			$('tr[data-userid="'+paths[2]+'"]').find('[data-func="open.details-user-modal"]').trigger("click");
		} else if(paths[1] == "orders") {
			$('tr[data-orderid="'+paths[2]+'"]').find('[data-func="open.order-details-modal"]').trigger("click");
		}
	}
});
function curr_page() {
    return $('[data-page]:not(.hidden)');
}
function data_func(t,e) {
	var func = t.attr('data-func');
	let datas = {};
	let next = true;
	if(func == "open.order-details-modal") {
		modalclose();
       "order-details".modal(function(_m) {
			 "get.order-data".backend({orderId: t.closest("tr").attr("data-orderid")})
			 	.done(function(r) {
					r = $.parseJSON(r);
					
					
					r.invoiceDatas = $.parseJSON(r.invoiceDatas);
					r.shippingDatas = $.parseJSON(r.shippingDatas);
					r.fullname = r.invoiceDatas.last_name+" "+r.invoiceDatas.first_name;
					r.profitAmount = Math.abs(r.productsAmount - r.totalAmount);
					
					invoiceAddress = r.invoiceDatas.postcode+" "+r.invoiceDatas.city+", "+r.invoiceDatas.houseno;
					shippingAddress = r.shippingDatas.postcode+" "+r.shippingDatas.city+", "+r.shippingDatas.houseno;
					_m.attr("data-orderId", r.orderId);
					window.history.pushState(null, '', '/orders/'+_m.attr("data-orderID"));
					$('.edit-btn').find('a').attr("href", "/order-edit/"+r.orderId+"");
					$.each(r, function(k,v) {
						if(v !== '' && k.toLowerCase().indexOf("amount") !== -1) v = formatMoney(v);
						if(k == "paymentId") {
							_m.find('[data-info="'+k+'"]').find('span').html(paymentMethod[v]);
						} else if(k == "shipmentId") {
							_m.find('[data-info="'+k+'"]').find('span').html(shippingMethod[v]);
						} else if(k == "status") {
							_m.find('[data-info="paid-status"]').find('[data-info="'+k+'"]').html(statuses[v]);
						} else if(k == "isPet") { 
							_m.find('[data-info="'+k+'"]').find('span').html(pets[v]);
						} else if(k == "orderId") {
							_m.find('[data-info="orderid"]').attr("data-orderid", v);
						} else if(k == "paidDate" || k == "paid") {
							if(v == 0 || v == null) {
								$('[data-info="paidDate"]').addClass('hidden');	
							}
							_m.find('[data-info="'+k+'"]').html(v);
						} else if (k == "fullname") {
							if(r.userId !== -1) {
								$("[data-info=fullname]").find('span').html('<a href="/users/'+r.userId+'">' + r.fullname + '</a>');
							} else {
								$("[data-info=fullname]").find('span').html(r.fullname);
							}
						} else {
							_m.find('[data-info="'+k+'"]').find("span").html(((v == null || v == '' || v == -1) ? 'N/A' : v));
						}
					});
					_m.find('[data-info="invoice_tax_number"]').find("span").html((r.invoiceDatas.tax_number == "" ? 'N/A' : r.invoiceDatas.tax_number));
					_m.find('[data-info="totalAmount"]').html(formatMoney(r.totalAmount));
					_m.find('[data-info="profitAmount"]').html(formatMoney(r.profitAmount));
					
					_m.find('[data-info="shipment_phone"]').find("span").html(r.shippingDatas.phone);
					_m.find('[data-info="shipment_email"]').find("span").html(r.shippingDatas.email);
					_m.find('[data-info="invoice_phone"]').find("span").html(r.invoiceDatas.phone);
					_m.find('[data-info="invoice_email"]').find("span").html(r.invoiceDatas.email);
					_m.find('[data-info="invoice_address"]').find("span").html(invoiceAddress);
					_m.find('[data-info="shipment_address"]').find("span").html(shippingAddress);
					$.each(r.orderedProducts, function(k, v) {
						let clone = _.temp['o-items'].clone();
						$.each(v, function(k2,v2) {
							if(v2 !== '' && k2.toLowerCase().indexOf("price") !== -1) v2 = formatMoney(v2);
							clone.find('[data-info="ordered_product_'+k2+'"]').html(v2);
						})
						_m.find('tbody').append(clone);
					})
					let clone_s = _.temp['o-items'].clone();
					clone_s.find('td').eq(0).html("Szállítási költség");
					clone_s.find('td').eq(2).html(formatMoney(shippingCosts[r.shipmentId]));
					_m.find('tbody').append(clone_s);
					let clone_p = _.temp['o-items'].clone();
					clone_p.find('td').eq(0).html("Fizetés költség");
					clone_p.find('td').eq(2).html(formatMoney(paymentCosts[r.paymentId]));
					_m.find('tbody').append(clone_p);
				})
        });
    } else if(func == "close-modal") {
        modalclose();
		window.history.pushState(null, '', '/'+paths[1]);
    } else if(func == "edit.status") {
		$('.options-edit-status').toggleClass('hidden');
	} else if(func == "open.product-modal") {
		"product-details".modal(function(_m) {
			"get.product-data".backend({productId: t.closest('tr').attr('data-productid')})
				.done(function(r) {
					r = $.parseJSON(r);
					
					_m.attr("data-productId", r.productId);
					_m.find('[data-info="name"]').html(r.name);
					window.history.pushState(null, '', '/products/'+_m.attr("data-productId"));
					$('.edit-btn').find('a').attr("href", "/product-edit/"+r.productId+"");				
					$.each(r, function(k,v) {
						if(v !== '' && k.toLowerCase().indexOf("price") !== -1) v = formatMoney(v);
						_m.find('[data-info="'+k+'"]').find("span").html(((k !== "paidDate" && (v == null || v == '' || v == -1)) ? 'N/A' : v));
					});
					r.imgs = $.parseJSON(r.imgs);
					$.each(r.imgs, function(k, v) {
						if(k == 0) {
							_m.find('.main-img').attr("src", "/uploads/products/"+v);	
						} else {
							let img = $('<img />').addClass('mg-right-10').attr('src', "/uploads/products/"+v);
							_m.find('.other-pic').append(img);
						}
					})
				})
		})
	} else if(func == "change.status") {
		"change.status".backend({newStatus: $('[name="status-change"]').val(), order_Id: $('[data-info="orderid"]').attr("data-orderid")})
			.done(function(r) {
				r = $.parseJSON(r);
				if(r.msg == "ok") {
					alert("Státusz módosítás sikeresen megtörtént!");
					$('[data-info="paid-status"]').find('[data-info="status"]').html(statuses[$('[name="status-change"]').val()]);
					$('.options-edit-status').toggleClass('hidden');
				} else {
					alert("Státusz módosítás sikertelen!");
				}
			})
	} else if(func == "delete.product") {
		if(confirm('Biztos, hogy törölni szeretnéd az alábbi terméket?\n\n'+t.closest('tr').find('.product-name div').html()+'\n\nA művelet nem vonható vissza!')) {
			
			"delete.product".backend({deleteId: t.closest('tr').attr('data-productid')})
				.done(function(r) {
					r = $.parseJSON(r);
					
					if(r.msg == "ok") {
						t.closest('tr').remove();
						alert('Törlés sikeresen megtörtént!');
					} else {
						alert('Törlés sikertelen');
					}
				})
		}
	} else if(func == "delete.modal-product") {
		let productName = '';
		let productId;
		if(t.closest(".modal").length > 0) { // Ha modal
			productName = $('.modal-header').find('h1').html();
			productId = $('.modal-header').attr("data-productid");
		} else {
			productName = t.closest("tr").find("td").eq(0).html();
			productId = t.closest("tr").attr("data-productid");
		}
		if(confirm('Biztos, hogy törölni szeretnéd az alábbi terméket?\n\n'+productName+'\n\nA művelet nem vonható vissza!')) {
			"delete.product".backend({deleteId: productId})
				.done(function(r) {
					r = $.parseJSON(r);
					if(r.msg == "ok") {
						$('[data-table="products"]').find('tbody').find('[data-productid="'+productId+'"]').remove();
						$('.modal').remove();
						$(".overlay").addClass("hidden");
						$('html').removeClass('overflow-h');
						alert('Törlés sikeresen megtörtént!');
					}
				})
		}
	} else if(func == "upload.photos-to-product") {
		$('[name="upload-photos-to-product"]').trigger('click');
	} else if(func == "set.main-pic") {
		let clone = t.closest('.pics').clone();
		$('.manage-pics').find('.upload').after(clone);
		t.closest('.pics').remove();
	} else if(func == "save.order") {
		if (confirm("Biztos benne, hogy menti a változtatásokat?")) {
			datas.invoiceDatas = {};
			datas.shippingDatas = {};
			datas.product = [];
			datas.costs = {};
			$('.input-group').find('input:not([disabled]), select').each(function () {
				let key = $(this).attr("name");
				let val = $(this).val();
				if(key.startsWith("invoice_")) {
					datas.invoiceDatas[key.replace("invoice_", "")] = val;
				} else if (key.startsWith("shipping_")) {
					datas.shippingDatas[key.replace("shipping_", "")] = val;
				} else
					datas[key] = val;
			});
			datas.invoiceDatas = JSON.stringify(datas.invoiceDatas);
			datas.shippingDatas = JSON.stringify(datas.shippingDatas);
			$('tr[data-orderitemid]').each(function () {
				datas.product.push({
					orderItemId: $(this).attr("data-orderitemid"),
					quantity: $(this).find('td[data-info="ordered_product_quantity"]').find('input[name="quantity"]').val()
				});
			})
			datas.costs['paymentCost'] = $('[name="paymentCost"]').val();
			datas.costs['shipmentCost'] = $('[name="shipmentCost"]').val();
			datas['totalAmount'] = $('.input-group').find('label').find('.allprice').html().replace(/\D/g, '');
			datas['orderId'] = paths[2];
			"save.order".backend({datas: datas})
				.done(function(r) {
					r = $.parseJSON(r);
					
					if(r.msg == "ok") {
						alert("Sikeres módosítás!");
						window.location.href = '/orders/'+paths[2];
					}
				})
		}
	} else if(func == "save.product" || func == "create.product") {
		$(".input-group").find("input, select").each(function() {
			datas[$(this).attr("name")] = $(this).val();
		});
		datas['description'] = tinymce.get("editor").getContent();
		let files2 = {};
		$.each(files, function(k,v) {
			files2[k] = v;
		});
		if(func == "save.product") {
			"save.product".backend({id: paths[2], datas: datas, removePhotos: JSON.stringify(removePhotos)}, files2)
				.done(function(r) {
					r = $.parseJSON(r);
					
					if(r.msg == "ok") {
						alert("Változások elmentve!");
					}
					removePhotos = {};
					// files = {};
				});
		} else if(func == "create.product") {
			
			"create.product".backend({ datas: datas, removePhotos: JSON.stringify(removePhotos)}, files2)
				.done(function(r) {
					r = $.parseJSON(r);
					
					if(r.msg == "ok") {
						alert("Termék létrehozva");
					}
					removePhotos = {};
					// files = {};
				});
		}
	}else if(func == "remove.img") {
		t.parent().remove();
	} else if(func == "delete.img") {
		t.parent().css({"opacity": 0.5, "filter": "grayscale(5)"});
		removePhotos[count(removePhotos)] = t.attr("data-img");
	} else if(func == "edit.coupon") {
		$.each(t.closest('tr'), function(k, v) {
		})
	} else if(func == "open.create-coupon-modal") {
		"coupon".modal(function(_m) {
			$('[name="code"]').val(generateCouponCode(8).toUpperCase());
		});
	} else if(func == "generate.coupon-code") {
		$('[name="code"]').val(generateCouponCode(8).toUpperCase());
	} else if(func == "create.coupon") {
		if(confirm("Létrehozod a kupont?")) {
			datas.coupon = {};
			$('.c-group').find('input:not([disabled]), select:not(.inactive)').removeClass("error-border");
			$('.c-group').find('input:not([disabled]), select:not(.inactive)').each(function () {
				datas.coupon[$(this).attr("name")] = ($(this).attr("name") == "checkbox" ? ($(this).is(":checked") ? true : false) : $(this).val());
				if($(this).val().length < 1) {
					$(this).addClass("error-border");
					next = false;
				}
			});
			if(next) {
				
				"create.coupon".backend({ coupon: datas.coupon })
					.done(function (r) {
						r = $.parseJSON(r);
						
						if(r.msg == "ok") {
							alert('Kupon létrehozva!');
							modalclose();
							location.reload();
						} else {
							alert('létrehozás sikertelen');
						}
					});
			}
		}
	} else if(func == "delete.coupon") {
		if(confirm("Biztos törlöd a kupont? A folyamat nem visszafordítható!")){
			"delete.coupon".backend({delete_coupon: t.closest('tr').attr("data-couponId")})
				.done(function(r) {
					r = $.parseJSON(r);
					
				})
			t.closest("tr").remove();
		}
	} else if(func == "change.coupon") {
		"coupon".modal(function(_m) {
			_m.find('#create-update').attr("data-func", "update.coupon");
			_m.find('#create-update').attr("data-couponId", t.closest('tr').attr("data-couponId"));
			_m.find('#create-update').html("Kupon frissítése");
			$('[name="costumCode"]').prop('checked', true);
			$('[data-btn="generate.code"]').toggleClass("inactive");
			"edit.coupon".backend({couponId: t.closest('tr').attr("data-couponId")})
				.done(function(r) {
					r = $.parseJSON(r);
					
					_m.attr("data-coupon", r.couponId);
					$.each(r, function(k, v) {
						if(k == "startDate" || k == "stopDate") {
							if(v == -1) {
								_m.find('.c-code').find('.c-group').find('[name="couponDate"]').prop('checked', true);
								$('[name="startDate"], [name="stopDate"]').prop("disabled", true);
							} else {
								_m.find('.c-code').find('.c-group').find('[name="'+k+'"]').val(v);
							}
						} else if(k == "userId") {
							if(v == -1) {
								_m.find('.c-code').find('.c-group').find('[name="userId"]').prop('checked', true);
								$('[name="select.user"]').prop('disabled', true);
								$('[name="select.user"]').toggleClass('inactive');
							} else {
								_m.find('.c-code').find('.c-group').find('[name="'+k+'"]').val(v);
							}
						} else {
							_m.find('.c-code').find('.c-group').find('[name="'+k+'"]').val(v);
						}
					})
				})
		})
	} else if(func == "update.coupon") {
		if(confirm("Létrehozod a kupont?")) {
			datas.coupon = {};
			let allValid = true; 
			$('.c-group').find('input:not(:disabled), select:not(.inactive)').each(function () {
					let key = $(this).attr("name");
					let val;
					if ($(this).attr("type") === "checkbox") {
						val = $(this).is(":checked") ? false : true;
					} else {
						val = $(this).val();
					}
					if (val === "" || val === null) {
						allValid = false;
						$(this).addClass('error-border');
					} else {
						$(this).removeClass('error-border');
						datas.coupon[key] = val;
					}
			});
			if (!allValid) {
				return;
			}
			
			"update.coupon".backend({coupon: datas.coupon, couponId: t.attr("data-couponId") })
				.done(function (r) {
					r = $.parseJSON(r);
					
					if(r.msg == "ok") {
						alert('Kupon frissítve');
					} else {
						alert('Frissítés sikertelen');
					}
				});
			
		}
	} else if(func == "open.details-user-modal") {
		"details-user".modal(function(_m) {
			_m.attr("data-userId", t.closest('tr').attr("data-userid"));
			window.history.pushState(null, '', '/users/'+_m.attr("data-userId"));
			"get.user-data".backend({userid: t.closest('tr').attr("data-userid")})
				.done(function(r) {
					r = $.parseJSON(r);
					let fullname = r.firstName + " " + r.lastName;
					let address = $.parseJSON(r.shipping);
					address_shipping = address.postcode + " " + address.city + " " + address.houseno;
				 	address = $.parseJSON(r.invoice);
					address_inv = address.postcode + " " + address.city + " " + address.houseno;
					
					let ch;
					$.each(r, function(k, v) {
						$("[data-info=name]").find('span').html(fullname);
						if(k == "defaultShipment") {
							$('[data-info="'+k+'"]').find("span").html(shippingMethod[v]);
						} else if (k == "defaultPayment") {
							$('[data-info="'+k+'"]').find("span").html(paymentMethod[v]);
						} else if(k == "shipping" || k == "invoice") {
							v = $.parseJSON(v);
							if(k == "shipping") {
								ch = "shipment_";
								
							} else if(k == "invoice") {
								ch = "invoice_";
							}
							
							$.each(v, function(k2, v2) {
								$("[data-info="+ch+k2+"]").find('span').html(((v2 == null || v2 == '' || v2 == -1) ? 'N/A' : v2));
							})
						} else {
							$('[data-info="'+k+'"]').find("span").html(((v == null || v == '' || v == -1) ? 'N/A' : v));
						}
						$("[data-info=invoice_address]").find('span').html(address_inv);
						$("[data-info=shipment_address]").find('span').html(address_inv);
					})
					$.each(r.orders, function(k, v) {
						let clone = _.temp['u-orders'].clone();
						$.each(v, function(k2, v2) {
							if(v2 !== '' && k2.toLowerCase().indexOf("amount") !== -1) v2 = formatMoney(v2);
							if(k2 == "orderId") {
								clone.attr("data-orderid", v2);
							} else if(k2 == "status") {
								clone.find("[data-info=user_"+k2+"]").html(statuses[v2]);
							} else if(k2 == "orderId") {
								
							} else {
								clone.find("[data-info=user_"+k2+"]").html(v2);
							}
						})
						_m.find('tbody').append(clone);
						_m.find('.edit-btn').find('a').attr("href", "/user-edit/"+r.userId+"");
					})
				})
		})
	} else if(func == "send.email") {
		$('.input-group').find('input').each(function () {
			let key = $(this).attr("name");
			let val = $(this).val();
			datas[key] = val;
		})
		datas['content'] = tinymce.get("editor").getContent();
		
		"send.email".backend({datas: datas})
			.done(function(r) {
				r = $.parseJSON(r);
				
			})
	} else if(func == "open.email-modal") {
		"email-details".modal(function(_m) {
			"get.emails".backend({emailid: t.closest("tr").attr("data-emailid"), userid: t.closest("tr").attr("data-userid")})
				.done(function(r) {
					r = $.parseJSON(r);
					
					$.each(r, function(k, v) {
						if(k == "open") {
							_m.find('.email-box').find(".email-container").find(".data-bx").find("[data-name=open]").html(v !== "true" ? "Nem látta" : "Látta");	
						} else if(k == "created") {
							_m.find('.email-bx-header').find("[data-name="+k+"]").html(v);
						} else {
							_m.find('.email-box').find(".email-container").find(".data-bx").find("[data-name="+k+"]").html(v);
						}
					})
				})
		})
	} else if(func == "upload.photo-to-slider") {
		$('[name="upload-photo-to-slider"]').trigger('click');
	} else if(func == "create.slide" || func == "save.slide") {
		$(".s-code").find("input:not([disabled]):not([type='checkbox'])").each(function() {
			datas[$(this).attr("name")] = $(this).val();
		});
		datas['content'] = tinymce.get("editor").getContent();
		let files2 = {};
		$.each(files, function(k,v) {
			files2[k] = v;
		});
		console.log(files2, datas)
		if(func == "create.slide") {
			"create.slide".backend({id: paths[2], datas: datas, removePhotos: JSON.stringify(removePhotos)}, files2)
				.done(function(r) {
					r = $.parseJSON(r);
					
					if(r.msg == "ok") {
						alert("slider létrehozva");
						window.location.href = '/sliders/';
					}
					removePhotos = {};
					// files = {};
				});
		} else {
			datas.sliderId = t.attr("data-sliderId");
			"save.slide".backend({id: paths[2], datas: datas,}, files2)
				.done(function(r) {
					r = $.parseJSON(r);
					
					if(r.msg == "ok") {
						alert("slider frissítve");
						window.location.href = '/sliders/';
					}
					removePhotos = {};
					// files = {};
				});
		}
	} else if(func == "delete.slide") {
		if(confirm("Biztos törölni szeretnéd az elemet?")) {
			"delete.slide".backend({deleteid: t.closest("tr").attr("data-sliderid")})
				.done(function(r) {
					r = $.parseJSON(r);
					t.closest('tr').remove();
				})
		}
	} else if(func == "sort.up" || func == "sort.down") {
		let clone = t.closest("tr").clone();
		if(func == "sort.up") {
			t.closest('tr').prev('tr').before(clone);
			t.closest('tr').remove();
		} else {
			t.closest('tr').next('tr').after(clone);
			t.closest('tr').remove();
		}
		$('[data-table="sliders"] tbody tr').each(function (index) {
			let sliderId = $(this).data('sliderid');
			let sortOrder = index + 1;
			datas[sliderId] = sortOrder;
		});
		"update.slider-sort".backend({datas: datas})
			.done(function(r) {
				r = $.parseJSON(r);
				
			})
	} else if(func == "open.hamburger-menu") {
		$('.hamburger').toggleClass('hidden');
	} else if(func == "open.modal-options") {
		$('.floating-options').toggleClass("hidden");
	} else if(func == "start.login") {
		"login.check".backend({username: $('[name="username"]').val(), password: $('[name="password"]').val()})
            .done(function(r) {
                r = $.parseJSON(r);
                if(r.msg == "ok") {
                    localStorage.setItem("token", r.token);
                    window.location.reload();
                }
            })
	}
    // Func vége
}
function isset(_var) {
	if(typeof _var !== 'undefined') return true;
	else return false;
}
function formatMoney(amount, thousands = " ", _tofixed = 0) {
	if(typeof amount == 'undefined' || amount == '' || amount == 0 || amount == '0') {
		return '0 Ft';
	} else {
		let minus = (parseFloat(amount).toFixed(_tofixed).replace('.', ',') < 0 ? true : false);
		var i = parseFloat(amount = Math.abs(Number(amount) || 0)).toFixed(_tofixed).toString().replace('.', ',');
		var j = (i.length > 3) ? i.length % 3 : 0;
		let _ret = (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands);
		_ret = _ret.replace(',00', '').replace(/(.*?)\,([0-9]{1})0/g, '$1,$2');
		if(_ret.indexOf('.,') != -1) {
			_ret = _ret.replace(/.,/, ',');
		}
		return (minus ? '-' : '')+_ret+' Ft';
	}
}
$.fn.hasAttr = function(name) { // $.fn. = $(".teszt")-et tudunk használni
	return this.attr(name) !== undefined;
};
function refresh_paths() { paths = window.location.pathname.split('/'); }
String.prototype.backend = function(data, files) {
	files = (typeof files === 'undefined' ? false : files);
	data = (typeof data === 'undefined' ? {} : data);
	data['do'] = this;
	data['page'] = paths[1];
	if(files !== false) {
		data['datas'] = JSON.stringify(data['datas']);
		let formData = new FormData();
		$.each(data, function(k, v) {
			formData.append(k, v);
		});
		if(typeof files === "array" || typeof files === "object") {
			$.each(files, function(fk,fv) {
				if(fv instanceof File) {
					formData.append("file["+fk+"]", fv);
				} else {
					$.each(fv, function(fk2,fv2) {
						formData.append("file["+fk+"]["+fk2+"]", fv2);
					});
				}
			});
		} else
			formData.append("file[0]", files);
		console.groupCollapsed( "-> Sent POST data to %c" + this +" T="+new Date().getMinutes()+":"+new Date().getSeconds(), "color:#82b;" );
		console.log( "\r\n Data sent: " , data );
		console.log( "\r\n Files sent: " , files );
		console.groupEnd();
		var a = $.ajax({
			type: "POST",
			url: "/app/backend/ajax.php?v=001",
			async: true,
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			success: function (r) {
				console.groupCollapsed( "%c" + data['do'] + "%c's <- Raw response T="+new Date().getMinutes()+":"+new Date().getSeconds() , "color:#82b;" , "color:#000;" );
				console.log(r);
				console.groupEnd();
				if (r.match(/warning:|error:/gi)){
					console.error("----PHP ERROR----"+$(r).text()+"\n----PHP ERROR----");
				}
				if(typeof $.parseJSON(r).msg != 'undefined' && $.parseJSON(r).msg == "access_denied") {
					alert("Hozzáférés megtagadva");
					return;
				}
			},
			error: function (error) {
				console.log(error);
			},
			timeout: 60000
		});
	} else {
		var a = $.ajax({
			type : "POST",
			async: true,
			url  : "/app/backend/ajax.php",
			data : data,
		});
		console.groupCollapsed( "-> Sent POST data to %c" + this +" T="+new Date().getMinutes()+":"+new Date().getSeconds(), "color:#82b;" );
		console.log( "\r\n Data sent: " , data );
		console.groupEnd();
		(function(t){
			a.done(function(r){
				console.groupCollapsed( "%c" + t + "%c's <- Raw response T="+new Date().getMinutes()+":"+new Date().getSeconds() , "color:#82b;" , "color:#000;" );
				console.log( r );
				console.groupEnd();
				if (r.match(/warning:|error:/gi)){
					console.error("----PHP ERROR----"+$(r).text()+"\n----PHP ERROR----");
				}
			});
		})(this);
	}
	return a;
};
function generateCouponCode(length) {
    let characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
		let randomIndex = Math.floor(Math.random() * characters.length);
		result += characters[randomIndex];
    }
    return result;
}

String.prototype.modal = function(callback) {
	var modal = this;
	$.get("/view/modals/"+modal+".html?v="+new Date().getTime(), {do: 'load'}, function(r) {
        $(".overlay").toggleClass("hidden");
		$("body").append(r);
		$("body").find(".modal").attr("data-modal", modal);
		$('html').addClass('overflow-h');
		callTemp();
		if(typeof callback !== 'undefined') {
			callback($(".modal[data-modal='"+modal+"']"));
		}
	});
};
function count(_var) {
	return Object.keys(_var).length;
}
function modalclose() {
	$('.modal').remove();
    $(".overlay").addClass("hidden");
    $('html').removeClass('overflow-h');
}
function editor() {
	tinymce.init({
		selector: '#editor',
		plugins: [
		  'autolink', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'wordcount',
		  'checklist', 'mediaembed', 'formatpainter', 'permanentpen', 'powerpaste', 'tableofcontents','typography','inlinecss',
		],
		toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image table | align lineheight | checklist numlist bullist indent outdent | removeformat',
		language: 'hu_HU',
		spellchecker_active: false
	});
}