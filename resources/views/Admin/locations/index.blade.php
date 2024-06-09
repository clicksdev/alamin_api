@extends('Admin.layouts.main')

@section("title", "Locations")

@php
    $locations = App\Models\Location::all();
@endphp

@section("content")
<style>
    #dataTable_wrapper {
        width: 100%
    }
</style>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Locations</h1>
    <a href="{{ route("admin.locations.add") }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-plus fa-sm text-white-50"></i> Create Location</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive" style="width: auto">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Sub Title</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($locations as $loc)
                        <tr>
                            <td>{{ $loc->title }}</td>
                            <td>{{ ($loc->sub_title) }}</td>
                            <td>
                                <a href="{{ route("admin.locations.edit", ["id" => $loc->id]) }}" class="btn btn-success">Edit</a>
                                <a href="{{ route("admin.locations.delete.confirm", ["id" => $loc->id]) }}" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endSection


@section("scripts")
<script src="{{ asset('/admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('/admin/js/demo/datatables-demo.js') }}"></script>
@endSection
