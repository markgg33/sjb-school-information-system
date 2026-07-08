<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="mb-1">
            Students
        </h2>

        <p class="text-muted mb-0">
            Manage student records and accounts
        </p>

    </div>

    <button
        class="module-action-btn"
        id="btnAddStudent">

        <div class="btn-icon">
            <i class="fa-solid fa-user-plus"></i>
        </div>

        <span>
            Add Student
        </span>

    </button>

</div>

<div class="dashboard-card">

    <div class="row mb-3 g-3 align-items-end">

        <div class="col-md-2">

            <select
                id="studentStatusFilter"
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

                <option value="graduated">
                    Graduated
                </option>

                <option value="dropped">Dropped</option>

            </select>

        </div>

        <div class="col-md-2">

            <select
                id="studentTypeFilter"
                class="form-select modern-input">

                <option value="">
                    All Types
                </option>

                <option value="new">
                    New
                </option>

                <option value="old">
                    Old
                </option>

                <option value="transferee">
                    Transferee
                </option>

                <option value="returnee">
                    Returnee
                </option>

            </select>

        </div>

        <div class="col-md-3">

            <select
                id="studentCourseFilter"
                class="form-select modern-input">

                <option value="">
                    All Courses
                </option>

            </select>

        </div>

        <div class="col-md-4">

            <input
                type="text"
                id="studentSearch"
                class="form-control modern-input"
                placeholder="Search student...">

        </div>

        <div class="col-md-1">

            <button
                class="module-subject-btn w-100 justify-content-center"
                id="applyStudentFiltersBtn">

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

                    <th>Student No.</th>

                    <th>Name</th>

                    <th>Course</th>

                    <th>Section</th>

                    <th>Gender</th>

                    <th>Type</th>

                    <th>Status</th>

                    <th width="140">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody id="studentsTableBody">

            </tbody>

        </table>

        <div
            id="studentsPagination"
            class="d-flex justify-content-center mt-3">
        </div>

    </div>

</div>

<!--STUDENT MODAL-->

<div
    class="modal fade"
    id="studentModal"
    tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content modern-modal">

            <div class="modal-header">

                <h5 class="modal-title">
                    Add Student
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form id="studentForm">

                    <input
                        type="hidden"
                        id="studentId"
                        name="id">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="modern-label">
                                Student Number
                            </label>

                            <input
                                type="text"
                                name="student_number"
                                class="form-control modern-input"
                                required>

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

                        <div class="col-md-4">

                            <label class="modern-label">
                                Course
                            </label>

                            <select
                                name="course_id"
                                id="studentCourse"
                                class="form-select modern-input"
                                required>
                            </select>

                        </div>

                        <div class="col-md-4">

                            <label class="modern-label">
                                Student Type
                            </label>

                            <select
                                name="student_type"
                                class="form-select modern-input">

                                <option value="new">New</option>
                                <option value="old">Old</option>
                                <option value="transferee">Transferee</option>
                                <option value="returnee">Returnee</option>

                            </select>

                        </div>

                        <div class="col-md-4">

                            <label class="modern-label">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select modern-input">

                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="graduated">Graduated</option>
                                <option value="dropped">Dropped</option>

                            </select>

                        </div>

                        <div class="col-md-4">

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

                        <div class="col-md-4">

                            <label class="modern-label">
                                Birth Date
                            </label>

                            <input
                                type="date"
                                name="birth_date"
                                class="form-control modern-input">

                        </div>

                        <div class="col-md-4">

                            <label class="modern-label">
                                Contact Number
                            </label>

                            <input
                                type="text"
                                name="contact_number"
                                class="form-control modern-input">

                        </div>

                        <div class="col-12">

                            <label class="modern-label">
                                Address
                            </label>

                            <textarea
                                name="address"
                                class="form-control modern-input">
                            </textarea>

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
                    id="saveStudentBtn">

                    Save Student

                </button>

            </div>

        </div>

    </div>

</div>

<!-- ==========================================
     OFFICIAL GRADE REPORT
========================================== -->

<div class="modal fade"
    id="officialGradeModal"
    tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content modern-modal">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="fa-solid fa-print me-2"></i>

                    Official Grade Report

                </h5>

                <button
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <input
                    type="hidden"
                    id="printStudentId">

                <label class="modern-label">

                    Select Academic Record

                </label>

                <select
                    id="printEnrollment"
                    class="form-select modern-input">

                    <option value="">

                        Loading...

                    </option>

                </select>

            </div>

            <div class="modal-footer border-0">

                <button
                    class="modal-btn modal-btn-secondary"
                    data-bs-dismiss="modal">

                    Cancel

                </button>

                <button
                    class="modal-btn modal-btn-primary"
                    id="confirmPrintOfficialGrades">

                    Print

                </button>

            </div>

        </div>

    </div>

</div>