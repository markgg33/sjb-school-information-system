function loadDashboardStats() {
  $.getJSON("ajax/get_dashboard_stats.php", function (response) {
    $("#totalSubjects").text(response.subjects);

    $("#totalStudents").text(response.students);

    $("#totalSections").text(response.sections);

    $("#pendingGrades").text(response.pending);
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
