//=======================================
// DYNAMIC PAGE LOADING
//=======================================

$(document).ready(function () {
  loadPage("dashboard");

  $('.sidebar-menu li[data-page="dashboard"]').addClass("active");

  $(".sidebar-menu li[data-page]").click(function () {
    $(".sidebar-menu li[data-page]").removeClass("active");

    $(this).addClass("active");

    loadPage($(this).data("page"));
  });
});

function loadPage(page) {
  $("#content-area").load("modules/" + page + ".php", function () {
    //FOR DASHBOARD
    if (page === "dashboard") {
      loadDashboardStats();
    }
    //FOR COURSES
    if (page === "courses") {
      loadCourses();
    }

    //FOR CURRICULUM
    if (page === "curriculum") {
      loadCurriculumCourses();
    }

    //FOR SUBJECTS
    if (page === "subjects") {
      loadCourseFilter();
      loadSubjects();
    }

    //FOR STUDENTS
    if (page === "students") {
      loadStudentCourses();
      loadStudents();
    }

    // FOR FACULTY
    if (page === "faculty") {
      loadFacultyCourses();
      loadFaculty();
    }

    // FOR PROFILE SETTINGS
    if (page === "profile-settings") {
      loadProfile();
    }

    // FOR ENROLLMENT
    if (page === "enrollment") {
      loadEnrollmentCourses();
      loadEnrollments();
    }

    if (page === "enrollment-create") {
      loadEnrollmentCourses();
      loadEnrollmentStudentsPage();
    }

    if (page === "enrollment-details") {
      loadEnrollmentDetails();
    }

    if (page === "system-settings") {
      loadRecentActivity();
    }
  });
}

//=======================================
// RECENT ACTIVITY
//=======================================

function loadRecentActivity() {
  $.getJSON("ajax/get_recent_activity.php", function (rows) {
    let html = "";

    if (!rows.length) {
      html = `
        <div class="text-muted text-center py-4">
            No recent activity.
        </div>
      `;
    } else {
      rows.forEach(function (row) {
        const fullName = [row.first_name, row.middle_name, row.last_name]
          .filter(Boolean)
          .join(" ");

        html += `

<div class="activity-item border-bottom py-3">

    <div class="d-flex justify-content-between">

        <div>

            <div class="fw-semibold">

                ${fullName}

            </div>

            <div class="text-primary">

                ${row.activity}

            </div>

            <div class="text-muted small">

                ${row.description}

            </div>

        </div>

        <small class="text-secondary text-nowrap">

            ${row.created_at}

        </small>

    </div>

</div>

`;
      });
    }

    $("#recentActivity").html(html);
  });
}

//=======================================
// LOAD STATS
//=======================================

function loadDashboardStats() {
  $.getJSON("ajax/get_dashboard_stats.php", function (response) {
    $("#totalStudents").text(response.students);
    $("#totalFaculty").text(response.faculty);
    $("#totalCourses").text(response.courses);
    $("#totalSubjects").text(response.subjects);
  });
}

/*function loadRecentEnrollments() {
  $.getJSON("ajax/get_recent_enrollments.php", function (rows) {
    let html = "";

    if (!rows.length) {
      html = `
          <div class="text-muted">
            No recent activity.
          </div>
        `;
    }

    rows.forEach((r) => {
      html += `
          <div class="border-bottom py-2">

            <strong>
              ${r.last_name},
              ${r.first_name}
            </strong>

            <div class="small text-muted">

              Year ${r.year_level}
              • Trimester ${r.trimester}
              • ${r.school_year}

            </div>

          </div>
        `;
    });

    $("#recentEnrollments").html(html);
  });
}*/

//=======================================
// MOBILE SIDEBAR
//=======================================

$(document).on("click", "#menuToggle", function () {
  $(".sidebar").addClass("show");

  $("#sidebarOverlay").addClass("show");
});

$(document).on("click", "#sidebarClose, #sidebarOverlay", function () {
  $(".sidebar").removeClass("show");

  $("#sidebarOverlay").removeClass("show");
});

//=======================================
// LOGOUT
//=======================================

$(document).on("click", "#logoutBtn", function (e) {
  e.preventDefault();

  AlertService.confirm("Logout", "Are you sure you want to logout?").then(
    (result) => {
      if (result.isConfirmed) {
        window.location.href = "../auth/logout.php";
      }
    },
  );
});

//=======================================
// QUICK ACTION
//=======================================
$(document).on("click", ".dashboardAction", function () {
  loadPage($(this).data("page"));
});
