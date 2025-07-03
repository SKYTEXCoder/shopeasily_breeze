<div>
    @if (count($breadcrumbs) > 1)
        <div class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
            <div class="w-full max-w-[85rem] xl:max-w-[95rem] 2xl:max-w-[120rem] py-3 px-4 sm:px-6 lg:px-8 mx-auto">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-1">
                        @foreach ($breadcrumbs as $index => $breadcrumb)
                            <li class="inline-flex items-center">
                                @if ($index > 0)
                                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                @endif
                                @if ($breadcrumb['active'])
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-current="page">
                                        {{ $breadcrumb['name'] }}
                                    </span>
                                @else
                                    @if ($breadcrumb['url'] !== '#')
                                        <a wire:navigate href="{{ $breadcrumb['url'] }}"
                                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500 transition-colors duration-200">
                                            @if ($index === 0)
                                                <svg class="w-3 h-3 mr-2.5" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                                                </svg>
                                            @endif
                                            {{ $breadcrumb['name'] }}
                                        </a>
                                    @else
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ $breadcrumb['name'] }}
                                        </span>
                                    @endif
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            </div>
        </div>
    @endif
</div>
