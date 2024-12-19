@extends("dashboard.layout")

@section('content')

    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Retrait</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('web.dashboard')}}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Demande de retrait</li>
                    </ol>
                </nav>
            </div>
            
        </div>
        <!--end breadcrumb-->
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h5 class="mb-0">Listes des demandes de retrait</h5>
                </div>
                <hr/>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Profile</th>
                                <th>Nom et Prénom</th>
                                <th>Email</th>
                                <th>Phone Momo</th>
                                <th>Montant</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($demandes as $item)
                                <tr>
                                    <td>
                                        <img class="img-profile rounded-circle img-fluid" src="{{ asset($item->url_profil ?? 'assets/images/profile.png') }}" alt="profile" style="max-width: 50px; max-height: 50px;">
                                    </td>
                                    <td>{{ $item->user->fullname }}</td>
                                    <td>{{ $item->user->email }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ $item->montant }}</td>
                                    <td>
                                        @if($item->status == 'Valider')
                                            <div class="badge rounded-pill bg-success w-100">{{ $item->status }}</div>
                                        @elseif($item->status == 'En attente')
                                            <div class="badge rounded-pill bg-primary w-100">{{ $item->status }}</div>
                                        @elseif($item->status == 'Rejeter')
                                            <div class="badge rounded-pill bg-danger w-100">{{ $item->status }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary">Action</button>
                                            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	
                                                <span class="visually-hidden">Action List</span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                                <!-- Profile -->
                                                <a href="{{ route('web.user.profile', $item->user->id) }}" class="dropdown-item d-flex align-items-center">
                                                    <button class="btn btn-warning btn-circle btn-sm">
                                                        <i class="bx bx-user"></i>
                                                    </button>
                                                    <span class="ml-2">Profile</span>
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <!-- Voir demande -->
                                                <a href="#" class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#DemandeModal{{ $item->id }}">
                                                    <button class="btn btn-primary btn-circle btn-sm">
                                                        <i class="bx bx-plus"></i>
                                                    </button>
                                                    <span class="ml-2">Voir</span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Dialog -->
                                <div class="modal fade" id="DemandeModal{{ $item->id }}" tabindex="-1" aria-labelledby="DemandeModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="DemandeModalLabel{{ $item->id }}">Traitement de la demande de retrait</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <p>Demande de retrait de <strong>{{ $item->user->fullname }}</strong></p>
                                                <p>Montant : <strong>{{ $item->montant }}</strong></p>
                                                <p>Numéro Momo : <strong>{{ $item->phone }}</strong></p>
                                                <p>Statut actuel : <strong>{{ $item->status }}</strong></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <a class="btn btn-primary" href="{{ route('web.retrait.demande', $item->id) }}?accept=1">Accepter</a>
                                                <a class="btn btn-danger" href="{{ route('web.retrait.demande', $item->id) }}?accept=0">Refuser</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $demandes->links() }}
                </div>
            </div>
        </div>
        
    </div>


@endsection