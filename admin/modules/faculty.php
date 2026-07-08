<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="mb-1">
            Faculty
        </h2>

        <p class="text-muted mb-0">
            Manage faculty records and accounts
        </p>

    </div>

    <button
        class="module-action-btn"
        id="btnAddFaculty">

        <div class="btn-icon">
            <i class="fa-solid fa-user-plus"></i>
        </div>

        <span>
            Add Faculty
        </span>

    </button>

</div>

<div class="dashboard-card">

    <div class="row mb-3 g-3 align-items-end">

        <div class="col-md-3">

            <select
                id="facultyStatusFilter"
                class="form-select modern-input">

                <option value="">
                    All Status
                </option>

                <option value="active">
                    Active
                </option>

                <option value="inactive">
                    Inactive
                </option>

            </select>

        </div>

        <div class="col-md-3">

            <select
                id="facultyCourseFilter"
                class="form-select modern-input">

                <option value="">
                    All Courses
                </option>

            </select>

        </div>

        <div class="col-md-5">

            <input
                type="text"
                id="facultySearch"
                class="form-control modern-input"
                placeholder="Search faculty...">

        </div>

        <div class="col-md-1">

            <button
                class="module-subject-btn w-100 justify-content-center"
                id="applyFacultyFiltersBtn">

                <div class="btn-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>

            </button>

        </div>

    </div>

    <div class="table-responsive">

        <table class="table align-middle">

            <thead>

                <tr>

                    <th>Employee No.</th>

                    <th>Name</th>

                    <th>Gender</th>

                    <th>Courses</th>

                    <th>Status</th>

                    <th width="170">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody id="facultyTableBody"></tbody>

        </table>

        <div
            id="facultyPagination"
            class="d-flex justify-content-center mt-3">
        </div>

    </div>

</div>

<!--MODAL ADD FACULTY-->

<div
    class="modal fade"
    id="facultyModal"
    tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content modern-modal">

            <div class="modal-header">

                <h5 class="modal-title">
                    Add Faculty
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form id="facultyForm">

                    <input
                        type="hidden"
                        id="facultyId"
                        name="id">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="modern-label">
                                Employee Number
                            </label>

                            <input
                                type="text"
                                name="employee_number"
                                class="form-control modern-input">

                        </div>

                        <div class="col-md-6">

                            <label class="modern-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control modern-input"
                                required>

                        </div>

                        <div class="col-md-4">

                            <label class="modern-label">
                                First Name
                            </label>

                            <input
                                type="text"
                                name="first_name"
                                class="form-control modern-input"
                                required>

                        </div>

                        <div class="col-md-4">

                            <label class="modern-label">
                                Middle Name
                            </label>

                            <input
                                type="text"
                                name="middle_name"
                                class="form-control modern-input">

                        </div>

                        <div class="col-md-4">

                            <label class="modern-label">
                                Last Name
                            </label>

                            <input
                                type="text"
                                name="last_name"
                                class="form-control modern-input"
                                required>

                        </div>

                        <div class="col-md-6">

                            <label class="modern-label">
                                Gender
                            </label>

                            <select
                                name="gender"
                                class="form-select modern-input">

                                <option value="male">Male</option>
                                <option value="female">Female</option>

                            </select>

                        </div>

                        <div class="col-12">

                            <label class="modern-label">
                                Courses Handled
                            </label>

                            <div class="dropdown">

                                <button
                                    class="form-select text-start"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    id="facultyCourseDropdownText">

                                    Select Courses

                                </button>

                                <div
                                    class="dropdown-menu p-3 w-100"
                                    style="max-height:300px; overflow-y:auto;">

                                    <div id="facultyCoursesContainer"></div>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="modern-label">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select modern-input">

                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label class="modern-label">
                                Contact Number
                            </label>

                            <input
                                type="text"
                                name="contact_number"
                                class="form-control modern-input">

                        </div>

                    </div>

                </form>

            </div>

            <div class="modal-footer border-0">

                <button
                    type="button"
                    class="modal-btn modal-btn-secondary"
                    data-bs-dismiss="modal">

                    Cancel

                </button>

                <button
                    type="button"
                    class="modal-btn modal-btn-primary"
                    id="saveFacultyBtn">

                    Save Faculty

                </button>

            </div>

        </div>

    </div>

</div>

<!-- ASSIGN SUBJECT TO FACULTY MODAL -->

<div
    class="modal fade"
    id="subjectAssignmentModal"
    tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content modern-modal">

            <div class="modal-header">

                <h5 class="modal-title">

                    Subject Assignment

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <input
                    type="hidden"
                    id="assignmentFacultyId">

                <div class="row g-3 mb-4">

                    <div class="col-md-6">

                        <label class="modern-label">

                            Faculty

                        </label>

                        <input
                            id="assignmentFacultyName"
                            class="form-control modern-input"
                            readonly>

                    </div>

                    <div class="col-md-3">

                        <label class="modern-label">

                            School Year

                        </label>

                        <select
                            id="assignmentSchoolYear"
                            class="form-select modern-input">
                        </select>

                    </div>

                    <div class="col-md-3">

                        <label class="modern-label">

                            Trimester

                        </label>

                        <select
                            id="assignmentTrimester"
                            class="form-select modern-input">

                            <option value="1">1st Trimester</option>
                            <option value="2">2nd Trimester</option>
                            <option value="3">3rd Trimester</option>

                        </select>

                    </div>

                    <!-- NEW -->

                    <div class="col-md-6">

                        <label class="modern-label">

                            Course

                        </label>

                        <select
                            id="assignmentCourse"
                            class="form-select modern-input">

                            <option value="">
                                Select Course
                            </option>

                        </select>

                    </div>

                    <div class="col-md-6">

                        <label class="modern-label">

                            Year Level

                        </label>

                        <select
                            id="assignmentYearLevel"
                            class="form-select modern-input">

                            <option value="">
                                Select Year
                            </option>

                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>

                        </select>

                    </div>

                    <div class="col-md-6">

                        <label class="modern-label">

                            Section

                        </label>

                        <select
                            id="assignmentSection"
                            class="form-select modern-input">

                            <option value="">
                                Select Section
                            </option>

                        </select>

                    </div>

                </div>

                <div class="row">

                    <!-- AVAILABLE SUBJECTS -->

                    <div class="col-lg-7">

                        <h6 class="mb-3">

                            Available Subjects

                        </h6>

                        <div
                            id="assignmentSubjectsContainer"
                            class="assignment-container">

                        </div>

                    </div>

                    <!-- CURRENT LOAD -->

                    <div class="col-lg-5">

                        <h6 class="mb-3">

                            Current Teaching Load

                        </h6>

                        <div
                            id="facultyTeachingLoad"
                            class="assignment-container">

                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer border-0">

                <button
                    class="modal-btn modal-btn-secondary"
                    data-bs-dismiss="modal">

                    Cancel

                </button>

                <button
                    class="modal-btn modal-btn-primary"
                    id="saveSubjectAssignmentBtn">

                    Save Assignment

                </button>

            </div>

        </div>

    </div>

</div>