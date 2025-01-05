<div class="col-md-12">
    <h1>Felhasználók</h1>
</div>
<div class="search pd-vertical-10 mg-top-50">
    <input class="pd-side-15 pd-vertical-10 float-r" type="text" data-searchable="users" name="search" placeholder="Keresés...">
    <div class="pagination mg-vertical-20 text-center float-l">
<?php
    $allProducts = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(userId) as count_id FROM users"))['count_id'];
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
        echo '<a href="/users/?page=1"><button><<</button></a>';
    for($i = $i_start; $i <= $i_stop; $i++) {
        echo '<a href="/users/?page='.$i.'"><button'.($curr_page == $i ? ' class="active"' : '').'>'.$i.'</button></a>';
    }
    if($curr_page < ($pages - 5))
        echo '<a href="/users/?page='.$pages.'"><button>>></button></a>';
?>
    </div>
</div>
<div class="products table-list mg-top-20 row col-md-">
    <table class="user-table" data-table="users">
        <thead>
            <tr>
                <th>#</th>
                <th class="sku">Név</th>
                <th class="price">Email</th>
                <th class="price">Telefonszám</th>
                <th>Műveletek</th>
            </tr>
        </thead>
        <tbody>
<?php
    $start = ($productPerPage * $curr_page) - $productPerPage;
    $getusers = mysqli_query($con, "SELECT * FROM users ORDER BY firstname ASC LIMIT ".$start.",".$productPerPage);
    while($f = mysqli_fetch_assoc($getusers)) {
        echo '<tr data-userid="'.$f['userId'].'">
                <td>'.$f['userId'].'</td>
                <td class="user-name"><div>'.$f['firstName']." ".$f['lastName'].'</div></td>
                <td class="prodPrice">'.$f['email'].'</td>
                <td class="prodPrice">'.$f['phone'].'</td>
                <td>
                    <button data-func="open.details-user-modal"><i class="las la-info"></i></button>
                    <button data-func="delete.user" class="bg-red" data-func="delete.user"><i class="las la-trash"></i></button>
                </td>
            </tr>';
    }
?>
        </tbody>
    </table>
</div>