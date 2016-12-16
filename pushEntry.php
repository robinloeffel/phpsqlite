<?php
    $db = new SQLite3('database.sqlite');
    
    $query = 'insert into names values (null, "michaela", "äuäää", "glöibig", "chli hingedrii", "no hübsch", "glöibig")';
    $db->query($query);
    
    $query = 'select name from names order by id desc limit 1';
    $result = $db->query($query);
    
    echo $result->fetchArray(SQLITE3_ASSOC)['name'] . ' hinzugefügt';
    
    echo '<a href="./index.html">zrugg</a>';
?>