<?php

use App\Models\examquestionoptions;
use App\Models\examquestions;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public $examid;
    public $showmodal = false;
    
    public $question = '';
    public $questionmarks;
    public $options = [];
    // reset form input field data and make them empty
    public function resetForm()
    {
    $this->reset(['question', 'questionmarks']);

    // 🔴 MUST reinitialize options AFTER reset
    $this->options = [
        ['text' => '', 'is_correct' => false],
        ['text' => '', 'is_correct' => false],
        ['text' => '', 'is_correct' => false],
    ];
    }

    protected $rules = [
        'question' => 'required|string',
        'options.*.text' => 'required|string',
        'questionmarks' => 'required|numeric',
    ];

    protected $messages = [
        'question.required' => 'Please enter the question text.',
        'options.*.text.required' => 'Each option must have text.',
        'questionmarks.required' => 'Qeustion must have marks',
        'questionmarks.numeric' => 'Qeustion marks should be a number',
    ];

    public function openModal(){
        $this->resetErrorBag();
        $this->resetForm();
        $this->showmodal = true;
        }
        
        public function closeModal()
        {
            $this->showmodal = false;
              // reset the form input field content
            $this->resetForm();
            }
            
            
            
        public function mount()
        {
        
        $this->options = [
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
        ];
    }

    public function savequestion()
    {
        $this->resetErrorBag();
        $this->validate();


        $correctIndexes = [];
        foreach ($this->options as $index => $option) {
            if (!empty($option['is_correct'])) {
                $correctIndexes[] = $index;
            }
        }

        if (count($correctIndexes) !== 1) {
            $this->addError('options.is_correct_option', 'This is a single-choice question. Please mark exactly one correct answer.');
            return;
        }

        $this->resetErrorBag();
        $correctIndex = $correctIndexes[0];
        
        // save question
        $question = examquestions::create([
            'exam_id'=>$this->examid,
            'question_text'=>$this->question,
            'marks'=>$this->questionmarks,
            'user_id'=>Auth::user()->id
        ]);

        // store question option
        examquestionoptions::create([
            'question_id'=>$question->id,
            'exam_id'=>$this->examid,
            'option_text'=>array_column($this->options, 'text'),
            'correct_option'=>$correctIndex,
            'user_id'=>Auth::user()->id
        ]);

        //  success message 
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Question Data Saved Successfully!'
        ]);

        // reset the form input field content
         $this->resetForm();
         // close the modal
         $this->closeModal();

         // create listener to notify sperate  display livewire components
            $this->dispatch('newquestion-create');

        
    }


};


?>

<div>

<!-- Question Modal starts here -->
 <div class="container px-4">
    <button  
    wire:click='openModal' wire:loading.attr="disabled"  class="btn btn-primary shadow-0 fw-bold mb-3">
   Create Single Choice Question
</button>
 </div>

@if ($showmodal)
<div class="modal  d-block" style="background: rgba(0,0,0,0.5);">
  <div class="modal-dialog modal-lg"> 
    <div class="modal-content" >
      
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style='font-size:16px !important; color:black;'>Create Single Choice Question</h5>
      </div>

      <div class="modal-body">
        <form  wire:submit.prevent='savequestion'>
            
    {{-- QUESTION --}}
    <div class="mb-4">
        <label class="form-label fw-bold" style='font-size:14px; color:black;'>Question</label>
        <textarea
            class="form-control"
            rows="3"
            wire:model="question"
            placeholder="Enter your question..."></textarea>
        @error('question')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    {{-- Quesnt Marks --}}
     <div class="mb-4">
        <label class="form-label fw-bold" style='font-size:14px; color:black;'>Question Marks</label>
        <input type="text" class='form-control' placeholder="Enter your question marks..." wire:model="questionmarks">
        @error('questionmarks')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
  

      {{-- OPTIONS --}}
    <div class="mb-3">
        <label class="form-label fw-bold" style='font-size:14px; color:black;'>Options</label>

      @foreach ($options as $index => $option)
    
    <div class="d-flex align-items-center gap-2 mb-2">


        <input type="text"
               class="form-control"
               wire:model.defer="options.{{ $index }}.text"
               placeholder="Option">

        <div class="form-check">
            <input class="form-check-input"
                   type="checkbox"
                   wire:model="options.{{ $index }}.is_correct">
        </div>
    </div>
    @error('options.'.$index.'.text')
        <div class="text-danger small mb-2">{{ $message }}</div>
    @enderror
@endforeach
    @error('options.is_correct_option')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
       
</div>

<div class="modal-footer">
    <button type="button" wire:loading.attr="disabled"  class="btn btn-secondary shadow-0 fw-bold text-capitalize" style='font-size:12px;' wire:click='closeModal'>Close</button>
    <button type="submit" 
    wire:loading.attr="disabled"   {{--  this prevents double saving content in db when button clicked twice--}}
     class="btn btn-primary shadow-0 text-capitalize fw-bold" style='font-size:12px;'>Save Question</button>
</div>
</form>

    </div>
  </div>
</div>

@endif
<!-- Question Modal ends here -->
</div>