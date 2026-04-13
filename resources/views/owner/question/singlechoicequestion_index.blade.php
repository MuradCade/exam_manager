<x-ownerlayout>
        <x-slot name="title">Exam Manager | Create Exam Questions</x-slot>
   <x-ownersidebar />
    <x-decomentcontentarea>
        

        <div class="container">
           <div class="d-flex align-items-center justify-content-evently mt-2 mb-4 border-bottom">
               <a href="{{ route('exam') }}" 
               class=" mb-3">
                   <i class="fas fa-arrow-left me-3"></i>
               </a>
            <h4 style="font-size:16px;color:black;" class="mb-3 fw-bold">Exam Question List</h4>
        </div>

        <div class="row">
           
        </div>
        </div>

        @livewire('livewire.singlechoice-questionmodal', ['examid' => $exam_id])
        @livewire('livewire.displaysinglechoicequestions', ['examid' => $exam_id])
    </x-decomentcontentarea>

    
</x-ownerlayout>



