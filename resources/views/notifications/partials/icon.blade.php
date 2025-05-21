@switch($type)
    @case('property.created')
        <x-heroicon-o-home class="h-5 w-5 text-green-500" />
        @break
    @case('visit.requested')
        <x-heroicon-o-calendar class="h-5 w-5 text-blue-500" />
        @break
    @default
        <x-heroicon-o-bell class="h-5 w-5 text-gray-500" />
@endswitch