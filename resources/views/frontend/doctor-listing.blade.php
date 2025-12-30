@extends('partials.app')

@section('title', 'Doctor Listing')

@section('content')
<div class="container-xxl py-5 bg-dark page-header mb-5">
  <div class="container my-5 pt-5 pb-4">
    <h1 class="display-3 text-white mb-3 animated slideInDown">Doctor Listing</h1>
  </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<style>
  .card-page{max-width:1000px;margin:18px auto 48px;background:#e8eef8;padding:18px;border-radius:10px;}
  .card-page h5{font-weight:700;margin-bottom:14px;}
  .small-label{font-size:.92rem;display:block;margin-bottom:.35rem;color:#333;}
  .form-compact .form-control,.form-compact .form-select{height:42px;padding:.45rem .8rem;border-radius:6px;}
  .row.gx-3>[class*='col-']{margin-bottom:.8rem;}
  .divider{border-top:1px solid #d0d7e3;margin:14px 0;padding-top:12px;}
  .btn-save{background:#fff;color:#000;border:1px solid #000;padding:.55rem 1.2rem;border-radius:8px;font-weight:600;cursor:pointer;font-size:14px;transition:.3s;}
  .btn-save:hover{background:#f3f4f6;}
  .btn-go-profile{background:#0d6efd;color:#fff;border:1px solid #0d6efd;padding:.45rem 1rem;border-radius:8px;font-weight:600;margin-left:10px;}
  .text-small-muted{font-size:.85rem;color:#6b7280;margin-top:.3rem;}
  .ajax-error { color: #b91c1c; margin-top: .5rem; }
  .ajax-success { color: #065f46; margin-top: .5rem; }

  /* disabled Save button look */
  .btn-save[disabled] { opacity: 0.6; cursor: not-allowed; }

  /* Hover / focus popup for email-help */
  .hover-wrap { position: relative; display:block; }
  .hover-help-popup {
    display: none;
    position: absolute;
    left: 0;
    top: calc(100% + 10px);
    min-width: 260px;
    max-width: 360px;
    background: #ffffff;
    border-radius: 6px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    padding: 10px 12px;
    color: #1f2937;
    font-size: 0.92rem;
    line-height: 1.25;
    z-index: 40;
  }
  .hover-wrap:hover .hover-help-popup,
  .hover-wrap:focus-within .hover-help-popup { display:block; }
  .hover-help-indicator { display:none; }

  @media (max-width: 600px) {
    .hover-help-popup { position: static; margin-top:8px; box-shadow:none; border:1px solid #e5e7eb; }
  }
  @media(max-width:767px){.card-page{padding:14px;}}
</style>

<div class="container">
  <div class="card-page">

    <div id="serverErrors" style="display:none;" class="alert alert-danger"></div>
    <div id="serverSuccess" style="display:none;" class="alert alert-success"></div>

    @if ($errors->any())
      <div class="alert alert-danger">
          <ul class="mb-0">
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif

    @if (session('success'))
      <div class="alert alert-success">
          {{ session('success') }}
      </div>
    @endif

    <form id="doctorForm" class="form-compact" action="{{ route('listdoctorstore') }}" method="POST" enctype="multipart/form-data" novalidate>
      @csrf

      {{-- Basic Info --}}
      <div class="row gx-3">
        <div class="col-md-3">
          <label for="email" class="small-label">Email <span class="text-danger">*</span></label>

          <div class="hover-wrap" tabindex="-1" aria-describedby="emailHelpPopup">
            <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required>

            <div id="emailHelpPopup" class="hover-help-popup" role="region" aria-live="polite">
              Must be a valid email address (e.g., example@domain.com).<br>
              Each doctor can use only one unique email for registration.<br>
              If this email is already registered,<br>
              please use a different one.
            </div>
          </div>

          <div id="emailStatus" class="ajax-error" style="display:none;margin-top:8px;"></div>
        </div>

        <div class="col-md-3">
          <label for="name" class="small-label">Name <span class="text-danger">*</span></label>
          <input id="name" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="col-md-3">
          <label for="phone_number" class="small-label">Contact Number 1 <span class="text-danger">*</span></label>
          <input id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number') }}"
                 required pattern="^[0-9]{10}$" title="Enter exactly 10 digits" inputmode="numeric" maxlength="10">
          <div id="phone1Error" class="ajax-error" style="display:none;"></div>
        </div>

        <div class="col-md-3">
  <label for="contact_number_2" class="small-label">Contact Number 2</label>
  <input id="contact_number_2" 
         name="contact_number_2" 
         class="form-control"
         value="{{ old('contact_number_2') }}"
         maxlength="10"
         inputmode="numeric"
         pattern="[0-9]{0,10}" 
         title="Enter up to 10 digits only.">

  <div id="contact2Error" style="display:none;" class="ajax-error text-danger small mt-1"></div>
</div>


      {{-- Professional Info --}}
      <div class="row gx-3 mt-3">
        <div class="col-md-4">
          <label for="category_id" class="small-label">Category <span class="text-danger">*</span></label>
          <select name="category_id" id="category_id" class="form-select" required>
            <option value="">Select Category</option>
            @foreach($category as $cat)
              <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <label for="degree" class="small-label">Degree <span class="text-danger">*</span></label>
          <input id="degree" name="degree" class="form-control" value="{{ old('degree') }}" required>
        </div>

        <div class="col-md-4">
          <label for="profile_picture" class="small-label">Profile Picture</label>
          <input id="profile_picture" type="file" name="profile_picture" class="form-control" accept="image/png,image/jpeg">
          <small class="text-muted">JPG, PNG allowed (optional)</small>
        </div>
      </div>

      {{-- Pincode --}}
      <div class="row gx-3 mt-3">
        <div class="col-md-6">
          <label for="pincode" class="small-label">Pincode <span class="text-danger"> </span></label>
          <div class="d-flex gap-2">
            <input id="pincode" name="pincode" class="form-control" value="{{ old('pincode') }}" placeholder="Enter 6-digit Pincode" maxlength="6" required>
            <button type="button" id="pincodeLookupBtn" class="btn btn-outline-primary">Lookup</button>
          </div>
          <div id="pincodeHelp" class="text-small-muted">Type a 6-digit pincode and press Lookup (or move away).</div>
          <input type="hidden" name="pincode_id" id="pincode_id" value="{{ old('pincode_id') ?? '' }}">
        </div>

        <div class="col-md-6">
          <label for="address" class="small-label">Address</label>
          <input id="address" name="address" class="form-control" value="{{ old('address') }}">
        </div>
      </div>

      {{-- Location --}}
      <div class="row gx-3 mt-3">
        <div class="col-md-3">
          <label for="country_id" class="small-label">Country <span class="text-danger">*</span></label>
          <select name="country_id" id="country_id" class="form-select" required>
            <option value="">Select Country</option>
            @foreach($countries as $c)
              <option value="{{ $c->id }}" @selected(old('country_id') == $c->id)>{{ $c->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3">
          <label for="state_id" class="small-label">State <span class="text-danger">*</span></label>
          <select name="state_id" id="state_id" class="form-select" required>
            <option value="">Select State</option>
            @foreach($state as $s)
              <option value="{{ $s->id }}" @selected(old('state_id') == $s->id) data-country="{{ $s->country_id }}">{{ $s->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3">
          <label for="district_id" class="small-label">District <span class="text-danger">*</span></label>
          <select name="district_id" id="district_id" class="form-select" required>
            <option value="">Select District</option>
          </select>
        </div>

        <div class="col-md-3">
          <label for="city_id" class="small-label">Area <span class="text-danger">*</span></label>

          <!-- Area dropdown (no 'Others' option anymore) -->
          <select name="city_id" id="city_id" class="form-select" required>
            <option value="">Select City</option>
            @foreach($city as $ct)
              <option value="{{ $ct->id }}" @selected(old('city_id') == $ct->id) data-district="{{ $ct->district_id }}">{{ $ct->name }}</option>
            @endforeach
          </select>

          <!-- "Can't find area?" toggle that enables manual input -->
          <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" value="1" id="cantFindArea" name="city_manual_toggle" autocomplete="off">
            <label class="form-check-label small" for="cantFindArea">
              can't find area?
            </label>
          </div>

          <!-- Always visible text input, disabled by default -->
          <div id="cityOtherDiv" style="margin-top:8px;">
            <input type="text" id="city_other" name="city_other" class="form-control" placeholder="Enter other area name" value="{{ old('city_other') }}" disabled>
          </div>
        </div>
      </div>

      <div class="divider"></div>

      {{-- Buttons --}}
      <div class="row gx-3 mt-3">
        <div class="col-md-6 d-flex justify-content-start">
          <button type="reset" class="btn btn-secondary">Clear</button>
        </div>

        <div class="col-md-6 d-flex justify-content-end align-items-center">
          <!-- Confirmation checkbox (new) -->
          <div class="me-3" style="max-width:420px;">
            <div class="form-check d-flex align-items-start">
              <input class="form-check-input" type="checkbox" id="confirmReview" name="confirm_review" value="1" />
              <label class="form-check-label small ms-2" for="confirmReview" id="confirmReviewLabel">
                I have reviewed all the information and confirm that everything entered above is correct.
              </label>
            </div>
            <div id="confirmError" class="ajax-error" style="display:none;margin-top:.45rem;">
              Please confirm that all details are correct before submitting.
            </div>
          </div>

          <button type="submit" id="saveBtn" class="btn btn-save" disabled>Save Doctor</button>

          <a href="#" id="goToProfileBtn" class="btn btn-go-profile" style="display:none;" target="_blank">Go to Profile</a>

          @if(session('new_doctor_id'))
            <a href="{{ route('doctor.details', session('new_doctor_id')) }}" class="btn btn-go-profile" style="display:inline-block;">Go to Profile</a>
          @endif
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
(function($){
  $(function(){
    // Ensure csrf meta token exists
    (function ensureMetaToken() {
      if (!document.querySelector('meta[name="csrf-token"]')) {
        const tokenInput = document.querySelector('input[name="_token"]');
        if (tokenInput) {
          const meta = document.createElement('meta');
          meta.setAttribute('name', 'csrf-token');
          meta.setAttribute('content', tokenInput.value);
          document.head.appendChild(meta);
        }
      }
    })();

    function getCsrfToken() {
      return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
             || document.querySelector('input[name="_token"]')?.value
             || '{{ csrf_token() }}';
    }

    // Escape helper to prevent HTML injection into options
    function escapeHtml(str) {
      if (str === null || str === undefined) return '';
      return String(str).replace(/[&<>"'`=/]/g, function(s) {
        return ({
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;',
          "'": '&#39;',
          '/': '&#x2F;',
          '`': '&#x60;',
          '=': '&#x3D;'
        })[s];
      });
    }

    const OLD_STATE = @json(old('state_id'));
    const OLD_DISTRICT = @json(old('district_id'));
    const OLD_CITY = @json(old('city_id'));
    const OLD_PIN = @json(old('pincode') ?? '');
    const OLD_PIN_ID = @json(old('pincode_id') ?? '');
    const OLD_CITY_OTHER = @json(old('city_other') ?? '');

    // Initialize Select2
    $('#category_id,#country_id,#state_id,#district_id,#city_id').select2({ width:'100%' });

    if (OLD_STATE) { $('#state_id').val(OLD_STATE).trigger('change'); }
    if (OLD_CITY)  { $('#city_id').val(OLD_CITY).trigger('change.select2'); }
    if (OLD_PIN_ID){ $('#pincode_id').val(OLD_PIN_ID); }
    if (OLD_PIN)   { $('#pincode').val(OLD_PIN); }
    if (OLD_CITY_OTHER) {
      // If old city_other exists (editing or validation fail), enable manual input and disable dropdown
      $('#cantFindArea').prop('checked', true);
      $('#city_other').prop('disabled', false).val(OLD_CITY_OTHER);
      $('#city_id').prop('disabled', true).val('').trigger('change.select2');
    }

    function resetDistrict() {
      $('#district_id').html('<option value="">Select District</option>').trigger('change.select2');
    }

    // Load districts by state
    function loadDistrictsForState(stateId, selectedDistrictId = null, selectedDistrictName = null) {
      const $dist = $('#district_id');
      if (!stateId) { resetDistrict(); return; }
      $dist.html('<option>Loading...</option>').trigger('change.select2');
      $.getJSON("{{ url('/get-districts') }}/" + encodeURIComponent(stateId))
        .done(function(res){
          let html = '<option value="">Select District</option>';
          if (Array.isArray(res)) {
            res.forEach(d => html += '<option value="'+ escapeHtml(d.id) +'">'+ escapeHtml(d.name) +'</option>');
          }
          $dist.html(html).trigger('change.select2');

          if (selectedDistrictId) {
            $dist.val(selectedDistrictId).trigger('change.select2');
          } else if (selectedDistrictName) {
            setTimeout(function(){ setSelectByText($dist, selectedDistrictName); }, 60);
          } else if (OLD_DISTRICT) {
            $dist.val(OLD_DISTRICT).trigger('change.select2');
          }
        })
        .fail(function(){ resetDistrict(); });
    }

    // Country change
    $('#country_id').on('change', function(){
      const countryId = $(this).val();
      const $state = $('#state_id');
      const $district = $('#district_id');
      const $city = $('#city_id');

      $state.val('').trigger('change.select2');
      $district.html('<option value="">Select District</option>').trigger('change.select2');
      $city.val('').trigger('change.select2');

      $state.find('option').each(function(){
        const ok = !countryId || $(this).data('country') == countryId || $(this).val() === '';
        $(this).toggle(ok);
      });
      $state.trigger('change.select2');
    });

    // State change
    $('#state_id').on('change', function(){
      const stateId = $(this).val();
      $('#city_id').val('').trigger('change.select2');
      $('#district_id').html('<option value="">Select District</option>').trigger('change.select2');
      loadDistrictsForState(stateId);
    });

    // District change
    $('#district_id').on('change', function(){
      const distId = $(this).val();
      const pin = $('#pincode').val().trim();

      if (distId) {
        loadAreasByDistrict(distId, isValidPin(pin) ? pin : null);
      } else {
        $('#city_id').html('<option value="">Select Area</option>').trigger('change.select2');
        // do not hide cityOtherDiv — it's always visible, but ensure manual toggle keeps correct state
        if (!$('#cantFindArea').is(':checked')) {
          $('#city_other').prop('disabled', true).val('');
          $('#city_id').prop('disabled', false);
        }
      }
    });

    function loadAreasByDistrict(districtId, pincode = null) {
      const $city = $('#city_id');
      try { $city.select2('destroy'); } catch(e){}
      $city.html('<option>Loading...</option>');

      const url = "{{ url('/get-areas') }}/" + encodeURIComponent(districtId) + (pincode ? "/" + encodeURIComponent(pincode) : "");

      $.getJSON(url)
        .done(function(res){
          let html = '<option value="">Select Area</option>';
          if (res && res.success && Array.isArray(res.areas)) {
            res.areas.forEach(a => {
              html += `<option value="${escapeHtml(a.id)}">${escapeHtml(a.name)}</option>`;
            });
          }
          // DO NOT append 'Others' option anymore
          $city.html(html).select2({ width: '100%' });

          // restore previous selection if present
          if (OLD_CITY) {
            setTimeout(function(){ $('#city_id').val(OLD_CITY).trigger('change.select2'); }, 80);
          }
        })
        .fail(function(){
          $city.html('<option value="">Error loading areas</option>').select2({ width: '100%' });
        });
    }

    // --- Mutual exclusion for area fields ---
    var $citySelect = $('#city_id');
    var $cityOther  = $('#city_other');
    var $toggle     = $('#cantFindArea');

    // initial safety: ensure consistent state
    function initAreaMutualExclusion() {
      if ($toggle.is(':checked')) {
        $cityOther.prop('disabled', false);
        $citySelect.prop('disabled', true).val('').trigger('change.select2');
        $cityOther.focus();
      } else {
        $cityOther.prop('disabled', true).val('');
        $citySelect.prop('disabled', false);
      }
    }
    // run at startup
    initAreaMutualExclusion();

    // toggle handler: when user clicks "can't find area?"
    $toggle.on('change', function(){
      if ($(this).is(':checked')) {
        // enable manual input and disable dropdown
        $cityOther.prop('disabled', false).focus();
        // clear and disable city dropdown (and ensure select2 reflects)
        try { $citySelect.select2('destroy'); } catch(e){}
        $citySelect.prop('disabled', true).val('');
        $citySelect.select2({ width: '100%' });
      } else {
        // enable dropdown and disable manual input
        $cityOther.prop('disabled', true).val('');
        $citySelect.prop('disabled', false).select2({ width: '100%' }).focus();
      }
    });

    // If user selects an area from dropdown, ensure manual input is disabled and toggle unchecked
    $citySelect.on('change', function(){
      if ($(this).val()) {
        $cityOther.prop('disabled', true).val('');
        $toggle.prop('checked', false);
      }
    });

    // --- Lock/unlock helpers ---
    function destroySelect2Safe($s) {
      try { $s.select2('destroy'); } catch (e) {}
      $s.next('.select2').remove();
    }

    function lockLocation(countryId, countryName, stateId, stateName, districtId, districtName) {
      const $countrySelect = $('#country_id');
      if ($countrySelect.length && countryId) {
        destroySelect2Safe($countrySelect);
        if (!$('#country_static').length) {
          const countryHtml = `<div id="country_static" class="form-control-plaintext">${escapeHtml(countryName)}</div>
                               <input type="hidden" name="country_id" value="${escapeHtml(countryId)}">`;
          $countrySelect.after(countryHtml).hide();
        }
      }
      const $stateSelect = $('#state_id');
      if ($stateSelect.length && stateId) {
        destroySelect2Safe($stateSelect);
        if (!$('#state_static').length) {
          const stateHtml = `<div id="state_static" class="form-control-plaintext">${escapeHtml(stateName)}</div>
                             <input type="hidden" name="state_id" value="${escapeHtml(stateId)}">`;
          $stateSelect.after(stateHtml).hide();
        }
      }
      const $districtSelect = $('#district_id');
      if ($districtSelect.length && districtId) {
        destroySelect2Safe($districtSelect);
        if (!$('#district_static').length) {
          const districtHtml = `<div id="district_static" class="form-control-plaintext">${escapeHtml(districtName)}</div>
                                <input type="hidden" name="district_id" value="${escapeHtml(districtId)}">`;
          $districtSelect.after(districtHtml).hide();
        }
      }
    }

    function unlockLocation() {
      $('#country_static,#state_static,#district_static').remove();
      $('#country_id,#state_id,#district_id').show().select2({ width: '100%' });
    }

    // --- Pincode Lookup ---
    const PINCODE_LOOKUP_URL_BASE = "{{ url('/pincode/lookup') }}";
    function isValidPin(p) { return /^\d{6}$/.test(String(p||'').trim()); }
    $('#pincode').on('input', function(){ this.value = this.value.replace(/\D/g,'').slice(0,6); });

    function setSelectByText($select, text) {
      if (!text) return false;
      const needle = String(text).trim().toLowerCase();
      let val = null;
      $select.find('option').each(function(){
        if ($(this).text().trim().toLowerCase() === needle) { val = $(this).val(); return false; }
      });
      if (val === null) {
        $select.find('option').each(function(){
          if ($(this).text().trim().toLowerCase().includes(needle)) { val = $(this).val(); return false; }
        });
      }
      if (val !== null) { $select.val(val).trigger('change.select2'); return true; }
      return false;
    }

    function showSuccess(msg){ $('#pincodeHelp').text(msg).css('color','green'); }
    function showError(msg){ $('#pincodeHelp').text(msg).css('color','crimson'); }

    function lookupPincode(pincode) {
      if (!isValidPin(pincode)) { showError('Invalid Pincode!'); return; }

      $('#pincodeLookupBtn').prop('disabled', true).text('Looking...');
      $.getJSON(PINCODE_LOOKUP_URL_BASE + '/' + encodeURIComponent(pincode))
        .done(function(res){
          if (!res || res.success !== true || !res.payload) { showError('Invalid Pincode'); return; }
          const payload = res.payload;

          $('#pincode_id').val(payload.id ?? payload.pincode_id ?? '');
          if (payload.country) setSelectByText($('#country_id'), payload.country);
          if (payload.state) setSelectByText($('#state_id'), payload.state);

          const selectedStateVal = $('#state_id').val();
          if (selectedStateVal) {
            loadDistrictsForState(selectedStateVal, null, payload.district ?? null);
            setTimeout(function(){
              const districtId = $('#district_id').val();
              if (districtId) loadAreasByDistrict(districtId, pincode);
              lockLocation($('#country_id').val(), payload.country, selectedStateVal, payload.state, $('#district_id').val(), payload.district);
            }, 800);
          }
          showSuccess('Pincode looked up successfully.');
        })
        .fail(function(){ showError('Lookup failed.'); })
        .always(function(){ $('#pincodeLookupBtn').prop('disabled', false).text('Lookup'); });
    }

    $('#pincodeLookupBtn').on('click', function(){ lookupPincode($('#pincode').val().trim()); });
    $('#pincode').on('blur', function(){ const val = $(this).val().trim(); if (isValidPin(val)) lookupPincode(val); });

    // Reset
    $('button[type="reset"]').on('click', function(){
      setTimeout(()=> $('#category_id,#country_id,#state_id,#district_id,#city_id').val(null).trigger('change.select2'), 10);
      $('#pincode_id,#pincode').val('');
      $('#pincodeHelp').text('Type a 6-digit pincode and press Lookup.').css('color','#6b7280');
      $('#serverErrors,#serverSuccess').hide().empty();
      $('#emailStatus,#contact2Error,#phone1Error').hide().empty();
      $('#phone_number,#contact_number_2').val('');
      unlockLocation();
      // reset mutual exclusion
      $('#cantFindArea').prop('checked', false);
      $('#city_other').prop('disabled', true).val('');
      $('#city_id').prop('disabled', false).select2({ width: '100%' });
      // reset confirmation checkbox
      $('#confirmReview').prop('checked', false);
      $('#confirmError').hide();
      $('#confirmReviewLabel').css('color','');
      $('#saveBtn').prop('disabled', true);
    });

    // Contact number validation
    $('#phone_number').on('input', function(){
      this.value = this.value.replace(/\D/g,'').slice(0,10);
    });
   $('#contact_number_2').on('input', function() {
  const original = this.value;
  const digits = original.replace(/\D/g, '').slice(0, 10);
  this.value = digits;

  if (original !== digits) {
    $('#contact2Error')
      .text('Only numbers are allowed.')
      .css('color', 'red')
      .show();
  } else {
    $('#contact2Error').hide();
  }
});

    // Confirmation checkbox logic (enable/disable Save button)
    (function initConfirmation(){
      const $confirm = $('#confirmReview');
      const $saveBtn = $('#saveBtn');
      const $confirmError = $('#confirmError');
      // initial state: ensure Save disabled unless checked
      $saveBtn.prop('disabled', !$confirm.is(':checked'));

      $confirm.on('change', function(){
        const checked = $(this).is(':checked');
        $saveBtn.prop('disabled', !checked);
        if (checked) {
          $confirmError.hide();
          $('#confirmReviewLabel').css('color','');
        }
      });
    })();

    // --- AJAX Form Submit ---
    $('#doctorForm').on('submit', function(e){
      e.preventDefault();
      $('#serverErrors,#serverSuccess').hide().empty();

      // Confirm checkbox guard (frontend-only: controller unchanged)
      if (!$('#confirmReview').is(':checked')) {
        $('#confirmError').show();
        $('#confirmReviewLabel').css('color', '#b91c1c');
        // scroll into view
        $('html, body').animate({ scrollTop: Math.max(0, $('#confirmReview').offset().top - 120) }, 180);
        return false;
      }

      const $btn = $('#saveBtn');
      $btn.prop('disabled', true).text('Saving...');

      const form = this;
      const formData = new FormData(form);
      const csrfToken = getCsrfToken();
      if (!formData.has('_token')) formData.append('_token', csrfToken);

      // Ensure mutual exclusion state is enforced before sending:
      // - if manual toggle is checked => ensure city_id is empty and disabled
      // - else ensure city_other is empty and disabled
      if ($('#cantFindArea').is(':checked')) {
        // enable city_other if somehow disabled and ensure city_id cleared
        $('#city_other').prop('disabled', false);
        $('#city_id').prop('disabled', true).val('');
        formData.set('city_other', $('#city_other').val() || '');
        formData.set('city_id', '');
      } else {
        $('#city_other').prop('disabled', true).val('');
        $('#city_id').prop('disabled', false);
        formData.set('city_other', '');
        formData.set('city_id', $('#city_id').val() || '');
      }

      $.ajax({
        url: $(form).attr('action'),
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: function(resp){
          if (resp && resp.success) {
            // redirect to the correct named route in web.php
            let dest = '{{ route("listdoctor.success") }}';
            if (resp.id) dest += '?id=' + encodeURIComponent(resp.id);
            window.location.href = dest;
          } else {
            $('#serverErrors').text(resp?.message || 'Unexpected response.').show();
          }
        },
        error: function(xhr){
          if (xhr && xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
            // display validation errors
            let html = '<ul class="mb-0">';
            const errors = xhr.responseJSON.errors;
            Object.keys(errors).forEach(function(k){
              errors[k].forEach(function(m){
                html += '<li>' + m + '</li>';
              });
            });
            html += '</ul>';
            $('#serverErrors').html(html).show();
          } else {
            const msg = xhr.responseJSON?.message || 'Server error.';
            $('#serverErrors').text(msg).show();
          }
        },
        complete: function(){ $btn.prop('disabled', false).text('Save Doctor'); }
      });
    });

    // Email realtime validation
    (function(){
      const $email = $('#email'), $status = $('#emailStatus');
      function validEmail(e){ return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(e||'').trim()); }
      function showStatus(msg,color){ $status.show().css('color',color).text(msg); }
      $email.on('blur', function(){
        const val = this.value.trim().toLowerCase();
        if (!val || !validEmail(val)) { showStatus('Invalid email.','#b91c1c'); return; }
        showStatus('Checking email...','#6b7280');
        $.post('{{ route("ajax.check-email") }}',{ email: val, _token:getCsrfToken() })
          .done(res=>{
            if(res.exists === true || res.exists === 'true') showStatus('Email already registered.','#b91c1c');
            else if(res.exists === 'linked') showStatus('Email linked to account — proceed.','orange');
            else showStatus('Email available.','#065f46');
          })
          .fail(()=> showStatus('Could not check email.','#b91c1c'));
      });
    })();

  });
})(jQuery);
</script>

@endsection
