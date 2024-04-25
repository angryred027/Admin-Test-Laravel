<?php

declare(strict_types=1);

namespace App\Library\Stripe;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Exceptions\MyApplicationHttpException;
use App\Library\Message\StatusCodeMessages;
use App\Library\Stripe\StripeLibrary;
use App\Models\Users\UserCoinPaymentStatus;
use Stripe\Checkout\Session;
use Stripe\StripeClient;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\AuthenticationException;
use Stripe\Util\RequestOptions;
use Stripe\StripeObject;
use Stripe\Exception\ApiErrorException;

class CheckoutLibrary extends StripeLibrary
{
    // query
    private const QUERY_ORDER_ID = '?orderId=';

    // mode
    private const CHECKOUT_MODE_PAYMENT = 'payment'; // Accept one-time payments for cards, iDEAL, and more.
    private const CHECKOUT_MODE_SET_UP = 'setup'; // Save payment details to charge your customers later.
    private const CHECKOUT_MODE_SUBSCRIPTION = 'subscription'; // Use Stripe Billing to set up fixed-price subscriptions.

    // payment method types
    private const PAYMENT_TYPE_CARD = 'card';
    private const PAYMENT_TYPE_ACSS_DEBIT = 'acss_debit';
    private const PAYMENT_TYPE_ALIPAY = 'alipay';
    private const PAYMENT_TYPE_KONBINI = 'konbini';
    private const PAYMENT_TYPE_P24 = 'p24';
    private const PAYMENT_TYPE_PAYNOW = 'paynow';
    private const PAYMENT_TYPE_SEPA_DEBIT = 'sepa_debit';
    private const PAYMENT_TYPE_US_BANK_ACCOUNT = 'us_bank_account';
    private const PAYMENT_TYPE_WECHAT_PAY = 'wechat_pay';

    // 当サービスで利用する決済方法
    private const SERVICE_PAYMENT_TYPES = [
        self::PAYMENT_TYPE_CARD,
        self::PAYMENT_TYPE_KONBINI,
        // self::PAYMENT_TYPE_WECHAT_PAY,
    ];

    // status (open, complete, expired,)
    private const CHECKOUT_STATUS_OPEN = 'open';
    private const CHECKOUT_STATUS_COMPLETE = 'complete';
    private const CHECKOUT_STATUS_EXPIRED = 'expired';

    // 当サービスで利用する決済方法
    /** @var array<string, int> CHECKOUT_STATUS_VALUE_LIST */
    public const CHECKOUT_STATUS_VALUE_LIST = [
        self::CHECKOUT_STATUS_OPEN => UserCoinPaymentStatus::PAYMENT_STATUS_START,
        self::CHECKOUT_STATUS_COMPLETE => UserCoinPaymentStatus::PAYMENT_STATUS_COMPLETE,
        self::CHECKOUT_STATUS_EXPIRED => UserCoinPaymentStatus::PAYMENT_STATUS_EXPIRED,
    ];

    // currency types
    public const CURRENCY_TYPE_JPY = 'jpy';

    // request param
    public const REQUEST_KEY_SUCCESS_URL = 'success_url'; // require 決済完了後のリダイレクト先URL
    public const REQUEST_KEY_CANCEL_URL = 'cancel_url'; // require 失敗時や決済画面の「キャンセルボタン」押下時のリダイレクト先URL
    public const REQUEST_KEY_MODE = 'mode'; // require
    public const REQUEST_KEY_LINE_ITEMS = 'line_items';
    public const REQUEST_KEY_LINE_ITEM_NAME = 'name'; // 商品名
    public const REQUEST_KEY_LINE_ITEM_DESCRIPTION = 'description'; // 説明
    public const REQUEST_KEY_LINE_ITEM_IMAGES = 'images'; // 画像URL
    public const REQUEST_KEY_LINE_ITEM_AMOUNT = 'amount'; // 金額
    public const REQUEST_KEY_LINE_ITEM_CURRENCY = 'currency'; // 単位(ex: 'jpy')
    public const REQUEST_KEY_LINE_ITEM_PRICE = 'price'; // 価格
    public const REQUEST_KEY_LINE_ITEM_QUANTITY = 'quantity'; // 数量
    public const REQUEST_KEY_CUSTOMER = 'customer';
    public const REQUEST_KEY_CUSTOMER_EMAIL = 'customer_email';
    public const REQUEST_KEY_PAYMENT_METHOD_TYPES = 'payment_method_types';

