<div class="container-fluid">

    <div class="welcome-banner mb-4">

        <h2>

            Curriculum Progress

        </h2>

        <p>

            Track your progress throughout your academic program.

        </p>

    </div>

    <!-- Statistics -->

    <div class="row g-4 mb-4">

        <div class="col-lg-3">

            <div class="dashboard-card">

                <div class="card-icon">

                    <i class="fa-solid fa-circle-check"></i>

                </div>

                <h6>

                    Completed

                </h6>

                <h2 id="completedCurriculumSubjects">

                    0

                </h2>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="dashboard-card">

                <div class="card-icon">

                    <i class="fa-solid fa-book-open"></i>

                </div>

                <h6>

                    Current

                </h6>

                <h2 id="currentCurriculumSubjects">

                    0

                </h2>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="dashboard-card">

                <div class="card-icon">

                    <i class="fa-solid fa-clock"></i>

                </div>

                <h6>

                    Remaining

                </h6>

                <h2 id="remainingCurriculumSubjects">

                    0

                </h2>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="dashboard-card">

                <div class="card-icon">

                    <i class="fa-solid fa-chart-pie"></i>

                </div>

                <h6>

                    Progress

                </h6>

                <h2 id="curriculumProgressPercent">

                    0%

                </h2>

            </div>

        </div>

    </div>

    <div class="dashboard-card">

        <h5 class="mb-3">

            Overall Curriculum Progress

        </h5>

        <div class="progress mb-4" style="height:12px;">

            <div
                id="curriculumProgressBar"
                class="progress-bar"
                role="progressbar"
                style="width:0%;">

            </div>

        </div>

        <div id="curriculumRoadmap">

        </div>

    </div>

</div>

<!-- Curriculum Details Modal -->

<div
    class="modal fade"
    id="curriculumSubjectsModal"
    tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="curriculumModalTitle">

                    Curriculum Details

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div
                class="modal-body"
                id="curriculumModalBody">

            </div>

        </div>

    </div>

</div>