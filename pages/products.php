<div class="col-md-12">
    <h1>Temékek</h1>
    <button class="float-r" data-func="create-product"><a href="/product-create">Termék létrehozása</a></button>
</div>
<div class="search pd-vertical-10 mg-top-50">
    <input class="pd-side-15 pd-vertical-10" type="text" data-searchable="products" name="search" placeholder="Keresés...">
    <select class="mg-left-20 pd-side-20" name="categories-select">
    <option value="all">Összes Termék</option>
<?php
    $getcateg = mysqli_query($con, "SELECT categoryId, name FROM categories");
    while($f = mysqli_fetch_assoc($getcateg)) {
        echo '<option value="'.$f['categoryId'].'">'.$f['name'].'</option>';
    }
?>
    </select>
    <div class="pagination mg-top-20 text-center">
<?php
    $allProducts = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(productId) as count_id FROM products"))['count_id'];
    $productPerPage = 100;
    $curr_page = (isset($_GET['page']) ? $_GET['page'] : 1);
    $pages = ceil($allProducts / $productPerPage);
    if($curr_page < 6) { $i_start = 1; $i_stop = $curr_page+5; }
    else if($curr_page > ($pages - 5)) { $i_start = $pages - 5; $i_stop = $pages; }
    else {
        $i_start = $curr_page - 5;
        $i_stop = $curr_page + 5;
    }
    if($curr_page > 6)
        echo '<a href="/products/?page=1"><button><<</button></a>';
    for($i = $i_start; $i <= $i_stop; $i++) { // For == for
        echo '<a href="/products/?page='.$i.'"><button'.($curr_page == $i ? ' class="active"' : '').'>'.$i.'</button></a>';
    }
    if($curr_page < ($pages - 5))
        echo '<a href="/products/?page='.$pages.'"><button>>></button></a>';
?>
    </div>
</div>
<div class="products table-list mg-top-20 row">
    <table class="product-table" data-table="products">
        <thead>
            <tr>
                <th>Termék neve</th>
                <th class="sku">SKU</th>
                <th class="price">Eladási ár</th>
                <th class="price">Beszerzési ár</th>
                <th class="supplier">Beszállító</th>
                <th>Műveletek</th>
            </tr>
        </thead>
        <tbody>
            <tr data-productid="" data-category="" data-temp="search-item">
                <td data-search="name" class="product-name"><div></div></td>
                <td data-search="sku"></td>
                <td data-search="sellPrice" class="prodPrice"></td>
                <td data-search="buyPrice" class="prodPrice"></td>
                <td data-search="supplier" class="manufaturer"><div></div></td>
                <td>
                    <button data-func="open.product-modal"><i class="las la-info"></i></button>
                    <button data-func="delete.product" class="bg-red" data-func="delete.product"><i class="las la-trash"></i></button>
                </td>
            </tr>
<?php
    $start = ($productPerPage * $curr_page) - $productPerPage;
    $getProduct = mysqli_query($con, "SELECT * FROM products ORDER BY name ASC LIMIT ".$start.",".$productPerPage);
    while($f = mysqli_fetch_assoc($getProduct)) {
        echo '<tr data-productid="'.$f['productId'].'" data-category="'.$f['categoryId'].'">
                <td class="product-name"><div>'.$f['name'].'</div></td>
                <td>'.$f['sku'].'</td>
                <td class="prodPrice">'.formatMoney($f['sellPrice']).'</td>
                <td class="prodPrice">'.formatMoney($f['buyPrice']).'</td>
                <td class="manufaturer"><div>'.$f['supplier'].'</div></td>
                <td>
                    <button data-func="open.product-modal"><i class="las la-info"></i></button>
                    <button data-func="delete.product" class="bg-red" data-func="delete.product"><i class="las la-trash"></i></button>
                </td>
            </tr>';
    }
?>
        </tbody>
    </table>
</div>