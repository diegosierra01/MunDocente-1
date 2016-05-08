@extends('layouts.app')

@section('links')
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link href="{{ elixir('../css/app.css') }}" rel="stylesheet"> --}}

    <link rel="stylesheet" href="../js/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../js/bootstrap/css/bootstrap-responsive.min.css" />
    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="../css/main.css" />
    <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->

    <link rel="stylesheet" type="text/css" href="../css/simpletree.css" />
@stop

@section('menu')

	@yield('menus')

@stop

@section('logo')
    <a class="logo" href="/"><img  src="../images/logomundocente.png"></a>
@stop

@section('main')
    
    @yield('principal')

@stop

@section('scripts')

<script src="../js/jquery.min.js"></script>
<script src="../js/jquery.dropotron.min.js"></script>
<script src="../js/skel.min.js"></script>
<script src="../js/skel-viewport.min.js"></script>
<script src="../js/util.js"></script>
<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
<script src="../js/main.js"></script>
<script src='http://code.jquery.com/jquery-latest.js'></script>
<script src="../js/slider.js"></script>
<script src="../js/areas.js"></script>
<script type="text/javascript" src="../js/simpletreemenu.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    {{-- <script src="{{ elixir('../js/app.js') }}"></script> --}}
@stop