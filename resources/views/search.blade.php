@extends('layouts.app')

@section('title', config('app.name', 'The Archive') . ' — ' . __('Search'))

@section('content')
    <section class="max-w-7xl mx-auto mt-8 px-6">
        <livewire:search-page />
    </section>
@endsection
