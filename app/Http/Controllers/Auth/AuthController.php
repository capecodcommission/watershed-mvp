<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Embayment;
use Auth;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use DB;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/start';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);

        $embayments = Embayment::orderBy('EMBAY_DISP')->get();
        $groupedEmbayments = array();
        foreach($embayments as $embayment) {
            $groupedEmbayments[$embayment['Region']][] = $embayment;
        }

		session()->forget('scenarioid');
		session()->forget('n_removed');
		session()->forget('fert_applied');
        session()->forget('storm_applied');
        
        view()->share('groupedEmbayments', $groupedEmbayments);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        Validator::extend('unique_email', function($attribute, $value, $parameters)
        {
            // check the users table to make sure the email address is unique
            $email_exists = DB::select('select count(user_id) as user_count from dbo.Scenario_Users where email = \'' . $value . '\'');

            if ($email_exists[0]->user_count > 0) 
            {
                return false;    
            }
            else
            {
                return true;   
            }

        });
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique_email',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
