<div class="col-md-12">
    <h1 class="mg-bottom-30">Új e-mail</h1>
    <div class="inputs row">
        <div class="input-group edit col-md-12">
            <label>
                <span>Címzett neve</span>
                <input type="text" class="pd-side-15 pd-vertical-10" name="name" />
            </label>
        </div>
        <div class="input-group edit col-md-4">
            <label>
                <span>Címzett e-mail címe</span>
                <input class="pd-side-15 pd-vertical-10 " type="email" name="email" />
            </label>
        </div>
        <div class="input-group edit col-md-4">
            <label>
                <span>Tárgy</span>
                <input class="pd-side-15 pd-vertical-10 " type="text" name="subject" />
            </label>
        </div>
        <div class="input-group edit col-md-12">
            <span class="desc-title mg-vertical-20">E-mail szövege</span>
            <textarea id="editor" class="pd-side-15 pd-vertical-10 mg-vertical-30" name="content"></textarea>
        </div>
    </div>
    <div class="options">
        <button class="float-r" data-func="send.email">Mentés</button>
        <button class="float-r mg-right-20"><a href="">Mégsem</a></button>
    </div>
</div>