<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">

    <div>

        <h2 class="mb-1">
            Curriculum Builder
        </h2>

        <p class="text-muted mb-0">
            Manage course curriculum by year level and trimester
        </p>

    </div>

</div>

<div class="row g-4">

    <!-- LEFT PANEL -->
    <div class="col-lg-4">

        <div class="dashboard-card">

            <h5 class="mb-4">
                Curriculum Setup
            </h5>

            <div class="mb-3">

                <label class="modern-label">
                    Course
                </label>

                <select
                    class="form-select modern-input"
                    id="courseSelect">

                    <option value="">
                        Select Course
                    </option>

                </select>

            </div>

            <div class="mb-3">

                <label class="modern-label">
                    Year Level
                </label>

                <select
                    class="form-select modern-input"
                    id="curriculumYearLevel">

                    <option value="">
                        Select Year Level
                    </option>

                    <option value="1">Year 1</option>
                    <option value="2">Year 2</option>
                    <option value="3">Year 3</option>

                </select>

            </div>

            <div class="mb-4">

                <label class="modern-label">
                    Trimester
                </label>

                <select
                    class="form-select modern-input"
                    id="curriculumTrimester">

                    <option value="">
                        Select Trimester
                    </option>

                    <option value="1">Trimester 1</option>
                    <option value="2">Trimester 2</option>
                    <option value="3">Trimester 3</option>

                </select>

            </div>

            <button
                class="module-action-btn w-100 justify-content-center"
                id="loadSubjectsBtn">

                <div class="btn-icon">

                    <i class="fa-solid fa-magnifying-glass"></i>

                </div>

                <span>
                    Load
                </span>

            </button>

        </div>

    </div>

    <!-- RIGHT PANEL -->
    <div class="col-lg-8">

        <div class="dashboard-card curriculum-subjects-card">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="mb-0">
                    Available Subjects
                </h5>

                <div>

                    <strong>
                        Selected Units:
                    </strong>

                    <span id="selectedUnits">
                        0
                    </span>

                </div>

            </div>

            <div class="row g-2 mb-3 align-items-end">

                <div class="col-md-8">

                    <input
                        type="text"
                        id="curriculumSubjectSearch"
                        class="form-control modern-input"
                        placeholder="Search subject...">

                </div>

                <div class="col-md-2">

                    <select
                        id="curriculumUnitsFilter"
                        class="form-select modern-input">

                        <option value="">
                            All Units
                        </option>

                        <option value="2">
                            2 Units
                        </option>

                        <option value="3">
                            3 Units
                        </option>

                    </select>

                </div>

                <div class="col-md-2">

                    <button
                        class="module-action-btn w-100 justify-content-center"
                        id="filterCurriculumSubjectsBtn">

                        <div class="btn-icon">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>

                        <span>Search</span>

                    </button>

                </div>

            </div>

            <div
                id="subjectsContainer"
                class="curriculum-subjects scrollable-subjects">

                <div class="text-center py-5 text-muted">

                    Select a course, year level,
                    and trimester first.

                </div>

            </div>

            <div
                id="curriculumPagination"
                class="d-flex justify-content-center mt-3">
            </div>

            <div class="mt-4">

                <button
                    class="module-action-btn"
                    id="saveCurriculumBtn">

                    <div class="btn-icon">
                        <i class="fa-solid fa-floppy-disk"></i>
                    </div>

                    <span>
                        Save Curriculum
                    </span>

                </button>

            </div>

        </div>

    </div>

</div>

<!-- CURRENT CURRICULUM -->

<div class="dashboard-card mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <h5 class="mb-1">
                Current Curriculum
            </h5>

            <small class="text-muted">

                Assigned subjects for the selected curriculum

            </small>

        </div>

    </div>

    <div
        id="curriculumTableContainer">

        <div class="text-center py-5 text-muted">

            No curriculum selected.

        </div>

    </div>

</div>