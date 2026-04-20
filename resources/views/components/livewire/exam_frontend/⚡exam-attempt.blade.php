<?php

use App\Models\examquestions;
use App\Models\participant_option_answer;
use App\Models\participants;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $participantid;
    public $participantfullname;
    public $examform;
    
    // exam content stores in variables
    public $questions = [];
    
    // properties that help in getting answer for participant/student attempting to take the exam
    public $answer = [];
    
    public function displayexamcontent(){
      $this->questions = examquestions::with(['options:id,question_id,option_text']) // specifing columns to load (optionid , question_id,option_text)
        ->where('exam_id', $this->examform->id)
        ->get();
    }

    public function mount(){
        $this->displayexamcontent();
    }
   
    // public function save_exam(){
    //     $result = [];

    //         // Step 1: Flatten answers (question_id => selected option index)
    //         foreach ($this->answer as $questionId => $options) {
    //             $result[$questionId] = array_key_first($options);
    //         }

    //         // // Step 2: Separate arrays
    //         // $questionIds = array_keys($result);
    //         // $optionIndexes = array_values($result);

    //         // dd($result);
    //         foreach($result as $questionid => $optionid){

    //             // dump($this->participantid,$this->participantfullname,[
    //             //     "questionid"=>$questionid,
    //             //     "optionid"=> $optionid
    //             // ]);
    //             DB::transaction(function() use($examform,$participantid, $participantfullname,$questionid,$optionid){

    //                 // 1. store participant information
    //                 $participant = participants::create([
    //                     "exam_id"=> $this->examform->id,
    //                     "participant_id"=>$this->participantid,
    //                     "fullname" => $this->participantfullname,
    //                 ]);

    //                 // 2. store participient exam option answer
    //                 participant_option_answer::create([
    //                     "exam_id"=>$this->examform->id,
    //                     "participant_id"=>$participantid,
    //                     "question_id" => $questionid,
    //                     "selected_option"=>$optionid
    //                 ]);
    //             });
    //         }
           
    // }


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
            "exam_id" => $this->examform->id,
            "participant_id" => $this->participantid,
            "fullname" => $this->participantfullname,
        ]);

        // 2. Save all answers
        foreach ($result as $questionid => $optionid) {
            participant_option_answer::create([
                "exam_id" => $this->examform->id,
                "participant_id" => $participant->id, // ✅ use created participant
                "question_id" => $questionid,
                "selected_option" => $optionid
            ]);
        }
    });

    $this->dispatch('exam_submitted');
    }

    
    public function exitExam(){
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
                <div class="col-lg-6 col-md-6 col-sm-12 mx-auto mb-5">
                <div class="card shadow-sm p-2 ">
                    <h4 class="card-header text-capitalize" style='font-size:16px; color:black;'>
                        {{ $examform->title }}
                    </h4>
                    <div class="card-body">

                   <!-- dislaying questions and options -->
                   <form wire:submit.prevent='save_exam'>
                   @forelse ($questions as $question)

                    <div class="mb-4"> {{-- space between questions --}}
                    
                    <h4 class="text-capitalize mb-3" style="font-size:15px; color:black;">
                        {{ $question->question_text }}
                    </h4>

                @foreach ($question->options as $option)
                
               @foreach ($option->option_text as $optionindex => $optiondata)
               <div class="form-check mb-2"> {{-- space between options --}}
                    <input
                        type="checkbox"
                        class="me-2"
                        {{-- wire:model="answer.{{ $question->id }}"
                        value="{{ $optionindex }}" --}}
                        {{-- value="{{ $option->id }}"
                        wire:model="answer.{{ $question->id }}" --}}
                       wire:model='answer.{{ $question->id }}.{{ $optionindex }}'
                        >
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
                @if($questions != null)
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