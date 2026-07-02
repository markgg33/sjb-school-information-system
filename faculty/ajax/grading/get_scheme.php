<?php

require_once '../../../includes/db.php';

$faculty_subject_id = $_GET['faculty_subject_id'] ?? 0;
$period = $_GET['period'] ?? 'Prelim';

/*
|--------------------------------------------------------------------------
| GET / CREATE SCHEME
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT id
FROM grading_schemes
WHERE faculty_subject_id = ?
AND period = ?
");

$stmt->execute([
    $faculty_subject_id,
    $period
]);

$scheme = $stmt->fetch(PDO::FETCH_ASSOC);

$components = [];
$totalWeight = 0;

if ($scheme) {

    $stmt = $pdo->prepare("
    SELECT *
    FROM grading_components
    WHERE grading_scheme_id = ?
    ORDER BY display_order
    ");

    $stmt->execute([
        $scheme['id']
    ]);

    $components = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($components as $row) {
        $totalWeight += $row['weight'];
    }
}

?>

<div class="row">

    <div class="col-lg-4">

        <div class="dashboard-card">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="mb-0">

                    <?= htmlspecialchars($period) ?> Components

                </h5>

                <button
                    class="btn btn-primary"
                    id="addComponentBtn"
                    data-period="<?= htmlspecialchars($period) ?>">

                    <i class="fa-solid fa-plus"></i>

                    Add

                </button>

            </div>

            <div id="gradingComponents">

                <?php if (!$components): ?>

                    <div class="text-muted">

                        No grading components yet.

                    </div>

                <?php else: ?>

                    <?php foreach ($components as $component): ?>

                        <div class="border rounded p-3 mb-2">

                            <div class="d-flex justify-content-between">

                                <div>

                                    <strong>

                                        <?= htmlspecialchars($component['component_name']) ?>

                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        <?= htmlspecialchars($component['component_type']) ?>

                                    </small>

                                </div>

                                <div class="text-end">

                                    <div>

                                        <?= $component['max_score'] ?> pts

                                    </div>

                                    <span class="badge bg-primary">

                                        <?= $component['weight'] ?>%

                                    </span>

                                </div>

                            </div>

                            <div class="mt-2 text-end">

                                <button
                                    class="btn btn-sm btn-outline-primary editComponentBtn"
                                    data-id="<?= $component['id'] ?>">

                                    <i class="fa-solid fa-pen"></i>

                                </button>

                                <button
                                    class="btn btn-sm btn-outline-danger deleteComponentBtn"
                                    data-id="<?= $component['id'] ?>">

                                    <i class="fa-solid fa-trash"></i>

                                </button>

                            </div>

                        </div>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

            <hr>

            <div class="fw-semibold">

                Total Weight

            </div>

            <div class="progress mt-2">

                <div
                    class="progress-bar <?= $totalWeight == 100 ? 'bg-success' : 'bg-warning' ?>"
                    style="width: <?= $totalWeight ?>%;">

                    <?= $totalWeight ?>%

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-8">

        <div class="dashboard-card">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    Gradebook

                </h5>   

            </div>

            <hr>

            <div id="gradebookContainer">

                <?php require 'get_gradebook.php'; ?>

            </div>

        </div>

    </div>

</div>