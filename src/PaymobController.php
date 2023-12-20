<?php

namespace Abdulbaset\PaymentGateways\Paymob;

use GuzzleHttp\Client;

class PaymobController
{

    private $apiKey;
    private $tokenStepOne;
    private $tokenStepThree;
    private $frame_id;
    private $integration_id;
    private $order_id;
    private $delivery_needed;
    private $merchant_order_id;
    private $currency;
    private $amount_cents;
    private $billing_data;
    private $shipping_data;
    private $shipping_details;
    private $items;
    private $response;

    public function __construct()
    {
        $this->delivery_needed = 'false';
        $this->currency = 'EGP';
        $this->amount_cents = 100;
        $this->setItems(
            [
                "name" => "Apple",
                "amount_cents" => "100",
                "description" => "Smart Watch",
                "quantity" => "1"
            ],
            [
                "name" => "Apple",
                "amount_cents" => "100",
                "description" => "Smart Watch",
                "quantity" => "1"
            ]
        );
        $this->setShippingData([
            "first_name" => "Abdulbaset",
            "last_name" => "R. Sayed",
            'email' => 'email@example.com',
            'phone_number' => '01097579845',
        ]);
        $this->setBillingData([
            "first_name" => "Abdulbaset",
            "last_name" =>  "R. Sayed",
            "email" => "AbdulbasetRedaSayedHF@Gmail.com",
            "phone_number" => "01097579845",
            "apartment" => "N/A",
            "floor" => "N/A",
            "street" => "N/A",
            "building" => "N/A",
            "shipping_method" => "N/A",
            "postal_code" => "N/A",
            "city" => "N/A",
            "country" => "N/A",
            "state" => "N/A"
        ]);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function getTypeAvailable()
    {
        $types = ['Paypal', 'MIGS', 'Mobile Wallet', 'Accept Kiosk', 'Cash Collection'];
        return $types;
    }
    /**
     * Undocumented function
     *
     * @param string $amount_cents
     * @return void
     */
    public function setAmountCents(string $amount_cents)
    {
        $this->amount_cents = $amount_cents;
    }

    private function getAmountCents()
    {
        return $this->amount_cents;
    }

    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->authenticate($apiKey);
    }

    private function getApiKey()
    {
        return $this->apiKey;
    }

    private function setTokenStepOne(string $tokenStepOne)
    {
        $this->tokenStepOne = $tokenStepOne;
    }

    public function getTokenStepOne()
    {
        return $this->tokenStepOne;
    }

    private function setTokenStepThree(string $tokenStepThree)
    {
        $this->tokenStepThree = $tokenStepThree;
    }

    public function getTokenStepThree()
    {
        return $this->tokenStepThree;
    }

    private function setResponse(array $response = null, string $message = null, int $status = null)
    {
        $this->response = [
            'response' => $response,
            'message' => $message,
            'status' => $status,
        ];
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setItems(array $items)
    {
        $this->items = [$items];
    }

    private function getItems()
    {
        return $this->items;
    }

    /**
     * The billing data related to the customer related to this payment. All the fields in this object are mandatory, you can send any of these information if it isn't available, please send it to be "NA", except, first_name, last_name, email, and phone_number cannot be sent as "NA".
     *
     * @param array $billing_data
     * @return void
     */
    public function setBillingData(array $billing_data)
    {
        $this->billing_data = $billing_data;
    }

    private function getBillingData()
    {
        return $this->billing_data;
    }
    public function setShippingData(array $shipping_data)
    {
        $this->shipping_data = $shipping_data;
    }

    private function getShippingData()
    {
        return $this->shipping_data;
    }

    public function setShippingDetails(array $shipping_details)
    {
        $this->shipping_details = $shipping_details;
    }

    private function getShippingDetails()
    {
        return $this->shipping_details;
    }

    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }

    private function getCurrency()
    {
        return $this->currency;
    }

    private function setOrderId(string $setOrderId)
    {
        $this->order_id = $setOrderId;
    }

    public function getOrderId()
    {
        return $this->order_id;
    }

    public function setDeliveryNeeded(string $delivery_needed)
    {
        $this->delivery_needed = $delivery_needed;
    }

    private function getDeliveryNeeded()
    {
        return $this->delivery_needed;
    }

    public function setMerchantOrderId(string $merchant_order_id)
    {
        $this->merchant_order_id = $merchant_order_id;
    }

    private function getMerchantOrderId()
    {
        if ($this->merchant_order_id == null) {
            return 'null';
        }
        return $this->merchant_order_id;
    }

    public function setIntegrationId(string $integration_id)
    {
        $this->integration_id = $integration_id;
    }

    private function getIntegrationId()
    {
        return $this->integration_id;
    }

    public function setIframe(string $Iframe)
    {
        $this->frame_id = $Iframe;
    }

    private function getIframe()
    {
        return $this->frame_id;
    }

