$(document).ready(function () {
    loadproducts(); // Load all products by default

    $("#search").on("input", function () {
        loadproducts(); // Load products based on search input
    });
});

function loadproducts() {
    var name = $("#search").val();
    $.ajax({
        type: "post",
        data: {
            products: name,
        },
        url: "loadproducts.php",
        success: function (Response) {
            $("#tableData").html(Response);
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error: ", status, error);
        },
    });
}

// Customer search using typeahead
$("#customer_search").typeahead({
    source: function (query, result) {
        $.ajax({
            url: "loadcustomer.php",
            method: "POST",
            data: { query: query },
            dataType: "json",
            success: function (data) {
                result(
                    $.map(data, function (item) {
                        return item;
                    }),
                );
            },
        });
    },
});

// function GrandTotal() {
//     var TotalValue = 0;
//     var TotalPriceArr = $("#products tr .totalPrice").get();
//     var discount = $("#discount").val();

//     var TotalValue = 0;

//     $(TotalPriceArr).each(function () {
//         TotalValue += parseFloat(
//             $(this).text().replace(/,/g, "").replace("Ksh", "")
//         ) || 0;
//     });

//     var discountAmount = parseFloat(discount) || 0;

//     if (
//         !Number.isFinite(discountAmount) ||
//         discountAmount < 0 ||
//         discountAmount >= TotalValue
//     ) {
//         swal("Error", "Invalid discount amount!", "error");
//         return;
//     }

//     TotalValue = TotalValue - discountAmount;

//     $(TotalPriceArr).each(function () {
//         TotalValue += parseFloat(
//             $(this).text().replace(/,/g, "").replace("Ksh", ""),
//         );
//     });

//     if (discount != null) {
//         var f_discount = TotalValue - discount;
//         $("#totalValue").text(
//             accounting.formatMoney(f_discount, { symbol: "Ksh", format: "%s %v" }),
//         );
//         $("#totalValue1").text(
//             accounting.formatMoney(TotalValue, { format: "%v" }),
//         );
//     } else {
//         $("#totalValue").text(
//             accounting.formatMoney(TotalValue, { symbol: "Ksh", format: "%s %v" }),
//         );
//         $("#totalValue1").text(
//             accounting.formatMoney(TotalValue, { format: "%v" }),
//         );
//     }
// }

function GrandTotal() {
    let TotalValue = 0;

    $("#products tr .totalPrice").each(function () {
        TotalValue +=
            parseFloat($(this).text().replace(/,/g, "").replace("Ksh", "")) || 0;
    });

    $("#totalValue").text(
        accounting.formatMoney(TotalValue, {
            symbol: "Ksh",
            format: "%s %v",
        }),
    );

    $("#totalValue1").text(
        accounting.formatMoney(TotalValue, {
            format: "%v",
        }),
    );
}

$(document).on("change", "#discount", function () {
    GrandTotal();
});

$("body").on("click", ".js-add", function () {
    var target = $(this);
    var product = target.attr("data-product");
    var minimum = target.attr("data-minimum_price");
    var price = target.attr("data-price");
    var barcode = target.attr("data-barcode");
    var unit = target.attr("data-unt");

    swal({
        title: "Enter number of items:",
        content: "input",
    }).then((value) => {
        if (value == "") {
            swal("Error", "Entered none!", "error");
        } else {
            var qtynum = value;
            if (isNaN(qtynum)) {
                swal("Error", "Please enter a valid number!", "error");
            } else if (qtynum == null) {
                swal("Error", "Please enter a number!", "error");
            } else {
                var total = parseInt(value, 10) * parseFloat(price);
                var minimum_total = parseInt(value, 10) * parseFloat(minimum);
                $("#products").append(
                    "<tr class='prd'>" +
                    "<td class='barcode text-left'>" +
                    barcode +
                    "</td>" +
                    "<td class=' text-left'>" +
                    product +
                    "</td>" +
                    "<td class='minimum text-left'>" +
                    accounting.formatMoney(minimum_total, {
                        symbol: "Ksh",
                        format: "%s %v",
                    }) +
                    "</td>" +
                    "<td class='price text-left'>" +
                    accounting.formatMoney(total, { symbol: "Ksh", format: "%s %v" }) +
                    "</td>" +
                    "<td class='text-left'>" +
                    unit +
                    "</td>" +
                    "<td class='qty text-left'>" +
                    value +
                    "</td>" +
                    "<td class='totalPrice text-left'>" +
                    accounting.formatMoney(total, { symbol: "Ksh", format: "%s %v" }) +
                    "</td>" +
                    "<td class='text-left p-1'>" +
                    "<button class='btn btn-danger btn-sm p-1' type='button' id='delete-row'>" +
                    "<i class='fas fa-times-circle'></i>" +
                    "</button>" +
                    "&nbsp" +
                    "<button class='btn btn-primary btn-sm p-1 edit-total' type='button' " +
                    "data-minimum='" +
                    minimum_total +
                    "' " +
                    "data-price='" +
                    total +
                    "'>" +
                    "<i class='fas fa-money-bill'><p>Edit Total</p></i>" +
                    "</button>" +
                    "</td>" +
                    "</tr>",
                );
                GrandTotal();
            }
        }
    });
});

