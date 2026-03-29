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

        .label-strong,
        .label-info,
        .label-minimal {
            max-width: calc(100% - 2rem);
            border-radius: 999px;
            backdrop-filter: blur(16px);
            z-index: 20;
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

        .label-info {
            top: 4.25rem;
            right: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            background: rgba(8, 16, 25, 0.9);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 0.9rem 1.1rem;
        }

        .label-minimal {
            top: 4.25rem;
            left: 1rem;
            background: rgba(8, 16, 25, 0.86);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 0.75rem 1rem;
        }

        .scale input,
        .binary-choice input {
            position: absolute;
            opacity: 0;
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
            .label-info,
            .label-minimal {
                top: 3.5rem;
            }
        }
    </style>
</head>
<body class="px-3 py-4 md:px-4" dir="rtl">
    <main class="mx-auto grid max-w-6xl gap-4 xl:grid-cols-[minmax(360px,520px)_minmax(340px,520px)] xl:items-start xl:justify-center">
        <section class="viewer-shell mx-auto w-full max-w-[520px] xl:sticky xl:top-4">
            <div class="media-stage">
                <video id="experiment-video" controls playsinline preload="metadata">
                    <source src="{{ asset('videos/' . $video['file']) }}" type="video/mp4">
                </video>

                @if($condition === 'strong')
                    <div class="label-strong absolute">
                        <span class="text-base font-bold">!</span>
                        <div class="text-sm font-semibold">{{ $conditionMeta['label_ar'] }}</div>
                    </div>
                @endif

                @if($condition === 'informational')
                    <div class="label-info absolute">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5 text-emerald-200" aria-hidden="true">
                            <path d="M12 3l2.7 5.3L20 11l-5.3 2.7L12 19l-2.7-5.3L4 11l5.3-2.7L12 3z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                        </svg>
                        <div class="text-sm font-semibold">{{ $conditionMeta['label_ar'] }}</div>
                    </div>
                @endif

                @if($condition === 'minimalist')
                    <div class="label-minimal absolute">
                        <div class="text-sm font-medium">{{ $conditionMeta['label_ar'] }}</div>
                    </div>
                @endif
            </div>
            <div class="border-t border-white/10 px-4 py-3">
                <p class="text-lg font-semibold text-white">{{ $video['title_ar'] }}</p>
            </div>
        </section>

        <section class="rounded-[1.8rem] border border-white/16 bg-[rgba(8,18,30,0.88)] p-5 backdrop-blur-xl">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-amber-50">أسئلة الاستبيان</h1>
            </div>

            <form id="experiment-form" method="POST" action="{{ route('experiment.store', ['participant' => $participant->public_token, 'step' => $step]) }}" class="space-y-5">
                @csrf
                <input type="hidden" name="page_view_duration_ms" value="0">
                <input type="hidden" name="decision_time_ms" value="0">
                <input type="hidden" name="time_to_first_play_ms" value="">
                <input type="hidden" name="time_to_first_response_ms" value="">
                <input type="hidden" name="video_watch_time_ms" value="0">
                <input type="hidden" name="video_watch_ratio_percent" value="0">
                <input type="hidden" name="video_completion_percent" value="0">
                <input type="hidden" name="play_count" value="0">
                <input type="hidden" name="pause_count" value="0">
                <input type="hidden" name="ended_count" value="0">
                <input type="hidden" name="seek_count" value="0">
                <input type="hidden" name="seek_forward_count" value="0">
                <input type="hidden" name="seek_backward_count" value="0">
                <input type="hidden" name="rewatch_count" value="0">
                <input type="hidden" name="total_seek_distance_ms" value="0">
                <input type="hidden" name="focus_loss_count" value="0">
                <input type="hidden" name="focus_loss_duration_ms" value="0">
                <input type="hidden" name="fullscreen_count" value="0">
                <input type="hidden" name="volume_change_count" value="0">
                <input type="hidden" name="playback_rate_change_count" value="0">
                <input type="hidden" name="form_interaction_count" value="0">
                <input type="hidden" name="answer_change_count" value="0">
                <input type="hidden" name="submit_attempt_count" value="1">

                <div class="field-card">
                    <p class="mb-3 text-lg font-semibold">1) هل تعتقد أن هذا الفيديو حقيقي أم مزيف؟</p>
                    <div class="binary-choice grid grid-cols-2 gap-3 text-base">
                        <div class="relative">
                            <input type="radio" name="real_or_fake" id="real_or_fake_real" value="real" required {{ old('real_or_fake') === 'real' ? 'checked' : '' }}>
                            <label for="real_or_fake_real">حقيقي</label>
                        </div>
                        <div class="relative">
                            <input type="radio" name="real_or_fake" id="real_or_fake_fake" value="fake" required {{ old('real_or_fake') === 'fake' ? 'checked' : '' }}>
                            <label for="real_or_fake_fake">مزيف</label>
                        </div>
                    </div>
                </div>

                <div class="field-card">
                    <p class="mb-3 text-lg font-semibold">2) ما مدى احتمال اعتقادك بأن هذا الفيديو تم إنشاؤه أو تعديله بشكل كبير بواسطة الذكاء الاصطناعي؟</p>
                    <div class="scale grid grid-cols-5 gap-3 text-base">
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="relative">
                                <input type="radio" name="ai_likelihood" id="ai_likelihood_{{ $i }}" value="{{ $i }}" required {{ old('ai_likelihood') == $i ? 'checked' : '' }}>
                                <label for="ai_likelihood_{{ $i }}">{{ $i }}</label>
                            </div>
                        @endfor
                    </div>
                    <div class="mt-2 flex justify-between text-xs text-white/85">
                        <span>مستبعد جداً</span>
                        <span>محتمل جداً</span>
                    </div>
                </div>

                <div class="field-card">
                    <p class="mb-3 text-lg font-semibold">3) قيم مدى ثقتك في صحة إجابتك (50 تعني تخمين عشوائي، 100 تعني تأكد تام):</p>
                    <input
                        type="range"
                        name="confidence_probability"
                        id="confidence_probability"
                        min="50"
                        max="100"
                        step="1"
                        value="{{ old('confidence_probability', 75) }}"
                        class="w-full accent-amber-300"
                        oninput="document.getElementById('conf_val').textContent = this.value"
                    >
                    <div class="mt-2 flex items-center justify-between text-sm text-white/88">
                        <span>50</span>
                        <span id="conf_val" class="text-lg font-bold text-amber-100">{{ old('confidence_probability', 75) }}</span>
                        <span>100</span>
                    </div>
                </div>

                @if ($hasLabel)
                    <div class="field-card">
                        <p class="mb-3 text-lg font-semibold">4) ما مدى ثقتك في التصنيف الموضح؟</p>

                        <div class="def-box mb-4 p-3">
                            <div class="flex gap-2">
                                <span class="text-sm text-amber-200">ⓘ</span>
                                <p class="text-sm leading-relaxed text-white/94">
                                    <strong>ملاحظة:</strong> يقصد بـ <strong>التصنيف</strong> النص الظاهر على الفيديو (مثل "تم إنشاؤه بواسطة الذكاء الاصطناعي") والذي يوضح كيفية إنشاء المحتوى.
                                </p>
                            </div>
                        </div>

                        <div class="scale grid grid-cols-5 gap-3 text-base">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="relative">
                                    <input type="radio" name="trust_label" id="trust_label_{{ $i }}" value="{{ $i }}" required {{ old('trust_label') == $i ? 'checked' : '' }}>
                                    <label for="trust_label_{{ $i }}">{{ $i }}</label>
                                </div>
                            @endfor
                        </div>
                        <div class="mt-2 flex justify-between text-xs text-white/85">
                            <span>منخفضة جداً</span>
                            <span>عالية جداً</span>
                        </div>
                    </div>

                    <div class="field-card mt-5">
                        <p class="mb-3 text-lg font-semibold">5) إذا رأيت هذا التحذير على منصة تواصل اجتماعي، ما مدى ثقتك في هذه المنصة؟</p>
                        <div class="scale grid grid-cols-5 gap-3 text-base">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="relative">
                                    <input type="radio" name="trust_platform" id="trust_platform_{{ $i }}" value="{{ $i }}" required {{ old('trust_platform') == $i ? 'checked' : '' }}>
                                    <label for="trust_platform_{{ $i }}">{{ $i }}</label>
                                </div>
                            @endfor
                        </div>
                        <div class="mt-2 flex justify-between text-xs text-white/85">
                            <span>منخفضة جداً</span>
                            <span>عالية جداً</span>
                        </div>
                    </div>

                    <div class="field-card mt-5">
                        <p class="mb-3 text-lg font-semibold">6) ساعدني هذا التصنيف في اتخاذ قرارات مدروسة حول كيفية التفاعل مع المحتوى.</p>
                        <div class="scale grid grid-cols-5 gap-3 text-base">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="relative">
                                    <input type="radio" name="informed_engagement" id="inf_eng_{{ $i }}" value="{{ $i }}" required {{ old('informed_engagement') == $i ? 'checked' : '' }}>
                                    <label for="inf_eng_{{ $i }}">{{ $i }}</label>
                                </div>
                            @endfor
                        </div>
                        <div class="mt-2 flex justify-between text-xs text-white/85">
                            <span>أعارض بشدة</span>
                            <span>أوافق بشدة</span>
                        </div>
                    </div>
                @endif

                <textarea name="notes" rows="3" placeholder="ملاحظة اختيارية..." class="w-full rounded-xl border border-white/20 bg-white/8 px-4 py-3 text-base text-white outline-none focus:border-amber-200/70">{{ old('notes') }}</textarea>

                @if ($errors->any())
                    <div class="rounded-xl border border-red-300/35 bg-red-500/14 p-3 text-sm text-red-50">يرجى الإجابة على جميع الأسئلة المطلوبة.</div>
                @endif

                <div class="flex justify-start border-t border-white/10 pt-6">
                    <button type="submit" class="w-full rounded-full bg-gradient-to-r from-[#ffd08a] to-[#8fe0d3] px-10 py-4 text-lg font-extrabold text-slate-950 shadow-lg transition hover:scale-[1.02] md:w-auto">
                        {{ $step === $totalSteps ? 'إنهاء الاستبيان' : 'السؤال التالي' }}
                    </button>
                </div>
            </form>
        </section>
    </main>

    <script>
        (() => {
            const form = document.getElementById('experiment-form');
            const video = document.getElementById('experiment-video');

            if (!form || !video) {
                return;
            }

            const pageLoadAt = performance.now();
            const hiddenFields = Object.fromEntries(
                Array.from(form.querySelectorAll('input[type="hidden"][name]')).map((input) => [input.name, input])
            );

            const state = {
                playCount: 0,
                pauseCount: 0,
                endedCount: 0,
                seekCount: 0,
                seekForwardCount: 0,
                seekBackwardCount: 0,
                rewatchCount: 0,
                totalSeekDistanceMs: 0,
                focusLossCount: 0,
                focusLossDurationMs: 0,
                fullscreenCount: 0,
                volumeChangeCount: 0,
                playbackRateChangeCount: 0,
                formInteractionCount: 0,
                answerChangeCount: 0,
                submitAttemptCount: 0,
                timeToFirstPlayMs: null,
                timeToFirstResponseMs: null,
                lastWatchStartAt: null,
                videoWatchTimeMs: 0,
                maxProgressSeconds: 0,
                lastSeekFromSeconds: 0,
                hiddenAt: document.hidden ? performance.now() : null,
            };

            const writeMetric = (name, value) => {
                if (hiddenFields[name]) {
                    hiddenFields[name].value = value;
                }
            };

            const roundedNow = () => Math.round(performance.now() - pageLoadAt);

            const recordFirstResponse = () => {
                if (state.timeToFirstResponseMs === null) {
                    state.timeToFirstResponseMs = roundedNow();
                }
            };

            video.addEventListener('play', () => {
                state.playCount += 1;
                if (state.timeToFirstPlayMs === null) {
                    state.timeToFirstPlayMs = roundedNow();
                }
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
                state.endedCount += 1;
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

                state.seekCount += 1;
                state.totalSeekDistanceMs += Math.round(Math.abs(deltaSeconds) * 1000);

                if (deltaSeconds > 0.25) {
                    state.seekForwardCount += 1;
                } else if (deltaSeconds < -0.25) {
                    state.seekBackwardCount += 1;
                    if (toSeconds + 0.25 < state.maxProgressSeconds) {
                        state.rewatchCount += 1;
                    }
                }
            });

            video.addEventListener('volumechange', () => {
                state.volumeChangeCount += 1;
            });

            video.addEventListener('ratechange', () => {
                state.playbackRateChangeCount += 1;
            });

            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    state.focusLossCount += 1;
                    state.hiddenAt = performance.now();
                    if (state.lastWatchStartAt !== null) {
                        state.videoWatchTimeMs += performance.now() - state.lastWatchStartAt;
                        state.lastWatchStartAt = null;
                    }
                } else if (state.hiddenAt !== null) {
                    state.focusLossDurationMs += performance.now() - state.hiddenAt;
                    state.hiddenAt = null;
                    if (!video.paused && !video.ended) {
                        state.lastWatchStartAt = performance.now();
                    }
                }
            });

            document.addEventListener('fullscreenchange', () => {
                if (document.fullscreenElement) {
                    state.fullscreenCount += 1;
                }
            });

            form.querySelectorAll('input:not([type="hidden"]), textarea, select').forEach((field) => {
                const eventName = field.matches('input[type="range"], textarea') ? 'input' : 'change';

                field.addEventListener(eventName, () => {
                    state.formInteractionCount += 1;
                    recordFirstResponse();
                });

                if (field.matches('input[type="radio"], input[type="checkbox"], select')) {
                    field.addEventListener('change', () => {
                        state.answerChangeCount += 1;
                    });
                }
            });

            form.addEventListener('submit', () => {
                state.submitAttemptCount += 1;

                if (state.lastWatchStartAt !== null) {
                    state.videoWatchTimeMs += performance.now() - state.lastWatchStartAt;
                    state.lastWatchStartAt = null;
                }

                if (document.hidden && state.hiddenAt !== null) {
                    state.focusLossDurationMs += performance.now() - state.hiddenAt;
                    state.hiddenAt = performance.now();
                }

                const pageViewDurationMs = roundedNow();
                const durationMs = Math.round((video.duration || 0) * 1000);
                const watchRatioPercent = durationMs > 0
                    ? ((state.videoWatchTimeMs / durationMs) * 100)
                    : 0;
                const completionPercent = durationMs > 0
                    ? (Math.min(state.maxProgressSeconds, video.duration || 0) / (video.duration || 1)) * 100
                    : 0;

                writeMetric('page_view_duration_ms', pageViewDurationMs);
                writeMetric('decision_time_ms', pageViewDurationMs);
                writeMetric('time_to_first_play_ms', state.timeToFirstPlayMs ?? '');
                writeMetric('time_to_first_response_ms', state.timeToFirstResponseMs ?? '');
                writeMetric('video_watch_time_ms', Math.round(state.videoWatchTimeMs));
                writeMetric('video_watch_ratio_percent', watchRatioPercent.toFixed(2));
                writeMetric('video_completion_percent', completionPercent.toFixed(2));
                writeMetric('play_count', state.playCount);
                writeMetric('pause_count', state.pauseCount);
                writeMetric('ended_count', state.endedCount);
                writeMetric('seek_count', state.seekCount);
                writeMetric('seek_forward_count', state.seekForwardCount);
                writeMetric('seek_backward_count', state.seekBackwardCount);
                writeMetric('rewatch_count', state.rewatchCount);
                writeMetric('total_seek_distance_ms', state.totalSeekDistanceMs);
                writeMetric('focus_loss_count', state.focusLossCount);
                writeMetric('focus_loss_duration_ms', Math.round(state.focusLossDurationMs));
                writeMetric('fullscreen_count', state.fullscreenCount);
                writeMetric('volume_change_count', state.volumeChangeCount);
                writeMetric('playback_rate_change_count', state.playbackRateChangeCount);
                writeMetric('form_interaction_count', state.formInteractionCount);
                writeMetric('answer_change_count', state.answerChangeCount);
                writeMetric('submit_attempt_count', state.submitAttemptCount);
            });
        })();
    </script>
</body>
</html>
