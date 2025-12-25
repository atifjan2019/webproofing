<x-app-layout>
    <div class="container">
        <div class="mt-2xl mb-3xl text-center animate-fadeInUp">
            <h1 class="text-4xl font-bold text-black mb-md">Simple, Transparent Pricing</h1>
            <p class="text-xl text-secondary max-w-2xl mx-auto">
                Everything you need to monitor and optimize your websites. No hidden fees, cancel anytime.
            </p>
        </div>

        <div class="flex justify-center mb-3xl animate-fadeInUp" style="animation-delay: 100ms;">
            <div class="card p-0 overflow-hidden max-w-md w-full border-accent" style="border-width: 2px;">
                <div class="p-xl bg-bg-secondary text-center border-b border-border">
                    <h3 class="text-lg font-bold text-secondary uppercase tracking-wider mb-sm">Pro Plan</h3>
                    <div class="flex items-baseline justify-center gap-xs">
                        <span class="text-5xl font-extrabold text-black">$9.99</span>
                        <span class="text-secondary">/month</span>
                    </div>
                    <p class="text-muted mt-sm">All features included</p>
                </div>

                <div class="p-xl">
                    <ul class="flex flex-col gap-md mb-xl">
                        <li class="flex items-start gap-md">
                            <div class="mt-xs text-success">
                                <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <strong class="text-black block">Unlimited Websites</strong>
                                <span class="text-muted text-sm">Monitor as many sites as you need</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-md">
                            <div class="mt-xs text-success">
                                <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <strong class="text-black block">Weekly Screenshots</strong>
                                <span class="text-muted text-sm">Automated visual history tracking</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-md">
                            <div class="mt-xs text-success">
                                <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <strong class="text-black block">Google Analytics & Search Console</strong>
                                <span class="text-muted text-sm">Integrated dashboard for all metrics</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-md">
                            <div class="mt-xs text-success">
                                <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <strong class="text-black block">Uptime Monitoring</strong>
                                <span class="text-muted text-sm">Instant alerts if your site goes down</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-md">
                            <div class="mt-xs text-success">
                                <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <strong class="text-black block">Priority Support</strong>
                                <span class="text-muted text-sm">Direct access to our support team</span>
                            </div>
                        </li>
                    </ul>

                    <a href="#" class="btn btn-primary btn-lg btn-full justify-center">
                        Get Started Now
                    </a>
                </div>
            </div>
        </div>

        <!-- Pricing Feedback Section -->
        <div class="max-w-2xl mx-auto mb-3xl animate-fadeInUp" style="animation-delay: 200ms;">
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-xl">
                <h3 class="text-xl font-bold text-center mb-md text-gray-800">What do you think about our pricing?</h3>
                <p class="text-center text-gray-600 mb-lg">We value your honest feedback to help us serve you better.
                </p>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('pricing.feedback') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="price_opinion" value="too_expensive" class="peer sr-only">
                            <div
                                class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-red-500 peer-checked:bg-red-50 hover:bg-gray-100 transition-all">
                                <div class="text-2xl mb-2">💸</div>
                                <div class="font-semibold text-gray-700">Too Expensive</div>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="price_opinion" value="fair" class="peer sr-only">
                            <div
                                class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-100 transition-all">
                                <div class="text-2xl mb-2">⚖️</div>
                                <div class="font-semibold text-gray-700">Fair Price</div>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="price_opinion" value="good_deal" class="peer sr-only">
                            <div
                                class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-100 transition-all">
                                <div class="text-2xl mb-2">💎</div>
                                <div class="font-semibold text-gray-700">Good Deal</div>
                            </div>
                        </label>
                    </div>

                    <div>
                        <label for="suggestion" class="block text-sm font-medium text-gray-700 mb-2">Any suggestions or
                            features you'd like to see?</label>
                        <textarea name="suggestion" id="suggestion" rows="3"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Tell us what would make this a no-brainer for you..."></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors">
                            Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mb-3xl">
            <p class="text-muted">Questions? <a href="#" class="text-accent hover:underline">Contact our support
                    team</a></p>
        </div>
    </div>
</x-app-layout>