    /**
     * Authenticate and obtain an API token from Paymob for accessing the API.
     *
     * @return void
     */
    private function authenticate()
    {
        // 1. Set the API endpoint URL for authentication.
        $url = 'https://accept.paymob.com/api/auth/tokens';

        // 2. Make an HTTP POST request to obtain an API token.
        $client = new Client();
        $response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => ['api_key' => $this->getApiKey()],
        ]);

        // 3. Decode the JSON response from the API.
        $data = json_decode($response->getBody(), true);

        // 4. Check if the 'token' is present in the API response.
        if (isset($data['token'])) {
            // 5. Set the API response and update the step one token in the class.
            $this->setResponse($data);
            return $this->setTokenStepOne($data['token']);
        }

        // 6. If 'token' is not found, set an error response.
        $this->setResponse([], 'Authentication response did not contain a valid token.', 404);
        return;
    }

    private function orderRegistration()
    {
        $url = 'https://accept.paymob.com/api/ecommerce/orders';

        $client = new Client();
        $response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'auth_token' => $this->getTokenStepOne(),
                'delivery_needed' => $this->getDeliveryNeeded(),
                'amount_cents' => $this->getAmountCents(),
                'currency' => $this->getCurrency(),
                // 'merchant_order_id' => $this->getMerchantOrderId(),
                'items' => $this->getItems(),
                'shipping_data' => $this->getShippingData(),
                'shipping_details' => $this->getShippingDetails(),
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        $this->setResponse($data, null, 200);
        $this->setOrderId($data['id']);
        return;
    }

    /**
     * Request a payment key from the Paymob API for processing payments.
     *
     * @return void
     */
    public function paymentKeyRequest()
    {
        // 1. Perform order registration to obtain necessary order details.
        $this->orderRegistration();

        // 2. Set the API endpoint URL for requesting payment keys.
        $url = 'https://accept.paymob.com/api/acceptance/payment_keys';

        // 3. Make an HTTP POST request to the Paymob API to obtain a payment key.
        $client = new Client();
        $response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'auth_token' => $this->getTokenStepOne(),
                'amount_cents' => $this->getAmountCents(),
                'expiration' => 3600,
                'order_id' => $this->getOrderId(),
                'billing_data' => $this->getBillingData(),
                'currency' => $this->getCurrency(),
                'integration_id' => $this->getIntegrationId(),
            ],
        ]);

        // 4. Decode the JSON response from the API.
        $data = json_decode($response->getBody(), true);

        // 5. Check if the 'token' is present in the API response.
        if (isset($data['token'])) {
            // 6. Set the API response and update the step three token in the class.
            $this->setResponse($data);
            return $this->setTokenStepThree($data['token']);
        }

        // 7. If 'token' is not found, set an error response.
        $this->setResponse([], 'Step three token has not been found.', 404);
        return;
    }

    /**
     * Get the URL for initiating card payments using the Paymob API and the specified iframe.
     *
     * @param string $integration_id Integration ID for the payment process.
     * @param string $frame The identifier for the iframe to be used for the card payment.
     *
     * @return string The URL to redirect the customer to the specified iframe for completing the card payment process.
     */
    public function getCardPaymentsURL(string $integration_id, string $frame)
    {
        // 1. Set the integration ID for the payment process.
        $this->setIntegrationId($integration_id);

        // 2. Set the specified iframe for card payments.
        $this->setIframe($frame);

        // 3. Request a payment key necessary for the payment process.
        $this->paymentKeyRequest();

        // 4. Get the payment token for step three of the payment process.
        $token = $this->getTokenStepThree();

        // 5. Get the specified iframe identifier.
        $frame = $this->getIframe();

        // 6. Construct and return the URL for redirecting the customer to the specified iframe for card payments.
        return 'https://accept.paymobsolutions.com/api/acceptance/iframes/' . $frame . '?payment_token=' . $token;
    }

    /**
     * Get the URL for initiating a mobile wallet payment using the Paymob API.
     *
     * @param string $integration_id Integration ID for the payment process.
     * @param string $phone_number Customer's phone number associated with the mobile wallet.
     *
     * @return string The URL to redirect the customer for completing the payment process.
     *                If the mobile number does not have a wallet, a message is returned.
     */
    public function getMobileWalletsURL(string $integration_id, string $phone_number)
    {
        // 1. Set the integration ID for the payment process.
        $this->setIntegrationId($integration_id);

        // 2. Define the API endpoint URL for initiating the payment.
        $url = 'https://accept.paymob.com/api/acceptance/payments/pay';

        // 3. Request a payment key necessary for the payment process.
        $this->paymentKeyRequest();

        // 4. Make an HTTP POST request to the Paymob API to initiate the payment.
        $client = new Client();
        $response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'source' => [
                    'identifier' => $phone_number,
                    'subtype' => 'WALLET'
                ],
                'payment_token' => $this->getTokenStepThree(),
            ],
        ]);

        // 5. Decode the JSON response from the API.
        $data = json_decode($response->getBody(), true);

        // 6. Set the API response in the class for further reference.
        $this->setResponse($data, null, 200);

        // 7. Check if the redirect URL is empty. If so, the mobile number does not have a wallet.
        if ($data['redirect_url'] == '') {
            return 'The Mobile Number Does Not Have a Wallet';
        }

        // 8. Return the redirect URL for completing the payment process.
        return $data['redirect_url'];
    }
}
