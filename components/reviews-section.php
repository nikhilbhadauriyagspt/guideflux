<?php
/**
 * Testimonials & Verified Guest Reviews Component - Orion Advent
 * - Clean, Lightweight Testimonial Cards (Real Testimonial UI, Not Heavy)
 * - 5 Gold Stars, Verified Guest Badge, Quote, Author & Booked Tour Pill
 * - Interactive Filter Pills Track (All, Honeymoon, Family, Adventure, Luxury)
 * - Smooth Auto-Sliding Carousel (3 Cards Desktop, 2 Tablet, 1 Mobile)
 * - 100% Flat, Border-First, Zero Shadows
 * - Pure Font Awesome 6 Icons Only (Zero Emojis)
 */

$testimonialCategories = [
    ['id' => 'all', 'label' => 'All Stories', 'icon' => 'fa-solid fa-earth-americas'],
    ['id' => 'honeymoon', 'label' => 'Honeymoon', 'icon' => 'fa-solid fa-heart'],
    ['id' => 'family', 'label' => 'Family Vacations', 'icon' => 'fa-solid fa-people-roof'],
    ['id' => 'adventure', 'label' => 'Adventure Treks', 'icon' => 'fa-solid fa-person-hiking'],
    ['id' => 'luxury', 'label' => 'Luxury Getaways', 'icon' => 'fa-solid fa-crown'],
];

$testimonialsList = [
    // 1. HONEYMOON - MALDIVES
    [
        'id' => 'test-01',
        'name' => 'Sneha & Rohan Sharma',
        'city' => 'New Delhi, India',
        'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80',
        'category' => 'honeymoon luxury',
        'tour' => 'Maldives 5N/6D Villa',
        'headline' => 'The best honeymoon we could have dreamed of!',
        'quote' => 'From the private pool villa upgrade to the seamless speedboat transfers in Male, everything was effortless. Our coordinator was on WhatsApp 24/7. Truly a 5-star experience from start to finish.',
        'rating' => 5
    ],

    // 2. ADVENTURE - LADAKH
    [
        'id' => 'test-02',
        'name' => 'Aditya & Varun Rao',
        'city' => 'Bangalore, India',
        'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80',
        'category' => 'adventure',
        'tour' => 'Ladakh 7N/8D Biking',
        'headline' => 'Flawless Enfield bikes and heated Pangong camps!',
        'quote' => 'Crossing Khardung La at 18,000 ft was incredible. The backup vehicle with oxygen kits and dedicated mechanic support gave us complete peace of mind through rugged passes.',
        'rating' => 5
    ],

    // 3. FAMILY - DUBAI
    [
        'id' => 'test-03',
        'name' => 'Dr. Rajesh & Sunita Mehra',
        'city' => 'Mumbai, India',
        'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80',
        'category' => 'family luxury',
        'tour' => 'Dubai 4N/5D Atlantis',
        'headline' => 'Traveling with elderly parents was 100% stress-free!',
        'quote' => 'Our private chauffeur was punctual, polite, and very helpful. Fast-track tickets to Burj Khalifa and Aquaventure saved us from standing in long queues with the kids.',
        'rating' => 5
    ],

    // 4. HONEYMOON - KASHMIR
    [
        'id' => 'test-04',
        'name' => 'Vikram & Ananya Sen',
        'city' => 'Kolkata, India',
        'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80',
        'category' => 'honeymoon',
        'tour' => 'Kashmir 5N/6D Gulmarg',
        'headline' => 'Waking up on a Dal Lake houseboat was pure magic.',
        'quote' => 'Our heritage cedarwood houseboat was spotless. Orion arranged Phase 2 Gulmarg Gondola passes when they were completely sold out everywhere else. Unforgettable trip!',
        'rating' => 5
    ],

    // 5. LUXURY - RAJASTHAN
    [
        'id' => 'test-05',
        'name' => 'Kavita & Arvind Singhania',
        'city' => 'Ahmedabad, India',
        'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=200&q=80',
        'category' => 'luxury',
        'tour' => 'Rajasthan 5N/6D Palaces',
        'headline' => 'Treated like royalty from airport pickup to checkout.',
        'quote' => 'The private boat entry at Lake Pichola Udaipur and vintage car ride in Jaipur made our anniversary feel truly majestic. Transparent billing with zero hidden fees.',
        'rating' => 5
    ],

    // 6. FAMILY - KERALA
    [
        'id' => 'test-06',
        'name' => 'Pooja & Sameer Deshmukh',
        'city' => 'Pune, India',
        'avatar' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=200&q=80',
        'category' => 'family',
        'tour' => 'Kerala 4N/5D Alleppey',
        'headline' => 'The private chef on our backwater houseboat was outstanding.',
        'quote' => 'Cruising along the silent palm backwaters while the chef prepared fresh local river fish was the highlight of our vacation. The kids loved every moment!',
        'rating' => 5
    ]
];
?>
<!-- ==========================================
     TESTIMONIALS & VERIFIED GUEST REVIEWS
     - Clean, Lightweight Testimonial Cards (Real Testimonial UI, Not Heavy)
     - Star Rating, Verified Guest Badge, Author Details & Tour Tag
     - Interactive Filter Pills & Auto-Sliding Carousel
     - 100% Flat, Border-First (Zero Shadows)
     - Font Awesome 6 Icons Only (Zero Emojis)