    // response param
    private const RESPONSE_KEY_ID_LOCAL = 'id'; // 重複を避ける為に`LOCAL`をつけている
    private const RESPONSE_KEY_OBJECT = 'object';
    private const RESPONSE_KEY_AFTER_EXPIRATION = 'after_expiration';
    private const RESPONSE_KEY_ALLOW_PROMOTION_CODES = 'allow_promotion_codes';
    private const RESPONSE_KEY_AMOUNT_SUBTOTAL = 'amount_subtotal';
    private const RESPONSE_KEY_AMMOUNT_TOTAL = 'amount_total';
    private const RESPONSE_KEY_AUTOMATIC_TAX = 'automatic_tax';
    private const RESPONSE_KEY_ENABLED = 'enabled';
    private const RESPONSE_KEY_STATUS = 'status';
    private const RESPONSE_KEY_BILLING_ADDRESS_COLLECTION = 'billing_address_collection';
    private const RESPONSE_KEY_CANCEL_URL = 'cancel_url';
    private const RESPONSE_KEY_CLIENT_REFERENCE_ID = 'client_reference_id';
    private const RESPONSE_KEY_CONSENT = 'consent';
    private const RESPONSE_KEY_CONSENT_COLLECTION = 'consent_collection';
    private const RESPONSE_KEY_CURRENCY = 'currency';
    private const RESPONSE_KEY_CUSTOMER = 'customer';
    private const RESPONSE_KEY_CUSTOMER_CREATION = 'customer_creation';
    private const RESPONSE_KEY_CUSTOMER_DETAILS = 'customer_details';
    private const RESPONSE_KEY_CUSTOMER_EMAIL = 'customer_email';
    private const RESPONSE_KEY_EXPIRES_AT = 'expires_at';
    private const RESPONSE_KEY_LIVEMODE = 'livemode';
    private const RESPONSE_KEY_LOCALE = 'locale';
    private const RESPONSE_KEY_MODE = 'mode';
    private const RESPONSE_KEY_PAYMENT_INTENT= 'payment_intent';
    private const RESPONSE_KEY_PAYMENT_LINK = 'payment_link';
    private const RESPONSE_KEY_PAYMENT_METHOD_OPTIONS = 'payment_method_options';
    private const RESPONSE_KEY_PAYMENT_METHOD_TYPES = 'payment_method_types';
    private const RESPONSE_KEY_PAYMENT_METHOD_TYPE_CARD = 'card';
    private const RESPONSE_KEY_PAYMENT_STATUS = 'payment_status';
    private const RESPONSE_KEY_PHONE_NUMBER_COLLECTION = 'phone_number_collection';
    private const RESPONSE_KEY_PHONE_NUMBER_COLLECTION_ENABLED = 'enabled';
    private const RESPONSE_KEY_RECOVERD_FROM= 'recovered_from';
    private const RESPONSE_KEY_SETUP_INTENT = 'setup_intent';
    private const RESPONSE_KEY_SNIPPING = 'shipping';
    private const RESPONSE_KEY_SNIPPING_ADDRESS_COLLECTION = 'shipping_address_collection';
    private const RESPONSE_KEY_SNIPPING_OPTIONS = 'shipping_options';
    private const RESPONSE_KEY_SNIPPING_RATE = 'shipping_rate';
    private const RESPONSE_KEY_STATUS_LOCAL = 'status'; // 重複を避ける為に`LOCAL`をつけている
    private const RESPONSE_KEY_SUBMIT_TYPE = 'submit_type';
    private const RESPONSE_KEY_SUBSCRIPTION = 'subscription';
    private const RESPONSE_KEY_SUCCESS_URL = 'success_url';
    private const RESPONSE_KEY_TOTAL_DETAILS = 'total_details';
    private const RESPONSE_KEY_URL = 'url';

