<x-ownerlayout>
    <x-slot name="title">Exam Manager | Single Exam Entry Details</x-slot>

    <x-ownersidebar />

    <x-decomentcontentarea>

        <div class="container">

            <!-- Heading -->
            <div class="d-flex align-items-center justify-content-evently mt-2 mb-4 border-bottom">
                <a href="{{ route('exam.allentries.index', ['exam_id' => base64_encode($examform->id)]) }}" class="mb-3">
                    <i class="fas fa-arrow-left me-3"></i>
                </a>
                <h4 style="font-size:16px;color:black;" class="mb-3 fw-bold">
                    Single Exam Entry Details
                </h4>
            </div>

            <!-- Student Info -->
            <div class="col-lg-10 col-md-10 col-sm-12">

                <div class="mt-2 mb-2">
                    <p>Student ID: <span class="fw-bold">{{ $participants->participant_id }}</span></p>
                    <p>Student Fullname: <span class="fw-bold">{{ $participants->fullname }}</span></p>
                    <p>Examination Name: <span class="fw-bold">{{ $examform->title }}</span></p>
                    <p>
                        Student Exam Score:
                        <span class="fw-bold">
                            {{ $participants_examscore }} / {{ (int) $exam_originalmarks }}
                        </span>
                    </p>
                </div>

                <!-- Questions -->
                @forelse ($questions_and_options as $questiondata)

                    @php
                        $userSelection = $userAnswers[$questiondata->id] ?? [];
                    @endphp

                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">

                            <!-- Question -->
                            <h5 style="font-size:15px;">
                                {{ $questiondata->question_text }}
                            </h5>

                            {{-- ================= SINGLE CHOICE ================= --}}
                            @if ($examform->exam_type == 'single_choice')

                                @foreach ($questiondata->options as $option)
                                    @foreach ($option->option_text as $textIndex => $textValue)

                                        @php
                                            $isCorrect = $textIndex == $option->correct_option;
                                            $isSelected = in_array($textIndex, $userSelection);
                                        @endphp

                                        <div class="mt-2 px-3 py-2 border rounded d-flex justify-content-between
                                            {{ $isCorrect ? 'bg-light border-success' : 'border-light' }}"
                                            style="{{ $isSelected ? 'border-left:5px solid #2196f3' : '' }}">

                                            <div>
                                                @if ($isCorrect)
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                @elseif($isSelected)
                                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                                @else
                                                    <i class="far fa-circle text-muted me-2"></i>
                                                @endif

                                                <span class="{{ $isSelected ? 'fw-bold text-primary' : '' }}">
                                                    {{ $textValue }}
                                                </span>
                                            </div>

                                            @if ($isSelected)
                                                <span class="badge bg-primary p-2" style="font-size:12px;">
                                                    Your Choice
                                                </span>
                                            @endif

                                        </div>

                                    @endforeach
                                @endforeach
                            @endif


                            {{-- ================= MULTI CHOICE ================= --}}
                            @if ($examform->exam_type == 'multi_choice')

                                @foreach ($questiondata->options as $option)

                                    @php
                                        $correctOptions = is_array($option->correct_option)
                                            ? $option->correct_option
                                            : json_decode($option->correct_option, true);

                                        if (!is_array($correctOptions)) {
                                            $correctOptions = $correctOptions ? [$correctOptions] : [];
                                        }

                                        $hasAnyCorrectSelection = count(array_intersect($userSelection, $correctOptions)) > 0;
                                    @endphp

                                    @foreach ($option->option_text as $textIndex => $textValue)

                                        @php
                                            $isCorrect = in_array($textIndex, $correctOptions);
                                            $isSelected = in_array($textIndex, $userSelection);
                                        @endphp

                                        <div class="mt-2 px-3 py-2 border rounded d-flex justify-content-between
                                            {{ $isCorrect ? 'bg-light border-success' : ($isSelected ? 'border-danger bg-light' : 'border-light') }}"

                                            style="
                                            {{ $isSelected && $isCorrect ? 'border-left:5px solid green' : '' }}
                                            {{ $isSelected && !$isCorrect ? 'border-left:5px solid red' : '' }}
                                            ">

                                            <div>
                                                @if ($isCorrect && $isSelected)
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                @elseif($isCorrect)
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                @elseif($isSelected)
                                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                                @else
                                                    <i class="far fa-circle text-muted me-2"></i>
                                                @endif

                                                <span class="{{ $isSelected ? 'fw-bold text-primary' : '' }}">
                                                    {{ $textValue }}
                                                </span>
                                            </div>

                                            @if ($isSelected)
                                                <span class="badge bg-primary  p-2" style="font-size:12px;">
                                                    Your Choice
                                                </span>
                                            @endif

                                        </div>

                                    @endforeach
                                @endforeach

                                {{-- Summary --}}
                                <div class="mt-2">
                                    @if ($hasAnyCorrectSelection)
                                        <span class="text-success fw-bold">
                                            ✔ At least one correct answer selected
                                        </span>
                                    @else
                                        <span class="text-danger fw-bold">
                                            ✘ No correct answer selected
                                        </span>
                                    @endif
                                </div>

                            @endif

                        </div>
                    </div>

                @empty
                    <p>No Questions Found</p>
                @endforelse

            </div>

        </div>

    </x-decomentcontentarea>
</x-ownerlayout>