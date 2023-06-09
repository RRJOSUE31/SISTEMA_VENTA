@php( $logout_url = View::getSection('logout_url') ?? config('adminlte.logout_url', 'logout') )
@php( $profile_url = View::getSection('profile_url') ?? config('adminlte.profile_url', 'logout') )

@if (config('adminlte.usermenu_profile_url', false))
    @php( $profile_url = Auth::user()->adminlte_profile_url() )
@endif

@if (config('adminlte.use_route_url', false))
    @php( $profile_url = $profile_url ? route($profile_url) : '' )
    @php( $logout_url = $logout_url ? route($logout_url) : '' )
@else
    @php( $profile_url = $profile_url ? url($profile_url) : '' )
    @php( $logout_url = $logout_url ? url($logout_url) : '' )
@endif




@can('apertura-caja.index')
    <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle text-gray-800 font-semibold" data-toggle="dropdown">
            @if(Auth::user()->apertura == "no" )
            <span class="text-red-600 font-semibold">
                Caja: Cerrada  
            </span>
            @else
            <span class="text-green-700 font-semibold">
                Caja: Aperturada
            </span>
            @endif
        </a>

        <ul class="dropdown-menu">
            @if(Auth::user()->apertura == "no" )
            <a class="block dropdown-item stretched-link text-success fw-bolder" href="{{route('apertura-caja.index')}}"> Aperturar</a>
            @else
            <a class="block dropdown-item stretched-link text-danger fw-bolder" href="{{route('apertura-caja.index')}}"> Cerrar</a>
            @endif

        </ul>
    </li>
@endcan

@can('reportes.reportes_seniat')
    <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle text-gray-800 font-semibold" data-toggle="dropdown">
            Reportes
        </a>
        <ul class="dropdown-menu">
            <a class="block dropdown-item " href="{{route('reporte.x')}}"> Reporte del día</a>
            <a class="block dropdown-item " href="{{route('reporte.z')}}"> Reporte por rango</a>
        </ul>
    </li>
@endcan


<li class="nav-item dropdown">

    {{-- User menu toggler --}}
    <a href="#" class="nav-link dropdown-toggle text-gray-800 font-semibold" data-toggle="dropdown">
        @if(session('moneda') && session('simbolo_moneda'))
        <span>
            Moneda: {{ session('moneda') }} ({{ session('simbolo_moneda') }})  
        </span>
        @else
        <span>
            Moneda: Bolivar (Bs)
        </span>
        @endif
    </a>

    <ul class="dropdown-menu">
        @livewire('moneda.cambiar-moneda')

    </ul>
</li>

<li class="nav-item dropdown user-menu">

    {{-- User menu toggler --}}
    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        @if(config('adminlte.usermenu_image'))
            <img src="{{ Auth::user()->adminlte_image() }}"
                 class="user-image img-circle elevation-2"
                 alt="{{ Auth::user()->name }}">
        @endif
        <span @if(config('adminlte.usermenu_image')) class="d-none d-md-inline" @endif>
            {{ Auth::user()->name }}  {{ Auth::user()->apellido }}
        </span>
    </a>

    {{-- User menu dropdown --}}
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

        {{-- User menu header --}}
        @if(!View::hasSection('usermenu_header') && config('adminlte.usermenu_header'))
            <li class="user-header {{ config('adminlte.usermenu_header_class', 'bg-primary') }}
                @if(!config('adminlte.usermenu_image')) h-auto @endif">
                @if(config('adminlte.usermenu_image'))
                    <img src="{{ Auth::user()->adminlte_image() }}"
                         class="img-circle elevation-2"
                         alt="{{ Auth::user()->name }}">
                @endif
                <p class="@if(!config('adminlte.usermenu_image')) mt-0 @endif">
                    {{ Auth::user()->name }}
                    @if(config('adminlte.usermenu_desc'))
                        <small>{{ Auth::user()->adminlte_desc() }}</small>
                    @endif
                </p>
            </li>
        @else
            @yield('usermenu_header')
        @endif

        {{-- Configured user menu links --}}
        @each('adminlte::partials.navbar.dropdown-item', $adminlte->menu("navbar-user"), 'item')

        {{-- User menu body --}}
        @hasSection('usermenu_body')
            <li class="user-body">
                @yield('usermenu_body')
            </li>
        @endif

        {{-- User menu footer --}}
        <li class="user-footer">
            @if($profile_url)
                <a href="{{ $profile_url }}" class="btn btn-default btn-flat">
                    <i class="fa fa-fw fa-user text-lightblue"></i>
                    {{ __('adminlte::menu.profile') }}
                </a>
            @endif
            <a class="btn btn-default btn-flat float-right @if(!$profile_url) btn-block @endif"
               href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-fw fa-power-off text-red"></i>
                {{ __('adminlte::adminlte.log_out') }}
            </a>
            <form id="logout-form" action="{{ $logout_url }}" method="POST" style="display: none;">
                @if(config('adminlte.logout_method'))
                    {{ method_field(config('adminlte.logout_method')) }}
                @endif
                {{ csrf_field() }}
            </form>
        </li>


    </ul>

</li>


<li class="nav-item">
    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
    </a>
</li>


