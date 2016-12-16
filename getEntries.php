<?php
    $db = new SQLite3('database.sqlite');
    $query = 'select * from names order by random() limit 2';
    $result = $db->query($query);
    
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        echo 'name = ' . $row['name'] . ' id = ' . $row['id'] . ' erste charakteristik = ' . $row['first'];
        echo '<br/>';
    }
    
    echo '<a href="./index.html">zrugg</a>';
?>