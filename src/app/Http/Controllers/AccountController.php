<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Log;
use App\Models\Account;
use Aws\Sts\StsClient;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = Account::all();
        return view('accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'access_key_id' => 'required|max:255',
            'secret_access_key' => 'required|max:255',
        ]);
        Account::create($request->all());
        return redirect()->route('accounts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $account = Account::findOrFail($id);
        return view('accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|max:255',
            'access_key_id' => 'required|max:255',
            'secret_access_key' => 'required|max:255',
        ]);

        $account->name = $request->name;
        $account->access_key_id = $request->access_key_id;
        $account->secret_access_key = $request->secret_access_key;
        $account->save();

        return redirect()->route('accounts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Account::findOrFail($id)->delete();
        return redirect()->route('accounts.index');
    }

    /**
     * Login to the specified account.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function login($id)
    {
        $account = Account::findOrFail($id);
        $name = Auth::user()->name;
        try {
            $url = $this->getSigninTokenURL($account, $name);
        } catch (\Exception $e) {
            Log::warning($e);
            return redirect()->route('accounts.index');
        }
        return redirect($url);
    }

    /**
     * Generate signin token url and return it.
     *
     * @param  \App\Models\Account  $account
     * @param  string  $name
     * @return string URL
     */
    public function getSigninTokenURL(Account $account, $name)
    {
        $client = StsClient::factory([
            'version' => 'latest',
            'region'  => 'ap-northeast-1',
            'credentials' => [
                'key' => $account->access_key_id,
                'secret' => $account->secret_access_key,
            ],
        ]);
        $result = $client->getFederationToken([
            'Name' => $name,
            'Policy' => json_encode(json_decode(Account::DEFAULT_POLICY)),
            'DurationSeconds' => 3600,
        ]);
        $credentials = $result->get('Credentials');
        $session_json = rawurlencode(json_encode([
            'sessionId' => $credentials['AccessKeyId'],
            'sessionKey' => $credentials['SecretAccessKey'],
            'sessionToken' => $credentials['SessionToken'],
        ]));
        $get_signin_token_url = Account::SIGNIN_URL.'?Action=getSigninToken&Session='.$session_json;
        $returned_content = file_get_contents($get_signin_token_url);
        $signin_token = rawurlencode(json_decode($returned_content)->SigninToken);
        $issuer = rawurlencode(config('app.url'));
        $destination = rawurlencode(Account::CONSOLE_URL);
        $url = Account::SIGNIN_URL.'?Action=login&Issuer='.$issuer.'&Destination='.$destination.'&SigninToken='.$signin_token;

        return $url;
    }
}
