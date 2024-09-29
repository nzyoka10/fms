<!-- *** Calendar Modal -->
<div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title d-flex align-items-center" id="calendarModalLabel">
                    <span data-feather="calendar" class="me-2"></span>Calendar
                </h5>
                <button type="button" class="btn bg-light text-light btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <div class="d-flex justify-content-center">
                    <div id="calendar" class="border p-3 shadow-sm" style="width: 100%; min-height: 400px;">
                        <!-- Calendar will be injected here -->
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- *** End of Calendar Modal -->

<!-- Title section -->

<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom-none">

        <!-- Page heading -->
        <div class="text-center mt-3">
            <h4 class="text-muted">FMS Dashboard</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Dashboard / Main</li>
            </ol>
        </div>

        <!-- calender button  -->
        <div class="btn-toolbar mb-md-0">
            <button type="button" id="calender" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#calendarModal">
                <span data-feather="calendar"></span>
                This week
            </button>
        </div>

    </div>