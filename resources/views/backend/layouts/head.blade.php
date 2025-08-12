<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('public/backend/images/favicon.png') }}">

    <!-- plugins css -->
    <link rel="stylesheet" href="{{ asset('public/backend/vendors/bootstrap/dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('public/backend/vendors/PACE/themes/blue/pace-theme-minimal.css') }}">
    <link rel="stylesheet" href="{{ asset('public/backend/vendors/perfect-scrollbar/css/perfect-scrollbar.min.css') }}">

    <!-- page plugins css -->
    <link rel="stylesheet" href="{{ asset('public/backend/vendors/selectize/dist/css/selectize.default.css') }}">
    <link rel="stylesheet" href="{{ asset('public/backend/vendors/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('public/backend/vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('public/backend/vendors/summernote/dist/summernote.css') }}">

    <!-- core css -->
    <link href="{{ asset('public/backend/css/ei-icon.css') }}" rel="stylesheet">
    <link href="{{ asset('public/backend/css/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('public/backend/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/backend/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/backend/css/app.css') }}?v=3.1" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/backend/css/dropzone.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/backend/css/bootstrap-tagsinput.css') }}">
</head>
<body>
<div class="app">
    <div class="layout">