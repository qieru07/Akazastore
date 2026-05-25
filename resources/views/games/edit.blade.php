<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Game: ') . $game->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-8 shadow-sm sm:rounded-lg border border-gray-700">
                <form action="{{ route('games.update', $game->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Nama Game</label>
                        <input type="text" name="name" value="{{ old('name', $game->name) }}" required 
                            class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300">Slug / Nama File (Contoh: ml, ff, pubg)</label>
                        <input type="text" name="slug" value="{{ old('slug', $game->slug) }}" required 
                            class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-gray-500">Ini menentukan file yang dibuka di frontend (topup/nama_file.php).</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 font-bold">Ganti Thumbnail Gambar (Opsi A: Upload)</label>
                        @if($game->thumbnail && !str_starts_with($game->thumbnail, 'http'))
                            <div class="mb-2">
                                <p class="text-xs text-gray-500 mb-1">Thumbnail Saat Ini (Upload):</p>
                                <img src="{{ asset('images/games/' . $game->thumbnail) }}" class="h-20 w-auto rounded border border-gray-600">
                            </div>
                        @endif
                        <input type="file" name="thumbnail" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-2 text-xs text-gray-500">Abaikan jika tidak ingin mengganti gambar via upload.</p>
                        @error('thumbnail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="thumbnail_url" class="block text-sm font-medium text-gray-300 font-bold">Atau Ganti Thumbnail (Opsi B: URL Eksternal)</label>
                        @if($game->thumbnail && str_starts_with($game->thumbnail, 'http'))
                            <div class="mb-2">
                                <p class="text-xs text-gray-500 mb-1">URL Thumbnail Saat Ini:</p>
                                <img src="{{ $game->thumbnail }}" class="h-20 w-auto rounded border border-gray-600">
                            </div>
                        @endif
                        <input type="url" name="thumbnail_url" id="thumbnail_url" value="{{ old('thumbnail_url', str_starts_with($game->thumbnail, 'http') ? $game->thumbnail : '') }}"
                            class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Contoh: https://i.imgur.com/xyz.png">
                        <p class="mt-2 text-xs text-gray-500">Gunakan opsi ini jika upload langsung gagal karena batasan serverless read-only di Vercel.</p>
                        @error('thumbnail_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-700">
                        <a href="{{ route('games.index') }}" class="text-sm text-gray-400 hover:underline">Batal</a>
                        <button type="submit" class="bg-indigo-600 px-6 py-2 rounded-md font-bold text-white uppercase text-xs tracking-widest hover:bg-indigo-700 shadow-md">
                            Update Game
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>