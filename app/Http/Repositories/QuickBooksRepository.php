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
        // check if token as expired
        if ($token->expires_in < now()) {
            // refresh token
            $accessToken = $this->dataService->refreshOAuth2Token($token->refresh_token);
            // update token
            $token->access_token = $accessToken->getAccessToken();
            $token->refresh_token = $accessToken->getRefreshToken();
            $token->expires_in = $accessToken->getAccessTokenExpiresAt();
            $token->x_refresh_token_expires_in = $accessToken->getRefreshTokenExpiresAt();
            $token->save();
        }

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
        // get by id
        $account = $dataService->FindById("Account", $id);
        // $account = $dataService->Query("SELECT * FROM Account WHERE Id = '$id'");
        return $account;
    }

    public function getAccountByAccountNumber($accountNumber)
    {
        $dataService = $this->getAccessToken();
        $account = $dataService->Query("SELECT * FROM Account WHERE AccountNumber = '$accountNumber'");
        return $account;
    }
    // create account
    public function createAccount($account)
    {
        $dataService = $this->getAccessToken();
        $newAccount = Account::create([
            "Name" => $account['name'],
            "AccountType" => $account['account_type'],
            // "AccountSubType" => $account['account_sub_type'],
            // "AccNum" => $account['account_number'],
            "Description" => $account['description'],
            "Active" => $account['active'],
            "Classification" => $account['classification'],
            "CurrentBalance" => $account['current_balance'],
            // "CurrentBalanceWithSubAccounts" => $account['current_balance_with_sub_accounts'],
            "CurrencyRef" => [
                "value" => 'USD',
                "name" => 'United States Dollar'
            ],
            // "Domain" => $account['domain'],
            "FullyQualifiedName" => $account['name'],
            "SubAccount" => false,
            "TaxCodeRef" => [
                "value" => 'TAXABLE',
                "name" =>'Taxable'
            ],
            // "TaxTypeApplicable" => 'TAXABLE',
            // "AcctNum" => $account['account_number'],
            // "AcctNumWithSubAccounts" => '',
            // "AcctType" => $account['account_type'],
            // "AcctTypeWithSubAccounts" => '',
            // "Balance" => $account['current_balance'],
            // "BalanceWithSubAccounts" => $account['balance_with_sub_accounts'],
            // "ClassificationWithSubAccounts" => $account['classification_with_sub_accounts'],
            // "FullyQualifiedNameWithSubAccounts" => $account['fully_qualified_name_with_sub_accounts'],
            // "SubAccountWithSubAccounts" => false,
            // "TaxTypeApplicableWithSubAccounts" => $account['tax_type_applicable_with_sub_accounts'],
            // "UnitPrice" => $account['unit_price'],
            // "UnitPriceWithSubAccounts" => $account['unit_price_with_sub_accounts'],
            // "UnitPriceType" => $account['unit_price_type'],
            // "UnitPriceTypeWithSubAccounts" => $account['unit_price_type_with_sub_accounts'],
            // "UnitOfMeasure" => 'EACH',
            // "UnitOfMeasureWithSubAccounts" => $account['unit_of_measure_with_sub_accounts'],
        ]);
        $resultingObj = $dataService->Add($newAccount);
        return $resultingObj;
    }


    
}
