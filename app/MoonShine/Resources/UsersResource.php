<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

use Illuminate\Validation\Rule;
use MoonShine\Decorations\Heading;
use MoonShine\Decorations\Tab;
use MoonShine\Fields\Date;
use MoonShine\Fields\Email;
use MoonShine\Fields\Image;
use MoonShine\Fields\Password;
use MoonShine\Fields\PasswordRepeat;
use MoonShine\Fields\Text;
use MoonShine\Metrics\ValueMetric;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Checkbox;
use MoonShine\Decorations\Tabs;

/**
 * @extends ModelResource<User>
 */
class UsersResource extends ModelResource
{
    protected string $model = User::class;

    protected string $title = 'Пользователи';

    protected string $column = 'name';

    protected bool $createInModal = false;
//
//    protected bool $editInModal = false;
//
//    protected bool $detailInModal = false;

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
                        Email::make(__('moonshine::ui.resource.email'), 'email')
                            ->sortable()
                            ->showOnExport()
                            ->required(),
                        Checkbox::make('Подтверждение почты', 'email_verified_at')
                            ->onValue('Подтверждена')
                            ->offValue('Не подтверждена')
                            ->hideOnUpdate()
                        ,
                        Text::make(__('moonshine::ui.resource.name'), 'name')
                            ->required()
                            ->showOnExport(),
                        Text::make(__('Номер'), 'number')
                            ->required()
                            ->showOnExport(),
                        Image::make(__('moonshine::ui.resource.avatar'), 'photo')
                            ->showOnExport()
                            ->disk(config('moonshine.disk', ''))
                            ->dir('images/users')
                            ->allowedExtensions(['jpg', 'png', 'jpeg', 'gif']),
                        Date::make(__('moonshine::ui.resource.created_at'), 'created_at')
                            ->format("d.m.Y")
                            ->default(now()->toDateTimeString())
                            ->sortable()
                            ->hideOnForm()
                            ->showOnExport(),
                    ]),
                    Tab::make(__('moonshine::ui.resource.password'), [
                        Heading::make(__('moonshine::ui.resource.change_password')),

                        Password::make(__('moonshine::ui.resource.password'), 'password')
                            ->customAttributes(['autocomplete' => 'new-password'])
                            ->hideOnIndex()
                            ->hideOnDetail()
                            ->eye(),

                        PasswordRepeat::make(__('moonshine::ui.resource.repeat_password'), 'password_repeat')
                            ->customAttributes(['autocomplete' => 'confirm-password'])
                            ->hideOnIndex()
                            ->hideOnDetail()
                            ->eye(),
                    ]),
                    ]),
                ]),
            ];
    }

    public function detailFields(): array
    {
        return [
                ID::make()->sortable(),
                Email::make(__('moonshine::ui.resource.email'), 'email')
                    ->sortable()
                    ->showOnExport()
                    ->required()
                    ->copy(),
                Text::make('Подтверждение почты', 'email_verified_at')
                    ->required()
                ,
                Text::make(__('moonshine::ui.resource.name'), 'name')
                    ->required()
                    ->showOnExport(),
                Text::make('Номер', 'number')
                    ->required()
                    ->showOnExport(),
                Image::make(__('moonshine::ui.resource.avatar'), 'photo')
                    ->showOnExport()
                    ->disk(config('moonshine.disk', ''))
                    ->dir('images/users')
                    ->allowedExtensions(['jpg', 'png', 'jpeg', 'gif']),
                Date::make(__('moonshine::ui.resource.created_at'), 'created_at')
                    ->format("d.m.Y")
                    ->default(now()->toDateTimeString())
                    ->sortable()
                    ->hideOnForm()
                    ->showOnExport(),
        ];
    }
