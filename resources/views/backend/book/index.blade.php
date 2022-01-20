@extends('backend.layouts.index')

@section('main-content')
@push('css-libraries')
    {{-- Table custom --}}
    <link rel="stylesheet" href="{{ asset('css/table-custom.css') }}">
    {{-- Datatables custom --}}
    <link rel="stylesheet" href="{{ asset('css/datatables-custom.css') }}">
    {{-- Dropify --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/dropify@0.2.2/dist/css/dropify.min.css') }}">
    {{-- Datatables --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables/datatables4.min.css') }}">
    
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
                    <h5 class="text-gray">Halaman Buku</h5>
                    <small class="text-muted">Halaman buku untuk menambahkan data, melakukan edit dan hapus data buku.</small>
                </div>
                <div class="ml-auto">
                    <button class="btn--28 btn-sm-28 btn-blue mb-4" role="button" onclick="add()">
                        Tambah Data
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table-books" width="100%">
                        <thead>
                            <tr>
                                <th width="5%">NO</th>
                                <th width="5%">COVER</th>
                                <th width="15%">JUDUL</th>
                                <th width="10%">PENGARANG</th>
                                <th width="10%">PENERBIT</th>
                                <th width="10%">HARGA</th>
                                <th width="10%">JUMLAH</th>
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
<div class="modal fade" id="modalBook" tabindex="-1"
    aria-labelledby="modalBookLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header--28">
                <h5 class="modal-title">Form Data Buku</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal-body--28">
               <div class="row">
                  <div class="col-md-12">
                     <form action="#" method="post" id="book-form" enctype="multipart/form-data">
                        @csrf
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="Cover">Cover</label>
                                        <input type="file" name="cover" id="cover" class="dropify"
                                        data-max-file-size="3M" data-height="125" data-allowed-file-extensions="jpg jpeg png"/>
                                    </div>
                                </div>
                            </div>
                           <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="Judul Buku">Judul</label> <small class="required"> * </small>
                                        <input type="text" name="title" id="title" class="form-control--28" placeholder="Judul Buku">
                                        <input type="hidden" name="id" id="id">
                                        <input type="hidden" name="_method" id="_method">
                                    </div>
                                </div>
                           </div>
                           <div class="row">
                               <div class="col-6">
                                   <div class="form-group">
                                       <label for="Pengarang">Pengarang</label> <small class="required"> * </small>
                                       <input type="text" name="author" id="author" class="form-control--28" placeholder="Pengarang">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="Pengarang">Penerbit</label> <small class="required"> * </small>
                                        <input type="text" name="publisher" id="publisher" class="form-control--28" placeholder="Penerbit">
                                    </div>
                                </div>
                           </div>
                           <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="Jumlah Buku">Jumlah</label> <small class="required"> * </small>
                                        <input type="number" min="1" name="stock" id="stock" class="form-control--28" placeholder="Jumlah Buku">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="Harga Buku">Harga</label> <small class="required"> * </small>
                                        <input type="number" min="1" name="price" id="price" class="form-control--28" placeholder="Harga Buku">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="Descripton">Deskripsi</label>
                                        <textarea name="description" id="description" rows="4" class="form-control--28" placeholder="Deskripsi Buku"></textarea>
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
    const formInput = $('#book-form');

    let tableBook = $('#table-books').DataTable({
        iDisplayLength: 25,
        processing: true,
        serverSide: true,
        ordering: false,
        language: translatedFormat(),
        ajax: `${APP_URL}/book/grid`,
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false, orderable: false, className: 'text-center'},
            {data: 'cover', name: 'cover', orderable: false, searchable: true, className: 'text-left'},
            {data: 'title', name: 'title', orderable: false, searchable: true, className: 'text-left'},
            {data: 'author', name: 'author', orderable: false, searchable: true, className: 'text-left'},
            {data: 'publisher', name: 'publisher', orderable: false, searchable: true, className: 'text-center'},
            {data: 'price', name: 'price', orderable: false, searchable: true, className: 'text-center'},
            {data: 'stock', name: 'stock', orderable: false, searchable: true, className: 'text-center'},
            {data: 'action', name: 'action', orderable: false, className: 'text-center'},
        ],
    });

    tableBook.on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    })

    setDropify('dropify');
</script>

<script>
    function add() {
        save_method = "add";
        setModal('modalBook', 'show');
    }

    function save() {
        const url = getUrl();
        let formData = new FormData();
        let formRawData = formInput.serializeArray();

        // Book  photo
        let filePhoto = document.getElementById("cover").files[0];

        formRawData.forEach((element) => {
            if(element.value != '')
                formData.append(element.name, element.value);
        });

        formData.append('cover', (filePhoto == undefined ? '' : filePhoto));
        const formSetup  = { url: url, type: 'post', hasInputFile: true };

        commit(formSetup, formData)
            .then((response) => {
                if(response.status == true)  {
                    showSuccessMessage(response.message);
        
                    tableBook.ajax.reload();

                    setModal('modalBook', 'hide');
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

        const url = `${APP_URL}/book/${id}/edit`;
        const formSetup = { url: url, type: 'get' }

        commit(formSetup)
            .then((response) => {
                $("#id").val(response.id);
                $("#title").val(response.title);
                $("#author").val(response.author);
                $("#publisher").val(response.publisher);
                $("#stock").val(response.stock);
                $("#price").val(response.price);
                $("#description").val(response.description);

                let filedropper = $('#cover').dropify();
                filedropper = filedropper.data('dropify');
                filedropper.resetPreview();
                filedropper.clearElement();
                filedropper.settings['defaultFile'] = `${APP_URL}/images/books/${response.cover}`;
                filedropper.destroy();
                filedropper.init();

                $("#_method").val('PUT');

                setModal('modalBook', 'show');
            })
            .catch((err) => {
                console.log(err);
            });
    }

    function deleteData(id){

        confirmDelete()
            .then((response) => {
                
                const url =  `${APP_URL}/book/${id}`;
                const formSetup = { url:url, type: 'delete' };

                commit(formSetup)
                    .then((response) => {
                        if(response.status == true)  {
                            showSuccessMessage(response.message);
                
                            tableBook.ajax.reload();

                            setModal('modalBook', 'hide');
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
            url = `${APP_URL}/book`;
        }else{
            const id = $('#id').val();
            url = `${APP_URL}/book/${id}`;
        }

        return url
    }
</script>
@endsection