<?php

namespace Tests\Unit\Models;

use App\Models\examform;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class ExamFormTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_exam_belongs_to_its_creator(): void
    {
        // create new user
        $user = User::factory()->create(['role' => 'owner']);
        // create new exam
        $exam = examform::factory()->create(['user_id' => $user->id]);
        // $this->assertTrue($exam->user->is($user));
        $this->assertEquals($user->id, $exam->user_id);
    }
}
