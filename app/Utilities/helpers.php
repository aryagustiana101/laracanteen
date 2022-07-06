<?php

use App\Jobs\GenerateQrCodePaymentJob;
use App\Jobs\GenerateQrCodeTransactionJob;
use App\Models\Store;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Models\Status;
use App\Models\Tracker;
use App\Models\OrderHeader;
use App\Models\PaymentGroup;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

if (!function_exists('reduceOrderPrice')) {
    function reduceOrderPrice($order): array
    {
        $paymentGroup = PaymentGroup::where('id', $order->payment_group_id)->first();
        return [
            'total_price' => (int)$paymentGroup->total_price - (int)$order->total_price,
            'total_price_rounded' => (int)$paymentGroup->total_price_rounded - (int)$order->total_price_rounded,
            'total_admin_price' => (int)$paymentGroup->total_admin_price - (int)$order->total_admin_price,
            'total_admin_price_rounded' => (int)$paymentGroup->total_admin_price_rounded - (int)$order->total_admin_price_rounded,
            'total_tax_price' => (int)$paymentGroup->total_tax_price - (int)$order->total_tax_price,
            'total_tax_price_rounded' => (int)$paymentGroup->total_tax_price_rounded - (int)$order->total_tax_price_rounded,
            'total_user_price' => (int)$paymentGroup->total_user_price - (int)$order->total_user_price,
            'total_user_price_rounded' => (int)$paymentGroup->total_user_price_rounded - (int)$order->total_user_price_rounded,
            'total_items' => (int)$paymentGroup->total_items - (int)$order->total_items,
            'total_reduced_price' => (int)$paymentGroup->total_reduced_price + (int)$order->total_price,
            'total_reduced_price_rounded' => (int)$paymentGroup->total_reduced_price_rounded + (int)$order->total_price_rounded,
            'total_reduced_admin_price' => (int)$paymentGroup->total_reduced_admin_price + (int)$order->total_admin_price,
            'total_reduced_admin_price_rounded' => (int)$paymentGroup->total_reduced_admin_price_rounded + (int)$order->total_admin_price_rounded,
            'total_reduced_tax_price' => (int)$paymentGroup->total_reduced_tax_price + (int)$order->total_tax_price,
            'total_reduced_tax_price_rounded' => (int)$paymentGroup->total_reduced_tax_price_rounded + (int)$order->total_tax_price_rounded,
            'total_reduced_user_price' => (int)$paymentGroup->total_reduced_user_price + (int)$order->total_user_price,
            'total_reduced_user_price_rounded' => (int)$paymentGroup->total_reduced_user_price_rounded + (int)$order->total_user_price_rounded,
            'total_reduced_items' => (int)$paymentGroup->total_reduced_items + (int)$order->total_items
        ];
    }
}

if (!function_exists('incomingOrderCheck')) {
    function incomingOrderCheck($order)
    {
        $takeTimeLimit = 2;
        $currentTimeStamp = Carbon::now()->timestamp;
        $latestTracker = $order->trackers->sortDesc()->first();
        if ($currentTimeStamp > $latestTracker->created_at->addMinutes($takeTimeLimit)->timestamp) {
            PaymentGroup::where('id', $order->payment_group_id)->update(reduceOrderPrice($order));
            Tracker::create([
                'status_id' => Status::where('position', 'second')->where('result', 'failed-seller')->first()->id,
                'order_header_id' => $order->id,
            ]);
            Tracker::create([
                'status_id' => Status::where('position', 'end')->where('result', 'failed-seller')->first()->id,
                'order_header_id' => $order->id,
            ]);
            OrderHeader::where('id', $order->id)->update(['status_id' => Status::where('position', 'end')->where('result', 'failed-seller')->first()->id]);
        }
    }
}

if (!function_exists('prepareOrderCheck')) {
    function prepareOrderCheck($order)
    {
        $prepareTimeLimit = 60;
        $currentTimeStamp = Carbon::now()->timestamp;
        $latestTracker = $order->trackers->sortDesc()->first();
        if ($currentTimeStamp > $latestTracker->created_at->addMinutes($prepareTimeLimit)->timestamp) {
            PaymentGroup::where('id', $order->payment_group_id)->update(reduceOrderPrice($order));
            Tracker::create([
                'status_id' => Status::where('position', 'fourth')->where('result', 'failed-seller')->first()->id,
                'order_header_id' => $order->id,
            ]);
            Tracker::create([
                'status_id' => Status::where('position', 'end')->where('result', 'failed-seller')->first()->id,
                'order_header_id' => $order->id,
            ]);
            OrderHeader::where('id', $order->id)->update(['status_id' => Status::where('position', 'end')->where('result', 'failed-seller')->first()->id]);
        }
    }
}

