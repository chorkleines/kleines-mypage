@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-3">{{ __('accounts_list') }}</h2>
        <div class="table-responsive">
            <table class="table table-sm table-striped text-nowrap">
                <thead>
                    <tr>
                        <th scope="col">@sortablelink('grade', __('grade'))</th>
                        <th scope="col">@sortablelink('part', __('part'))</th>
                        <th scope="col">@sortablelink('name', __('full_name'))</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($profiles as $profile)
                        <tr>
                            <td>{{ $profile->grade }}</td>
                            <td>{{ $profile->part }}</td>
                            <td>{{ $profile->last_name . ' ' . $profile->first_name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-pagination :paginator="$profiles" />
    </div>
@endsection
