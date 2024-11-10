<?php
$conn = pg_connect('host=127.0.0.1 port=5432 dbname=binary-tree-project user=postgres password=your_password_here');
if($conn) {
    echo 'Connected successfully';
    pg_close($conn);
} else {
    echo 'Connection failed';
}

