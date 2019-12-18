 <!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel = "icon" href ="{{asset('images/adlaw-logo.png')}}" type = "image/x-icon" style="line-height: 40px;">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>ADLAW</title>

  <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
      crossorigin="anonymous"></script>
  <!-- Bootstrap CSS CDN -->

  <link rel="stylesheet" href="{{asset('adlaw_files/css/bootstrap.min.css')}}">
  <!-- Fontawesome icon CDN -->
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="stylesheet" href="{{asset('adlaw_files/css/ionicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('adlaw_files/css/jquery-jvectormap.css')}}">
  <link rel="stylesheet" href="{{asset('adlaw_files/css/adlaw.min.css')}}">
  <link rel="stylesheet" href="{{asset('adlaw_files/css/_all-skins.min.css')}}">

  <link rel="stylesheet" href="{{asset('css/parts-selector.css')}}">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />

  <link rel="stylesheet" href="http://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
  <script src="{{asset('tinymce/tinymce.min.js')}}"></script>
  <link rel="stylesheet" type="text/css" href="{{asset('css/dashboard/dashboard.css')}}"> 
  <link href="{{ asset('tabler/assets/css/dashboard.css') }}" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />

  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />

  <link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
  <style type="text/css">
   .select2-container--default .select2-selection--multiple .select2-selection__choice {
      background-color: #247ae4;
    }
    .locked-tag .select2-selection__choice__remove{
    display: none!important;
  }

  .select2{
    width: 100% !important;
  }
/*    .select2-selection__choice__remove,.select2-selection__clear{display:none !important;} */
</style>
 @stack('links')
</head>
<body class="hold-transition skin-blue sidebar-mini fixed">