<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Type;

use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<Type>
 */
class TypeResource extends ModelResource
{
    protected string $model = Type::class;

    protected string $title = 'Типы';

    public string $column = 'name';

    protected bool $createInModal = true;

    protected bool $editInModal = true;
    protected bool $isAsync = true;

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make(__('Название'), 'name')
                    ->required()
                    ->showOnExport(),
            ]),
        ];
    }

    public function getActiveActions(): array
    {
        return ['create', 'view', 'delete', 'massDelete'];
    }

    /**
     * @param Type $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [
            'name' => 'required|min:5',
        ];
    }
}
