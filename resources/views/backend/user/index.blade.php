@extends('backend.layouts.index')

@section('main-content')
@push('css-libraries')
    {{-- Table custom --}}
    <link rel="stylesheet" href="{{ asset('css/table-custom.css') }}">
    {{-- Datatables custom --}}
    <link rel="stylesheet" href="{{ asset('css/datatables-custom.css') }}">
    {{-- Datatables --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables/datatables4.min.css') }}">
    {{-- Dropify --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/dropify@0.2.2/dist/css/dropify.min.css') }}">
    
@endpush

@push('script-libraries')
    {{-- Datatables --}}
    <script src="{{ asset('assets/vendors/datatables/jquery-datatables.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables/datatables4.min.js') }}"></script>
    {{-- Dropify --}}
    <script src="{{ asset('assets/vendors/dropify@0.2.2/dist/js/dropify.min.js') }}"></script>
@endpush

<div class="row">
    <div class="col-md-12">
        <div class="card card--28">
            <div class="d-flex align-items-center px-4 pb-4">
                <div>
                    <h5 class="text-gray">Halaman User</h5>
                    <small class="text-muted">Halaman user untuk menambahkan data, melakukan edit dan hapus data user.</small>
                </div>
                <div class="ml-auto">
                    <button class="btn--28 btn-sm-28 btn-blue mb-4" role="button" onclick="add()">
                        Tambah Data
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table-user" width="100%">
                        <thead>
                            <tr>
                                <th width="5%">NO</th>
                                <th width="5%">AVATAR</th>
                                <th width="10%">NAME</th>
                                <th width="15%">EMAIL</th>
                                <th width="15%">ADDRESS</th>
                                <th width="15%">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalUser" tabindex="-1"
    aria-labelledby="modalUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header--28">
                <h5 class="modal-title">Form Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal-body--28">
               <div class="row">
                  <div class="col-md-12">
                     <form action="#" method="post" id="users-form" enctype="multipart/form-data">
                        @csrf
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="Avatar">Avatar</label>
                                        <input type="file" name="avatar" id="avatar" class="dropify"
                                        data-max-file-size="3M" data-height="125" data-allowed-file-extensions="jpg jpeg png"/>
                                    </div>
                                </div>
                            </div>
                           <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Nama</label> <small class="required"> * </small>
                                        <input type="text" name="name" id="name" class="form-control--28" placeholder="Nama Lengkap">
                                        <input type="hidden" name="id" id="id">
                                        <input type="hidden" name="_method" id="_method">
                                    </div>
                                </div>
                           </div>
                           <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="Email">Email</label> <small class="required"> * </small>
                                        <input type="text" name="email" id="email" class="form-control--28" placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="Password">Password</label> <small class="required"> * </small>
                                        <input type="password" name="password" id="password" class="form-control--28" placeholder="Password" autocomplete="new-password">
                                    </div>
                                </div>
                           </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="Alamat">Alamat</label>
                                        <textarea name="address" id="address" rows="4" class="form-control--28" placeholder="Alamat Lengkap"></textarea>
                                     </div>
                                </div>
                            </div>
                     </form>
                  </div>
               </div>
            </div>
            <div class="modal-footer modal-footer--28">
                <div class="mx-auto">
                    <button type="button" class="btn--28 btn-blue mr-2" onclick="save()">Simpan</button>
                    <button type="button" class="btn--28 btn-custom-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let save_method, url, id = '';
    const formInput = $('#users-form');

    let tableUser = $('#table-user').DataTable({
        iDisplayLength: 25,
        processing: true,
        serverSide: true,
        ordering: false,
        language: translatedFormat(),
        ajax: `${APP_URL}/user/grid`,
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false, orderable: false, className: 'text-center'},
            {data: 'avatar', name: 'avatar', orderable: false, searchable: true, className: 'text-left'},
            {data: 'name', name: 'name', orderable: false, searchable: true, className: 'text-left'},
            {data: 'email', name: 'email', orderable: false, searchable: true, className: 'text-left'},
            {data: 'address', name: 'address', orderable: false, searchable: true, className: 'text-left'},
            {data: 'action', name: 'action', orderable: false, className: 'text-center'},
        ],
    });

    tableUser.on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    })

    setDropify('dropify');

</script>

<script>
    function add() {
        save_method = "add";
        setModal('modalUser', 'show');
    }

    function save() {
        const url = getUrl();
        let formData = new FormData();
        let formRawData = formInput.serializeArray();

        // Book  photo
        let filePhoto = document.getElementById("avatar").files[0];

        formRawData.forEach((element) => {
            if(element.value != '')
                formData.append(element.name, element.value);
        });

        formData.append('avatar', (filePhoto == undefined ? '' : filePhoto));
        const formSetup  = { url: url, type: 'post', hasInputFile: true };

        commit(formSetup, formData)
            .then((response) => {
                if(response.status == true)  {
                    showSuccessMessage(response.message);
        
                    tableUser.ajax.reload();

                    setModal('modalUser', 'hide');
                }
            })
            .catch((xhr) => {
                if(xhr.status == 422) {
                    const messages = xhr.responseJSON.message;
                    
                    for(messageKey in messages) {
                        return showFailMessage(messages[messageKey][0]);
                    }
                }
            });
    }

    function editData(id) {
        save_method = 'update';

        const url = `${APP_URL}/user/${id}/edit`;
        const formSetup = { url: url, type: 'get' }

        commit(formSetup)
            .then((response) => {
                $("#id").val(response.id);
                $("#name").val(response.name);
                $("#email").val(response.email);
                $("#address").val(response.address);

                let filedropper = $('#avatar').dropify();
                filedropper = filedropper.data('dropify');
                filedropper.resetPreview();
                filedropper.clearElement();
                filedropper.settings['defaultFile'] = `${APP_URL}/images/users/${response.avatar}`;
                filedropper.destroy();
                filedropper.init();

                $("#_method").val('PUT');

                setModal('modalUser', 'show');
            })
            .catch((err) => {
                console.log(err);
            });
    }

    function deleteData(id){
        confirmDelete()
            .then((response) => {
                
                const url =  `${APP_URL}/user/${id}`;
                const formSetup = { url: url, type: 'delete' };

                commit(formSetup)
                    .then((response) => {
                        if(response.status == true)  {
                            showSuccessMessage(response.message);
                
                            tableUser.ajax.reload();

                            setModal('modalUser', 'hide');
                        } else {
                            showFailMessage(response.message);
                        }
                    }).catch((err) => {
                        console.log(err);
                    });
            })

    }

    function getUrl() {
        if(save_method == "add"){
            url = `${APP_URL}/user`;
        }else{
            const id = $('#id').val();
            url = `${APP_URL}/user/${id}`;
        }

        return url;
    }
</script>
@endsection