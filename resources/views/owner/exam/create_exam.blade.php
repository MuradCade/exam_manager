<x-ownerlayout>
        <x-slot name="title">Exam Manager | Create New Exam</x-slot>
   <x-ownersidebar />
    <x-decomentcontentarea>
        

        <div class="container">
           <div class="d-flex align-items-center justify-content-evently mt-2 mb-4 border-bottom">
               <a href="{{ route('exam') }}" 
               class=" mb-3">
                   <i class="fas fa-arrow-left me-3"></i>
               </a>
            <h4 style="font-size:16px;color:black;" class="mb-3 fw-bold">Create New Exam</h4>
        </div>

        <!-- exam creation form starts here-->
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @if (session()->has('new_exam_creaction'))
                        <p class='bg-success-light p-2'>{{session('new_exam_creaction')}}</p>                            
                        @endif
                <form action='{{ route('exam.create.submit') }}' method='POST'>
                    @csrf
                    <div class="form-group">
                        <label class="form-label" style='font-size:14px;color:black;'>Exam Name</label>
                        <input type="text" class='form-control' placeholder="Enter Exam Name" style='font-size:14px'
                        name='exam_name' value='{{ old('exam_name') }}'>
                         @error('exam_name')
                        <p class='text-danger '  style='font-size:14px;'>{{$message}}</p>
                         @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label" style='font-size:14px;color:black;'>Exam Description (Instructions)</label>
                        <textarea rows='6' columns='6' class='form-control' placeholder="Enter Exam Description" style='font-size:14px'
                        name='exam_description'>{{old('exam_description')}}</textarea>
                         @error('exam_description')
                        <p class='text-danger '  style='font-size:14px;'>{{$message}}</p>
                         @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label" style='font-size:14px;color:black;'>Exclude Participants</label>
                        <input type="text" name='exclude_participants' class='form-control' style='font-size:14px' placeholder="Enter participant id's to be excluded">
                         {{-- @error('exclude_participants')
                        <p class='text-danger '  style='font-size:14px;'>{{$message}}</p>
                         @enderror --}}
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label" style='font-size:14px;color:black;'>Exam Status</label>
                        <select class='form-select' style='font-size:14px;color:black;' name='exam_status'>
                            <option value="active">Active</option>
                            <option value="disabled">Disabled</option>
                        </select>
                         @error('exam_status')
                        <p class='text-danger '  style='font-size:14px;'>{{$message}}</p>
                         @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label" style='font-size:14px;color:black;'>Exam Type</label>
                        <select class='form-select' style='font-size:14px;color:black;' name='exam_type'>
                            <option value="single_choice">Single Choice Questions</option>
                            <option value="multi_choice">Multiple Choice Questions</option>
                            {{-- <option value="direct_questions">Direct Questions</option> --}}
                        </select>
                         @error('exam_type')
                        <p class='text-danger '  style='font-size:14px;'>{{$message}}</p>
                         @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label" style='font-size:14px;color:black;'>Exam Duration</label>
                        <select class='form-select' name='duration' style='font-size:14px;color:black;'>
                            <option value="1:00">1 Hour</option>
                            <option value="2:00">2 Hour</option>
                            <option value="3:00">3 Hour</option>
                            <option value="4:00">4 Hour</option>
                            <option value="5:00">5 Hour</option>
                        </select>
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



