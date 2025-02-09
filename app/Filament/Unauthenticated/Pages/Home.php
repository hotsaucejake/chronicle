<?php

namespace App\Filament\Unauthenticated\Pages;

use Filament\Pages\Page;

class Home extends Page
{
    protected static string $routePath = '/';

    protected static ?int $navigationSort = -2;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $activeNavigationIcon = 'heroicon-s-home';

    protected static ?string $navigationLabel = 'Home';

    protected static string $view = 'filament.unauthenticated.pages.home';

    protected ?string $heading = '';

    public static function getRoutePath(): string
    {
        return static::$routePath;
    }
}
