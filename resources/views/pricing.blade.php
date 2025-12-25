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
                        <div class="flex flex-col items-start leading-tight">
                            <span class="text-secondary text-sm font-medium">/site</span>
                            <span class="text-secondary text-sm">per month</span>
                        </div>
                    </div>
                    <p class="text-muted mt-sm">Simple, transparent pricing</p>
                </div>
                
                <div class="p-xl">
                    <ul class="flex flex-col gap-md mb-xl">
                        <li class="flex items-start gap-md">
                            <div class="mt-xs text-success">
                                <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <strong class="text-black block">Full Feature Access</strong>
                                <span class="text-muted text-sm">Everything you need for one site</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-md">
                            <div class="mt-xs text-success">
                                <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
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
        <div class="max-w-xl mx-auto mb-3xl animate-fadeInUp" style="animation-delay: 200ms;">
            <div class="text-center mb-lg">
                <h3 class="text-lg font-medium text-gray-900">What do you think about this price?</h3>
            </div>

            @if(session('success'))
                <div class="bg-green-50 text-green-700 text-sm p-4 rounded-lg text-center mb-6">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                 <div class="bg-red-50 text-red-700 text-sm p-4 rounded-lg text-center mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('pricing.feedback') }}" method="POST" class="space-y-6" x-data="{ selected: null }">
                @csrf
                
                <div class="grid grid-cols-3 gap-3">
                    <label class="cursor-pointer group">
                        <input type="radio" name="price_opinion" value="too_expensive" class="sr-only" @change="selected = 'too_expensive'">
                        <div class="flex flex-col items-center justify-center p-3 rounded-xl border border-gray-200 bg-white transition-all duration-200 group-hover:border-red-300 group-hover:shadow-sm"
                             :class="{ 'ring-2 ring-red-500 border-red-500 bg-red-50': selected === 'too_expensive' }">
                            <span class="text-2xl mb-1 grayscale group-hover:grayscale-0 transition-all" :class="{ 'grayscale-0': selected === 'too_expensive' }">💸</span>
                            <span class="text-xs font-medium text-gray-600 group-hover:text-gray-900">Too High</span>
                        </div>
                    </label>
                    
                    <label class="cursor-pointer group">
                        <input type="radio" name="price_opinion" value="fair" class="sr-only" @change="selected = 'fair'">
                        <div class="flex flex-col items-center justify-center p-3 rounded-xl border border-gray-200 bg-white transition-all duration-200 group-hover:border-blue-300 group-hover:shadow-sm"
                             :class="{ 'ring-2 ring-blue-500 border-blue-500 bg-blue-50': selected === 'fair' }">
                            <span class="text-2xl mb-1 grayscale group-hover:grayscale-0 transition-all" :class="{ 'grayscale-0': selected === 'fair' }">⚖️</span>
                            <span class="text-xs font-medium text-gray-600 group-hover:text-gray-900">Fair</span>
                        </div>
                    </label>
                    
                    <label class="cursor-pointer group">
                        <input type="radio" name="price_opinion" value="good_deal" class="sr-only" @change="selected = 'good_deal'">
                        <div class="flex flex-col items-center justify-center p-3 rounded-xl border border-gray-200 bg-white transition-all duration-200 group-hover:border-green-300 group-hover:shadow-sm"
                             :class="{ 'ring-2 ring-green-500 border-green-500 bg-green-50': selected === 'good_deal' }">
                            <span class="text-2xl mb-1 grayscale group-hover:grayscale-0 transition-all" :class="{ 'grayscale-0': selected === 'good_deal' }">💎</span>
                            <span class="text-xs font-medium text-gray-600 group-hover:text-gray-900">Great Deal</span>
                        </div>
                    </label>
                </div>

                <div class="relative">
                    <textarea name="suggestion" rows="2" 
                              class="w-full rounded-lg border-gray-200 text-sm focus:border-black focus:ring-black bg-gray-50 placeholder-gray-400 resize-none py-3 px-4" 
                              placeholder="Any suggestions? (Optional)"></textarea>
                    <div class="absolute bottom-2 right-2">
                        <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-black hover:bg-gray-800 focus:outline-none transition-colors">
                            Send
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="text-center mb-3xl">
            <p class="text-muted">Questions? <a href="#" class="text-accent hover:underline">Contact our support team</a></p>
        </div>
    </div>
</x-app-layout>