<?php
session_start();

//connection setup
include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

$sql = mysqli_query($conn, "SELECT * FROM `contacts`");

if (mysqli_num_rows($sql) > 0) {
    while ($row = mysqli_fetch_array($sql)) {
?>
        <tr>
            <td><?= $row['fname']; ?></td>
            <td><?= $row['lname']; ?></td>
            <td><?= $row['number']; ?></td>
            <td><?= $row['email']; ?></td>
            <td><?= $row['address']; ?></td>
            <td>
                <a class="btn btn-success" href="edit_contact.php?id=<?= $row['id']; ?>">Edit</a>
                <a class="btn btn-danger" href="delete_contact.php?id=<?= $row['id']; ?>">Remove</a>
            </td>
        </tr>
<?php

    }
}else{
    echo "<tr><td cols='5'>Data Not Found</td></tr>";
}
?>