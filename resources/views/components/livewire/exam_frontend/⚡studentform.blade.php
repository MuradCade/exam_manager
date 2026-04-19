<?php

use App\Models\examexclusion;
use App\Models\participants;
use Livewire\Component;

new class extends Component
{
    // examform data
    public $examform;
    // input variables
    public $studentid;
    public $studentfullname;
    // exam flash message property
    public $examMessage;

     protected $rules = [
        'studentid' => 'required|numeric',
        'studentfullname' => 'required|string',
    ];

    protected $messages = [
        'studentid.required' => 'Please enter student id.',
        'studentid.numeric' => 'Student id should be a number',
        'studentfullname.required' => 'Please enter student fullname',
        'studentfullname.string' => 'Please enter proper student fullname',
    ];
    public function eaxmInterance(){
        $this->resetErrorBag();
        $this->validate();

        $isExcluded = false;
        // check if the student id is excluded from the exam
        $examexclusion = examexclusion::where('exam_id',$this->examform->id)->first();
        if ($examexclusion) {
            $participants = $examexclusion->participant_id;

            $isExcluded = in_array($this->studentid, $participants);
            
        }

        if($isExcluded){
            $this->resetErrorBag();
            $this->addError('studentid','You are not allowed to take this exam.');
            
        }else{
            // check if student/participant already submitted exam
            $participants_alreadysubmitted = participants::where('participant_id',$this->studentid)->first();

            if($participants_alreadysubmitted){
                $this->resetErrorBag();
                $this->addError('studentid',"You cannot retake the exam because you have already submitted it.");
            }else{

                $this->dispatch('student_is_allowed',participantid:$this->studentid,participantfullname:$this->studentfullname);
            }
           // store the participant data
            // $participantdata = participants::create([
            //     'exam_id' => $this->examform->id,
            //     'participant_id' => $this->studentid,
            //     'fullname' => $this->studentfullname
            // ]); 
            // $this->dispatch('student_is_allowed', participantid: $participantdata->id)->toParent();
        }
            
        
    }
};
?>

<div class="container">
     <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 mx-auto">
               @if($examform != null)

                @if ($examform->status == 'active')
                  <div class="card shadow-sm p-2">
                    <!-- exam success flash message -->
                    @if(!empty($examMessage['error']))
                        <p 
                         x-data="{ show: true }"
                        x-init="
                            setTimeout(() => {
                                show = false;
                                $wire.examMessage = null;
                            }, 3000)
                        "
                        x-show="show"
                        class='bg-info-light p-2 mt-1 mb-1'
                        >{{$examMessage['error']}}</p>

                        @elseif (!empty($examMessage['success']))
                         <p 
                         x-data="{ show: true }"
                        x-init="
                            setTimeout(() => {
                                show = false;
                                $wire.examMessage = null;
                            }, 3000)
                        "
                        x-show="show"
                        class='bg-success-light p-2 mt-1 mb-1'
                        >{{$examMessage['success']}}</p>
                    @endif
                    <!-- exam success flash message-->
                    <p ></p>
                    <h4 class="card-header text-capitalize" style='font-size:17px; color:black;'>
                        {{ $examform->title }}
                    </h4>
                    <!-- description starts here-->
                    <div class='px-4 mt-3'>
                    @if ($examform->description != null)
                    <p style='color:#2b2a2a;font-size:15px;'>{{$examform->description}}</p>                    
                    @endif
                    </div>
                    <!-- description ends here-->
                   <div class="card-body" style='padding-top:6px !important;'>
                    <form wire:submit.prevent='eaxmInterance'>
                     <div class="form-group">
                        <label  class="form-label" style='font-size:15px; color:black;'>Student ID</label>
                        <input type="text" class='form-control' placeholder='Enter Student ID' style='font-size:15px;' wire:model='studentid'>
                        @error('studentid')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label  class="form-label" style='font-size:15px; color:black;'>Student Fullname</label>
                        <input type="text" class='form-control' placeholder='Enter Student Fullname' style='font-size:15px;' wire:model='studentfullname'>
                    @error('studentfullname')
                            <span class="text-danger small">{{ $message }}</span>
                    @enderror
                    </div>

                    <button class='btn btn-primary btn-sm shadow-0 text-capitalize fw-bold mt-3' style='font-size:14px;'>
                        <span wire:loading class='spinner-border spinner-border-sm text-white me-2'></span>
                        Submit
                    </button>
                    </form>
                    </div>
                </div>
                     <p class='mt-5 mb-2 text-black text-center'>This form is powered by <a href='{{ route('home') }}' class='text-decoration-underline fw-bold'>Exam Manager</a></p>
                @elseif ($examform->status == 'disabled')
                <!-- exam disabled feedback -->
                <div class="card shadow-sm p-2">
                    <div class="card-body">
                        <div class="bg-danger-light p-3">
                            <h5 class='fw-bold'>Exam Currently Not Available</h5>
                            <p>This exam form doesn't accept any submission at the moment</p>
                        </div>
                    </div>
                </div>
                <p class='mt-5 text-black text-center'>This form is powered by <a href='{{ route('home') }}' class='text-decoration-underline fw-bold'>Exam Manager</a></p>
                @endif

                @else
                <!-- exam not found feedback-->
                <div class="card shadow-sm p-2">
                    <div class="card-body">
                        <div class="bg-danger-light p-3">
                            <h5 class='fw-bold'>Exam not found</h5>
                            <p>The exam link may be invalid or expired.</p>
                        </div>
                    </div>
                </div>
                     <p class='mt-5 text-black text-center'>This form is powered by <a href='{{ route('home') }}' class='text-decoration-underline fw-bold'>Exam Manager</a></p>
                @endif
                
              
            </div>
        </div>
</div>