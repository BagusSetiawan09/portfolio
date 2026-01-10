<x-filament-panels::page>
    <div class="flex flex-col gap-y-6">
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-ta-ctn overflow-hidden rounded-xl bg-white ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="fi-ta-content relative divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10">
                    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-left dark:divide-white/5">
                        
                        <thead class="bg-gray-50 dark:bg-white/5">
                            <tr>
                                <th class="fi-ta-header-cell px-6 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                    <span class="text-sm font-semibold text-gray-950 dark:text-white">
                                        Filename
                                    </span>
                                </th>
                                <th class="fi-ta-header-cell px-6 py-3.5">
                                    <span class="text-sm font-semibold text-gray-950 dark:text-white">
                                        Date
                                    </span>
                                </th>
                                <th class="fi-ta-header-cell px-6 py-3.5">
                                    <span class="text-sm font-semibold text-gray-950 dark:text-white">
                                        Size
                                    </span>
                                </th>
                                <th class="fi-ta-header-cell px-6 py-3.5 text-right">
                                    <span class="text-sm font-semibold text-gray-950 dark:text-white">
                                        Actions
                                    </span>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                            @forelse($this->getBackups() as $backup)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                    
                                    <td class="fi-ta-cell px-6 py-4">
                                        <div class="flex items-center gap-x-3">
                                            <x-filament::icon
                                                icon="heroicon-o-document-arrow-down"
                                                class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                            />
                                            <span class="text-sm font-medium text-gray-950 dark:text-white">
                                                {{ $backup['name'] }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="fi-ta-cell px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <x-filament::badge color="gray">
                                            {{ $backup['date'] }}
                                        </x-filament::badge>
                                    </td>

                                    <td class="fi-ta-cell px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $backup['size'] }}
                                    </td>

                                    <td class="fi-ta-cell px-6 py-4 text-right">
                                        <div class="flex justify-end gap-x-3">
                                            
                                            <x-filament::icon-button
                                                icon="heroicon-o-arrow-down-tray"
                                                color="info"
                                                tooltip="Download File"
                                                wire:click="downloadBackup('{{ $backup['path'] }}')"
                                            />

                                            <x-filament::icon-button
                                                icon="heroicon-o-trash"
                                                color="danger"
                                                tooltip="Hapus Backup"
                                                wire:click="deleteBackup('{{ $backup['path'] }}')"
                                                wire:confirm="Apakah Anda yakin ingin menghapus file backup ini secara permanen?"
                                            />
                                            
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <x-filament::icon
                                                icon="heroicon-o-inbox"
                                                class="h-10 w-10 text-gray-400"
                                            />
                                            <span>Belum ada file backup yang tersedia.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>