<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
// use quickbooks repository class
use App\Http\Repositories\QuickBooksRepository;
class QuickBooksController extends Controller
{
    // quickbooks controller
    // use quickbooks repository class
    protected $quickbooks;
    public function __construct(QuickBooksRepository $quickbooks)
    {
        $this->quickbooks = $quickbooks;
    }

    // quickbooks initiate connection
    public function initiateConnection()
    {
        $authUrl = $this->quickbooks->getAuthUrl();
        return redirect($authUrl);
    }

    // quickbooks index
    public function index()
    {
        return view('quickbooks.index');
    }


    // quickbooks callback save token
    // quickbooks callback
public function callback(Request $request)
{
   
    // get access token from code 
    $accessToken = $this->quickbooks->getAccessTokenObjFromCode($request->code, $request->realmId);

    // log access token

    $token = Token::where('realm_id', $request->realmId)->first();
    if ($token) {
        $token->access_token = $accessToken->getAccessToken();
        $token->refresh_token = $accessToken->getRefreshToken();
        $token->x_refresh_token_expires_in = $accessToken->getRefreshTokenExpiresAt();
        // $token->id_token = $accessToken->getIdToken();
        $token->code = $request->code;
        $token->save();
    } else {
        Token::create([
            'realm_id' => $request->realmId,
            'access_token' => $accessToken->getAccessToken(),
            'refresh_token' => $accessToken->getRefreshToken(),
            'expires_in' => $accessToken->getAccessTokenExpiresAt(),
            'x_refresh_token_expires_in' => $accessToken->getRefreshTokenExpiresAt(),
            // 'token_type' => $accessToken->getTokenType(),
            // 'id_token' => $accessToken->getIdToken(),
            'code' => $request->code,
        ]);
    }
    return redirect()->route('quickbooks.index');
}


  
    
    // quickbooks refresh
    public function refresh()
    {
        $url = 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer';
        $response = Http::post($url, [
            'client_id' => config('app.quickbooks.client_id'),
            'client_secret' => config('app.quickbooks.client_secret'),
            'redirect_uri' => config('app.quickbooks.redirect_uri'),
            'grant_type' => 'refresh_token',
            'refresh_token' => Token::latest()->first()->refresh_token,
        ]);
        // update token in database
        $token = Token::latest()->first();
        $token->access_token = $response->json()['access_token'];
        $token->refresh_token = $response->json()['refresh_token'];
        $token->x_refresh_token_expires_in = $response->json()['x_refresh_token_expires_in'];
        $token->id_token = $response->json()['id_token'];
        $token->token_type = $response->json()['token_type'];
        $token->expires_in = $response->json()['expires_in'];
        $token->save();
        return redirect()->route('quickbooks.account');
    }
    // quickbooks revoke
    public function revoke()
    {
        $url = 'https://developer.api.intuit.com/v2/oauth2/tokens/revoke';
        $response = Http::post($url, [
            'client_id' => config('app.quickbooks.client_id'),
            'client_secret' => config('app.quickbooks.client_secret'),
            'token' => Token::latest()->first()->access_token,
        ]);
        // delete token from database
        Token::latest()->first()->delete();
        return redirect()->route('quickbooks.account');
    }
    // quickbooks accounts
    public function accounts()
    {
        $accounts = $this->quickbooks->getAccounts() ;
        // return $accounts;
        // convert accounts arrays to object

        return view('quickbooks.account', compact('accounts'));
    }
    // quickbooks single account
    public function account($id)
    {
        $account = $this->quickbooks->getAccount($id);
        // get first object in array
        // $account = dd($account) ;
        return view('quickbooks.single_account', compact('account'));
    }
    // createAccount
    public function createAccount()
    {
        return view('quickbooks.create_account');
    }
    // storeAccount
    public function storeAccount(Request $request)
    {
        // validate request
        $request->validate([
            'name' => 'required',
            'account_type' => 'required',
            // 'account_sub_type' => 'required',
            'description' => 'required',
            'account_number' => 'required',
            'active' => 'required',
            'classification' => 'required',
            'current_balance' => 'required',
            // 'current_balance_with_sub_accounts' => 'nullable',
            'domain' => 'required',
            

        ]);
  
        $this->quickbooks->createAccount($request->all());
        // flash message
        session()->flash('success', 'Account created successfully');
        return redirect()->route('quickbooks.account');
    }
    // editAccount
    public function editAccount($id)
    {
        $account = $this->quickbooks->getAccount($id);
        return view('quickbooks.edit_account', compact('account'));
    }
    

   
   
}
