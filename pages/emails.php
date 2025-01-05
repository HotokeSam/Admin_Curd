<div class="col-md-12">
    <h2 class="mg-bottom-20">E-mailek</h2>
    <div class="table-list">
        <table data-table="emails">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Név</th>
                    <th>Email cím</th>
                    <th>Látta</th>
                    <th>Küldés dátuma</th>
                    <th class="emails-functions">Műveletek</th>
                </tr>
            </thead>
            <tbody>
    <?php
        $get = mysqli_query($con, "SELECT * FROM emails ORDER BY id DESC");
        while($f = mysqli_fetch_assoc($get)) {
            echo '<tr data-emailid="'.$f['id'].'" data-userid="'.$f['userId'].'">
                    <td>'.$f['id'].'</td>
                    <td>'.$f['name'].'</td>
                    <td>'.$f['address'].'</td>
                    <td>'.($f['open'] == true ? "Látta" : "Nem látta").'</td>
                    <td>'.$f['created'].'</td>
                    <td class="emails-btn">
                        <button data-func="open.email-modal"><i class="las la-info"></i></button>
                    </td>
                </tr>';
        }
    ?>
            </tbody>
        </table>
    </div>
</div>