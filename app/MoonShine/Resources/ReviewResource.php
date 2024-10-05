<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Categorie;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Review;

use MoonShine\Fields\Date;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Metrics\ValueMetric;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<Review>
 */
class ReviewResource extends ModelResource
{
    protected string $model = Review::class;

    protected string $title = 'Reviews';

    protected string $sortColumn = 'id';

    protected bool $columnSelection = true;

    protected string $column = 'text';

    public array $with = [
        'user',
        'categorie',
    ];

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                BelongsTo::make(
                    __('Пользователь'),
                    'user',
                    static fn (User $model) => $model->name,
                    new UsersResource(),
                )->sortable(),
                BelongsTo::make(
                    __('Категория'),
                    'categorie',
                    static fn (Categorie $model) => $model->name,
                    new CategorieResource(),
                )->sortable(),
                Text::make(__('ID карточки'), 'card_id')
                    ->required()
                    ->sortable()
                    ->showOnExport(),
                Text::make(__('Текст'), 'text')
                    ->required()
                    ->showOnExport(),
                Text::make(__('Рейтинг'), 'rating')
                    ->required()
                    ->sortable()
                    ->showOnExport(),
                Date::make(__('moonshine::ui.resource.created_at'), 'created_at')
                    ->format("d.m.Y")
                    ->default(now()->toDateTimeString())
                    ->sortable()
                    ->hideOnForm()
                    ->showOnExport(),
            ]),
        ];
    }

    public function formFields(): array
    {
        return [
            Block::make([
                Text::make(__('ID пользователя'), 'user_id')
                    ->nullable()
                    ->showOnExport(),
                BelongsTo::make(
                    __('Категория'),
                    'categorie',
                    static fn (Categorie $model) => $model->name,
                    new CategorieResource(),
                ),
                Text::make(__('ID карточки'), 'card_id')
                    ->nullable()
                    ->showOnExport(),
                Text::make(__('Текст'), 'text')
                    ->nullable()
                    ->showOnExport(),
                Text::make(__('Рейтинг'), 'rating')
                    ->nullable()
                    ->showOnExport(),
            ]),
        ];
    }

    public function filters(): array
    {
        return [
            Text::make('Рейтинг', 'rating'),
            BelongsTo::make(
                __('Категория'),
                'categorie',
                static fn (Categorie $model) => $model->name,
            )->nullable(),
            Text::make(__('Номер карточки'), 'card_id'),
            Text::make('ID пользователя', 'user_id'),
        ];
    }

    public function metrics(): array
    {
        return [
            ValueMetric::make('Количество отзывов')
                ->value(Review::count()),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'card_id' => ['required', 'integer'],
            'text' => ['required', 'string', 'max:240', 'min:3'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'category_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
        ];
    }
}
