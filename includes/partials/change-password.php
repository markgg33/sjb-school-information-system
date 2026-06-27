    <!-- PASSWORD -->

    <div class="dashboard-card mt-4">

        <h5 class="mb-4">

            Change Password

        </h5>

        <form id="passwordForm">

            <div class="row g-3">

                <!-- CURRENT PASSWORD -->

                <div class="col-md-4">

                    <label class="modern-label">

                        Current Password

                    </label>

                    <div class="input-group">

                        <input
                            type="password"
                            id="currentPassword"
                            name="current_password"
                            class="form-control modern-input"
                            placeholder="Current Password">

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            onclick="togglePassword('currentPassword', this)">

                            <i class="fa-solid fa-eye"></i>

                        </button>

                    </div>

                </div>

                <!-- NEW PASSWORD -->

                <div class="col-md-4">

                    <label class="modern-label">

                        New Password

                    </label>

                    <div class="input-group">

                        <input
                            type="password"
                            id="newPassword"
                            name="new_password"
                            class="form-control modern-input"
                            placeholder="New Password">

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            onclick="togglePassword('newPassword', this)">

                            <i class="fa-solid fa-eye"></i>

                        </button>

                    </div>

                </div>

                <!-- CONFIRM PASSWORD -->

                <div class="col-md-4">

                    <label class="modern-label">

                        Confirm Password

                    </label>

                    <div class="input-group">

                        <input
                            type="password"
                            id="confirmPassword"
                            name="confirm_password"
                            class="form-control modern-input"
                            placeholder="Confirm Password">

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            onclick="togglePassword('confirmPassword', this)">

                            <i class="fa-solid fa-eye"></i>

                        </button>

                    </div>

                </div>

            </div>

            <button
                type="button"
                class="module-action-btn mt-4"
                id="changePasswordBtn">

                Update Password

            </button>

        </form>

    </div>