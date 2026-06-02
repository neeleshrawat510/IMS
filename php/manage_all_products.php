<?php
session_start();

//connection setup

include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

$sql = mysqli_query($conn, "SELECT * FROM  `products`");

if (mysqli_num_rows($sql) > 0) {
    while ($data = mysqli_fetch_array($sql)) {
?>
        <tr>
            <td><?= $data['product_code'] ?></td>
            <td><?= $data['product_name'] ?></td>
            <td><?= $data['cost_price'] ?></td>
            <td><?= $data['selling_price'] ?></td>
            <td><a href="edit_product.php?id=<?= $data['id']; ?>" class="btn btn-success">Edit</a>
                <a href="delete_product.php?id=<?= $data['id'] ?>" class="btn btn-danger">Remove</a>
            </td>
        </tr>

<?php
    }

} else {
    echo "<tr><td colspan='5'>No Data Found</td></tr>";
}
?>