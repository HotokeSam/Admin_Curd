<?php
if(isset($paths[2])) {
    $check = mysqli_query($con, "SELECT * FROM sliders WHERE sliderId='".$paths[2]."'");
    $f = mysqli_fetch_assoc($check);
}
?>
<div class="col-md-12">
	<h1>Slide Hozzáadása</h1>
    <div class="container pd-all-20 row">
        <div class="s-code col-md-4">
            <label class="s-group mg-bottom-20">
                <span class="mg-bottom-10">Cím</span>
                <input class="pd-left-20" type="text" placeholder="Cím" name="title" value="<?php echo $f['title'] ?>">
            </label>
        </div>
        <div class="s-code col-md-4">
            <label class="s-group mg-bottom-20">
                <span class="mg-bottom-10">Gomb felirat</span>
                <input class="pd-left-20" type="text" placeholder="Gomb felirat" name="button" value="<?php echo $f['button'] ?>">
            </label>
            <label class="check_bx mg-top-10">
                <input type="checkbox" name="need_btn">
                <span>Nem kell gomb</span>
            </label>
        </div>
        <div class="s-code col-md-4">
            <label class="s-group mg-bottom-20">
                <span class="mg-bottom-10">Gombhoz tartozó URL cím</span>
                <input class="pd-left-20" type="text" placeholder="link" name="link" value="<?php echo $f['link'] ?>">
            </label>
        </div>
        <div class="s-code col-md-12">
            <label class="s-group mg-bottom-20">
                <span class="mg-bottom-10">Tartalom</span>
                <textarea id="editor"><?php echo $f['content'] ?></textarea>
            </label>
        </div>
        <div class="manage-pics sliders row pd-all-20 col-md-12">
            <h2 class="mg-bottom-20">Kép feltöltése (Előző kép automatikusan törlésre kerül!)</h2>
            <input type="file" name="upload-photo-to-slider" accept="image/*" hidden value="" /> 
            <div data-func="upload.photo-to-slider" class="pics upload col-md-3 mg-bottom-20">
                <div class="pics-content pd-all-20">
                    <i class="las la-plus"></i>
                </div>
            </div>
            <div class="pics col-md-3 mg-bottom-20" data-pics="new_pics">
                <div class="pics-content pd-all-20">
                    <img src="/uploads/fun-trex-3d-illustration.jpg" alt="">
                    <button class="bg-red float-r" data-func="remove.img">Törlés</button>
                </div>
            </div>
<?php
    $img = json_decode($f['image'], true);
    foreach($img as $k => $v) {
        echo 
        '<div class="pics col-md-3 mg-bottom-20">
            <div class="pics-content pd-all-20">
                <img src="/uploads/products/'.$v.'" alt="">
                <button data-img="'.$k.'" data-func="delete.img" class="bg-red float-r">Törlés</button>
            </div>
        </div>';
    }
?>
        </div>
    </div>
    <div class="modal-footer mg-vertical-20 pd-side-20">
		<div class="options row mg-vertical-20">
			<div class="float-r">
				<button data-func="close-modal" class="options-btn mg-side-20 pd-side-20"><a href="/sliders">mégse</a></button>
				<button data-func="save.slide" data-sliderid="<?php echo $f['sliderId'] ?>" id="update-slide" class="options-btn mg-side-20 pd-side-20">Slide létrehozása</button>
			</div>
		</div>
    </div>
</div>