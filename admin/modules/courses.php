<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="mb-1">
            Courses
        </h2>

        <p class="text-muted mb-0">
            Manage academic programs
        </p>

    </div>

    <button
        class="module-action-btn"
        id="btnAddCourse">

        <div class="btn-icon">

            <i class="fa-solid fa-plus"></i>

        </div>

        <span>

            Add Course

        </span>

    </button>

</div>

<div class="dashboard-card">

    <div class="table-responsive">

        <table class="table align-middle">

            <thead>

                <tr>

                    <th>Code</th>
                    <th>Course Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th width="150">Actions</th>

                </tr>

            </thead>

            <tbody id="coursesTableBody">

            </tbody>

        </table>

    </div>

</div>

<div
    class="modal fade"
    id="courseModal"
    tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content modern-modal">

            <div class="modal-header">

                <h5 class="modal-title">
                    Add Course
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form id="courseForm">

                    <input
                        type="hidden"
                        id="courseId"
                        name="id">

                    <div class="mb-3">

                        <label class="form-label modern-label">
                            Course Code
                        </label>

                        <input
                            type="text"
                            class="form-control modern-input"
                            name="course_code"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label modern-label">
                            Course Name
                        </label>

                        <input
                            type="text"
                            class="form-control modern-input"
                            name="course_name"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label modern-label">
                            Description
                        </label>

                        <textarea
                            class="form-control modern-input"
                            name="description"></textarea>

                    </div>

                    <div class="mb-3">

                        <label class="form-label modern-label">
                            Status
                        </label>

                        <select
                            class="form-select modern-input"
                            name="status">

                            <option value="active">
                                Active
                            </option>

                            <option value="inactive">
                                Inactive
                            </option>

                        </select>

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
                    id="saveCourseBtn">

                    Save Course

                </button>

            </div>

        </div>

    </div>

</div>

<!-- SECTION MANAGEMENT MODAL -->

<div class="modal fade"
    id="sectionsModal"
    tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content modern-modal">

            <div class="modal-header">

                <h5 class="modal-title">

                    Manage Course Sections

                </h5>

                <button
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <!-- COURSE SECTIONS CONTAINER -->
                <div class="dashboard-card mb-4">

                    <h5 class="mb-4">

                        Add / Edit Section

                    </h5>

                    <form id="sectionForm">

                        <input
                            type="hidden"
                            id="sectionId"
                            name="id">

                        <input
                            type="hidden"
                            id="sectionCourseId"
                            name="course_id">

                        <div class="row g-3">

                            <div class="col-md-3">

                                <label class="modern-label">

                                    Year Level

                                </label>

                                <select
                                    class="form-select modern-input"
                                    name="year_level">

                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>

                                </select>

                            </div>

                            <div class="col-md-3">

                                <label class="modern-label">

                                    Section

                                </label>

                                <input
                                    class="form-control modern-input"
                                    name="section_name">

                            </div>

                            <div class="col-md-3">

                                <label class="modern-label">

                                    Display Order

                                </label>

                                <input
                                    type="number"
                                    class="form-control modern-input"
                                    name="display_order"
                                    value="1">

                            </div>

                            <div class="col-md-3">

                                <label class="modern-label">

                                    Status

                                </label>

                                <select
                                    class="form-select modern-input"
                                    name="status">

                                    <option value="active">

                                        Active

                                    </option>

                                    <option value="inactive">

                                        Inactive

                                    </option>

                                </select>

                            </div>

                        </div>

                        <div class="mt-4">

                            <button
                                type="button"
                                id="saveSectionBtn"
                                class="module-action-btn">

                                Save Section

                            </button>

                        </div>

                    </form>

                </div>

                <div id="courseSectionsContainer"></div>

            </div>

            <div class="modal-footer">

                <button
                    class="modal-btn modal-btn-secondary"
                    data-bs-dismiss="modal">

                    Close

                </button>

            </div>

        </div>

    </div>

</div>