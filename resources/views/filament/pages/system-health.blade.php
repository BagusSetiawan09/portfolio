<x-filament-panels::page>
    @php
        $results = $this->results ?? [];

        $cards = [
            'smtp_config' => ['title' => 'SMTP', 'subtitle' => 'Mail config', 'icon' => 'heroicon-m-envelope'],
            'telegram_config' => ['title' => 'Telegram', 'subtitle' => 'Bot config', 'icon' => 'heroicon-m-paper-airplane'],
            'storage' => ['title' => 'Storage', 'subtitle' => 'Write / delete', 'icon' => 'heroicon-m-folder-open'],
            'pdf' => ['title' => 'PDF', 'subtitle' => 'Render / DomPDF', 'icon' => 'heroicon-m-document-text'],
        ];

        $get = function (string $key) use ($results) {
            return $results[$key] ?? ['ok' => null, 'message' => 'Belum dicek.'];
        };

        $badge = function ($ok) {
            if ($ok === true)  return ['PASS', 'success'];
            if ($ok === false) return ['FAIL', 'danger'];
            return ['—', 'gray'];
        };

        $chipClasses = fn (string $color) => match ($color) {
            'success' => 'bg-success-500/10 text-success-600 dark:text-success-400 ring-success-500/20',
            'danger'  => 'bg-danger-500/10 text-danger-600 dark:text-danger-400 ring-danger-500/20',
            default   => 'bg-gray-500/10 text-gray-600 dark:text-gray-300 ring-gray-500/20',
        };
    @endphp

    <div class="space-y-6">
        {{-- HEADER (Filament style) --}}
        <x-filament::section
            heading="System Health"
            description="Ringkasan status sistem. Gunakan tombol di kanan atas untuk menjalankan test."
        >
            {{-- SUMMARY CARDS ala Filament --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                @foreach ($cards as $key => $meta)
                    @php
                        $res = $get($key);
                        [$label, $color] = $badge($res['ok'] ?? null);
                    @endphp

                    <x-filament::card class="h-full">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="rounded-xl p-2 bg-gray-900/5 dark:bg-white/5">
                                    <x-filament::icon :icon="$meta['icon']" class="h-5 w-5 text-gray-700 dark:text-gray-200" />
                                </div>

                                <div class="min-w-0">
                                    <div class="font-semibold leading-tight truncate">
                                        {{ $meta['title'] }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        {{ $meta['subtitle'] }}
                                    </div>
                                </div>
                            </div>

                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold ring-1 {{ $chipClasses($color) }}">
                                {{ $label }}
                            </span>
                        </div>

                        <div class="mt-3 text-sm text-gray-700 dark:text-gray-200 leading-relaxed wrap-break-word">
                            {{ $res['message'] ?? '-' }}
                        </div>
                    </x-filament::card>
                @endforeach
            </div>
        </x-filament::section>

        {{-- DETAIL RESULTS --}}
        <x-filament::section
            heading="Check Results"
            description="Menampilkan semua output check yang tersimpan saat ini."
            collapsible
        >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($results as $key => $res)
                    @php
                        [$label, $color] = $badge($res['ok'] ?? null);
                    @endphp

                    <x-filament::card class="h-full">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-sm font-semibold">
                                    {{ strtoupper(str_replace('_',' ', $key)) }}
                                </div>
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-300 leading-relaxed wrap-break-word">
                                    {{ $res['message'] ?? '-' }}
                                </div>
                            </div>

                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold ring-1 {{ $chipClasses($color) }}">
                                {{ $label }}
                            </span>
                        </div>
                    </x-filament::card>
                @endforeach
            </div>
        </x-filament::section>

        {{-- ENV SUMMARY --}}
        <x-filament::section
            heading="Env Summary"
            description="Tanpa password. Klik untuk melihat ringkasan konfigurasi penting."
            collapsible
        >
            <x-filament::card>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-1 text-sm">
                    @foreach ($this->envSummary as $k => $v)
                        <div class="flex items-center justify-between gap-4 border-b border-gray-200/60 dark:border-gray-800/60 py-2">
                            <div class="font-medium text-gray-700 dark:text-gray-200">
                                {{ $k }}
                            </div>
                            <div class="text-gray-500 dark:text-gray-400 break-all text-right">
                                {{ is_bool($v) ? ($v ? 'true' : 'false') : ($v ?? '-') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-filament::card>
        </x-filament::section>

        <div class="text-xs text-gray-500 dark:text-gray-400">
            Tips: “Run All Checks” tidak mengirim email/telegram. Gunakan tombol SMTP/Telegram untuk test kirim.
        </div>
    </div>
</x-filament-panels::page>
