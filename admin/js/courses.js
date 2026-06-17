//=======================================
// LOAD COURSES
//=======================================

function loadCourses() {
  $.get("ajax/get_courses.php", function (response) {
    const courses = JSON.parse(response);

    let html = "";

    courses.forEach((course) => {
      html += `
                    <tr>
                        <td>${course.course_code}</td>
                        <td>${course.course_name}</td>
                        <td>${course.description ?? ""}</td>

                        <td>

                            <span class="badge bg-${
                              course.status === "active"
                                ? "success"
                                : "warning"
                            } text-uppercase">

                                ${course.status}

                            </span>

                        </td>

<td>

    <button
        class="btn btn-sm btn-outline-primary editCourseBtn"
        data-course='${JSON.stringify(course)}'>

        <i class="fa-solid fa-pencil"></i>

    </button>

    <button
        class="btn btn-sm btn-outline-danger deleteCourseBtn"
        data-id="${course.id}">

        <i class="fa-solid fa-trash"></i>

    </button>

</td>

                    </tr>
                `;
    });

    $("#coursesTableBody").html(html);
  });
}

//=======================================
// OPEN ADD COURSE MODAL
//=======================================

$(document).on("click", "#btnAddCourse", function () {
  $("#courseForm")[0].reset();

  $("#courseId").val("");

  $("#courseModal .modal-title").text("Add Course");

  const modal = new bootstrap.Modal(document.getElementById("courseModal"));

  modal.show();
});

//=======================================
// EDIT COURSE
//=======================================

$(document).on("click", ".editCourseBtn", function () {
  const course = $(this).data("course");

  $("#courseId").val(course.id);

  $('[name="course_code"]').val(course.course_code);

  $('[name="course_name"]').val(course.course_name);

  $('[name="description"]').val(course.description);

  $('[name="status"]').val(course.status);

  $("#courseModal .modal-title").text("Edit Course");

  new bootstrap.Modal(document.getElementById("courseModal")).show();
});

//=======================================
// SAVE COURSE
//=======================================

$(document).on("click", "#saveCourseBtn", function () {
  AlertService.saveConfirm("course").then((result) => {
    if (!result.isConfirmed) {
      return;
    }

    $.ajax({
      url: "ajax/add_course.php",
      type: "POST",
      data: $("#courseForm").serialize(),
      dataType: "json",

      success: function (response) {
        if (response.success) {
          Notification.success(response.message);

          bootstrap.Modal.getInstance(
            document.getElementById("courseModal"),
          ).hide();

          loadCourses();
        } else {
          Notification.error(response.message);
        }
      },

      error: function () {
        AlertService.error("Unable to connect to server.");
      },
    });
  });
});

//=======================================
// DELETE COURSE
//=======================================

$(document).on("click", ".deleteCourseBtn", function () {
  const id = $(this).data("id");

  AlertService.deleteConfirm("course").then((result) => {
    if (!result.isConfirmed) {
      return;
    }

    $.ajax({
      url: "ajax/delete_course.php",

      type: "POST",

      data: {
        id: id,
      },

      dataType: "json",

      success: function (response) {
        if (response.success) {
          Notification.success(response.message);

          loadCourses();
        } else {
          AlertService.error(response.message);
        }
      },

      error: function () {
        AlertService.error("Unable to connect to server.");
      },
    });
  });
});
