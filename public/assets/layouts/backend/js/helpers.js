const translatedFormat = () => {
    return {
        'processing': '<i class="fa fa-spinner fa-spin fa-2x fa-fw text-gray">',
        "sLengthMenu":   "Tampilkan _MENU_ data",
            "sZeroRecords":  "Tidak ditemukan data yang sesuai",
            "sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 data",
            "sInfoFiltered": "(disaring dari _MAX_ data keseluruhan)",
            "sInfoPostFix":  "",
            "sSearch":       "Cari:",
            "sUrl":          "",
            "oPaginate": {
                "sFirst":    "Awal",
                "sPrevious": "Sebelumnya",
                "sNext":     "Selanjutnya",
                "sLast":     "Akhir"
            }
    }
}


const showSuccessMessage = (messageSuccess) => {
    Swal.fire({
        icon: 'success',
        title: messageSuccess,
        text: '',
        confirmButtonColor: '#21c8f6',
        confirmButtonText: 'Okay'
    })
}

const showFailMessage = (messageError) => {
    Swal.fire({
        icon: 'info',
        title: 'Perhatian',
        confirmButtonColor: '#21c8f6',
        confirmButtonText: 'Mengerti',
        text: messageError,
    })
}

const setModal = (idModal, state) => {
    $(`#${idModal}`).modal(state);
}

const setDatePicker = (className) => {
    $(`.${className}`).datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        orientation: 'bottom'
    });
}

const setYearPicker = (className) => {
    $(`.${className}`).datepicker({
        autoclose: true,
        format: 'yyyy',
        viewMode: 'years',
        minViewMode: 'years',
        orientation: 'bottom',
        showOnFocus: true,
    });
}

const setDropify = (className) => {
    $(`.${className}`).dropify({
        messages: {
            'default': 'Drag and drop file atau klik disini',
            'replace': 'Drag and drop or click to replace',
            'remove':  'Hapus',
            'error':   'Ooops, terjadi kesalahan saat melakukan upload'
        },
        error: {
            'fileSize': 'Ukuran File Foto/Video yg anda upload terlalu besar.',
            'imageFormat': `File Foto/Video yg anda upload tidak sesuai.`,
        }
    });
}

const setSelect2 = (className, containerDropdown) => {
    $(`.${className}`).select2({
        theme: 'bootstrap4',
        placeholder: 'Pilih',
        allowClear: true,
        dropdownParent: $(`#${containerDropdown}`),
    })
}

const commit = ({url, type, hasInputFile = false}, formData = {}) => new Promise((resolve, reject) => {
    const finalSetup = {
        url: url,
        type:type,
        data: formData,
        dataType: 'json',
        beforeSend: (xhr) => {
            return xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        },
    };

    if(hasInputFile === true) {
        finalSetup.contentType =  false;
        finalSetup.processData = false;
    }

    $.ajax(finalSetup)
        .done((response) => {
            resolve(response);
        })
        .fail((xhr, status, errorThrown) => {
            reject(xhr);
        })
});

const confirmDelete = () => new Promise((resolve, reject) => {
    Swal.fire({
        icon: 'warning',
        title: `Anda yakin ingin menghapus data ini?`,
        text: 'Data yang sudah dihapus tidak dapat dikembalikan!',
        showCloseButton: true,
        confirmButtonText: 'Hapus',
        confirmButtonColor: '#dd6b55',
        showCancelButton: true,
        confirmCancelText: 'Batal',
    })
    .then((result) => {
        if(result.isConfirmed) resolve() // return boolean
    })
});
