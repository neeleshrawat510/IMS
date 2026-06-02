<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>
    @media print {

        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Hide everything except invoice */
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
            padding: 10mm;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            word-wrap: break-word;
        }

        thead {
            background: #f2f2f2 !important;

        }

        h2 {
            margin: 0;
            font-size: 20px;
        }

        /* Prevent table breaking badly */
        tr {
            page-break-inside: avoid;
        }

        /* Summary box alignment fix */
        table[align="right"] {
            float: right;
            width: 40%;
        }

        /* Prevent overflow */
        * {
            box-sizing: border-box;
        }
    }
</style>

<body>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery Validation Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- Sweet alert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            let invoiceId = new URLSearchParams(window.location.search).get('id');
            $.ajax({
                    url: "controller/fetch_print_invoice.php",
                    type: "GET",
                    dataType: "json",
                    data: {
                        id: invoiceId
                    },
                    success: function(data) {
                        if (!data || data.length === 0) {
                            alert("No invoice data found");
                            window.location.href = "manage_invoice.php";
                            return;
                        }

                  
                        $(".printInvoiceNo").html(data[0].invoice_no);
                        $("#printDate").html(data[0].invoice_date);
                        $("#printCustomer").html(data[0].fname + ' ' + data[0].lname);
                        $("#printSubtotal").html(data[0].subtotal);
                        $("#printTax").html(data[0].tax_total);
                        $("#printGrand").html(data[0].grand_total);

                     
                        let itemRow = '';
                        data.forEach(function(item) {
                            itemRow += `<tr>
                        <td>${item.product_code}</td>
                        <td>${item.product_name}</td>
                        <td>${item.qty}</td>
                        <td>${item.price}</td>
                        <td>${item.tax}</td>
                        <td>${item.amount}</td>
                    </tr>`;
                        });

                        $("#printItems").html(itemRow);

                        $("#printInvoice").show();
                        setTimeout(function() {
                            window.print();
                            window.location.href = "manage_invoice.php";
                        }, 500);
                    
                },
                error: function() {
                    alert("Failed to load invoice");
                    window.location.href = "manage_invoice.php"
                }

            });
        });
    </script>
</body>

</html>