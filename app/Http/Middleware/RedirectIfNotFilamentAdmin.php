<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Session;
use Log;

class RedirectIfNotFilamentAdmin extends Middleware
{

    /**
     * @param  array<string>  $guards
     */
    protected function authenticate($request, array $guards)
    {
        $auth = Filament::auth();

        if (!$auth->check()) {
            Log::debug('Detected unauthenticated user.');
            $this->unauthenticated($request, $guards);

            return;
        }

        $this->auth->shouldUse(Filament::getAuthGuard());

        /** @var Model $user */
        $user = $auth->user();
        Log::debug('Detected authenticated User with User ID: ' . $user->id);

        $panel = Filament::getCurrentPanel();

        if ($user instanceof FilamentUser) {
            Log::debug('Environment: ' . config('app.env'));
            Log::debug('Can Access Panel: ' . $user->canAccessPanel($panel));
            if (!$user->canAccessPanel($panel) && config('app.env') !== 'local') {
                Log::debug('User does not have access to the panel');
                return redirect(route('index'));
            }
        }
    }

    protected function redirectTo($request): ?string
    {
        return Filament::getLoginUrl();
    }
}
