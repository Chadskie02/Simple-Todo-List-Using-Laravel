@extends('layouts.app')

@section('content')

{{-- Add Modal --}}
<div class="modal fade" id="AddTodoModal" tabindex="-1" aria-labelledby="AddTodoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="AddTodoModalLabel">Add Todo List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <ul id="save_msgList"></ul>
                <div class="form-group mb-3">
                    <label for="">User ID</label>
                    <input type="text" required class="user_id form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">Title</label>
                    <input type="text" required class="title form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">Notes</label>
                    <input type="text" required class="body form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">Category</label>
                    <input type="text" required class="category form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary add_todo">Save</button>
            </div>

        </div>
    </div>
</div>


{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Todo List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <ul id="update_msgList"></ul>

                <input type="hidden" id="todo_id" />

                <div class="form-group mb-3">
                    <label for="">User ID</label>
                    <input type="text" id="user_id" required class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">Title</label>
                    <input type="text" id="title" required class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">Notes</label>
                    <input type="text" id="body" required class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">Category</label>
                    <input type="text" id="category" required class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary update_todo">Update</button>
            </div>

        </div>
    </div>
</div>
{{-- Edn- Edit Modal --}}


{{-- Delete Modal --}}
<div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Todo List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4>Confirm to Delete Data ?</h4>
                <input type="hidden" id="deleteing_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary delete_todo">Yes Delete</button>
            </div>
        </div>
    </div>
</div>
{{-- End - Delete Modal --}}

<div class="container py-5">
    <div class="row">
        <div class="col-md-12">

            <div id="success_message"></div>

            <div class="card">
                <div class="card-header">
                    <h4>
                        Todo List
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#AddTodoModal">Add Todo</button>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User ID</th>
                                    <th>Title</th>
                                    <th>Notes</th>
                                    <th>Category</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    $(document).ready(function () {

        fetchtodo();

        function fetchtodo() {
            $.ajax({
                type: "GET",
                url: "/fetch-todos",
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    $('tbody').html("");
                    $.each(response.todos, function (key, item) {
                        $('tbody').append('<tr>\
                            <td>' + item.id + '</td>\
                            <td>' + item.user_id + '</td>\
                            <td>' + item.title + '</td>\
                            <td>' + item.body + '</td>\
                            <td>' + item.category + '</td>\
                            <td><button type="button" value="' + item.id + '" class="btn btn-primary editbtn btn-sm">Edit</button></td>\
                            <td><button type="button" value="' + item.id + '" class="btn btn-danger deletebtn btn-sm">Delete</button></td>\
                        \</tr>');
                    });
                }
            });
        }

        $(document).on('click', '.add_todo', function (e) {
            e.preventDefault();

            $(this).text('Sending..');

            var data = {
                'user_id': $('.user_id').val(),
                'title': $('.title').val(),
                'body': $('.body').val(),
                'category': $('.category').val(),
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/todos",
                data: data,
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.status == 400) {
                        $('#save_msgList').html("");
                        $('#save_msgList').addClass('alert alert-danger');
                        $.each(response.errors, function (key, err_value) {
                            $('#save_msgList').append('<li>' + err_value + '</li>');
                        });
                        $('.add_todo').text('Save');
                    } else {
                        $('#save_msgList').html("");
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                        $('#AddTodoModal').find('input').val('');
                        $('.add_todo').text('Save');
                        $('#AddTodoModal').modal('hide');
                        fetchtodo();
                    }
                }
            });

        });
        $(document).on('click', '.editbtn', function (e) {
            e.preventDefault();
            var todo_id = $(this).val();
            // alert(stud_id);
            $('#editModal').modal('show');
            $.ajax({
                type: "GET",
                url: "/edit-todo/" + todo_id,
                success: function (response) {
                    if (response.status == 404) {
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                        $('#editModal').modal('hide');
                    } else {
                        // console.log(response.todo.name);
                        $('#user_id').val(response.todo.user_id);
                        $('#title').val(response.todo.title);
                        $('#body').val(response.todo.body);
                        $('#category').val(response.todo.category);
                        $('#todo_id').val(todo_id);
                    }
                }
            });
            $('.btn-close').find('input').val('');

        });

        $(document).on('click', '.update_todo', function (e) {
            e.preventDefault();

            $(this).text('Updating..');
            var id = $('#todo_id').val();
            // alert(id);

            var data = {
                'user_id': $('#user_id').val(),
                'title': $('#title').val(),
                'body': $('#body').val(),
                'category': $('#category').val(),
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT",
                url: "/update-todo/" + id,
                data: data,
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.status == 400) {
                        $('#update_msgList').html("");
                        $('#update_msgList').addClass('alert alert-danger');
                        $.each(response.errors, function (key, err_value) {
                            $('#update_msgList').append('<li>' + err_value +
                                '</li>');
                        });
                        $('.update_todo').text('Update');
                    } else {
                        $('#update_msgList').html("");

                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                        $('#editModal').find('input').val('');
                        $('.update_todo').text('Update');
                        $('#editModal').modal('hide');
                        fetchtodo();
                    }
                }
            });

        });

        $(document).on('click', '.deletebtn', function () {
            var todo_id = $(this).val();
            $('#DeleteModal').modal('show');
            $('#deleteing_id').val(todo_id);
        });

        $(document).on('click', '.delete_todo', function (e) {
            e.preventDefault();

            $(this).text('Deleting..');
            var id = $('#deleteing_id').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "DELETE",
                url: "/delete-todo/" + id,
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.status == 404) {
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                        $('.delete_todo').text('Yes Delete');
                    } else {
                        $('#success_message').html("");
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                        $('.delete_todo').text('Yes Delete');
                        $('#DeleteModal').modal('hide');
                        fetchtodo();
                    }
                }
            });
        });

    });

</script>

@endsection