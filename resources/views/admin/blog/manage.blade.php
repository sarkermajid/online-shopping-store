@extends('admin.master')

@section('body')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">Blogs</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table" id="tableCategory">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Blog Category</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($blogs as $blog)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $blog->title }}</td>
                                    <td>{{ $blog->user->name }}</td>
                                    <td>{{ $blog->blogCategory->name }}</td>
                                    <td><img src="{{ asset('admin/blog-image/'.$blog->image) }}" height="50" width="70"
                                            alt=""></td>
                                    <td><a href="" data-id="{{ $blog->id }}"
                                            class="btn btn-sm blog-status {{ $blog->status == 1 ? 'btn-success' : 'btn-danger' }}">{{ $blog->status == 1 ? 'Active' : 'Inactive' }}</a>
                                    </td>
                                    <td>
                                        <div>
                                            <a href="{{ route('blog.view', ['id' => $blog->id]) }}"
                                                class="btn btn-outline-info btn-sm"><i class="fa fa-eye"></i> View</a>
                                             <a href="{{ route('blog.edit', ['id' => $blog->id]) }}"
                                                 class="btn btn-outline-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                            <a href="" data-id="{{ $blog->id }}" class="btn btn-outline-danger btn-sm show_confirm blog-delete" data-toggle="tooltip"><i class="fa fa-trash"></i> Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tableCategory').DataTable();
        });
    </script>

    <script type="text/javascript">

        $('.show_confirm').click(function(event) {
             var form =  $(this).closest("form");
             var name = $(this).data("name");
             event.preventDefault();
             swal({
                 title: `Are you sure?`,
                 text: "You won't be able to revert this!",
                 icon: "warning",
                 buttons: true,
                 dangerMode: true,
             })
             .then((willDelete) => {
               if (willDelete) {
                 form.submit();
               }
             });
         });

    </script>
@endpush
