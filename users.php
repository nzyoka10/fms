<?php
// Include functions file
include 'includes/functions.php';


// Include HTML templates
include './includes/header.php';
include './includes/sidebar.php';
?>

<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">
    <h4 class="mt-4">Account Users</h4>
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard / Users / Admin</li>
    </ol>

    <!-- Table to display account users -->
    <div class="table mt-4">
        <table class="">
            <thead>
                <tr>
                    <th>Sn#</th>
                    <th>Date created</th>
                    <th>Full name</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>

            <tbody>
                <tr class="me-3">
                    <td>1</td>
                    <td>2024-09-28 16:09:02</td>
                    <td>admin@app.com</td>
                    <td>Adminstrator</td>
                </tr>
            </tbody>
        </table>
    </div>











</main>

<!-- Footer -->
<?php include './includes/footer.php'; ?>