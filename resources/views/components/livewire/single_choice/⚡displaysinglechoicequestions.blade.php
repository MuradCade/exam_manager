<?php

use App\Models\examform;
use App\Models\examquestionoptions;
use App\Models\examquestions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $examid;
    public $error = null;
    public $exam;
    public $questions = [];
    public $options = [];
    // public $edit_enabled = false;
    public $editingQuestionId = null;

    // variables of the input fields
    public $question = '';
    public $questionmarks;
    public $questionoptions = [];
   

    protected $rules = [
        'question' => 'required|string',
        'questionoptions.*.text' => 'required|string',
        'questionmarks' => 'required|numeric',
    ];

    protected $messages = [
        'question.required' => 'Please enter the question text.',
        'questionoptions.*.text.required' => 'Each option must have text.',
        'questionmarks.required' => 'Qeustion must have marks',
        'questionmarks.numeric' => 'Qeustion marks should be a number',
    ];
    // listen for the dispatch that comes to notify new question is created
    #[On('newquestion-create')]
    public function refreshData(){
        try {
            // 1. fetch all exam form data
            $exam = examform::findOrFail($this->examid);
            //2. check if the current authenticated user owns the exam form
            if ($exam->user_id != Auth::id()) {
                $this->error = 'Unauthorized';
                return;
            }
            // 3. fetch already exists question on exam_questions table
            $this->questions = examquestions::where('exam_id', $this->examid)->get();

            // get all questions id
            $questionIds = $this->questions->pluck('id');
            $this->options = examquestionoptions::whereIn('question_id', $questionIds)->get();
        } catch (ModelNotFoundException $e) {
            $this->error = 'Exam not found';
        }
    }
    public function mount()
    {
        $this->refreshData();
    }

    public function enableEdit($id)
    {
        $this->editingQuestionId = $id;

        // this data below is need for displaying  data inside input element of the currently edited question
        $question = examquestions::findOrFail($id);

        $this->question = $question->question_text;
        $this->questionmarks = (int) $question->marks;

        $optionRow = examquestionoptions::where('question_id', $id)->first();

        $this->questionoptions = collect($optionRow->option_text ?? [])
            ->map(function ($text, $index) use ($optionRow) {
                return [
                    'text' => $text,
                    'is_correct' => (int) $optionRow->correct_option === $index,
                ];
            })
            ->toArray();
    }

    public function disableEditing(){
         $this->editingQuestionId = null;
         //reset the values of the input fields
        $this->reset(['question','questionmarks','questionoptions']);
    }
    public function update()
    {
        $this->validate();

        // find the correct index
        $correctIndexes = [];
        foreach ($this->questionoptions as $index => $optiondata) {
            if (!empty($optiondata['is_correct'])) {
                $correctIndexes[] = $index;
            }
        }

        // validate if the user choice more then one answer as correct
        if (count($correctIndexes) !== 1) {
            $this->addError('questionoptions.is_correct_option', 'This is a single-choice question. Please mark exactly one correct answer.');
            return;
        }
        $correctIndex = $correctIndexes[0];
        $this->resetErrorBag();

        // update the question
        DB::transaction(function() use($correctIndex){
            // 1. get exam question data
            $question = examquestions::findOrFail($this->editingQuestionId);
            // 2. update exam questions
            $question->update([
                'question_text'=>$this->question,
                'marks'=>$this->questionmarks
            ]);

            //  3. update question options
            examquestionoptions::where('question_id',$this->editingQuestionId)
            ->update([
            'option_text'=>array_column($this->questionoptions, 'text'),
            'correct_option'=>$correctIndex,
            ]);



        });

        //reset the values of the input fields
        $this->reset(['question','questionmarks','questionoptions']);
        // hide editing input fields
        $this->editingQuestionId = null;
        // refresh data in the ui
        $this->refreshData();
        // display success feedback
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Question Data Updated Successfully!'
        ]);

        
    }

    public function delete($id){
        $questionid = $id;
        DB::transaction(function() use ($questionid){
            // 1. find question data by fetching question table with specified question id 
            $questions = examquestions::findOrFail($questionid);
            //  2. delete fetched question data
            $questions->delete();
        });

        // refresh data in the ui
        $this->refreshData();
        // display success feedback
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Question Data Deleted Successfully!'
        ]);
    }
};
?>

