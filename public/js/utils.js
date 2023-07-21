const bootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-danger',
    cancelButton: 'btn btn-secondary'
  },
  buttonsStyling: false
})

const deleteModalOptions = {
  icon: 'warning',
  confirmButtonText: 'Ok',
  showCancelButton: true,
  cancelButtonText: 'Cancelar',
  reverseButtons: true,
  showLoaderOnConfirm: true
}

const showDeleteModal = ({
  title = 'Eliminar registro',
  text = '¿Está seguro? Está acción es irreversible',
  source = null,
  id
}) => {
  bootstrapButtons
    .fire({
      ...deleteModalOptions,
      title,
      text,
      preConfirm: async () => {
        try {
          const formData = new FormData()
          formData.append('method', 'delete')
          formData.append('id', id)
          const response = await fetch(`http://incomecontrol.test/${source}/delete/${id}`, {
            method: 'POST',
            body: formData
          })
          if (!response.ok) {
            showErrorModal(response.statusText)
          }
          return await response.json()
        } catch (error) {
          return showErrorModal(error)
        }
      },
      allowOutsideClick: () => !Swal.isLoading()
    })
    .then((result) => {
      if (result.isConfirmed) {
        if (result.value.deleted) {
          Swal.fire({ text: 'Eliminado correctamente', icon: 'success', timer: 2000 }).then(() => {
            location.reload()
          })
        } else {
          Swal.fire({ text: 'Ha ocurrido un error', icon: 'error' })
        }
      }
    })
}

const showErrorModal = (message = 'Ha ocurrido un error') => {
  Swal.fire({ text: message, icon: 'error', color: 'red' })
}

// SOLO TEST
const wait = (seconds) => {
  return new Promise((resolve) => setTimeout(resolve, seconds * 1000))
}
