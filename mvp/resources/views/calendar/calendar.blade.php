<x-layouts.app>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">タスクカレンダー</h1>
        <div id="calendar" data-events-url="{{ route('task.events') }}"></div>
    </div>

    {{-- JSをVite経由で読み込む --}}
   @vite(['resources/css/app.css', 'resources/js/calendar.js'])

</x-layouts.app>
