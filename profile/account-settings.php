<div class="container p-4">
    <h3 class="fw-bolder mb-5">Account Settings</h3>
    <div id="accAlertMessage"></div>
    <h4 class="fw-bold p2">Change Password</h4>
    <div class="col-lg-8">
        <form id="changePassForm">
            <div class="">
                <label for="currentPass" class="form-label">Current
                    Password</label>
                <div class="input-group">
                    <input type="password" class="form-control rounded-0 z-0 aa" id="currentPass" name="currentPass"
                        required>
                    <i class="bx bxs-show z-4 position-absolute end-0 pt-2 pe-3 mt-1" id="eyex"
                        onclick="showPass('currentPass', 'eyex')"></i>
                </div>
            </div>
            <div class="">
                <label for="newPass" class="form-label">New
                    Password</label>
                <div class="input-group">
                    <input type="password" class="form-control rounded-0 z-0 aa" id="newPass" name="newPass" required>
                    <i class="bx bxs-show z-4 position-absolute end-0 pt-2 pe-3 mt-1" id="eye2"
                        onclick="showPass('newPass', 'eye2')"></i>
                </div>
            </div>
            <div class="mb-3">
                <label for="confirmPass" class="form-label">Confirm
                    Password</label>
                <div class="input-group">
                    <input type="password" class="form-control rounded-0 z-0 aa" id="confirmPass" name="confirmPass"
                        required>
                    <i class="bx bxs-show z-4 position-absolute end-0 pt-2 pe-3 mt-1" id="eye3"
                        onclick="showPass('confirmPass', 'eye3')"></i>
                </div>
            </div>
            <input class="btn btn-primary mt-2 mb-3" type="text" id="changePassButton" value="Save Password">
        </form>
        <hr>
    </div>
</div>