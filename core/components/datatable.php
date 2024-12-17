<div class="row">
    <div class="input-field col s12">
        <i class="material-icons prefix">search</i>
        <input id="icon_prefix" type="text" class="validate">
        <label for="icon_prefix">Search</label>
    </div>
</div>
<table class="highlight">
    <thead>
        <tr>
            <?php
            foreach ($rows as $row) {
                echo '<th>' . $row . '</th>';
            }
            ?>
            <th>View</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>

    <tbody>
        <?php
        foreach ($data as $object) {
            echo '<tr>';
            foreach (array_keys($rows) as $row_key) {
                echo '<td>' . $object->{$row_key} . '</td>';
            }
            echo '<td><a href="/pages/admin/' . $object_name . 's/view_' . $object_name . '.php?id=' . $object->Id . '"><i class="material-icons left">remove_red_eye</i></a></td>';
            echo '<td><a href="/pages/admin/'. $object_name .'s/edit_' . $object_name . '.php?id=' . $object->Id . '"><i class="material-icons left">edit</i></a></td>';
            echo '<td><a href="/pages/admin/'. $object_name .'s/delete_' . $object_name . '.php?id=' . $object->Id . '"><i class="material-icons left">delete</i></a></td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>

<script>
    document.getElementById('icon_prefix').addEventListener('input', function() {
        var filter = this.value.toLowerCase();
        var rows = document.querySelectorAll('tbody tr');

        rows.forEach(function(row) {
            var rowCells = row.querySelectorAll('td');
            var rowCellsArray = Array.from(rowCells);

            if (rowCellsArray.map(function(cell) {
                    return cell.textContent.toLowerCase();
                }).join('').includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>