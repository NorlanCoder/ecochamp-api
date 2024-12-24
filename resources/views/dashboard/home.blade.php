@extends("dashboard.layout")

@section('content')

    <div class="page-content">
        <div class="card shadow-none bg-transparent">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <h4 class="mb-3 mb-md-0">Audience Overview</h4>
                    </div>
                    <div class="col-md-9">
                        <form class="float-md-end">
                            <div class="row row-cols-md-auto g-lg-3">
                                <label for="inputFromDate" class="col-md-2 col-form-label text-md-end">From Date</label>
                                <div class="col-md-4">
                                    <input type="date" class="form-control" id="inputFromDate">
                                </div>
                                <label for="inputToDate" class="col-md-2 col-form-label text-md-end">To Date</label>
                                <div class="col-md-4">
                                    <input type="date" class="form-control" id="inputToDate">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="chart1"></div>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0">Total Users</p>
                                <h5 class="mb-0">{{$total_users}}</h5>
                            </div>
                            <div class="dropdown ms-auto">
                                <a href="javascript:;" class="dropdown-toggle-nocaret more-options dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <i class='bx bx-dots-vertical-rounded'></i>
                                </a>
                                <!-- <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a>
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                    </li>
                                </ul> -->
                            </div>
                        </div>
                        <!-- <div class="" id="chart2"></div> -->
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0">Total ONG</p>
                                <h5 class="mb-0">{{$total_ongs}}</h5>
                            </div>
                            <div class="dropdown ms-auto">
                                <a href="javascript:;" class="dropdown-toggle-nocaret more-options dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <i class='bx bx-dots-vertical-rounded'></i>
                                </a>
                                <!-- <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a>
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                    </li>
                                </ul> -->
                            </div>
                        </div>
                        <!-- <div class="" id="chart3"></div> -->
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0">Total Alertes</p>
                                <h5 class="mb-0">{{$total_alerts}}</h5>
                            </div>
                            <div class="dropdown ms-auto">
                                <a href="javascript:;" class="dropdown-toggle-nocaret more-options dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <i class='bx bx-dots-vertical-rounded'></i>
                                </a>
                                <!-- <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a>
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                    </li>
                                </ul> -->
                            </div>
                        </div>
                        <!-- <div class="" id="chart4"></div> -->
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0">Total Evennement</p>
                                <h5 class="mb-0">{{$total_evennements}}</h5>
                            </div>
                            <div class="dropdown ms-auto">
                                <a href="javascript:;" class="dropdown-toggle-nocaret more-options dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <i class='bx bx-dots-vertical-rounded'></i>
                                </a>
                                <!-- <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a>
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                    </li>
                                </ul> -->
                            </div>
                        </div>
                        <!-- <div class="" id="chart5"></div> -->
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2">
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div id="chart6"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div id="chart7"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!--end row-->
        <div class="card radius-10">
            <div class="card-body">
                <div class="table-responsive lead-table">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Potential Leads</th>
                                <th>Diposit</th>
                                <th>Progress</th>
                                <th>Last Update</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
                                        </div>
                                        <div class="">
                                            <img src="assets/images/avatars/avatar-1.png" class="rounded-circle" width="40" height="40" alt="">
                                        </div>
                                        <div class="ms-2">
                                            <h6 class="mb-0 font-14">Ronald Waters</h6>
                                            <p class="mb-0 font-13 text-secondary">Lead Designers</p>
                                        </div>
                                    </div>
                                </td>
                                <td>$89,620</td>
                                <td class=" w-25">
                                    <div class="progress radius-10" style="height:5px">
                                        <div class="progress-bar bg-primary w-75" role="progressbar"></div>
                                    </div>
                                </td>
                                <td>14 Oct 2020</td>
                                <td>
                                    <div class="badge rounded-pill bg-primary w-100">In Progress</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
                                        </div>
                                        <div class="">
                                            <img src="assets/images/avatars/avatar-2.png" class="rounded-circle" width="40" height="40" alt="">
                                        </div>
                                        <div class="ms-2">
                                            <h6 class="mb-0 font-14">David Buckley</h6>
                                            <p class="mb-0 font-13 text-secondary">Lead Designers</p>
                                        </div>
                                    </div>
                                </td>
                                <td>$38,520</td>
                                <td class=" w-25">
                                    <div class="progress radius-10" style="height:5px">
                                        <div class="progress-bar bg-danger w-50" role="progressbar"></div>
                                    </div>
                                </td>
                                <td>15 Oct 2020</td>
                                <td>
                                    <div class="badge rounded-pill bg-danger w-100">Cancelled</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
                                        </div>
                                        <div class="">
                                            <img src="assets/images/avatars/avatar-3.png" class="rounded-circle" width="40" height="40" alt="">
                                        </div>
                                        <div class="ms-2">
                                            <h6 class="mb-0 font-14">James Caviness</h6>
                                            <p class="mb-0 font-13 text-secondary">Lead Designers</p>
                                        </div>
                                    </div>
                                </td>
                                <td>$63,820</td>
                                <td class=" w-25">
                                    <div class="progress radius-10" style="height:5px">
                                        <div class="progress-bar bg-success w-100" role="progressbar"></div>
                                    </div>
                                </td>
                                <td>16 Oct 2020</td>
                                <td>
                                    <div class="badge rounded-pill bg-success w-100">Completed</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
                                        </div>
                                        <div class="">
                                            <img src="assets/images/avatars/avatar-4.png" class="rounded-circle" width="40" height="40" alt="">
                                        </div>
                                        <div class="ms-2">
                                            <h6 class="mb-0 font-14">John Roman</h6>
                                            <p class="mb-0 font-13 text-secondary">Lead Designers</p>
                                        </div>
                                    </div>
                                </td>
                                <td>$97,420</td>
                                <td class=" w-25">
                                    <div class="progress radius-10" style="height:5px">
                                        <div class="progress-bar bg-primary w-50" role="progressbar"></div>
                                    </div>
                                </td>
                                <td>18 Oct 2020</td>
                                <td>
                                    <div class="badge rounded-pill bg-primary w-100">In Progress</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
                                        </div>
                                        <div class="">
                                            <img src="assets/images/avatars/avatar-7.png" class="rounded-circle" width="40" height="40" alt="">
                                        </div>
                                        <div class="ms-2">
                                            <h6 class="mb-0 font-14">Johnny Seitz</h6>
                                            <p class="mb-0 font-13 text-secondary">Lead Designers</p>
                                        </div>
                                    </div>
                                </td>
                                <td>$48,360</td>
                                <td class=" w-25">
                                    <div class="progress radius-10" style="height:5px">
                                        <div class="progress-bar bg-danger w-50" role="progressbar"></div>
                                    </div>
                                </td>
                                <td>22 Oct 2020</td>
                                <td>
                                    <div class="badge rounded-pill bg-danger w-100">Cancelled</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
                                        </div>
                                        <div class="">
                                            <img src="assets/images/avatars/avatar-8.png" class="rounded-circle" width="40" height="40" alt="">
                                        </div>
                                        <div class="ms-2">
                                            <h6 class="mb-0 font-14">Pauline Bird</h6>
                                            <p class="mb-0 font-13 text-secondary">Lead Designers</p>
                                        </div>
                                    </div>
                                </td>
                                <td>$74,620</td>
                                <td class=" w-25">
                                    <div class="progress radius-10" style="height:5px">
                                        <div class="progress-bar bg-success w-100" role="progressbar"></div>
                                    </div>
                                </td>
                                <td>24 Oct 2020</td>
                                <td>
                                    <div class="badge rounded-pill bg-success w-100">Completed</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection