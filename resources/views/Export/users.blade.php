@extends('master')
@section('head')
@endsection

@section('content')

    <h2 style="text-align: center;">Lista de Usuários</h2>
    <table class="table">
        <thead">
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Status</th>
                <th>Permissão</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ optional($user->status)->name }}</td>
                        <td>{{ optional($user->roles)->first()->name }}</td>
                    </tr>
                @endforeach
            </tbody>
    </table>
@endsection