if (!function_exists('readyToPayOrderCheck')) {
    function readyToPayOrderCheck($order)
    {
        $paymentGroup = PaymentGroup::with(['orderHeaders'])->where('id', $order->payment_group_id)->first();
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
                continue;
            }
            if ($ordersStatusCheck->status_id == $prepareStatus->id) {
                prepareOrderCheck($ordersStatusCheck);
                $orders['prepare_order'] += [$i - ($i - count($orders['prepare_order'])) => $ordersStatusCheck];
                continue;
            }
            if ($ordersStatusCheck->status_id == $readyPayStatus->id) {
                $orders['ready_pay_order'] += [$i - ($i - count($orders['ready_pay_order'])) => $ordersStatusCheck];
                continue;
            }
            if ($ordersStatusCheck->status_id == $readyTakeStatus->id) {
                $orders['ready_take_order'] += [$i - ($i - count($orders['ready_take_order'])) => $ordersStatusCheck];
                continue;
            }
            if ($ordersStatusCheck->status_id == $cancelUserStatus->id) {
                $orders['order_canceled_by_user'] += [$i - ($i - count($orders['order_canceled_by_user'])) => $ordersStatusCheck];
                continue;
            }
            if ($ordersStatusCheck->status_id == $cancelStoreStatus->id) {
                $orders['order_canceled_by_seller'] += [$i - ($i - count($orders['order_canceled_by_seller'])) => $ordersStatusCheck];
            }
        }

        $orderHeaderAmountReduced = $orderHeaderAmount - (count($orders['order_canceled_by_user']) + count($orders['order_canceled_by_seller']));
        if (count($orders['ready_pay_order']) != $orderHeaderAmountReduced) {
            $takeTimeLimit = 2;
            $prepareTimeLimit = 60;
            $payTimeLimit = 30 + 1;
            $currentTimeStamp = Carbon::now()->timestamp;
            $latestTracker = $order->trackers->sortDesc()->first();
            if ($currentTimeStamp > $latestTracker->created_at->addMinutes($payTimeLimit + ($takeTimeLimit + $prepareTimeLimit))->timestamp) {
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
        }

        $payTimeLimit = 30;
        $currentTimeStamp = Carbon::now()->timestamp;
        $latestTracker = Tracker::where('status_id', $readyPayStatus->id)->where('order_header_id', $orders['ready_pay_order'][(count($orders['ready_pay_order']) - 1)]->id)->first();
        $timeLimitToPay = $latestTracker->created_at->addMinutes(($payTimeLimit))->timestamp;
        if ($currentTimeStamp > $timeLimitToPay) {
            for ($i = 1; $i <= count($orders['ready_pay_order']); $i++) {
                PaymentGroup::where('id', $order->payment_group_id)->update(reduceOrderPrice($orders['ready_pay_order'][$i - 1]));
                Tracker::create([
                    'status_id' => Status::where('position', 'sixth')->where('result', 'failed-user')->first()->id,
                    'order_header_id' => $orders['ready_pay_order'][$i - 1]->id,
                ]);
                Tracker::create([
                    'status_id' => Status::where('position', 'end')->where('result', 'failed-user')->first()->id,
                    'order_header_id' => $orders['ready_pay_order'][$i - 1]->id,
                ]);
                OrderHeader::where('id', $orders['ready_pay_order'][$i - 1]->id)->update(['status_id' => Status::where('position', 'end')->where('result', 'failed-user')->first()->id]);
            }
        }
    }
}

if (!function_exists('readyToTakeOrderCheck')) {
    function readyToTakeOrderCheck($order)
    {
        $giveTimeLimit = 30;
        $currentTimeStamp = Carbon::now()->timestamp;
        $latestTracker = $order->trackers->sortDesc()->first();
        if ($currentTimeStamp > $latestTracker->created_at->addMinutes($giveTimeLimit)->timestamp) {
            $userOrder = PaymentGroup::filter(['id' => $order->payment_group_id])->first()->user;
            $retrievalDetails = ($userOrder->student ?? false) ? 'Asumsi diambil oleh peserta didik dengan nama ' . $userOrder->student->name : 'Asumsi diambil oleh guru dengan nama ' . $userOrder->teacher->name;
            Tracker::create([
                'status_id' => Status::where('position', 'eighth')->where('result', 'failed-seller')->first()->id,
                'order_header_id' => $order->id,
            ]);
            Tracker::create([
                'status_id' => Status::where('position', 'end')->where('result', 'success-seller')->first()->id,
                'order_header_id' => $order->id,
            ]);
            OrderHeader::where('id', $order->id)->update([
                'retrieval_time' => Carbon::now(),
                'retrieval_details' => $retrievalDetails,
                'status_id' => Status::where('position', 'end')->where('result', 'success-seller')->first()->id
            ]);
        }
    }
}

if (!function_exists('createInitials')) {
    function createInitials($name): string
    {
        $words = preg_split("/\s+/", $name);
        $initials = "";
        foreach ($words as $w) {
            $initials .= $w[0];
        }
        if (strlen($initials) <= 2) {
            $initials .= substr($initials, -1);
        }
        if (strlen($initials) > 3) {
            $initials =  substr($initials, 0, (3 - strlen($initials)));
        }
        $checkUniqueInitials = Store::where('initials', $initials)->first();
        if ($checkUniqueInitials) {
            $initials = substr($initials, 0, -1) . chr(rand(65, 90));
        }
        return $initials;
    }
}

if (!function_exists('checkFirstTransaction')) {
    function checkFirstTransaction()
    {
        $checkFirstTransaction = Transaction::filter([
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->format('Y-m-d')
        ])->orderBy('id', 'asc')->first();
        if (!$checkFirstTransaction) {
            $storeIds = Store::get('id');
            $storeInitials = Store::get('initials');
            for ($i = 1; $i <= $storeIds->count(); $i++){
                $transactionCode = $storeInitials[$i - 1]->initials . strtoupper(substr(uniqid(), 0, -1));
                $transactionImageName = uniqid() . '.png';
                Transaction::create([
                    'store_id' => $storeIds[$i - 1]->id,
                    'transaction_code' => $transactionCode,
                    'total_income' => 0,
                    'total_income_rounded' => 0,
                    'total_admin_income' => 0,
                    'total_admin_income_rounded' => 0,
                    'total_tax_income' => 0,
                    'total_tax_income_rounded' => 0,
                    'total_user_income' => 0,
                    'total_user_income_rounded' => 0,
                    'total_items' => 0,
                    'withdraw_status' => false,
                    'withdraw_time' => null,
                    'withdraw_details' => null,
                    'image' => $transactionImageName
                ]);
                GenerateQrCodeTransactionJob::dispatch($transactionCode, $transactionImageName);
            }
        }
    }
}
