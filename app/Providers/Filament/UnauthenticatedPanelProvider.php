<?php

namespace App\Providers\Filament;

use App\Enums\FilamentPanelEnum;
use App\Filament\Unauthenticated\Pages\Login;
use App\Filament\Unauthenticated\Pages\Register;
use Exception;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class UnauthenticatedPanelProvider extends PanelProvider
{
    /**
     * @throws Exception
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id(FilamentPanelEnum::UNAUTHENTICATED->value)
            ->path('')
            ->login(Login::class)
            ->registration(Register::class)
            ->colors([
                'primary' => Color::Blue,
                'info' => Color::Cyan,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger' => Color::Rose,
                'gray' => Color::Slate,
            ])
            ->maxContentWidth('full')
            ->topNavigation()
            ->breadcrumbs(false)
            ->discoverResources(in: app_path('Filament/Unauthenticated/Resources'), for: 'App\\Filament\\Unauthenticated\\Resources')
            ->discoverPages(in: app_path('Filament/Unauthenticated/Pages'), for: 'App\\Filament\\Unauthenticated\\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Unauthenticated/Widgets'), for: 'App\\Filament\\Unauthenticated\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->renderHook(PanelsRenderHook::TOPBAR_END, fn () => auth()->check() ? null : Blade::render('<x-unauthenticated.settings-menu />'))
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ]);
    }
}
