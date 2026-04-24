<x-ownerlayout>
    <x-slot name="title">Exam Manager | Exam Lists </x-slot>
    <style>
        /* 2. Higher z-index for the menu itself */
        .dropdown-menu {
            z-index: 1055;
        }

        /* 3. Optional: If the card-body is still clipping it */
        .card-body {
            overflow: visible;
        }

        /* #examTable {
    margin-bottom: 20px; /* Gives the last row's dropdown room to open downward
    }*/
    </style>
    <x-ownersidebar />
    <x-decomentcontentarea>

        <div class="container">
            <div
                class="d-lg-flex flex-column flex-lg-row align-items-center justify-content-between mt-2 mb-4 border-bottom">
                <h4 style="font-size:16px;color:black;" class="mb-3 fw-bold">Exam Lists</h4>
                <a href="{{ route('exam.create') }}" class="btn btn-secondary shadow-0 fw-bold mb-3">
                    <i class="fa-solid fa-plus me-2"></i> Create New Exam
                </a>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <!-- search exam form by exam name -->
                            <div class="row g-2 mb-2">
                                <div class="col-lg-4 col-md-2 col-sm-2">
                                    <input type="text" id='examSearch' class="form-control" style="font-size:14px;"
                                        placeholder="Search Exam By Name (Title)">
                                </div>

                            </div>
                            <!-- display examform deletion flash message-->
                            @if (session()->has('exam_form_deleted'))
                                <p class='bg-success-light p-2'>{{ session('exam_form_deleted') }}</p>
                            @endif
                            <!-- display examform deletion flash message-->
                            <!-- search ends here -->
                            <div class='table-responsive'>
                                <table class='table table-sm table-bordered' id='examTable'>
                                    <thead>
                                        <tr>
                                            <th style='font-size:14px;'>#</th>
                                            <th style='font-size:14px;'>Exam_Name</th>
                                            <th style='font-size:14px;'>Exam_Type</th>
                                            <th style='font-size:14px;'>Status</th>
                                            <th style='font-size:14px;'>Score</th>
                                            <th style='font-size:14px;'>Duration</th>
                                            <th style='font-size:14px;'>Date</th>
                                            <th style='font-size:14px;'>Actions</th>
                                        </tr>
                                    </thead>

                                    @forelse ($allexams as $examdata)
                                        <tbody>
                                            <tr>
                                                <td>{{ ($allexams->currentPage() - 1) * $allexams->perPage() + $loop->iteration }}
                                                </td>
                                               <td style="max-width: 250px; word-break: break-word; white-space: normal;">
                                                    {{ $examdata->title }}
                                                </td>

                                                <td>
                                                    {{ match ($examdata->exam_type) {
                                                        'single_choice' => 'Single Choice Questions',
                                                        'multi_choice' => 'Multi Choice Questions',
                                                        'direct_questions' => 'Direct Questions',
                                                    } }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="{{ match ($examdata->status) {
                                                            'active' => 'bg-success-light',
                                                            'disabled' => 'bg-danger-light',
                                                        } }} p-2 rounded">
                                                        {{ $examdata->status }} </span>
                                                </td>
                                                <td>{{ (int) $examdata->questions_sum_marks ?? 0 }}</td>
                                                <td>{{ match ($examdata->duration) {
                                                    '1:00' => '1 Hour',
                                                    '2:00' => '2 Hour',
                                                    '3:00' => '3 Hour',
                                                    '4:00' => '4 Hour',
                                                    '5:00' => '5 Hour',
                                                } }}
                                                </td>
                                                <td>{{ $examdata->created_at->format('d M Y') }}</td>

                                                <td class=''>
                                                    <!-- action button-->
                                                    <div class="dropdown">
                                                        <button class="btn btn-link text-dark p-2" type="button"
                                                            id="dropdownMenuButton{{ $examdata->id }}"
                                                            data-bs-toggle="dropdown" data-bs-boundary="viewport"
                                                            {{-- This is the critical line --}} aria-expanded="false"
                                                            {{-- aria-expanded="false" --}}>
                                                            <i class="fas fa-ellipsis-vertical"
                                                                style="font-size:20px;"></i>
                                                        </button>

                                                        <ul class="dropdown-menu dropdown-menu-end"
                                                            aria-labelledby="dropdownMenuButton{{ $examdata->id }}">
                                                            <li><a class="dropdown-item"
                                                                    href="{{ route('exam.edit.index', ['exam_id' => $examdata->id]) }}">Edit</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="{{ match ($examdata->exam_type) {
                                                                        'single_choice' => route('exam.questions.singlechoice_questions', ['exam_id' => $examdata->id]),
                                                                        'multi_choice' => route('exam.questions.multiplechoice_questions', ['exam_id' => $examdata->id]),
                                                                    } }}">Add
                                                                    Questions</a></li>
                                                            <li><a class="dropdown-item"
                                                                    href="{{ route('exam.allentries.index', ['exam_id' => base64_encode($examdata->id)]) }}">Enteries</a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0)" class="dropdown-item"
                                                                    onclick="openShareModal('{{ route('frontend.studentform.index', ['exam_id' => base64_encode($examdata->id)]) }}')">
                                                                    Generate Shareable Exam Link
                                                                </a>
                                                                </a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="{{ route('exam.delete', ['exam_id' => $examdata->id]) }}">Delete</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <!-- action button-->
                                                </td>
                                            </tr>
                                        </tbody>
                                    @empty
                                        <tr>
                                            <td colspan="8" class='text-center'>
                                                There is nothing to be shown.
                                            </td>
                                        </tr>
                                    @endforelse
                                    <!-- exam not found message when searching-->
                                    <tr class="no-search" style="display:none;">
                                        <td colspan="8" class="text-center">
                                            Exam not found
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            {{ $allexams->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Generate exam submission portal link modal-->
        <div class="modal fade" id="shareLinkModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-sm">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" style='font-size:15px;color:black;'>Generate Shareable Exam Link</h5>
                    </div>

                    <div class="modal-body">
                        <input type="text" id="examLinkInput" class="form-control" readonly style='font-size:13px;font-weight:600;'>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary text-capitalize shadow-0 fw-bold" style='font-size:13px;'
                            data-bs-dismiss="modal">
                            Close
                        </button>

                        <a id="visitLinkBtn" href="#" target="_blank"
                            class="btn btn-primary text-capitalize shadow-0 fw-bold" style='font-size:13px;'>
                            Preview Exam Submission
                        </a>
                    </div>

                </div>
            </div>
        </div>
        <!-- Generate exam   submission portal link modal ends here-->
    </x-decomentcontentarea>
    {{-- search exam by name --}}
    <script>
        document.getElementById('examSearch').addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#examTable tbody tr:not(.no-search)');
            let found = false;

            rows.forEach(row => {
                const examName = row.cells[1]?.textContent.toLowerCase();

                if (examName && examName.includes(filter)) {
                    row.style.display = '';
                    found = true;
                } else {
                    row.style.display = 'none';;
                }
            });

            const noSearchRow = document.querySelector('#examTable tbody .no-search');

            if (noSearchRow) {

                noSearchRow.style.display = found ? 'none' : '';
            }
        });
    </script>
    <!-- drop down modal in exam table-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'))
            var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl, {
                    popperConfig: function(defaultBsPopperConfig) {
                        return {
                            ...defaultBsPopperConfig,
                            strategy: 'fixed' // This forces the menu to float above everything
                        };
                    }
                });
            });
        });
    </script>

    <!-- Generate exam submission portal link modal js-->
    <script>
        function openShareModal(url) {
            // set input value
            document.getElementById('examLinkInput').value = url;

            // set visit button
            document.getElementById('visitLinkBtn').href = url;

            // show bootstrap modal
            const modal = new bootstrap.Modal(document.getElementById('shareLinkModal'));
            modal.show();
        }
    </script>
</x-ownerlayout>
