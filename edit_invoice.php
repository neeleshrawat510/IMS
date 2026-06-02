<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("location: index.php");
    exit();
}

include("config/connection.php");
date_default_timezone_set('Asia/Kolkata');

//invoice no and date to fetch 

$invoice_no = "INV-10001";

$getInvoice = mysqli_query($conn, "SELECT invoice_no FROM invoices ORDER BY id DESC LIMIT 1");

if(mysqli_num_rows($getInvoice) > 0){

    $row = mysqli_fetch_assoc($getInvoice);

    // Remove INV-
    $last_number = str_replace("INV-", "", $row['invoice_no']);

    // Increment number
    $new_number = (int)$last_number + 1;

    $invoice_no = "INV-" . $new_number;
}


$invoice_date = date("Y-m-d");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>INVOICE</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        .search-dropdown {
            position: absolute;
            width: 100%;
            background: #fff;
            border: 1px solid #ddd;
            z-index: 999;
            max-height: 200px;
            overflow-y: auto;
            display: none;
        }

        .contact-item,
        .product-item {
            cursor: pointer;
        }

        .contact-item:hover,
        .product-item:hover {
            background: #f1f1f1;
        }


        /* print page */
        @media print {
            @page {
                margin: 0;
                size: auto;
            }

            body {
                margin: 1.5cm;
            }

            body>*:not(#printInvoice) {
                display: none !important;
            }

            #printInvoice {
                display: block !important;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                padding: 30px;
            }
        }
    </style>
</head>


