<?php

use App\Models\examquestions;
use App\Models\participant_option_answer;
use App\Models\participants;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $participantid;
    public $participantfullname;
    public $examform;

    // exam content stores in variables
    public $questions = [];

    // properties that help in getting answer for participant/student attempting to take the exam
    public $answer = [];

    // propert below is used for the timer feature
    public $isStarted = false;
    public $totalSeconds = 0;
    public $initialDuration = 0; //store the starting point
    public $timeUsed = 0; // stores the actual time left

    // exam timer is up (no we submit exam answer for the participants forcefully)
    public function timeUp($secondsRemaining = 0)
    {
        // 1. DO THE MATH FIRST
        $this->timeUsed = $this->totalSeconds - $secondsRemaining;

        // 2. NOW SAVE
        $this->save_exam();

        $this->isStarted = false;
    }

    public function displayexamcontent()
    {
        $this->questions = examquestions::with(['options:id,question_id,option_text']) // specifing columns to load (optionid , question_id,option_text)
            ->where('exam_id', $this->examform->id)
            ->get();
    }

    public function mount()
    {
        $this->displayexamcontent();
        if ($this->examform && !empty($this->examform->duration)) {
            // Use the correct property name: examform
            // $parts = explode(':', $this->examform->duration);
            // $minutes = isset($parts[0]) ? (int) $parts[0] : 0;
            // $seconds = isset($parts[1]) ? (int) $parts[1] : 0;
            $hours = (int) $this->examform->duration;

            $this->totalSeconds = $hours * 3600;
            $this->initialDuration = $this->totalSeconds;
            $this->isStarted = true;

            // $this->totalSeconds = $minutes * 60 + $seconds;
            // $this->initialDuration = $this->totalSeconds;
            // $this->isStarted = true;
        }
    }

    // this method inside of it handles the validation that prevents student from clicking exam submit button
    // if they didn't answer all the questions the exam
    public function submitManual($secondsRemaining)
    {
        // prevent participant from submitting exam answers without chosing 0 options
        $totalQuestions = count($this->questions);

        $answeredCount = collect($this->answer)->filter(fn($options) => collect($options)->contains(true))->count();

        if ($answeredCount < $totalQuestions) {
            // This stops the function here and prevents the code below from running
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Please answers all exam questions before clicking submit buttons',
            ]);
            return;
        }
        // calculate how much time left from the timer when student submitted the exam answers
        $this->timeUsed = $this->initialDuration - $secondsRemaining;
        // save student/participant exam answers
        $this->save_exam();
    }

    public function save_exam()
    {
        $result = [];

        // Step 1: Flatten answers
        foreach ($this->answer as $questionId => $options) {
            $result[$questionId] = array_key_first($options);
        }

        DB::transaction(function () use ($result) {
            // 1. Create participant ONCE
            $participant = participants::create([
                'exam_id' => $this->examform->id,
                'participant_id' => $this->participantid,
                'fullname' => $this->participantfullname,
                'time_spent' => $this->timeUsed,
            ]);

            // 2. Save all answers
            foreach ($result as $questionid => $optionid) {
                participant_option_answer::create([
                    'exam_id' => $this->examform->id,
                    'participant_id' => $participant->id, // ✅ use created participant
                    'question_id' => $questionid,
                    'selected_option' => $optionid,
                ]);
            }
        });

        $this->dispatch('exam_submitted');
    }

    public function exitExam()
    {
        $this->dispatch('exam_exited');
    }
};

?>

