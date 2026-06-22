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

    // FOR ENROLLMENT

    if (page === "enrollment") {
      loadEnrollments();
    }

    if (page === "enrollment-create") {
      loadEnrollmentCourses();
      loadEnrollmentStudentsPage();
    }

    if (page === "enrollment-details") {
      loadEnrollmentDetails();
    }
  });
}

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
