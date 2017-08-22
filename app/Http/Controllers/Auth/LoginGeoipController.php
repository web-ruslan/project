<?php

namespace App\Http\Controllers\Auth;

use App\Events\eventTrigger;
use App\Pusher;
use App\UserGeoipLog;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Socialite;
use GeoIP;
use Illuminate\Support\Facades\Redis;

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
        Redis::del('geoip_logs');
        if(!$geoip_logs = Redis::lrange('geoip_logs', 0 , -1)){
            $geoip_logs = UserGeoipLog::with('user')->select(['login_date','user_id'])->orderBy('login_date', 'decs')->get();
            foreach ($geoip_logs as $one) {
                $result[] = Carbon::createFromTimestamp($one->login_date)->toDateString(). ' :: ' .$one->user->name;
            }

            Redis::pipeline(function ($pipe) use ($result) {
                foreach($result as $key => $val){
                    $pipe->rpush('geoip_logs', $val);
                }
            });
            $geoip_logs = Redis::lrange('geoip_logs', 0, -1);
        }

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


        if (!$log_exist) {
            $geoip_log = new UserGeoipLog([
                'user_id' => $user->id,
                'country' => GeoIP::getLocation(Request()->ip())['country'],
                'login_date' => Carbon::today()->timestamp]);

            $geoip_log->save();
            event(new eventTrigger(Carbon::today()->toDateString().' :: '.$user->name));
            Redis::del('geoip_logs');
        }

        $success = [$this->username() => trans('auth.success')];

        if ($request->expectsJson()) {
            return response()->json($success, 200);
        }
        return json_encode($success);
    }
}
