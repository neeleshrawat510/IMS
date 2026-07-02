<?php
$conn = new mysqli("HOST","USER","PASS","DB");

$sql = file_get_contents("dump.sql");

if ($conn->multi_query($sql)) {
    echo "Imported successfully";
} else {
    echo "Error: " . $conn->error;
}
?>