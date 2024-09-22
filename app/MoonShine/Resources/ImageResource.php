<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
//use MoonShine\Fields\Image;
use App\Models\Categorie;

use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<Image>
 */
class ImageResource extends ModelResource
{
    protected string $model = Image::class;

    protected string $title = 'Images';

    protected string $sortColumn = 'id';

    protected bool $columnSelection = true;

    protected string $column = 'name';

    public array $with = [
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
                Number::make(__('ID карточки'), 'card_id')
                    ->required()
                    ->showOnExport(),
                Number::make(__('Номер изображения'), 'page')
                    ->required()
                    ->showOnExport(),
                BelongsTo::make(
                    __('Категория'),
                    'categorie',
                    static fn (Categorie $model) => $model->name,
                ),
                \MoonShine\Fields\Image::make(__('Изображение'), 'name')
                    ->showOnExport()
                    ->disk(config('moonshine.disk', ''))
                    ->dir('images')
                    ->allowedExtensions(['jpg', 'png', 'jpeg']),
            ]),
        ];
    }

    /**
     * @param Image $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
