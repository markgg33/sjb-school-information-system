<div class="container-fluid">

    <div id="workspaceHeader"></div>

    <div
        class="nav workspace-tabs mb-4"
        id="workspaceTabs"
        role="tablist">

        <button
            class="nav-link workspace-tab active"
            data-bs-toggle="tab"
            data-bs-target="#studentsTab"
            type="button">

            <i class="fa-solid fa-users me-2"></i>
            Students

        </button>

        <button
            class="nav-link workspace-tab"
            data-bs-toggle="tab"
            data-bs-target="#gradesTab"
            type="button">

            <i class="fa-solid fa-graduation-cap me-2"></i>
            Grades

        </button>

        <button
            class="nav-link workspace-tab"
            data-bs-toggle="tab"
            data-bs-target="#reportsTab"
            type="button">

            <i class="fa-solid fa-chart-column me-2"></i>
            Reports

        </button>

    </div>

    <div class="row mb-4">

        <div class="col-lg-3">

            <div class="dashboard-card text-center">

                <h3 id="workspaceStudentCount">35</h3>

                <div class="text-muted">

                    Students

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="dashboard-card text-center">

                <h3 id="workspaceComponents">8</h3>

                <div class="text-muted">

                    Components

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="dashboard-card text-center">

                <h3 id="workspaceAverage">

                    89.25

                </h3>

                <div class="text-muted">

                    Class Average

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="dashboard-card text-center">

                <h3 id="workspacePassing">

                    31 / 35

                </h3>

                <div class="text-muted">

                    Passed

                </div>

            </div>

        </div>

    </div>

    <div class="tab-content mt-4">

        <div
            class="tab-pane fade show active"
            id="studentsTab">

        </div>

        <div
            class="tab-pane fade"
            id="gradesTab">

            <div class="dashboard-card">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="mb-0">

                        Grade Management

                    </h4>

                    <div>

                        <button
                            class="btn btn-outline-primary gradingPeriod active"
                            data-period="Prelim">

                            Prelim

                        </button>

                        <button
                            class="btn btn-outline-primary gradingPeriod"
                            data-period="Midterm">

                            Midterm

                        </button>

                        <button
                            class="btn btn-outline-primary gradingPeriod"
                            data-period="Finals">

                            Finals

                        </button>

                    </div>

                </div>

                <hr>

                <div id="gradingWorkspace">

                </div>

            </div>

        </div>

        <div
            class="tab-pane fade"
            id="reportsTab">

        </div>

    </div>

</div>

<!-- WORKSPACE MODAL -->

<div class="modal fade" id="componentModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    Add Grading Component

                </h5>

                <button
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <input
                    type="hidden"
                    id="componentPeriod">

                <div class="mb-3">

                    <label class="form-label">

                        Component Name

                    </label>

                    <input
                        type="text"
                        id="componentName"
                        class="form-control">

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Type

                    </label>

                    <select
                        id="componentType"
                        class="form-select">

                        <option>Quiz</option>
                        <option>Activity</option>
                        <option>Assignment</option>
                        <option>Project</option>
                        <option>Laboratory</option>
                        <option>Seatwork</option>
                        <option>Recitation</option>
                        <option>Performance Task</option>
                        <option>Exam</option>
                        <option>Others</option>

                    </select>

                </div>

                <div class="row">

                    <div class="col">

                        <label>

                            Max Score

                        </label>

                        <input
                            type="number"
                            id="maxScore"
                            class="form-control">

                    </div>

                    <div class="col">

                        <label>

                            Weight %

                        </label>

                        <input
                            type="number"
                            id="weight"
                            class="form-control">

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Cancel

                </button>

                <button
                    class="btn btn-primary"
                    id="saveComponent">

                    Save

                </button>

            </div>

        </div>

    </div>

</div>

<!-- =======================================
     STUDENT PROFILE
======================================= -->

<div
    class="modal fade"
    id="studentProfileModal"
    tabindex="-1">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title">

                    <i class="fa-solid fa-user-graduate me-2"></i>

                    Student Information

                </h4>

                <button
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div
                class="modal-body"
                id="studentProfileContent">

                <div class="text-center py-5">

                    <div class="spinner-border text-primary"></div>

                </div>

            </div>

        </div>

    </div>

</div>