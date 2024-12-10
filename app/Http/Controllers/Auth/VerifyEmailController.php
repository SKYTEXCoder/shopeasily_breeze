<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $intendedUrl = session()->get('url.intended');

        if ($request->user()->hasVerifiedEmail()) {

            if ($intendedUrl && str_contains($intendedUrl, 'admin')) {
                return redirect()->route('index');
            }

            return redirect()->intended(route('index', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        if ($intendedUrl && str_contains($intendedUrl, 'admin')) {
            return redirect()->route('index');
        }

        return redirect()->intended(route('index', absolute: false).'?verified=1');
    }
}
