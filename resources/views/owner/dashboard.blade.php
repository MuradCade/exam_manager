<x-ownerlayout>
    <x-slot name="title">Exam Manager | Dashboard </x-slot>
    <x-ownersidebar />
    <x-decomentcontentarea>



        <div class="container">
            <div class="d-flex align-items-center justify-content-evently mt-2 mb-4 border-bottom">
                <h4 style="font-size:16px;color:black;" class="mb-3 fw-bold">Dashboard</h4>
            </div>
            <!-- cards row start here-->
            <div class="row g-3">
                <!-- first card starts here (displays sum created examforms by the authenticated user)-->
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <!-- card element starts here -->
                    <div class="card shadow-0 border" style="max-width: 280px; border-radius: 12px;">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="me-3 p-3 bg-light rounded-circle text-primary">
                                <i class="fas fa-file-circle-plus fa-lg"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Total Exams</p>
                                <p class="h3 mb-0 fw-bold text-black">{{ $user->total_exams }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- card element ends here-->
                </div>
                <!-- first card ends here -->

                <!-- second card starts here (displays sum active examforms by the authenticated user)-->
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <!-- card element starts here -->
                    <div class="card shadow-0 border" style="max-width: 280px; border-radius: 12px;">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="me-3 p-3 bg-light rounded-circle text-success">
                                <i class="fas fa-file-circle-plus fa-lg"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Total Active Exams</p>
                                <p class="h3 mb-0 fw-bold text-black">{{ $user->active_exams }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- card element ends here-->
                </div>
                <!-- second card ends here -->

                <!-- third card starts here (displays sum expired examforms by the authenticated user)-->
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <!-- card element starts here -->
                    <div class="card shadow-0 border" style="max-width: 280px; border-radius: 12px;">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="me-3 p-3 bg-light rounded-circle text-danger">
                                <i class="fas fa-file-circle-plus fa-lg"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1 ">Total Disabled Exams</p>
                                <p class="h3 mb-0 fw-bold text-black">{{ $user->disabled_exams }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- card element ends here-->
                </div>
                <!-- third card ends here -->

            </div>
            <!-- cards row ends here-->

            <!-- exam activities starts here-->
            <div class="col-lg-9 col-md-9 col-sm-12 mt-4">
                <div class="card shadow-0 border">
                    <div class="card-body table-responsive">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                            <h4 style="font-size:15px; color:black;" class="mb-0 fw-bold">Recent Exam Submissions</h4>
                            <a href="{{ route('exam') }}"
                                class="btn btn-link btn-sm p-0 text-primary fw-bold text-capitalize">View All</a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="font-size: 13px;">Participant</th>
                                        <th scope="col" style="font-size: 13px;">Examination Name</th>
                                        <th scope="col" style="font-size: 13px;" class="text-end">Submitted At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($participants as $participantdata)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span
                                                        class="fw-bold text-dark text-capitalize">{{ $participantdata->fullname ?? 'Student' }}</span>
                                                    <small class="text-muted">ID:
                                                        {{ $participantdata->participant_id ?? 'N/A' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($participantdata->examform)
                                                    <a href="{{ route('exam.single_participants_entry', [
                                                        'exam_id' => base64_encode($participantdata->examform->id),
                                                        'participant_id' => base64_encode($participantdata->id),
                                                    ]) }}"
                                                        class="text-muted text-decoration-underline text-capitalize">
                                                        {{ $participantdata->examform->title }}
                                                    </a>
                                                @else
                                                    <span class="text-muted italic">Deleted Exam</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <small class="text-muted">
                                                    {{ $participantdata->created_at->diffForHumans() }}
                                                </small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-5 text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                                <span class="small">No Recent Activities Found</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- exam activities ends here-->


        </div>




    </x-decomentcontentarea>
</x-ownerlayout>
