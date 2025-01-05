<?php
?>
<h1>Rendelések</h1>
<div class="input-group">
    <label class="mg-right-20 mg-left-10">
        <input class="vm mg-right-5" type="checkbox" name="show.deleted-orders">
        <span class="vm">Törölt rendelések megjelenítése</span>
    </label>
    <input class="pd-side-15" type="text" data-searchable="orders" name="search" placeholder="Keresés...">
    <button class="pd-all-10"><i class="las la-search"></i></button>
</div>
<div class="orders-list table-list">
    <table class="order-table" data-table="order">
        <thead>
            <tr>
                <th>#</th>
                <th>Rendelő neve</th>
                <th>Rendelésszám</th>
                <th>Rendelés dátuma</th>
                <th>Fizetési mód</th>
                <th>Státusz</th>
                <th>Műveletek</th>
            </tr>
        </thead>
        <tbody>
            <tr data-temp="deleted_order">
                <td class="pd-bottom-10"></td>
                <td class="pd-bottom-10"></td>
                <td class="pd-bottom-10"></td>
                <td class="pd-bottom-10"></td>
                <td class="pd-bottom-10"></td>
                <td class="pd-bottom-10"></td>
                <td class="pd-bottom-10"><button class="pd-all-10" data-func="open.order-details-modal">Műveletek</button></td>
            </tr>
<?php
    $check = mysqli_query($con, 'SELECT * FROM orders ORDER BY orderId DESC');
    while ($f = mysqli_fetch_assoc($check)) {
        switch($f['paymentId']) {
            case 's': $paymentMethod = 'Stripe'; break;
            default: $paymentMethod = "Átutalás"; break;
        }
        switch($f['status']) {
            case 'wait-to-pay': $status = 'Fizetésre vár'; break;
            case 'paid': $status = 'Fizetve'; break;
            case 'processing': $status = 'Folyamatban'; break;
            case 'in-transit': $status = 'Szállítás alatt'; break;
            case 'done': $status = 'Kiszállítva'; break;
            case 'deleted': $status = 'törölve'; break;
        }
        echo '<tr data-orderid="'.$f['orderId'].'" data-status="'.$f['status'].'" class="'.($f['status'] == 'deleted' ? 'hidden' : '').'">
                <td class="pd-bottom-10">'.$f['orderId'].'</td>
                <td class="pd-bottom-10">'.json_decode($f['shippingDatas'], true)['last_name'].' '.json_decode($f['shippingDatas'], true)['first_name'].'</td>
                <td class="pd-bottom-10">'.$f['orderNumber'].'</td>
                <td class="pd-bottom-10">'.$f['created'].'</td>
                <td class="pd-bottom-10">'.$paymentMethod.'</td>
                <td class="pd-bottom-10">'.$status.'</td>
                <td class="pd-bottom-10"><button class="pd-all-10" data-func="open.order-details-modal">Műveletek</button></td>
            </tr>';
    }
?>
        </tbody>
    </table>
</div>