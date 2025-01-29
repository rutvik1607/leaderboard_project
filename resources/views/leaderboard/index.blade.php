<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<style>
    .highlighted {
        background-color: #f0f8ff;
        font-weight: bold;
        border: 2px solid #007bff;
    }
</style>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Leaderboard</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between mb-3">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('leaderboard.index') }}" class="d-flex">
                <select name="filter" class="form-select me-2">
                    <option value="">All Time</option>
                    <option value="day" {{ request('filter') == 'day' ? 'selected' : '' }}>Today</option>
                    <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="year" {{ request('filter') == 'year' ? 'selected' : '' }}>This Year</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>

                @if (request('filter'))
                    <a href="{{ route('leaderboard.index') }}" class="btn btn-danger ms-2">Clear</a>
                @endif
            </form>

            <!-- Search Form -->
            <form method="GET" action="{{ route('leaderboard.index') }}" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by ID"
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-secondary">Search</button>
                @if (request('search'))
                    <a href="{{ route('leaderboard.index') }}" class="btn btn-danger ms-2">Clear</a>
                @endif
            </form>

            <!-- Recalculate Button -->
            <form method="POST" action="{{ route('leaderboard.recalculate') }}">
                @csrf
                <button type="submit" class="btn btn-warning">Recalculate</button>
            </form>
        </div>

        <!-- Leaderboard Table -->
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Total Points</th>
                    <th>Rank</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="user-item @if ($user->id == $searchId) highlighted @endif">
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->total_points }}</td>
                        <td>{{ '#' . $user->rank }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
