<x-ownerlayout>
    <x-slot name="title">Exam Manager | All Exam Entries</x-slot>
    <x-ownersidebar />
    <x-decomentcontentarea>

        <div class="container">
            <div class="d-flex align-items-center justify-content-evently mt-2 mb-4 border-bottom">
                <a href="{{ route('exam') }}" class=" mb-3">
                    <i class="fas fa-arrow-left me-3"></i>
                </a>
                <h4 style="font-size:16px;color:black;" class="mb-3 fw-bold">All Exam Entries List
                    ({{ $examform->title }})</h4>
            </div>
            <!-- row starts here-->
            <div class="row">
                <!-- columns start here-->
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <!-- card starts here-->
                    <div class="card shadow-sm">
                        <!-- card body starts here-->
                        <div class="card-body">
                            <!-- flash messages starts here -->
                            @if (session()->has('participant_deletion_success'))
                                <p x-data="{ show: true }" x-init="setTimeout(() => {
                                    show = false;
                                    $wire.examMessage = null;
                                }, 3000)" x-show="show"
                                    class='bg-success-light p-2 mt-1 mb-1'>{{ session('participant_deletion_success') }}</p>
                            @elseif (session()->has('participant_deletion_failed'))
                                <p x-data="{ show: true }" x-init="setTimeout(() => {
                                    show = false;
                                    $wire.examMessage = null;
                                }, 3000)" x-show="show"
                                    class='bg-danger-light p-2 mt-1 mb-1'>{{ session('participant_deletion_failed') }}</p>
                            @endif
                        <!-- flash messages ends here -->

                            <!-- search exam form by exam name -->
                            <div class="row g-2 mb-3">
                                <div class="col-lg-6 col-md-2 col-sm-2">
                                    <input type="text" id='entrieslistsearch' class="form-control"
                                        style="font-size:14px;" placeholder="Search Student By ID">
                                </div>
                            </div>
                            <!-- search ends here-->
                            <div class="table-responsive">
                                <table class='table table-bordered table-sm' id='entriesTable'>
                                    <thead>
                                        <tr>
                                            <th style='font-size:14px;color:black;'>#</th>
                                            <th style='font-size:14px;color:black;'>Student_ID</th>
                                            <th style='font-size:14px;color:black;'>Student_Fullname</th>
                                            <th style='font-size:14px;color:black;'>Student_Score</th>
                                            <th style='font-size:14px;color:black;'>Time_Spent</th>
                                            <th style='font-size:14px;color:black;'>Submitted_Date</th>
                                            <th style='font-size:14px;color:black;'>Actions</th>
                                        </tr>
                                    </thead>
                                        <tbody>
                                            @forelse ($participantsdata as $participantdata)
                                            <tr>
                                                <td style='font-size:14px;color:#484848;'>
                                                    {{ ($participantsdata->currentPage() - 1) * $participantsdata->perPage() + $loop->iteration }}
                                                </td>
                                                <td style='font-size:14px;color:#484848;'>
                                                    {{ $participantdata->participant_id }}</td>
                                                <td style='font-size:14px;color:#484848;'>
                                                    {{ $participantdata->fullname }}</td>
                                                <td style='font-size:14px;color:#484848;'>
                                                    {{ $participantdata->calculated_score ?? '0' }} / {{ (int) $totalMarks }}
                                                </td>
                                                <td style='font-size:14px;color:#484848;'>
                                                    {{ $participantdata->time_spent_formatted ?? 'N/A' }}</td>
                                                <td style='font-size:14px;color:#484848;'>
                                                    {{ $participantdata->created_at->format('d M Y') }}</td>
                                                <td style='font-size:14px;color:#484848;'>
                                                    <a href="{{ route('exam.single_participants_entry',['exam_id'=>base64_encode($examform->id),'participant_id'=>base64_encode($participantdata->id)]) }}" class='text-primary  btn-sm shadow-0 text-capitalize me-2'>
                                                        <i class='fa fa-file' style='font-size:18px !important;'></i>
                                                    </a>

                                                    <a  href="{{ route('exam.allentries.participants.delete',["exam_id"=>base64_encode($examform->id),'participant_id'=>base64_encode($participantdata->id)]) }}" class='text-danger  btn-sm shadow-0 text-capitalize'>
                                                        <i class='fa fa-trash' style='font-size:18px !important;'></i>
                                                    </a>

                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="8" class='text-center'>
                                                    There is nothing to be shown.
                                                </td>
                                        </tr>
                                        @endforelse
                                        </tbody>
                                        <!-- entries  not found message when searching-->
                                        <tr class="no-search" style="display:none;">
                                            <td colspan="8" class="text-center">
                                                Student with specified ID not found
                                            </td>
                                        </tr>
                                </table>
                                {{ $participantsdata->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                        <!-- card body ends here -->
                    </div>
                    <!-- cards ends here-->
                </div>
                <!-- columns ends here-->
            </div>
            <!-- row ends here-->

        </div>
    </x-decomentcontentarea>
    <script>
        document.getElementById('entrieslistsearch').addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#entriesTable tbody tr:not(.no-search)');
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

            const noSearchRow = document.querySelector('#entriesTable tbody .no-search');

            if (noSearchRow) {

                noSearchRow.style.display = found ? 'none' : '';
            }
        });
    </script>
</x-ownerlayout>
