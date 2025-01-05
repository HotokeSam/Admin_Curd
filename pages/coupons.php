<div class="col-md-12">
<h2 class="float-l">Kuponok</h2>
<button data-func="open.create-coupon-modal" class="float-r mg-bottom-20"><i class="las la-plus"></i> Kupon létrehozása</button>
    <div class="table-list">

        <table data-table="cupons">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Felhasználó</th>
                    <th>Kuponkód</th>
                    <th>Érték</th>
                    <th>Típus</th>
                    <th>Kezd. dátum</th>
                    <th>Lejár. dátum</th>
                    <th>Felhasználható</th>
                    <th>Műveletek</th>
                </tr>
            </thead>
            <tbody>
    <?php 
        $getcupons = mysqli_query($con, "SELECT * FROM coupons ORDER BY couponId ASC");
        while($f = mysqli_fetch_assoc($getcupons)) {
            echo '
                 <tr data-couponId="'.$f['couponId'].'">
                    <td name="couponId">'.$f['couponId'].'</td>
                    <td name="userId">'.(($f['userId'] == -1) ? 'Mindenki' : $f['userId']).'</td>
                    <td name="code">'.$f['code'].'</td>
                    <td name="amount">'.(($f['type'] == 'M') ? formatMoney($f['amount']) : $f['amount']).'</td>
                    <td name="type">'.(($f['type'] == "%") ? "Százalék" : "Összeg").'</td>
                    <td name="startDate">'.(($f['startDate'] == -1) ? "Nincs dátum" : $f['startDate']).'</td>
                    <td name="stopDate">'.(($f['stopDate'] == -1) ? "Nincs dátum" : $f['stopDate']).'</td>
                    <td name="usability">'.(($f['usability'] == "1x") ? "Egyszer felhasznáható" : "Többször felhasználható").'</td>
                    <td>
                        <button data-func="change.coupon" ><i class="las la-edit"></i></button>
                        <button data-func="delete.coupon" ><i class="las la-trash"></i></button>
                    </td>
                </tr>
            ';
        };
    
    ?>
            </tbody>
        </table>
    </div>
</div>