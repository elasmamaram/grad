<?php

namespace Tests\Feature;

use App\Models\Participant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExperimentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_loads(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Start the Experiment');
    }

    public function test_new_participants_cycle_through_the_four_conditions(): void
    {
        $this->post('/start')->assertRedirect();
        $this->assertDatabaseHas('participants', ['condition' => 'strong']);

        $this->post('/start')->assertRedirect();
        $this->assertDatabaseHas('participants', ['condition' => 'informational']);

        $this->post('/start')->assertRedirect();
        $this->assertDatabaseHas('participants', ['condition' => 'minimalist']);

        $this->post('/start')->assertRedirect();
        $this->assertDatabaseHas('participants', ['condition' => 'control']);
    }

    public function test_participant_can_submit_a_video_response(): void
    {
        $participant = Participant::create([
            'public_token' => 'test-token',
            'condition' => 'informational',
            'preferred_language' => 'bilingual',
        ]);

        $this->post("/experiment/{$participant->public_token}/1", [
            'believability' => 4,
            'trust_platform' => 2,
            'information_credibility' => 5,
            'trust_label' => 4,
            'notes' => 'Looks polished.',
        ])->assertRedirect("/experiment/{$participant->public_token}/2");

        $this->assertDatabaseHas('experiment_responses', [
            'participant_id' => $participant->id,
            'step_index' => 1,
            'condition' => 'informational',
            'seen_label' => 1,
            'actual_source' => 'fake',
            'answer_is_correct' => 0,
            'ai_likelihood' => 4,
            'information_credibility' => 5,
        ]);
    }

    public function test_start_redirects_to_the_first_experiment_step(): void
    {
        $response = $this->post('/start', [
            'consent' => 'yes',
            'preferred_language' => 'bilingual',
            'age_18' => 'yes',
            'reside_libya' => 'yes',
            'internet_regular' => 'yes',
            'heard_deepfake' => 'yes',
            'age_group' => '25-34',
        ]);

        $participant = Participant::latest('id')->first();

        $response->assertRedirect("/experiment/{$participant->public_token}/1");
        $this->assertNotNull($participant?->started_at);
        $this->assertSame('yes', $participant?->consent_answer);
        $this->assertSame('yes', $participant?->age_18);
        $this->assertSame('yes', $participant?->reside_libya);
        $this->assertSame('yes', $participant?->internet_regular);
        $this->assertSame('yes', $participant?->heard_deepfake);
        $this->assertSame('25-34', $participant?->age_group);
    }

    public function test_informational_condition_requires_all_visible_experiment_fields(): void
    {
        $participant = Participant::create([
            'public_token' => 'validation-token',
            'condition' => 'informational',
            'preferred_language' => 'bilingual',
        ]);

        $this->from("/experiment/{$participant->public_token}/1")
            ->post("/experiment/{$participant->public_token}/1", [
                'real_or_fake' => 'real',
                'ai_likelihood' => 4,
                'confidence_probability' => 75,
                'trust_platform' => 3,
            ])
            ->assertRedirect("/experiment/{$participant->public_token}/1")
            ->assertSessionHasErrors(['trust_label', 'information_credibility']);
    }

    public function test_control_condition_allows_submission_without_label_specific_fields(): void
    {
        $participant = Participant::create([
            'public_token' => 'control-token',
            'condition' => 'control',
            'preferred_language' => 'bilingual',
        ]);

        $this->post("/experiment/{$participant->public_token}/1", [
            'real_or_fake' => 'fake',
            'ai_likelihood' => 2,
            'confidence_probability' => 60,
            'trust_platform' => 4,
            'notes' => 'No label was shown.',
        ])->assertRedirect("/experiment/{$participant->public_token}/2");

        $this->assertDatabaseHas('experiment_responses', [
            'participant_id' => $participant->id,
            'step_index' => 1,
            'condition' => 'control',
            'seen_label' => 0,
            'actual_source' => 'fake',
            'answer_is_correct' => 1,
            'trust_label' => null,
            'information_credibility' => null,
        ]);
    }

    public function test_show_skips_steps_that_already_have_a_saved_response(): void
    {
        $participant = Participant::create([
            'public_token' => 'skip-token',
            'condition' => 'informational',
            'preferred_language' => 'bilingual',
        ]);

        $this->post("/experiment/{$participant->public_token}/1", [
            'real_or_fake' => 'real',
            'ai_likelihood' => 5,
            'confidence_probability' => 80,
            'trust_platform' => 2,
            'trust_label' => 4,
            'information_credibility' => 5,
        ]);

        $this->get("/experiment/{$participant->public_token}/1")
            ->assertRedirect("/experiment/{$participant->public_token}/2");
    }

    public function test_final_step_submission_marks_the_participant_complete(): void
    {
        $participant = Participant::create([
            'public_token' => 'complete-token',
            'condition' => 'control',
            'preferred_language' => 'bilingual',
        ]);

        foreach ([1, 2, 3] as $step) {
            $this->post("/experiment/{$participant->public_token}/{$step}", [
                'real_or_fake' => 'real',
                'ai_likelihood' => 3,
                'confidence_probability' => 70,
                'trust_platform' => 4,
            ])->assertRedirect("/experiment/{$participant->public_token}/" . ($step + 1));
        }

        $this->post("/experiment/{$participant->public_token}/4", [
            'real_or_fake' => 'fake',
            'ai_likelihood' => 4,
            'confidence_probability' => 85,
            'trust_platform' => 5,
        ])->assertRedirect("/complete/{$participant->public_token}");

        $participant->refresh();

        $this->assertNotNull($participant->completed_at);
        $this->assertDatabaseCount('experiment_responses', 4);
    }

    public function test_google_sheets_receives_all_expected_participant_and_response_fields(): void
    {
        Config::set('services.google_sheets.experiment_webhook', 'https://example.test/google-sheets');
        Config::set('services.google_sheets.allow_during_tests', true);
        Http::fake();

        $startResponse = $this->post('/start', [
            'consent' => 'yes',
            'preferred_language' => 'english',
            'age_18' => 'yes',
            'reside_libya' => 'yes',
            'internet_regular' => 'yes',
            'heard_deepfake' => 'no',
            'age_group' => '25-34',
        ]);

        $participant = Participant::latest('id')->firstOrFail();

        $startResponse->assertRedirect("/experiment/{$participant->public_token}/1");

        Http::assertSent(function ($request) use ($participant) {
            $data = $request->data();
            $expectedKeys = [
                'type',
                'public_token',
                'condition',
                'preferred_language',
                'consent_answer',
                'age_18',
                'reside_libya',
                'internet_regular',
                'heard_deepfake',
                'age_group',
                'started_at',
                'completed_at',
            ];

            return $request->url() === 'https://example.test/google-sheets'
                && $data['type'] === 'participant'
                && array_keys($data) === $expectedKeys
                && $data['public_token'] === $participant->public_token
                && $data['preferred_language'] === 'en'
                && $data['completed_at'] === null;
        });

        Http::fake();

        $this->post("/experiment/{$participant->public_token}/1", [
            'real_or_fake' => 'fake',
            'ai_likelihood' => 5,
            'confidence_probability' => 60,
            'trust_platform' => 4,
            'trust_label' => 5,
            'information_credibility' => 4,
            'decision_time_ms' => 7000,
            'video_watch_ratio_percent' => 86,
            'pause_count' => 4,
            'rewatch_count' => 1,
            'notes' => 'Looks suspicious.',
        ])->assertRedirect("/experiment/{$participant->public_token}/2");

        Http::assertSent(function ($request) use ($participant) {
            $data = $request->data();
            $expectedKeys = [
                'type',
                'participant_token',
                'step_index',
                'video_key',
                'actual_source',
                'condition',
                'seen_label',
                'real_or_fake',
                'answer_is_correct',
                'ai_likelihood',
                'confidence_probability',
                'q_uncertainty_question',
                'uncertainty_level',
                'information_credibility',
                'trust_label',
                'trust_platform',
                'notes',
                'decision_time_ms',
                'video_watch_ratio_percent',
                'pause_count',
                'rewatch_count',
                'hesitation_score',
                'recorded_at',
            ];

            return $request->url() === 'https://example.test/google-sheets'
                && $data['type'] === 'response'
                && array_keys($data) === $expectedKeys
                && $data['participant_token'] === $participant->public_token
                && $data['actual_source'] === 'AI'
                && $data['q_uncertainty_question'] === 'How uncertain are you?'
                && $data['hesitation_score'] === 15.0;
        });
    }

    public function test_completed_participant_is_resent_to_google_sheets_with_completed_at(): void
    {
        Config::set('services.google_sheets.experiment_webhook', 'https://example.test/google-sheets');
        Config::set('services.google_sheets.allow_during_tests', true);
        Http::fake();

        $participant = Participant::create([
            'public_token' => 'sheet-complete-token',
            'condition' => 'control',
            'preferred_language' => 'bilingual',
            'consent_answer' => 'yes',
            'age_18' => 'yes',
            'reside_libya' => 'yes',
            'internet_regular' => 'yes',
            'heard_deepfake' => 'yes',
            'age_group' => '25-34',
            'started_at' => now(),
        ]);

        foreach ([1, 2, 3] as $step) {
            $this->post("/experiment/{$participant->public_token}/{$step}", [
                'real_or_fake' => 'real',
                'ai_likelihood' => 3,
                'confidence_probability' => 70,
                'trust_platform' => 4,
            ]);
        }

        $this->post("/experiment/{$participant->public_token}/4", [
            'real_or_fake' => 'fake',
            'ai_likelihood' => 4,
            'confidence_probability' => 85,
            'trust_platform' => 5,
        ])->assertRedirect("/complete/{$participant->public_token}");

        Http::assertSent(function ($request) use ($participant) {
            $data = $request->data();

            return $request->url() === 'https://example.test/google-sheets'
                && $data['type'] === 'participant'
                && $data['public_token'] === $participant->public_token
                && !empty($data['completed_at']);
        });
    }
}
