<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Poster;


use App\Models\Citie;
use App\Models\Type;
use App\Models\Food;
use MoonShine\Decorations\Heading;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\Date;
use MoonShine\Fields\Email;
use MoonShine\Fields\Image;
use MoonShine\Fields\Number;
use MoonShine\Fields\Password;
use MoonShine\Fields\PasswordRepeat;
use MoonShine\Fields\Position;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Models\MoonshineUserRole;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Resources\MoonShineUserRoleResource;
use MoonShine\Metrics\ValueMetric;
use MoonShine\Fields\Json;
use MoonShine\Fields\Select;
use Illuminate\Http\Request;

/**
 * @extends ModelResource<Poster>
 */
class PosterResource extends ModelResource
{
    protected string $model = Poster::class;

    protected string $title = 'Posters';

    protected string $sortColumn = 'id';

    protected bool $columnSelection = true;

    protected string $column = 'name';

    public array $with = [
        'city',
        'type',
    ];

    /**
     * @return list<MoonShineComponent|Field>
     */
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
                        Text::make(__('Название учреждение'), 'nameInstitution')
                            ->required()
                            ->hideOnIndex()
                            ->showOnExport(),
                        Text::make(__('Адрес'), 'address')
                            ->required()
                            ->showOnExport(),
//                        Text::make(__('Адрес кассы'), 'addressesTicet')
//                            ->required()
//                            ->hideOnIndex()
//                            ->showOnExport(),
                        Json::make('Адрес кассы', 'addressesTicet')
                            ->required()
                            ->hideOnIndex()
                            ->onlyValue(),
                        Number::make('Ограничение по возрасту', 'restrictions')
                            ->required()
                            ->hideOnIndex()
                            ->min(0)
                            ->max(100),
                        Text::make(__('Сайт'), 'site')
                            ->required()
                            ->hideOnIndex()
                            ->showOnExport(),
                        Json::make('Начало мероприятия', 'begin')
                            ->required()
                            ->hideOnIndex()
                            ->hint('Укажите дату начала по типу: 2023-05-15T18:00:00.000Z')
                            ->onlyValue(),
                        Text::make(__('titleDesc'), 'titleDesc')
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
                        Text::make(__('Широта'), 'latitude')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Text::make(__('Долгота'), 'longitude')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Text::make(__('Номер'), 'phone')
                            ->required()
                            ->showOnExport()
                            ->hint('+7 (999) 999-99-99')
                            ->hideOnIndex(),
                        Json::make('Соц сети', 'social')
                            ->hideOnIndex()
                            ->onlyValue(),
                        Text::make(__('Максимальный чек'), 'maxCheck')
                            ->required()
                            ->showOnExport()
                            ->hint('Средний чек строиться из двух значений (Максимальный чек и Минимальный чек), если нет одного, то заполнить только Максимальный чек')
                            ->hideOnIndex(),
                        Text::make(__('Минимальный чек'), 'minCheck')
                            ->required()
                            ->showOnExport()
                            ->hideOnIndex(),
                        Json::make('Верификация', 'verified')
                            ->hideOnIndex()
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
                Text::make(__('Название учреждение'), 'nameInstitution')
                    ->nullable()
                    ->showOnExport(),
                Text::make(__('Адрес'), 'address')
                    ->nullable()
                    ->showOnExport(),
//                Text::make(__('Адрес кассы'), 'addressesTicet')
//                    ->nullable()
//                    ->showOnExport(),
                Json::make('Адрес кассы', 'addressesTicet')
                    ->nullable()
                    ->onlyValue(),
                Number::make('Ограничение по возрасту', 'restrictions')
                    ->nullable()
                    ->min(0)
                    ->max(100),
                Text::make(__('titleDesc'), 'titleDesc')
                    ->hint('Введите значения через запятую')
                    ->nullable()
                    ->showOnExport(),
                Json::make('Начало мероприятия', 'begin')
                    ->nullable()
                    ->onlyValue(),
                Text::make(__('Описание'), 'description')
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
                Text::make(__('Широта'), 'latitude')
                    ->hint('Пример: 43.9003')
                    ->nullable()
                    ->showOnExport(),
                Text::make(__('Долгота'), 'longitude')
                    ->hint('Пример: 42.7243')
                    ->nullable()
                    ->showOnExport(),
                Text::make(__('Номер'), 'phone')
                    ->nullable()
                    ->showOnExport()
                    ->hint('+7 (999) 999-99-99'),
                Json::make('Соц сети', 'social')
                    ->nullable()
                    ->onlyValue(),
                Text::make(__('Сайт'), 'site')
                    ->nullable()
                    ->showOnExport(),
                Text::make(__('Максимальный чек'), 'maxCheck')
                    ->required()
                    ->showOnExport()
                    ->hint('Средний чек строиться из двух значений (Максимальный чек и Минимальный чек), если нет одного, то заполнить только Максимальный чек')
                    ->hideOnIndex(),
                Text::make(__('Минимальный чек'), 'minCheck')
                    ->required()
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
            ]),
        ];
    }

    public function filters(): array
    {
        return [
            Text::make('Название', 'name'),
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
                ->value(Poster::count()),
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
            'description' => 'nullable|string|max:500',
            'previewDescription' => 'nullable|string|max:255',
            'percent' => 'nullable|integer',
            'restrictions' => 'nullable|integer',
            'preview' => 'nullable|string|max:255',
            'text' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'nameInstitution' => 'required|string|max:50',
            'site' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'phone' => 'nullable|string|max:255',
            'social' => 'nullable',
            'verified' => 'nullable',
            'reasonsVisit' => 'nullable',
            'begin' => 'nullable',
            'addressesTicet' => 'nullable',
            'chooseCurort26' => 'nullable|string|max:255',
            'features' => 'nullable|string|max:255',
            'maxCheck' => 'nullable|integer',
            'minCheck' => 'nullable|integer',
        ];
    }
}