    /**
     * create Stripe session.
     *
     * @param string $orderId order id.
     * @param array $lineItems taget productions of payment.
     * @return Session
     */
    public static function createSession(string $orderId, array $lineItems): Session
    {
        $stripe = self::getStripeClient();

        $query = self::QUERY_ORDER_ID . $orderId;

        // リダイレクト先はヘッダー等をつけられない都合でフロントエンドにリダイレクトしてからリクエストして貰う必要がある。
        return $stripe->checkout->sessions->create([
            self::REQUEST_KEY_SUCCESS_URL => route('user.coins.payment.complete') . $query, // 決済完了後のリダイレクト先
            self::REQUEST_KEY_CANCEL_URL => route('user.coins.payment.cancel') . $query, // 決済画面の「キャンセルボタン」押下時のリダイレクト先
            // self::REQUEST_KEY_PAYMENT_METHOD_TYPES => [self::PAYMENT_TYPE_CARD],
            self::REQUEST_KEY_PAYMENT_METHOD_TYPES => self::SERVICE_PAYMENT_TYPES,
            self::REQUEST_KEY_MODE => self::CHECKOUT_MODE_PAYMENT,
            self::REQUEST_KEY_LINE_ITEMS => $lineItems,
        ]);
    }

    /**
     * cancel Stripe session.
     *
     * @param string $serviceId payment service id
     * @return Session
     */
    public static function cancelSession(string $serviceId): Session
    {
        $stripe = self::getStripeClient();

        try {
            // セッションの取得(取得出来ない場合はエラーが発生)
            $session = $stripe->checkout->sessions->retrieve($serviceId);

            if ($serviceId === $session->id) {
                // セッションの削除
                $exipiredSession = $stripe->checkout->sessions->expire($session->id);
            } else {
                throw new MyApplicationHttpException(
                    StatusCodeMessages::STATUS_500,
                    'stripe api error. service id'
                );
            }
        } catch (Exception $e) {
            Log::error(__CLASS__ . '::' . __FUNCTION__ . ' line:' . __LINE__ . ' ' . 'message: ' . json_encode($e->getMessage()));
            throw $e;
            // TODO パラメーターの設定とエラー内容によってメッセージの制御
            /* throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_500,
                'stripe api error.'
            ); */
        }

        return $exipiredSession;
    }

    /**
     * complete Stripe session.
     *
     * @param string $serviceId payment service id
     * @return Session
     */
    public static function completeSession(string $serviceId): Session
    {
        $stripe = self::getStripeClient();

        try {
            // セッションの取得(取得出来ない場合はエラーが発生)
            $session = $stripe->checkout->sessions->retrieve($serviceId);

            if ($serviceId !== $session->id) {
                throw new MyApplicationHttpException(
                    StatusCodeMessages::STATUS_500,
                    'stripe api error. service id'
                );
            }
        } catch (Exception $e) {
            Log::error(__CLASS__ . '::' . __FUNCTION__ . ' line:' . __LINE__ . ' ' . 'message: ' . json_encode($e->getMessage()));
            throw $e;
            // TODO パラメーターの設定とエラー内容によってメッセージの制御
            /* throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_500,
                'stripe api error.'
            ); */
        }

        return $session;
    }

    /**
     * exec stripe api request for POST
     *
     * @param string $orderId order id.
     * @param array $lineItems taget productions of payment.
     * @return Session
     */
    public static function debugCreateSession(string $orderId, array $lineItems): Session
    {
        $stripe = self::getStripeClient();

        $query = self::QUERY_ORDER_ID . $orderId;

        return $stripe->checkout->sessions->create([
            self::REQUEST_KEY_SUCCESS_URL => route('user.debug.checkout.complete') . $query, // 'https://example.com/success', 決済完了後のリダイレクト先
            self::REQUEST_KEY_CANCEL_URL => route('user.debug.checkout.cancel') . $query, // 'https://example.com/cancel' 決済画面の「キャンセルボタン」押下時のリダイレクト先
            // self::REQUEST_KEY_PAYMENT_METHOD_TYPES => [self::PAYMENT_TYPE_CARD],
            self::REQUEST_KEY_PAYMENT_METHOD_TYPES => self::SERVICE_PAYMENT_TYPES,
            self::REQUEST_KEY_MODE => self::CHECKOUT_MODE_PAYMENT,
            self::REQUEST_KEY_LINE_ITEMS => $lineItems,
        ]);
    }
}
