<?php

namespace Ag84ark\SmartBill;

use Ag84ark\SmartBill\Resources\Invoice;
use Ag84ark\SmartBill\Resources\Payment;
use Ag84ark\SmartBill\Resources\ReverseInvoices;

class SmartBillCloudRestClient
{
    public const INVOICE_URL = 'https://ws.smartbill.ro/SBORO/api/invoice';
    public const INVOICE_REVERSE_URL = 'https://ws.smartbill.ro/SBORO/api/invoice/reverse';
    public const STATUS_INVOICE_URL = 'https://ws.smartbill.ro/SBORO/api/invoice/paymentstatus';
    public const PROFORMA_URL = 'https://ws.smartbill.ro/SBORO/api/estimate';
    public const STATUS_PROFORMA_URL = 'https://ws.smartbill.ro/SBORO/api/estimate/invoices';
    public const PAYMENT_URL = 'https://ws.smartbill.ro/SBORO/api/payment';
    public const EMAIL_URL = 'https://ws.smartbill.ro/SBORO/api/document/send';
    public const TAXES_URL = 'https://ws.smartbill.ro/SBORO/api/tax?cif=%s';
    public const SERIES_URL = 'https://ws.smartbill.ro/SBORO/api/series?cif=%s&type=%s';
    public const PRODUCTS_STOCK_URL = 'https://ws.smartbill.ro/SBORO/api/stocks?cif=%s&date=%s&warehouseName=%s&productName=%s&productCode=%s';
    public const PARAMS_PDF = '/pdf?cif=%s&seriesname=%s&number=%s';
    public const PARAMS_DELETE = '?cif=%s&seriesname=%s&number=%s';
    public const PARAMS_DELETE_RECEIPT = '/chitanta?cif=%s&seriesname=%s&number=%s';
    public const PARAMS_CANCEL = '/cancel?cif=%s&seriesname=%s&number=%s';
    public const PARAMS_RESTORE = '/restore?cif=%s&seriesname=%s&number=%s';
    public const PARAMS_STATUS = '?cif=%s&seriesname=%s&number=%s';
    public const PARAMS_FISCAL_RECEIPT = '/text?cif=%s&id=%s';

    public const PaymentType_OrdinPlata = 'Ordin plata';

    public const PaymentType_Chitanta = 'Chitanta';

    public const PaymentType_Card = 'Card';

    public const PaymentType_CEC = 'CEC';

    public const PaymentType_BiletOrdin = 'Bilet ordin';

    public const PaymentType_MandatPostal = 'Mandat postal';

    public const PaymentType_Other = 'Alta incasare';

    public const PaymentType_BonFiscal = 'Bon';

    public const DiscountType_Valoric = 1;

    public const DiscountType_Value = 1; // en

    public const DiscountType_Procentual = 2;

    public const DiscountType_Percent = 2; // en

    public const DocumentType_Invoice = 'factura'; // en

    public const DocumentType_Factura = 'factura';

    public const DocumentType_Proforma = 'proforma';

    public const DocumentType_Receipt = 'chitanta'; // en

    public const DocumentType_Chitanta = 'chitanta';

    public const DEBUG_ON_ERROR = false; // use this only in development phase; DON'T USE IN PRODUCTION !!!

    private string $hash = '';

    public function __construct($user, $token)
    {
        $this->hash = base64_encode($user.':'.$token);
    }

