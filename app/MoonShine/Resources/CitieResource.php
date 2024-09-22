<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Citie;

use Illuminate\Http\Request;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<Citie>
 */
class CitieResource extends ModelResource
{
    protected string $model = Citie::class;

    protected string $title = 'Citie';

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
                ID::make()
                    ->sortable()
                    ->showOnExport(),
                Text::make(__('Название'), 'name')
                    ->required()
                    ->showOnExport(),
            ]),
        ];
    }

    /**
     * @param Citie $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules($item): array
    {
        return [
            'name' => 'required|min:5',
        ];
    }

    public function getActiveActions(): array
    {
        return ['create', 'view', 'delete', 'massDelete'];
    }

    public function search(): array
    {
        return [
            'id',
            'name',
        ];
    }
}
