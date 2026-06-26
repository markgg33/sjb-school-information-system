<div class="container-fluid">

    <div class="welcome-banner mb-4">

        <h2>
            Welcome Back 👋
        </h2>

        <p>
            View your academic progress.
        </p>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-lg-3 col-md-6">

            <div class="dashboard-card">

                <div class="card-icon">
                    <i class="fa-solid fa-book-open"></i>
                </div>

                <h6>Current Subjects</h6>

                <h2 id="currentSubjectsCount">
                    0
                </h2>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="dashboard-card">

                <div class="card-icon">
                    <i class="fa-solid fa-layer-group"></i>
                </div>

                <h6>Units Enrolled</h6>

                <h2 id="currentUnits">
                    0
                </h2>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="dashboard-card">

                <div class="card-icon">
                    <i class="fa-solid fa-check-circle"></i>
                </div>

                <h6>Completed Subjects</h6>

                <h2 id="completedSubjects">
                    0
                </h2>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="dashboard-card">

                <div class="card-icon">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>

                <h6>Status</h6>

                <h2 id="studentStatus">
                    Active
                </h2>

            </div>

        </div>

    </div>

    <div class="dashboard-card">

        <h5 class="mb-3">

            Current Enrollment

        </h5>

        <div id="currentEnrollmentSummary">

        </div>

        <hr>

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h5 class="mb-1">

                    Current Subjects

                </h5>

                <p class="text-muted mb-0">

                    View your currently enrolled subjects.

                </p>

            </div>

            <button
                type="button"
                class="btn btn-outline-primary"
                id="viewCurrentSubjectsBtn">

                <i class="fa-solid fa-book-open me-2"></i>

                View Subjects

            </button>

        </div>

    </div>

</div>

<!--CURRENT SUBJECT MODAL-->

<div
    class="modal fade"
    id="currentSubjectsModal"
    tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    Current Subjects

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div
                class="modal-body"
                id="currentSubjectsContent">

                Loading...

            </div>

        </div>

    </div>

</div>