<div class="col-md-12">
    <h2 class="mg-bottom-20 float-l">Sliders</h2>
    <button class="float-r mg-bottom-20"><a href="/slider-create"><i class="las la-plus"></i> Slide Hozzáadása</a></button>
    <div class="table-list">
        <table data-table="sliders">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kép</th>
                    <th>Cím</th>
                    <th>Tartalom</th>
                    <th>Gomb</th>
                    <th>Link</th>
                    <th>Sorszám</th>
                    <th>Műveletek</th>
                </tr>
            </thead>
            <tbody> 
    <?php
        $maxSort = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(sliderId) AS counted FROM sliders"));
        $query = mysqli_query($con, "SELECT * FROM sliders ORDER BY sort ASC");
        while($f = mysqli_fetch_assoc($query)) { 
            $f['content'] = html_entity_decode($f['content']);
            echo '<tr data-sliderid="'.$f['sliderId'].'">
                    <td>'.$f['sliderId'].'</td>
                    <td><img src="/uploads/slider/'.$f['image'].'" alt="'.$f['image'].'"></td>
                    <td>'.$f['title'].'</td>
                    <td>'.$f['content'].'</td>
                    <td>'.$f['button'].'</td>
                    <td>'.$f['link'].'</td>
                    <td>
                        <button class="mg-right-5 srt_btn" data-func="sort.up"><i class="las la-angle-up"></i></button>
                        <button class="mg-left-5 srt_btn" data-func="sort.down"><i class="las la-angle-down"></i></button>
                    </td>
                    <td>
                        <button data-func="change.slide"><a href="/slider-edit/'.$f['sliderId'].'"><i class="las la-edit"></i></a></button>
                        <button data-func="delete.slide"><i class="las la-trash"></i></button>
                    </td>
                </tr>';
        }
    ?>
            </tbody>
        </table>
    </div>
</div>