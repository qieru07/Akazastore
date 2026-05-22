<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Manajemen Banner Promo</h2>
            <a href="{{ route('banners.create') }}" class="bg-indigo-600 px-4 py-2 rounded-md text-white text-sm font-semibold hover:bg-indigo-700 transition">+ Tambah Banner</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-800 text-green-200 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-800 text-red-200 rounded-md text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 p-6 overflow-hidden shadow-sm sm:rounded-lg">
                @if ($banners->isEmpty())
                    <p class="text-gray-400 text-center py-8">Belum ada banner. <a href="{{ route('banners.create') }}" class="text-indigo-400 hover:underline">Tambah sekarang</a>.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead>
                            <tr>
                                <th class="text-left text-white py-3 px-2">#</th>
                                <th class="text-left text-white py-3 px-2">Preview</th>
                                <th class="text-left text-white py-3 px-2">Judul</th>
                                <th class="text-left text-white py-3 px-2">Status</th>
                                <th class="text-right text-white py-3 px-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($banners as $b)
                            <tr>
                                <td class="py-4 px-2 text-gray-400 text-sm">{{ $loop->iteration }}</td>
                                <td class="py-4 px-2">
                                    @if($b->type === 'video')
                                        <div class="h-20 w-40 bg-black flex items-center justify-center rounded shadow border border-gray-600 text-white text-xs">
                                            📺 Video YouTube
                                        </div>
                                    @else
                                        <img src="{{ asset('images/banners/'.$b->image) }}"
                                             class="h-20 w-40 object-cover rounded shadow border border-gray-600"
                                             onerror="this.src='https://placehold.co/160x80/1e293b/94a3b8?text=No+Image'">
                                    @endif
                                </td>
                                <td class="text-white px-2">
                                    {{ $b->title }}
                                    <br><span class="text-xs text-gray-500 uppercase">{{ $b->type }}</span>
                                </td>
                                <td class="px-2">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $b->status ? 'bg-green-800 text-green-200' : 'bg-gray-700 text-gray-400' }}">
                                        {{ $b->status ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="text-right px-2">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('banners.edit', $b->id) }}"
                                           class="px-3 py-1 bg-yellow-600 text-white rounded text-xs hover:bg-yellow-700 transition">Edit</a>
                                        <form action="{{ route('banners.destroy', $b->id) }}" method="POST"
                                              onsubmit="return confirm('Hapus banner ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1 bg-red-700 text-white rounded text-xs hover:bg-red-800 transition">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>