<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الاستبيان التمهيدي للتجربة</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=IBM+Plex+Sans+Arabic:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --panel: rgba(11, 23, 37, 0.96);
            --panel-soft: rgba(255, 255, 255, 0.06);
            --border: rgba(255, 255, 255, 0.18);
            --text: #fff8ef;
            --accent: #ff9a76;
            --teal: #6bc7ba;
        }

        body {
            font-family: 'Cairo', sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top right, rgba(255, 154, 118, 0.14), transparent 28%),
                radial-gradient(circle at bottom left, rgba(107, 199, 186, 0.14), transparent 30%),
                linear-gradient(180deg, #04101a 0%, #07131f 52%, #0b1826 100%);
            min-height: 100vh;
        }

        .latin { font-family: 'IBM Plex Sans Arabic', sans-serif; }

        .panel {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 1.75rem;
            backdrop-filter: blur(14px);
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.3);
        }

        .choice-card {
            border: 1px solid rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.06);
            transition: 160ms ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-height: 4rem;
        }

        .choice-card:hover { background: rgba(255, 255, 255, 0.1); }

        .choice-card:has(input:checked) {
            background: rgba(107, 199, 186, 0.18);
            border-color: var(--teal);
            color: #ecfffb;
        }

        .choice-card.danger:has(input:checked) {
            background: rgba(255, 154, 118, 0.2);
            border-color: var(--accent);
            color: #fff4ee;
        }
    </style>
