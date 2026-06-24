<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("location: index.php");
    exit();
}

include("config/connection.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Invoice management System">
    <title>Edit Invoice | Invoice Management System</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        body {
            background: #f4f5f7;
            font-family: 'Segoe UI', sans-serif;
            font-size: 14px;
        }

        .invoice-wrapper {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 1.75rem;
            max-width: 1100px;
            margin: 2rem auto;
        }

        .invoice-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 1rem;
            margin-bottom: 1.25rem;
            border-bottom: 1px solid #eee;
        }

        .invoice-header h5 {
            font-weight: 600;
            font-size: 17px;
            margin: 0;
            color: #1a1a2e;
        }

        .online-pay-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border: 1px solid #d0d5dd;
            border-radius: 6px;
            background: #fff;
            font-size: 12px;
            color: #555;
            cursor: pointer;
            text-decoration: none;
        }

        .online-pay-btn:hover {
            background: #f9f9f9;
        }

        .pay-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 19px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: 700;
            color: #fff;
        }

        .visa-badge {
            background: #1434CB;
        }

        .mc-badge {
            background: #EB001B;
        }

        /* Top Fields Row */
        .fields-grid {
            display: grid;
            grid-template-columns: 2.2fr 1.1fr 1.1fr 1.1fr 1.1fr;
            gap: 14px;
            margin-bottom: 1rem;
        }

        .field-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .field-group label {
            font-size: 11px;
            font-weight: 600;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .field-group .,
        .field-group .form-select {
            height: 36px;
            font-size: 13px;
            border: 1px solid #dde1e7;
            border-radius: 6px;
            padding: 0 10px;
            color: #1a1a2e;
            background: #fff;
        }

        .field-group .:focus,
        .field-group .form-select:focus {
            border-color: #4a90d9;
            box-shadow: 0 0 0 3px rgba(74, 144, 217, 0.12);
        }

        .contact-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            height: 36px;
            padding: 0 10px;
            border: 1px solid #dde1e7;
            border-radius: 6px;
            background: #fff;
        }

        .chip-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .chip-name {
            font-size: 13px;
            font-weight: 500;
            color: #1a1a2e;
            flex: 1;
        }

        .chip-remove {
            color: #aaa;
            cursor: pointer;
            font-size: 17px;
            line-height: 1;
            padding: 0 2px;
        }

        .chip-remove:hover {
            color: #e53e3e;
        }

        .contact-sub {
            font-size: 11px;
            color: #999;
            margin-top: 3px;
        }

        /* Currency Row */
        .tax-inclusive {
            display: flex;
            gap: 14px;
            margin-bottom: 1.5rem;
        }

        .tax-inclusive .field-group {
            width: 210px;
        }

        /* Table */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 0;
        }

        .invoice-table thead tr {
            border-bottom: 1px solid #eee;
        }

        .invoice-table thead th {
            font-size: 11px;
            font-weight: 600;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 6px 8px;
        }

        .invoice-table thead th.text-end {
            text-align: right;
        }

        .invoice-table tbody tr {
            border-bottom: 1px solid #f0f0f0;
        }

        .invoice-table tbody tr:hover {
            background: #fafafa;
        }

        .invoice-table tbody td {
            padding: 9px 8px;
            vertical-align: middle;
        }

        .drag-handle {
            color: #ccc;
            cursor: grab;
            font-size: 15px;
        }

        .item-badge {
            font-size: 10px;
            padding: 2px 6px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            color: #888;
            background: #f8f8f8;
            white-space: nowrap;
            margin-right: 5px;
        }

        .item-name {
            font-size: 13px;
            color: #1a1a2e;
            font-weight: 500;
        }

        .desc-text {
            font-size: 12px;
            color: #777;
        }

        .num-cell {
            font-size: 13px;
            color: #1a1a2e;
            text-align: right;
        }

        .tax-cell {
            font-size: 12px;
            color: #777;
            text-align: right;
        }

        /* Inline editable inputs inside table */
        .table-input {
            height: 32px;
            padding: 0 8px;
            border: 1px solid #dde1e7;
            border-radius: 5px;
            font-size: 13px;
            color: #1a1a2e;
            background: #fff;
            width: 100%;
        }

        .table-input:focus {
            outline: none;
            border-color: #4a90d9;
            box-shadow: 0 0 0 3px rgba(74, 144, 217, 0.1);
        }

        .table-select {
            height: 32px;
            padding: 0 6px;
            border: 1px solid #dde1e7;
            border-radius: 5px;
            font-size: 12px;
            color: #555;
            background: #fff;
            width: 100%;
        }

        .table-select:focus {
            outline: none;
            border-color: #4a90d9;
        }

        .search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #dde1e7;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 100;
            display: none;
            max-height: 180px;
            overflow-y: auto;
        }

        .dropdown-item-custom {
            padding: 8px 12px;
            font-size: 13px;
            cursor: pointer;
            color: #333;
        }

        .dropdown-item-custom:hover {
            background: #f0f4ff;
        }

        .del-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #ccc;
            padding: 4px 6px;
            border-radius: 5px;
            font-size: 16px;
            line-height: 1;
            transition: all 0.15s;
        }

        .del-btn:hover {
            background: #fff0f0;
            color: #e53e3e;
        }

        /* Add line buttons */
        .add-line-row {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        .add-line-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 5px 14px;
            border: 1px dashed #bbb;
            border-radius: 6px;
            background: none;
            font-size: 12px;
            color: #777;
            cursor: pointer;
            transition: all 0.15s;
        }

        .add-line-btn:hover {
            background: #f0f4ff;
            color: #1a4ed8;
            border-color: #4a90d9;
        }

        /* Totals */
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        .totals-box {
            min-width: 300px;
        }

        .totals-box table {
            width: 100%;
        }

        .totals-box td {
            padding: 5px 0;
            font-size: 13px;
        }

        .totals-box td:last-child {
            text-align: right;
            font-weight: 500;
            color: #1a1a2e;
        }

        .totals-box .muted-row td {
            color: #aaa;
            font-size: 12px;
        }

        .grand-row {
            border-top: 1px solid #e0e0e0;
        }

        .grand-row td {
            padding-top: 12px !important;
            font-size: 16px !important;
            font-weight: 700 !important;
            color: #1a1a2e !important;
        }

        .currency-label {
            font-size: 12px;
            font-weight: 400;
            color: #888;
            margin-right: 5px;
        }

        /* Actions */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            padding-top: 1rem;
            margin-top: 1rem;
            border-top: 1px solid #eee;
        }

        .btn-cancel {
            height: 36px;
            padding: 0 18px;
            border: 1px solid #d0d5dd;
            border-radius: 6px;
            background: #fff;
            font-size: 13px;
            color: #555;
            cursor: pointer;
        }

        .btn-cancel:hover {
            background: #f5f5f5;
        }

        .btn-more {
            height: 36px;
            width: 36px;
            border: 1px solid #d0d5dd;
            border-radius: 6px;
            background: #fff;
            font-size: 18px;
            color: #555;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-more:hover {
            background: #f5f5f5;
        }

        .btn-save {
            height: 36px;
            padding: 0 22px;
            border: none;
            border-radius: 6px;
            background: #1a5abe;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-save:hover {
            background: #1348a0;
        }

        label.error {
            color: red;

        }

        /* Responsive */
        @media (max-width: 768px) {
            .fields-grid {
                grid-template-columns: 1fr 1fr;
            }

            .tax-inclusive {
                flex-wrap: wrap;
            }

            .invoice-table {
                display: block;
                overflow-x: auto;
            }
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #printInvoice,
            #printInvoice * {
                visibility: visible;
            }

            #printInvoice {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="admin-shell">
        <div class="sidebar-backdrop" data-sidebar-close></div>

        <!-- INCLUDE SIDEBAR -->
        <?php include("includes/sidebar.php"); ?>

        <div class="admin-main">

            <!-- INCLUDE HEADER -->
            <?php include("includes/header.php"); ?>

            <!-- MAIN CONTENT -->

            <form method="POST" id="invoiceForm">
                <div class="invoice-wrapper">

                    <!-- Header -->
                    <div class="invoice-header">
                        <h5>Edit Invoice</h5>
                        <button type="button" class="online-pay-btn">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="1" y="4" width="22" height="16" rx="2" />
                                <line x1="1" y1="10" x2="23" y2="10" />
                            </svg>
                            Set up online payments
                            <span class="pay-badge visa-badge">VISA</span>
                            <span class="pay-badge mc-badge">MC</span>
                        </button>
                    </div>

                    <!-- Top Fields -->
                    <div class="fields-grid">
                        <input type="hidden" name="invoice_id" id="invoice_id">
                        <!-- Contact -->
                        <div class="field-group">
                            <label>To</label>
                            <div class="position-relative">
                                <input type="text" id="contactSearch" class="" placeholder="Search contact…">
                                <input type="hidden" name="contact_id" id="contactId">
                                <div class="search-dropdown" id="contactDropdown"></div>
                            </div>
                        </div>


                        <!-- Issue Date -->
                        <div class="field-group">
                            <label>Issue Date</label>
                            <input type="date" class="" name="invoice_date" id="invoiceDate" readonly>
                        </div>

                        <!-- Due Date -->
                        <div class="field-group">
                            <label>Due Date</label>
                            <input type="date" class="" id="dueDate" name="due_date">
                        </div>

                        <!-- Invoice Number -->
                        <div class="field-group">
                            <label>Invoice Number</label>
                            <input type="text" class="" name="invoice_no" id="invoiceNo" readonly>
                        </div>

                        <!-- Reference -->
                        <!-- <div class="field-group">
                            <label>Reference</label>
                            <input type="text" class="" name="reference" placeholder="">
                        </div> -->
                    </div>

                    <!-- Bill Status -->
                    <div class="tax-inclusive">
                        <div class="field-group">
                            <label>Status</label>
                            <select class="form-select" id="status" name="status">
                                <option selected disabled class="text-center">Select Status</option>
                                <option value="Paid">Paid</option>
                                <option value="Unpaid">Unpaid</option>
                            </select>
                        </div>
                        <!-- <div class="field-group">
                            <label>Tax Type</label>
                            <select class="form-select" name="tax_type">
                                <option selected disabled class="text-center">Select tax</option>
                                <option value="inclusive">Tax inclusive</option>
                                <option value="exclusive">Tax exclusive</option>
                                <option value="notax">No tax</option>
                            </select>
                        </div> -->
                    </div>

                    <!-- Line Items Table -->
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th style="width:3%"></th>
                                <th style="width:17%">Product Code</th>
                                <th style="width:25%">Product Name</th>
                                <th style="width:7%" class="text-end">Qty.</th>
                                <th style="width:9%" class="text-end">Price</th>
                                <th style="width:14%" class="text-end">Tax Rate</th>
                                <th style="width:9%" class="text-end">Amount</th>
                                <th style="width:3%"></th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItems">
                            <!-- Default Row -->
                            <tr>
                                <td><span class="drag-handle">⠿</span></td>
                                <td>
                                    <div class="position-relative">
                                        <input type="text"
                                            class="table-input productSearch"
                                            value="${item.product_code}">

                                        <input type="hidden"
                                            class="productId"
                                            name="product_id[]"
                                            value="${item.product_id}">

                                        <span class="product-error text-danger"></span>

                                        <div class="search-dropdown productDropdown"></div>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" class="table-input description" name="description[]" placeholder="Description" readonly>
                                </td>
                                <td>
                                    <input type="number" class="table-input qty text-end" name="qty[]" value="1" min="1">
                                </td>
                                <td>
                                    <input type="number" class="table-input price text-end" name="price[]" value="0" step="0.01">
                                </td>
                                <td>
                                    <input type="number" class="table-input tax text-end" name="tax[]" value="0.00" readonly>
                                </td>
                                <td>
                                    <input type="text" class="table-input amount text-end" name="amount[]" value="0.00" readonly>
                                </td>
                                <td>
                                    <button type="button" class="del-btn remove-row" title="Remove">&times;</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Add Line Buttons -->
                    <div class="add-line-row">
                        <button type="button" class="add-line-btn" id="addRow">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Add a line item
                        </button>
                        
                    </div>

                    <!-- Totals -->
                    <div class="totals-section">
                        <div class="totals-box">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="color:#777">Subtotal</td>
                                        <td><input type="hidden" name="subtotal" id="subTotal"><span id="displaySubtotal">0.00</span></td>
                                    </tr>
                                    <tr class="muted-row">
                                        <td>Includes tax</td>
                                        <td><input type="hidden" name="tax_total" id="tax_total">
                                            <span id="displayTaxTotal">0.00</span>
                                        </td>
                                    </tr>
                                    <tr class="grand-row">
                                        <td>Total</td>
                                        <td>
                                            <input type="hidden" name="grand_total" id="grand_total">
                                            <span class="currency-label">INR</span>
                                            <span id="displayGrandTotal">0.00</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="form-actions">
                        <!-- <button type="button" class="btn-cancel">Cancel</button>
                        <button type="button" class="btn-more" title="More options">&#8943;</button> -->
                        <button type="submit" class="btn-save" id="saveBtn">Edit Invoice</button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <!-- jQuery Validation Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- Sweet alert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- LOGOUT Redirect -->
    <script src="controller/logout.js"></script>

    <script>
        $(document).ready(function() {
            //DUE DATE VALIDATION

            let today = new Date().toISOString().split('T')[0];
            $("#dueDate").attr("min", today);

            let editInvoiceId = new URLSearchParams(window.location.search).get('id');

        //alert if Invoice id not found
            if (!editInvoiceId) {
                Swal.fire({
                    icon: "error",
                    title: "Invalid Invoice ID"
                });
                return;
            }

            // FETCH DATA from DB
            $.ajax({
                url: "controller/fetch_edit_invoice.php",
                type: "GET",
                dataType: "json",
                data: {
                    id: editInvoiceId
                },
                success: function(res) {

                    let invoice = res.invoice;
                    let items = res.items;

                    // INVOICE HEADER
                    $("#invoice_id").val(invoice.id);
                    $("#contactSearch").val(invoice.name);
                    $("#contactId").val(invoice.contact_id);
                    $("#invoiceDate").val(invoice.invoice_date);
                    $("#dueDate").val(invoice.due_date);
                    $("#invoiceNo").val(invoice.invoice_no);
                    $("#status").val(invoice.status);

                    $("#subTotal").val(invoice.subtotal);
                    $("#tax_total").val(invoice.tax_total);
                    $("#grand_total").val(invoice.grand_total);

                    $("#displaySubtotal").text(invoice.subtotal);
                    $("#displayTaxTotal").text(invoice.tax_total);
                    $("#displayGrandTotal").text(invoice.grand_total);


                    // INVOICE ITEMS

                    $("#invoiceItems").html("");

                    items.forEach(function(item) {

                        let row = `
                        <tr>
                            <td><span class="drag-handle">⠿</span></td>

                            <td>
                                <div class="position-relative">
                                    <input type="text"
                                        class="table-input productSearch"
                                        value="${item.product_code}">

                                    <input type="hidden"
                                        class="productId"
                                        name="product_id[]"
                                        value="${item.product_id}">

                                    <span class="product-error text-danger"></span>

                                    <div class="search-dropdown productDropdown"></div>
                                </div>
                            </td>

                            <td>
                                <input type="text"
                                    class="table-input description"
                                    name="description[]"
                                    value="${item.description}"
                                    readonly>
                            </td>

                            <td>
                                <input type="number"
                                    class="table-input qty text-end"
                                    name="qty[]"
                                    value="${item.qty}">
                            </td>

                            <td>
                                <input type="number"
                                    class="table-input price text-end"
                                    name="price[]"
                                    value="${item.price}">
                            </td>

                            <td>
                                <input type="number"
                                    class="table-input tax text-end"
                                    name="tax[]"
                                    value="${item.tax}"
                                    readonly>
                            </td>

                            <td>
                                <input type="text"
                                    class="table-input amount text-end"
                                    name="amount[]"
                                    value="${item.amount}"
                                    readonly>
                            </td>

                            <td>
                                <button type="button"
                                    class="del-btn remove-row">
                                    &times;
                                </button>
                            </td>
                        </tr>
                        `;

                        $("#invoiceItems").append(row);
                    });

                }
            });

            $("#invoiceItems tr").each(function() {
                calculateRow($(this));
            });

            calculateTotals();

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
                                data-id ="${row.id}" data-name = "${row.name}">
                                ${row.name}
                                <br>
                                <small>${row.company} | ${row.gst}</small>
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
                                            data-tax = "${row.tax}" 
                                            data-sell = "${row.selling_price}"
                                            >
                                           <small> ${row.product_code} | ${row.product_name}</small>
                                            <br>
                                           <small> ${row.selling_price}</small>
                                        </div>`;
                            });
                        } else {
                            html += `<div class="p-2 text-danger">
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
                let tax = $(this).data("tax");


                let currentRow = $(this).closest("tr");

                //check if already selected
                let existingRow = null;

                $("#invoiceItems tr").each(function() {
                    if ($(this).find(".productId").val() == id) {
                        existingRow = $(this);
                    }

                });

                if (existingRow && existingRow.length && existingRow[0] !== currentRow[0]) {
                    let qtyInput = existingRow.find(".qty");
                    let qty = parseFloat(qtyInput.val()) || 0;
                    qtyInput.val(qty + 1);

                    calculateRow(existingRow);
                    calculateTotals();

                    //clear current row
                    currentRow.find(".productId, .productSearch, .description, .tax, .price").val("");
                    currentRow.find(".productDropdown").hide();

                    return;
                }

                currentRow.find(".productId, .productSearch, .description, .tax, .price").val("");

                currentRow.find(".productId").val(id);
                currentRow.find(".productSearch").val(code);
                currentRow.find(".description").val(name);
                currentRow.find(".tax").val(tax);
                currentRow.find(".price").val(sell);

                currentRow.find(".productDropdown").hide();

                calculateRow(currentRow);
                calculateTotals();

            });

            //add new row
            $("#addRow").click(function() {

                //no new row until last row filled
                let lastRow = $("#invoiceItems tr:last");
                let lastProduct = lastRow.find(".productId").val();

                if (!lastProduct) {
                    alert("Please fill the current row first.");
                    return;
                }

                let newRow = ` <tr>
                                <td><span class="drag-handle">⠿</span></td>
                                <td>
                                    <div class="position-relative">
                                        <input type="text" class="table-input productSearch" placeholder="Search product…">
                                        <input type="hidden" class="productId" name="product_id[]">
                                        <span class="product-error text-danger"></span>
                                        <div class="search-dropdown productDropdown"></div>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" class="table-input description" name="description[]" placeholder="Description" readonly>
                                </td>
                                <td>
                                    <input type="number" class="table-input qty text-end" name="qty[]" value="1" min="1">
                                </td>
                                <td>
                                    <input type="number" class="table-input price text-end" name="price[]" value="0" step="0.01">
                                </td>
                                <td>
                                    <input type="number" class="table-input tax text-end" name="tax[]" value="0.00" readonly>
                                </td>
                                <td>
                                    <input type="text" class="table-input amount text-end" name="amount[]" readonly>
                                </td>
                                <td>
                                    <button type="button" class="del-btn remove-row" title="Remove">&times;</button>
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

            //CALCULATE ROW
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

                //display total in forms
                $("#displaySubtotal").text(subTotal.toFixed(2));
                $("#displayTaxTotal").text(taxTotal.toFixed(2));
                $("#displayGrandTotal").text(grandTotal.toFixed(2));

            }

            // Total
            $(document).on("input", ".qty, .price, .tax", function() {

                let row = $(this).closest("tr");

                calculateRow(row);

                calculateTotals();
            });


            //Form Submission
            $("#invoiceForm").validate({
                ignore: [],
                rules: {
                    contact_id: {
                        required: true
                    },
                    invoice_date: {
                        required: true
                    },
                    status: {
                        required: true
                    }
                },
                messages: {
                    contact_id: {
                        required: "This field is required"
                    },
                    invoice_date: {
                        required: "This field is required"
                    },
                    status: {
                        required: "This field is required"
                    }
                },

                submitHandler: function(form) {

                    //validate dynamic row - Product

                    let valid = true;
                    $(".product-error").text("");
                    $("#invoiceItems tr").each(function(index) {
                        let productId = $(this).find(".productId").val();

                        if (!productId) {

                            $(this).find(".product-error")
                                .text("Please select a product");

                            valid = false;
                            return false;
                        }

                    });
                    if (!valid) {
                        return false;
                    }

                    let formData = new FormData(form);
                    $.ajax({
                        url: "php/edit_all_invoice.php",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,

                        success: function(response) {

                            let res = JSON.parse(response);

                            if (res.status === 'success') {

                                Swal.fire({
                                    title: "Successful",
                                    text: "Invoice Edited",
                                    icon: "success",
                                    confirmButtonText: "View/Print Invoice",
                                    showCancelButton: true
                                }).then((result) => {

                                    if (result.isConfirmed) {
                                        window.open(res.pdf, '_blank'); // OPEN PDF
                                        location.reload();
                                    } else {
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

                }


            });

            //close dropdown when user click on screen
            $(document).on("click", function(e) {

                // Contact dropdown
                if (!$(e.target).closest("#contactSearch, #contactDropdown").length) {
                    $("#contactDropdown").hide();
                }

                // Product dropdowns
                if (!$(e.target).closest(".productSearch, .productDropdown").length) {
                    $(".productDropdown").hide();
                }

            });
        });
    </script>

</body>

</html>