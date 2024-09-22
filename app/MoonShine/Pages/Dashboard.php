<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\Pages\Page;
use MoonShine\Components\MoonShineComponent;

class Dashboard extends Page
{
    /**
     * @return array<string, string>
     */
    public function breadcrumbs(): array
    {
        return [
            'Admin Panel' => $this->title()
        ];
    }

    public function title(): string
    {
        return $this->title ?: 'Admin Panel';
    }

    /**
     * @return list<MoonShineComponent>
     */
    public function components(): array
	{
		return [];
	}
}