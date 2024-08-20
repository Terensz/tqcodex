<?php

namespace Domain\Customer\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Customer\Models\Contact;
use Domain\User\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // dump('RegisteredUserController store');exit;
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Contact::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user instanceof Contact) {
            event(new Registered($user));
            Auth::login($user);
        }

        return redirect(UserService::getDashboardRoute(UserService::ROLE_TYPE_CUSTOMER));
    }
}
