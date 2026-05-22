<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Riwayat Transaksi Masuk') }}
            </h2>
            <form action="{{ route('transactions.clearCompleted') }}" method="POST" onsubmit="return confirm('Hapus semua transaksi yang berstatus LUNAS dan BATAL? Data tidak bisa dikembalikan!')">
                @csrf
                <button type="submit" class="bg-red-600/20 hover:bg-red-600 text-red-500 hover:text-white border border-red-600/50 px-4 py-2 rounded-xl text-xs font-bold transition-all">
                    Bersihkan Semua Selesai
                </button>
            </form>
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
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Waktu</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">User / Game</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Item</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Total Bayar</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Metode</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse($transactions as $trx)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-xs text-gray-500">#{{ $trx->id }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-xs">
                                    {{ date('d/m/y H:i', strtotime($trx->tanggal)) }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-white">{{ $trx->username }}</div>
                                    <div class="text-xs text-indigo-400">{{ $trx->game }} ({{ $trx->user_id }})</div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-xs text-gray-300">{{ $trx->email }}</div>
                                    <div class="flex items-center gap-1">
                                        <span class="text-xs font-mono text-green-400">{{ $trx->whatsapp }}</span>
                                        @php
                                            $waNumber = preg_replace('/[^0-9]/', '', $trx->whatsapp);
                                            if (strpos($waNumber, '0') === 0) {
                                                $waNumber = '62' . substr($waNumber, 1);
                                            }
                                        @endphp
                                        <a href="https://wa.me/{{ $waNumber }}?text=Halo%20{{ $trx->username }},%20pesanan%20{{ $trx->item }}%20di%20AkazaStore%20telah%20berhasil%20SUKSES!%20Silakan%20cek%20game%20kamu." target="_blank" class="text-[10px] bg-green-900 text-green-300 px-1 rounded hover:bg-green-700">HUBUNGI</a>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-amber-400 font-semibold">{{ $trx->item }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                    <div class="font-bold">Rp {{ number_format($trx->nominal + $trx->kode_unik, 0, ',', '.') }}</div>
                                    <div class="text-[10px] text-gray-500">Unik: {{ $trx->kode_unik }}</div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-xs uppercase">{{ $trx->metode }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    @php
                                        $statusClass = [
                                            'PENDING' => 'bg-yellow-900 text-yellow-200',
                                            'Lunas' => 'bg-green-900 text-green-200',
                                            'Batal' => 'bg-red-900 text-red-200',
                                        ][$trx->status] ?? 'bg-gray-700 text-gray-300';
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold {{ $statusClass }}">
                                        {{ $trx->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm">
                                    @if($trx->status == 'PENDING')
                                    <div class="flex justify-end gap-2">
                                        <form action="{{ route('transactions.updateStatus', $trx->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Lunas">
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition">Konfirmasi</button>
                                        </form>
                                        <form action="{{ route('transactions.updateStatus', $trx->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Batal">
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition">Tolak</button>
                                        </form>
                                    </div>
                                    @else
                                        <div class="flex justify-end items-center gap-3">
                                            <span class="text-gray-500 text-[10px] uppercase font-bold italic tracking-tighter">Selesai</span>
                                            <form action="{{ route('transactions.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Hapus data transaksi ini secara permanen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-400 p-1.5 rounded-lg hover:bg-red-500/10 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">Belum ada transaksi masuk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>