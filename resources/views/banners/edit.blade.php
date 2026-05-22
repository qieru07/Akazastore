<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit Banner</h2>
            <a href="{{ route('banners.index') }}" class="text-sm text-gray-400 hover:underline">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-8 shadow-sm sm:rounded-lg border border-gray-700">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-900 text-red-200 rounded-md">
                        <ul class="list-disc pl-4 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ type: '{{ $banner->type }}' }">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Judul Promo</label>
                        <input type="text" name="title" value="{{ old('title', $banner->title) }}" required
                            class="mt-1 block w-full rounded-md border border-gray-600 bg-gray-900 text-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Tipe Banner</label>
                        <select name="type" x-model="type" class="mt-1 block w-full rounded-md border border-gray-600 bg-gray-900 text-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="image">Gambar (Slide Foto)</option>
                            <option value="video">Video (YouTube Embed)</option>
                        </select>
                    </div>

                    <div x-show="type === 'image'">
                        <label class="block text-sm font-medium text-gray-300 mb-1">Gambar Banner Saat Ini</label>
                        @if ($banner->image)
                            <img src="{{ asset('images/banners/' . $banner->image) }}"
                                 class="h-32 w-full object-cover rounded-md shadow mb-3 border border-gray-600">
                        @endif
                        <label class="block text-sm font-medium text-gray-300 mb-1">Ganti Gambar (opsional, max 5MB)</label>
                        <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/gif"
                            class="block w-full text-sm text-gray-400 bg-gray-900 border border-gray-600 rounded-md px-3 py-2 file:mr-4 file:py-1 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                    </div>

                    <div x-show="type === 'video'">
                        <label class="block text-sm font-medium text-gray-300 mb-1">File Video Saat Ini</label>
                        @if ($banner->video_url)
                            <div class="p-3 bg-gray-900 rounded border border-gray-700 mb-3 text-sm text-gray-400">
                                🎬 {{ $banner->video_url }}
                            </div>
                        @endif
                        <label class="block text-sm font-medium text-gray-300 mb-1">Ganti File Video (MP4, max 20MB)</label>
                        <input type="file" name="video" accept="video/mp4"
                            class="block w-full text-sm text-gray-400 bg-gray-900 border border-gray-600 rounded-md px-3 py-2 file:mr-4 file:py-1 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-700">
                        <a href="{{ route('banners.index') }}" class="text-sm text-gray-400 hover:underline">Batal</a>
                        <button type="submit" class="bg-indigo-600 px-6 py-2 rounded-md font-bold text-white uppercase text-xs tracking-widest hover:bg-indigo-700 shadow-md transition">
                            Update Banner
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
