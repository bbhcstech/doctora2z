{{-- resources/views/admin/district/inline.blade.php --}}
@extends('admin.layout.app')

@section('title', 'Pincodes')

@section('content')
<main id="main" class="main bg-app">
  <div class="pagetitle mb-3">
    <h1 class="page-heading">Pincodes</h1>
    <nav>
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Pincodes</li>
      </ol>
    </nav>
  </div>

  {{-- ADD / EXPORT BAR --}}
  <div class="card card-lite mb-4">
    <div class="card-body py-3 px-3 px-md-4">
      <div class="row g-2 align-items-end">
        <div class="col-12 col-md-3">
          <label class="form-label form-label-xs mb-1">Pincode</label>
          <input id="pin_input"
                 type="tel"
                 class="form-control form-control-compact only-pin"
                 placeholder="Ex:- 712409"
                 inputmode="numeric"
                 maxlength="6"
                 autocomplete="off">
        </div>

        {{-- Cascading selects --}}
        <div class="col-12 col-md-2">
          <label class="form-label form-label-xs mb-1">Country</label>
          <select id="country_select" class="form-select form-control-compact">
            <option value="">Select country</option>
            @foreach(($countries ?? []) as $c)
              <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-12 col-md-2">
          <label class="form-label form-label-xs mb-1">State</label>
          <select id="state_select" class="form-select form-control-compact" disabled>
            <option value="">Select state</option>
          </select>
        </div>

        <div class="col-12 col-md-2">
          <label class="form-label form-label-xs mb-1">District</label>
          <select id="district_select" class="form-select form-control-compact" disabled>
            <option value="">Select district</option>
          </select>
        </div>

        <div class="col-12 col-md-3">
          <label class="form-label form-label-xs mb-1">City</label>
          <select id="city_select" class="form-select form-control-compact" disabled>
            <option value="">Select city</option>
          </select>
          <div class="small text-muted mt-1">choose by name, id handled automatically</div>
        </div>

        <div class="col-12 col-md-2 d-grid">
          <label class="form-label form-label-xs mb-1">&nbsp;</label>
          <button id="addBtn" class="btn btn-brand">Add / Update</button>
        </div>

        <div class="col-12 col-md-4 d-flex gap-2 justify-content-end">
          <a href="{{ route('districts.export.excel') }}" class="btn btn-outline-secondary btn-compact" title="Export Excel">
            <i class="bi bi-file-earmark-excel"></i>
          </a>
          <a href="{{ route('districts.export.csv') }}" class="btn btn-outline-secondary btn-compact" title="Export CSV">
            <i class="bi bi-filetype-csv"></i>
          </a>
          <a href="{{ route('districts.export.pdf') }}" class="btn btn-outline-secondary btn-compact" title="Export PDF">
            <i class="bi bi-file-earmark-pdf"></i>
          </a>
          <button id="printBtn" class="btn btn-outline-secondary btn-compact" title="Print">
            <i class="bi bi-printer"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- PINCODES TABLE (names, not IDs) --}}
  <div class="card card-table">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table id="pincodesTable" class="table table-modern align-middle mb-0">
          <thead>
            <tr>
              <th style="width:44px"><input type="checkbox" id="selectAll"></th>
              <th>ID</th>
              <th>Pincode</th>
              <th>Country</th>
              <th>State</th>
              <th>District</th>
              <th>City</th>
              <th>Created At</th>
              <th>Updated At</th>
              <th style="width:160px">Actions</th>
            </tr>
          </thead>
          <tbody id="pincodeTbody"></tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- STICKY BAR --}}
  <div class="sticky-actions shadow-sm">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <div>
        <button id="deleteSelectedBtn" class="btn btn-square-lg btn-outline-danger">
          <i class="bi bi-trash3 me-2"></i> Delete Selected
        </button>
      </div>
      <div><span class="small text-muted">Showing pincodes with location names</span></div>
    </div>
  </div>
</main>

<div id="toastStack" class="position-fixed top-0 end-0 p-3" style="z-index:1080"></div>

