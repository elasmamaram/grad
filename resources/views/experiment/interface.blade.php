<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>واجهة التجربة</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'IBM Plex Sans Arabic', sans-serif;
            color: #fff8ef;
            background:
                radial-gradient(circle at top left, rgba(255, 154, 118, 0.16), transparent 26%),
                radial-gradient(circle at bottom right, rgba(107, 199, 186, 0.16), transparent 28%),
                linear-gradient(180deg, #040910 0%, #08111a 45%, #0c1723 100%);
        }

        .viewer-shell {
            background: linear-gradient(180deg, rgba(255,255,255,0.12), rgba(255,255,255,0.05));
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.5);
        }

        .label-fullscreen-stage {
            position: relative;
        }

        .label-fullscreen-stage:fullscreen,
        .label-fullscreen-stage:-webkit-full-screen,
        .label-fullscreen-stage.is-fullscreen {
            width: 100vw;
            height: 100vh;
            background: #000;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .media-stage {
            position: relative;
            width: 100%;
            min-height: 26rem;
            max-height: 72vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.92), rgba(6, 11, 18, 0.96));
        }

        .media-stage video {
            width: auto;
            max-width: 100%;
            height: auto;
            max-height: calc(72vh - 2rem);
            object-fit: contain;
            display: block;
            border-radius: 1rem;
            background: #000;
            box-shadow: 0 18px 48px rgba(0, 0, 0, 0.35);
        }

        .label-fullscreen-stage:fullscreen .media-stage,
        .label-fullscreen-stage:-webkit-full-screen .media-stage,
        .label-fullscreen-stage.is-fullscreen .media-stage {
            flex: 1 1 auto;
            min-height: 0;
            max-height: none;
            padding: 1.5rem;
            background: #000;
        }

        .label-fullscreen-stage:fullscreen .media-stage video,
        .label-fullscreen-stage:-webkit-full-screen .media-stage video,
        .label-fullscreen-stage.is-fullscreen .media-stage video {
            max-width: 100%;
            max-height: 100%;
        }

        .label-strong,
        .label-minimal {
            max-width: calc(100% - 2rem);
            border-radius: 999px;
            backdrop-filter: blur(16px);
            z-index: 20;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .label-strong {
            top: 4.25rem;
            right: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            background: linear-gradient(90deg, rgba(139, 16, 16, 0.98), rgba(232, 90, 69, 0.94));
            padding: 0.9rem 1.1rem;
            box-shadow: 0 14px 34px rgba(173, 32, 32, 0.35);
        }

        .label-minimal {
            top: 4.9rem;
            left: 1rem;
            background: rgba(8, 16, 25, 0.86);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 0.75rem 1rem;
            direction: rtl;
        }

        .label-minimal-star {
            order: -1;
            flex: 0 0 auto;
            width: 1.55rem;
            height: 1.55rem;
            border-radius: 999px;
            color: #8fe0d3;
            background:
                radial-gradient(circle, rgba(143, 224, 211, 0.2), rgba(143, 224, 211, 0.04) 62%),
                rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(143, 224, 211, 0.38);
            padding: 0.28rem;
            box-shadow: 0 0 22px rgba(143, 224, 211, 0.22);
        }

        [data-label-block][data-label-lang="en"].label-minimal {
            direction: ltr;
        }

        .label-fullscreen-stage:fullscreen .label-strong,
        .label-fullscreen-stage:-webkit-full-screen .label-strong,
        .label-fullscreen-stage.is-fullscreen .label-strong {
            top: 1rem;
        }

        .label-fullscreen-stage:fullscreen .label-minimal,
        .label-fullscreen-stage:-webkit-full-screen .label-minimal,
        .label-fullscreen-stage.is-fullscreen .label-minimal {
            top: 4.75rem;
        }

        .label-content-row,
        .label-text-shell {
            min-width: 0;
        }

        .label-content-row {
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            direction: rtl;
        }

        .label-text-shell {
            direction: rtl;
            text-align: right;
        }

        [data-label-block][data-label-lang="en"] .label-content-row,
        [data-label-block][data-label-lang="en"] .label-info-row,
        [data-label-block][data-label-lang="en"] .label-text-shell,
        [data-label-block][data-label-lang="en"] .label-info-copy {
            direction: ltr;
            text-align: left;
        }

        .label-language-toggle {
            flex: 0 0 auto;
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 999px;
            background: rgba(4, 10, 18, 0.34);
            color: #fff7ef;
            padding: 0.38rem 0.7rem;
            font-size: 0.72rem;
            font-weight: 700;
            line-height: 1;
            transition: 160ms ease;
        }

        .label-language-toggle:hover {
            background: rgba(4, 10, 18, 0.5);
        }

        .label-language-toggle:focus-visible {
            outline: 2px solid rgba(255, 208, 138, 0.9);
            outline-offset: 2px;
        }

        .fullscreen-toggle {
            position: absolute;
            top: 1rem;
            left: 1rem;
            z-index: 35;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 999px;
            background: rgba(4, 10, 18, 0.5);
            color: #fff7ef;
            padding: 0.45rem 0.78rem;
            font-size: 0.74rem;
            font-weight: 700;
            line-height: 1;
            transition: 160ms ease;
        }

        .fullscreen-toggle:hover {
            background: rgba(4, 10, 18, 0.68);
        }

        .fullscreen-toggle:focus-visible {
            outline: 2px solid rgba(255, 208, 138, 0.9);
            outline-offset: 2px;
        }

        .fullscreen-toggle-exit {
            display: none;
        }

        .label-fullscreen-stage:fullscreen .fullscreen-toggle-enter,
        .label-fullscreen-stage:-webkit-full-screen .fullscreen-toggle-enter,
        .label-fullscreen-stage.is-fullscreen .fullscreen-toggle-enter {
            display: none;
        }

        .label-fullscreen-stage:fullscreen .fullscreen-toggle-exit,
        .label-fullscreen-stage:-webkit-full-screen .fullscreen-toggle-exit,
        .label-fullscreen-stage.is-fullscreen .fullscreen-toggle-exit {
            display: inline;
        }

        .label-english {
            direction: ltr;
            text-align: left;
        }

        .label-info-panel {
            border-top: 1px solid rgba(255,255,255,0.08);
            background:
                linear-gradient(180deg, rgba(14, 18, 25, 0.96), rgba(9, 12, 18, 0.96));
            padding: 0.95rem 1rem 1rem;
        }

        .label-fullscreen-stage:fullscreen .label-info-panel,
        .label-fullscreen-stage:-webkit-full-screen .label-info-panel,
        .label-fullscreen-stage.is-fullscreen .label-info-panel {
            border-top-color: rgba(255,255,255,0.1);
            background: linear-gradient(180deg, rgba(14, 18, 25, 0.98), rgba(9, 12, 18, 0.98));
        }

        .label-info-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.9rem;
        }

        .label-info-row {
            display: flex;
            align-items: flex-start;
            gap: 0.9rem;
            direction: rtl;
            flex: 1 1 auto;
        }

        .label-info-icon {
            flex: 0 0 auto;
            width: 2rem;
            height: 2rem;
            border-radius: 999px;
            border: 2px solid #ff7a59;
            color: #ff7a59;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
            line-height: 1;
            margin-top: 0.15rem;
        }

        .label-info-copy {
            min-width: 0;
            direction: rtl;
            text-align: right;
        }

        .label-info-title {
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.45;
            color: #fff7ef;
        }

        .label-info-detail {
            margin-top: 0.35rem;
            font-size: 0.84rem;
            line-height: 1.6;
            color: rgba(255, 248, 239, 0.78);
        }

        .label-info-advisory {
            margin-top: 0.35rem;
            font-size: 0.84rem;
            line-height: 1.6;
            color: #ffd8c9;
        }

        .scale input,
        .binary-choice input {
            position: absolute;
            opacity: 0;
        }

        .scale,
        .scale-axis {
            direction: ltr;
        }

        .scale label,
        .binary-choice label {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 3.6rem;
            border-radius: 0.9rem;
            border: 1px solid rgba(255,255,255,0.16);
            background: rgba(255,255,255,0.08);
            cursor: pointer;
            transition: 160ms ease;
            font-size: 1rem;
            font-weight: 700;
        }

        .scale input:checked + label,
        .binary-choice input:checked + label {
            border-color: rgba(255, 210, 138, 0.95);
            background: linear-gradient(180deg, rgba(255,210,138,0.24), rgba(255,210,138,0.12));
        }

        .field-card {
            border: 1px solid rgba(255,255,255,0.16);
            background: rgba(255,255,255,0.06);
            border-radius: 1.25rem;
            padding: 1.25rem;
        }

        .def-box {
            border: 1px solid rgba(255, 210, 138, 0.45);
            background: rgba(255, 210, 138, 0.12);
            border-radius: 1rem;
        }

        @media (max-width: 767px) {
            .media-stage {
                min-height: 20rem;
                max-height: 58vh;
                padding: 0.75rem;
            }

            .media-stage video {
                max-height: calc(58vh - 1.5rem);
            }

            .label-strong,
            .label-minimal {
                top: 3.5rem;
            }

            .label-minimal {
                top: 4.25rem;
            }

            .label-info-panel {
                padding: 0.85rem 0.85rem 0.95rem;
            }

            .fullscreen-toggle {
                top: 0.75rem;
                left: 0.75rem;
                padding: 0.4rem 0.7rem;
                font-size: 0.7rem;
            }

            .label-language-toggle {
                padding: 0.34rem 0.62rem;
                font-size: 0.68rem;
            }

            .label-info-head {
                flex-wrap: wrap;
            }

            .label-info-row {
                gap: 0.75rem;
            }

            .label-info-icon {
                width: 1.85rem;
                height: 1.85rem;
                font-size: 0.95rem;
            }

            .label-info-title {
                font-size: 0.95rem;
            }

            .label-info-detail {
                font-size: 0.8rem;
            }

            .label-info-advisory {
                font-size: 0.8rem;
            }
        }

        .media-stage video::-webkit-media-controls-fullscreen-button {
            display: none;
        }
    </style>
