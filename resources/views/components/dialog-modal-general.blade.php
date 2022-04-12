@props(['id' => null, 'maxWidth' => null])

<x-modal-general :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-3 text-lg" style="background: #2c3b4a; color:white; height:60px">
        {{ $title }}
    </div>
    <div class="px-6 py-4">
        {{ $content }}
    </div>

    <div class="px-6 py-4 bg-gray-100 text-right">
        {{ $footer }}
    </div>
</x-modal-general>