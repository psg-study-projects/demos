<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Validator;
//use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

//use App\Http\Controllers\Controller;
use App\Http\Controllers\SiteController;
use App\Models\User;
use App\Models\Role;
use App\Models\Invite;
use App\Mail\Registered as MailRegistered;

class RegisterController extends SiteController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    //use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    // Create a new user instance after a valid registration.
    protected function create(array $data)
    {
        $mtsRole = Role::where('name','=','mts')->firstOrFail();

        $user = User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        $user->attachRole($mtsRole);
        return $user;
    }

// From trait RedirectsUsers
    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
// From trait RegistersUsers
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
        Mail::to($user->email)->queue(new MailRegistered($user));
    }


    // =======

    public function showRegisterByInviteForm(Request $request, $token)
    {
//dd('showRegisterByInviteForm',$token);
        $invite = Invite::where('token',$token)->where('is_accepted',0)->first();
        if ( empty($invite) ) {
            //return redirect()->route('login');
            return redirect(route('login'));
        }
        if ( $invite->is_accepted ) {
            return redirect(route('login'));
        }

        //$data = [];
        //$data['token'] = $token;

        return view('auth.register_by_invite',[
            'invite'=>$invite,
        ]);

    } // showRegisterByInviteForm()

    public function registerByInvite(Request $request)
    {
        $extraRules =  [
            'token' => 'required',
        ];

//$t = $request->all();
//dd('request',$t);
        $validator = $this->validator($request->all(),$extraRules);
        $validator->validate();

        $invite = Invite::where('token',$request->token)->first();

        // Confirm token matches email
        $validator->after(function($validator) use($invite) {
            if ( empty($invite) ) {
                $validator->errors()->add('token', 'Error: Token does not match email');
            }
        });

        event(new \Illuminate\Auth\Events\Registered($user = $this->createByInvite($request->all(),$invite)));

        $this->guard()->login($user);

        return $this->registered($request, $user) ?: redirect($this->redirectPath());

    } // registerByInvite()

    protected function createByInvite(array $data, Invite $invite)
    {
        if ( $invite->is_accepted ) {
            throw new \Exception('Can not process invite-based registration');
        }

        //$invite = \App\Models\Invite::findOrFail($invitePKID);
        $user = \DB::transaction(function() use($data,$invite) {
            $role = Role::findOrFail($invite->role->id);
            $user = User::create([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'is_confirmed'=> 1,
            ]);
            $user->roles()->attach($role->id);

            // Update Invite
            $invite->is_accepted = 1;
            $invite->accepted_at = $timestamp = date('Y-m-d H:i:s');
            $invite->save();

            return $user;
        });

        return $user;
        /*
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
         */
    }

}
