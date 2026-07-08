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
<div class="border rounded p-3 mb-3">

<div class="d-flex justify-content-between">

<div>

<h6 class="mb-1">

${row.subject_code}

</h6>

<div class="text-muted">

${row.subject_name}

</div>

</div>

<span class="badge bg-primary">

${row.students} Students

</span>

</div>

<div class="small text-secondary mt-2">

${row.course_code}

•

Year ${row.year_level}

${row.section_name ? " • " + row.section_name : ""}

</div>

</div>
`;
      });
    }

    $("#dashboardSubjects").html(html);
  });
}