//
//    public function indexFields(): array
//    {
//        return [
//                ID::make()->sortable(),
//
//                Email::make(__('moonshine::ui.resource.email'), 'email')
//                    ->sortable()
//                    ->showOnExport()
//                    ->required()
//                    ->copy(),
//                Checkbox::make('Подтверждение почты', 'email_verified_at')
//                    ->onValue('Подтверждена')
//                    ->offValue('Не подтверждена')
//                ,
//                Text::make(__('moonshine::ui.resource.name'), 'name')
//                    ->required()
//                    ->showOnExport(),
//                Text::make('Номер', 'number')
//                    ->required()
//                    ->showOnExport(),
//                Image::make(__('moonshine::ui.resource.avatar'), 'photo')
//                    ->showOnExport()
//                    ->disk(config('moonshine.disk', ''))
//                    ->dir('images/users')
//                    ->allowedExtensions(['jpg', 'png', 'jpeg', 'gif']),
//                Date::make(__('moonshine::ui.resource.created_at'), 'created_at')
//                    ->format("d.m.Y")
//                    ->default(now()->toDateTimeString())
//                    ->sortable()
//                    ->hideOnForm()
//                    ->showOnExport(),
//        ];
//    }

    public function formFields(): array
    {
        return [
            Block::make([
                Tabs::make([
                    Tab::make(__('moonshine::ui.resource.main_information'), [
                        ID::make()->sortable(),

                        Email::make(__('moonshine::ui.resource.email'), 'email')
                            ->sortable()
                            ->showOnExport()
                            ->required()
                            ->copy(),
                        Text::make(__('moonshine::ui.resource.name'), 'name')
                            ->required()
                            ->showOnExport(),
                        Text::make(__('Номер'), 'number')
                            ->nullable()
                            ->showOnExport(),
                        Image::make(__('moonshine::ui.resource.avatar'), 'photo')
                            ->showOnExport()
                            ->disk(config('moonshine.disk', ''))
                            ->dir('images/users')
                            ->allowedExtensions(['jpg', 'png', 'jpeg']),
                        Date::make(__('moonshine::ui.resource.created_at'), 'created_at')
                            ->format("d.m.Y")
                            ->default(now()->toDateTimeString())
                            ->sortable()
                            ->hideOnForm()
                            ->showOnExport(),
                    ]),
                    Tab::make(__('moonshine::ui.resource.password'), [
                        Heading::make(__('moonshine::ui.resource.change_password')),

                        Password::make(__('moonshine::ui.resource.password'), 'password')
                            ->customAttributes(['autocomplete' => 'new-password'])
                            ->hideOnIndex()
                            ->hideOnDetail()
                            ->eye(),

                        PasswordRepeat::make(__('moonshine::ui.resource.repeat_password'), 'password_repeat')
                            ->customAttributes(['autocomplete' => 'confirm-password'])
                            ->hideOnIndex()
                            ->hideOnDetail()
                            ->eye(),
                    ]),
                ]),
            ]),
        ];
    }

    /**
     * @param User $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [
            'name' => 'required|string|max:30|min:2|regex:/^[\p{Cyrillic}-]+$/u',
            'email' => [
                'sometimes',
                'bail',
                'required',
                'email',
                'min:10',
                Rule::unique('users')->ignoreModel($item),
                'max:100',
            ],
            'number' => ['nullable', 'regex:/^[\+7] \(\d{3}\) \d{3}-\d{2}-\d{2}$/', Rule::unique('users')],
            'photo' => 'nullable|file|mimes:jpg,png,jpeg',
            'password' => $item->exists
                ? 'sometimes|nullable|min:6|required_with:password_repeat|same:password_repeat'
                : 'required|min:6|required_with:password_repeat|same:password_repeat',
        ];
    }

    public function getActiveActions(): array
    {
        return ['view', 'update', 'delete', 'massDelete'];
    }

    public function metrics(): array
    {
        return [
            ValueMetric::make('Количество пользователей')
                ->value(User::count()),
        ];
    }

//    public function validationMessages(): array
//    {
//        return [
//            'email.required' => 'Required email',
//            'email.email' => 'Required email'
//        ];
//    }
}
