<?php

namespace Athwari\FilamentZktecoAdms\Filament\Pages;

use Athwari\FilamentZktecoAdms\Filament\Widgets\ZktecoOverviewStats;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class ZktecoDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBarSquare;

    protected static ?int $navigationSort = -1;

    protected static ?string $slug = 'zkteco/dashboard';

    protected string $view = 'filament-zkteco-adms::pages.zkteco-dashboard';

    public static function getNavigationGroup(): ?string
    {
        return __('filament-zkteco-adms::default.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-zkteco-adms::default.navigation.dashboard');
    }

    public function getTitle(): string
    {
        return __('filament-zkteco-adms::default.navigation.dashboard');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ZktecoOverviewStats::class,
        ];
    }
}
