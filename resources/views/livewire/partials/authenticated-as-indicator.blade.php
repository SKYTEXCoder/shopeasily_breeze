<div>
    @auth
        <div class="bg-gray-50 dark:bg-gray-700">
            <div class="max-w-screen-xl px-4 py-3 mx-auto">
                <div class="flex items-center">
                    <p class="text-gray-600 dark:text-gray-400">You are currently authenticated as <strong
                            class="font-extrabold">{{ auth()->user()->is_admin ? 'an Admin' : 'a Customer' }}</strong> with
                        Username <strong class="font-extrabold">{{ auth()->user()->name }}</strong> and User ID <strong
                            class="font-extrabold">{{ auth()->user()->id }}.</strong></p>
                </div>
            </div>
        </div>
    @endauth

    @guest

    @endguest
</div>
