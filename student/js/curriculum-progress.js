//=======================================
// LOAD CURRICULUM PROGRESS
//=======================================

function loadCurriculumProgress() {
  $.getJSON("ajax/get_curriculum_progress.php", function (response) {
    $("#completedCurriculumSubjects").text(response.completed);

    $("#currentCurriculumSubjects").text(response.current);

    $("#remainingCurriculumSubjects").text(response.remaining);

    $("#curriculumProgressPercent").text(response.progress + "%");

    $("#curriculumProgressBar").css("width", response.progress + "%");

    window.curriculumRoadmap = response.roadmap;

    let html = "";

    response.roadmap.forEach((year) => {
      html += `

    <div class="mb-4">

      <h4 class="mb-3">

        Year ${year.year}

      </h4>

      <div class="row g-3">

  `;

      year.trimesters.forEach((trimester) => {
        const completed = trimester.subjects.filter(
          (s) => s.status === "completed",
        ).length;

        const current = trimester.subjects.filter(
          (s) => s.status === "current",
        ).length;

        const remaining = trimester.subjects.filter(
          (s) => s.status === "remaining",
        ).length;

        const units = trimester.subjects.reduce(
          (sum, s) => sum + Number(s.units),
          0,
        );

        const trimesterText =
          trimester.trimester == 1
            ? "1st Trimester"
            : trimester.trimester == 2
              ? "2nd Trimester"
              : "3rd Trimester";

        let border = "secondary";
        let badge = "Not Started";

        if (completed === trimester.subjects.length) {
          border = "success";
          badge = "Completed";
        } else if (current > 0) {
          border = "warning";
          badge = "Current";
        }

        html += `

      <div class="col-lg-4">

        <div class="dashboard-card border-start border-4 border-${border}">

          <div class="d-flex justify-content-between align-items-start">

            <div>

              <h6>

                ${trimesterText}

              </h6>

              <div class="small text-muted">

                ${units} Units

              </div>

              <div class="small text-muted">

                ${trimester.subjects.length} Subjects

              </div>

            </div>

            <span class="badge bg-${border}">

              ${badge}

            </span>

          </div>

          <hr>

          <div class="small">

            ✔ Completed:

            <strong>${completed}</strong>

            <br>

            🟡 Current:

            <strong>${current}</strong>

            <br>

            ⚪ Remaining:

            <strong>${remaining}</strong>

          </div>

          <button
              class="btn btn-outline-primary btn-sm mt-3 w-100 curriculumDetailsBtn"
              data-year="${year.year}"
              data-trimester="${trimester.trimester}">

              View Subjects

          </button>

        </div>

      </div>

    `;
      });

      html += `

      </div>

    </div>

  `;
    });

    $("#curriculumRoadmap").html(html);
  });
}

//=======================================
// SUBJECT MODAL
//=======================================

$(document).on("click", ".curriculumDetailsBtn", function () {
  const year = Number($(this).data("year"));

  const trimester = Number($(this).data("trimester"));

  const yearData = window.curriculumRoadmap.find((y) => y.year === year);

  const term = yearData.trimesters.find((t) => t.trimester === trimester);

  $("#curriculumModalTitle").text(
    `Year ${year} • ${
      trimester == 1 ? "1st" : trimester == 2 ? "2nd" : "3rd"
    } Trimester`,
  );

  let html = "";

  term.subjects.forEach((subject) => {
    let badge = "secondary";

    if (subject.status === "completed") badge = "success";

    if (subject.status === "current") badge = "warning";

    html += `

      <div class="d-flex justify-content-between align-items-center border-bottom py-2">

        <div>

          <strong>

            ${subject.subject_code}

          </strong>

          <div class="small text-muted">

            ${subject.subject_name}

          </div>

        </div>

        <span class="badge bg-${badge}">

          ${subject.status}

        </span>

      </div>

    `;
  });

  $("#curriculumModalBody").html(html);

  new bootstrap.Modal(
    document.getElementById("curriculumSubjectsModal"),
  ).show();
});
