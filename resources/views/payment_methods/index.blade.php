<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Metode Pembayaran') }}
            </h2>
            <a href="{{ route('payment-methods.create') }}" class="bg-indigo-600 px-4 py-2 rounded-md text-white text-sm font-semibold hover:bg-indigo-700 transition">+ Tambah Metode</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-800 text-green-200 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Logo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">No. Rekening / VA</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse($methods as $method)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($method->image)
                                        <img src="{{ asset('images/payments/' . $method->image) }}" class="h-10 w-10 object-contain bg-white rounded-md p-1" alt="">
                                    @else
                                        <span class="text-gray-500 text-xs italic">No Image</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">{{ $method->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $method->code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 py-1 bg-gray-700 rounded-md text-xs uppercase">{{ $method->type }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-amber-400">{{ $method->account_number ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('payment-methods.edit', $method->id) }}" class="text-indigo-400 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="{{ route('payment-methods.destroy', $method->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus metode ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-900">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada metode pembayaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
