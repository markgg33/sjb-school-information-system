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

      loadRecentActivity();
    }

    //FOR SUBJECTS
    if (page === "my-subjects") {
      loadMySubjects();
    }

    if (page === "subject-workspace") {
      loadSubjectWorkspace();
    }

    if (page === "profile-settings") {
      loadProfile();
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