    private function _cURL($url, $data, $request, $headAccept)
    {
        $headers = [$headAccept, 'Authorization: Basic '.$this->hash];

        $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_MUTE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if (! empty($data)) {
            $headers[] = 'Content-Type: application/json; charset=utf-8';
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        if (! empty($request)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // debugging
        $isDebug = self::DEBUG_ON_ERROR;
        if ($isDebug) {
            $debug = [
                'URL: ' => $url,
                'data: ' => $data,
                'headers: ' => $headAccept,
            ];
            echo '<pre>' , print_r($debug, true), '</pre>';
        }

        return $ch;
    }

    /**
     * @throws \Exception
     */
    private function _callServer($url, $data = '', $request = '', $headAccept = 'Accept: application/json')
    {
        if (empty($url)) {
            return false;
        }

        $ch = $this->_cURL($url, $data, $request, $headAccept);
        $return = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status != 200) {
            $errorMessage = json_decode($return, true);

            if (false !== strpos($url, self::EMAIL_URL)) {
                $errorMessage = ! empty($errorMessage['status']['code']) ? $errorMessage['status']['message'] : $return;
            } else {
                $errorMessage = ! empty($errorMessage['errorText']) ? $errorMessage['errorText'] : $return;
            }

            throw new \Exception($errorMessage);
        } elseif (false === strpos($url, '/pdf?')) {
            $return = json_decode($return, true);
        }

        return $return;
    }

    private function _prepareDocumentData(&$data)
    {
        if (! empty($data['subject'])) {
            $data['subject'] = base64_encode($data['subject']);
        }
        if (! empty($data['bodyText'])) {
            $data['bodyText'] = base64_encode($data['bodyText']);
        }
    }

    public function createInvoiceFromArray(array $data)
    {
        return $this->_callServer(self::INVOICE_URL, $data);
    }

    public function createInvoice(Invoice $invoice)
    {
        return $this->createInvoiceFromArray($invoice->toArray());
    }

    public function createProformaFromArray(array $data)
    {
        return $this->_callServer(self::PROFORMA_URL, $data);
    }

    public function createReverseInvoice(ReverseInvoices $reverseInvoices)
    {
        return $this->createReverseInvoiceFromArray($reverseInvoices->toArray());
    }

    public function createReverseInvoiceFromArray(array $data)
    {
        return $this->_callServer(self::INVOICE_REVERSE_URL, $data);
    }

    public function createProforma(Invoice $invoice)
    {
        return $this->createProformaFromArray($invoice->toArray());
    }

    public function createPaymentFromArray(array $data)
    {
        return $this->_callServer(self::PAYMENT_URL, $data);
    }

    public function createPayment(Payment $payment)
    {
        return $this->createPaymentFromArray($payment->toArray());
    }

    public function PDFInvoice($companyVatCode, $seriesName, $number)
    {
        $seriesName = urlencode($seriesName);
        $url = sprintf(self::INVOICE_URL.self::PARAMS_PDF, $companyVatCode, $seriesName, $number);

        return $this->_callServer($url, '', '', 'Accept: application/octet-stream');
    }

    public function PDFProforma($companyVatCode, $seriesName, $number)
    {
        $seriesName = urlencode($seriesName);
        $url = sprintf(self::PROFORMA_URL.self::PARAMS_PDF, $companyVatCode, $seriesName, $number);

        return $this->_callServer($url, '', '', 'Accept: application/octet-stream');
    }

    public function deleteInvoice($companyVatCode, $seriesName, $number)
    {
        $seriesName = urlencode($seriesName);
        $url = sprintf(self::INVOICE_URL.self::PARAMS_DELETE, $companyVatCode, $seriesName, $number);

        return $this->_callServer($url, '', 'DELETE');
    }

    public function deleteProforma($companyVatCode, $seriesName, $number)
    {
        $seriesName = urlencode($seriesName);
        $url = sprintf(self::PROFORMA_URL.self::PARAMS_DELETE, $companyVatCode, $seriesName, $number);

        return $this->_callServer($url, '', 'DELETE');
    }

    public function deleteReceipt($companyVatCode, $seriesName, $number)
    {
        $seriesName = urlencode($seriesName);
        $url = sprintf(self::PAYMENT_URL.self::PARAMS_DELETE_RECEIPT, $companyVatCode, $seriesName, $number);

        return $this->_callServer($url, '', 'DELETE');
    }

    public function deletePayment($payment)
    {
        return $this->_callServer(self::PAYMENT_URL, $payment, 'DELETE');
    }

    public function cancelInvoice($companyVatCode, $seriesName, $number)
    {
        $seriesName = urlencode($seriesName);
        $url = sprintf(self::INVOICE_URL.self::PARAMS_CANCEL, $companyVatCode, $seriesName, $number);

        return $this->_callServer($url, '', 'PUT');
    }

    public function cancelProforma($companyVatCode, $seriesName, $number)
    {
        $seriesName = urlencode($seriesName);
        $url = sprintf(self::PROFORMA_URL.self::PARAMS_CANCEL, $companyVatCode, $seriesName, $number);

        return $this->_callServer($url, '', 'PUT');
    }

    public function cancelPayment($companyVatCode, $seriesName, $number)
    {
        $seriesName = urlencode($seriesName);
        $url = sprintf(self::PAYMENT_URL.self::PARAMS_CANCEL, $companyVatCode, $seriesName, $number);

        return $this->_callServer($url, '', 'PUT');
    }

    public function restoreInvoice($companyVatCode, $seriesName, $number)
    {
        $seriesName = urlencode($seriesName);
        $url = sprintf(self::INVOICE_URL.self::PARAMS_RESTORE, $companyVatCode, $seriesName, $number);

        return $this->_callServer($url, '', 'PUT');
    }

    public function restoreProforma($companyVatCode, $seriesName, $number)
    {
        $seriesName = urlencode($seriesName);
        $url = sprintf(self::PROFORMA_URL.self::PARAMS_RESTORE, $companyVatCode, $seriesName, $number);

        return $this->_callServer($url, '', 'PUT');
    }

    public function sendDocument($data)
    {
        $this->_prepareDocumentData($data);

        return $this->_callServer(self::EMAIL_URL, $data);
    }

    public function getTaxes($companyVatCode)
    {
        $url = sprintf(self::TAXES_URL, $companyVatCode);

        return $this->_callServer($url);
    }

    public function getDocumentSeries($companyVatCode, $documentType = '')
    {
        $documentType = ! empty($documentType) ? substr($documentType, 0, 1) : $documentType; // take the 1st character
        $url = sprintf(self::SERIES_URL, $companyVatCode, $documentType);

        return $this->_callServer($url);
    }

    public function statusInvoicePayments($companyVatCode, $seriesName, $number)
    {
        $seriesName = urlencode($seriesName);
        $url = sprintf(self::STATUS_INVOICE_URL.self::PARAMS_STATUS, $companyVatCode, $seriesName, $number);

        return $this->_callServer($url);
    }

    public function statusProforma($companyVatCode, $seriesName, $number)
    {
        $seriesName = urlencode($seriesName);
        $url = sprintf(self::STATUS_PROFORMA_URL.self::PARAMS_STATUS, $companyVatCode, $seriesName, $number);

        return $this->_callServer($url);
    }

    public function detailsFiscalReceipt($companyVatCode, $id)
    {
        $url = sprintf(self::PAYMENT_URL.self::PARAMS_FISCAL_RECEIPT, $companyVatCode, $id);
        $text = $this->_callServer($url);

        try {
            $text = base64_decode($text['message']);
        } catch (\Exception $ex) {
            throw new \Exception('invalid / empty response');
        }

        return $text;
    }

    public function productsStock($data)
    {
        self::_validateProductsStock($data);
        $url = self::_urlProductsStock($data);
        $list = $this->_callServer($url);

        try {
            $list = $list['list'];
        } catch (\Exception $ex) {
            throw new \Exception('invalid / empty response');
        }

        return $list;
    }

    private static function _validateProductsStock(&$data)
    {
        // append required keys in case they are missing
        $data += [
            'cif' => '',
            'date' => date('Y-m-d'),
            'warehouseName' => '',
            'productName' => '',
            'productCode' => '',
        ];
        // urlencode values
        foreach ($data as &$value) {
            $value = urlencode($value);
        }
    }

    private static function _urlProductsStock($data)
    {
        return sprintf(self::PRODUCTS_STOCK_URL, $data['cif'], $data['date'], $data['warehouseName'], $data['productName'], $data['productCode']);
    }
}
