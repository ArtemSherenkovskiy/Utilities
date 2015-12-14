<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());
        \Log::error($validator->fails());
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
            Log::error($validator);
        }

        Auth::login($this->create($request->all()));

        return redirect($this->redirectPath());
        //return $request;
    }
}