// New Edit Total button click handler
$(document).on("click", ".edit-total", function () {
    var row = $(this).closest("tr");
    var minimumPrice = parseFloat($(this).data("minimum"));
    var sellingPrice = parseFloat($(this).data("price"));

    swal({
        title: "Edit Total Price",
        text: "Minimum: Ksh " + minimumPrice + "\nMaximum: Ksh " + sellingPrice,
        content: "input",
        buttons: {
            cancel: true,
            confirm: true,
        },
    }).then((value) => {
        if (value) {
            var newTotal = parseFloat(value);
            if (isNaN(newTotal)) {
                swal("Error", "Please enter a valid number!", "error");
            } else if (newTotal < minimumPrice) {
                swal("Error", "Price cannot be lower than minimum price!", "error");
            } else if (newTotal > sellingPrice) {
                swal("Error", "Price cannot exceed selling price!", "error");
            } else {
                row.find(".totalPrice").text(
                    accounting.formatMoney(newTotal, {
                        symbol: "Ksh",
                        format: "%s %v",
                    }),
                );
                GrandTotal();
            }
        }
    });
});

// Rest of your existing code for delete, payment processing, etc.
$(document).ready(function () {
    document.getElementById("search").focus();
});

$("body").on("click", "#delete-row", function () {
    var target = $(this);
    swal({
        title: "Remove this item?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $(this).parents("tr").remove();
            swal("Removed Successfully!", {
                icon: "success",
            });
            GrandTotal();
        }
    });
});

