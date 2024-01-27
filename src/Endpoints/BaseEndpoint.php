<?php

namespace Ag84ark\SmartBill\Endpoints;

use Ag84ark\SmartBill\SmartBillCloudRestClient;

abstract class BaseEndpoint
{
    public const INVOICE_URL = '/invoice';
    public const INVOICE_REVERSE_URL = '/invoice/reverse';
    public const STATUS_INVOICE_URL = '/invoice/paymentstatus';

    public const PROFORMA_URL = '/estimate';
    public const STATUS_PROFORMA_URL = '/estimate/invoices';
    public const PAYMENT_URL = '/payment';
    public const EMAIL_URL = '/document/send';
    public const TAXES_URL = '/tax?cif=%s';
    public const SERIES_URL = '/series?cif=%s&type=%s';
    public const PRODUCTS_STOCK_URL = '/stocks?cif=%s&date=%s&warehouseName=%s&productName=%s&productCode=%s';
    public const PARAMS_PDF = '/pdf?cif=%s&seriesname=%s&number=%s';
    public const PARAMS_DELETE = '?cif=%s&seriesname=%s&number=%s';
    public const PARAMS_DELETE_RECEIPT = '/chitanta?cif=%s&seriesname=%s&number=%s';
    public const PARAMS_DELETE_OTHER_PAYMENT_TYPE = '/v2'; //?cif=%s&paymentType=%s&paymentDate=%s&paymentValue=%s&clientName=%s&clientCif=%s&invoiceSeries=%s&invoiceNumber=%s';
    public const PARAMS_CANCEL = '/cancel?cif=%s&seriesname=%s&number=%s';
    public const PARAMS_RESTORE = '/restore?cif=%s&seriesname=%s&number=%s';
    public const PARAMS_STATUS = '?cif=%s&seriesname=%s&number=%s';
    public const PARAMS_FISCAL_RECEIPT = '/text?cif=%s&id=%s';

    public const REST_CREATE = SmartBillCloudRestClient::HTTP_POST;
    public const REST_UPDATE = SmartBillCloudRestClient::HTTP_PUT;
    public const REST_READ = SmartBillCloudRestClient::HTTP_GET;
    public const REST_LIST = SmartBillCloudRestClient::HTTP_GET;
    public const REST_DELETE = SmartBillCloudRestClient::HTTP_DELETE;

    protected SmartBillCloudRestClient $restClient;

    public function __construct(SmartBillCloudRestClient $restClient)
    {
        $this->restClient = $restClient;
    }

    /**
     * Creates a new resource using the RESTful API.
     *
     * @param string $url The URL of the RESTful API endpoint to create the resource.
     * @param mixed $body The request body data for creating the resource.
     * @param string $acceptHeader The Accept header for the HTTP request. (Default: 'Accept: application/json')
     * @return mixed The response from the RESTful API.
     */
    protected function rest_create(string $url, $body, string $acceptHeader = 'Accept: application/json')
    {
        return $this->restClient->performHttpCall(
            self::REST_CREATE,
            $url,
            $body,
            $acceptHeader
        );
    }

    protected function rest_update($url, $body, $acceptHeader = 'Accept: application/json')
    {
        return $this->restClient->performHttpCall(
            self::REST_UPDATE,
            $url,
            $body,
            $acceptHeader
        );
    }

    protected function rest_read($url, $acceptHeader = 'Accept: application/json')
    {
        return $this->restClient->performHttpCall(
            self::REST_READ,
            $url,
            $acceptHeader
        );
    }

    protected function rest_list($url, $acceptHeader = 'Accept: application/json')
    {
        return $this->restClient->performHttpCall(
            self::REST_LIST,
            $url,
            $acceptHeader
        );
    }

    protected function rest_delete($url, $acceptHeader = 'Accept: application/json')
    {
        return $this->restClient->performHttpCall(
            self::REST_DELETE,
            $url,
            $acceptHeader
        );
    }
}