<div class='container'>
    @if ($error)
        <p class='bg-danger-light p-2'>
            {{ $error }}
        </p>
    @else
        {{-- <form  wire:submit.prevent='update'> --}}
        <div class="row mt-4">
            <form wire:submit.prevent='update'>
                @forelse ( $questions as $questiondata )
                    <div class="col-lg-8 col-md-8 col-sm-12 p-2">
                        <div class="card shadow-sm border border-sm">

                            <div class="mb-4 p-2">
                                <!-- questions starts here -->
                                <div class="d-flex justify-content-between align-items-start p-2 border-bottom">
                                    <!-- LEFT: Question -->
                                    <div class="me-3">
                                        <p class="mb-1 fw-semibold text-capitalize" style="font-size:16px; color:black;">
                                            @if ($editingQuestionId === $questiondata->id)
                                                <label class="form-label fw-bold"
                                                    style='font-size:14px; color:black;'>Question</label>
                                                <textarea class="form-control" rows="3" wire:model="question" placeholder="Enter your question...">{{ $questiondata['question_text'] }}</textarea>
                                                @error('question')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            @else
                                                {{ $questiondata['question_text'] }}
                                            @endif
                                        </p>
                                        <small class="text-muted">
                                            @if ($editingQuestionId === $questiondata->id)
                                                <label class="form-label fw-bold"
                                                    style='font-size:14px; color:black;'>Question Marks</label>
                                                <input type="text" class='form-control'
                                                    placeholder="Enter your question marks..."
                                                    wire:model='questionmarks'
                                                    value="{{ (int) $questiondata['marks'] }}">
                                                @error('questionmarks')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            @else
                                                Marks: {{ (int) $questiondata['marks'] }}
                                            @endif
                                        </small>
                                    </div>

                                    <!-- RIGHT: Actions -->
                                    <div class="d-flex gap-2">


                                        {{-- save updated exam questions and options --}}
                                        @if ($editingQuestionId === $questiondata->id)
                                        <!-- UNDO EDITING BUTTON-->
                                        <button type='button' wire:loading.attr="disabled"  wire:click='disableEditing'
                                            class="btn btn-secondary btn-sm shadow-0 text-capitalize fw-bold">
                                            Undo
                                        </button>
                                        
                                        <!-- UDPATE BUTTON-->
                                            <button type='button' wire:loading.attr="disabled"  wire:click='update'
                                                class="btn btn-primary btn-sm shadow-0 text-capitalize fw-bold">
                                                Update
                                            </button>
                                        @else
                                            <!-- EDIT BUTTON -->
                                            <button type='button' wire:click='enableEdit({{ $questiondata->id }})'
                                                class="btn btn-outline-primary btn-sm shadow-0">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            
                                            <!-- DELETE BUTTON-->
                                            <button type='button' wire:loading.attr="disabled" wire:click='delete({{ $questiondata->id }})' class="btn btn-outline-danger btn-sm shadow-0">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>

                                </div>
                                <!-- questions ends here -->


                                <div class="mt-2">
                                    @foreach ($options->where('question_id', $questiondata->id) as $optiondata)
                                        <ul class='d-flex flex-column  gap-2'>
                                            @foreach ($optiondata->option_text as $index => $text)
                                                @if ($editingQuestionId === $questiondata->id)
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <input type="text" class="form-control"
                                                            value='{{ $text }}'
                                                            wire:model.defer="questionoptions.{{ $index }}.text"
                                                            placeholder="Option">

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                {{ $index == $optiondata->correct_option ? 'checked' : '' }}
                                                                wire:model="questionoptions.{{ $index }}.is_correct">
                                                        </div>
                                                    </div>
                                                    @error('questionoptions.'.$index .'.text')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                    @error('questionoptions.is_correct_option')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                @else
                                                    <li style='color:black;'>
                                                        @if ($index == $optiondata->correct_option)
                                                            <span
                                                                class='me-2 d-flex align-items-center text-capitalize'>{{ $text }}
                                                                <i class='fa fa-check px-4 text-success'></i></span>
                                                        @else
                                                            <span class=' text-capitalize'>{{ $text }}</span>
                                                        @endif
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endforeach
                                </div>
                                <!-- end options-->
                            </div>
                            <!-- questions ends here-->
                        </div>
                    </div>
                  @empty
                    <div class="container px-3">
                        <p class='small text-black'>There are no questions to be shown , please create new questions.</p>
                    </div>
                @endforelse
            </form>
        </div>
    @endif
</div>
