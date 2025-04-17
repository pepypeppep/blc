<?php

namespace App\Providers;

use App\Enums\ThemeList;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Modules\GlobalSetting\app\Models\Setting;
use Modules\GlobalSetting\app\Models\SeoSetting;
use Modules\GlobalSetting\app\Models\MarketingSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('appConfig', function () {
            return DB::table('configs')->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            /** Cache settings */
            $setting = Cache::rememberForever('setting', fn() => (object) Setting::pluck('value', 'key')->all());
            $marketing_setting = Cache::rememberForever('marketing_setting', fn() => (object) MarketingSetting::pluck('value', 'key')->all());
            $seo_setting = Cache::rememberForever('seo_setting', fn() => (object) SeoSetting::all()->groupBy('page_name')->mapWithKeys(function ($group, $pageName) {
                return [$pageName => $group->first()];
            }));

            if ($setting) {
                set_wasabi_config();
                set_aws_config();
            }
        } catch (\Throwable $th) {
            info($th);
            $setting = (object) ['timezone' => config('app.timezone'), 'site_theme' => ThemeList::MAIN->value];
            $marketing_setting = (object) [];
            $seo_setting = (object) [];
        }

        /** Share settings to all views */
        View::composer('*', function ($view) use ($setting, $marketing_setting, $seo_setting) {
            $view->with(['setting' => $setting, 'marketing_setting' => $marketing_setting, 'seo_setting' => $seo_setting]);
        });

        // set timezone
        date_default_timezone_set($setting->timezone ?? config('app.timezone'));

        /** Register custom blade directives */
        $this->registerBladeDirectives();

        // Use Bootstrap 4 pagination
        Paginator::useBootstrapFour();

        // Define default homepage based on site_theme from setting, with fallback
        define('DEFAULT_HOMEPAGE', $setting?->site_theme ?? ThemeList::MAIN->value);

        // Register socialite providers
        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('keycloak', \SocialiteProviders\Keycloak\Provider::class);
        });

        // Register guards
        Auth::viaRequest('keycloak', function (Request $request) {
            if ($request->header('x-username') && App::environment() == 'local') {
                $username = $request->header('x-username');
                $user = User::where('username', $username)->first();
                if ($user) {
                    return $user;
                }

                // $user = Admin::where('username', $username)->first();
                // if ($user) {
                //     return $user;
                // }
                return null;
            }

            try {
                $provider = Socialite::driver('keycloak');
                $userData = $provider
                    ->stateless()
                    ->userFromToken($request->bearerToken());
                $username = $userData->getNickname();

                return User::where('username', $username)->first();
            } catch (\Throwable $throwable) {
                if ($throwable->getCode() != 401) {
                    report($throwable);
                }
            }
            return null;
        });
    }

    protected function registerBladeDirectives()
    {
        Blade::directive('adminCan', function ($permission) {
            return "<?php if(auth()->guard('admin')->user()->can({$permission})): ?>";
        });

        Blade::directive('endadminCan', function () {
            return '<?php endif; ?>';
        });

        // Blade directive for checking the current theme
        Blade::directive('theme', function ($themes) {
            return "<?php if(in_array(DEFAULT_HOMEPAGE, {$themes})): ?>";
        });

        Blade::directive('endtheme', function () {
            return '<?php endif; ?>';
        });
    }
}
