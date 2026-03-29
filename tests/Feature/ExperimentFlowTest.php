<?php

namespace Tests\Feature;

use App\Models\Participant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExperimentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_loads(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Start experiment');
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
            'engagement_intent' => 2,
            'clarity' => 5,
            'trust_label' => 4,
            'notes' => 'Looks polished.',
        ])->assertRedirect("/experiment/{$participant->public_token}/2");

        $this->assertDatabaseHas('experiment_responses', [
            'participant_id' => $participant->id,
            'step_index' => 1,
            'condition' => 'informational',
            'seen_label' => 1,
            'believability' => 4,
        ]);
    }
}
