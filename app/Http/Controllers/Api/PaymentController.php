<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderHeader;
use App\Models\PaymentGroup;
use App\Models\Product;
use App\Models\Status;
use App\Models\Tracker;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{

    public function show(Request $request, $payment_credentials)
    {
        $code = 200;
        $response = [
            'code' => $code,
            'status' => 'OK'
        ];

        $filters = [];
        if ($request->user()->student ?? false) {
            $filters += ['user_id' => $request->user()->id];
        }
        if ($request->user()->seller ?? false) {
            $filters += ['store_id' => $request->user()->seller->store->id];
        }
        if (is_numeric($payment_credentials)) {
            $filters += ['id' => $payment_credentials];
        } else {
            $filters += ['payment_code' => $payment_credentials];
        }

        $payment = PaymentGroup::filter($filters)->first();
        if (!$payment) {
            $code = 404;
            $response['code'] = $code;
            $response['status'] = 'NOT FOUND';
            $response['data'] = [
                'message' => 'Pembayaran pesanan dari kode tidak dapat ditemukan.'
            ];
            return response()->json($response, $code);
        }

        $response['data'] = ['payment' => $payment];
        return response()->json($response, $code);
    }

    public function pay(Request $request, $payment_code)
    {
        $validated = $request->validate([
            'amount_user_paid' => ['required', 'numeric'],
            'payment_details' => ['required']
        ]);

        $code = 200;
        $response = [
            'code' => $code,
            'status' => 'OK',
            'data' => []
        ];

        $paymentGroup = PaymentGroup::with(['orderHeaders'])->where('payment_code', $payment_code)->first();
        if (!$paymentGroup) {
            $code = 404;
            $response['code'] = $code;
            $response['status'] = 'NOT FOUND';
            $response['data'] = [
                'message' => 'Pembayaran pesanan dari kode tidak dapat ditemukan.'
            ];
            return response()->json($response, $code);
        }

        if ((int)$paymentGroup->total_user_price_rounded == 0) {
            $code = 422;
            $response['code'] = $code;
            $response['status'] = 'UNPROCESSABLE CONTENT';
            $response['data'] = [
                'message' => 'Pembayaran pesanan dari kode tidak dapat dilakukan, karena semua pesanan yang berkaitan sudah dibatalkan.'
            ];
            return response()->json($response, $code);
        }

        $messages = [];
        $orders = [
            'incoming_order' => [],
            'prepare_order' => [],
            'ready_pay_order' => [],
            'ready_take_order' => [],
            'order_canceled_by_user' => [],
            'order_canceled_by_seller' => []
        ];

        $incomingStatus = Status::where('position', 'first')->where('result', 'success-user')->first();
        $prepareStatus = Status::where('position', 'third')->where('result', 'success-seller')->first();
        $readyPayStatus = Status::where('position', 'fifth')->where('result', 'success-seller')->first();
        $readyTakeStatus = Status::where('position', 'seventh')->where('result', 'success-seller')->first();
        $cancelUserStatus = Status::where('position', 'end')->where('result', 'failed-user')->first();
        $cancelStoreStatus = Status::where('position', 'end')->where('result', 'failed-seller')->first();

        $orderHeaderAmount = $paymentGroup->orderHeaders->count();
        for ($i = 1; $i <= $orderHeaderAmount; $i++) {
            $ordersStatusCheck = $paymentGroup->orderHeaders[$i - 1];
            if ($ordersStatusCheck->status_id == $incomingStatus->id) {
                incomingOrderCheck($ordersStatusCheck);
                $orders['incoming_order'] += [$i - ($i - count($orders['incoming_order'])) => $ordersStatusCheck];
                $messages += [$i - ($i - count($messages)) => 'Pesanan dengan kode ' . $ordersStatusCheck->order_code . ' masih dalam status masuk sistem dan belum diterima toko.'];
                continue;
            }
            if ($ordersStatusCheck->status_id == $prepareStatus->id) {
                prepareOrderCheck($ordersStatusCheck);
                $orders['prepare_order'] += [$i - ($i - count($orders['prepare_order'])) => $ordersStatusCheck];
                $messages += [$i - ($i - count($messages)) => 'Pesanan dengan kode ' . $ordersStatusCheck->order_code . ' masih dalam status disiapkan toko.'];
                continue;
            }
            if ($ordersStatusCheck->status_id == $readyPayStatus->id) {
                $orders['ready_pay_order'] += [$i - ($i - count($orders['ready_pay_order'])) => $ordersStatusCheck];
                $messages += [$i - ($i - count($messages)) => 'Pesanan dengan kode ' . $ordersStatusCheck->order_code . ' sudah siap dibayar.'];
                continue;
            }
            if ($ordersStatusCheck->status_id == $readyTakeStatus->id) {
                $orders['ready_take_order'] += [$i - ($i - count($orders['ready_take_order'])) => $ordersStatusCheck];
                $messages += [$i - ($i - count($messages)) => 'Pesanan dengan kode ' . $ordersStatusCheck->order_code . ' sudah dalam status siap diambil.'];
                continue;
            }
            if ($ordersStatusCheck->status_id == $cancelUserStatus->id) {
                $orders['order_canceled_by_user'] += [$i - ($i - count($orders['order_canceled_by_user'])) => $ordersStatusCheck];
                $messages += [$i - ($i - count($messages)) => 'Pesanan dengan kode ' . $ordersStatusCheck->order_code . ' telah dibatalkan oleh pengguna dan sudah dikurangi dari jumlah pembayaran pesanan.'];
                continue;
            }
            if ($ordersStatusCheck->status_id == $cancelStoreStatus->id) {
                $orders['order_canceled_by_seller'] += [$i - ($i - count($orders['order_canceled_by_seller'])) =>
                    $ordersStatusCheck];
                $messages += [$i - ($i - count($messages)) => 'Pesanan dengan kode ' . $ordersStatusCheck->order_code . ' telah dibatalkan oleh toko dan sudah dikurangi dari jumlah pembayaran pesanan.'];
            }
        }

        $orderHeaderAmountReduced = $orderHeaderAmount - (count($orders['order_canceled_by_user']) + count($orders['order_canceled_by_seller']));

        if (count($orders['ready_pay_order']) != $orderHeaderAmountReduced) {
            $code = 422;
            $response['code'] = $code;
            $response['status'] = 'UNPROCESSABLE CONTENT';
            if (count($orders['incoming_order']) == $orderHeaderAmount) {
                $response['data'] = [
                    'message' => 'Pembayaran pesanan belum bisa dilakukan karena semua pesanan yang berkaitan dengan kode pembayaran masih dalam status masuk sistem dan belum diterima toko.'
                ];
                return response()->json($response, $code);
            }
            if (count($orders['prepare_order']) == $orderHeaderAmount) {
                $response['data'] = [
                    'message' => 'Pembayaran pesanan belum bisa dilakukan karena semua pesanan yang berkaitan dengan kode pembayaran masih dalam status disiapkan toko.'
                ];
                return response()->json($response, $code);
            }
            if (count($orders['ready_take_order']) == $orderHeaderAmount) {
                $response['data'] = [
                    'message' => 'Pembayaran pesanan dari kode sudah dalam status sudah dibayar oleh ' . $paymentGroup->payment_details . ' pada ' . $paymentGroup->payment_time . '. Silahkan sudah bisa diambil masing-masing di toko.'
                ];
                return response()->json($response, $code);
            }
            $response['data'] = [
                'messages' => $messages
            ];
            return response()->json($response, $code);
        }

        $payTimeLimit = 30;
        $currentTimeStamp = Carbon::now()->timestamp;
        $timeLimitToPay = collect($orders['ready_pay_order'])->sortByDesc('updated_at')->first()->trackers->sortDesc()->first()->created_at->addMinutes(($payTimeLimit))->timestamp;
        if ($currentTimeStamp > $timeLimitToPay) {
            for ($i = 1; $i <= count($orders['ready_pay_order']); $i++) {
                $order = $orders['ready_pay_order'][$i - 1];
                PaymentGroup::where('id', $order->payment_group_id)->update(reduceOrderPrice($order));
                Tracker::create([
                    'status_id' => Status::where('position', 'sixth')->where('result', 'failed-user')->first()->id,
                    'order_header_id' => $order->id,
                ]);
                Tracker::create([
                    'status_id' => Status::where('position', 'end')->where('result', 'failed-user')->first()->id,
                    'order_header_id' => $order->id,
                ]);
                OrderHeader::where('id', $order->id)->update(['status_id' => Status::where('position', 'end')->where('result', 'failed-user')->first()->id]);
            }
            $code = 422;
            $response['code'] = $code;
            $response['status'] = 'UNPROCESSABLE CONTENT';
            $response['data'] = ['message' => 'Pembayaran dari pesanan sudah tidak bisa diproses karena melebihi batas waktu pembayaran dari saat semua pesanan selesai disiapkan (30 menit).'];
            return response()->json($response, $code);
        }

        if ($validated['amount_user_paid'] < (int)$paymentGroup->total_user_price_rounded) {
            $code = 422;
            $response['code'] = $code;
            $response['status'] = 'UNPROCESSABLE CONTENT';
            $response['data'] = ['message' => 'Pembayaran dari pesanan tidak bisa diproses karena jumlah pembayaran yang dimasukan (Rp. ' . number_format($validated['amount_user_paid'], 2, ',', '.') . ') kurang dari total jumlah yang harus dibayar (Rp. ' . number_format($paymentGroup->total_user_price_rounded, 2, ',', '.') . ').'];
            return response()->json($response, $code);
        }

        for ($i = 1; $i <= count($orders['ready_pay_order']); $i++) {
            $order = $orders['ready_pay_order'][$i - 1];
            $transaction = Transaction::filter([
                'store_id' => $order->store_id,
                'start_date' => Carbon::now()->format('Y-m-d'),
                'end_date' => Carbon::now()->format('Y-m-d')
            ])->orderBy('id', 'desc')->first();
            Tracker::create([
                'status_id' => Status::where('position', 'sixth')->where('result', 'success-user')->first()->id,
                'order_header_id' => $order->id,
            ]);
            Tracker::create([
                'status_id' => Status::where('position', 'seventh')->where('result', 'success-seller')->first()->id,
                'order_header_id' => $order->id,
            ]);
            OrderHeader::where('id', $order->id)->update(['status_id' => Status::where('position', 'seventh')->where('result', 'success-seller')->first()->id]);
            Transaction::where('id', $order->store_id)->update([
                'total_income' => (int)$transaction->total_income + (int)$order->total_price,
                'total_income_rounded' => (int)$transaction->total_income_rounded + (int)$order->total_price_rounded,
                'total_admin_income' => (int)$transaction->total_admin_income + (int)$order->total_admin_price,
                'total_admin_income_rounded' => (int)$transaction->total_admin_income_rounded + (int)$order->total_admin_price_rounded,
                'total_tax_income' => (int)$transaction->total_tax_income + (int)$order->total_tax_price,
                'total_tax_income_rounded' => (int)$transaction->total_tax_income_rounded + (int)$order->total_tax_price_rounded,
                'total_user_income' => (int)$transaction->total_user_income + (int)$order->total_user_price,
                'total_user_income_rounded' => (int)$transaction->total_user_income_rounded + (int)$order->total_user_price_rounded,
                'total_items' => (int)$transaction->total_items + (int)$order->total_items,
            ]);
        }

        PaymentGroup::where('id', $paymentGroup->id)->update([
            'cashier_id' => $request->user()->teller->cashier->id,
            'amount_user_paid' => $validated['amount_user_paid'],
            'user_change' => $validated['amount_user_paid'] - (int)$paymentGroup->total_user_price_rounded,
            'payment_status' => true,
            'payment_time' => Carbon::now(),
            'payment_details' => $validated['payment_details']
        ]);

        return response()->json($response, $code);
    }

    public function image(Request $request, $image_name)
    {
        $filters = [];
        $filters += ['image' => $image_name];
        if ($request->user()->student ?? false) {
            $filters += ['user_id' => $request->user()->id];
        }
        if ($request->user()->seller) {
            $filters += ['store_id' => $request->user()->seller->store->id];
        }

        $paymentGroup = PaymentGroup::filter($filters)->first();
        if (!$paymentGroup && $image_name != 'default.png') {
            $code = 404;
            $response = [
                'code' => $code,
                'status' => 'NOT FOUND',
                'data' => [
                    'message' => 'Gambar kode QR pembayaran pesanan dengan nama ' . $image_name . ' tidak dapat ditemukan.'
                ]
            ];
            return response()->json($response, $code);
        }

        return response()->file('storage/qrcodes/paymentgroups/' . $image_name);
    }
}
