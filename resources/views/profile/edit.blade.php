@extends('layouts.ticket-system')

@section('title', 'Mi Perfil')

@section('content')
<h1 style="margin: 0 0 1.5rem 0;">Mi Perfil</h1>

<!-- Actualizar información del perfil -->
@include('profile.partials.update-profile-information-form')

<!-- Cambiar contraseña -->
@include('profile.partials.update-password-form')

<!-- Eliminar cuenta -->
@include('profile.partials.delete-user-form')
@endsection
