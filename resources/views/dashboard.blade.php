@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>User</h1>
@stop

@section('content')
    <div class="card">

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                @if ($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '{{ $errors->first() }}'
                    });
                @endif
                @if (session('success'))

                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: '{{ session('success') }}'
                    });
                @endif
            });
        </script>
        <div class="card-header">
            <h3 class="card-title">Lista de Usuarios</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary" data-toggle="modal"
                    data-target="#modal-registrar">Registrar</button>
            </div>
        </div>
        <div class="card-body">
            <table id="users-table" class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th>Nro de Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Iterar sobre los usuarios y mostrarlos en la tabla -->
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->nro_phone }}</td>
                            <td>
                                <div style="display: inline-block;">
                                    <button class="btn btn-sm btn-info"
                                        onclick="editUser({{ $user->id }})">Editar</button>
                                    <form id="delete-user-form-{{ $user->id }}"
                                        action="{{ route('user.destroy', ['userId' => $user->id]) }}" method="POST"
                                        style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </div>
                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Registro de Usuario -->
    <div class="modal fade" id="modal-registrar" tabindex="-1" role="dialog" aria-labelledby="modal-registrar-label"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-registrar-label">Registrar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Aquí incluir el formulario de registro de usuario -->
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="Ingrese su nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Apellido</label>
                            <input type="text" name="last_name" id="last_name" class="form-control"
                                placeholder="Ingrese su apellido" required>
                        </div>
                        <div class="form-group">
                            <label for="nro_phone">Número de Teléfono</label>
                            <input type="text" name="nro_phone" id="nro_phone" class="form-control"
                                placeholder="Ingrese su número de teléfono" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Correo</label>
                            <input type="email" name="email" id="email" class="form-control"
                                placeholder="Ingrese su correo electrónico" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Ingrese su contraseña" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Registrar Usuario</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Edición de Usuario -->
    <div class="modal fade" id="modal-editar-usuario" tabindex="-1" role="dialog"
        aria-labelledby="modal-editar-usuario-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-editar-usuario-label">Editar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-editar-usuario" method="POST"
                        action="{{ route('user.update', ['userId' => $user->id]) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="user_id" id="edit_user_id">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" id="edit_name" class="form-control"
                                placeholder="Ingrese su nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_last_name">Apellido</label>
                            <input type="text" name="last_name" id="edit_last_name" class="form-control"
                                placeholder="Ingrese su apellido" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_nro_phone">Número de Teléfono</label>
                            <input type="text" name="nro_phone" id="edit_nro_phone" class="form-control"
                                placeholder="Ingrese su número de teléfono" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_password">Contraseña</label>
                            <input type="password" name="edit_password" id="edit_password" class="form-control"
                                placeholder="Ingrese su contraseña">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')

    <script>
        function editUser(userId) {
            $.ajax({
                url: '/user/' + userId,
                type: 'GET',
                success: function(data) {
                    $('#edit_user_id').val(data.id);
                    $('#edit_name').val(data.name);
                    $('#edit_last_name').val(data.last_name);
                    $('#edit_email').val(data.email);
                    $('#edit_nro_phone').val(data.nro_phone);
                    $('#edit_password').val(data.password);
                    // Abrir el modal de edición de usuario
                    $('#modal-editar-usuario').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function confirmDelete(userId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Una vez eliminado, no podrás recuperar este usuario.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminarlo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteUserData(userId);
                }
            });
        }

        function deleteUserData(userId) {
            $.ajax({
                type: 'DELETE',


                success: function(response) {
                    // Manejar la respuesta exitosa del servidor (por ejemplo, mostrar un mensaje de éxito)
                    Swal.fire({
                        title: '¡Eliminado!',
                        text: 'El usuario ha sido eliminado correctamente.',
                        icon: 'success'
                    }).then((result) => {
                        // Redireccionar a la página de inicio después de la eliminación
                        window.location.href = '{{ route('dashboard.index') }}';
                    });
                },
                error: function(xhr, status, error) {
                    // Manejar errores en la solicitud AJAX (por ejemplo, mostrar un mensaje de error)
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un error al eliminar el usuario. Inténtelo de nuevo.',
                        icon: 'error'
                    });
                }
            });
        }
    </script>
@stop
