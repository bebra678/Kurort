<?php

declare(strict_types=1);

namespace App\Providers;

use App\MoonShine\Resources\AttractionResource;
use App\MoonShine\Resources\CategorieResource;
use App\MoonShine\Resources\CitieResource;
use App\MoonShine\Resources\FoodResource;
use App\MoonShine\Resources\ImageResource;
use App\MoonShine\Resources\PosterResource;
use App\MoonShine\Resources\ReviewResource;
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
                MenuItem::make('Пользователи', new UsersResource())->icon('heroicons.NewUsers'),
            ])->canSee(fn() => request()->routeIs('moonshine.*')),
            MenuGroup::make('Карточки', [
                MenuItem::make('Города', new CitieResource())->icon('heroicons.NewCity'),
                MenuItem::make('Типы', new TypeResource())->icon('heroicons.NewType'),
                MenuItem::make('Категории', new CategorieResource())->icon('heroicons.NewCategory'),
                MenuItem::make('Еда', new FoodResource())->icon('heroicons.NewFood'),
                MenuItem::make('Достопримечательности', new AttractionResource())->icon('heroicons.NewAttrac'),
                MenuItem::make('Сувениры и шопинг', new ShopingResource())->icon('heroicons.NewShop'),
                MenuItem::make('Афиша', new PosterResource())->icon('heroicons.NewPosters'),
                MenuItem::make('Маршруты', new RouterResource())->icon('heroicons.NewRoutes'),
                MenuItem::make('Точки маршрута', new RouterpointResource())->icon('heroicons.NewRouterPoints'),
                MenuItem::make('Изображения', new ImageResource())->icon('heroicons.NewImage'),
                MenuItem::make('Отзывы', new ReviewResource())->icon('heroicons.star'),
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
