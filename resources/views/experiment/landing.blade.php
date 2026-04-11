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
                <p class="mb-4 text-lg leading-relaxed text-white/72" dir="ltr">Pre-Experiment Survey</p>
                <p class="max-w-3xl text-xl leading-relaxed text-white/92">
                    ندعوكم للمشاركة في دراسة تهدف إلى فهم كيفية تقييم الأشخاص للفيديوهات الحقيقية وتلك المصنوعة بالذكاء الاصطناعي. ستشاهد {{ $videoCount }} فيديوهات قصيرة وتجيب عن بعض الأسئلة.
                </p>
                <p class="mt-3 max-w-3xl text-base leading-relaxed text-white/72" dir="ltr">
                    You are invited to take part in a research study that examines how people evaluate authentic videos and AI-generated videos. You will watch {{ $videoCount }} short videos and answer a few brief questions.
                </p>
            </div>

            <div class="mb-10 rounded-2xl border border-white/15 bg-white/8 p-6">
                <p class="mb-2 text-xl font-bold text-white">معلومات مهمة:</p>
                <p class="mb-2 text-sm text-white/70" dir="ltr">Important Information</p>
                <p class="text-lg leading-relaxed text-white/90">
                    مشاركتكم في هذه الدراسة اختيارية تمامًا، ويمكنكم التوقف في أي وقت. نؤكد لكم أن جميع الإجابات ستبقى سرية ومجهولة الهوية، ولن يتم ربطها ببياناتكم الشخصية، حيث تُستخدم فقط لأغراض البحث العلمي.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-white/72" dir="ltr">
                    Participation in this study is entirely voluntary, and you may stop at any time. All responses will remain confidential and anonymous, will not be linked to your personal identity, and will be used only for scientific research purposes.
                </p>
            </div>

            <div class="mb-8 rounded-2xl border border-amber-200/40 bg-amber-200/14 p-5">
                <p class="text-lg font-bold text-amber-50">ما المقصود بالتزييف العميق؟</p>
                <p class="mt-1 text-sm text-amber-50/80" dir="ltr">What is a deepfake?</p>
                <p class="mt-2 text-lg leading-8 text-amber-50/95">
                    التزييف العميق هو فيديو تم إنشاؤه أو تعديله باستخدام الذكاء الاصطناعي ليبدو حقيقيًا.
                </p>
                <p class="mt-2 text-sm leading-relaxed text-amber-50/80" dir="ltr">
                    A deepfake is a video that has been created or altered using artificial intelligence to appear authentic.
                </p>
            </div>

            <form method="POST" action="{{ route('experiment.start') }}" id="preExperimentForm" class="space-y-8">
                @csrf

                <div class="space-y-4">
                    <h2 class="text-2xl font-bold">1. هل توافق على المشاركة في هذه الدراسة؟</h2>
                    <p class="text-sm text-white/70" dir="ltr">1. Do you agree to participate in this study?</p>
                    <div class="grid gap-3 md:grid-cols-2">
                        <label class="choice-card rounded-2xl px-4 py-3 text-center">
                            <input type="radio" name="consent" value="yes" required class="hidden">
                            <span>
                                <span class="block text-xl font-bold">نعم، أوافق</span>
                                <span class="mt-1 block text-sm font-medium text-white/75" dir="ltr">Yes, I agree</span>
                            </span>
                        </label>
                        <label class="choice-card danger rounded-2xl px-4 py-3 text-center">
                            <input type="radio" name="consent" value="no" required class="hidden">
                            <span>
                                <span class="block text-xl font-bold">لا، لا أرغب</span>
                                <span class="mt-1 block text-sm font-medium text-white/75" dir="ltr">No, I do not wish to participate</span>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="grid gap-8 md:grid-cols-2">
                    <div class="space-y-4">
                        <h2 class="text-xl font-bold">2. هل عمرك 18 سنة أو أكثر؟</h2>
                        <p class="text-sm text-white/70" dir="ltr">2. Are you 18 years of age or older?</p>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="choice-card rounded-xl text-center"><input type="radio" name="age_18" value="yes" required class="hidden"><span><span class="block text-lg font-bold">نعم</span><span class="block text-xs text-white/75" dir="ltr">Yes</span></span></label>
                            <label class="choice-card danger rounded-xl text-center"><input type="radio" name="age_18" value="no" class="hidden"><span><span class="block text-lg font-bold">لا</span><span class="block text-xs text-white/75" dir="ltr">No</span></span></label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h2 class="text-xl font-bold">3. هل أنت ليبي/ليبية؟</h2>
                        <p class="text-sm text-white/70" dir="ltr">3. Are you Libyan?</p>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="choice-card rounded-xl text-center"><input type="radio" name="reside_libya" value="yes" required class="hidden"><span><span class="block text-lg font-bold">نعم</span><span class="block text-xs text-white/75" dir="ltr">Yes</span></span></label>
                            <label class="choice-card danger rounded-xl text-center"><input type="radio" name="reside_libya" value="no" class="hidden"><span><span class="block text-lg font-bold">لا</span><span class="block text-xs text-white/75" dir="ltr">No</span></span></label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h2 class="text-xl font-bold">4. هل تستخدم الإنترنت بانتظام؟</h2>
                        <p class="text-sm text-white/70" dir="ltr">4. Do you use the internet regularly?</p>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="choice-card rounded-xl text-center"><input type="radio" name="internet_regular" value="yes" required class="hidden"><span><span class="block text-lg font-bold">نعم</span><span class="block text-xs text-white/75" dir="ltr">Yes</span></span></label>
                            <label class="choice-card danger rounded-xl text-center"><input type="radio" name="internet_regular" value="no" class="hidden"><span><span class="block text-lg font-bold">لا</span><span class="block text-xs text-white/75" dir="ltr">No</span></span></label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h2 class="text-xl font-bold">5. هل سمعت من قبل عن "التزييف العميق"؟</h2>
                        <p class="text-sm text-white/70" dir="ltr">5. Have you heard of "deepfake" before?</p>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="choice-card rounded-xl text-center"><input type="radio" name="heard_deepfake" value="yes" required class="hidden"><span><span class="block text-lg font-bold">نعم</span><span class="block text-xs text-white/75" dir="ltr">Yes</span></span></label>
                            <label class="choice-card rounded-xl text-center"><input type="radio" name="heard_deepfake" value="no" class="hidden"><span><span class="block text-lg font-bold">لا</span><span class="block text-xs text-white/75" dir="ltr">No</span></span></label>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-4">
                    <h2 class="text-xl font-bold">6. ما هي فئتك العمرية؟</h2>
                    <p class="text-sm text-white/70" dir="ltr">6. What is your age group?</p>
                    <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                        <label class="choice-card rounded-xl py-4"><input type="radio" name="age_group" value="18-24" required class="hidden"><span class="latin text-lg font-bold">18-24</span></label>
                        <label class="choice-card rounded-xl py-4"><input type="radio" name="age_group" value="25-34" class="hidden"><span class="latin text-lg font-bold">25-34</span></label>
                        <label class="choice-card rounded-xl py-4"><input type="radio" name="age_group" value="35-44" class="hidden"><span class="latin text-lg font-bold">35-44</span></label>
                        <label class="choice-card rounded-xl py-4"><input type="radio" name="age_group" value="45+" class="hidden"><span class="latin text-lg font-bold">45+</span></label>
                    </div>
                </div>

                <div id="ineligibleMessage" class="hidden rounded-2xl border border-red-500/20 bg-red-500/10 px-6 py-5 text-center transition-all">
                    <div class="flex flex-col items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-sm font-semibold tracking-wide text-red-100">عذرًا، أنت غير مؤهل للمشاركة في هذه الدراسة.</p>
                        <p class="text-xs text-red-100/80" dir="ltr">Sorry, you are not eligible to participate in this study.</p>
                        <span class="sr-only">Start the experiment</span>
                    </div>
                </div>

                <div class="flex flex-col gap-6 border-t border-white/10 pt-8 md:flex-row md:items-center md:justify-between">
                    <p class="text-base text-white/85">بالضغط على زر البدء، سيتم نقلك مباشرة إلى صفحة التجربة.</p>
                    <p class="text-sm text-white/70" dir="ltr">By selecting the start button, you will be taken directly to the experiment page.</p>
                    <button type="submit" class="rounded-full bg-gradient-to-r from-[#ff9a76] to-[#ffd08a] px-12 py-4 text-center text-xl font-extrabold text-slate-950 shadow-[0_15px_40px_rgba(242,106,75,0.3)] transition-all hover:-translate-y-1">
                        <span class="block">ابدأ التجربة الآن</span>
                        <span class="block text-sm font-semibold" dir="ltr">Start the Experiment</span>
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
                data.get('reside_libya') === 'yes';

            if (!eligible) {
                e.preventDefault();
                ineligibleMessage.classList.remove('hidden');
                ineligibleMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>
</body>
</html>
