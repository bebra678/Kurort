<x-moonshine::sidebar.group title="Карточки">
    <x-moonshine::sidebar.link href="{{ moonshine_route('index', 'cities') }}">
        <x-slot name="icon">
            <!-- Добавьте вашу пользовательскую иконку здесь -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <!-- SVG path для иконки города -->
            </svg>
        </x-slot>
        Города
    </x-moonshine::sidebar.link>
    <!-- Другие пункты меню с пользовательскими иконками -->
</x-moonshine::sidebar.group>
