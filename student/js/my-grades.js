//=======================================
// HELPERS
//=======================================

function getTrimesterLabel(trimester) {
  const term = Number(trimester);

  if (term === 1) return "1st Trimester";
  if (term === 2) return "2nd Trimester";
  return "3rd Trimester";
}

function getGradeBadge(status) {
  if (status === "Completed") return "success";
  if (status === "Ongoing") return "warning";
  return "secondary";
}

//=======================================
// LOAD MY GRADES
//=======================================

function loadMyGrades() {
  Loader.show("Loading grades...");

  $.getJSON("ajax/get_my_grades.php", function (rows) {
    if (!rows.length) {
      $("#gradesContainer").html(`
        <div class="dashboard-card">
          <div class="text-center py-5 text-muted">
            No grades available.
          </div>
        </div>
      `);
      return;
    }

    const grouped = {};

    rows.forEach((row) => {
      const key =
        row.school_year + "|" + row.year_level + "|" + row.trimester;

      if (!grouped[key]) {
        grouped[key] = {
          school_year: row.school_year,
          year_level: row.year_level,
          trimester: row.trimester,
          subjects: [],
        };
      }

      grouped[key].subjects.push(row);
    });

    let html = `
      <div class="accordion" id="gradesAccordion">
    `;

    let counter = 0;

    Object.values(grouped).forEach((group) => {
      counter++;

      const trimesterText = getTrimesterLabel(group.trimester);

      let subjectRows = "";
      let totalUnits = 0;

      group.subjects.forEach((row) => {
        totalUnits += Number(row.units);

        const badge = getGradeBadge(row.grading_status);

        subjectRows += `
          <tr>
            <td>
              <strong>${row.subject_code}</strong>
              <div class="small text-muted">
                ${row.subject_name}
              </div>
            </td>
            <td>${row.units}</td>
            <td>${row.prelim_grade ?? "-"}</td>
            <td>${row.midterm_grade ?? "-"}</td>
            <td>${row.final_grade ?? "-"}</td>
            <td>${row.overall_grade ?? "-"}</td>
            <td>
              <span class="badge bg-${badge}">
                ${row.grading_status ?? "Pending"}
              </span>
            </td>
            <td>
              ${row.remarks ?? "-"}
            </td>
          </tr>
        `;
      });

      html += `
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button
              class="accordion-button ${counter > 1 ? "collapsed" : ""}"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#gradesGroup${counter}">
              ${group.school_year}
              |
              Year ${group.year_level}
              |
              ${trimesterText}
            </button>
          </h2>

          <div
            id="gradesGroup${counter}"
            class="accordion-collapse collapse ${counter === 1 ? "show" : ""}"
            data-bs-parent="#gradesAccordion">

            <div class="accordion-body">
              <div class="table-responsive">
                <table class="table align-middle">
                  <thead>
                    <tr>
                      <th>Subject</th>
                      <th>Units</th>
                      <th>Prelim</th>
                      <th>Midterm</th>
                      <th>Final</th>
                      <th>Overall</th>
                      <th>Status</th>
                      <th>Remarks</th>
                    </tr>
                  </thead>
                  <tbody>
                    ${subjectRows}
                  </tbody>
                </table>
              </div>

              <div class="fw-bold text-end">
                Total Units: ${totalUnits}
              </div>
            </div>
          </div>
        </div>
      `;
    });

    html += "</div>";

    $("#gradesContainer").html(html);
  }).always(function () {
    Loader.hide();
  });
}
