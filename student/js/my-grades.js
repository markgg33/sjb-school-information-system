//=======================================
// LOAD MY GRADES
//=======================================

function loadMyGrades() {
  $.getJSON("ajax/get_my_grades.php", function (rows) {
    let html = "";

    if (!rows.length) {
      $("#gradesContainer").html(`
                    <div class="dashboard-card">

                        <div class="text-center py-5">

                            No grades available.

                        </div>

                    </div>
                `);

      return;
    }

    let currentGroup = "";

    rows.forEach((row) => {
      const group = row.school_year + "-" + row.trimester;

      if (group !== currentGroup) {
        if (currentGroup !== "") {
          html += `
                                </tbody>
                            </table>
                        </div>
                    `;
        }

        currentGroup = group;

        const trimester =
          row.trimester == 1
            ? "1st Trimester"
            : row.trimester == 2
              ? "2nd Trimester"
              : "3rd Trimester";

        html += `

                    <div class="dashboard-card mb-4">

                        <h5>

                            ${row.school_year}

                            •

                            Year ${row.year_level}

                            •

                            ${trimester}

                        </h5>

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

                            </tr>

                        </thead>

                        <tbody>

                    `;
      }

      let badge = "secondary";

      if (row.grading_status == "Ongoing") badge = "warning";

      if (row.grading_status == "Completed") badge = "success";

      html += `

                <tr>

                    <td>

                        <strong>

                            ${row.subject_code}

                        </strong>

                        <div class="small text-muted">

                            ${row.subject_name}

                        </div>

                    </td>

                    <td>

                        ${row.units}

                    </td>

                    <td>

                        ${row.prelim_grade ?? "-"}

                    </td>

                    <td>

                        ${row.midterm_grade ?? "-"}

                    </td>

                    <td>

                        ${row.final_grade ?? "-"}

                    </td>

                    <td>

                        ${row.overall_grade ?? "-"}

                    </td>

                    <td>

                        <span class="badge bg-${badge}">

                            ${row.grading_status}

                        </span>

                    </td>

                </tr>

                `;
    });

    html += `
                    </tbody>
                </table>

                </div>

            </div>
            `;

    $("#gradesContainer").html(html);
  });
}
