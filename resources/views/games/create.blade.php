<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Game Baru - Akazastore') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-8">
                    <!-- Form Start -->
                    <form action="{{ route('games.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Nama Game -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Game</label>
                            <input type="text" name="name" id="name" required 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                placeholder="Contoh: Mobile Legends, Free Fire, Valorant">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug / Nama File (Contoh: ml, ff, pubg)</label>
                            <input type="text" name="slug" id="slug" required 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                placeholder="ml">
                            <p class="mt-1 text-xs text-gray-500">Ini menentukan file yang dibuka di frontend (topup/nama_file.php).</p>
                            @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Thumbnail (Gambar) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 font-bold">Opsi A: Upload Thumbnail Game</label>
                            <div class="mt-1 flex items-center gap-4">
                                <div id="preview-container" class="hidden">
                                    <img id="image-preview" src="#" alt="Preview" class="h-20 w-20 object-cover rounded-lg border-2 border-indigo-500 shadow-sm">
                                </div>
                                <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                                    onchange="previewImage(event)"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Format: JPG, PNG, atau WebP. Maksimal 2MB.</p>
                            @error('thumbnail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Thumbnail URL (Alternatif Vercel) -->
                        <div>
                            <label for="thumbnail_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 font-bold">Opsi B: Atau Gunakan URL Gambar Eksternal (Sangat Direkomendasikan untuk Vercel)</label>
                            <input type="url" name="thumbnail_url" id="thumbnail_url" value="{{ old('thumbnail_url') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                placeholder="Contoh: https://i.imgur.com/xyz.png">
                            <p class="mt-1 text-xs text-gray-500">Gunakan opsi ini jika upload langsung gagal karena batasan server (read-only filesystem pada Vercel). Anda bisa mengunggah gambar ke situs gratis seperti postimages.org atau imgur.com lalu salin "Direct Link" gambarnya di sini.</p>
                            @error('thumbnail_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Video Promo (Slide/Carousel) - BARU -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Video Promo (Untuk Slide Carousel)</label>
                            <div class="mt-1 flex flex-col gap-4">
                                <div id="video-preview-container" class="hidden">
                                    <video id="video-preview" controls class="h-40 w-full rounded-lg border-2 border-indigo-500 shadow-sm bg-black">
                                        <source src="#" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                <input type="file" name="video" id="video" accept="video/mp4"
                                    onchange="previewVideo(event)"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Format: MP4. Maksimal 10-20MB. Kosongkan jika tidak ingin masuk slide.</p>
                            @error('video') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Status Game -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Publikasi</label>
                            <select name="status" id="status" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="1">Aktif (Muncul di Website Depan)</option>
                                <option value="0">Non-Aktif (Sembunyikan)</option>
                            </select>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('games.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Batal</a>
                            <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 transition ease-in-out duration-150 shadow-md">
                                Simpan Game
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Preview Gambar & Video -->
    <script>
        // Preview Gambar
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('image-preview');
                const container = document.getElementById('preview-container');
                output.src = reader.result;
                container.classList.remove('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // Preview Video
        function previewVideo(event) {
            const file = event.target.files[0];
            const blobURL = URL.createObjectURL(file);
            const container = document.getElementById('video-preview-container');
            const video = document.getElementById('video-preview');
            
            video.src = blobURL;
            container.classList.remove('hidden');
        }
    </script>
</x-app-layout>