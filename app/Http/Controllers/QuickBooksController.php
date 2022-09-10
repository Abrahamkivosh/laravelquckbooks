<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use QuickBooksOnline\API\Facades\Account;
use QuickBooksOnline\API\DataService\DataService;

class QuickBooksController extends Controller
{
    protected function dataService()
    {
        // Prep Data Services
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => "ABKTp9rV9Hn1Sz4bZ2s3EKvrYRKhCD4rvT5aHq8kg8kP58NefR",
            'ClientSecret' => "bUKM74bO15jcGinvW84ctv31syMxlPDQ54fuKIpV",
            'RedirectURI' => "http://localhost:8000/callback",
            'scope' => "com.intuit.quickbooks.accounting",
            'baseUrl' => "Development"
        ));


        /**
         * You can use setMinorVersion() to specify which minor version you want
         *  to use against QuickBooks Online API.
         * default 8
         */
        $dataService->setMinorVersion("9");
        /**
         * Logging is default to be turned ON QuickBooks V3 PHP SDK. You can use
         *  disableLog() to disable logging. tmp
         */
        $dataService->disableLog();
        /**
         * If a request failed, the DataService will record the error on its lastError
         *  object, and developer should always use
         */
        $error = $dataService->getLastError();
        // dd($error) ;
        // If no error it will return false
        if ($error) {
            Log::error($error);
        }
        return $dataService;
    }

    public function index()
    {

        $dataService = $this->dataService();
        // ServiceContext contains all the information associated with the DataService
        $serviceContext = $dataService->getServiceContext();
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $authorizationCodeUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
        // Redirect client to enable him to login in to his acc to auth the sys
        return redirect($authorizationCodeUrl);
    }
    public function callback(Request $request)
    {
        /**
         * DATA RETURNED AFTER  AUTHORIZING
         * 'code' => '',
         *  'state' => '',
         * 'realmId' => '',
         */
        $data = $request->all();

        $dataService = $this->dataService();
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

        /**
         * Pass the returned realmId
         * parameters to “exchangeAuthorizationCodeForToken” method to exchange for OAuth 2 tokens:

         */
        $accessTokenObj = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($data['code'], $data['realmId']);
        // dd($accessTokenObj);

        $request->session()->put('accessTokenObj', $accessTokenObj);
        dd("READY TO DO YOUR STAFF");
    }
    public function prepareDataService()
    {
        $accessTokenObj = session()->get('accessTokenObj');
        //dd( $accessTokenObj ) ;
        // Prep Data Services
        $accessTokenValue = $accessTokenObj['accessTokenKey'];
        $companyId =  $accessTokenObj['realmID'];
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $accessTokenObj['clientID'],
            'ClientSecret' =>  $accessTokenObj['clientSecret'],
            'accessTokenKey' => $accessTokenValue,
            'refreshTokenKey' =>  $accessTokenObj['refresh_token'],
            'QBORealmID' => $companyId,
            'baseUrl' => "Development"
        ));

        return $dataService;
    }
    public function createAccount()
    {
        # Create quickbook accounts
        $dataService = $this->prepareDataService();

        $data = [
            "Name" => "Loves",
            "AccountType" => "Accounts Receivable",
            "AcctNum" => "1120123"
        ];
        $acc = Account::create($data);

        $newAcc =  $dataService->Add($acc);
        dd($newAcc) ;
    }
    public function fetchData()
    {
        # code...
    }
}
