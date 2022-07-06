<?php

namespace App\Http\Controllers\Api;

use App\Jobs\GenerateQrCodePaymentJob;
use App\Models\Product;
use App\Models\OrderHeader;
use App\Models\PaymentGroup;
use App\Models\Setting;
use App\Models\Store;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use App\Models\Status;
use App\Models\Tracker;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $inStatus = ['masuk', 'dibatalkan', 'ditolak', 'disiapkan', 'gagal_disiapkan', 'siap_dibayar', 'tidak_dibayar', 'sudah_dibayar', 'siap_diambil', 'sudah_diambil', 'batal', 'selesai'];
        $inOrder = ['asc', 'desc'];

        $validated = $request->validate([
            'status' => [Rule::in($inStatus)],
            'order' => [Rule::in($inOrder)],
            'start_date' => ['date'],
            'end_date' => ['date']
        ]);

        $statusPositionResults = [
            'masuk' => ['first', 'success-user'],
            'dibatalkan' => ['second', 'failed-user'],
            'ditolak' => ['second', 'failed-seller'],
            'disiapkan' => ['third', 'success-seller'],
            'gagal_disiapkan' => ['fourth', 'failed-seller'],
            'siap_dibayar' => ['fifth', 'success-seller'],
            'tidak_dibayar' => ['sixth', 'failed-user'],
            'sudah_dibayar' => ['seventh', 'success-user'],
            'siap_diambil' => ['seventh', 'success-seller'],
            'sudah_diambil' => ['end', 'success-seller'],
            'batal' => ['end', 'failed-user|failed-seller'],
            'selesai' => ['end', 'success-seller']
        ];

        $filters = [];
        if ($request->user()->student || $request->user()->teacher) {
            $filters += ['user_id' => $request->user()->id];
        }
        if ($request->user()->seller) {
            $filters += ['store_id' => $request->user()->seller->store->id];
        }

        if ($validated['status'] ?? false) {
            $statusPosition = Status::where('position', $statusPositionResults[$validated['status']][0]);
            $statusResult = explode('|', $statusPositionResults[$validated['status']][1]);
            $status = (count($statusResult) == 1) ? $statusPosition->where('result', $statusPositionResults[$validated['status']][1])->first() : $statusPosition->whereIn('result', [$statusResult[0], $statusResult[1]])->get();
            if (substr($statusResult[0], 0, 6) == 'failed') {
                $filters += ['status_failed' => ($status->count() == 2) ? [$status[0]->id, $status[1]->id] : [$status->id]];
            } else {
                $filters += ['status_success' => $status->id];
            }
        }

        $filters += ['order' => ($validated['order'] ?? false) ? $validated['order'] : 'desc'];
        if ($validated['start_date'] ?? false) {
            $filters += ['start_date' => $validated['start_date'] ?? false];
        }
        if ($validated['end_date'] ?? false) {
            $filters += ['end_date' => $validated['end_date'] ?? false];
        }

        $code = 200;
        $response = [
            'code' => $code,
            'status' => 'OK',
        ];

        $orders = OrderHeader::filter($filters)->get();
        $response['data'] = ['orders' => $orders];
        return response()->json($response, $code);
    }

    public function show(Request $request, $order_code)
    {
        $code = 200;
        $response = [
            'code' => $code,
            'status' => 'OK'
        ];

        $filters = [];
        if ($request->user()->student || $request->user()->teacher) {
            $filters += ['user_id' => $request->user()->id];
        }
        if ($request->user()->seller) {
            $filters += ['store_id' => $request->user()->seller->store->id];
        }
        $filters += ['order_code' => $order_code];

        $order = OrderHeader::filter($filters)->first();
        if (!$order) {
            $code = 404;
            $response['code'] = $code;
            $response['status'] = 'NOT FOUND';
            $response['data'] = [
                'message' => 'Pesanan tidak ditemukan.'
            ];
            return response()->json($response, $code);
        }
        $response['data'] = ['order' => $order];
        return response()->json($response, $code);
    }

    public function store(Request $request)
    {
        $code = 201;
        $response = [
            'code' => $code,
            'status' => 'CREATED'
        ];

        // $openTransaction = Setting::where('name', 'Open Transaction')->first();
        $openTransaction = 1;
        if ((int)$openTransaction != 1) {
            $code = 422;
            $response['code'] = $code;
            $response['status'] = 'UNPROCESSABLE CONTENT';
            $response['data'] = [
                'message' => 'Transaksi belum dibuka.'
            ];
            return response()->json($response, $code);
        }

        $validated = $request->validate([
            'items' => 'required'
        ]);

        $items = collect($validated['items'])->sortBy('product_id');
        $stores = [];
        $products = [];
        $messages = [
            'product_not_found' => [],
            'store_not_open' => [],
            'product_availability_status' => [],
            'total_items_over_limit' => [],
            'total_price_over_limit' => [],
        ];
        for ($i = 1; $i <= $items->count(); $i++) {
            $product = Product::where('id', $items[$i - 1]['product_id'])->first();
            if (!$product) {
                $messages['product_not_found'] += [$i - ($i - count($messages['product_not_found'])) => 'Produk dengan id ' . $items[$i - 1]['product_id'] . ' tidak ditemukan.'];
                continue;
            }
            if (!$product->store->open_status) {
                $messages['store_not_open'] +=
                    [$i - ($i - count($messages['store_not_open'])) => 'Toko dengan nama ' . $product['store']['name'] . ' yang terkait dengan produk ' . $product['name'] . ' belum buka.'];
                continue;
            }
            if (!$product->availability_status) {
                $messages['product_availability_status'] += [$i - ($i - count($messages['product_availability_status'])) => 'Produk dengan nama ' . $product['name'] . ' saat ini tidak tersedia.'];
                continue;
            }
            $stores += [$i - 1 => $product->store->toArray()];
            $products += [$i - 1 => $product->toArray()];
        }

        if (!$products) {
            $code = 422;
            $response['code'] = $code;
            $response['status'] = 'UNPROCESSABLE CONTENT';
            $response['data'] = [
                'message' => $messages
            ];
            return response()->json($response, $code);
        }

        $stores = collect($stores)->unique('id')->sort();
        $storeCount = $stores->count();
        $stores = $stores->values()->all();
        $products = collect($products)->groupBy('store_id');

        $total_payment_items = 0;
        $total_payment_price = 0;
        $total_payment_price_rounded = 0;
        $total_payment_admin_price = 0;
        $total_payment_admin_price_rounded = 0;
        $total_payment_tax_price = 0;
        $total_payment_tax_price_rounded = 0;
        $total_payment_user_price = 0;
        $total_payment_user_price_rounded = 0;
        for ($i = 1; $i <= $storeCount; $i++) {
            for ($j = 1; $j <= $products[$stores[$i - 1]['id']]->count(); $j++) {
                $total_payment_item = (int)$items->where('product_id', $products[$stores[$i - 1]['id']][$j - 1]['id'])->values()[0]['amount'];
                $total_payment_items += $total_payment_item;
                $total_payment_price += $total_payment_item * (int)$products[$stores[$i - 1]['id']][$j - 1]['price'];
                $total_payment_price_rounded += $total_payment_item * (int)$products[$stores[$i - 1]['id']][$j - 1]['price_rounded'];
                $total_payment_admin_price += $total_payment_item * (int)$products[$stores[$i - 1]['id']][$j - 1]['admin_price'];
                $total_payment_admin_price_rounded += $total_payment_item * (int)$products[$stores[$i - 1]['id']][$j - 1]['admin_price_rounded'];
                $total_payment_tax_price += $total_payment_item * (int)$products[$stores[$i - 1]['id']][$j - 1]['tax_price'];
                $total_payment_tax_price_rounded += $total_payment_item * (int)$products[$stores[$i - 1]['id']][$j - 1]['tax_price_rounded'];
                $total_payment_user_price += $total_payment_item * (int)$products[$stores[$i - 1]['id']][$j - 1]['user_price'];
                $total_payment_user_price_rounded += $total_payment_item * (int)$products[$stores[$i - 1]['id']][$j - 1]['user_price_rounded'];
                if ($total_payment_items > 40) {
                    $code = 422;
                    $response['code'] = $code;
                    $response['status'] = 'UNPROCESSABLE CONTENT';
                    $messages['total_items_over_limit'] += [0 => 'Total produk yang dibeli adalah ' . $total_payment_items . ' melebihi batas (40 produk).'];
                    $response['data'] = [
                        'message' => $messages
                    ];
                    return response()->json($response, $code);
                }
                if ($total_payment_user_price_rounded >= 150000) {
                    $code = 422;
                    $response['code'] = $code;
                    $response['status'] = 'UNPROCESSABLE CONTENT';
                    $messages['total_price_over_limit'] += [0 => 'Total harga yang harus dibayar adalah Rp. ' . sprintf(number_format($total_payment_user_price_rounded, 2)) . ' melebihi batas (Rp. 150,000.00).'];
                    $response['data'] = [
                        'message' => $messages
                    ];
                    return response()->json($response, $code);
                }
            }
        }

        $currentDate = date("Ymd");
        $lastPaymentGroup = PaymentGroup::orderBy('id', 'desc')->first();
        $paymentCode = '';
        if ($lastPaymentGroup) {
            $lastDate = substr($lastPaymentGroup->payment_code, 3, -4);
            $lastIncrement = substr($lastPaymentGroup->payment_code, -4);
            $newIncrement = ($currentDate == $lastDate) ? str_pad((int)$lastIncrement + 1, 4, 0, STR_PAD_LEFT) : str_pad(1, 4, 0, STR_PAD_LEFT);
            $paymentCode = 'PYQ' . $currentDate . $newIncrement;
            if (PaymentGroup::where('payment_code', $paymentCode)->count() != 0) {
                $newIncrement = str_pad((int)$newIncrement + 1, 4, 0, STR_PAD_LEFT);
                $paymentCode = 'PYQ' . $currentDate . $newIncrement;
            }
        } else {
            $increment = 1;
            $paymentCode = 'PYQ' . $currentDate . str_pad($increment, 4, 0, STR_PAD_LEFT);
            if (PaymentGroup::where('payment_code', $paymentCode)->count() != 0) {
                $paymentCode = 'PYQ' . $currentDate . str_pad($increment + 1, 4, 0, STR_PAD_LEFT);
            }
        }

        checkFirstTransaction();

        $imageName = uniqid() . '.png';
        PaymentGroup::create([
            'user_id' => $request->user()->id,
            'cashier_id' => null,
            'payment_code' => $paymentCode,
            'total_price' => $total_payment_price,
            'total_price_rounded' => $total_payment_price_rounded,
            'total_admin_price' => $total_payment_admin_price,
            'total_admin_price_rounded' => $total_payment_admin_price_rounded,
            'total_tax_price' => $total_payment_tax_price,
            'total_tax_price_rounded' => $total_payment_tax_price_rounded,
            'total_user_price' => $total_payment_user_price,
            'total_user_price_rounded' => $total_payment_user_price_rounded,
            'total_items' => $total_payment_items,
            'total_reduced_price' => 0,
            'total_reduced_price_rounded' => 0,
            'total_reduced_admin_price' => 0,
            'total_reduced_admin_price_rounded' => 0,
            'total_reduced_tax_price' => 0,
            'total_reduced_tax_price_rounded' => 0,
            'total_reduced_user_price' => 0,
            'total_reduced_user_price_rounded' => 0,
            'total_reduced_items' => 0,
            'payment_status' => false,
            'payment_time' => null,
            'payment_details' => null,
            'image' => $imageName
        ]);
        GenerateQrCodePaymentJob::dispatch($paymentCode, $imageName);

        $currentPaymentGroup = PaymentGroup::where('payment_code', $paymentCode)->first();
        for ($i = 1; $i <= $storeCount; $i++) {
            $total_items = 0;
            $total_price = 0;
            $total_price_rounded = 0;
            $total_admin_price = 0;
            $total_admin_price_rounded = 0;
            $total_tax_price = 0;
            $total_tax_price_rounded = 0;
            $total_user_price = 0;
            $total_user_price_rounded = 0;

            $lastOrderHeader = OrderHeader::orderBy('id', 'desc')->first();
            if ($lastOrderHeader) {
                $lastDate = substr($lastOrderHeader->order_code, 3, -4);
                $lastIncrement = substr($lastOrderHeader->order_code, -4);
                $newIncrement = ($currentDate == $lastDate) ? str_pad((int)$lastIncrement + 1, 4, 0, STR_PAD_LEFT) : str_pad(1, 4, 0, STR_PAD_LEFT);
                $orderCode = $stores[$i - 1]['initials'] . $currentDate . str_pad($newIncrement, 4, 0, STR_PAD_LEFT);
                if (OrderHeader::where('order_code', $orderCode)->count() != 0) {
                    $newIncrement = str_pad((int)$newIncrement + 1, 4, 0, STR_PAD_LEFT);
                    $orderCode = $stores[$i - 1]['initials'] . $currentDate . str_pad($newIncrement, 4, 0, STR_PAD_LEFT);
                }
            } else {
                $increment = 1;
                $orderCode = $stores[$i - 1]['initials'] . $currentDate . str_pad($increment, 4, 0, STR_PAD_LEFT);
                if (OrderHeader::where('order_code', $orderCode)->count() != 0) {
                    $orderCode = $stores[$i - 1]['initials'] . $currentDate . str_pad($increment + 1, 4, 0, STR_PAD_LEFT);
                }
            }

            for ($j = 1; $j <= $products[$stores[$i - 1]['id']]->count(); $j++) {
                $total_item = (int)$items->where('product_id', $products[$stores[$i - 1]['id']][$j - 1]['id'])->values()[0]['amount'];
                $total_items += $total_item;
                $total_price += $total_item * (int)$products[$stores[$i - 1]['id']][$j - 1]['price'];
                $total_price_rounded += $total_item * (int)$products[$stores[$i - 1]['id']][$j - 1]['price_rounded'];
                $total_admin_price += $total_item * (int)$products[$stores[$i - 1]['id']][$j - 1]['admin_price'];
                $total_admin_price_rounded += $total_item * (int)$products[$stores[$i - 1]['id']][$j - 1]['admin_price_rounded'];
                $total_tax_price += $total_item * (int)$products[$stores[$i - 1]['id']][$j - 1]['tax_price'];
                $total_tax_price_rounded += $total_item * (int)$products[$stores[$i - 1]['id']][$j - 1]['tax_price_rounded'];
                $total_user_price += $total_item * (int)$products[$stores[$i - 1]['id']][$j - 1]['user_price'];
                $total_user_price_rounded += $total_item * (int)$products[$stores[$i - 1]['id']][$j - 1]['user_price_rounded'];
            }

            $currentTransaction = Transaction::filter([
                'store_id' => $stores[$i - 1]['id'],
                'start_date' => Carbon::now()->format('Y-m-d'),
                'end_date' => Carbon::now()->format('Y-m-d')
            ])->orderBy('id', 'desc')->first();
            $status = Status::where('position', 'first')->where('result', 'success-user')->first();
            OrderHeader::create([
                'transaction_id' => $currentTransaction->id,
                'payment_group_id' => $currentPaymentGroup->id,
                'store_id' => $stores[$i - 1]['id'],
                'status_id' => $status->id,
                'order_code' => $orderCode,
                'total_price' => $total_price,
                'total_price_rounded' => $total_price_rounded,
                'total_admin_price' => $total_admin_price,
                'total_admin_price_rounded' => $total_admin_price_rounded,
                'total_tax_price' => $total_tax_price,
                'total_tax_price_rounded' => $total_tax_price_rounded,
                'total_user_price' => $total_user_price,
                'total_user_price_rounded' => $total_user_price_rounded,
                'total_items' => $total_items,
                'retrieval_time' => null,
                'retrieval_details' => null
            ]);

            $currentOrderHeader = OrderHeader::where('order_code', $orderCode)->first();
            Tracker::create([
                'status_id' => $status->id,
                'order_header_id' => $currentOrderHeader->id,
            ]);

            for ($j = 1; $j <= $products[$stores[$i - 1]['id']]->count(); $j++) {
                OrderDetail::create([
                    'order_header_id' => $currentOrderHeader->id,
                    'product_id' => $products[$stores[$i - 1]['id']][$j - 1]['id'],
                    'amount' => $items->where('product_id', $products[$stores[$i - 1]['id']][$j - 1]['id'])->values()[0]['amount'],
                    'name' => $products[$stores[$i - 1]['id']][$j - 1]['name'],
                    'description' => $products[$stores[$i - 1]['id']][$j - 1]['description'],
                    'price' => $products[$stores[$i - 1]['id']][$j - 1]['price'],
                    'price_rounded' => $products[$stores[$i - 1]['id']][$j - 1]['price_rounded'],
                    'admin_price' => $products[$stores[$i - 1]['id']][$j - 1]['admin_price'],
                    'admin_price_rounded' => $products[$stores[$i - 1]['id']][$j - 1]['admin_price_rounded'],
                    'tax_price' => $products[$stores[$i - 1]['id']][$j - 1]['tax_price'],
                    'tax_price_rounded' => $products[$stores[$i - 1]['id']][$j - 1]['tax_price_rounded'],
                    'user_price' => $products[$stores[$i - 1]['id']][$j - 1]['user_price'],
                    'user_price_rounded' => $products[$stores[$i - 1]['id']][$j - 1]['user_price_rounded'],
                    'customer_notes' => $items->where('product_id', $products[$stores[$i - 1]['id']][$j - 1]['id'])->values()[0]['customer_notes']
                ]);
            }
        }

        $response['data'] = [
            'message' => $messages
        ];
        return response()->json($response, $code);
    }

    public function take(Request $request, $order_code)
    {
        $code = 200;
        $response = [
            'code' => $code,
            'status' => 'OK',
            'data' => []
        ];

        $filters = [];
        $filters += ['store_id' => $request->user()->seller->store->id];
        $filters += ['order_code' => $order_code];

        $order = OrderHeader::filter($filters)->first();
        if (!$order) {
            $code = 404;
            $response['code'] = $code;
            $response['status'] = 'NOT FOUND';
            $response['data'] = [
                'message' => 'Pesanan dari kode tidak dapat ditemukan.'
            ];
            return response()->json($response, $code);
        }

        $latestTracker = $order->trackers->sortDesc()->first();
        $currentStatus = $order->status;
        $incomingStatus = Status::where('position', 'first')->where('result', 'success-user')->first();
        if ($latestTracker->status_id != $incomingStatus->id || $currentStatus->id != $incomingStatus->id) {
            $code = 422;
            $response['code'] = $code;
            $response['status'] = 'UNPROCESSABLE CONTENT';
            $response['data'] = [
                'message' => 'Pesanan dari kode tidak dapat diambil.'
            ];
            return response()->json($response, $code);
        }

        $currentTimeStamp = Carbon::now()->timestamp;
        $takeTimeStampLimit = $latestTracker->created_at->addMinutes(2)->timestamp;
        if ($currentTimeStamp > $takeTimeStampLimit) {
            incomingOrderCheck($order);
            $code = 422;
            $response['code'] = $code;
            $response['status'] = 'UNPROCESSABLE CONTENT';
            $response['data'] = ['message' => 'Pesanan tidak bisa diambil karena melebihi batas waktu pengambilan dari pesanan dibuat (2 menit).'];
            return response()->json($response, $code);
        }

        Tracker::create([
            'status_id' => Status::where('position', 'second')->where('result', 'success-seller')->first()->id,
            'order_header_id' => $order->id
        ]);
        Tracker::create([
            'status_id' => Status::where('position', 'third')->where('result', 'success-seller')->first()->id,
            'order_header_id' => $order->id
        ]);
        OrderHeader::where('id', $order->id)->update(['status_id' => Status::where('position', 'third')->where('result', 'success-seller')->first()->id]);
        return response()->json($response, $code);
    }

    public function cancel(Request $request, $order_code)
    {
        $code = 200;
        $response = [
            'code' => $code,
            'status' => 'OK',
            'data' => []
        ];

        $filters = [];
        $filters += ['user_id' => $request->user()->id];
        $filters += ['order_code' => $order_code];
        $order = OrderHeader::filter($filters)->first();
        if (!$order) {
            $code = 404;
            $response['code'] = $code;
            $response['status'] = 'NOT FOUND';
            $response['data'] = [
                'message' => 'Pesanan dari kode tidak dapat ditemukan.'
            ];
            return response()->json($response, $code);
        }

        $latestTracker = $order->trackers->sortDesc()->first();
        $currentStatus = $order->status;
        $incomingStatus = Status::where('position', 'first')->where('result', 'success-user')->first();
        if ($latestTracker->status_id != $incomingStatus->id || $currentStatus->id != $incomingStatus->id) {
            $code = 422;
            $response['code'] = $code;
            $response['status'] = 'UNPROCESSABLE CONTENT';
            $response['data'] = [
                'message' => 'Pesanan dari kode tidak dapat dibatalkan.'
            ];
            return response()->json($response, $code);
        }

        $currentTimeStamp = Carbon::now()->timestamp;
        $cancelTimeStampLimit = $latestTracker->created_at->addMinutes(2)->timestamp;
        if ($currentTimeStamp > $cancelTimeStampLimit) {
            incomingOrderCheck($order);
            $code = 422;
            $response['code'] = $code;
            $response['status'] = 'UNPROCESSABLE CONTENT';
            $response['data'] = ['message' => 'Pesanan tidak bisa dibatalkan karena melebihi batas waktu pembatalan dari pesanan dibuat (2 menit).'];
            return response()->json($response, $code);
        }

        PaymentGroup::where('id', $order->payment_group_id)->update(reduceOrderPrice($order));
        Tracker::create([
            'status_id' => Status::where('position', 'second')->where('result', 'failed-user')->first()->id,
            'order_header_id' => $order->id
        ]);
        Tracker::create([
            'status_id' => Status::where('position', 'end')->where('result', 'failed-user')->first()->id,
            'order_header_id' => $order->id
        ]);
        OrderHeader::where('id', $order->id)->update(['status_id' => Status::where('position', 'end')->where('result', 'failed-user')->first()->id]);
        return response()->json($response, $code);
    }

    public function decline(Request $request, $order_code)
    {
        $code = 200;
        $response = [
            'code' => $code,
            'status' => 'OK',
            'data' => []
        ];

        $filters = [];
        $filters += ['store_id' => $request->user()->seller->store->id];
        $filters += ['order_code' => $order_code];
        $order = OrderHeader::filter($filters)->first();
        if (!$order) {
            $code = 404;
            $response['code'] = $code;
            $response['status'] = 'NOT FOUND';
            $response['data'] = [
                'message' => 'Pesanan dari kode tidak dapat ditemukan.'
            ];
            return response()->json($response, $code);
        }

        $latestTracker = $order->trackers->sortDesc()->first();
        $currentStatus = $order->status;
        $incomingStatus = Status::where('position', 'first')->where('result', 'success-user')->first();
        if ($latestTracker->status_id != $incomingStatus->id || $currentStatus->id != $incomingStatus->id) {
            $code = 422;
            $response['code'] = $code;
            $response['status'] = 'UNPROCESSABLE CONTENT';
            $response['data'] = [
                'message' => 'Pesanan dari kode tidak dapat ditolak.'
            ];
            return response()->json($response, $code);
        }

        $currentTimeStamp = Carbon::now()->timestamp;
        $declineTimeStampLimit = $latestTracker->created_at->addMinutes(10)->timestamp;
        if ($currentTimeStamp > $declineTimeStampLimit) {
            incomingOrderCheck($order);
            $code = 422;
            $response['code'] = $code;
            $response['status'] = 'UNPROCESSABLE CONTENT';
            $response['data'] = ['message' => 'Pesanan tidak bisa diambil karena melebihi batas waktu penolakan dari pesanan dibuat (2 menit).'];
            return response()->json($response, $code);
        }

        PaymentGroup::where('id', $order->payment_group_id)->update(reduceOrderPrice($order));
        Tracker::create([
            'status_id' => Status::where('position', 'second')->where('result', 'failed-seller')->first()->id,
            'order_header_id' => $order->id
        ]);
        Tracker::create([
            'status_id' => Status::where('position', 'end')->where('result', 'failed-seller')->first()->id,
            'order_header_id' => $order->id
        ]);
        OrderHeader::where('id', $order->id)->update(['status_id' => Status::where('position', 'end')->where('result', 'failed-seller')->first()->id]);
        return response()->json($response, $code);
    }

    public function ready(Request $request, $order_code)
    {
        $code = 200;
        $response = [
            'code' => $code,
            'status' => 'OK',
            'data' => []
        ];

        $filters = [];
        $filters += ['store_id' => $request->user()->seller->store->id];
        $filters += ['order_code' => $order_code];
        $order = OrderHeader::filter($filters)->first();
        if (!$order) {
            $code = 404;
            $response['code'] = $code;
            $response['status'] = 'NOT FOUND';
            $response['data'] = [
                'message' => 'Pesanan dari kode tidak dapat ditemukan.'
            ];
            return response()->json($response, $code);
        }

        $latestTracker = $order->trackers->sortDesc()->first();
        $currentStatus = $order->status;
        $prepareStatus = Status::where('position', 'third')->where('result', 'success-seller')->first();
        if ($latestTracker->status_id != $prepareStatus->id || $currentStatus->id != $prepareStatus->id) {
            $code = 422;
            $response['code'] = $code;
            $response['status'] = 'UNPROCESSABLE CONTENT';
            $response['data'] = [
                'message' => 'Pesanan dari kode tidak dapat disiapkan untuk diambil.'
            ];
            return response()->json($response, $code);
        }

        $currentTimeStamp = Carbon::now()->timestamp;
        $prepareTimeStampLimit = $latestTracker->created_at->addMinutes(60)->timestamp;
        if ($currentTimeStamp > $prepareTimeStampLimit) {
            prepareOrderCheck($order);
            $code = 422;
            $response['code'] = $code;
            $response['status'] = 'UNPROCESSABLE CONTENT';
            $response['data'] = ['message' => 'Pesanan tidak bisa disiapkan untuk diambil karena melebihi batas waktu untuk menyiapkan pesanan dari saat pesanan dibuat (1 jam).'];
            return response()->json($response, $code);
        }

        Tracker::create([
            'status_id' => Status::where('position', 'fourth')->where('result', 'success-seller')->first()->id,
            'order_header_id' => $order->id
        ]);
        Tracker::create([
            'status_id' => Status::where('position', 'fifth')->where('result', 'success-seller')->first()->id,
            'order_header_id' => $order->id
        ]);
        OrderHeader::where('id', $order->id)->update(['status_id' => Status::where('position', 'fifth')->where('result', 'success-seller')->first()->id]);
        return response()->json($response, $code);
    }

    public function give(Request $request, $order_code)
    {
        $validated = $request->validate([
            'retrieval_details' => ['required']
        ]);

        $code = 200;
        $response = [
            'code' => $code,
            'status' => 'OK',
            'data' => []
        ];

        $filters = [];
        $filters += ['store_id' => $request->user()->seller->store->id];
        $filters += ['order_code' => $order_code];
        $order = OrderHeader::filter($filters)->first();
        if (!$order) {
            $code = 404;
            $response['code'] = $code;
            $response['status'] = 'NOT FOUND';
            $response['data'] = [
                'message' => 'Pesanan dari kode tidak dapat ditemukan.'
            ];
            return response()->json($response, $code);
        }

        $latestTracker = $order->trackers->sortDesc()->first();
        $currentStatus = $order->status;
        $readyTakeStatus = Status::where('position', 'seventh')->where('result', 'success-seller')->first();
        if ($latestTracker->status_id != $readyTakeStatus->id || $currentStatus->id != $readyTakeStatus->id) {
            $code = 422;
            $response['code'] = $code;
            $response['status'] = 'UNPROCESSABLE CONTENT';
            $response['data'] = [
                'message' => 'Pesanan dari kode tidak dapat diberikan untuk diambil pengguna.'
            ];
            return response()->json($response, $code);
        }

        $currentTimeStamp = Carbon::now()->timestamp;
        $giveTimeStampLimit = $latestTracker->created_at->addMinutes(30)->timestamp;
        if ($currentTimeStamp > $giveTimeStampLimit) {
            readyToTakeOrderCheck($order);
            $code = 422;
            $response['code'] = $code;
            $response['status'] = 'UNPROCESSABLE CONTENT';
            $response['data'] = ['message' => 'Pesanan sudah diasumsikan diambil oleh pengguna terkait karena melebihi batas waktu pengambilan pesanan dari saat sudah dibayar (30 menit). Bila ada kesalahan hubungi teknisi.'];
            return response()->json($response, $code);
        }

        Tracker::create([
            'status_id' => Status::where('position', 'eighth')->where('result', 'success-seller')->first()->id,
            'order_header_id' => $order->id
        ]);
        Tracker::create([
            'status_id' => Status::where('position', 'end')->where('result', 'success-seller')->first()->id,
            'order_header_id' => $order->id
        ]);
        OrderHeader::where('id', $order->id)->update([
            'status_id' => Status::where('position', 'end')->where('result', 'success-seller')->first()->id,
            'retrieval_time' => Carbon::now(),
            'retrieval_details' => $validated['retrieval_details']
        ]);
        return response()->json($response, $code);
    }
}
