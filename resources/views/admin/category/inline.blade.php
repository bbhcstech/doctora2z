@extends('admin.layout.app')

@section('title', 'Manage Categories')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<main id="main" class="main bg-app">
  <div class="pagetitle mb-3">
    <h1 class="page-heading">Manage Categories</h1>
  </div>

  {{-- ADD + IMPORT/EXPORT BAR --}}
  <div class="card card-lite mb-4">
    <div class="card-body py-3 px-3 px-md-4">
      <form id="quickAddForm" class="row g-2 align-items-end">
        @csrf
        <div class="col-12 col-md-3">
          <label class="form-label form-label-xs mb-1">Type</label>
          <input id="qa_type" name="type" type="text" class="form-control form-control-compact" placeholder="clinic / doctor / etc" required>
        </div>
        <div class="col-12 col-md-4">
          <label class="form-label form-label-xs mb-1">Name</label>
          <input id="qa_name" name="name" type="text" class="form-control form-control-compact" placeholder="Category name" required>
        </div>
        <div class="col-12 col-md-3">
          <label class="form-label form-label-xs mb-1">Image (optional)</label>
          <input id="qa_image" name="image" type="file" accept="image/*" class="form-control form-control-compact">
        </div>
        <div class="col-12 col-md-2 d-grid">
          <button id="addBtn" type="button" class="btn btn-brand"><i class="bi bi-plus-square me-1"></i>Add</button>
        </div>
      </form>

      <div class="d-flex flex-column flex-md-row gap-2 justify-content-between mt-3">
        <div class="btn-group">
          <a href="{{ route('categories.sample') }}" class="btn btn-outline-secondary btn-compact" data-bs-toggle="tooltip" title="Download Sample CSV">
            <i class="bi bi-filetype-csv me-1"></i> Sample
          </a>
          <label class="btn btn-outline-secondary btn-compact mb-0" for="excel_file" data-bs-toggle="tooltip" title="Import Excel/CSV">
            <i class="bi bi-upload me-1"></i> Import
            <form id="importForm" class="d-none" enctype="multipart/form-data">@csrf
              <input type="file" id="excel_file" name="excel_file" accept=".xls,.xlsx,.csv">
            </form>
          </label>
        </div>

        <div class="btn-group">
          <a href="{{ route('categories.export.csv') }}" class="btn btn-outline-secondary btn-compact" data-bs-toggle="tooltip" title="Export CSV">
            <i class="bi bi-filetype-csv me-1"></i> CSV
          </a>
          <a href="{{ route('categories.export.excel') }}" class="btn btn-outline-secondary btn-compact" data-bs-toggle="tooltip" title="Export Excel">
            <i class="bi bi-filetype-xlsx me-1"></i> Excel
          </a>
          <a href="{{ route('categories.export.pdf') }}" class="btn btn-outline-secondary btn-compact" data-bs-toggle="tooltip" title="Export PDF">
            <i class="bi bi-filetype-pdf me-1"></i> PDF
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- TABLE CARD --}}
  <div class="card card-table clean-card">
    <div class="card-body p-0">
      <div class="table-controls d-flex justify-content-between align-items-center p-3">
        <div>
          <label class="me-2 small">Show</label>
          <select id="dt_length" class="form-select form-select-compact d-inline-block" style="width:78px">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
          </select>
          <span class="ms-2 small">entries</span>
        </div>

        <div>
          <label class="me-2 small">Search:</label>
          <input id="dt_search" type="search" class="form-control form-control-compact" placeholder="Search categories">
        </div>
      </div>

      <div class="table-responsive p-2">
        <table class="table table-modern align-middle mb-0" id="categoriesTable" style="width:100%">
          <thead>
            <tr>
              <th style="width:44px" class="text-center"><input type="checkbox" id="selectAll"></th>
              <th style="width:80px">ID</th>
              <th>Type</th>
              <th>Name</th>
              <th style="width:200px">Image</th>
              <th style="width:160px">Actions</th>
            </tr>
          </thead>
          <tbody></tbody> {{-- DataTables fills --}}
        </table>
      </div>
    </div>
  </div>

  {{-- STICKY ACTION BAR --}}
  <div class="sticky-actions shadow-sm">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <button id="bulkDeleteBtn" class="btn btn-square-lg btn-outline-danger" disabled>
        <i class="bi bi-trash3 me-2"></i> Delete Selected
      </button>
      <button id="saveAllBtn" class="btn btn-brand">
        <i class="bi bi-check2-square me-2"></i> Save All Changes
      </button>
    </div>
  </div>
