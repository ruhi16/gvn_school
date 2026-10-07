<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Green Vally Nursary School - Nursery School Management System</title>
        <!-- Load assets via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fonts -->
    <link rel="preconnect" href="https://googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://googleapis.com/css2?family=Fredoka:wght@300..700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS (Via CDN for instant utility, matches Laravel 12 styling paradigms) -->
    <script src="https://tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        brand: ['Fredoka', 'sans-serif'],
                        sans: ['Nunito', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#FDFBF7] font-sans antialiased text-gray-700">

    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-sm border-b border-orange-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo / School Name -->
                <div class="flex items-center gap-2">
                    <span class="text-3xl">🧸</span>
                    <span class="font-brand font-bold text-2xl tracking-wide text-orange-500">Green Vally Nursary <span class="text-amber-500">School</span></span>
                </div>

                <!-- Auth Navigation Options -->
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="font-bold text-gray-600 hover:text-orange-500 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-orange-500 px-3 py-2 rounded-lg transition">Log in</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold px-5 py-2.5 rounded-full shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                                    Parent Registration
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative overflow-hidden bg-gradient-to-b from-amber-50 to-[#FDFBF7] py-16 sm:py-24">
        <!-- Floating Abstract Background Elements for Playground Vibe -->
        <div class="absolute top-10 left-10 text-4xl opacity-20 select-none animate-bounce">🎨</div>
        <div class="absolute bottom-10 right-10 text-4xl opacity-20 select-none">🧩</div>
        <div class="absolute top-1/3 right-1/4 text-4xl opacity-10 select-none">🚀</div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left Grid: Headline Copy -->
            <div class="space-y-6 text-center lg:text-left">
                <span class="inline-block bg-orange-100 text-orange-700 font-bold px-4 py-1.5 rounded-full text-sm uppercase tracking-wider">
                    Smart Nursery Management
                </span>
                <h1 class="font-brand font-bold text-4xl sm:text-5xl lg:text-6xl text-slate-800 leading-tight">
                    Where Little Steps Lead to <span class="text-amber-500 underline decoration-wavy decoration-orange-300">Big Dreams</span>
                </h1>
                <p class="text-lg text-gray-600 max-w-xl mx-auto lg:mx-0">
                    Welcome to the Green Vally Nursary Portal. Bridging the gap between tracking classrooms, assignments, child safety, and nursery updates seamlessly for parents and early educators.
                </p>
                
                <!-- CTA Action Center -->
                <div class="flex flex-wrap gap-4 justify-center lg:justify-start pt-2">
                    <a href="{{ route('login') }}" class="bg-amber-500 hover:bg-amber-600 text-white font-bold text-lg px-8 py-3.5 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                        Portal Sign In
                    </a>
                    <a href="#features" class="border-2 border-slate-200 hover:border-orange-300 text-slate-700 font-semibold text-lg px-8 py-3.5 rounded-full transition-all bg-white/80">
                        Explore Features
                    </a>
                </div>
            </div>

            <!-- Right Grid: Visual Interactive Panel / Mock Illustration Container -->
            <div class="flex justify-center items-center">
                <div class="relative bg-white p-8 rounded-3xl shadow-xl border border-amber-100 w-full max-w-md transform rotate-1 hover:rotate-0 transition-transform duration-300">
                    <div class="absolute -top-5 -right-5 bg-rose-400 text-white font-brand text-sm px-4 py-1 rounded-full shadow-md transform rotate-12">
                        Admissions Open! 🎉
                    </div>
                    <div class="space-y-4">
                        <div class="bg-amber-100 h-48 rounded-2xl flex flex-col justify-center items-center text-center p-4">
                            <span class="text-6xl mb-2">🎒</span>
                            <h3 class="font-brand font-bold text-xl text-amber-800">Quick Parent Portal</h3>
                            <p class="text-xs text-amber-700">Access regular infant schedules, meals, and activities</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('login') }}" class="bg-sky-500 hover:bg-sky-600 text-white font-bold p-4 rounded-xl text-center shadow transition">
                                <span class="block text-2xl mb-1">👨‍🏫</span>
                                <span class="text-sm">Teacher Login</span>
                            </a>
                            <a href="{{ route('login') }}" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold p-4 rounded-xl text-center shadow transition">
                                <span class="block text-2xl mb-1">🛡️</span>
                                <span class="text-sm">Admin Portal</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Notices Section -->
    <section id="notices" class="py-16 bg-white border-y border-amber-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-8">
                <div>
                    <span class="text-sm font-bold uppercase tracking-wider text-orange-600">School updates</span>
                    <h2 class="font-brand font-bold text-3xl sm:text-4xl text-slate-800 mt-2">Latest Notices</h2>
                </div>
                <p class="text-gray-500 text-sm">Important announcements, dates, and documents for families.</p>
            </div>

            <div class="max-h-[32rem] overflow-y-auto pr-2 space-y-4">
                @forelse ($notices as $notice)
                    <article class="bg-[#FDFBF7] border border-amber-100 rounded-2xl p-5 sm:p-6 shadow-sm">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-orange-600">
                                    {{ optional($notice->upload_dt)->format('d M Y') ?? 'Date not specified' }}
                                </p>
                                <h3 class="font-brand font-bold text-xl text-slate-800 mt-1">{{ $notice->title }}</h3>
                                @if ($notice->description)
                                    <p class="text-gray-600 text-sm leading-relaxed mt-2">{{ $notice->description }}</p>
                                @endif
                            </div>

                            <div class="flex flex-wrap gap-2 shrink-0">
                                @if ($notice->notice_img_ref)
                                    <a href="{{ Storage::url($notice->notice_img_ref) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white font-bold px-4 py-2 rounded-lg transition">
                                        View Image
                                    </a>
                                @endif
                                @if ($notice->notice_pdf_ref)
                                    <a href="{{ Storage::url($notice->notice_pdf_ref) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white font-bold px-4 py-2 rounded-lg transition">
                                        View PDF
                                    </a>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-amber-200 bg-[#FDFBF7] p-10 text-center text-gray-500">
                        No notices are available right now.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Photo Gallery Section -->
    <section id="photo-gallery" class="py-16 bg-[#FDFBF7] border-b border-amber-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-8">
                <div>
                    <span class="text-sm font-bold uppercase tracking-wider text-orange-600">School life</span>
                    <h2 class="font-brand font-bold text-3xl sm:text-4xl text-slate-800 mt-2">Photo Gallery</h2>
                </div>
                <p class="text-gray-500 text-sm">{{ $session?->name ?? 'Current school session' }}</p>
            </div>
            @forelse ($galleries->groupBy('category_name') as $categoryName => $categoryPhotos)
                <section class="mb-8 last:mb-0">
                    <h3 class="font-brand font-bold text-xl text-slate-800 mb-4">{{ $categoryName ?: 'School photos' }}</h3>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                        @foreach ($categoryPhotos as $photo)
                            <figure class="overflow-hidden rounded-lg border border-amber-100 bg-white shadow-sm">
                                <a href="{{ Storage::url($photo->image_path) }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ Storage::url($photo->image_path) }}" alt="{{ $photo->image_alt_text ?: $photo->name }}" class="aspect-[4/3] w-full object-cover">
                                </a>
                                @if ($photo->image_caption || $photo->name)
                                    <figcaption class="px-3 py-2 text-xs font-semibold text-slate-700">{{ $photo->image_caption ?: $photo->name }}</figcaption>
                                @endif
                            </figure>
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="rounded-lg border border-dashed border-amber-200 bg-white/70 p-8 text-center text-sm text-gray-500">No gallery photos are available for this session.</div>
            @endforelse
        </div>
    </section>

    <!-- Question Paper Archive Section -->
    <section id="question-papers" class="py-16 bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-8">
                <div>
                    <span class="text-sm font-bold uppercase tracking-wider text-cyan-700">Study resources</span>
                    <h2 class="font-brand font-bold text-3xl sm:text-4xl text-slate-800 mt-2">Question Papers</h2>
                </div>
                <p class="text-gray-500 text-sm">{{ $session?->name ?? 'Current school session' }}</p>
            </div>
            @if ($archiveClasses->isNotEmpty())
                <div role="tablist" aria-label="Question papers by class" class="flex flex-wrap gap-2 border-b border-slate-200">
                    @foreach ($archiveClasses as $class)
                        <button type="button" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-controls="paper-class-{{ $class->id }}" data-archive-tab="paper-class-{{ $class->id }}" class="border-b-2 px-4 py-2 text-sm font-semibold {{ $loop->first ? 'border-cyan-700 text-cyan-800' : 'border-transparent text-slate-500 hover:text-slate-900' }}">{{ $class->name }}</button>
                    @endforeach
                </div>
                @foreach ($archiveClasses as $class)
                    @php($classArchives = $questionArchives->where('shreny_id', $class->id))
                    <div id="paper-class-{{ $class->id }}" role="tabpanel" class="space-y-5 pt-5 {{ $loop->first ? '' : 'hidden' }}" @if (!$loop->first) hidden @endif>
                        @foreach ($classArchives->groupBy(fn ($paper) => $paper->exam_name_id . ':' . $paper->exam_type_id . ':' . $paper->exam_part_id) as $combinationPapers)
                            @php($combinationPaper = $combinationPapers->first())
                            <section class="border-l-4 border-cyan-600 pl-4">
                                <h3 class="font-brand font-bold text-lg text-slate-800">{{ $combinationPaper->examName?->name }} / {{ $combinationPaper->examType?->name }} / {{ $combinationPaper->examPart?->name }}</h3>
                                <div class="mt-3 divide-y divide-slate-100 border-y border-slate-100">
                                    @foreach ($combinationPapers as $paper)
                                        <article class="flex flex-col gap-3 py-3 sm:flex-row sm:items-center sm:justify-between">
                                            <div><h4 class="font-semibold text-slate-800">{{ $paper->subject?->name ?? $paper->name }}</h4><p class="text-xs text-slate-500">{{ $paper->name }}{{ $paper->description ? ' · '.$paper->description : '' }}</p></div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                @if ($paper->question_paper_pdf_ref)
                                                    <a href="{{ Storage::url($paper->question_paper_pdf_ref) }}" download class="rounded-md bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700">Download PDF</a>
                                                @endif
                                                @if ($paper->question_paper_img_ref)
                                                    <a href="{{ Storage::url($paper->question_paper_img_ref) }}" target="_blank" rel="noopener noreferrer" aria-label="View {{ $paper->name }} image">
                                                        <img src="{{ Storage::url($paper->question_paper_img_ref) }}" alt="{{ $paper->name }}" class="h-16 w-20 rounded border border-slate-200 object-cover">
                                                    </a>
                                                @endif
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>
                @endforeach
            @else
                <div class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-sm text-slate-500">No question papers are available for this session.</div>
            @endif
        </div>
    </section>

    <!-- Core Features Section -->
    <section id="features" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
            <h2 class="font-brand font-bold text-3xl sm:text-4xl text-slate-800">Designed with Loving Care & Precision</h2>
            <p class="text-gray-500">Everything needed to oversee a high-performing nursery education structure seamlessly.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center text-2xl mb-6">👁️</div>
                <h3 class="font-brand font-bold text-xl text-slate-800 mb-2">Real-time Tracking</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Keep absolute track of classroom dynamics, daily meal details, activities, and sleep logs.</p>
            </div>
            <!-- Feature 2 -->
            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center text-2xl mb-6">💬</div>
                <h3 class="font-brand font-bold text-xl text-slate-800 mb-2">Parent Messaging</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Direct channels linking guardians directly with teachers for seamless communications.</p>
            </div>
            <!-- Feature 3 -->
            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-sky-100 flex items-center justify-center text-2xl mb-6">📊</div>
                <h3 class="font-brand font-bold text-xl text-slate-800 mb-2">Easy Invoicing</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Automate nursery fees, generate statements, and track upcoming family balances.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <p class="font-brand text-xl text-white">🧸 Green Vally Nursary School</p>
            <p class="text-sm">&copy; {{ date('Y') }} Nursery Management System. Built securely with Laravel 12.</p>
        </div>
    </footer>

    <script>
        document.querySelectorAll('[data-archive-tab]').forEach((tab) => {
            tab.addEventListener('click', () => {
                const section = tab.closest('#question-papers');
                section.querySelectorAll('[data-archive-tab]').forEach((item) => {
                    item.setAttribute('aria-selected', 'false');
                    item.classList.remove('border-cyan-700', 'text-cyan-800');
                    item.classList.add('border-transparent', 'text-slate-500');
                });
                section.querySelectorAll('[role="tabpanel"]').forEach((panel) => {
                    panel.hidden = true;
                    panel.classList.add('hidden');
                });
                tab.setAttribute('aria-selected', 'true');
                tab.classList.add('border-cyan-700', 'text-cyan-800');
                tab.classList.remove('border-transparent', 'text-slate-500');
                const panel = document.getElementById(tab.dataset.archiveTab);
                panel.hidden = false;
                panel.classList.remove('hidden');
            });
        });
    </script>

</body>
</html>
