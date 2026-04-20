<?php

use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $examform;
    public $participantId = null;
    public $participantfullname = null;
    public $showExam = false;
    public $examMessage = []; // holds exam flash messages 

    #[On('student_is_allowed')]
    public function startExam($participantid,$participantfullname)
    {
        $this->participantId = $participantid;
        $this->participantfullname = $participantfullname;
        $this->showExam = true;
    }

   
    #[On('exam_submitted')]
    public function endExam(){
           $this->showExam = false;
           $this->examMessage = ['success'=>"Exam answers submitted successfully."];
    }


     #[On('exam_exited')]
    public function exitExam(){
           $this->showExam = false;
           $this->examMessage = ['error'=>"You left the exam. You can retake it later."];
    }


};
?>

<div>
  @if(!$showExam)
        @livewire('livewire.exam_frontend.studentform', ['examform' => $examform,'examMessage'=>$examMessage])
    @else
        @livewire('livewire.exam_frontend.exam-attempt', [
            'examform' => $examform,
            'participantid' => $participantId,
            'participantfullname'=>$participantfullname
        ])
    @endif
</div>