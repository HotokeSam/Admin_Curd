<div class="col-md-12">
	<h1>Slide Hozzáadása</h1>
    <div class="container pd-all-20 row">
        <div class="s-code col-md-4">
            <label class="s-group mg-bottom-20">
                <span class="mg-bottom-10">Cím</span>
                <input class="pd-left-20" type="text" placeholder="Cím" name="title">
            </label>
        </div>
        <div class="s-code col-md-4">
            <label class="s-group mg-bottom-20">
                <span class="mg-bottom-10">Gomb felirat</span>
                <input class="pd-left-20" type="text" placeholder="Gomb felirat" name="button">
            </label>
            <label class="check_bx mg-top-10">
                <input type="checkbox" name="need_btn">
                <span>Nem kell gomb</span>
            </label>
        </div>
        <div class="s-code col-md-4">
            <label class="s-group mg-bottom-20">
                <span class="mg-bottom-10">Gombhoz tartozó URL cím</span>
                <input class="pd-left-20" type="text" placeholder="url" name="url">
            </label>
        </div>
        <div class="s-code col-md-12">
            <label class="s-group mg-bottom-20">
                <span class="mg-bottom-10">Tartalom</span>
                <textarea id="editor"></textarea>
            </label>
        </div>
        <div class="manage-pics sliders row pd-all-20 col-md-12">
            <h2 class="mg-bottom-20">Kép feltöltése</h2>
            <input type="file" name="upload-photo-to-slider" accept="image/*" hidden value="" /> 
            <div data-func="upload.photo-to-slider" class="pics upload col-md-3 mg-bottom-20">
                <div class="pics-content pd-all-20">
                    <i class="las la-plus"></i>
                </div>
            </div>
            <div class="pics col-md-3 mg-bottom-20" data-pics="new_pics">
                <div class="pics-content pd-all-20">
                    <img src="" alt="">
                    <button class="bg-red float-r" data-func="remove.img">Törlés</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer mg-vertical-20 pd-side-20">
		<div class="options row mg-vertical-20">
			<div class="float-r">
				<button data-func="close-modal" class="options-btn mg-side-20 pd-side-20"><a href="/sliders">mégse</a></button>
				<button data-func="create.slide" id="create-slide" class="options-btn mg-side-20 pd-side-20">Slide létrehozása</button>
			</div>
		</div>
    </div>
</div>