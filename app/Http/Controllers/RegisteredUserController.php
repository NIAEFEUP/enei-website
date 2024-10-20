<?php

// TODO: if Fortify releases new versions that are interesting to use, we should update this file accordingly.

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\RegisterViewResponse;
use Laravel\Fortify\Fortify;

class RegisteredUserController extends Controller
{
    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * Show the registration view.
     */
    public function create(Request $request)
    {

        /**
         * @var RegisterViewResponse
         */
        $response = app(RegisterViewResponse::class);

        $promoter = null;
        if ($request->is('register/promoter/*')) {
            $promoter = $request->route('promoter');

            // TODO: store the code somewhere or do something with the promoter code here
            // Since we do not want to allow users to edit this code we don't need to send it to the frontend

            // One idea is to store the respective promoter in cache (since we already use that) and provide the cache key to the View
            // On submission, that same cache key will be used to associate a given promoter with the newly registered participant.
            // Additional measures can be taken to ensure this system does not get exploited.

            Cookie::queue('', '', 3);
        }

        return $response;
    }

    /**
     * Create a new registered user.
     */
    public function store(Request $request,
        CreatesNewUsers $creator): RegisterResponse
    {
        if (config('fortify.lowercase_usernames')) {
            $request->merge([
                Fortify::username() => Str::lower($request->{Fortify::username()}),
            ]);
        }

        event(new Registered($user = $creator->create($request->all())));

        $this->guard->login($user);

        return app(RegisterResponse::class);
    }
}