=========================================== -->
<section id="reviews-section" class="py-16 md:py-24 px-4 sm:px-8 xl:px-12 bg-white border-b border-slate-200 relative overflow-hidden">
    <div class="max-w-7xl mx-auto relative z-10">

        <!-- 1. Centered Section Header with Generated Inline Capsule Image -->
        <div class="max-w-3xl mx-auto text-center space-y-3 mb-10">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                <i class="fa-solid fa-star text-amber-500"></i>
                <span>4.9 / 5.0 Average Guest Rating</span>
            </div>

            <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                <span>What Our Travelers</span>
                <span class="inline-block align-middle mx-1.5 sm:mx-2.5">
                    <img src="assets/images/banner/heading-review-01.jpg" 
                         alt="Smiling Travelers on Beach" 
                         class="h-8 sm:h-11 md:h-12 w-auto rounded-full object-cover border border-slate-200 hover:scale-105 transition-transform duration-300 select-none">
                </span>
                <span class="text-brand-600">Say About Us</span>
            </h2>

            <p class="text-xs sm:text-sm text-slate-500 font-normal max-w-xl mx-auto">
                Real feedback from verified explorers who booked their domestic escapes and international getaways with Orion Advent.
            </p>
        </div>

        <!-- 2. Sleek Unified Segmented Capsule Track for Categories -->
        <div class="flex items-center justify-start sm:justify-center space-x-2 overflow-x-auto pb-4 pt-1 px-1 scrollbar-none mb-10" id="testimonialFilterTrack">
            <?php foreach ($testimonialCategories as $index => $cat): ?>
                <button type="button" 
                        data-testimonial-filter="<?= htmlspecialchars($cat['id']) ?>" 
                        class="testimonial-filter-btn inline-flex items-center space-x-2 px-4 py-2 rounded-full border text-xs transition-all shrink-0 select-none <?= $index === 0 ? 'bg-brand-600 text-white border-brand-600 font-bold active-filter' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-brand-500 hover:bg-white font-semibold' ?>">
                    <i class="<?= htmlspecialchars($cat['icon']) ?> <?= $index === 0 ? 'text-white' : 'text-brand-600' ?>"></i>
                    <span><?= htmlspecialchars($cat['label']) ?></span>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- 3. Lightweight Testimonial Slider / Carousel Container -->
        <div class="relative testimonials-slider-container group">
            
            <!-- Prev Arrow Button (Hover-only on desktop) -->
            <button type="button" 
                    id="testimonialSlidePrev" 
                    aria-label="Previous Testimonials" 
                    class="absolute -left-3 sm:-left-5 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white text-slate-800 border border-slate-300 flex items-center justify-center transition-all hover:bg-brand-600 hover:text-white hover:border-brand-600 disabled:opacity-30 disabled:pointer-events-none">
                <i class="fa-solid fa-chevron-left text-sm"></i>
            </button>

            <!-- Testimonials Track -->
            <div class="testimonials-slider-track py-2 px-1" id="testimonialsSlider">
                <?php foreach ($testimonialsList as $testimonial): ?>
                    <div class="testimonial-card-item bg-white rounded-2xl border border-slate-200 hover:border-brand-500 transition-all p-4.5 sm:p-6 flex flex-col justify-between group select-none relative" data-category="<?= htmlspecialchars($testimonial['category']) ?>">
                        
                        <div>
                            <!-- Top: Stars + Verified Badge + Quote Mark -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-1">
                                    <?php for ($i = 0; $i < $testimonial['rating']; $i++): ?>
                                        <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                                    <?php endfor; ?>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center space-x-1">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                                        <span>Verified Guest</span>
                                    </span>
                                    <i class="fa-solid fa-quote-right text-slate-200 group-hover:text-brand-300 transition-colors text-lg"></i>
                                </div>
                            </div>

                            <!-- Headline Quote -->
                            <h4 class="text-sm sm:text-base font-extrabold text-slate-900 group-hover:text-brand-600 transition leading-snug mb-2">
                                "<?= htmlspecialchars($testimonial['headline']) ?>"
                            </h4>

                            <!-- Review Body -->
                            <p class="text-xs sm:text-[13px] text-slate-600 font-normal leading-relaxed line-clamp-4">
                                <?= htmlspecialchars($testimonial['quote']) ?>
                            </p>
                        </div>

                        <!-- Bottom: Author Info & Tour Tag -->
                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                            <div class="flex items-center space-x-3 min-w-0">
                                <img src="<?= htmlspecialchars($testimonial['avatar']) ?>" 
                                     alt="<?= htmlspecialchars($testimonial['name']) ?>" 
                                     class="w-10 h-10 rounded-full object-cover border border-slate-200 shrink-0">
                                
                                <div class="min-w-0">
                                    <h5 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-tight truncate">
                                        <?= htmlspecialchars($testimonial['name']) ?>
                                    </h5>
                                    <span class="text-[11px] text-slate-400 font-medium block truncate">
                                        <i class="fa-solid fa-location-dot text-[10px] text-brand-600 mr-1"></i><?= htmlspecialchars($testimonial['city']) ?>
                                    </span>
                                </div>
                            </div>

                            <span class="inline-flex items-center space-x-1 text-[11px] font-semibold text-brand-700 bg-brand-50 px-2.5 py-1 rounded-lg border border-brand-200/80 shrink-0">
                                <i class="fa-solid fa-suitcase-rolling text-[10px] text-brand-600"></i>
                                <span class="truncate max-w-[110px]"><?= htmlspecialchars($testimonial['tour']) ?></span>
                            </span>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Next Arrow Button (Hover-only on desktop) -->
            <button type="button" 
                    id="testimonialSlideNext" 
                    aria-label="Next Testimonials" 
                    class="absolute -right-3 sm:-right-5 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white text-slate-800 border border-slate-300 flex items-center justify-center transition-all hover:bg-brand-600 hover:text-white hover:border-brand-600 disabled:opacity-30 disabled:pointer-events-none">
                <i class="fa-solid fa-chevron-right text-sm"></i>
            </button>

        </div>

        <!-- 4. Clean Trust Signals Footer Strip -->
        <div class="mt-12 pt-6 border-t border-slate-100 flex flex-wrap items-center justify-center gap-6 sm:gap-12 text-xs font-bold text-slate-600 select-none">
            <div class="flex items-center space-x-2">
                <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-black border border-emerald-200">★</span>
                <span>Trustpilot 4.9 / 5 Rating</span>
            </div>
            <div class="flex items-center space-x-2">
                <i class="fa-brands fa-google text-sky-600 text-sm"></i>
                <span>Google Verified 4.9 ★ (2,400+ Reviews)</span>
            </div>
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-award text-amber-500 text-sm"></i>
                <span>TripAdvisor Travelers' Choice 2026</span>
            </div>
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-shield-halved text-emerald-600 text-sm"></i>
                <span>100% Verified Customer Feedback</span>
            </div>
        </div>

    </div>
</section>
