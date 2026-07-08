function loadDashboardStats() {
  $.getJSON("ajax/get_dashboard_stats.php", function (response) {
    $("#totalSubjects").text(response.subjects);

    $("#totalStudents").text(response.students);

    $("#totalSections").text(response.sections);

    $("#gradeProgress").text(response.progress);
  });
}

function loadRecentActivity() {
  $.getJSON("ajax/get_recent_activity.php", function (rows) {
    let html = "";

    if (!rows.length) {
      html = `
                    <div class="text-muted">
                        No recent activity.
                    </div>
                `;
    } else {
      rows.forEach(function (row) {
        html += `

                        <div class="border-bottom py-2">

                            <strong>

                                ${row.activity}

                            </strong>

                            <div class="small text-muted">

                                ${row.description}

                            </div>

                            <small class="text-secondary">

                                ${row.created_at}

                            </small>

                        </div>

                    `;
      });
    }

    $("#recentActivity").html(html);
  });
}

//=======================================
// LOAD DASHBOARD SUBJECTS
//=======================================

function loadDashboardSubjects() {
  $.getJSON("ajax/get_dashboard_subjects.php", function (rows) {
    let html = "";

    if (!rows.length) {
      html = `
        <div class="text-muted">
            No assigned subjects.
        </div>
      `;
    } else {
      rows.forEach(function (row) {
        html += `

        <div class="col-md-6">
        
        <div class="dashboard-card dashboard-subject-card h-100">
        
        <div class="d-flex justify-content-between">
        
        <div>
        
        <h6 class="mb-1">
        
        ${row.subject_code}
        
        </h6>
        
        <div class="fw-semibold">
        
        ${row.subject_name}
        
        </div>
        
        <div class="small text-muted mt-2">
        
        ${row.course_code}
        
        •
        
        Year ${row.year_level}
        
        ${row.section_name ? " • " + row.section_name : ""}
        
        </div>
        
        <div class="small text-muted">
        
        ${row.school_year}
        
        •
        
        Trimester ${row.trimester}
        
        </div>
        
        </div>
        
        <div>
        
        <span class="badge bg-primary">
        
        ${row.students}
        
        </span>
        
        </div>
        
        </div>
        
        <hr>
        
        <button
        
        class="module-action-btn module-action-btn-sm openWorkspaceBtn"
        
       data-faculty-subject="${row.faculty_subject_id}"
        
        data-subject="${row.subject_id}"
        
        data-course="${row.course_id}"
        
        data-year="${row.year_level}"
        
        data-section="${row.section_id}"
        
        data-section-name="${row.section_name ?? ""}"
        
        data-school="${row.school_year}"
        
        data-trimester="${row.trimester}"
        
        data-subject-code="${row.subject_code}"
        
        data-subject-name="${row.subject_name}"
        
        data-course-code="${row.course_code}"
        
        data-course-name="${row.course_name}">
        
        <div class="btn-icon">
        
        <i class="fa-solid fa-arrow-right"></i>
        
        </div>
        
        <span>
        
        Open
        
        </span>
        
        </button>
        
        </div>
        
        </div>
        
        `;
      });
    }

    $("#dashboardSubjects").html(html);
  });
}
