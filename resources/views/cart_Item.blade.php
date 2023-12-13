@extends('layouts.main')
@section('title', 'Billing Form')
@section('content')
<section class="h-100 h-custom" style="background-color: #eee;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col">
                <div class="card">
                    <div class="card-body p-4">
                        <form role="form" method="POST" id="itemsForm" >
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <h5 class="mb-3"><a class="text-body">
                                        <i class="fa fa-file me-2"></i> Billing
                                    </h5>
                                    <hr>
                                    <div class="cartItems">
                                        <div class="card mb-3">
                                            <div class="card-body item-card">
                                                <h5>Customer Detail :</h5>
                                                <table id="items-table">
                                                    <tr>
                                                        <td><div class="form-group ">
                                                            <label for="name" class="form-label">Name:</label>
                                                            <input type="text" class="form-control" id="name" name="username">
                                                          </div>
                                                        </td>
                                                        <td><div class="form-group ">
                                                            <label for="email" class="form-label">Email:</label>
                                                            <input type="email" class="form-control" id="email" name="email">
                                                          </div>
                                                        </td>
                                                        <td><div class="form-group ">
                                                            <label for="mobile" class="form-label">Mobile No.:</label>
                                                            <input type="text" class="form-control" id="mobile" name="mobile">
                                                          </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">
                                                            <div class="form-group ">
                                                                <label for="billing_address" class="form-label">Billing Address:</label>
                                                                <textarea class="form-control" id="billing_address" name="billing_address" rows="3"></textarea>
                                                              </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <h5>Items :</h5>
                                               
                                                <input type="hidden" id="total_item" name="total_item" value="1" >
                                                <table id="items-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Description</th>
                                                            <th class="width-10-per">Quantity</th>
                                                            <th class="width-10-per">Rate</th>
                                                            <th class="width-10-per">Amount</th>
                                                            <th class="width-10-per">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tbl-body">
                                                        <tr class="item1">
                                                            <td>
                                                              <input type="text" class="form-control form-control-lg description1" name="description[]" placeholder="Description of service or product" >
                                                            </td>
                                                            <td>
                                                              <input type="number" class="form-control form-control-lg item1-quantity"  name="quantity[]" placeholder="Quantity" onchange="calculateAmount(this);" >
                                                            </td>
                                                            <td>
                                                              <input type="text" class="form-control form-control-lg item1-rate" name="rate[]" placeholder="Rate of per product" onchange="calculateAmount(this);" onkeyup="validateRate(this);">
                                                            </td>
                                                            <td>
                                                              <input type="text" class="form-control form-control-lg item1-amount" name="amount[]" placeholder="Amount" readonly>
                                                            </td>
                                                            <td>
                                                              <a onclick="removeItem(this)" class="remove-item  display-none" style="color: #f55050;"><i class="fas fa-trash-alt remove-item-icon"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="col-md-12 add-new-values-div">
                                                    <div class="col-md-10">&nbsp;</div>
                                                        <div class="col-md-2 float-right">
                                                        <!-- Add more rows as needed -->
                                                            <button type="button" class="btn btn-primary btn-block addItem-btn" data-item="1"><i class="fa fa-plus add-item-icon"></i> Add Item</button>
                                                        </div>
                                                  </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 ">
                                    <div class="col-md-9">&nbsp;</div>
                                    <div class="col-lg-3 float-right">
                                        <h5 class="mb-3"><a class="text-body">
                                            {{-- <i class="fa fa-list-alt me-2"></i> Payment Summary --}}
                                        </h5>
                                        {{-- <hr> --}}
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <input type="hidden" class="subTotal" name="subTotal" value="0">
                                                    <p class="mb-2 payment-summary">Subtotal</p>
                                                    <p class="mb-2 payment-summary">$<span class="sub-total payment-summary">0.00</span></p>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <p class="mb-2 payment-summary">SGST</p>
                                                    <p class="mb-2 payment-summary">
                                                        <p class="mb-2 payment-summary">$<span class="sgst payment-summary">0.00</span>
                                                            <input type="hidden" class="input-tax-sgst" name="sgst" value="0">
                                                        {{-- <input type="text" class="tax" name="tax" placeholder="$0"   style="width: 70px;text-align: right" onchange="calculateGrandTotal()"> --}}
                                                    </p>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <p class="mb-2 payment-summary">CGST</p>
                                                    <p class="mb-2 payment-summary">
                                                        <p class="mb-2 payment-summary">$<span class="cgst payment-summary">0.00</span>
                                                            <input type="hidden" class="input-tax-cgst"  name="cgst" value="0">
                                                        {{-- <input type="text" class="tax" name="tax" placeholder="$0"   style="width: 70px;text-align: right" onchange="calculateGrandTotal()"> --}}
                                                    </p>
                                                </div>
                            
                                                <div class="d-flex justify-content-between mb-4">
                                                    <input type="hidden" name="grand_total" class="grand_total">
                                                    <p class="mb-2 payment-summary">Total(Incl. taxes)</p>
                                                    <p class="mb-2 payment-summary">$<span class="grandTotal payment-summary">0.00</span></p>
                                                </div>
                                                <button type="button" class="btn btn-primary btn-block btn-lg submit-items">
                                                    <div class="d-flex justify-content-between">
                                                    <span class="payment-text">Process To Payment <i class="fas fa-long-arrow-alt-right ms-2"></i></span>
                                                    </div>
                                                </button>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
@parent
    <script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/sweet-alert.js') }}"></script>
    <script src="{{ asset('assets/js/cart-payment.js') }}"></script>
    
@endsection