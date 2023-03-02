<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>稽核管理系統</title>

  @include('layouts.css')
  @yield('css')
</head>
<body>
<div class="wrapper">
  @yield('content')
</div>
<!-- ./wrapper -->

@include('layouts.js')
@yield('js')
</body>
</html>
