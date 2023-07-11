<!-- Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="toast" class="<?= 'toast align-items-center text-bg-' . $toast['type'] ?>" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        <?=$toast['message']?>
      </div>
      <button type="button" class="<?= 'btn me-2 m-auto text-bg-' . $toast['type'] ?>" data-bs-dismiss="toast" aria-label="Close">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
  </div>
</div>