<body class="bg-light">

    <!-- HEADER NAVBAR -->
    <?php include("includes/header.php");  ?>

    <!-- MAIN DASHBOARD -->
    <div class="container py-4">

        <div class="container py-4">
            <div class="invoice-wrapper">
                <form method="POST" id="invoiceForm">
                    <h4>Invoice#</h4>
                    <!-- Top Row -->
                    <div class="row align-items-end mb-4">
                        <!-- Contact -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Contact
                            </label>
                            <div class="position-relative">
                                <input type="text" id=contactSearch class="form-control" placeholder="Search Contact">
                                <input type="hidden" name="contact_id" id="contactId">
                                <div id="contactDropdown" class="search-dropdown">
                                </div>
                            </div>
                        </div>
                        <!-- Invoice Number -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">
                                Invoice No.
                            </label>
                            <input type="text" class="form-control" name="invoice_no" id="invoiceNo" readonly>
                        </div>
                        <!-- Date -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                Invoice Date
                            </label>
                            <input type="text" class="form-control" name="invoice_date" id="invoiceDate" readonly>
                        </div>
                        <!-- Due Date -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                Due Date
                            </label>
                            <input type="date" class="form-control" name="due_date">
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="">
                        <table class="table invoice-table align-middle">
                            <thead>
                                <tr>
                                    <th width="22%">Product</th>
                                    <th width="28%">Description</th>
                                    <th width="10%">Qty</th>
                                    <th width="12%">Price</th>
                                    <th width="10%">Tax%</th>
                                    <th width="13%">Amount</th>
                                    <th width="5%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="invoiceItems">
                                <!-- Default Row -->
                                <tr>
                                    <!-- Product -->
                                    <td>
                                        <div class="position-relative">
                                            <input type="text" class="form-control productSearch" placeholder="Search Product">
                                            <input type="hidden" class="productId" name="product_id[]">
                                            <div class="product-dropdown search-dropdown productDropdown" id="">
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Description -->
                                    <td>
                                        <textarea class="form-control description" name="description[]" rows="1" readonly></textarea>
                                    </td>
                                    <!-- Qty -->
                                    <td>
                                        <input type="number" class="form-control qty" name="qty[]" value="1">
                                    </td>
                                    <!-- Price -->
                                    <td>
                                        <input type="number" class="form-control price" name="price[]" readonly>
                                    </td>
                                    <!-- Tax -->
                                    <td>
                                        <select class="form-control tax" name="tax[]">
                                            <option value="" disabled selected>Tax %</option>
                                            <option value="0">0%</option>
                                            <option value="5">5%</option>
                                            <option value="12">12%</option>
                                            <option value="18">18%</option>
                                        </select>
                                    </td>
                                    <!-- Amount -->
                                    <td>
                                        <input type="text" class="form-control amount" name="amount[]" readonly>
                                    </td>
                                    <!-- Remove -->
                                    <td>
                                        <button type="button" id="" class="btn btn-danger remove-row"> × </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" id="addRow" class="btn btn-success add-row"> + </button>
                    </div>


                    <!-- Totals -->
                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <div class="card total-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-3">
                                        <strong>Subtotal</strong>
                                        <input type="text" name="subtotal" id="subTotal" class="form-control total-input" readonly>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <strong>Tax Total</strong>
                                        <input type="text" name="tax_total" id="tax_total" class="form-control total-input" readonly>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <strong>Grand Total</strong>
                                        <input type="text" name="grand_total" id="grand_total" class="form-control total-input total-grand" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Save -->
                    <div class="text-end mt-4">
                        <button type="submit"
                            class="btn btn-success px-5" id="saveBtn">
                            Save Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
            //FETCH ALL DATA FROM DB
        
        let invoiceId = new URLSearchParams(window.location.search).get('id');
            $.ajax({
                    url: "controller/fetch_invoice.php",
                    type: "GET",
                    dataType: "json",
                    data: {
                        id: invoiceId
                    },
                    success: function(data){
                        $("#contactId").val(data.contact_id);
                        $("#invoiceNo").val(data.invoice_no);
                        $("#invoiceDate").val(data.invoice_date);
                        $("#productId").val(data.product_id);
                        $("#description").val(data.description);
                        $("#qty").val(data.qty);
                        $("#price").val(data.price);

                    }

            });

            //FOR DYNAMIC CONTACTS
            $("#contactSearch").keyup(function() {
                let keyword = $(this).val();
                if (keyword.length < 1) {
                    $("#contactDropdown").hide();
                    return;
                }
                $.ajax({
                    url: "controller/all_contacts.php",
                    type: "GET",

                    data: {
                        keyword: keyword
                    },
                    success: function(response) {
                        let data = JSON.parse(response);
                        let html = '';
                        //add contact dropdown
                        html += `<div class="contact-item p-2 border-bottom text-primary fw-bold add-contact"> + Add contact </div>`;

                        //all contacts dynamically 
                        if (data.length > 0) {
                            $.each(data, function(index, row) {

                                html += `<div class="contact-item p-2 border-bottom"
                                data-id ="${row.id}" data-name = "${row.fname} ${row.lname}">
                                ${row.fname} ${row.lname}
                                <br>
                                <small>${row.number} | ${row.email}</small>
                                </div>`;

                            });
                        } else {
                            html += `<div class="p-2 text-danger">
                                    Contact Not Found
                                    </div>`;
                        }
                        $("#contactDropdown").html(html).show();
                    }

                });
            });

            //select contact
            $(document).on("click", ".contact-item", function() {
                if ($(this).hasClass("add-contact")) {
                    window.location.href = "add_contact.php";
                    return;
                }

                let id = $(this).data("id");
                let name = $(this).data("name");

                $("#contactId").val(id);
                $("#contactSearch").val(name);

                $("#contactDropdown").hide();
            });

            //FOR DYNAMIC PRODUCTS
            $(document).on("keyup", ".productSearch", function() {
                let keyword = $(this).val();
                let row = $(this).closest("tr");
                let dropdown = row.find(".productDropdown");

                if (keyword.length < 1) {
                    dropdown.hide();
                    return;
                }
                $.ajax({
                    url: "controller/all_products.php",
                    type: "GET",
                    data: {
                        keyword: keyword
                    },
                    success: function(response) {
                        let data = JSON.parse(response);
                        let html = '';
                        if (data.length > 0) {
                            $.each(data, function(index, row) {
                                html += `<div class="product-item p-2 border-bottom"
                                            data-id = "${row.id}" 
                                            data-code = "${row.product_code}" 
                                            data-name = "${row.product_name}" 
                                            data-sell = "${row.selling_price}"
                                            >
                                           <small> ${row.product_code} | ${row.product_name}</small>
                                            <br>
                                           <small> ${row.selling_price}</small>
                                        </div>`;
                            });
                        } else {
                            html += `<div class="p-2 texr-danger">
                                    No Product Found
                                    </div>`;
                        }
                        dropdown.html(html).show();
                    }

                });
            });

            //select product
            $(document).on("click", ".product-item", function() {
                let id = $(this).data("id");
                let code = $(this).data("code");
                let name = $(this).data("name");
                let sell = $(this).data("sell");

                let row = $(this).closest("tr");

                row.find(".productId").val(id);
                row.find(".productSearch").val(code);
                row.find(".description").val(name);
                row.find(".price").val(sell);

                row.find(".productDropdown").hide();

                calculateRow(row);
                calculateTotals();

            });

            //add new row
            $("#addRow").click(function() {
                let newRow = ` <tr>
                                    <!-- Product -->
                                    <td>
                                        <div class="position-relative">
                                            <input type="text" class="form-control productSearch" placeholder="Search Product">
                                            <input type="hidden" class="productId" name="product_id[]">
                                            <div class="product-dropdown search-dropdown productDropdown" id="">
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Description -->
                                    <td>
                                        <textarea class="form-control description" name="description[]" rows="1" readonly></textarea>
                                    </td>
                                    <!-- Qty -->
                                    <td>
                                        <input type="number" class="form-control qty" name="qty[]" value="1">
                                    </td>
                                    <!-- Price -->
                                    <td>
                                        <input type="number" class="form-control price" name="price[]" readonly>
                                    </td>
                                    <!-- Tax -->
                                    <td>
                                        <select class="form-control tax" name="tax[]">
                                            <option value="" disabled selected>Tax %</option>
                                            <option value="0">0%</option>
                                            <option value="5">5%</option>
                                            <option value="12">12%</option>
                                            <option value="18">18%</option>
                                        </select>
                                    </td>
                                    <!-- Amount -->
                                    <td>
                                        <input type="text" class="form-control amount" name="amount[]" readonly>
                                    </td>
                                    <!-- Remove -->
                                    <td>
                                        <button type="button" id="" class="btn btn-danger remove-row"> × </button>
                                    </td>
                                </tr>`;
                $("#invoiceItems").append(newRow);

            });

            //remove row
            $(document).on("click", ".remove-row", function() {

                if ($("#invoiceItems tr").length > 1) {

                    $(this).closest("tr").remove();

                    calculateTotals();
                }
            });

            //CALCULATION
            function calculateRow(row) {
                let qty = parseFloat(row.find(".qty").val()) || 0;
                let price = parseFloat(row.find(".price").val()) || 0;
                let tax = parseFloat(row.find(".tax").val()) || 0;
                let total = qty * price;
                let taxAmount = (total * tax) / 100;
                let finalAmount = total;

                row.find(".amount").val(finalAmount.toFixed(2));
            }

            //CALCULATE TOTALS
            function calculateTotals() {
                let subTotal = 0;
                let taxTotal = 0;
                let grandTotal = 0;

                $("#invoiceItems tr").each(function() {

                    let qty = parseFloat($(this).find(".qty").val()) || 0;
                    let price = parseFloat($(this).find(".price").val()) || 0;
                    let tax = parseFloat($(this).find(".tax").val()) || 0;
                    let total = qty * price;
                    let taxAmount = (total * tax) / 100;

                    subTotal += total;
                    taxTotal += taxAmount;
                    grandTotal += total + taxAmount;
                });

                $("#subTotal").val(subTotal.toFixed(2));
                $("#tax_total").val(taxTotal.toFixed(2));
                $("#grand_total").val(grandTotal.toFixed(2));

                console.log(grandTotal);
            }

            // Total
            $(document).on("input", ".qty, .price, .tax", function() {

                let row = $(this).closest("tr");

                calculateRow(row);

                calculateTotals();
            });

            //funciton to fetch values for print
            function generatePrintInvoice() {
                $("#printCustomer").text($("#contactSearch").val());
                $(".printInvoiceNo").text($("input[name='invoice_no']").val());
                $("#printDate").text($("input[name='invoice_date']").val());

                let itemsHtml = '';

                $("#invoiceItems tr").each(function() {
                    let product = $(this).find('.productSearch').val();
                    let description = $(this).find('.description').val();
                    let qty = $(this).find('.qty').val();
                    let price = $(this).find('.price').val();
                    let tax = $(this).find('.tax').val();
                    let amount = $(this).find('.amount').val();

                    itemsHtml += `
                                <tr>
                                    <td>${product}</td>
                                    <td>${description}</td>
                                    <td>${qty}</td>
                                    <td>${price}</td>
                                    <td>${tax}</td>
                                    <td>${amount}</td>
                                   
                                </tr>
                            `;
                });
                $("#printItems").html(itemsHtml);
                $("#printSubtotal").text($("#subTotal").val());
                $("#printTax").text($("#tax_total").val());
                $("#printGrand").text($("#grand_total").val());

            }

            //Form Submission
            $("#invoiceForm").submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "php/save_invoice.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.trim() == 'success') {

                            generatePrintInvoice();

                            Swal.fire({
                                title: "Successful",
                                text: "Invoice saved",
                                icon: "success",
                                confirmButtonText: "Print Invoice",
                                showCancelButton: "Cancel"

                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $("#printInvoice").show();

                                    setTimeout(function() {
                                        window.print();
                                        $("#printInvoice").hide();
                                        $("#invoiceForm")[0].reset();
                                          location.reload();
                                    }, 300);
                                }else{
                                    $("#invoiceForm")[0].reset();
                                      location.reload();
                                }

                            });

                        }

                    },
                    error: function() {
                        Swal.fire({
                            title: "error",
                            text: "An error occured",
                            icon: "error"
                        });
                    }

                });
            });

        });
    </script>


    <!-- PRINT INVOICE FORMAT -->
    <div id="printInvoice" style="display:none; padding:30px; font-family:Arial;">

        <div style="text-align:center; margin-bottom:20px;">
            <h2>INVOICE</h2>
            <p><strong class="printInvoiceNo">#</strong></p>
        </div>

        <table width="100%" style="margin-bottom:20px;">
            <tr>
                <td>
                    <strong>Customer:</strong>
                    <div id="printCustomer"></div>
                </td>

                <td align="right">
                    <strong>Invoice No:</strong>
                    <div class="printInvoiceNo"></div>

                    <strong>Date:</strong>
                    <div id="printDate"></div>
                </td>
            </tr>
        </table>

        <table width="100%" border="1" cellspacing="0" cellpadding="8">
            <thead style="background:#f2f2f2;">
                <tr>
                    <th>Product</th>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Tax</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody id="printItems"></tbody>
        </table>

        <br>

        <table width="300" align="right" border="1" cellspacing="0" cellpadding="8">
            <tr>
                <th>Subtotal</th>
                <td id="printSubtotal"></td>
            </tr>

            <tr>
                <th>Tax Total</th>
                <td id="printTax"></td>
            </tr>

            <tr>
                <th>Grand Total</th>
                <td id="printGrand"></td>
            </tr>
        </table>

    </div>

</body>

</html>