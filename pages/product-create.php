<div class="col-md-12">
    <h1 class="mg-bottom-30" data-info="name">Termék neve</h1>
    <div class="inputs row">
        <div class="input-group edit col-md-12">
            <label>
                <span>Termék neve</span>
                <input type="text" class="pd-side-15 pd-vertical-10" name="name" value="" />
            </label>
        </div>
        <div class="input-group edit col-md-4">
            <label>
                <span>Beszerzési ár</span>
                <input data-input="money" class="pd-side-15 pd-vertical-10 " type="text" name="buyPrice" value="" />
            </label>
        </div>
        <div class="input-group edit col-md-4">
            <label>
                <span>Eladási ár</span>
                <input data-input="money" class="pd-side-15 pd-vertical-10 " type="text" name="sellPrice" value="" />
            </label>
        </div>
        <div class="input-group edit col-md-4">
            <label>
                <span>Beszállító neve</span>
                <input class="pd-side-15 pd-vertical-10" type="text" name="supplier" value="" />
            </label>
        </div>
        <div class="input-group edit col-md-4">
            <label>
                <span>SKU</span>
                <input class="pd-side-15 pd-vertical-10" type="text" name="sku" value="" />
            </label>
        </div>
        <div class="input-group edit col-md-4">
            <label>
                <span>Kategória</span>
                <select class="pd-side-15 pd-vertical-10" name="categoryId" >
        <?php
            $categs_q = mysqli_query($con, "SELECT * FROM categories WHERE parent='0'");
            while($categs = mysqli_fetch_assoc($categs_q)) {
                echo '<option value="'.$categs['categoryId'].'">'.$categs['name'].'</option>';
                $subcategs_q = mysqli_query($con, "SELECT * FROM categories WHERE parent='".$categs['categoryId']."'");
                while($subcategs = mysqli_fetch_assoc($subcategs_q)) {
                    echo '<option '.($subcategs['categoryId'] == $f['categoryId'] ? "selected" : "").' value="'.$subcategs['categoryId'].'"> - '.$subcategs['name'].'</option>';
                }
            }
        ?>
                </select>
            </label>
        </div>
        <div class="desc edit col-md-12">
                <span class="desc-title mg-vertical-20">Termék leírása</span>
                <textarea id="editor" class="pd-side-15 pd-vertical-10 mg-vertical-20" name="description"></textarea>
        </div>
    </div>
    <div class="manage-pics row pd-all-20">
        <h2 class="mg-bottom-20">Feltöltött képek</h2>
        <input type="file" name="upload-photos-to-product" multiple accept="image/*" hidden value="" /> 
        <div data-func="upload.photos-to-product" class="pics upload col-md-3 mg-bottom-20">
            <div class="pics-content pd-all-20">
                <i class="las la-plus"></i>
            </div>
        </div>
        <div class="pics col-md-3 mg-bottom-20" data-temp="product-photo">
            <div class="pics-content pd-all-20">
                <img src="" alt="">
                <button data-func="set.main-pic">Legyen ez a fő kép!</button>
                <button class="bg-red float-r" data-func="remove.img">Törlés</button>
            </div>
        </div>
    </div>
    <div class="options">
        <button class="float-r" data-func="create.product">Mentés</button>
        <button class="float-r mg-right-20"><a href="/products">Mégsem</a></button>
    </div>
</div>