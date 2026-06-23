<div class="dashboard-card mb-4">

    <div class="d-flex align-items-center justify-content-between">

        <div>

            <div class="small text-muted mb-1">

                <a href="#"
                    id="backToEnrollment">

                    Enrollment

                </a>

                <i class="fa-solid fa-chevron-right mx-2"></i>

                <a href="#"
                    id="backToEnrollmentCreate">

                    New Enrollment

                </a>

                <i class="fa-solid fa-chevron-right mx-2"></i>

                <span class="fw-semibold">

                    Enrollment Details

                </span>

            </div>

        </div>

    </div>

</div>

<div class="dashboard-card">

    <h4
        class="mb-3"
        id="enrollmentPageTitle">

        <i class="fa-solid fa-user-check me-2 mb-3"></i>

        New Enrollment

    </h4>

    <div id="selectedStudentInfo">

    </div>

    <hr>

    <form id="saveEnrollmentForm">

        <input
            type="hidden"
            id="selectedStudentId"
            name="student_id">

        <input
            type="hidden"
            id="enrollmentId"
            name="enrollment_id">

        <div class="row g-3">

            <div class="col-md-4">

                <label class="modern-label">
                    School Year
                </label>

                <input
                    type="text"
                    id="schoolYear"
                    name="school_year"
                    class="form-control modern-input"
                    placeholder="2026-2027">

            </div>

            <div class="col-md-4">

                <label class="modern-label">
                    Year Level
                </label>

                <select
                    id="yearLevel"
                    name="year_level"
                    class="form-select modern-input">

                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>

                </select>

            </div>

            <div class="col-md-4">

                <label class="modern-label">
                    Trimester
                </label>

                <select
                    id="trimester"
                    name="trimester"
                    class="form-select modern-input">

                    <option value="1">
                        1st Trimester
                    </option>

                    <option value="2">
                        2nd Trimester
                    </option>

                    <option value="3">
                        3rd Trimester
                    </option>

                </select>

            </div>

        </div>

        <hr>

        <hr>

        <div class="row mt-4">

            <div class="col-lg-5">

                <div class="dashboard-card h-100">

                    <h6
                        class="mb-3"
                        id="leftPanelTitle">

                        Curriculum Subjects

                    </h6>

                    <div id="curriculumSubjectsContainer">

                        <div class="text-muted">

                            Select year level and trimester
                            to load curriculum subjects.

                        </div>

                    </div>

                    <div
                        id="currentEnrollmentSubjects"
                        style="display:none;">
                    </div>

                </div>

            </div>

            <div
                class="col-lg-7"
                id="additionalSubjectsPanel">

                <div class="dashboard-card">

                    <h6 class="mb-3">
                        Additional Subjects
                    </h6>

                    <div class="row">

                        <!-- AVAILABLE SUBJECTS -->

                        <div class="col-md-6">

                            <div class="d-flex gap-2 mb-3">

                                <input
                                    type="text"
                                    id="additionalSubjectSearch"
                                    class="form-control modern-input"
                                    placeholder="Search subject...">

                                <button
                                    type="button"
                                    class="module-subject-btn"
                                    id="searchAdditionalSubjectsBtn">

                                    Search

                                </button>

                            </div>

                            <div
                                id="additionalSubjectsList"
                                class="border rounded p-2"
                                style="height:400px; overflow-y:auto;">
                            </div>

                        </div>

                        <!-- SELECTED SUBJECTS -->

                        <div class="col-md-6">

                            <div
                                class="d-flex justify-content-between align-items-center mb-3">

                                <h6 class="mb-0">
                                    Selected Subjects
                                </h6>

                                <span
                                    id="manualSubjectCount"
                                    class="badge bg-primary">

                                    0

                                </span>

                            </div>

                            <div
                                id="manualSubjectsContainer"
                                class="border rounded p-2"
                                style="height:400px; overflow-y:auto;">

                                <!--div class="text-muted small">

                                    No additional subjects selected.

                                </div-->

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="mt-4">

            <button
                type="button"
                class="module-action-btn"
                id="saveEnrollmentBtn">

                Save Enrollment

            </button>

        </div>

    </form>

</div>