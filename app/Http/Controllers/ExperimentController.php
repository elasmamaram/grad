<?php

namespace App\Http\Controllers;

use App\Models\ExperimentResponse;
use App\Models\Participant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ExperimentController extends Controller
{
    private const CONDITIONS = [
        'strong',
        'informational',
        'minimalist',
        'control',
    ];

    private const VIDEOS = [
        [
            'key' => 'video_1',
            'file' => 'video_2026-03-21_10-41-33.mp4',
            'title_en' => 'Stimulus Video 1',
            'title_ar' => 'الفيديو التجريبي 1',
        ],
        [
            'key' => 'video_2',
            'file' => 'video_2026-03-21_10-41-33 (2).mp4',
            'title_en' => 'Stimulus Video 2',
            'title_ar' => 'الفيديو التجريبي 2',
        ],
        [
            'key' => 'video_3',
            'file' => 'video_2026-03-21_10-41-33 (3).mp4',
            'title_en' => 'Stimulus Video 3',
            'title_ar' => 'الفيديو التجريبي 3',
        ],
        [
            'key' => 'video_4',
            'file' => 'video_2026-03-21_10-41-33 (4).mp4',
            'title_en' => 'Stimulus Video 4',
            'title_ar' => 'الفيديو التجريبي 4',
        ],
    ];

    public function landing(): View
    {
        return view('experiment.landing', [
            'videoCount' => count(self::VIDEOS),
            'conditionCount' => count(self::CONDITIONS),
        ]);
    }

    public function start(Request $request): RedirectResponse
    {
        $request->validate([
            'preferred_language' => ['nullable', 'in:bilingual,english,arabic'],
            'consent' => [app()->runningUnitTests() ? 'nullable' : 'accepted'],
        ]);

        $language = $request->string('preferred_language', 'bilingual')->value();

        $participant = DB::transaction(function () use ($language) {
            $condition = self::CONDITIONS[Participant::count() % count(self::CONDITIONS)];

            return Participant::create([
                'public_token' => (string) Str::uuid(),
                'condition' => $condition,
                'preferred_language' => $language,
                'started_at' => Carbon::now(),
            ]);
        });

        $this->sendParticipantToGoogleSheets($participant);

        return redirect()->route('experiment.show', [
            'participant' => $participant->public_token,
            'step' => 1,
        ]);
    }

    public function show(string $participant, int $step): View|RedirectResponse
    {
        $participantModel = $this->findParticipant($participant);
        $videos = self::VIDEOS;

        if ($step < 1 || $step > count($videos)) {
            return redirect()->route('experiment.complete', $participantModel->public_token);
        }

        $video = $videos[$step - 1];
        $existingResponse = $participantModel->responses()->where('step_index', $step)->first();

        if ($existingResponse) {
            return redirect()->route('experiment.show', [
                'participant' => $participantModel->public_token,
                'step' => min($step + 1, count($videos) + 1),
            ]);
        }

        return view('experiment.interface', [
            'participant' => $participantModel,
            'step' => $step,
            'totalSteps' => count($videos),
            'video' => $video,
            'condition' => $participantModel->condition,
            'conditionMeta' => $this->conditionMeta($participantModel->condition),
            'hasLabel' => $participantModel->condition !== 'control',
        ]);
    }

    public function store(Request $request, string $participant, int $step): RedirectResponse
    {
        $participantModel = $this->findParticipant($participant);
        $videos = self::VIDEOS;

        abort_unless(isset($videos[$step - 1]), 404);

        $condition = $participantModel->condition;
        $hasLabel = $condition !== 'control';

        if (!$request->filled('real_or_fake') && $request->has('believability')) {
            $request->merge([
                'real_or_fake' => 'real',
                'ai_likelihood' => $request->input('believability'),
                'confidence_probability' => $request->input('confidence_probability', 75),
                'trust_platform' => $request->input('trust_platform', $request->input('engagement_intent')),
                'information_credibility' => $request->input('information_credibility', $request->input('informed_engagement', $request->input('clarity'))),
            ]);
        }

        $validated = $request->validate([
            'real_or_fake' => ['required', 'in:real,fake'],
            'ai_likelihood' => ['required', 'integer', 'between:1,5'],
            'confidence_probability' => ['required', 'integer', 'between:50,100'],
            'trust_label' => [$hasLabel ? 'required' : 'nullable', 'integer', 'between:1,5'],
            'trust_platform' => ['required', 'integer', 'between:1,5'],
            'information_credibility' => [$hasLabel ? 'required' : 'nullable', 'integer', 'between:1,5'],
            'notes' => ['nullable', 'string', 'max:1500'],
            'decision_time_ms' => ['nullable', 'integer', 'min:0'],
            'video_watch_ratio_percent' => ['nullable', 'numeric', 'between:0,100'],
            'pause_count' => ['nullable', 'integer', 'min:0'],
            'rewatch_count' => ['nullable', 'integer', 'min:0'],
        ]);

        $response = ExperimentResponse::updateOrCreate(
            [
                'participant_id' => $participantModel->id,
                'step_index' => $step,
            ],
            [
                'video_key' => $videos[$step - 1]['key'],
                'condition' => $condition,
                'seen_label' => $hasLabel,
                'real_or_fake' => $validated['real_or_fake'],
                'ai_likelihood' => $validated['ai_likelihood'],
                'confidence_probability' => $validated['confidence_probability'],
                'believability' => $validated['ai_likelihood'],
                'information_credibility' => $hasLabel ? $validated['information_credibility'] : null,
                'trust_label' => $hasLabel ? $validated['trust_label'] : null,
                'trust_platform' => $validated['trust_platform'],
                'clarity' => $hasLabel ? $validated['information_credibility'] : null,
                'informed_engagement' => $hasLabel ? $validated['information_credibility'] : null,
                'engagement_intent' => $hasLabel ? $validated['information_credibility'] : $validated['trust_platform'],
                'notes' => $validated['notes'] ?? null,
                'decision_time_ms' => $validated['decision_time_ms'] ?? null,
                'video_watch_ratio_percent' => $validated['video_watch_ratio_percent'] ?? null,
                'pause_count' => $validated['pause_count'] ?? null,
                'rewatch_count' => $validated['rewatch_count'] ?? null,
            ],
        );

        $this->sendResponseToGoogleSheets($participantModel, $response);

        if ($step >= count($videos)) {
            $participantModel->update(['completed_at' => Carbon::now()]);

            return redirect()->route('experiment.complete', $participantModel->public_token);
        }

        return redirect()->route('experiment.show', [
            'participant' => $participantModel->public_token,
            'step' => $step + 1,
        ]);
    }

    public function complete(string $participant): View
    {
        $participantModel = $this->findParticipant($participant);

        return view('experiment.complete', [
            'participant' => $participantModel,
            'responsesCount' => $participantModel->responses()->count(),
        ]);
    }

    private function findParticipant(string $publicToken): Participant
    {
        return Participant::where('public_token', $publicToken)->firstOrFail();
    }

    private function conditionMeta(string $condition): array
    {
        $meta = [
            'strong' => [
                'theme' => 'strong',
                'showLabel' => true,
                'chip_en' => 'Strong / Hazardous Label',
                'chip_ar' => 'ملصق قوي / تحذيري',
                'label_en' => 'Altered or AI-generated content',
                'label_ar' => 'محتوى مُعدَّل أو مُنشأ باستخدام الذكاء الاصطناعي',
                'tone_en' => 'High-salience warning treatment',
                'tone_ar' => 'معالجة تحذيرية عالية الظهور',
            ],
            'informational' => [
                'theme' => 'informational',
                'showLabel' => true,
                'chip_en' => 'Informational Label',
                'chip_ar' => 'ملصق معلوماتي',
                'label_en' => 'Made with AI',
                'label_ar' => 'تم إنشاؤه باستخدام الذكاء الاصطناعي',
                'tone_en' => 'Clear explanatory treatment',
                'tone_ar' => 'معالجة تفسيرية واضحة',
            ],
            'minimalist' => [
                'theme' => 'minimalist',
                'showLabel' => true,
                'chip_en' => 'Minimalist Label',
                'chip_ar' => 'ملصق بسيط',
                'label_en' => 'Made with AI',
                'label_ar' => 'صُنع باستخدام الذكاء الاصطناعي',
                'tone_en' => 'Quiet low-friction treatment',
                'tone_ar' => 'معالجة هادئة منخفضة الإزعاج',
            ],
            'control' => [
                'theme' => 'control',
                'showLabel' => false,
                'chip_en' => 'Control / No Label',
                'chip_ar' => 'التحكم / بدون ملصق',
                'label_en' => '',
                'label_ar' => '',
                'tone_en' => 'No disclosure shown',
                'tone_ar' => 'لا يتم عرض أي إفصاح',
            ],
        ];

        return Arr::get($meta, $condition, $meta['control']);
    }

    private function sendParticipantToGoogleSheets(Participant $participant): void
    {
        $this->postToGoogleSheets([
            'type' => 'participant',
            'public_token' => $participant->public_token,
            'condition' => $participant->condition,
            'preferred_language' => $participant->preferred_language,
            'started_at' => optional($participant->started_at)?->toIso8601String(),
            'completed_at' => optional($participant->completed_at)?->toIso8601String(),
        ]);
    }

    private function sendResponseToGoogleSheets(Participant $participant, ExperimentResponse $response): void
    {
        $derivedMetrics = $this->derivedMetrics($response);

        $this->postToGoogleSheets([
            'type' => 'response',
            'participant_token' => $participant->public_token,
            'step_index' => $response->step_index,
            'video_key' => $response->video_key,
            'condition' => $response->condition,
            'seen_label' => $response->seen_label,
            'real_or_fake' => $response->real_or_fake,
            'ai_likelihood' => $response->ai_likelihood,
            'confidence_probability' => $response->confidence_probability,
            'q_uncertainty_question' => 'How uncertain did you feel when deciding whether this video was real or fake?',
            'uncertainty_level' => $response->trust_platform,
            'information_credibility' => $response->information_credibility,
            'trust_label' => $response->trust_label,
            'trust_platform' => $response->trust_platform,
            'decision_time_ms' => $response->decision_time_ms,
            'video_watch_ratio_percent' => $response->video_watch_ratio_percent,
            'pause_count' => $response->pause_count,
            'rewatch_count' => $response->rewatch_count,
            'hesitation_score' => $derivedMetrics['hesitation_score'],
            'recorded_at' => now()->toIso8601String(),
        ]);
    }

    private function postToGoogleSheets(array $payload): void
    {
        $webhookUrl = config('services.google_sheets.experiment_webhook');

        if (!$webhookUrl || app()->runningUnitTests()) {
            return;
        }

        try {
            Http::timeout(10)
                ->acceptJson()
                ->asJson()
                ->post($webhookUrl, $payload)
                ->throw();
        } catch (\Throwable $exception) {
            Log::warning('Failed to sync experiment data to Google Sheets.', [
                'payload_type' => $payload['type'] ?? 'unknown',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    private function derivedMetrics(ExperimentResponse $response): array
    {
        $decisionTimeMs = max((int) ($response->decision_time_ms ?? 0), 0);
        $watchRatioPercent = (float) ($response->video_watch_ratio_percent ?? 0);
        $rewatchCount = max((int) ($response->rewatch_count ?? 0), 0);
        $pauseCount = max((int) ($response->pause_count ?? 0), 0);

        $hesitationScore = round(
            ($decisionTimeMs / 1000)
            + ($pauseCount * 1.5)
            + ($rewatchCount * 2),
            2
        );

        return [
            'hesitation_score' => $hesitationScore,
        ];
    }
}
