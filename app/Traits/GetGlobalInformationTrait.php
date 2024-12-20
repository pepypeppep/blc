<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Modules\BasicPayment\app\Models\BasicPayment;
use Modules\BasicPayment\app\Models\PaymentGateway;
use Modules\BasicPayment\app\Services\PaymentMethodService;

trait GetGlobalInformationTrait {
    // get basic payment gateway information
    public function get_basic_payment_info() {
        $basic_payment = Cache::rememberForever('basic_payment', function () {

            $payment_info = BasicPayment::get();

            $basic_payment = [];
            foreach ($payment_info as $payment_item) {
                $basic_payment[$payment_item->key] = $payment_item->value;
            }

            return (object) $basic_payment;
        });

        return $basic_payment;

    }

    // get addon payment gateway information
    public function get_payment_gateway_info() {
        $payment_setting = Cache::rememberForever('payment_setting', function () {

            $payment_info = PaymentGateway::get();

            $payment_setting = [];
            foreach ($payment_info as $payment_item) {
                $payment_setting[$payment_item->key] = $payment_item->value;
            }

            return (object) $payment_setting;
        });

        return $payment_setting;
    }
    private function getMultiCurrencyInfo() {
        $gateway_currency = allCurrencies()->where('currency_code', getSessionCurrency())->first();

        return [
            'currency_code' => $gateway_currency->currency_code,
            'country_code'  => $gateway_currency->country_code,
            'currency_rate' => $gateway_currency->currency_rate,
            'currency_id'   => $gateway_currency->id,
        ];
    }

    /**
     * @param $currencyId
     */
    private function getCurrencyDetails($currencyId) {
        $gateway_currency = allCurrencies()->where('id', $currencyId)->first();

        return [
            'currency_code' => $gateway_currency->currency_code,
            'country_code'  => $gateway_currency->country_code,
            'currency_rate' => $gateway_currency->currency_rate,
        ];
    }

    /**
     * @param $payable_amount
     * @param $gateway_name
     */
    public function calculate_payable_charge($payable_amount, $gateway_name) {
        $paymentService = app(PaymentMethodService::class);

        $paymentDetails = $paymentService->getGatewayDetails($gateway_name);

        $currencyId = $paymentDetails->currency_id ?? 1;
        $gateway_charge = $paymentDetails->charge;

        $currencyDetails = $this->getCurrencyDetails($currencyId);
        $currency_code = $currencyDetails['currency_code'];
        $country_code = $currencyDetails['country_code'];
        $currency_rate = $currencyDetails['currency_rate'];

        getSessionCurrency();

        if ($paymentService->isSupportsMultiCurrency($gateway_name) && session()->has('currency_code')) {
            $multiCurrencyInfo = $this->getMultiCurrencyInfo();
            $currency_code = $multiCurrencyInfo['currency_code'];
            $country_code = $multiCurrencyInfo['country_code'];
            $currency_rate = $multiCurrencyInfo['currency_rate'];
            $currency_id = $multiCurrencyInfo['currency_id'];
        }

        $payable_amount = $payable_amount * $currency_rate;
        $gateway_charge = $payable_amount * ($gateway_charge / 100);
        $payable_with_charge = $payable_amount + $gateway_charge;
        $payable_with_charge = sprintf('%0.2f', $payable_with_charge);

        session()->put('gateway_charge', $gateway_charge);
        session()->put('payable_currency', $currency_code);

        return (object) [
            'country_code'               => $country_code,
            'currency_code'              => $currency_code,
            'currency_id'                => $currency_id ?? $currencyId,
            'payable_amount'             => $payable_amount,
            'gateway_charge'             => $gateway_charge,
            'payable_with_charge'        => $payable_with_charge,
        ];
    }

    // mail configuraton setup
    private function set_mail_config() {
        $email_setting = Cache::get('setting');
        $mailConfig = [
            'transport'  => 'smtp',
            'host'       => $email_setting->mail_host,
            'port'       => $email_setting->mail_port,
            'encryption' => $email_setting->mail_encryption,
            'username'   => $email_setting->mail_username,
            'password'   => $email_setting->mail_password,
            'timeout'    => null,
        ];

        config(['mail.mailers.smtp' => $mailConfig]);
        config(['mail.from.address' => $email_setting->mail_sender_email]);
        config(['mail.from.name' => $email_setting->mail_sender_name]);
    }
}
