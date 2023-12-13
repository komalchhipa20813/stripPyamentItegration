<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\Exception\CardException;
use Stripe\StripeClient;
use App\Models\PayamentDetail;
use Illuminate\Support\Facades\Session;
use Mail;
use PDF;
use Storage;
use File;

class PaymentController extends Controller
{
    private $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.api_keys.secret_key'));
    }

    public function index()
    {
        session()->put('billingArray', '');
        return view('cart_Item');
    }

    public function itemManage(Request $request)
    {
        Session::put('billingArray',$request->all());
        $response = [
            'status' => true,
            'redirect_url' => "/card-payament",
        ];
        return response($response);
    }

    public function cardPayment()
    {
        $data=Session::get('billingArray');

       if(empty($data))
       {
            return response()->redirectTo('/');
       }
      
        return view('payment_card');
    }

    public function payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullName' => 'required',
            'cardNumber' => 'required',
            'month' => 'required',
            'year' => 'required',
            'cvv' => 'required'
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => $validator->errors()->first(),
                'icon' => 'error',
                'redirect_url' => "/card-payament",
            ];
            return response($response);
        }

        //check card validation
        $token = $this->createToken($request);
        if (!empty($token['error'])) {
            $request->session()->flash('danger', $token['error']);
            $response = [
                'status' => false,
                'message' => $token['error'],
                'icon' => 'error',
                'redirect_url' => "/card-payament",
            ];
            return response($response);
        }
        if (empty($token['id'])) {
            $response = [
                'status' => false,
                'message' => 'Payment failed.',
                'icon' => 'error',
                'redirect_url' => "/card-payament",
            ];
            return response($response);
        }
        //payment here
        $session_data=Session::get('billingArray');
        $grand_total=round($session_data['grand_total']);
        $charge = $this->createCharge($token['id'],  $grand_total);
        

        if (!$charge['error'] && !empty($charge) && $charge['status'] == 'succeeded') {

           $results = $this->storePatmentDetails($charge,$request,$session_data);
           $data=array(
                'invoice_no'=>$results['result']->invoice_no,
                'from'=>env('APP_COMAPNY_NAME'),
                'name'=>$session_data['username'],
                'date'=> date('m-d-Y'),
                'sub_total'=>$session_data['subTotal'],
                'sgst'=>number_format($session_data['sgst'], 2),
                'cgst'=>number_format($session_data['cgst'], 2),
                'grandTotal'=>number_format($session_data['grand_total'], 2),
            );
           $item_array=$results['item_arrays'];
           $fileName   = $this->generatePDF($item_array, $data,$results['result']->invoice_no);
                         $this->sendMail($session_data['email'], $fileName ,$charge, $results['result']->invoice_no,$session_data['username']);
             $request->session()->flash('success', 'Payment completed.');

             $response = [
                'status' => true,
                'icon' => 'success',
                'redirect_url' => "/payment-sucessfull",
            ];

        } else {
            if($charge['error'])
            {
                $response = [
                    'status' => false,
                    'message' => $charge['error'],
                    'icon' => 'error',
                    'redirect_url' => "/card-payament",
                ];
            }
            else{
                $response = [
                    'status' => false,
                    'message' => 'Payment failed.',
                    'icon' => 'error',
                    'redirect_url' => "/card-payament",
                ];
            }
            
        }
        return response($response);
    }

    private function sendMail($email,$fileName,$charge , $invoice_no,$username)
    {
        $data["email"] = $email;
        $data['app_name']=env('APP_COMAPNY_NAME');
        $data["title"] = "Invoice  #".$invoice_no." for product due ".date('m-d-Y');
        $data["body"] ='<p>Hello,'.$username.' </p>
                        <p>
                            I hope you are well. Please see attached invoice number #'.$invoice_no.' for product or service, due on '.date('m-d-Y').'. Do not hesitate to reach out if you have any questions.
                        </p>';

        $files =public_path('files/'.$fileName);
  
        Mail::send('emails.sendBilling', $data, function($message)use($data, $files) {
            $message->to($data["email"], $data["email"])
                    ->subject($data["title"]);
            $message->attach($files);
            });

        // Remove File from Storege
        if(Storage::exists('public/files/'.$fileName)){
            Storage::delete('public/files/'.$fileName);
        }

        //Remove file from public folder
        $image_path = public_path('files/'.$fileName);;  // Value is not URL but directory file path
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
    }

    private function storePatmentDetails($charge,$request, $session_data)
    {
      
        $total_item=$request->total_item;
        $invoice_no=date('Ymdhi');
        $item_arrays=[];
        for($i=1; $i<=sizeof($session_data['amount']); $i++)
        {
            $no=$i-1;
           $description= $session_data['description'][$no];
           $quantity= $session_data['quantity'][$no];
           $rate= $session_data['rate'][$no];
           $amount= $session_data['amount'][$no];
                array_push($item_arrays,[
                    'description'=> $description,
                    'quntity'=> $quantity,
                    'rate'=>  $rate,
                    'amount'=>  $amount,
                ]);
       
        }
        $result = PayamentDetail::updateOrCreate(
                 [
                    'id' => 0,
                ],
                [
                    'name'=>$session_data['username'],
                    'email'=>$session_data['email'],
                    'address'=>$session_data['billing_address'],
                    'mobile'=>$session_data['mobile'],
                    'billing_date'=>date('Y-m-d'),
                    'payment_id'=> $charge['id'],
                    'transaction_id'=>$charge['balance_transaction'],
                    'currency'=>$charge['currency'],
                    'amount'=> round($session_data['grand_total']),
                    'description'=>$charge['description'],
                    'response_json'=> json_encode($charge),
                    'item_details_json'=>json_encode($item_arrays),
                ]);

        $result->update([ 'invoice_no'=>date('Ymdhi').$result->id]);
        $data['item_arrays']=$item_arrays;
        $data['result']=$result;
        return $data;
    }

    private function generatePDF($item_array, $data ,$invoice_no)
    {
        
        $pdf = PDF::loadView('pdf.invoice', compact('item_array','data'));

        $content = $pdf->download()->getOriginalContent();
        $name= $invoice_no.'.pdf';
        Storage::put('public/files/'.$name,$content);

        $storage_path = Storage::path('public/files/') . $name;
		$public_path = public_path('files/') . $name;

		File::move($storage_path, $public_path);

        return $name;
    }
    private function createToken($cardData)
    {
        $token = null;
        try {
            $token = $this->stripe->tokens->create([
                'card' => [
                    'number' => $cardData['cardNumber'],
                    'exp_month' => $cardData['month'],
                    'exp_year' => $cardData['year'],
                    'cvc' => $cardData['cvv']
                ]
            ]);
        } catch (CardException $e) {
            $token['error'] = $e->getError()->message;
        } catch (Exception $e) {
            $token['error'] = $e->getMessage();
        }
        return $token;
    }

    private function createCharge($tokenId, $amount)
    {
        $charge = null;
        try {
            $charge = $this->stripe->charges->create([
                'amount' => $amount,
                'currency' => "usd",
                'source' => $tokenId,
                'description' => 'Bill payment'
            ]);
        } catch (Exception $e) {
            $charge['error'] = $e->getMessage();
        }
        return $charge;
    }

    public function paymentSucessfully()
    {
        $data=Session::get('billingArray');
        if(empty($data))
        {
            return response()->redirectTo('/');
        }
       session()->put('billingArray', '');
        return view('payment_sucessfully');
    }
}
