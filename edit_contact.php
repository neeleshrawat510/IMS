<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Contacts</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        #editForm {
            width: 400px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-family: Arial, sans-serif;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #aaa;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        label.error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        input.error {
            border: 1px solid red;
        }
    </style>
</head>

<body class="bg-light">

    <!-- HEADER NAVBAR -->
    <?php include("includes/header.php");  ?>

    <!-- MAIN DASHBOARD -->
    <div class="container py-4">

        <form id="editForm" method="post">
            <input type="hidden" id="editContactId" name="id">
            <h3>Edit Contact</h3>
            <div class="form-group">
                <label for="fName">First Name</label>
                <input type="text" id="fName" name="fname" placeholder="">
            </div>

            <div class="form-group">
                <label for="lName">Last Name</label>
                <input type="text" id="lName" name="lname" placeholder="">
            </div>

            <div class="form-group">
                <label for="number">Contact Number</label>
                <input type="text" id="number" name="number" placeholder="">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" placeholder="">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea name="address" id="address" rows="4" cols="42"></textarea>
            </div>

            <button type="submit" id="submit">Update Contact</button>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery Validation Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- Sweet alert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- LOGOUT Redirect -->
    <script src="controller/logout.js"></script>


    <script>
        $(document).ready(function() {
            let editContactId = new URLSearchParams(window.location.search).get('id');
            //get form data
            $.ajax({
                url: "controller/fetch_contacts.php",
                type: "GET",
                dataType: "json",
                data: {
                    id: editContactId
                },
                success: function(data) {
                    $("#editContactId").val(data.id);
                    $("#fName").val(data.fname);
                    $("#lName").val(data.lname);
                    $("#number").val(data.number);
                    $("#email").val(data.email);
                    $("#address").val(data.address);
                }
            });
            $("#editForm").validate({
                rules: {
                    fname: {
                        required: true
                    },
                    lname: {
                        required: true
                    },
                    number: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10,
                        remote: {
                            url: "controller/check_number.php",
                            type: "POST",
                            data: {
                                id: function() {
                                    return $("#editContactId").val();
                                }
                            }
                        }
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    address: {
                        required: true,
                    }
                },
                messages: {
                    fname: {
                        required: "First Name is required"
                    },
                    lname: {
                        required: "Last Name is required"
                    },
                    number: {
                        required: "Number is required",
                        digits: "Only 0-9 required",
                        minlength: "10 digits required",
                        maxlength: "10 digits required",
                        remote: "This contact is already exist"
                    },
                    email: {
                        required: "Email is required",
                        email: "abc@gmail.com format required"
                    },
                    address: {
                        required: "Address is required"
                    }


                },
                submitHandler: function(form) {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, update it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let formData = new FormData(form);
                            $.ajax({
                                url: "php/edit_all_contact.php",
                                type: "POST",
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    if (response.trim() == 'success') {
                                        Swal.fire({
                                            title: "Successful",
                                            text: "Contact Updated",
                                            icon: "success"
                                        });

                                    }
                                    window.location.href = "manage_contact.php"
                                },
                                error: function() {
                                    Swal.fire({
                                        title: "Failed",
                                        text: "Contact not updated",
                                        icon: "error"
                                    });
                                }
                            });
                            
                        }

                    });

                }
            });
        });
    </script>
</body>

</html>