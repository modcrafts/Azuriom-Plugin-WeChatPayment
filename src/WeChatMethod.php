<?php

namespace Azuriom\Plugin\WeChatPayment;

use Azuriom\Plugin\Shop\Cart\Cart;
use Azuriom\Plugin\Shop\Models\Payment;
use Azuriom\Plugin\Shop\Payment\PaymentMethod;
use Azuriom\Support\QrCodeRenderer;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class WeChatMethod extends PaymentMethod
{
    /**
     * The payment method id name.
     *
     * @var string
     */
    protected $id = 'wechat-business';

    /**
     * The payment method display name.
     *
     * @var string
     */
    protected $name = '微信支付';

    public function startPayment(Cart $cart, float $amount, string $currency)
    {
        $payment = $this->createPayment($cart, $amount, $currency);

        $type = "wechat"; 
        $host = $this->gateway->data['host'];
        /*
        $param = urlencode(json_encode(array(
            "item_name" => $this->getPurchaseDescription($payment->id),
            "from" => 'Azuriom',
        )));
        */

        $sign = md5($payment->id."FishPort 付款".$type.$amount.route('shop.payments.notification', $this->id).route('shop.payments.success', $this->id).$this->gateway->data['secret']);
        
        $attributes = array(
            "out_trade_no" => $payment->id,
            "subject" => "FishPort 付款",
            "type" => $type,
            "total_amount" => $amount,
            "notify_url" => route('shop.payments.notification', $this->id),
            "return_url" => route('shop.payments.success', $this->id),
            "sign" => $sign,
        );

        $response = Http::asForm()->post($host."/createOrder.php", $attributes);
        //var_dump($response->getBody());
        if (! $response->successful() || $response['status'] != "success") {
            $this->logInvalid($response, 'Invalid init response'.$response);

            return $this->errorResponse();
        }

        if ($response['sign'] != md5($response['code_url'].$this->gateway->data['secret'])){
            $this->logInvalid($response, 'Invalid sign');

            return $this->errorResponse();
        }
        $payment->update(['status' => 'pending']);
        //print_r($response['message']);
        //return response("success");
        return view('wechatpayment::pay', [
            'logo' => $this->image(),
            'amount' => $amount,
            'orderid' => $payment->id,
            'description' => $this->getPurchaseDescription($payment->id),
            'qrCode' => new HtmlString(QrCodeRenderer::render(base64_decode($response['code_url']), 500, 0)),
            'returnUrl' => route('shop.payments.success', $this->id),
        ]);
        //return response(base64_decode($response['content']),200);
        //return redirect()->away($host.'/payPage/pay.html?'.Arr::query(["orderId"=>$response['data']['orderId']]));
    }

    public function notification(Request $request, ?string $rawPaymentId)
    {
        $_sign = md5($request['response'].$this->gateway->data['secret']);
        if($request->input('sign') !== $_sign){
            logger()->warning("[Shop] Invalid notification sign: {$request} ".$request['response']);
            return response()->json(['message' => 'Invalid sign']);
        }

        [
            'out_trade_no' => $payId,
            'transaction_id' => $orderId,
            'trade_state' => $status
        ] = json_decode(base64_decode($request['response']), true);
        
        /*if ($status === 'Expired') {
            $_sign = md5($orderId.$status.$this->gateway->data['secret']);
            if ($sign !== $_sign) {
                return response()->json('Invalid sign');
            }
            Payment::firstWhere('transaction_id',$orderId)->update(['status' => 'expired']);
            return response()->noContent();
        }*/
        
        

        $payment = Payment::findOrFail($payId);

        if (!$payment->isPending()) {
            return response("success")->header('Content-type','text/plain');
        }

        if ($status !== 'SUCCESS') {
            logger()->warning("[Shop] Invalid payment status for #{$payment->transaction_id}: {$status}");

            return $this->invalidPayment($payment, $orderId, 'Invalid status');
        }
        $payment->update(['transaction_id' => $orderId]);
        $this->processPayment($payment);
        return response("success")->header('Content-type','text/plain');
    }

    public function view()
    {
        return 'wechatpayment::admin.wechat-business';
    }

    public function payquery(Request $request, string $payId)
    {
        return (new \Symfony\Component\HttpFoundation\StreamedResponse(function () use($payId) {
            
            $times = 0;
            while (true) {
                if ($times >= 100 || connection_aborted()) return;
                
                $ts = microtime(true);
                //echo str_repeat("\n", 50000);
                
                $payment = Payment::findOrFail($payId);
                if ($payment->isCompleted()) {
                    echo "event: message\ndata: success\nid: $ts\n\n";
                    ob_flush();
                    flush();
                    return;
                } else {
                    echo "event: message\ndata: waiting\nid: $ts\n\n";
                }
                
                    // Laravel 中不加以下两个 flush 也能测试成功
                    ob_flush();
                    flush();
                ++$times;
                sleep(3);
            }
        }, 200, [
            "Content-Type" => "text/event-stream",
            "Cache-Control" => "no-cache",
            "Connection" => "Keep-Alive",
            "X-Accel-Buffering" => "no",
        ]))->send();
    }

    public function rules()
    {
        return [
            'host' => ['required', 'string'],
            'secret' => ['required', 'string'],
        ];
    }

    public function image()
    {
        return asset('plugins/wechatpayment/img/wechat-business.svg');
    }

    private function logInvalid(Response $response, string $message)
    {
        Log::warning("[Shop] AliPay - {$message} {$response->effectiveUri()} ({$response->status()}): {$response->json('msg')}");
    }
}