{{-- assets --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

{{-- Select2 for searchable city --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
  .bg-app{ background:#f8f9fb; min-height:100%; padding-bottom:84px; }
  .page-heading{ font-weight:700; color:#222; }
  .card-lite{ background:#fff; border:1px solid #e9ecef; border-radius:14px; box-shadow:0 6px 18px rgba(17,24,39,0.04); }
  .card-table{ background:#fff; border:1px solid #e9ecef; border-radius:14px; overflow:hidden; box-shadow:0 6px 18px rgba(17,24,39,0.04); }
  .table-modern thead tr{ background:#f1f3f5; color:#333; font-weight:700; }
  .table-modern th, .table-modern td{ padding:.75rem .9rem; vertical-align:middle; }
  .form-control-compact{ height:36px; padding:.25rem .5rem; border:1px solid #ddd !important; border-radius:8px; font-size:.9rem; box-shadow:none; }
  .form-label-xs{ font-size:.75rem; color:#6c757d; }
  .btn-compact{ padding:.35rem .6rem; font-size:.85rem; border-radius:8px; }
  .btn-brand{ background:#16a34a; border:1px solid #16a34a; color:#fff; padding:.5rem .9rem; border-radius:10px; }
  .btn-brand:hover{ background:#138a3f; border-color:#138a3f; color:#fff; }
  .sticky-actions{ position:fixed; left:0; right:0; bottom:0; background:#fff; border-top:1px solid #e9ecef; padding:.6rem 1rem; z-index:1030; }
  .cell-names{ line-height:1.1; }
  /* select2 height to match inputs */
  .select2-container--default .select2-selection--single{ height:36px; border:1px solid #ddd; border-radius:8px; }
  .select2-container--default .select2-selection--single .select2-selection__rendered{ line-height:34px; }
  .select2-container--default .select2-selection--single .select2-selection__arrow{ height:34px; }
</style>

<script>
(function(){
  const $ = window.jQuery;
  const CSRF = '{{ csrf_token() }}';

  // districts passed from controller (with state+country eager loaded)
  const rawDistricts = @json($districts ?? []);
  const districts = (Array.isArray(rawDistricts) ? rawDistricts : []).map(d => ({
    id: d.id,
    state_id: d.state_id,
    country_id: d.state?.country?.id ?? null
  }));

  const URLS = {
    listAll: '{{ route("pincodes.all") }}',                  // GET -> rows with *_name
    store:   '{{ route("pincodes.store_or_update") }}',      // POST
    destroyBase: '{{ url("/pincodes") }}',                   // DELETE /pincodes/{id}
    byDistrict: '{{ route("pincodes.by.district") }}',       // GET ?district_id=
    getStates:  '{{ route("districts.ajax.getStates") }}',   // ?country_id=
    getDists:   '{{ route("districts.ajax.getDistricts") }}',// ?state_id=
    getCities:  '{{ route("districts.ajax.getCities") }}',   // ?district_id=
  };

  // toasts
  function toast(msg, type='success', delay=2400){
    const id = 't'+Date.now();
    const cls = type === 'error' ? 'text-danger' : (type==='warning'?'text-warning':'');
    $('#toastStack').append(
      `<div id="${id}" class="toast shadow-sm mb-2" role="alert" data-bs-delay="${delay}">
         <div class="toast-body ${cls}">${msg}</div></div>`
    );
    const t = new bootstrap.Toast(document.getElementById(id)); t.show();
    setTimeout(()=>$('#'+id).remove(), delay+600);
  }

  function sanitizePin(v){
    return (v||'').toString()
      .replace(/[\u200B-\u200D\uFEFF]/g, '')
      .replace(/[^0-9]/g,'')
      .slice(0,6);
  }
  function validPin(v){ return /^\d{6}$/.test(v); }
  $(document).on('input', '.only-pin', function(){
    const v = sanitizePin(this.value); if (this.value!==v) this.value=v;
  });

  // fetch helper
  async function safeJsonFetch(input, init = {}){
    init.headers = Object.assign({'Accept':'application/json','X-CSRF-TOKEN':CSRF}, init.headers||{});
    const res = await fetch(input, init);
    const text = await res.text().catch(()=>null);
    let json=null; try{ json = JSON.parse(text); }catch(e){}
    return { ok: res.ok, status: res.status, json, text };
  }

  // ---- Upper form: Select2 + cascading ----
  $(document).ready(function(){
    if ($.fn.select2) $('#city_select').select2({ width: '100%', placeholder: 'Select city' });
  });

  async function fillStates(countryId){
    $('#state_select').prop('disabled', true).html('<option value="">Select state</option>');
    $('#district_select').prop('disabled', true).html('<option value="">Select district</option>');
    $('#city_select').prop('disabled', true).html('<option value="">Select city</option>').val(null).trigger('change');
    if (!countryId) return;

    const r = await safeJsonFetch(URLS.getStates + '?country_id=' + countryId);
    const list = (r.ok && r.json?.success) ? (r.json.data || []) : [];
    for (const s of list) $('#state_select').append(`<option value="${s.id}">${s.name}</option>`);
    $('#state_select').prop('disabled', false);
  }

  async function fillDistricts(stateId){
    $('#district_select').prop('disabled', true).html('<option value="">Select district</option>');
    $('#city_select').prop('disabled', true).html('<option value="">Select city</option>').val(null).trigger('change');
    if (!stateId) return;

    const r = await safeJsonFetch(URLS.getDists + '?state_id=' + stateId);
    const list = (r.ok && r.json?.success) ? (r.json.data || []) : [];
    for (const d of list) $('#district_select').append(`<option value="${d.id}">${d.name}</option>`);
    $('#district_select').prop('disabled', false);
  }

  async function fillCities(districtId){
    $('#city_select').prop('disabled', true).html('<option value="">Select city</option>').val(null).trigger('change');
    if (!districtId) return;

    const r = await safeJsonFetch(URLS.getCities + '?district_id=' + districtId);
    const list = (r.ok && r.json?.success) ? (r.json.data || []) : [];
    for (const c of list) $('#city_select').append(`<option value="${c.id}">${c.name}</option>`);
    $('#city_select').prop('disabled', false).trigger('change');
  }

  $('#country_select').on('change', function(){ fillStates(this.value); });
  $('#state_select').on('change', function(){ fillDistricts(this.value); });
  $('#district_select').on('change', function(){ fillCities(this.value); });

  // ---- Table rendering ----
  function rowHtml(p){
    const created = p.created_at ?? '';
    const updated = p.updated_at ?? '';
    return `
      <tr data-id="${p.id ?? ''}" data-city-id="${p.city_id ?? ''}">
        <td><input type="checkbox" class="row-check"></td>
        <td>${p.id ?? ''}</td>
        <td><input class="form-control form-control-compact only-pin pin-edit" value="${p.pincode ?? ''}" maxlength="6" inputmode="numeric"></td>
        <td class="cell-names">${p.country_name ?? ''}</td>
        <td class="cell-names">${p.state_name ?? ''}</td>
        <td class="cell-names">${p.district_name ?? ''}</td>
        <td class="cell-names">${p.city_name ?? ''}</td>
        <td>${created}</td>
        <td>${updated}</td>
        <td class="text-nowrap">
          <button class="btn btn-sm btn-outline-success save-row">Save</button>
          <button class="btn btn-sm btn-outline-danger delete-row">Delete</button>
        </td>
      </tr>`;
  }

  async function loadAll(){
    let rows = [];
    const r = await safeJsonFetch(URLS.listAll);
    if (r.ok && r.json && r.json.success) {
      rows = r.json.data || [];
    } else {
      // fallback: aggregate by district (no names in fallback)
      for (const d of districts){
        const rr = await safeJsonFetch(URLS.byDistrict + '?district_id=' + d.id);
        if (rr.ok && rr.json && rr.json.success) {
          const list = rr.json.data || [];
          for (const x of list){
            rows.push({
              id: x.id ?? null,
              pincode: x.pincode ?? '',
              city_id: x.city_id ?? null,
              district_id: d.id,
              state_id: d.state_id,
              country_id: d.country_id,
              created_at: '', updated_at: ''
            });
          }
        }
      }
    }

    const tbody = $('#pincodeTbody').empty();
    rows.sort((a,b)=> (b.id??0) - (a.id??0));
    rows.forEach(p=> tbody.append(rowHtml(p)));

    if (!$.fn.DataTable.isDataTable('#pincodesTable')) {
      $('#pincodesTable').DataTable({ pageLength: 25, order: [] });
    }
  }

  // ---- Add/Update from upper form using city dropdown ----
  $('#addBtn').on('click', async function(){
    const pin = sanitizePin($('#pin_input').val());
    const cityId = ($('#city_select').val() || '').toString();

    if (!validPin(pin)) return toast('pincode must be exactly 6 digits','error');
    if (!cityId) return toast('Please select a city','error');

    const fd = new FormData();
    fd.append('pincode', pin);
    fd.append('city_id', cityId);

    const r = await safeJsonFetch(URLS.store, { method:'POST', body: fd });
    if (r.ok && r.json && r.json.success){
      toast(r.json.message || 'Saved');
      $('#pin_input').val('');
      await loadAll();
    } else {
      toast(r.json?.message || 'Save failed','error');
      console.error('Save error', r);
    }
  });

  // inline save (uses data-city-id that came from API)
  $(document).on('click', '.save-row', async function(){
    const $tr = $(this).closest('tr');
    const id = $tr.data('id') || '';
    const pin = sanitizePin($tr.find('.pin-edit').val());
    const cityId = ($tr.data('city-id')||'').toString();

    if (!validPin(pin)) return toast('pincode must be 6 digits','error');
    if (!cityId) return toast('city_id is missing for this row','error');

    const fd = new FormData();
    if (id) fd.append('id', id);
    fd.append('pincode', pin);
    fd.append('city_id', cityId);

    const r = await safeJsonFetch(URLS.store, { method:'POST', body: fd });
    if (r.ok && r.json && r.json.success){
      toast('Row saved');
      await loadAll();
    } else {
      toast(r.json?.message || 'Save failed','error');
    }
  });

  // delete one
  $(document).on('click', '.delete-row', async function(){
    const $tr = $(this).closest('tr');
    const id = $tr.data('id');
    if (!id) { $tr.remove(); return; }
    if (!confirm('Delete this pincode?')) return;
    const r = await safeJsonFetch(`${URLS.destroyBase}/${id}`, { method:'DELETE' });
    if (r.ok && r.json && r.json.success){ $tr.remove(); toast('Deleted'); }
    else toast(r.json?.message || 'Delete failed','error');
  });

  // bulk delete
  $('#deleteSelectedBtn').on('click', async function(){
    const $rows = $('#pincodeTbody tr').has('.row-check:checked');
    if (!$rows.length) return toast('Select rows to delete','error');
    if (!confirm(`Delete ${$rows.length} selected rows?`)) return;
    let ok=0, fail=0;
    for (const tr of $rows.toArray()){
      const id = $(tr).dataset?.id || $(tr).getAttribute('data-id');
      if (!id){ $(tr).remove(); ok++; continue; }
      const r = await safeJsonFetch(`${URLS.destroyBase}/${id}`, { method:'DELETE' });
      if (r.ok && r.json && r.json.success){ ok++; $(tr).remove(); } else fail++;
    }
    toast(`Deleted ${ok}. ${fail? fail+' failed.':''}`, fail?'error':'success', 4000);
  });

  // select all
  $(document).on('change', '#selectAll', function(){
    $('#pincodeTbody .row-check').prop('checked', this.checked);
  });

  $('#printBtn').on('click', ()=>window.print());

  $(document).ready(loadAll);
})();
</script>
@endsection
