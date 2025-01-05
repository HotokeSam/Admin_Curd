<?php
// Itt majd próbálj meg SQL paranccsal összeszámoltatni, ne php-val (a statisztikákat)
$countOrders = mysqli_query($con, "SELECT COUNT(*) AS activeOrders FROM orders WHERE status!='done'");
while($f = mysqli_fetch_assoc($countOrders)) {
    $result['activeOrders'] = $f['activeOrders'];
}
$countOutcome = mysqli_query($con, "SELECT SUM(CAST(totalAmount AS DECIMAL(10, 2))) AS outcome,
    SUM(CAST(productsAmount AS DECIMAL(10, 2))) AS income FROM orders
    WHERE 
    MONTH(created) = MONTH(CURRENT_DATE()) 
    AND YEAR(created) = YEAR(CURRENT_DATE())");
while($f = mysqli_fetch_assoc($countOutcome)) {
    $result['outcome'] = $f['outcome'];
    $result['income'] = $f['income'];
}
$profit = abs($result['outcome'] - $result['income']);
$topProducts = mysqli_query($con, "SELECT name, productId, COUNT(productId) as c_productId FROM `orders_items` GROUP BY productId ORDER BY c_productId DESC LIMIT 5");
while($f = mysqli_fetch_assoc($topProducts)) {
    $result['topProducts'][] = [
        'name' => $f['name'],
        'productId' => $f['productId'],
        'count' => $f['c_productId']
    ];
}
?>
<div class="col-md-12 mg-bottom-10">
        <div class="col-md-4">
            <a href="/orders">
                <div class="activities-card pd-all-20">
                    <div class="activities-data mg-bottom-20">
                        <h4>Aktív rendelések</h4>
                        <span><?php echo $result['activeOrders']; ?></span>
                    </div>
                    <i class="las la-truck"></i>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="/emails">
                <div class="activities-card pd-all-20">
                    <div class="activities-data mg-bottom-20">
                        <h4>Reklamációk</h4>
                        <span>2222</span>
                    </div>
                    <i class="las la-envelope"></i>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <div class="activities-card pd-all-20">
                <div class="activities-data mg-bottom-20">
                    <h4>Visszáruk</h4>
                    <span>2222</span>
                </div>
                <i class="las la-truck-loading"></i>
            </div>
        </div>
</div>
<div class="col-md-12">
    <div class="mg-vertical-10 pd-all-20 overall-container row">
        <div class="title mg-bottom-20 col-md-12">
            <h2><i class="las mg-right-10 la-exchange-alt"></i>Havi Forgalom</h2>
            <button class="all pd-all-10">Megtekintés</button>
        </div>
        <div class="col-md-4">
            <div class="price-card first pd-all-20">
                <h3>Kiadás</h3>
                <i class="mg-vertical-10 las la-arrow-down"></i>
                <span class="mg-vertical-10 overall-outcome"><?php echo formatMoney($result['income']); ?></span>
                <h5 class="mg-top-10">Növekedés előző periódushoz képest: <span>222.22 %</span></h5>
            </div>
        </div>
        <div class="col-md-4">
            <div class="price-card pd-all-20">
                <h3>Bevétel</h3>
                <i class="mg-vertical-10 las la-arrow-up"></i>
                <span class="mg-vertical-10 overall-income"><?php echo formatMoney($result['outcome']); ?></span>
                <h5 class="mg-top-10">Növekedés előző periódushoz képest: <span>222.22 %</span></h5>
            </div>
        </div>
        <div class="col-md-4">
            <div class="price-card pd-all-20">
                <h3>Haszon</h3>
                <i class="mg-vertical-10 las la-money-bill"></i>
                <span class="mg-vertical-10 overall-profit"><?php echo formatMoney($profit); ?></span>
                <h5 class="mg-top-10">Növekedés előző periódushoz képest: <span>222.22 %</span></h5>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6 mg-top-15 mg-bottom-30">
    <div class="featured-item-container pd-all-20 weekly">
        <h3>Heti felkapott termékek</h3>
<?php 
    $Dnum = 1;
    foreach ($result['topProducts'] as $k) {
    echo '<div class="featured-card pd-all-15 mg-vertical-20" data-num="'.$Dnum.'" data-productId="'.$k['productId'].'" data-func="open.product-modal">
            <span>'.$k['name'].'</span>
        </div>';
        $Dnum++;
    }
?>
    </div>
</div>
<div class="col-md-6 mg-top-15 mg-bottom-30">
    <div class="featured-item-container pd-all-20 monthly">
        <h3>Havi felkapott termékek</h3>
        <?php 
    $Dnum = 1;
    foreach ($result['topProducts'] as $k) {
    echo '<div class="featured-card pd-all-15 mg-vertical-20" data-num="'.$Dnum.'" data-productId="'.$k['productId'].'" data-func="open.product-modal">
            <span>'.$k['name'].'</span>
        </div>';
        $Dnum++;
        if($Dnum > 5) {
            break;
        }
    }
?>
    </div>
</div>