// Payment processing: Cash and M-Pesa
let mpesaPaymentProcessing = false;
$(document).on("click", ".Enter", function () {
    if (mpesaPaymentProcessing) {
        swal(
            "Payment Already Processing",
            "Please check the current M-Pesa payment before starting another sale.",
            "warning",
        );
        return;
    }

    const rows = $("#products tr.prd");

    if (rows.length === 0) {
        swal("Warning", "No products ordered!", "warning");
        return;
    }

    const product = [];
    const quantity = [];
    const price = [];
    const lineTotals = [];

    let TotalValue = 0;

    // Read the current cart, including edited totals
    rows.each(function () {
        const row = $(this);

        const productId = row.find(".barcode").text().trim();

        const qty = parseInt(row.find(".qty").text().trim(), 10);

        const lineTotal = parseFloat(
            row
                .find(".totalPrice")
                .text()
                .replace(/,/g, "")
                .replace(/Ksh/gi, "")
                .trim(),
        );

        if (
            !productId ||
            !Number.isInteger(qty) ||
            qty <= 0 ||
            !Number.isFinite(lineTotal) ||
            lineTotal <= 0
        ) {
            return;
        }

        product.push(productId);
        quantity.push(qty);

        // Actual selling total, including Edit Total changes
        price.push(lineTotal.toFixed(2));
        lineTotals.push(lineTotal.toFixed(2));

        TotalValue += lineTotal;
    });

    if (product.length !== rows.length || TotalValue <= 0) {
        swal("Error", "Invalid cart details!", "error");
        return;
    }

    TotalValue = Math.round(TotalValue * 100) / 100;

    // Discounts temporarily disabled
    const discount = 0;

    swal({
        title: "Select Payment Method",
        buttons: {
            cash: {
                text: "Cash",
                value: "cash",
            },
            mpesa: {
                text: "MPESA",
                value: "mpesa",
            },
        },
    }).then(function (paymentMethod) {
        // =========================
        // CASH PAYMENT
        // =========================
        if (paymentMethod === "cash") {
            swal({
                title: "Enter Cash Amount",
                text: "Amount due: KSh " + TotalValue.toFixed(2),
                content: "input",
            }).then(function (cashAmount) {
                if (cashAmount === null) {
                    return;
                }

                const amountPaid = Number(cashAmount);

                if (
                    String(cashAmount).trim() === "" ||
                    !Number.isFinite(amountPaid) ||
                    amountPaid <= 0
                ) {
                    swal("Error", "Please enter a valid cash amount!", "error");
                    return;
                }

                if (amountPaid < TotalValue) {
                    swal(
                        "Error",
                        "Can't process a smaller amount than the total!",
                        "error",
                    );
                    return;
                }

                const change = amountPaid - TotalValue;

                $.ajax({
                    url: "insert_sales.php",
                    method: "POST",
                    data: {
                        totalvalue: TotalValue,
                        product: product,
                        price: price,
                        quantity: quantity,
                        discount: discount,
                        payment_method: "Cash",
                    },

                    success: function (data) {
                        if (String(data).trim() === "success") {
                            swal({
                                title: "Cash Payment Successful",
                                text:
                                    "Change: " +
                                    accounting.formatMoney(change, {
                                        symbol: "Ksh",
                                        format: "%s %v",
                                    }),
                                icon: "success",
                                button: "Okay",
                            }).then(function () {
                                window.location.href = "main.php";
                            });
                        } else {
                            swal("Error", "Could not record the cash sale.", "error");

                            console.error("Cash sale response:", data);
                        }
                    },

                    error: function (xhr) {
                        console.error("Cash payment error:", xhr.responseText);

                        swal(
                            "Error",
                            "Could not contact the sales server. Check whether the sale was recorded before retrying.",
                            "error",
                        );
                    },
                });
            });
        }

        // =========================
        // M-PESA PAYMENT
        // =========================
        else if (paymentMethod === "mpesa") {
            // Current sandbox setup accepts whole KSh amounts
            if (!Number.isInteger(TotalValue)) {
                swal(
                    "Invalid Amount",
                    "M-Pesa sandbox currently requires a whole-KSh total.",
                    "error",
                );
                return;
            }

            swal({
                title: "M-Pesa STK Push",
                text:
                    "Amount: KSh " +
                    TotalValue.toFixed(2) +
                    "\nEnter customer's M-Pesa number",

                content: {
                    element: "input",
                    attributes: {
                        placeholder: "07XXXXXXXX",
                        type: "tel",
                    },
                },

                buttons: {
                    cancel: true,
                    confirm: "Send STK Push",
                },
            }).then(function (phone) {
                if (phone === null) {
                    return;
                }

                phone = String(phone).trim();

                if (!/^(07|01)\d{8}$/.test(phone) && !/^254[71]\d{8}$/.test(phone)) {
                    swal(
                        "Invalid Number",
                        "Enter a valid Safaricom phone number.",
                        "error",
                    );
                    return;
                }
                mpesaPaymentProcessing = true;

                swal({
                    title: "Sending STK Push",
                    text: "Contacting Safaricom...",
                    icon: "info",
                    buttons: false,
                    closeOnClickOutside: false,
                });

                $.ajax({
                    url: "mpesa/stk_push.php",
                    method: "POST",
                    dataType: "json",

                    data: {
                        phone: phone,
                        amount: TotalValue,
                        product: product,
                        quantity: quantity,
                        line_total: lineTotals,
                        discount: 0,
                        csrf: $('meta[name="pos-csrf"]').attr("content"),
                    },

                    success: function (response) {
                        if (response.status === "pending" && response.checkout_request_id) {
                            swal({
                                title: "STK Push Sent",
                                text: "Waiting for the payment result...",
                                icon: "info",
                                buttons: false,
                                closeOnClickOutside: false,
                            });

                            monitorMpesaPayment(response.checkout_request_id);
                        } else {

                            mpesaPaymentProcessing = false;

                            swal(
                                "Request Failed",
                                response.message || "STK Push failed.",
                                "error"
                            );

                        }
                    },

                    error: function (xhr) {
                        mpesaPaymentProcessing = false;

                        console.error(
                            "M-Pesa request failed:",
                            xhr.status,
                            xhr.responseJSON || xhr.responseText,
                        );

                        swal(
                            "M-Pesa Request Error",
                            xhr.responseJSON?.message ||
                            "Could not initiate the payment. Check payment history before retrying.",
                            "error",
                        );
                    },
                });
            });
        }
    });
});



