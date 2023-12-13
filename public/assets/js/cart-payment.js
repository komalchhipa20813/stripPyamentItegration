

$(document).ready(function(){
    jQuery.validator.addMethod(
        "amountCheck",
        function (value, element) {
            console.log(value);
            return false;
        }
    );

   
    $( "#itemsForm" ).validate({
        errorPlacement: function(){
            return false
        },

        rules: {
            username: {
                required: true,
                maxlength: 50
            },
            email: {
                required: true,
                email: true
            },
            mobile: {
                required: true,
                minlength: 13,
                maxlength: 13
            },
            billing_address:{
                required: true,
            }
           
        }
    });

    jQuery.validator.addMethod(
        "cardmonth",
        function (value, element) {
            console.log(value);
            return false;
        }
    );

    // Payment
    $( "#paymentForm" ).validate({
        errorPlacement: function(){
            return false
        },
        rules: {
            fullName: {
                required: true,
                maxlength: 50
            },
            cardNumber: {
                required: true,
                creditcard: true
            },
            month: {
                required: true,
                // cardmonth:true,

            },
            year:{
                required: true,
            },
            cvv: {
                required: true,
                minlength: 3,
                maxlength: 3
            },
           
        }
    });
    $("#amount").on("keyup", function(){
        var valid = /^\d{0,8}(\.\d{0,2})?$/.test(this.value),
            val = this.value;
        
        if(!valid){
            this.value = val.substring(0, val.length - 1);
        }
    });

    $("body").on("click", ".addItem-btn", function (event) {
       var Item_no= parseInt($('#total_item').val()) + parseInt(1); 
       $('.remove-item').removeClass('display-none');

       var html= ' <tr class="item'+Item_no+'"> <td><input type="text" class="form-control form-control-lg description1" name="description[]" placeholder="Description of service or product" ></td><td> <input type="number" class="form-control form-control-lg item'+Item_no+'-quantity"  name="quantity[]" placeholder="Quantity" onchange="calculateAmount(this);" ></td><td> <input type="text" class="form-control form-control-lg item'+Item_no+'-rate" name="rate[]" placeholder="Rate of per product" onchange="calculateAmount(this);" onkeyup="validateRate(this);"></td> <td><input type="text" class="form-control form-control-lg item'+Item_no+'-amount" name="amount[]" placeholder="Amount" readonly></td><td><a onclick="removeItem(this)" class="remove-item" style="color: #f55050;"><i class="fas fa-trash-alt remove-item-icon"></i></a></td></tr>';
       $('.tbl-body').append(html);
       $('#total_item').val(Item_no);
    });

    $(".submit-payment").on("click", function (event) {
        event.preventDefault();

         var form = $("#paymentForm")[0];
          var formData = new FormData(form);
          if ($("#paymentForm").valid()) {
              $.ajax({
                  url: aurl + "/payment",
                  type: "POST",
                  dataType: "JSON",
                  data: formData,
                  cache: false,
                  contentType: false,
                  processData: false,
                  beforeSend: function () {
                        $("#fullName").prop("disabled", true);
                        $("#cardNumber").prop("disabled", true);
                        $("#month").prop("disabled", true);
                        $("#year").prop("disabled", true);
                        $("#cvv").prop("disabled", true);
                        $(".submit-payment").prop("disabled", true);
                        $(".submit-payment").text('Payment InProcess....');
                    //   $("#loadMe").modal({
                    //     backdrop: "static", //remove ability to close modal with click
                    //     keyboard: false, //remove option to close with keyboard
                    //     show: true //Display loader!
                    //   });
                  },
                  success: function (data) {
                    if(data.status)
                    {
                        window.location.href = aurl + "" + data.redirect_url;
                    }
                    else
                    {
                        toastr.options =
                        {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                        toastr.error(data.message); 
                    }
                    
                    $("#fullName").prop("disabled", false);
                    $("#cardNumber").prop("disabled", false);
                    $("#month").prop("disabled", false);
                    $("#year").prop("disabled", false);
                    $("#cvv").prop("disabled", false);
                    $(".submit-payment").prop("disabled", false);
                    $(".submit-payment").text('Make Payment');
                   
                  },
                  error: function (request) {
                    $("#fullName").prop("disabled", false);
                    $("#cardNumber").prop("disabled", false);
                    $("#month").prop("disabled", false);
                    $("#year").prop("disabled", false);
                    $("#cvv").prop("disabled", false);
                    $(".submit-payment").prop("disabled", false);
                    $(".submit-payment").text('Make Payment');
                    toastr.options =
                        {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                        toastr.error("Something Went Wrong! Please Try Again.");
                  },
              });
          }

       
       
    });


    $(".submit-items").on("click", function (event) {
        var form = $("#itemsForm")[0];
    
          var formData = new FormData(form);
          if ($("#itemsForm").valid() && validationItem() ) 
          {
            $.ajax({
                url: aurl + "/cart-items",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    window.location.href = aurl + "" + data.redirect_url
                }
            });

          }
    });
});

function validationItem()
{
    var description= $("input[name='description[]']").map(function(){
      if($(this).val() == ''){return "required";}
      else{return $(this).val();}}).get();
      
      var description_validate= (jQuery.inArray("required", description) != -1) ? false : true;

      var quantity= $("input[name='quantity[]']").map(function(){
        if($(this).val() == ''){return "required";}
        else{return $(this).val();}}).get();
        
        var quantity_validate= (jQuery.inArray("required", quantity) != -1) ? false : true;
    
        var rate= $("input[name='rate[]']").map(function(){
            if($(this).val() == ''){return "required";}
            else{return $(this).val();}}).get();
            
         var rate_validate= (jQuery.inArray("required", rate) != -1) ? false : true;
        
         var amount= $("input[name='amount[]']").map(function(){
            if($(this).val() == ''){return "required";}
            else{return $(this).val();}}).get();
            
         var amount_validate= (jQuery.inArray("required", amount) != -1) ? false : true;
        if(!description_validate)
         {
            toastr.error("Please add description of product.");
            return false
         }
         else if(!quantity_validate)
         {
            toastr.error("Please add quantity of product.");
            return false
         }
         else if(!rate_validate)
         {
            toastr.error("Please add rate of product.");
            return false
         }
         else if(!amount_validate)
         {
            toastr.error("Please add amount of product.");
            return false
         }
         else{
            return true
         }
}
function calculateAmount(obj)
{
    var parentClassName=$(obj).parent().parent().attr('class');
    var Item_no=$('#total_item').val(); 
    var quantity=$('.'+parentClassName+'-quantity').val();
    var rate= $('.'+parentClassName+'-rate').val();
    if(quantity < 0)
    {
        $('.'+parentClassName+'-quantity').val('');
    }
    var amount=(rate != '' && quantity != '' && quantity > 0) ? rate * quantity : 0;
    $('.'+parentClassName+'-amount').val(amount.toFixed(2)); 
    calculateGrandTotal();

}

function validateRate(obj)
{
    var parentClassName=$(obj).parent().parent().attr('class');
    var valid = /^\d{0,8}(\.\d{0,2})?$/.test($('.'+parentClassName+'-rate').val()),
    val = $('.'+parentClassName+'-rate').val();
    if(!valid){
        $('.'+parentClassName+'-rate').val(val.substring(0, val.length - 1));
    }
}

function cardMonth(obj)
{
    var valid = /^\d{0,2}?$/.test($('.card-month').val()),
    val = $('.card-month').val();
    if(!valid){
        $('.card-month').val(val.substring(0, val.length - 1));
    }
}

function cardYear(obj)
{
    var valid = /^\d{0,4}?$/.test($('.card-year').val()),
    val = $('.card-year').val();
    if(!valid){
        $('.card-year').val(val.substring(0, val.length - 1));
    }
}

function calculateGrandTotal()
{   
    var SGST=2.5;
    var CGST=2.5;
    var amount = $("input[name='amount[]']")
              .map(function(){return $(this).val();
            }).get();
            var subTotal = 0;
            for (var i = 0; i < amount.length; i++) {
                subTotal +=amount[i] << 0;
            }   
 
        $('.subTotal').val(subTotal);
        $('.sub-total').text(subTotal.toFixed(2));

        SGST= parseFloat((subTotal * SGST)/100);
        $('.sgst').text(SGST.toFixed(2));
        $('.input-tax-sgst').val(SGST);

        CGST= parseFloat((subTotal * CGST)/100);
        $('.cgst').text(CGST.toFixed(2));
        $('.input-tax-cgst').val(CGST);


    
    var grandTotal= parseFloat(subTotal) + parseFloat(SGST) + parseFloat(SGST);
    $('.grandTotal').text(grandTotal.toFixed(2));
    $('.grand_total').val(grandTotal);

}



function removeItem(obj)
{
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger me-2",
        },
        buttonsStyling: false,
    });

    swalWithBootstrapButtons
        .fire({
            title: "Are you sure?",
            text: "You won't be able to revert this item!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true,
        })
        .then((result) => {
            if (result.value) {
                var parentClassName=$(obj).parent().parent().attr('class');
                var Item_no= $('#total_item').val(); 
                if(Item_no > 1)
                {
                    $('.'+parentClassName).remove();
                }
                var Item_no= Item_no - 1 ; 
                $('#total_item').val(Item_no);
                if($('#total_item').val() == 1){
                    $('.remove-item').addClass('display-none');
                }
                calculateGrandTotal();
            }
        });
}