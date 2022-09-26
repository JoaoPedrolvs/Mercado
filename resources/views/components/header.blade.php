@php

    $pages =[
        ['Clientes', 'customers'],
        ['Funcionários', 'employees'],
        ['Produtos', 'products'],
        ['Categorias','categories'],
        ['Vendas','sales'],
        ['Estoque','inventories'],
        ['Promoção','promotions']
    ];

    $user = Auth::user();

@endphp

<div class="header">

    <nav class="navbar navbar-expand-lg navbar-light bg-light">

        <a class="navbar-brand" href="{{ url('') }}">{{ config('app.name') }}</a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav mr-auto">

                @if (isset($user->manager_id))

                    <li class="nav-item dropdown">

                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Usuários
                        </a>

                        <div class="nav-item dropdown-menu">

                            <a href="{{ url('admins') }}" class="dropdown-item">Administração</a>

                            <a href="{{ url('users') }}" class="dropdown-item"><i class="bi bi-people-fill"></i> Usuários</a>

                            <a href="{{ url('people') }}" class="dropdown-item"><i class="bi bi-person-badge"></i> Pessoas</a>

                        </div>

                    </li>

                @endif

                @foreach ($pages as $page)

                    @if (isset($user->employee_id) && !isset($user->manager_id))

                        @if ($page[0] != 'Funcionários')

                            <li class="nav-item">
                                <a href="{{ url($page[1]) }}" aria-current="page" class="nav-link">{{ $page[0] }}</a>
                            </li>

                        @endif

                    @else

                        @if (isset($user->customer_id) && !isset($user->manager_id))

                            @if ($page[0] == 'Produtos' || $page[0] == 'Categorias' || $page[0] == 'Promoção')

                                <li class="nav-item">
                                    <a href="{{ url($page[1]) }}" aria-current="page" class="nav-link">{{ $page[0] }}</a>
                                </li>

                            @endif

                        @else

                            @if ($page[0] == 'Funcionários')


                                <li class="nav-item dropdown">

                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ $page[0] }}
                                    </a>

                                    <div class="nav-item dropdown-menu" aria-labelledby="navbarDropdown">

                                        <a href="{{ url($page[1]) }}" class="dropdown-item">Funcionários</a>

                                        <a href="{{ url('/employees/roles') }}" class="dropdown-item">Cargos</a>
                                    </div>

                                </li>

                            @else

                                <li class="nav-item">
                                    <a href="{{ url($page[1]) }}" aria-current="page" class="nav-link">{{ $page[0] }}</a>
                                </li>

                            @endif

                        @endif

                    @endif

                @endforeach

            </ul>

                @if (isset($user->customer_id) && !isset($euser->employee_id))

                    <ul class="navbar-nav">

                        <div class="position-relative">

                            @if (Session::has('cart') && count(Session::get('cart')) > 0 )

                                <a href="/cart" class="btn btn-ligth"><i class="bi bi-basket"></i>

                                    @if(Session::has('cart'))

                                        <span class="position-absolute badge rounded-pill bg-danger">{{ count((array) Session::get('cart')) }}</span>

                                    @endif

                                </a>

                            @else

                                <a href="/cart" class="nav-link"><i class="bi bi-basket"></i></a>

                            @endif


                        </div>

                        <li class="nav-item dropdown">

                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ $user->customer->person->name }}
                            </a>

                            <div class="nav-item dropdown-menu dropdown-menu-start" aria-labelledby="navbarDropdown">

                                @if (isset($user->customer_id))

                                    <a href="{{ url('/customer/'.Auth::user()->customer_id.'/show') }}" class="dropdown-item"><i class="bi bi-person-fill"></i> Perfil</a>

                                @endif

                                <a class="dropdown-item logout">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Sair
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>

                            </div>

                        </li>

                    </ul>

                @else

                    @if (isset($user->employee_id) && !isset($user->manager_id))

                    <ul class="navbar-nav ms-auto ms-end">

                        <li class="nav-item dropdown">

                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->employee->person->name }}
                            </a>

                            <div class="nav-item dropdown-menu dropdown-menu-start" aria-labelledby="navbarDropdown">

                                @if (Session::get('employee'))

                                    <a href="{{ url('/employee/'.Auth::user()->employee_id.'/show') }}" class="dropdown-item"><i class="bi bi-person-fill"></i> Perfil</a>

                                @endif

                                <a class="dropdown-item logout">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Sair
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>

                            </div>

                        </li>

                    </ul>

                @else

                    <ul class="navbar-nav">

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->manager->person->name ?? '' }}
                            </a>

                            <div class="nav-item dropdown-menu dropdown-menu-start" aria-labelledby="navbarDropdown">

                                <a class="dropdown-item logout">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Sair
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>

                            </div>

                        </li>

                    </ul>

                @endif

            @endif

        </div>

    </nav>

</div>

