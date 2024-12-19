@extends("dashboard.layout")

@section('content')

    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Participation</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('web.dashboard')}}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Financement</li>
                    </ol>
                </nav>
            </div>
            
        </div>
        <!--end breadcrumb-->
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h5 class="mb-0">Listes des financements</h5>
                </div>
                <hr/>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Profile</th>
                                <th>Nom et Prenom</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($financements as $item)
                                <tr>
                                    <td>
                                        <img class="img-profile rounded-circle img-fluid" src="{{asset($item->url_profil ?? 'assets/images/profile.png')}}" alt="profile" style="max-width: 50px; max-height: 50px;">
                                    </td>
                                    <td>{{$item->user->fullname}}</td>
                                    <td>{{$item->user->email}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary">Action</button>
                                            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	
                                                <span class="visually-hidden">Action List</span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                                <!-- Profile -->
                                                <a href="{{route('web.user.profile', $item->user->id)}}" class="dropdown-item d-flex align-items-center">
                                                    <button class="btn btn-warning btn-circle btn-sm">
                                                        <i class="bx bx-user"></i>
                                                    </button>
                                                    <span class="ml-2">Profile</span>
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <!-- Delete -->
                                                <a href="#" class="dropdown-item d-flex align-items-center" data-toggle="modal" data-target="#DeleteModal{{ $item->user->id }}">
                                                    <button class="btn btn-primary btn-circle btn-sm">
                                                        <i class="bx bx-plus"></i>
                                                    </button>
                                                    <span class="ml-2">Voir</span>
                                                </a>
                                               
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $financements->links() }}
                </div>
            </div>
        </div>
        
    </div>


@endsection