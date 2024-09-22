<?php

declare(strict_types=1);

namespace App\Providers;

use App\MoonShine\Resources\AttractionResource;
use App\MoonShine\Resources\CategorieResource;
use App\MoonShine\Resources\CitieResource;
use App\MoonShine\Resources\FoodResource;
use App\MoonShine\Resources\ImageResource;
use App\MoonShine\Resources\PosterResource;
use App\MoonShine\Resources\RouterpointResource;
use App\MoonShine\Resources\RouterResource;
use App\MoonShine\Resources\ShopingResource;
use App\MoonShine\Resources\TypeResource;
use App\MoonShine\Resources\UsersResource;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;
use MoonShine\Contracts\Resources\ResourceContract;
use MoonShine\Menu\MenuElement;
use MoonShine\Pages\Page;
use Closure;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    /**
     * @return list<ResourceContract>
     */
    protected function resources(): array
    {
        return [
            new UsersResource(),
            new CitieResource(),
            new FoodResource(),
            new TypeResource(),
        ];
    }

    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [];
    }

    /**
     * @return Closure|list<MenuElement>
     */
    protected function menu(): array
    {
        return [
            MenuGroup::make(static fn() => __('moonshine::ui.resource.system'), [
                MenuItem::make(
                    static fn() => __('moonshine::ui.resource.admins_title'),
                    new MoonShineUserResource()
                ),
                MenuItem::make(
                    static fn() => __('moonshine::ui.resource.role_title'),
                    new MoonShineUserRoleResource()
                ),
                MenuItem::make('Пользователи', new UsersResource()),
            ])->canSee(fn() => request()->routeIs('moonshine.*')),
            MenuGroup::make('Карточки', [
                MenuItem::make('Города', new CitieResource()),
                MenuItem::make('Типы', new TypeResource()),
                MenuItem::make('Категории', new CategorieResource()),
                MenuItem::make('Еда', new FoodResource()),
                MenuItem::make('Достопримечательности', new AttractionResource()),
                MenuItem::make('Сувениры и шопинг', new ShopingResource()),
                MenuItem::make('Афиша', new PosterResource()),
                MenuItem::make('Маршруты', new RouterResource()),
                MenuItem::make('Точки маршрута', new RouterpointResource()),
                MenuItem::make('Изображения', new ImageResource()),

            ]),

//            MenuItem::make('Documentation', 'https://moonshine-laravel.com/docs')
//                ->badge(fn() => 'Check')
//                ->blank(),
        ];
    }

    /**
     * @return Closure|array{css: string, colors: array, darkColors: array}
     */
    protected function theme(): array
    {
        return [];
    }
}