<div class='container' style='margin-top:4px !important;'>

    <div class="row">
        @if (!$participantid && !$examform)
            <div class="col-lg-8 col-md-8 col-sm-12 mx-auto">
                <div class="card shadow-sm p-2">
                    <div class="card-body">
                        <div class="bg-danger-light p-3">
                            <h5 class='fw-bold'>Sorry Failed To Load Exam Content</h5>
                            <p>Internal error occured</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- second card -->
            <div class="col-lg-6 col-md-6 col-sm-12 mx-auto mb-5" {{-- x-data="{
                secondsLeft: {{ $totalSeconds }}, // Use a static PHP variable to start
                startTimer() {
                    let interval = setInterval(() => {
                        if (this.secondsLeft <= 0) {
                            clearInterval(interval);
                            $wire.timeUp(0);
                        } else {
                            this.secondsLeft--;
                        }
                    }, 1000);
                },
                formatTime() {
                    const mins = Math.floor(this.secondsLeft / 60);
                    const secs = this.secondsLeft % 60;
                    return `${mins}:${secs.toString().padStart(2, '0')}`;
                }
            }" x-init="startTimer()" --}} x-data="{
                secondsLeft: {{ $totalSeconds }},
                interval: null,
            
                startTimer() {
                    // Show full time first, then start countdown after 1 second
                    setTimeout(() => {
                        this.interval = setInterval(() => {
                            if (this.secondsLeft <= 0) {
                                clearInterval(this.interval);
                                this.secondsLeft = 0;
                                $wire.timeUp(0);
                            } else {
                                this.secondsLeft--;
                            }
                        }, 1000);
                    }, 1000);
                },
            
                formatTime() {
                    const hours = Math.floor(this.secondsLeft / 3600);
                    const minutes = Math.floor((this.secondsLeft % 3600) / 60);
                    const seconds = this.secondsLeft % 60;
            
                    return `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                }
            }"
                x-init="startTimer()">
                <div class="card shadow-sm p-2 ">
                    <h4 class="card-header text-capitalize" style='font-size:16px; color:black;'>
                        {{ $examform->title }}

                    </h4>
                    <!-- show exam duration left (timer)-->
                    @if ($isStarted)
                        <div
                            style="
                                position: fixed; 
                                top: 0; 
                                left: 50%; 
                                transform: translateX(-50%); 
                                width: 50%; 
                                z-index: 1050; 
                                background: white; 
                                border: 1px solid #eee; 
                                border-top: none;
                                border-radius: 0 0 15px 15px; 
                                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                            ">
                            <div class="p-2 text-center">
                                <div class="h4 mb-0" :class="secondsLeft < 60 ? 'text-danger' : 'text-primary'"
                                    style="font-weight: bold;">
                                    <i class="fa fa-clock me-2"></i>
                                    <span x-text="formatTime()"></span>
                                </div>
                                <small class="text-muted text-uppercase"
                                    style="font-size: 10px; letter-spacing: 1px;">Time Remaining</small>
                            </div>
                        </div>

                        {{-- <div style="height: 90px; width: 100%;"></div> --}}
                    @endif
                    <!-- timer ends here-->
                    <div class="card-body">

                        <!-- dislaying questions and options -->
                        <form wire:submit.prevent='submitManual(secondsLeft)'>
                            @forelse ($questions as $question)
                                <div class="mb-4"> {{-- space between questions --}}

                                    <h4 class="text-capitalize mb-3" style="font-size:15px; color:black;">
                                        {{ $question->question_text }}
                                    </h4>

                                    @foreach ($question->options as $option)
                                        @foreach ($option->option_text as $optionindex => $optiondata)
                                            <div class="form-check mb-2"> {{-- space between options --}}
                                                <input type="checkbox" class="me-2" {{-- wire:model="answer.{{ $question->id }}"
                        value="{{ $optionindex }}" --}}
                                                    {{-- value="{{ $option->id }}"
                        wire:model="answer.{{ $question->id }}" --}}
                                                    wire:model='answer.{{ $question->id }}.{{ $optionindex }}'>
                                                <label class="form-check-label text-capitalize">
                                                    {{ $optiondata }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            @empty
                                <p>Exam current has no questions, check back later.</p>
                            @endforelse
                            <div>
                                <button type="button"
                                    class='btn btn-secondary btn-sm shadow-0 text-capitalize fw-bold mt-3'
                                    style='font-size:14px;' wire:click='exitExam'>Exit Exam</button>
                                <!-- show exam submit if exam has already questions -->
                                @if (count($questions) > 0)
                                    <button type="submit"
                                        class='btn btn-primary btn-sm shadow-0 text-capitalize fw-bold mt-3'
                                        style='font-size:14px;'>Submit Exam</button>
                                @endif
                            </div>
                        </form>
                        <!-- dislaying questions and options ends here -->
                    </div>
                </div>
            </div>
            <!-- second card ends-->
        @endif
    </div>
</div>
