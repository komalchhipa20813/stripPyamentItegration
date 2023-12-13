@extends('layouts.main')
@section('title', 'Card Form')
@section('content')
<section class="h-100 h-custom" style="background-color: #eee;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-4">
                <div class="card bg-primary text-white rounded-3">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Card details</h5>
                      </div>
                      <p class="small mb-2 card-type">Card type</p>
                      <div class="card-icon">
                      <a  type="submit" class="text-white"><i
                          class="fab fa-cc-mastercard fa-2x me-2"></i></a>
                      <a  type="submit" class="text-white"><i
                          class="fab fa-cc-visa fa-2x me-2"></i></a>
                      <a  type="submit" class="text-white"><i
                          class="fab fa-cc-amex fa-2x me-2"></i></a>
                      <a  type="submit" class="text-white"><i class="fab fa-cc-paypal fa-2x"></i></a>
                      </div>
                      <form role="form" method="POST" id="paymentForm" action="{{ url('/payment')}}">
                        @csrf
                        <div class="form-outline form-white mb-4 form-group input-group">
                          <input type="text" id="fullName" name="fullName" class="form-control form-control-lg" siez="17"
                            placeholder="Cardholder's Name" />
                          {{-- <label class="form-label" for="typeName">Cardholder's Name</label> --}}
                        </div>
  
                        <div class="form-outline form-white mb-4 form-group input-group">
                          <input type="text" id="cardNumber" name="cardNumber" class="form-control form-control-lg" siez="17"
                            placeholder="1234567890123457" minlength="16" maxlength="16" />
                          {{-- <label class="form-label" for="typeText">Card Number</label> --}}
                        </div>
                       
                        <div class="row mb-6">
                          <div class="col-md-8">
                            <div class="form-outline form-white form-group">
                              <div class="input-group">
                                <input type="text" id="month" name="month" class="form-control form-control-lg card-month" siez="17"
                                placeholder="MM" onkeyup="cardMonth(this);"/>
                                <input type="text" id="year" name="year" class="form-control form-control-lg card-year" siez="17"
                                placeholder="YYYY" onkeyup="cardYear(this);"/>
                                
                            </div>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-outline form-white form-group">
                              <input type="password" id="cvv" name="cvv" class="form-control form-control-lg"
                                placeholder="CVV" size="1" minlength="3" maxlength="3" />
                            </div>
                          </div>
                        </div>

                        <button type="submit" class="btn btn-info btn-block btn-lg submit-payment text-center">
                          Make Payment 
                      </button>
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
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('assets/js/cart-payment.js') }}"></script>
    
@endsection