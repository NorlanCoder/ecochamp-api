@extends("dashboard.layout")

@section('content')

    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Utilisateurs</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('web.dashboard')}}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Utilisateur Simple</li>
                    </ol>
                </nav>
            </div>
            
        </div>
        <!--end breadcrumb-->
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h5 class="mb-0">Listes des Simples Utilisateurs</h5>
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
                            @foreach($ativits as $item)
                                <tr>
                                    <td>
                                        <img class="img-profile rounded-circle img-fluid" src="{{asset($item->url_profil ?? 'assets/images/profile.png')}}" alt="profile" style="max-width: 50px; max-height: 50px;">
                                    </td>
                                    <td>{{$item->fullname}}</td>
                                    <td>{{$item->email}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary">Action</button>
                                            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	
                                                <span class="visually-hidden">Action List</span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                                <!-- Profile -->
                                                <a href="{{route('web.user.profile', $item->id)}}" class="dropdown-item d-flex align-items-center">
                                                    <button class="btn btn-warning btn-circle btn-sm">
                                                        <i class="bx bx-user"></i>
                                                    </button>
                                                    <span class="ml-2">Profile</span>
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <!-- Delete -->
                                                <a href="#" class="dropdown-item d-flex align-items-center" data-toggle="modal" data-target="#DeleteModal{{ $item->id }}">
                                                    <button class="btn btn-danger btn-circle btn-sm">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                    <span class="ml-2">Delete</span>
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <!-- Bloquer -->
                                                <a href="#" class="dropdown-item d-flex align-items-center" data-toggle="modal" data-target="#BlockModal{{ $item->id }}">
                                                    <button class="btn btn-danger btn-circle btn-sm">
                                                        <i class="bx bx-block"></i>
                                                    </button>
                                                    <span class="ml-2">Bloquer</span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $ativits->links() }}
                </div>
            </div>
        </div>
        
    </div>


@endsection