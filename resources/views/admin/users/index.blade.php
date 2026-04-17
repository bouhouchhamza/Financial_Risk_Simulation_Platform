@extends('layouts.admin')

@section('content')

<div class="users-page">

    <div class="users-header">
        <div>
            <span class="kicker">USER MANAGEMENT</span>
            <h1>System Users</h1>
            <p>Monitor registered users and platform access activity.</p>
        </div>
    </div>

    <div class="users-card">
        <div class="table-scroll">
            <table class="users-table">

                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Created</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="name">{{ $user->name }}</div>
                                        <div class="meta">User ID: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="email">
                                {{ $user->email }}
                            </td>

                            <td class="date">
                                {{ $user->created_at->format('Y-m-d H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <div class="pagination">
        {{ $users->links() }}
    </div>

</div>
@endsection
