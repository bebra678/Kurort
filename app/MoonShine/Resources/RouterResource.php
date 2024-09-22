<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Attraction;
use App\Models\Citie;
use App\Models\Food;
use App\Models\Type;
use Illuminate\Database\Eloquent\Model;
use App\Models\Router;

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
 * @extends ModelResource<Router>
 */
class RouterResource extends ModelResource
{
    protected string $model = Router::class;

    protected string $title = 'Routers';

    protected string $sortColumn = 'id';

    protected bool $columnSelection = true;

    protected string $column = 'name';

    public array $with = [
        'city',
        'type',
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
                        Text::make(('Название'), 'name')
                            ->sortable()
                            ->required()
                            ->updateOnPreview()
                            ->showOnExport(),
                        BelongsTo::make(
                            __('Город'),
                            'city',
                            static fn (Citie $model) => $model->name,
                            new CitieResource(),
                        ),
                        BelongsTo::make(
                            ('Тип'),
                            'type',
                            static fn (Type $model) => $model->name,
                            new TypeResource(),
                        ),
                        Text::make(__('titleDesc'), 'titleDesc')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Switcher::make(__('В топе?'), 'isTop')
                            ->showOnExport()
                            ->hideOnIndex(),
                        Switcher::make(__('Есть парковка?'), 'isParking')
                            ->showOnExport()
                            ->hideOnIndex(),
                        Text::make(__('Совет'), 'advice')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Text::make(__('Описание'), 'description')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Text::make(__('Краткое описание'), 'previewDescription')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Text::make(__('Процент скидки'), 'percent')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Text::make(__('Кратки текст скидки'), 'preview')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Text::make(__('Текст скидки'), 'text')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Text::make(__('Номер'), 'phone')
                            ->required()
                            ->showOnExport()
                            ->hint('+7 (999) 999-99-99')
                            ->hideOnIndex(),
                        Text::make(__('Сайт'), 'site')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Json::make('Соц сети', 'social')
                            ->hideOnIndex()
                            ->onlyValue(),
                        Number::make(__('totalTime'), 'totalTime')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Number::make(__('totalDistance'), 'totalDistance')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Number::make(__('anyIndex'), 'anyIndex')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Json::make('Верификация', 'verified')
                            ->hideOnIndex()
                            ->fields([
//                        Position::make(),
                                Select::make('isVerified', 'isVerified')
                                    ->options([
                                        'true' => 'true',
                                        'false' => 'false'
                                    ]),
                                Select::make('isOwnerReeps', 'isOwnerReeps')
                                    ->options([
                                        'true' => 'true',
                                        'false' => 'false'
                                    ]),
                                Select::make('isKurort26', 'isKurort26')
                                    ->options([
                                        'true' => 'true',
                                        'false' => 'false'
                                    ]),
                                Date::make('date'),
                            ]),
                        Json::make('Причины посетить', 'reasonsVisit')
                            ->hideOnIndex()
                            ->onlyValue(),
                        Text::make(__('chooseCurort26'), 'chooseCurort26')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Text::make(__('Функции'), 'features')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Image::make(__('Изображение карты'), 'imageMap')
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
                Text::make(__('Название'), 'name')
                    ->required()
                    ->showOnExport(),
                BelongsTo::make(
                    __('Город'),
                    'city',
                    static fn (Citie $model) => $model->name,
                    new CitieResource(),
                ),
                BelongsTo::make(
                    ('Тип'),
                    'type',
                    static fn (Type $model) => $model->name,
                    new TypeResource(),
                ),
                Text::make(__('titleDesc'), 'titleDesc')
                    ->hint('Введите значения через запятую')
                    ->nullable()
                    ->showOnExport(),
                Switcher::make(__('В топе?'), 'isTop')
                    ->showOnExport(),
                Switcher::make(__('Есть парковка?'), 'isParking')
                    ->showOnExport(),
                Text::make(__('Описание'), 'description')
                    ->nullable()
                    ->showOnExport(),
                Text::make(__('Совет'), 'advice')
                    ->nullable()
                    ->showOnExport(),
                Text::make(__('Краткое описание'), 'previewDescription')
                    ->nullable()
                    ->showOnExport(),
                Text::make(__('Процент скидки'), 'percent')
                    ->nullable()
                    ->showOnExport(),
                Text::make(__('Кратки текст скидки'), 'preview')
                    ->nullable()
                    ->showOnExport(),
                Text::make(__('Текст скидки'), 'text')
                    ->nullable()
                    ->showOnExport(),
                Text::make(__('Номер'), 'phone')
                    ->nullable()
                    ->showOnExport()
                    ->hint('+7 (999) 999-99-99'),
                Text::make(__('Сайт'), 'site')
                    ->nullable()
                    ->showOnExport(),
                Json::make('Соц сети', 'social')
                    ->nullable()
                    ->onlyValue(),
                Number::make(__('totalTime'), 'totalTime')
                    ->nullable()
                    ->showOnExport(),
                Number::make(__('totalDistance'), 'totalDistance')
                    ->nullable()
                    ->showOnExport(),
                Number::make(__('anyIndex'), 'anyIndex')
                    ->nullable()
                    ->showOnExport(),
                Json::make('Верификация', 'verified')
                    ->fields([
                        Select::make('isVerified', 'isVerified')
                            ->options([
                                'true' => 'true',
                                'false' => 'false'
                            ]),
                        Select::make('isOwnerReeps', 'isOwnerReeps')
                            ->options([
                                'true' => 'true',
                                'false' => 'false'
                            ]),
                        Select::make('isKurort26', 'isKurort26')
                            ->options([
                                'true' => 'true',
                                'false' => 'false'
                            ]),
                        Date::make('date'),
                    ]),
                Json::make('Причины посетить', 'reasonsVisit')
                    ->nullable()
                    ->onlyValue(),
                Text::make(__('chooseCurort26'), 'chooseCurort26')
                    ->nullable()
                    ->showOnExport(),
                Text::make(__('Функции'), 'features')
                    ->nullable()
                    ->hint('Введите значения через запятую')
                    ->showOnExport(),
                Image::make(__('Изображение карты'), 'imageMap')
                    ->showOnExport()
                    ->disk(config('moonshine.disk', ''))
                    ->dir('images')
                    ->allowedExtensions(['jpg', 'png', 'jpeg']),
            ]),
        ];
    }

    public function filters(): array
    {
        return [
            Text::make('Название', 'name'),
            //Text::make('Город', 'city_id'),
            BelongsTo::make(
                __('Город'),
                'city',
                static fn (Citie $model) => $model->name,
                new CitieResource(),
            )->nullable(),
            BelongsTo::make(
                ('Тип'),
                'type',
                static fn (Type $model) => $model->name,
                new TypeResource(),
            )->nullable(),
        ];
    }

    public function metrics(): array
    {
        return [
            ValueMetric::make('Количество заведений')
                ->value(Router::count()),
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
            'name' => 'required|string|max:255',
            'titleDesc' => 'nullable|string|max:30',
            'city_id' => 'required|integer',
            'type_id' => 'required|integer',
            'isTop' => 'nullable|between:0,1',
            'description' => 'nullable|string|max:500',
            'previewDescription' => 'nullable|string|max:255',
            'percent' => 'nullable|integer',
            'totalTime' => 'nullable|integer',
            'totalDistance' => 'nullable|integer',
            'anyIndex' => 'nullable|integer',
            'preview' => 'nullable|string|max:255',
            'text' => 'nullable|string|max:255',
            'site' => 'nullable|string|max:255',
            'advice' => 'nullable|string|max:255',
            //'imageMap' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'social' => 'nullable',
            'verified' => 'nullable',
            'reasonsVisit' => 'nullable',
            'chooseCurort26' => 'nullable|string|max:255',
            'features' => 'nullable|string|max:255',
            'isParking' => 'nullable|between:0,1',
        ];
    }
}