</main>

{{-- Toast stack + Confirm modal --}}
<div id="toastStack" class="position-fixed top-0 end-0 p-3" style="z-index:1080"></div>

<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-0">
        <h6 class="modal-title fw-semibold" id="confirmTitle">Confirm</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-0" id="confirmMessage"></div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" id="confirmCancel">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmOk">Yes</button>
      </div>
    </div>
  </div>
</div>

{{-- Assets --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

{{-- DataTables (Bootstrap 5) --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css"/>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<style>
  .bg-app{ background:#f8f9fb; min-height:100%; padding-bottom:84px; }
  .page-heading{ font-weight:700; color:#222; }

  .card-lite{ background:#fff; border:1px solid #e9ecef; border-radius:14px; box-shadow:0 6px 18px rgba(17,24,39,0.04); }
  .card-table{ background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 6px 18px rgba(17,24,39,0.04); }
  .clean-card { border: none; box-shadow: none; background: transparent; padding:0; }

  .table-modern thead tr{ background:transparent; color:#333; font-weight:700; }
  .table-modern thead th{ border-bottom: none; font-size:.92rem; padding:.85rem .9rem; }
  .table-modern tbody tr{ background:transparent; border-bottom:1px solid rgba(0,0,0,0.04); }
  .table-modern tbody tr:hover{ background:#fbfdff; }
  .table-modern th, .table-modern td{ padding:.85rem .9rem; vertical-align:middle; border:none; }

  .img-cell { display:flex; align-items:center; gap:1rem; }
  .thumb { width:68px; height:68px; object-fit:cover; border-radius:8px; border:1px solid rgba(0,0,0,0.06); }
  .upload-btn { display:inline-flex; align-items:center; gap:.5rem; padding:.35rem .6rem; border-radius:8px; border:1px solid #ddd; background:#fff; cursor:pointer; }
  .upload-btn:hover{ background:#f8fafb; }
  .upload-spinner{ width:16px; height:16px; border:.12em solid currentColor; border-right-color:transparent; border-radius:50%; animation:spin .75s linear infinite; display:inline-block; vertical-align:middle; }
  @keyframes spin{ to{ transform:rotate(360deg);} }

  .form-control-compact{ height:36px; padding:.25rem .5rem; border:1px solid #ddd !important; border-radius:8px; font-size:.9rem; box-shadow:none; }
  .form-label-xs{ font-size:.75rem; color:#6c757d; }

  .btn-compact{ padding:.35rem .6rem; font-size:.85rem; border-radius:8px; }
  .btn-brand{ background:#16a34a; border:1px solid #16a34a; color:#fff; padding:.5rem .9rem; border-radius:10px; }
  .btn-brand:hover{ background:#138a3f; border-color:#138a3f; color:#fff; }

  .btn-square{ width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center; padding:0; border-radius:8px; }
  .btn-square-lg{ height:40px; padding:.35rem .9rem; border-radius:10px; }
  .btn-outline-success{ --bs-btn-color:#16a34a; --bs-btn-border-color:#16a34a; --bs-btn-hover-bg:#16a34a10; }
  .btn-outline-danger{ --bs-btn-color:#dc3545; --bs-btn-border-color:#dc3545; --bs-btn-hover-bg:#dc354510; }

  .sticky-actions{ position:fixed; left:0; right:0; bottom:0; background:#fff; border-top:1px solid #e9ecef; padding:.6rem 1rem; z-index:1030; }

  .edited{ box-shadow: inset 0 0 0 9999px rgba(255, 244, 186, .5); }

  @media (max-width:900px){
    .table-modern thead{ font-size:.8rem }
  }

</style>

<script>
(function(){
  const $ = window.jQuery;

  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } });
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

  function showToast(message, {type='success', title='', delay=2500} = {}) {
    const id = 't' + Date.now() + Math.random().toString(16).slice(2);
    const header = title ? `<div class="toast-header border-0"><strong class="me-auto">${title}</strong><button type="button" class="btn-close" data-bs-dismiss="toast"></button></div>` : '';
    const bodyClass = title ? '' : (type==='success'?'bg-success text-white':type==='danger'?'bg-danger text-white':'bg-info text-dark');
    const html = `<div id="${id}" class="toast shadow-sm mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="${delay}">${header}<div class="toast-body ${bodyClass}">${message}</div></div>`;
    document.getElementById('toastStack').insertAdjacentHTML('beforeend', html);
    const el = document.getElementById(id); new bootstrap.Toast(el).show(); el.addEventListener('hidden.bs.toast', ()=> el.remove());
  }
  const notify = { ok:(m,t='Success')=>showToast(m,{type:'success',title:t}), err:(m,t='Error')=>showToast(m,{type:'danger',title:t}), info:(m,t='Info')=>showToast(m,{type:'info',title:t}) };

  function imgUrl(name){
    if(!name) return '{{ asset('admin/uploads/category/default-category.jpg') }}';
    if (typeof name === 'string' && name.startsWith('http')) return name;
    return '{{ asset('admin/uploads/category') }}/' + name;
  }

  const selectedIds = new Set();
  const editedIds = new Set();

  let table = $('#categoriesTable').DataTable({
    serverSide: true,
    processing: true,
    ajax: { url: "{{ route('categories.list') }}", type: "GET" },
    pageLength: 10,
    lengthChange: false,
    searching: false,
    order: [[1,'desc']],
    createdRow: function(row, data){ $(row).attr('data-id', data.id).attr('id','row-'+data.id); },
    columns: [
      { data:null, orderable:false, searchable:false, className:'text-center',
        render: (_,__,row)=> `<input type="checkbox" class="categoryCheckbox" data-id="${row.id}">`
      },
      { data: 'id', name:'id' },
      { data: 'type', name:'type', render:(d)=> `<span contenteditable="true" class="editable" data-field="type">${d??''}</span>` },
      { data: 'name', name:'name', render:(d)=> `<span contenteditable="true" class="editable" data-field="name">${d??''}</span>` },
      { data: 'image', orderable:false, searchable:false,
        render: (d,__,row)=> `
          <div class="img-cell">
            <img class="thumb" src="${imgUrl(d)}" alt="${row.name??''}">
            <div>
              <button class="upload-btn" data-id="${row.id}" title="Upload image for ${row.name ?? ''}">
                <i class="bi bi-upload"></i> Update
              </button>
              <input type="file" accept="image/*" class="d-none upload-input" id="upload-input-${row.id}" data-id="${row.id}">
            </div>
          </div>`
      },
      { data:null, orderable:false, searchable:false, className:'text-nowrap',
        render: (_,__,row)=> `
          <button type="button" class="btn btn-square btn-outline-success saveBtn" data-id="${row.id}" title="Save Row">
            <i class="bi bi-check2"></i>
          </button>
          <button type="button" class="btn btn-square btn-outline-danger individualDeleteBtn ms-2" data-id="${row.id}" title="Delete Row">
            <i class="bi bi-trash3"></i>
          </button>`
      }
    ],
    drawCallback: function(){
      document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
      const visible = $('#categoriesTable tbody .categoryCheckbox').length;
      const checked = $('#categoriesTable tbody .categoryCheckbox:checked').length;
      $('#selectAll').prop('checked', visible && checked === visible);
      editedIds.forEach(id => $('#row-'+id).addClass('edited'));
    }
  });

  $('#dt_length').on('change', function(){ table.page.len(Number(this.value)).draw(false); });
  let sTimer = null;
  $('#dt_search').on('input', function(){ clearTimeout(sTimer); sTimer = setTimeout(()=> { table.search(this.value).draw(); }, 300); });

  $('#categoriesTable').on('input', '.editable', function(){ const id = $(this).closest('tr').data('id'); editedIds.add(id); $(this).closest('tr').addClass('edited'); });

  // Upload handlers (unchanged)
  $(document).on('click', '.upload-btn', function(e){
    e.preventDefault();
    const id = $(this).data('id');
    const input = document.getElementById('upload-input-' + id);
    if (input) input.click();
  });

  $(document).on('change', '.upload-input', function(){
    const id = $(this).dataset?.id || $(this).attr('data-id') || $(this).data('id');
    const file = this.files?.[0];
    if (!file) return;
    const $row = $('#row-' + id);
    const thumb = $row.find('img.thumb');
    const $btn = $row.find('.upload-btn');
    const oldHtml = $btn.html();
    $btn.prop('disabled', true).html('<span class="upload-spinner" aria-hidden="true"></span> Uploading');

    const fd = new FormData();
    fd.append('image', file);
    fd.append('category_id', id);

    $.ajax({
      url: '{{ route('categories.uploadImage') }}',
      method: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      dataType: 'json'
    }).done(res => {
      if (res.success && (res.filename || res.url)) {
        const newUrl = res.url ?? ('{{ asset('admin/uploads/category') }}/' + res.filename);
        thumb.attr('src', newUrl);
        notify.ok(res.message || 'Image uploaded');
        editedIds.delete(Number(id));
        $row.removeClass('edited');
      } else {
        notify.err(res.message || 'Upload failed');
      }
    }).fail(xhr => {
      const msg = xhr.responseJSON?.message || 'Upload failed';
      notify.err(msg);
    }).always(() => {
      $btn.prop('disabled', false).html(oldHtml);
      $(this).val('');
    });
  });

  // Save single row
  $('#categoriesTable').on('click', '.saveBtn', function(){
    const id = $(this).data('id');
    const $tr = $('#row-'+id);
    const type = $tr.find('[data-field="type"]').text().trim();
    const name = $tr.find('[data-field="name"]').text().trim();
    if (!name) return notify.info('Name is required.');
    const fd = new FormData();
    fd.append('type', type); fd.append('name', name);
    $.ajax({
      url: '{{ route('categories.update', ['id' => ':id']) }}'.replace(':id', id),
      method: 'POST',
      data: fd,
      processData: false,
      contentType: false
    }).done(res=>{
      if (res.success) {
        notify.ok(res.message || 'Saved');
        editedIds.delete(Number(id));
        $tr.removeClass('edited');
        table.ajax.reload(null,false);
      } else notify.err(res.message || 'Save failed');
    }).fail(xhr => notify.err(xhr.responseJSON?.message || 'Save failed'));
  });

  // individual delete
  $('#categoriesTable').on('click', '.individualDeleteBtn', function(){
    const id = $(this).data('id');
    confirmAction('Delete this category?', {okText:'Delete', okClass:'btn-danger'}).then(ok => {
      if (!ok) return;
      $.ajax({
        url: '{{ route('categories.destroy', ['id' => ':id']) }}'.replace(':id', id),
        method: 'DELETE'
      }).done(res => {
        if (res.success) {
          notify.ok(res.message || 'Deleted');
          selectedIds.delete(id); editedIds.delete(id);
          table.ajax.reload(null,false);
        } else notify.err(res.message || 'Delete failed');
      }).fail(()=> notify.err('Delete failed'));
    });
  });

  // select / bulk selection
  $('#selectAll').on('change', function(){
    const check = this.checked;
    $('#categoriesTable tbody .categoryCheckbox').each(function(){
      const id = Number($(this).data('id'));
      $(this).prop('checked', check);
      if(check) selectedIds.add(id); else selectedIds.delete(id);
    });
    toggleBulkDeleteButton();
  });
  $('#categoriesTable').on('change', '.categoryCheckbox', function(){
    const id = Number($(this).data('id'));
    if(this.checked) selectedIds.add(id); else selectedIds.delete(id);
    toggleBulkDeleteButton();
  });
  function toggleBulkDeleteButton(){ $('#bulkDeleteBtn').prop('disabled', selectedIds.size === 0); }

  /* === BULK DELETE (use FormData + method spoofing for max reliability) === */
$('#bulkDeleteBtn').off('click').on('click', function(){
  const ids = Array.from(selectedIds).map(Number).filter(Boolean);
  if (!ids.length) return notify.warn('No items selected.');

  confirmAction(`Delete ${ids.length} selected item(s)?`, {okText:'Delete', okClass:'btn-danger'}).then(ok => {
    if (!ok) return;

    // Build FormData with array-style inputs and method spoofing
    const fd = new FormData();
    ids.forEach(id => fd.append('category_ids[]', id));
    fd.append('_method', 'DELETE'); // method spoof for Laravel

    const $btn = $(this);
    const oldHtml = $btn.html();
    $btn.prop('disabled', true).html('<span class="upload-spinner" aria-hidden="true"></span> Deleting');

    $.ajax({
      url: '{{ route("categories.bulkDelete") }}',
      method: 'POST',                // POST with _method=DELETE is most compatible
      data: fd,
      processData: false,
      contentType: false,
      dataType: 'json',
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
    })
    .done(res => {
      if (res && res.success) {
        notify.ok(res.message || 'Deleted selected items.');
        ids.forEach(id => { selectedIds.delete(id); editedIds.delete(id); });
        toggleBulkDeleteButton();
        table.ajax.reload(null, false);
      } else {
        notify.err(res?.message || 'Bulk delete failed');
      }
    })
    .fail(xhr => {
      const m = xhr.responseJSON?.message || 'Bulk delete failed';
      notify.err(m);
    })
    .always(() => {
      $btn.prop('disabled', false).html(oldHtml);
    });
  });
});


  /* === SAVE ALL CHANGES (visible rows only, with off-page warning) === */
  $('#saveAllBtn').off('click').on('click', function(){
    if (!editedIds.size) return notify.info('No changes to save.');

    const allEdited = Array.from(editedIds);
    const visibleEdited = allEdited.filter(id => $('#row-'+id).length > 0);
    const offpageCount = allEdited.length - visibleEdited.length;

    const proceedToSave = () => {
      if (!visibleEdited.length) {
        notify.info('No visible edits to save on this page. Navigate to the edited rows to save them.');
        return;
      }

      const idsQueue = visibleEdited.slice();
      let successCount = 0;
      let failCount = 0;

      const saveNext = () => {
        if (!idsQueue.length) {
          if (failCount) notify.warn(`Saved ${successCount}, ${failCount} failed.`);
          else notify.ok(`Saved ${successCount} rows.`);
          table.ajax.reload(null, false);
          return;
        }

        const id = idsQueue.shift();
        const $tr = $('#row-'+id);
        if (!$tr.length) return saveNext();

        const payload = {};
        $tr.find('.editable').each(function(){
          const key = $(this).data('field');
          payload[key] = $(this).text().trim();
        });

        if (payload.name === '' || typeof payload.name === 'undefined') {
          failCount++;
          notify.warn(`Row ${id}: name is required. Skipping.`);
          return saveNext();
        }

        const fd = new FormData();
        Object.keys(payload).forEach(k => fd.append(k, payload[k]));

        $.ajax({
          url: '{{ route("categories.update", ["id" => ":id"]) }}'.replace(':id', id),
          method: 'POST',
          data: fd,
          processData: false,
          contentType: false,
          dataType: 'json'
        })
        .done(res => {
          if (res && res.success) {
            successCount++;
            editedIds.delete(id);
            $tr.removeClass('edited');
          } else {
            failCount++;
            console.warn('Save failure', id, res);
          }
        })
        .fail(xhr => {
          failCount++;
          console.error('Save failed', id, xhr);
        })
        .always(() => setTimeout(saveNext, 80));
      };

      saveNext();
    };

    if (offpageCount > 0) {
      confirmAction(
        `There are ${allEdited.length} edited rows, but ${offpageCount} are not visible on this page. Save only the ${visibleEdited.length} visible change(s) now?`,
        { title: 'Save visible changes?', okText:'Save visible', cancelText:'Cancel', okClass:'btn-success' }
      ).then(ok => { if (ok) proceedToSave(); });
    } else {
      proceedToSave();
    }
  });

  // add new
  $('#addBtn').on('click', function(){
    const fd = new FormData(document.getElementById('quickAddForm'));
    $.ajax({ url: '{{ route('categories.store') }}', method:'POST', data: fd, processData:false, contentType:false })
      .done(res=> { if(res.success){ notify.ok(res.message || 'Category created'); $('#quickAddForm')[0].reset(); table.ajax.reload(null,false); } else notify.err(res.message || 'Failed to create category'); })
      .fail(xhr=> notify.err(xhr.responseJSON?.message || 'Failed to create category'));
  });

  // import handler
  document.getElementById('excel_file')?.addEventListener('change', function(){
    const fd = new FormData(document.getElementById('importForm'));
    $.ajax({ url: '{{ route('categories.import') }}', method:'POST', data: fd, processData:false, contentType:false })
      .done(res=>{ res.success ? notify.ok(res.message || 'Import complete') : notify.err(res.message || 'Import failed'); setTimeout(()=> table.ajax.reload(null,false), 400); })
      .fail(xhr=> notify.err(xhr.responseJSON?.message || 'Import failed'));
  });

  // confirmAction helper
  function confirmAction(message, {title='Confirm', okText='Yes', cancelText='Cancel', okClass='btn-danger'} = {}) {
    return new Promise(resolve => {
      const m = document.getElementById('confirmModal');
      document.getElementById('confirmTitle').textContent = title;
      document.getElementById('confirmMessage').textContent = message;
      const ok = document.getElementById('confirmOk'), cc = document.getElementById('confirmCancel');
      ok.textContent = okText; cc.textContent = cancelText; ok.className = 'btn ' + okClass;
      const modal = new bootstrap.Modal(m);
      const cleanup = ()=> { ok.onclick=null; cc.onclick=null; m.removeEventListener('hidden.bs.modal', onHide); };
      const onHide = ()=> { cleanup(); resolve(false); };
      m.addEventListener('hidden.bs.modal', onHide);
      ok.onclick = ()=> { cleanup(); modal.hide(); resolve(true); };
      cc.onclick = ()=> { cleanup(); modal.hide(); resolve(false); };
      modal.show();
    });
  }

})();
</script>
@endsection
