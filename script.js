$(document).ready(function() {
  loadproducts(); // Load all products by default

  $("#search").on("input", function() {
      loadproducts(); // Load products based on search input
  });
});

function loadproducts() {
  var name = $("#search").val(); 
  // Get the search input value

  $.ajax({
      type: 'post',
      data: {
          products: name // Send the search input (empty if nothing typed)
      },
      url: 'loadproducts.php', // Ensure this is the correct path to your PHP file
      success: function(Response) {
          $('#tableData ').html(Response); // Update the products table with the response
      },
      error: function(xhr, status, error) {
          console.error("AJAX Error: ", status, error); // Log AJAX errors
      }
  });
}


// Call loadproducts() on page load to display all products
$(document).ready(function() {
  loadproducts(); // Load all products by default

  $("#search").on("input", function() {
      loadproducts(); // Load products based on search input
  });
});

  // Customer search using typeahead, if needed
  $('#customer_search').typeahead({
      source: function(query, result) {
          $.ajax({
              url: 'loadcustomer.php',
              method: 'POST',
              data: { query: query },
              dataType: 'json',
              success: function(data) {
                  result($.map(data, function(item) {
                      return item;
                  }));
              }
          });
      }
  });



function GrandTotal(){
  var TotalValue = 0;
  var TotalPriceArr = $('#products tr .totalPrice').get()
  var discount = $('#discount').val();

  $(TotalPriceArr).each(function(){
    TotalValue += parseFloat($(this).text().replace(/,/g, "").replace("Ksh",""));
  });

  if(discount != null){
    var f_discount = 0;

    f_discount = TotalValue - discount;

    $("#totalValue").text(accounting.formatMoney(f_discount,{symbol:"Ksh",format: "%s %v"}));
    $("#totalValue1").text(accounting.formatMoney(TotalValue,{format: "%v"}));
  }else{
    $("#totalValue").text(accounting.formatMoney(TotalValue,{symbol:"Ksh",format: "%s %v"}));
    $("#totalValue1").text(accounting.formatMoney(TotalValue,{format: "%v"}));
  }
};

$(document).on('change', '#discount', function(){
  GrandTotal();
});

$('body').on('click','.js-add',function(){
			var totalPrice = 0;
   		var target = $(this);
    	var product = target.attr('data-product');
    	var price = target.attr('data-price');
    	var barcode = target.attr('data-barcode');
    	var unit = target.attr('data-unt');   	
    	swal({
        title: "Enter number of items:",
  			content: "input",
		  })
		  .then((value) => {
			  if (value == "") {
				  swal("Error","Entered none!","error");
			  }else{
				  var qtynum = value;
				  if (isNaN(qtynum)){
    				swal("Error","Please enter a valid number!","error");
          }else if(qtynum == null){
            swal("Error","Please enter a number!","error");
    		  }else{
    				var total = parseInt(value,10) * parseFloat(price);
    				$('#products').append("<tr class='prd'><td class='barcode text-center'>"+barcode+"</td><td class='text-center'>"+product+"</td><td class='price text-center'>"+accounting.formatMoney(price,{symbol:"Ksh",format: "%s %v"})+"</td><td class='qty text-center'>"+value+"</td><td class='totalPrice text-center'>"+accounting.formatMoney(total,{symbol:"Ksh",format: "%s %v"})+"</td><td class='text-center p-1'><button class='btn btn-danger btn-sm' type='button' id='delete-row'><i class='fas fa-times-circle'></i></button><tr>");
	          GrandTotal();
        }
			}
  });
});

$(document).ready(function(){
  	document.getElementById("search").focus();
 });

$("body").on('click','#delete-row', function(){
   	var target = $(this);
   	swal({
  		title: "Remove this item?",
  		icon: "warning",
  		buttons: true,
  		dangerMode: true,
		})
		.then((willDelete) => {
  		if (willDelete) {
  			$(this).parents("tr").remove();
    		swal("Removed Successfully!", {
      		icon: "success",
    		});
    			GrandTotal();
  		}
	});
});

