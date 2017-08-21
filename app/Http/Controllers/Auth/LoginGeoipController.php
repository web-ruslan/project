<?php

namespace App\Http\Controllers\Auth;

use App\Pusher;
use App\UserGeoipLog;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Socialite;
use GeoIP;

class LoginGeoipController extends LoginController
{

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function showLoginForm()
    {
        $geoip_logs = UserGeoipLog::with('user')->get();
        return view('auth.login_geoip', ['geoip_logs' => $geoip_logs]);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user)
    {
        $log_exist = UserGeoipLog::whereUserId($user->id)
            ->whereLoginDate(Carbon::today()->timestamp)
            ->first();
        Pusher::sendDataToServer(['topic_id' => 'onNewLog','data' => Carbon::today()->timestamp.' :: '.$user->name]);
        if (!$log_exist) {
            $geoip_log = new UserGeoipLog([
                'user_id' => $user->id,
                'country' => GeoIP::getLocation(Request()->ip())['country'],
                'login_date' => Carbon::today()->timestamp]);

            $geoip_log->save();
            //Pusher::sendDataToServer(['topic_id' => 'onNewLog','data' => Carbon::today()->timestamp.' :: '.$user->name]);
        }

        $success = [$this->username() => trans('auth.success')];

        if ($request->expectsJson()) {
            return response()->json($success, 200);
        }
        return json_encode($success);
    }
}
