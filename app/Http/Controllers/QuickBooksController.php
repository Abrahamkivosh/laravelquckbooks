<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Account;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Vendor;

class QuickBooksController extends Controller
{
    protected function dataService()
    {
        // Prep Data Services
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => "ABFwF5Bo78Y6oTjAgasqcjtxjR8TTqYiR28NQ4Db6cBKRSDFZQ",
            'ClientSecret' => "DdhLpNAa3cIjOCeY1232F14xAiB6FcgO4KQT4JJf",
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
        // dd($dataService);
        // ServiceContext contains all the information associated with the DataService
        $serviceContext = $dataService->getServiceContext();
        // dd($serviceContext);
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

        // dd($OAuth2LoginHelper);
        $authorizationCodeUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
        // dd($authorizationCodeUrl);
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
        Log::info($data);
        $dataService = $this->dataService();

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

        /**
         * Pass the returned realmId
         * parameters to “exchangeAuthorizationCodeForToken” method to exchange for OAuth 2 tokens:

         */
        $accessTokenObj = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($data['code'], $data['realmId']);

        // dd($accessTokenObj->getAccessToken());
        // dd($accessTokenObj['accessTokenKey']);

        $token = Token::all();


            Token::create([
                'qb_token' => $accessTokenObj->getAccessToken(),
                'company_id' => $accessTokenObj->getRealmID(),
                'refresh_token' => $accessTokenObj->getRefreshToken()
            ]);




        // dd($accessTokenObj);

        // $request->session()->put('accessTokenObj', $accessTokenObj);
        // dd("READY TO DO YOUR STAFF");

        return redirect()->route('mtaani');
    }
    public function prepareDataService()
    {
        // $accessTokenObj = session()->get('accessTokenObj');
        $accessTokenObj = Token::query()->latest()->get()->take(1);

        $accessTokenObj =  $accessTokenObj[0] ;
        // Prep Data Services
        $accessTokenValue = $accessTokenObj->qb_token;
        $companyId =  $accessTokenObj->company_id;
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => "ABFwF5Bo78Y6oTjAgasqcjtxjR8TTqYiR28NQ4Db6cBKRSDFZQ",
            'ClientSecret' =>  "DdhLpNAa3cIjOCeY1232F14xAiB6FcgO4KQT4JJf",
            'accessTokenKey' => $accessTokenValue,
            'refreshTokenKey' =>  $accessTokenObj->refresh_token,
            'QBORealmID' => $companyId,
            'baseUrl' => "Development"
        ));

        return $dataService;
    }
    public function createAccount()
    {
        # Create quickbook accounts
        $dataService = $this->prepareDataService();
        // dd($dataService);

        $dataService->throwExceptionOnError(true);
        //Add a new Vendor
        $theResourceObj = Vendor::create([
            "BillAddr" => [
                "Line1" => "Dianne's Auto Shop",
                "Line2" => "Dianne Bradley",
                "Line3" => "29834 Mustang Ave.",
                "City" => "Millbrae",
                "Country" => "U.S.A",
                "CountrySubDivisionCode" => "CA",
                "PostalCode" => "94030"
            ],
            "TaxIdentifier" => "99-5688293",
            "AcctNum" => "35372649",
            "Title" => "Ms.",
            "GivenName" => "Dianne",
            "FamilyName" => "Bradley",
            "Suffix" => "Sr.",
            "CompanyName" => "Dianne's Auto Shop",
            "DisplayName" => "Dianne's Auto Shop",
            "PrintOnCheckName" => "Dianne's Auto Shop",
            "PrimaryPhone" => [
                "FreeFormNumber" => "(650) 555-2342"
            ],
            "Mobile" => [
                "FreeFormNumber" => "(650) 555-2000"
            ],
            "PrimaryEmailAddr" => [
                "Address" => "dbradley@myemail.com"
            ],
            "WebAddr" => [
                "URI" => "http://DiannesAutoShop.com"
            ]
        ]);

        $resultingObj = $dataService->Add($theResourceObj);
        $error = $dataService->getLastError();
        if ($error) {
            echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
            echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            echo "The Response message is: " . $error->getResponseBody() . "\n";
        } else {
            echo "Created Id={$resultingObj->Id}. Reconstructed response body:\n\n";
            $xmlBody = XmlObjectSerializer::getPostXmlFromArbitraryEntity($resultingObj, $urlResource);
            echo $xmlBody . "\n";
        }
    }


    // this one working
    public function testAccount()
    {
        # Create quickbook accounts
        $dataService = $this->prepareDataService();
        // dd($dataService);

        $dataService->throwExceptionOnError(true);

        //Add a new Vendor
        $theResourceObj = Account::create([

            "AccountType"=> "Accounts Receivable",
            "Name"=> "MyJobs_Abraham",
            "SyncToken"=> 1,
            "AcctNum"=>"0707585566"




        ]);

        $resultingObj = $dataService->Add($theResourceObj);
        $error = $dataService->getLastError();

        if ($error) {
            echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
            echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            echo "The Response message is: " . $error->getResponseBody() . "\n";
        } else {
            echo "Created Id={$resultingObj->Id}. Reconstructed response body:\n\n";
            $xmlBody = XmlObjectSerializer::getPostXmlFromArbitraryEntity($resultingObj, $urlResource);
            echo $xmlBody . "\n";
        }
    }
    public function fetchAccount($accountId)
    {
        // dd($accountId);
        # Create quickbook accounts
        $dataService = $this->prepareDataService();
        $allInvoices = $dataService->Query("SELECT * FROM Account");
        return $allInvoices ;
    //    $account =  $dataService->FindById("Account", 93);
    //    return $account ;
    }
    public function fetchData()
    {
        # code...
    }
}
