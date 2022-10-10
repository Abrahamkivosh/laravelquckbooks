<?php

namespace App\Http\Repositories;

// quickbooks class repository
use App\Models\Token;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Account;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Facades\PaymentMethod;
use QuickBooksOnline\API\Facades\TaxService;
use QuickBooksOnline\API\Facades\TaxRate;
use QuickBooksOnline\API\Facades\Vendor;
use QuickBooksOnline\API\Facades\Term;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\JournalEntry;
use QuickBooksOnline\API\Facades\Bill;
use QuickBooksOnline\API\Facades\BillPayment;
use QuickBooksOnline\API\Facades\Deposit;
use QuickBooksOnline\API\Facades\RefundReceipt;
use QuickBooksOnline\API\Facades\Transfer;
use QuickBooksOnline\API\Facades\Estimate;
use QuickBooksOnline\API\Facades\Classs;
use QuickBooksOnline\API\Facades\Account as FacadesAccount;
use QuickBooksOnline\API\Facades\Item as FacadesItem;
use QuickBooksOnline\API\Facades\Payment as FacadesPayment;
use QuickBooksOnline\API\Facades\PaymentMethod as FacadesPaymentMethod;
use QuickBooksOnline\API\Facades\TaxService as FacadesTaxService;
use QuickBooksOnline\API\Facades\TaxRate as FacadesTaxRate;
use QuickBooksOnline\API\Facades\Vendor as FacadesVendor;

class QuickBooksRepository 
{
    public function __construct()
    {
        $this->dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => config('app.quickbooks.client_id'),
            'ClientSecret' => config('app.quickbooks.client_secret'),
            'RedirectURI' => config('app.quickbooks.redirect_uri'),
            // 'scope' => config('app.quickbooks.scope'),
            // 'baseUrl' => config('app.quickbooks.base_url'),
        
            
            'scope' => "com.intuit.quickbooks.accounting",
            'baseUrl' => "Development"
        ));
    }

    public function getAccessToken()
    {
        $token = Token::first();
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => config('app.quickbooks.client_id'),
            'ClientSecret' => config('app.quickbooks.client_secret'),
            'RedirectURI' => config('app.quickbooks.redirect_uri'),
            'scope' => config('app.quickbooks.scope'),
            'baseUrl' => config('app.quickbooks.base_url'),
            'accessTokenKey' => $token->access_token,
            'refreshTokenKey' => $token->refresh_token,
            'QBORealmID' => $token->realm_id,
       ));
        return $dataService;
       
    }

    public function getAuthUrl()
    {
        $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
        $authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
        return $authUrl;
    }

    public function getAccessTokenObjFromCode(string $code,string $realmId)
    {
        $dataService = $this->dataService;
        $accessTokenObj = $dataService->getOAuth2LoginHelper()->exchangeAuthorizationCodeForToken($code, $realmId);
        $dataService->updateOAuth2Token($accessTokenObj);
        // $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
        $dataService->throwExceptionOnError(true);
        return $accessTokenObj;
    }
   


    public function getCompanyInfo()
    {
        $dataService = $this->getAccessToken();
        $companyInfo = $dataService->getCompanyInfo();
        return $companyInfo;
    }

    public function getAccounts()
    {
        $dataService = $this->getAccessToken();
        $accounts = $dataService->Query("SELECT * FROM Account");
        return $accounts;
    }

    public function getAccount($id)
    {
        $dataService = $this->getAccessToken();
        $account = $dataService->Query("SELECT * FROM Account WHERE Id = '$id'");
        return $account;
    }

    public function getAccountByAccountNumber($accountNumber)
    {
        $dataService = $this->getAccessToken();
        $account = $dataService->Query("SELECT * FROM Account WHERE AccountNumber = '$accountNumber'");
        return $account;
    }

    
}
