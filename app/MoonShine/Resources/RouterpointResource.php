<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Citie;
use App\Models\Food;
use App\Models\Router;
use App\Models\Type;
use Illuminate\Database\Eloquent\Model;
use App\Models\Routerpoint;

use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\Date;
use MoonShine\Fields\Image;
use MoonShine\Fields\Json;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Select;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Text;
use MoonShine\Metrics\ValueMetric;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<Routerpoint>
 */
class RouterpointResource extends ModelResource
{
    protected string $model = Routerpoint::class;

    protected string $title = 'Routerpoints';

    protected string $sortColumn = 'id';

    protected bool $columnSelection = true;

    protected string $column = 'name';

    public array $with = [
        'router',
    ];

    public function fields(): array
    {
        return [
            Block::make([
                Tabs::make([
                    Tab::make(__('moonshine::ui.resource.main_information'), [
                        ID::make()
                            ->sortable()
                            ->showOnExport(),
                        Text::make(('Заголовок'), 'title')
                            ->sortable()
                            ->required()
                            ->updateOnPreview()
                            ->showOnExport(),
                        BelongsTo::make(
                            __('Маршрут'),
                            'router',
                            static fn (Router $model) => $model->name,
                            new RouterResource(),
                        ),
                        Number::make(__('Номер точки'), 'routerpoints_id')
                            ->required()
                            ->showOnExport(),
                        Text::make(('Предварительное описание'), 'descPreview')
                            ->required()
                            ->hideOnIndex()
                            ->showOnExport(),
                        Text::make(('Описание'), 'description')
                            ->required()
                            ->hideOnIndex()
                            ->showOnExport(),
                        Text::make(__('Широта'), 'latitude')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Text::make(__('Долгота'), 'longitude')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Number::make(__('Время'), 'time')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Image::make(__('Изображение'), 'image')
                            ->showOnExport()
                            ->disk(config('moonshine.disk', ''))
                            ->dir('images')
                            ->allowedExtensions(['jpg', 'png', 'jpeg']),
                        Date::make(__('moonshine::ui.resource.created_at'), 'created_at')
                            ->format("d.m.Y")
                            ->default(now()->toDateTimeString())
                            ->sortable()
                            ->hideOnForm()
                            ->showOnExport(),
                    ]),
                ]),
            ]),
        ];
    }

    public function formFields(): array
    {
        return [
            Block::make([
                ID::make()
                    ->showOnExport(),
                Text::make(('Заголовок'), 'title')
                    ->nullable()
                    ->updateOnPreview()
                    ->showOnExport(),
                BelongsTo::make(
                    __('Маршрут'),
                    'router',
                    static fn (Router $model) => $model->name,
                    new RouterResource(),
                )->nullable(),
                Number::make(__('Номер точки маршрута'), 'routerpoints_id')
                    ->nullable()
                    ->showOnExport()
                    ->hideOnIndex(),
                Text::make(('Предварительное описание'), 'descPreview')
                    ->nullable()
                    ->hideOnIndex()
                    ->showOnExport(),
                Text::make(('Описание'), 'description')
                    ->nullable()
                    ->showOnExport(),
                Text::make(__('Широта'), 'latitude')
                    ->nullable()
                    ->showOnExport()
                    ->hideOnIndex(),
                Text::make(__('Долгота'), 'longitude')
                    ->nullable()
                    ->showOnExport()
                    ->hideOnIndex(),
                Number::make(__('Время'), 'time')
                    ->nullable()
                    ->showOnExport()
                    ->hideOnIndex(),
                Image::make(__('Изображение'), 'image')
                    ->showOnExport()
                    ->disk(config('moonshine.disk', ''))
                    ->dir('images')
                    ->allowedExtensions(['jpg', 'png', 'jpeg']),
            ]),
        ];
    }

    public function filters(): array //сделать
    {
        return [
            Text::make('Заголовок', 'title'),
            BelongsTo::make(
                __('Маршрут'),
                'router',
                static fn (Router $model) => $model->name,
                new RouterResource(),
            ),
        ];
    }

    public function metrics(): array
    {
        return [
            ValueMetric::make('Количество точек')
                ->value(Routerpoint::count()),
        ];
    }

    /**
     * @param Food $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [
            'router_id' => 'required|integer',
            'routerpoints_id' => 'required|integer',
            'title' => 'nullable|string|max:50',
            'descPreview' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric', // images
            'time' => 'nullable|integer',
        ];
    }
}
