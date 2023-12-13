@extends('layouts.main')
@section('title', 'Payment Form')
@section('content')
<section class="h-100 h-custom sucess-payment-section">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col">
        <div class="card card-success">
          <div class="card-body p-4">
            <div class="card-div"> 
              <div class="checkmark-div">
                <i class="checkmark">✓</i>
              </div>
                <h3>Payment Success</h3> 
                <p>You will received your invoice in your email<br/> we'll be in touch shortly!</p>
              </div>
              <a href="{{route('index.payment')}}" class="btn btn-primary hBack" type="button"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go to Payment</a>
        </div>
            </div>
              
        </div>
      </div>
    </div>
  </div>
</section>
@endsection