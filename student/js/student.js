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

//=======================================
// LOAD PAGE
//=======================================

function loadPage(page) {
  $("#content-area").load("modules/" + page + ".php", function () {
    if (page === "dashboard") {
      loadDashboardStats();
    }

    if (page === "my-subjects") {
      loadMySubjects();
    }
    
    if (page === "profile-settings") {
      loadProfile();
    }

    // FUTURE

    if (page === "my-grades") {
      loadMyGrades();
    }

    if (page === "curriculum-progress") {
      loadCurriculumProgress();
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
