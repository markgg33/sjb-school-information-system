<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="mb-1">Subjects</h2>

        <p class="text-muted mb-0">
            Manage academic subjects
        </p>
    </div>

    <button
        class="module-action-btn"
        id="btnAddSubject">

        <div class="btn-icon">
            <i class="fa-solid fa-plus"></i>
        </div>

        <span>Add Subject</span>

    </button>

</div>

<div class="dashboard-card">

    <div class="row mb-3 g-3 align-items-end">

        <div class="col-md-3">
            <select id="courseFilter" class="form-select modern-input">
                <option value="">All Courses</option>
            </select>
        </div>

        <div class="col-md-2">
            <select id="yearFilter" class="form-select modern-input">
                <option value="">All Years</option>
                <option value="1">Year 1</option>
                <option value="2">Year 2</option>
                <option value="3">Year 3</option>
            </select>
        </div>

        <div class="col-md-2">
            <select id="trimesterFilter" class="form-select modern-input">
                <option value="">All Trimesters</option>
                <option value="1">Trimester 1</option>
                <option value="2">Trimester 2</option>
                <option value="3">Trimester 3</option>
            </select>
        </div>

        <div class="col-md-4">
            <input
                type="text"
                id="subjectSearch"
                class="form-control modern-input"
                placeholder="Search subject...">
        </div>

        <div class="col-12 col-md-1">

            <button
                class="module-subject-btn w-100 justify-content-center"
                id="applyFiltersBtn">

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
                    <th>Code</th>
                    <th>Subject Name</th>
                    <th>Units</th>
                    <th>Status</th>
                    <th>Used In</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody id="subjectsTableBody"></tbody>

        </table>

        <div
            id="subjectsPagination"
            class="d-flex justify-content-center mt-3">
        </div>

    </div>

</div>

<div
    class="modal fade"
    id="subjectModal"
    tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content modern-modal">

            <div class="modal-header">

                <h5 class="modal-title">
                    Add Subject
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form id="subjectForm">

                    <input
                        type="hidden"
                        name="id"
                        id="subjectId">

                    <div class="mb-3">

                        <label class="form-label modern-label">
                            Subject Code
                        </label>

                        <input
                            type="text"
                            class="form-control modern-input"
                            name="subject_code"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label modern-label">
                            Subject Name
                        </label>

                        <input
                            type="text"
                            class="form-control modern-input"
                            name="subject_name"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label modern-label">
                            Units
                        </label>

                        <input
                            type="number"
                            class="form-control modern-input"
                            name="units"
                            min="1"
                            max="6"
                            value="3"
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
                    id="saveSubjectBtn">

                    Save Subject

                </button>

            </div>

        </div>

    </div>

</div>