$(document).on('click', '.Enter', function() {
  var TotalPriceArr = $('#products tr .totalPrice').get(); // Get total price from the table data

  if (TotalPriceArr == 0) {
      swal("Warning", "No products ordered!", "warning");
      return false;
  } else {
      var product = [];
      var quantity = [];
      var price = [];
      var user = $('#uname').val();
      var customer = $('#customer_search').val();
      var discount = $('#discount').val();

      $('.barcode').each(function() {
          product.push($(this).text());
      });
      $('.qty').each(function() {
          quantity.push($(this).text());
      });
      $('.price').each(function() {
          price.push($(this).text().replace(/,/g, "").replace("Ksh", ""));
      });

      // Ask for payment method: MPESA or Cash
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
              }
          }
      }).then((paymentMethod) => {
          if (paymentMethod == "cash") {
              // For Cash payment
              swal({
                  title: "Enter Cash Amount",
                  content: "input",
              }).then((cashAmount) => {
                  if (cashAmount == "" || isNaN(cashAmount)) {
                      swal("Error", "Please enter a valid amount!", "error");
                  } else {
                      var TotalValue = 0;
                      $(TotalPriceArr).each(function() {
                          TotalValue += parseFloat($(this).text().replace(/,/g, "").replace("Ksh", ""));
                      });

                      if (TotalValue > parseFloat(cashAmount)) {
                          swal("Error", "Can't process a smaller amount than the total!", "error");
                      } else {
                          var change = parseFloat(cashAmount) - TotalValue;
                          // Process the payment with Cash
                          $.ajax({
                              url: "insert_sales.php",
                              method: "POST",
                              data: {
                                  totalvalue: TotalValue,
                                  product: product,
                                  price: price,
                                  user: user,
                                  quantity: quantity,
                                  discount: discount,
                                  payment_method: "Cash" // Include payment method
                              },
                              success: function(data) {
                                  if (data == "success") {
                                      swal({
                                          title: "Change is " + accounting.formatMoney(change, {
                                              symbol: "Ksh",
                                              format: "%s %v"
                                          }),
                                          icon: "success",
                                          buttons: "Okay",
                                      }).then((okay) => {
                                          if (okay) {
                                              window.location.href = 'main.php';
                                          }
                                      });
                                  } else {
                                     alert("error")
                                  }
                              }
                              
                          });
                      }
                  }
              });
          } else if (paymentMethod == "mpesa") {
              // For MPESA payment
              swal({
                  title: "Enter MPESA Code",
                  content: "input",
              }).then((mpesaCode) => {
                  if (mpesaCode == "" || mpesaCode.length != 10) {
                      swal("Error", "Please enter a valid MPESA code!", "error");
                  } else {
                      var TotalValue = 0;
                      $(TotalPriceArr).each(function() {
                          TotalValue += parseFloat($(this).text().replace(/,/g, "").replace("Ksh", ""));
                      });

                      // Process the payment with MPESA
                      $.ajax({
                          url: "insert_sales.php",
                          method: "POST",
                          data: {
                              totalvalue: TotalValue,
                              product: product,
                              price: price,
                              user: user,
                              quantity: quantity,
                              discount: discount,
                              mpesacode: mpesaCode,
                              payment_method: "MPESA" // Include payment method
                          },
                          success: function(data) {
                              if (data == "success") {
                                  swal({
                                      title: "Payment successful with MPESA!",
                                      icon: "success",
                                      buttons: "Okay",
                                  }).then((okay) => {
                                      if (okay) {
                                          window.location.href = 'main.php';
                                      }
                                  });
                              } else {
                                  window.location.href = 'main.php?' + data;
                              }
                          }
                      });
                  }
              });
          }
      });
  }
});



$(document).on('click','.cancel',function(e){
  var TotalPriceArr = $('#tableData tr .totalPrice').get();
  if (TotalPriceArr == 0){
    return 0;
  }else{
    swal({
      title: "Cancel orders?",
      text: "By doing this,orders will remove!",
      icon: "warning",
      buttons: ["No","Yes"],
      dangerMode: true,
    })
    .then((reload) => {
      if (reload) {
        location.reload();
      }
    });
  }
});

function out(){
  var lag = "logout";
  swal({
      title: "Logout?",
      icon: "warning",
      buttons: ["Cancel","Yes"],
      dangerMode: true,
    })
    .then((value) => {
      if(value){
        if(lag){
            $.ajax({
              type: 'post',
              data: {
                logout:lag
              },
              url: 'server/connection.php',
              success: function (data){
                window.location.href='index.php';
              }
            });
        }
      }
    })
};
//========================================================================
$(document).on('click','.myForm',function(){

  


        const difference=document.getElementById('difference').value;
        swal("Error","Entered None!"+ difference,"error");
          
                
              })
