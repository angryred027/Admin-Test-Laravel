<?php

declare(strict_types=1);

namespace App\Library\Stripe;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\JsonResponse;
use Stripe\StripeClient;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\AuthenticationException;
use Stripe\Util\RequestOptions;
use Stripe\StripeObject;

class StripeLibrary
{
    // request methods
    private const REQUEST_METHOD_GET = 'GET';
    private const REQUEST_METHOD_POST = 'POST';
    private const REQUEST_METHOD_DELETE = 'DELETE';

    // api key config name
    private const CONFIG_KEY_NAME_PRIVATE = 'stripe.apiKey.private';

    /**
     * get permissions data for frontend parts
     *
     * @param  \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public static function getTestList(): JsonResponse
    {
        $stripe = self::getStripeClient();
        // $stripe->getApiBase();
        // $stripe->request('GET', '/');

        // TODO 確認したいものからコメントアウトを外す。
        $resource = [
            'getApiBase' => $stripe->getApiBase(),
            // 'customers' => $stripe->customers,
            // 'api_customers' => self::getRequest('/v1/customers'),
            //'api_customers' => self::getRequest('/v1/customers', ['limit' => 3], []),
            // 'api_balance' => self::getRequest('/v1/balance'),
            // 'api_balance_transactions' => self::getRequest('/v1/balance_transactions'),
            // 'api_charges' => self::getRequest('/v1/charges'),
            // 'api_disputes' => self::getRequest('/v1/disputes'),
            // 'api_events' => self::getRequest('/v1/events'),
            // 'api_files' => self::getRequest('/v1/files'),
            // 'api_file_links' => self::getRequest('/v1/file_links'),
            // 'api_payment_intents' => self::getRequest('/v1/payment_intents'),
            // 'api_setup_intents' => self::getRequest('/v1/setup_intents'),
            // 'api_setup_attempts' => self::getRequest('/v1/setup_attempts'),
            // 'api_payouts' => self::getRequest('/v1/payouts'),
            // 'api_payment_methods' => self::getRequest('/v1/payment_methods'),
            // 'api_products' => self::getRequest('/v1/products'),
            // 'api_prices' => self::getRequest('/v1/prices'),
            // 'api_coupons' => self::getRequest('/v1/coupons'),
            // 'api_promotion_codes' => self::getRequest('/v1/promotion_codes'),
            // 'api_tax_codes' => self::getRequest('/v1/tax_codes'),
            // 'api_invoices' => self::getRequest('/v1/invoices'),
            // 'api_invoiceitems' => self::getRequest('/v1/invoiceitems'),
            // 'api_subscriptions' => self::getRequest('/v1/subscriptions'),
            'api_orders' => self::getRequest('/v1/orders'),
        ];

        return response()->json($resource, 200);
    }

    /**
     * exec stripe api request for GET
     *
     * @param string $path — the path of the request
     * @param array $params — the parameters of the request
     * @param array|RequestOptions $options the special modifiers of the request
     * @return StripeObject
     */
    public static function getRequest(
        string $path,
        array $params = [],
        array|RequestOptions $options = []
    ): StripeObject {
        $stripe = self::getStripeClient();

        return $stripe->request(self::REQUEST_METHOD_GET, $path, $params, $options);
    }

    /**
     * exec stripe api request for POST
     *
     * @param string $path — the path of the request
     * @param array $params — the parameters of the request
     * @param array|RequestOptions $options the special modifiers of the request
     * @return StripeObject
     */
    public static function postRequest(
        string $path,
        array $params = [],
        array|RequestOptions $options = []
    ): StripeObject {
        $stripe = self::getStripeClient();

        return $stripe->request(self::REQUEST_METHOD_POST, $path, $params, $options);
    }

    /**
     * exec stripe api request for DELETE
     *
     * @param string $path — the path of the request
     * @param array $params — the parameters of the request
     * @param array|RequestOptions $options the special modifiers of the request
     * @return StripeObject
     */
    public static function deleteRequest(
        string $path,
        array $params = [],
        array|RequestOptions $options = []
    ): StripeObject {
        $stripe = self::getStripeClient();

        return $stripe->request(self::REQUEST_METHOD_DELETE, $path, $params, $options);
    }

    /**
     * get StripeClient.
     *
     * @return StripeClient
     */
    protected static function getStripeClient(): StripeClient
    {
        return new StripeClient(Config::get(self::CONFIG_KEY_NAME_PRIVATE));
    }
}