function monitorMpesaPayment(checkoutId, attempt = 0) {
    if (attempt >= 40) {
        swal(
            "Payment Status Uncertain",
            "The payment could not be fully confirmed. Check payment history before starting another payment.",
            "warning",
        );
        return;
    }

    setTimeout(function () {
        $.ajax({
            url: "mpesa/payment_status.php",
            method: "GET",
            dataType: "json",
            data: {
                checkout_id: checkoutId,
            },

            success: function (response) {
                // Payment verified and sale successfully recorded.
                if (response.status === "finalized") {
                    mpesaPaymentProcessing = false;

                    swal({
                        title: "M-Pesa Payment Successful",
                        text:
                            "Payment verified and sale recorded successfully.\n" +
                            "Receipt Number: " +
                            response.receipt_no,
                        icon: "success",
                        button: "Okay",
                        closeOnClickOutside: false,
                    }).then(function () {
                        // Reload only after the sale is finalized.
                        // This clears the current POS cart.
                        window.location.href = "main.php";
                    });

                    return;
                }

                // Still waiting for Safaricom verification.
                if (
                    response.status === "pending" ||
                    response.status === "reported_success" ||
                    response.status === "query_confirmed" ||
                    response.status === "needs_review"
                ) {
                    monitorMpesaPayment(checkoutId, attempt + 1);
                    return;
                }

                // Safaricom reported an unsuccessful payment.
                if (
                    response.status === "failed" ||
                    response.status === "cancelled" ||
                    response.status === "timed_out"
                ) {
                    mpesaPaymentProcessing = false;

                    swal(
                        "Payment Not Completed",
                        response.result_desc || "The M-Pesa transaction was unsuccessful.",
                        "warning",
                    );

                    return;
                }

                // Unknown status: don't assume failure.
                monitorMpesaPayment(checkoutId, attempt + 1);
            },

            error: function (xhr) {
                console.error(
                    "M-Pesa status check error:",
                    xhr.status,
                    xhr.responseText,
                );

                // Authentication failures require a new login.
                if (xhr.status === 401 || xhr.status === 403) {
                    swal(
                        "Session Expired",
                        "Please log in again and check payment history.",
                        "warning",
                    );
                    return;
                }

                // Other errors may be temporary.
                monitorMpesaPayment(checkoutId, attempt + 1);
            },
        });
    }, 3000);
}

$(document).on("click", ".cancel", function (e) {
    var TotalPriceArr = $("#tableData tr .totalPrice").get();
    if (TotalPriceArr == 0) {
        return 0;
    } else {
        swal({
            title: "Cancel orders?",
            text: "By doing this, orders will remove!",
            icon: "warning",
            buttons: ["No", "Yes"],
            dangerMode: true,
        }).then((reload) => {
            if (reload) {
                location.reload();
            }
        });
    }
});

function out() {
    var lag = "logout";
    swal({
        title: "Logout?",
        icon: "warning",
        buttons: ["Cancel", "Yes"],
        dangerMode: true,
    }).then((value) => {
        if (value) {
            if (lag) {
                $.ajax({
                    type: "post",
                    data: {
                        logout: lag,
                    },
                    url: "server/connection.php",
                    success: function (data) {
                        window.location.href = "index.php";
                    },
                });
            }
        }
    });
}