</head>
<body class="px-4 py-6 md:px-6 md:py-10">
    <main class="mx-auto max-w-4xl">
        <section class="panel p-6 md:p-10">
            <div class="mb-8 border-b border-white/10 pb-8">
                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/8 px-4 py-2 text-sm font-semibold text-white/90">
                    <span class="h-2.5 w-2.5 rounded-full bg-[var(--accent)]"></span>
                    دراسة بحثية: تقييم المحتوى البصري
                </div>
                <h1 class="mb-4 text-4xl font-extrabold leading-tight md:text-5xl">
                    الاستبيان التمهيدي للتجربة
                </h1>
                <p class="max-w-3xl text-xl leading-relaxed text-white/92">
                    ندعوكم للمشاركة في دراسة تهدف لفهم كيفية تقييم الناس للفيديوهات الحقيقية وتلك المصنوعة بالذكاء الاصطناعي. ستشاهد {{ $videoCount }} فيديوهات قصيرة وتجيب على بعض الأسئلة.
                </p>
            </div>

            <div class="mb-10 rounded-2xl border border-white/15 bg-white/8 p-6">
                <p class="mb-2 text-xl font-bold text-white">معلومات هامة:</p>
                <p class="text-lg leading-relaxed text-white/90">
                    مشاركتكم في هذه الدراسة اختيارية تماماً، ويمكنكم التوقف في أي وقت. نؤكد لكم أن جميع الإجابات ستبقى سرية ومجهولة الهوية، ولن يتم ربطها ببياناتكم الشخصية، حيث تُستخدم فقط لأغراض البحث العلمي.
                </p>
            </div>

            <div class="mb-8 rounded-2xl border border-amber-200/40 bg-amber-200/14 p-5">
                <p class="text-lg font-bold text-amber-50">ما المقصود بالديب فيك؟</p>
                <p class="mt-2 text-lg leading-8 text-amber-50/95">
                    الديب فيك هو فيديو تم إنشاؤه أو تعديله باستخدام الذكاء الاصطناعي ليبدو حقيقياً.
                </p>
            </div>

            <form method="POST" action="{{ route('experiment.start') }}" id="preExperimentForm" class="space-y-8">
                @csrf

                <div class="space-y-4">
                    <h2 class="text-2xl font-bold">1. هل توافق على المشاركة في هذه الدراسة؟</h2>
                    <div class="grid gap-3 md:grid-cols-2">
                        <label class="choice-card rounded-2xl px-4 py-3">
                            <input type="radio" name="consent" value="yes" required class="hidden">
                            <span class="text-xl font-bold">نعم، أوافق</span>
                        </label>
                        <label class="choice-card danger rounded-2xl px-4 py-3">
                            <input type="radio" name="consent" value="no" required class="hidden">
                            <span class="text-xl font-bold">لا، لا أرغب</span>
                        </label>
                    </div>
                </div>

                <div class="grid gap-8 md:grid-cols-2">
                    <div class="space-y-4">
                        <h2 class="text-xl font-bold">2. هل عمرك 18 سنة أو أكثر؟</h2>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="choice-card rounded-xl">
                                <input type="radio" name="age_18" value="yes" required class="hidden">
                                <span class="text-lg font-bold">نعم</span>
                            </label>
                            <label class="choice-card danger rounded-xl">
                                <input type="radio" name="age_18" value="no" class="hidden">
                                <span class="text-lg font-bold">لا</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h2 class="text-xl font-bold">3. هل أنت ليبي/ليبية؟</h2>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="choice-card rounded-xl">
                                <input type="radio" name="reside_libya" value="yes" required class="hidden">
                                <span class="text-lg font-bold">نعم</span>
                            </label>
                            <label class="choice-card danger rounded-xl">
                                <input type="radio" name="reside_libya" value="no" class="hidden">
                                <span class="text-lg font-bold">لا</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h2 class="text-xl font-bold">4. هل تستخدم الإنترنت بانتظام؟</h2>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="choice-card rounded-xl">
                                <input type="radio" name="internet_regular" value="yes" required class="hidden">
                                <span class="text-lg font-bold">نعم</span>
                            </label>
                            <label class="choice-card danger rounded-xl">
                                <input type="radio" name="internet_regular" value="no" class="hidden">
                                <span class="text-lg font-bold">لا</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h2 class="text-xl font-bold">5. هل سمعت من قبل عن "الديب فيك"؟</h2>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="choice-card rounded-xl">
                                <input type="radio" name="heard_deepfake" value="yes" required class="hidden">
                                <span class="text-lg font-bold">نعم</span>
                            </label>
                            <label class="choice-card rounded-xl">
                                <input type="radio" name="heard_deepfake" value="no" class="hidden">
                                <span class="text-lg font-bold">لا</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-4">
                    <h2 class="text-xl font-bold">6. ما هي فئتك العمرية؟</h2>
                    <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                        <label class="choice-card rounded-xl py-4">
                            <input type="radio" name="age_group" value="18-24" required class="hidden">
                            <span class="latin text-lg font-bold">18-24</span>
                        </label>
                        <label class="choice-card rounded-xl py-4">
                            <input type="radio" name="age_group" value="25-34" class="hidden">
                            <span class="latin text-lg font-bold">25-34</span>
                        </label>
                        <label class="choice-card rounded-xl py-4">
                            <input type="radio" name="age_group" value="35-44" class="hidden">
                            <span class="latin text-lg font-bold">35-44</span>
                        </label>
                        <label class="choice-card rounded-xl py-4">
                            <input type="radio" name="age_group" value="45+" class="hidden">
                            <span class="latin text-lg font-bold">45+</span>
                        </label>
                    </div>
                </div>

                <div id="ineligibleMessage" class="hidden rounded-2xl border border-red-300/40 bg-red-500/14 px-5 py-4 text-center text-base text-red-50">
                    عذراً، يجب استيفاء شروط المشاركة (الموافقة، العمر، والهوية الليبية) للمتابعة.
                    <span class="sr-only">Start experiment</span>
                </div>

                <div class="flex flex-col gap-6 border-t border-white/10 pt-8 md:flex-row md:items-center md:justify-between">
                    <p class="text-base text-white/85">
                        بالضغط على زر البدء، سيتم نقلك إلى صفحة التجربة مباشرة.
                    </p>
                    <button type="submit" class="rounded-full bg-gradient-to-r from-[#ff9a76] to-[#ffd08a] px-12 py-4 text-xl font-extrabold text-slate-950 shadow-[0_15px_40px_rgba(242,106,75,0.3)] transition-all hover:-translate-y-1">
                        ابدأ التجربة الآن
                    </button>
                </div>
            </form>
        </section>
    </main>

    <script>
        const form = document.getElementById('preExperimentForm');
        const ineligibleMessage = document.getElementById('ineligibleMessage');

        form.addEventListener('submit', function (e) {
            const data = new FormData(form);
            const eligible =
                data.get('consent') === 'yes' &&
                data.get('age_18') === 'yes' &&
                data.get('reside_libya') === 'yes' &&
                data.get('internet_regular') === 'yes';

            if (!eligible) {
                e.preventDefault();
                ineligibleMessage.classList.remove('hidden');
                ineligibleMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>
</body>
</html>
