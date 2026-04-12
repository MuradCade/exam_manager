<x-ownerlayout>
        <x-slot name="title">Exam Manager | Upate Exam Information</x-slot>
   <x-ownersidebar />
    <x-decomentcontentarea>
        

        <div class="container">
           <div class="d-flex align-items-center justify-content-evently mt-2 mb-4 border-bottom">
               <a href="{{ route('exam') }}" 
               class=" mb-3">
                   <i class="fas fa-arrow-left me-3"></i>
               </a>
            <h4 style="font-size:16px;color:black;" class="mb-3 fw-bold">Edit Exam Information</h4>
        </div>

        <!-- exam creation form starts here-->
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @if (session()->has('exam_form_updated'))
                        <p class='bg-success-light p-2'>{{session('exam_form_updated')}}</p>                            
                        @endif
                <form action='{{ route('exam.edit.update',['exam_id'=>$examform->id]) }}' method='POST'>
                    @csrf
                    <div class="form-group">
                        <label class="form-label" style='font-size:14px;color:black;'>Exam Name</label>
                        <input type="text" class='form-control' placeholder="Enter Exam Name" style='font-size:14px'
                        name='exam_name' value='{{ $examform->title }}'>
                         @error('exam_name')
                        <p class='text-danger '  style='font-size:14px;'>{{$message}}</p>
                         @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label" style='font-size:14px;color:black;'>Exam Description (Instructions)</label>
                        <textarea rows='6' columns='6' class='form-control' placeholder="Enter Exam Description" style='font-size:14px'
                        name='exam_description'>{{$examform->description}}</textarea>
                         @error('exam_description')
                        <p class='text-danger '  style='font-size:14px;'>{{$message}}</p>
                         @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label" style='font-size:14px;color:black;'>Exam Status</label>
                        <select class='form-select' style='font-size:14px;color:black;' name='exam_status'>
                            <option value="active" {{ $examform->status == 'active'? 'selected':'' }}>Active</option>
                            <option value="disabled" {{ $examform->status == 'disabled'? 'selected':'' }}>Disabled</option>
                        </select>
                         @error('exam_status')
                        <p class='text-danger '  style='font-size:14px;'>{{$message}}</p>
                         @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label" style='font-size:14px;color:black;'>Exam Type</label>
                        <select class='form-select' style='font-size:14px;color:black;' name='exam_type'>
                            <option value="single_choice" {{ $examform->exam_type == 'single_choice'? 'selected':'' }}>Single Choice Questions</option>
                            <option value="multi_choice"  {{ $examform->exam_type == 'multi_choice'? 'selected':'' }}>Multiple Choice Questions</option>
                            <option value="direct_questions"  {{ $examform->exam_type == 'direct_questions'? 'selected':'' }}>Direct Questions</option>
                        </select>
                         @error('exam_type')
                        <p class='text-danger '  style='font-size:14px;'>{{$message}}</p>
                         @enderror
                    </div>
                    <button class='btn btn-primary btn-sm fw-bold text-white shadow-0 mt-3 text-capitalize' style='font-size:14px;'>Submit</button>
                </form>
                </div>
                </div>
            </div>
        </div>
        <!-- exam creation form ends here-->
        </div>



    </x-decomentcontentarea>
</x-ownerlayout>