</head>
<body class="px-3 py-4 md:px-4" dir="rtl">
    <main class="mx-auto grid max-w-6xl gap-4 xl:grid-cols-[minmax(360px,520px)_minmax(340px,520px)] xl:items-start xl:justify-center">
        <section class="viewer-shell mx-auto w-full max-w-[520px] xl:sticky xl:top-4">
            <div class="label-fullscreen-stage" id="label-fullscreen-stage">
            <div class="media-stage">
                <video id="experiment-video" controls controlsList="nofullscreen" playsinline preload="metadata">
                    <source src="{{ asset('videos/' . $video['file']) }}" type="video/mp4">
                </video>

                <button type="button" id="fullscreen-toggle" class="fullscreen-toggle" aria-label="Toggle fullscreen video with label">
                    <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                        <path d="M4 9V4h5M20 9V4h-5M4 15v5h5M20 15v5h-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="fullscreen-toggle-enter">ملء الشاشة</span>
                    <span class="fullscreen-toggle-exit">إنهاء الملء</span>
                </button>

                @if($condition === 'strong')
                    <div class="label-strong absolute" data-label-block>
                        <div class="label-content-row">
                            <span class="text-base font-bold">!</span>
                            <div class="label-text-shell">
                                <span class="text-sm font-semibold" data-lang-copy="ar">{{ $conditionMeta['label_ar'] }}</span>
                                <span class="hidden text-sm font-semibold label-english" data-lang-copy="en">{{ $conditionMeta['label_en'] }}</span>
                            </div>
                        </div>
                        <button type="button" class="label-language-toggle" data-label-toggle>
                            <span data-toggle-target="en">English</span>
                            <span class="hidden" data-toggle-target="ar">العربية</span>
                        </button>
                    </div>
                @endif

                @if($condition === 'minimalist')
                    <div class="label-minimal absolute" data-label-block>
                        <div class="label-text-shell">
                            <span class="text-sm font-medium" data-lang-copy="ar">{{ $conditionMeta['label_ar'] }}</span>
                            <span class="hidden text-sm font-medium label-english" data-lang-copy="en">{{ $conditionMeta['label_en'] }}</span>
                        </div>
                        <svg viewBox="0 0 24 24" fill="none" class="label-minimal-star" aria-hidden="true">
                            <path d="M12 3l2.7 5.3L20 11l-5.3 2.7L12 19l-2.7-5.3L4 11l5.3-2.7L12 3z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                        </svg>
                        <button type="button" class="label-language-toggle" data-label-toggle>
                            <span data-toggle-target="en">English</span>
                            <span class="hidden" data-toggle-target="ar">العربية</span>
                        </button>
                    </div>
                @endif
            </div>

            @if($condition === 'informational')
                <div class="label-info-panel" data-label-block>
                    <div class="label-info-head">
                        <div class="label-info-row">
                            <span class="label-info-icon" aria-hidden="true">!</span>
                            <div class="label-info-copy">
                                <p class="label-info-title" data-lang-copy="ar">{{ $conditionMeta['label_ar'] }}</p>
                                <p class="hidden label-info-title label-english" data-lang-copy="en">{{ $conditionMeta['label_en'] }}</p>
                                <p class="label-info-detail" data-lang-copy="ar">{{ $conditionMeta['detail_ar'] }}</p>
                                <p class="hidden label-info-detail label-english" data-lang-copy="en">{{ $conditionMeta['detail_en'] }}</p>
                                <p class="label-info-advisory" data-lang-copy="ar">{{ $conditionMeta['advisory_ar'] }}</p>
                                <p class="hidden label-info-advisory label-english" data-lang-copy="en">{{ $conditionMeta['advisory_en'] }}</p>
                            </div>
                        </div>
                        <button type="button" class="label-language-toggle" data-label-toggle>
                            <span data-toggle-target="en">English</span>
                            <span class="hidden" data-toggle-target="ar">العربية</span>
                        </button>
                    </div>
                </div>
            @endif
            </div>

            <div class="border-t border-white/10 px-4 py-3">
                <p class="text-lg font-semibold text-white">{{ $video['title_ar'] }}</p>
                <p class="mt-1 text-sm text-white/65" dir="ltr">{{ $video['title_en'] }}</p>
            </div>
        </section>

        <section class="rounded-[1.8rem] border border-white/16 bg-[rgba(8,18,30,0.88)] p-5 backdrop-blur-xl">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-amber-50">أسئلة الاستبيان</h1>
                <p class="mt-2 text-sm text-white/70" dir="ltr">Survey Questions</p>
            </div>

            <form id="experiment-form" method="POST" action="{{ route('experiment.store', ['participant' => $participant->public_token, 'step' => $step]) }}" class="space-y-5">
                @csrf
                <input type="hidden" name="decision_time_ms" value="0">
                <input type="hidden" name="video_watch_ratio_percent" value="0">
                <input type="hidden" name="pause_count" value="0">
                <input type="hidden" name="rewatch_count" value="0">

                <div class="field-card">
                    <p class="mb-1 text-lg font-semibold">1) هل تعتقد أن هذا الفيديو حقيقي أم مزيف؟</p>
                    <p class="mb-3 text-sm text-white/70" dir="ltr">1) Do you believe this video is real or fake?</p>
                    <div class="binary-choice grid grid-cols-2 gap-3 text-base">
                        <div class="relative">
                            <input type="radio" name="real_or_fake" id="real_or_fake_real" value="real" required {{ old('real_or_fake') === 'real' ? 'checked' : '' }}>
                            <label for="real_or_fake_real" class="flex-col gap-1">
                                <span>حقيقي</span>
                                <span class="text-xs font-medium text-white/75" dir="ltr">Real</span>
                            </label>
                        </div>
                        <div class="relative">
                            <input type="radio" name="real_or_fake" id="real_or_fake_fake" value="fake" required {{ old('real_or_fake') === 'fake' ? 'checked' : '' }}>
                            <label for="real_or_fake_fake" class="flex-col gap-1">
                                <span>مزيف</span>
                                <span class="text-xs font-medium text-white/75" dir="ltr">Fake</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="field-card">
                    <p class="mb-1 text-lg font-semibold">2) ما مدى احتمالية أن يكون هذا الفيديو مُنشأ أو مُعدّل باستخدام الذكاء الاصطناعي؟</p>
                    <p class="mb-3 text-sm text-white/70" dir="ltr">2) How likely is it that this video was created or altered using artificial intelligence?</p>
                    <div class="scale grid grid-cols-5 gap-3 text-base" dir="ltr">
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="relative">
                                <input type="radio" name="ai_likelihood" id="ai_likelihood_{{ $i }}" value="{{ $i }}" required {{ old('ai_likelihood') == $i ? 'checked' : '' }}>
                                <label for="ai_likelihood_{{ $i }}">{{ $i }}</label>
                            </div>
                        @endfor
                    </div>
                    <div class="scale-axis mt-2 flex justify-between gap-4 text-xs text-white/85" dir="ltr">
                        <span class="text-left">
                            <span class="block">1 - مستبعد جدًا</span>
                            <span class="block" dir="ltr">1 - Very unlikely</span>
                        </span>
                        <span class="text-right">
                            <span class="block">5 - محتمل جدًا</span>
                            <span class="block" dir="ltr">5 - Very likely</span>
                        </span>
                    </div>
                </div>

                <div class="field-card">
                    <p class="mb-1 text-lg font-semibold">3) ما مدى ثقتك في صحة إجابتك؟</p>
                    <p class="mb-3 text-sm text-white/70" dir="ltr">3) How confident are you in your answer?</p>
                    <div class="scale grid grid-cols-5 gap-3 text-base" dir="ltr">
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="relative">
                                <input type="radio" name="confidence_probability" id="confidence_probability_{{ $i }}" value="{{ $i }}" required {{ old('confidence_probability') == $i ? 'checked' : '' }}>
                                <label for="confidence_probability_{{ $i }}">{{ $i }}</label>
                            </div>
                        @endfor
                    </div>
                    <div class="scale-axis mt-2 flex justify-between gap-4 text-xs text-white/85" dir="ltr">
                        <span class="text-left">
                            <span class="block">1 - منخفضة جدًا</span>
                            <span class="block" dir="ltr">1 - Very low</span>
                        </span>
                        <span class="text-right">
                            <span class="block">5 - عالية جدًا</span>
                            <span class="block" dir="ltr">5 - Very high</span>
                        </span>
                    </div>
                </div>

                @if ($hasLabel)
                    <div class="field-card">
                        <p class="mb-1 text-lg font-semibold">4) ما درجة عدم اليقين التي شعرت بها عند اتخاذ قرارك بشأن هذا الفيديو؟</p>
                        <p class="mb-3 text-sm text-white/70" dir="ltr">4) How uncertain did you feel when deciding whether this video was real or fake?</p>
                        <div class="def-box mb-4 p-3">
                            <div class="flex gap-2">
                                <span class="text-sm text-amber-200">i</span>
                                <p class="text-sm leading-relaxed text-white/94">المقصود بعدم اليقين هو مدى ترددك في تحديد ما إذا كان الفيديو حقيقيًا أم مزيفًا.</p>
                            </div>
                            <p class="mt-2 text-xs leading-relaxed text-white/75" dir="ltr">Uncertainty refers to how hesitant you felt while deciding whether the video was authentic or fake.</p>
                        </div>
                        <div class="scale grid grid-cols-5 gap-3 text-base" dir="ltr">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="relative">
                                    <input type="radio" name="trust_platform" id="trust_platform_{{ $i }}" value="{{ $i }}" required {{ old('trust_platform') == $i ? 'checked' : '' }}>
                                    <label for="trust_platform_{{ $i }}">{{ $i }}</label>
                                </div>
                            @endfor
                        </div>
                        <div class="scale-axis mt-2 flex justify-between gap-4 text-xs text-white/85" dir="ltr">
                            <span class="text-left">
                                <span class="block">1 - منخفضة جدًا</span>
                                <span class="block" dir="ltr">1 - Very low</span>
                            </span>
                            <span class="text-right">
                                <span class="block">5 - عالية جدًا</span>
                                <span class="block" dir="ltr">5 - Very high</span>
                            </span>
                        </div>
                    </div>

                    <div class="field-card">
                        <p class="mb-1 text-lg font-semibold">5) ما مدى ثقتك في التصنيف الموضح؟</p>
                        <p class="mb-3 text-sm text-white/70" dir="ltr">5) How much do you trust the label shown on the video?</p>
                        <div class="def-box mb-4 p-3">
                            <div class="flex gap-2">
                                <span class="text-sm text-amber-200">i</span>
                                <p class="text-sm leading-relaxed text-white/94">يقصد بالتصنيف النص الظاهر على الفيديو، مثل "تم إنشاؤه بواسطة الذكاء الاصطناعي"، والذي يوضح كيفية إنشاء المحتوى.</p>
                            </div>
                            <p class="mt-2 text-xs leading-relaxed text-white/75" dir="ltr">The label refers to the text displayed on the video, such as "Made with AI," which explains how the content was produced.</p>
                        </div>
                        <div class="scale grid grid-cols-5 gap-3 text-base" dir="ltr">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="relative">
                                    <input type="radio" name="trust_label" id="trust_label_{{ $i }}" value="{{ $i }}" required {{ old('trust_label') == $i ? 'checked' : '' }}>
                                    <label for="trust_label_{{ $i }}">{{ $i }}</label>
                                </div>
                            @endfor
                        </div>
                        <div class="scale-axis mt-2 flex justify-between gap-4 text-xs text-white/85" dir="ltr">
                            <span class="text-left">
                                <span class="block">1 - منخفضة جدًا</span>
                                <span class="block" dir="ltr">1 - Very low</span>
                            </span>
                            <span class="text-right">
                                <span class="block">5 - عالية جدًا</span>
                                <span class="block" dir="ltr">5 - Very high</span>
                            </span>
                        </div>
                    </div>

                    <div class="field-card mt-5">
                        <p class="mb-1 text-lg font-semibold">6) ساعدني هذا التصنيف في اتخاذ قرارات مدروسة حول كيفية التفاعل مع المحتوى.</p>
                        <p class="mb-3 text-sm text-white/70" dir="ltr">6) This label helped me make informed decisions about how to engage with the content.</p>
                        <div class="scale grid grid-cols-5 gap-3 text-base" dir="ltr">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="relative">
                                    <input type="radio" name="information_credibility" id="information_credibility_{{ $i }}" value="{{ $i }}" required {{ old('information_credibility') == $i ? 'checked' : '' }}>
                                    <label for="information_credibility_{{ $i }}">{{ $i }}</label>
                                </div>
                            @endfor
                        </div>
                        <div class="scale-axis mt-2 flex justify-between gap-4 text-xs text-white/85" dir="ltr">
                            <span class="text-left">
                                <span class="block">1 - أعارض بشدة</span>
                                <span class="block" dir="ltr">1 - Strongly disagree</span>
                            </span>
                            <span class="text-right">
                                <span class="block">5 - أوافق بشدة</span>
                                <span class="block" dir="ltr">5 - Strongly agree</span>
                            </span>
                        </div>
                    </div>
                @else
                    <div class="field-card">
                        <p class="mb-1 text-lg font-semibold">4) ما درجة عدم اليقين التي شعرت بها عند اتخاذ قرارك بشأن هذا الفيديو؟</p>
                        <p class="mb-3 text-sm text-white/70" dir="ltr">4) How uncertain did you feel when deciding whether this video was real or fake?</p>
                        <div class="def-box mb-4 p-3">
                            <div class="flex gap-2">
                                <span class="text-sm text-amber-200">i</span>
                                <p class="text-sm leading-relaxed text-white/94">المقصود بعدم اليقين هو مدى ترددك في تحديد ما إذا كان الفيديو حقيقيًا أم مزيفًا.</p>
                            </div>
                            <p class="mt-2 text-xs leading-relaxed text-white/75" dir="ltr">Uncertainty refers to how hesitant you felt while deciding whether the video was authentic or fake.</p>
                        </div>
                        <div class="scale grid grid-cols-5 gap-3 text-base" dir="ltr">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="relative">
                                    <input type="radio" name="trust_platform" id="trust_platform_{{ $i }}" value="{{ $i }}" required {{ old('trust_platform') == $i ? 'checked' : '' }}>
                                    <label for="trust_platform_{{ $i }}">{{ $i }}</label>
                                </div>
                            @endfor
                        </div>
                        <div class="scale-axis mt-2 flex justify-between gap-4 text-xs text-white/85" dir="ltr">
                            <span class="text-left">
                                <span class="block">1 - منخفضة جدًا</span>
                                <span class="block" dir="ltr">1 - Very low</span>
                            </span>
                            <span class="text-right">
                                <span class="block">5 - عالية جدًا</span>
                                <span class="block" dir="ltr">5 - Very high</span>
                            </span>
                        </div>
                    </div>
                @endif

                <div class="field-card">
                    <label for="notes" class="mb-1 block text-lg font-semibold">ملاحظة اختيارية: ما المؤشرات التي اعتمدت عليها في الحكم على الفيديو؟</label>
                    <p class="mb-3 text-sm text-white/70" dir="ltr">Optional note: What cues or signals influenced your judgment?</p>
                    <textarea id="notes" name="notes" rows="4" placeholder="يمكنك ذكر تعابير الوجه، حركة الشفاه، الصوت، أو السياق. / You may mention facial expression, lip movement, voice, or contextual cues." class="w-full rounded-xl border border-white/20 bg-slate-900/80 px-4 py-3 text-base text-white placeholder:text-white/55 outline-none focus:border-amber-200/70 focus:ring-2 focus:ring-amber-200/20">{{ old('notes') }}</textarea>
                </div>

                @if ($errors->any())
                    <div class="rounded-xl border border-red-300/35 bg-red-500/14 p-4 text-sm text-red-50">
                        <p class="font-semibold">يرجى الإجابة على جميع الأسئلة المطلوبة.</p>
                        <p class="mt-1" dir="ltr">Please complete all required visible questions.</p>
                        <div class="mt-3 space-y-1 text-xs text-red-100/95">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex justify-start border-t border-white/10 pt-6">
                    <button
                        type="submit"
                        id="experiment-submit"
                        class="w-full rounded-full bg-gradient-to-r from-[#ffd08a] to-[#8fe0d3] px-10 py-4 text-center text-lg font-extrabold text-slate-950 shadow-lg transition hover:scale-[1.02] disabled:cursor-not-allowed disabled:opacity-70 md:w-auto"
                    >
                        @if ($step === $totalSteps)
                            <span class="block">إنهاء الاستبيان</span>
                            <span class="block text-sm font-semibold" dir="ltr">Finish Survey</span>
                        @else
                            <span class="block">السؤال التالي</span>
                            <span class="block text-sm font-semibold" dir="ltr">Next Question</span>
                        @endif
                    </button>
                </div>
            </form>
        </section>
    </main>

    <script>
        (() => {
            const form = document.getElementById('experiment-form');
            const video = document.getElementById('experiment-video');
            const fullscreenStage = document.getElementById('label-fullscreen-stage');
            const fullscreenToggle = document.getElementById('fullscreen-toggle');
            const submitButton = document.getElementById('experiment-submit');

            if (!form || !video || !fullscreenStage) {
                return;
            }

            const pageLoadAt = performance.now();
            const hiddenFields = Object.fromEntries(
                Array.from(form.querySelectorAll('input[type="hidden"][name]')).map((input) => [input.name, input])
            );

            const state = {
                pauseCount: 0,
                rewatchCount: 0,
                lastWatchStartAt: null,
                videoWatchTimeMs: 0,
                maxProgressSeconds: 0,
                lastSeekFromSeconds: 0,
                isSubmitting: false,
            };

            const writeMetric = (name, value) => {
                if (hiddenFields[name]) {
                    hiddenFields[name].value = value;
                }
            };

            const setLabelLanguage = (block, language) => {
                block.dataset.labelLang = language;

                block.querySelectorAll('[data-lang-copy]').forEach((node) => {
                    node.classList.toggle('hidden', node.dataset.langCopy !== language);
                });

                const toggleButton = block.querySelector('[data-label-toggle]');
                if (!toggleButton) {
                    return;
                }

                toggleButton.querySelectorAll('[data-toggle-target]').forEach((node) => {
                    node.classList.toggle('hidden', node.dataset.toggleTarget !== (language === 'ar' ? 'en' : 'ar'));
                });
            };

            const roundedNow = () => Math.round(performance.now() - pageLoadAt);
            const fullscreenElement = () => document.fullscreenElement || document.webkitFullscreenElement || null;
            const isStageFullscreen = () => fullscreenElement() === fullscreenStage;
            const supportsStageFullscreen = Boolean(fullscreenStage.requestFullscreen || fullscreenStage.webkitRequestFullscreen);
            const requestStageFullscreen = async () => {
                if (!supportsStageFullscreen) {
                    return;
                }

                if (fullscreenStage.requestFullscreen) {
                    await fullscreenStage.requestFullscreen();
                    return;
                }

                if (fullscreenStage.webkitRequestFullscreen) {
                    fullscreenStage.webkitRequestFullscreen();
                }
            };
            const exitStageFullscreen = async () => {
                if (document.fullscreenElement && document.exitFullscreen) {
                    await document.exitFullscreen();
                    return;
                }

                if (document.webkitFullscreenElement && document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
            };
            const syncFullscreenState = () => {
                fullscreenStage.classList.toggle('is-fullscreen', isStageFullscreen());
            };

            document.querySelectorAll('[data-label-block]').forEach((block) => {
                setLabelLanguage(block, 'ar');

                const toggleButton = block.querySelector('[data-label-toggle]');
                if (!toggleButton) {
                    return;
                }

                toggleButton.addEventListener('click', () => {
                    const nextLanguage = block.dataset.labelLang === 'ar' ? 'en' : 'ar';
                    setLabelLanguage(block, nextLanguage);
                });
            });

            syncFullscreenState();

            if (!supportsStageFullscreen && fullscreenToggle) {
                fullscreenToggle.classList.add('hidden');
            }

            if (fullscreenToggle) {
                fullscreenToggle.addEventListener('click', async () => {
                    if (isStageFullscreen()) {
                        await exitStageFullscreen();
                        return;
                    }

                    await requestStageFullscreen();
                });
            }

            video.addEventListener('dblclick', async (event) => {
                if (!supportsStageFullscreen) {
                    return;
                }

                event.preventDefault();

                if (isStageFullscreen()) {
                    await exitStageFullscreen();
                    return;
                }

                await requestStageFullscreen();
            });

            document.addEventListener('fullscreenchange', async () => {
                if (document.fullscreenElement === video && supportsStageFullscreen && document.exitFullscreen) {
                    await document.exitFullscreen();
                    await requestStageFullscreen();
                    return;
                }

                syncFullscreenState();
            });

            document.addEventListener('webkitfullscreenchange', syncFullscreenState);

            video.addEventListener('play', () => {
                state.lastWatchStartAt = performance.now();
            });

            video.addEventListener('pause', () => {
                if (state.lastWatchStartAt !== null) {
                    state.videoWatchTimeMs += performance.now() - state.lastWatchStartAt;
                    state.lastWatchStartAt = null;
                }
                state.pauseCount += 1;
            });

            video.addEventListener('ended', () => {
                if (state.lastWatchStartAt !== null) {
                    state.videoWatchTimeMs += performance.now() - state.lastWatchStartAt;
                    state.lastWatchStartAt = null;
                }
                state.maxProgressSeconds = Math.max(state.maxProgressSeconds, video.duration || 0);
            });

            video.addEventListener('timeupdate', () => {
                state.maxProgressSeconds = Math.max(state.maxProgressSeconds, video.currentTime || 0);
            });

            video.addEventListener('seeking', () => {
                state.lastSeekFromSeconds = video.currentTime || 0;
            });

            video.addEventListener('seeked', () => {
                const toSeconds = video.currentTime || 0;
                const fromSeconds = state.lastSeekFromSeconds || 0;
                const deltaSeconds = toSeconds - fromSeconds;

                if (deltaSeconds < -0.25 && toSeconds + 0.25 < state.maxProgressSeconds) {
                    state.rewatchCount += 1;
                }
            });

            form.addEventListener('submit', (event) => {
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                event.preventDefault();

                if (state.isSubmitting) {
                    return;
                }

                state.isSubmitting = true;

                if (state.lastWatchStartAt !== null) {
                    state.videoWatchTimeMs += performance.now() - state.lastWatchStartAt;
                    state.lastWatchStartAt = null;
                }

                const pageViewDurationMs = roundedNow();
                const durationMs = Math.round((video.duration || 0) * 1000);
                const watchRatioPercent = durationMs > 0
                    ? Math.min(100, Math.max(0, (state.videoWatchTimeMs / durationMs) * 100))
                    : 0;

                writeMetric('decision_time_ms', pageViewDurationMs);
                writeMetric('video_watch_ratio_percent', watchRatioPercent.toFixed(2));
                writeMetric('pause_count', state.pauseCount);
                writeMetric('rewatch_count', state.rewatchCount);

                if (submitButton) {
                    submitButton.disabled = true;
                }

                form.submit();
            });
        })();
    </script>
</body>
</html>
