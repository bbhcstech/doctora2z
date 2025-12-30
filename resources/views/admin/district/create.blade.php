@extends('admin.layout.app')

@section('title', 'Add District')

@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Add District/City/Town/Village</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('district.index') }}">Districts/City/Town/Village</a></li>
        <li class="breadcrumb-item active">Add</li>
      </ol>
    </nav>
  </div>

  <div class="card" style="background-color:#d8e0f1; padding:20px; border-radius:10px;">
    <div class="card-body">
      <form id="districtForm" action="{{ route('district.store') }}" method="POST" novalidate>
        @csrf

        {{-- Country --}}
        <div class="mb-3">
          <label for="country_id" class="form-label">Country</label>
          <select name="country_id" id="country_id" class="form-select @error('country_id') is-invalid @enderror" required>
            <option value="">Select Country</option>
            @foreach($countries as $country)
              <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                {{ $country->name }}
              </option>
            @endforeach
          </select>
          @error('country_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- State --}}
        <div class="mb-3">
          <label for="state_id" class="form-label">State (Part)</label>
          <select name="state_id" id="state_id" class="form-select @error('state_id') is-invalid @enderror" required>
            <option value="">Select State(Part)</option>
          </select>
          @error('state_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- District name (text, required) --}}
        <div class="mb-3">
          <label for="name" class="form-label">District Name</label>
          <input type="text"
                 name="name"
                 id="name"
                 class="form-control @error('name') is-invalid @enderror"
                 value="{{ old('name') }}"
                 autocomplete="off"
                 required>
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- City/Town/Village name (text) --}}
        <div class="mb-3">
          <label for="city_name" class="form-label">City / Town / Village Name</label>
          <input type="text"
                 name="city_name"
                 id="city_name"
                 class="form-control @error('city_name') is-invalid @enderror"
                 value="{{ old('city_name') }}"
                 autocomplete="off"
                 placeholder="e.g., Singur">
          @error('city_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <small class="text-muted">If you enter a pincode, city/town/village is required.</small>
        </div>

        {{-- Pincode --}}
        <div class="mb-3">
          <label for="pincode" class="form-label">Pincode</label>
          <input type="text"
                 name="pincode"
                 id="pincode"
                 class="form-control @error('pincode') is-invalid @enderror"
                 maxlength="6"
                 pattern="\d{6}"
                 inputmode="numeric"
                 placeholder="Enter 6-digit pincode"
                 value="{{ old('pincode') }}"
                 autocomplete="off">
          @error('pincode') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <small class="text-muted">Optional. 6 digits (India PIN).</small>
        </div>

        <div class="text-end">
          <button id="submitBtn" type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</main>

{{-- Inline, layout-independent JS (no jQuery) --}}
<script>
(function(){
  const BASE = "{{ url('/') }}"; // includes /public on your host
  const countrySel = document.getElementById('country_id');
  const stateSel   = document.getElementById('state_id');
  const form       = document.getElementById('districtForm');
  const submitBtn  = document.getElementById('submitBtn');
  const pincodeInp = document.getElementById('pincode');
  const cityInp    = document.getElementById('city_name');

  function option(el, value, text, selected=false){
    const o = document.createElement('option');
    o.value = value; o.textContent = text;
    if(selected) o.selected = true;
    el.appendChild(o);
  }
  function reset(el, placeholder){
    el.innerHTML = '';
    option(el, '', placeholder);
  }
  function statesUrl(countryId){ return BASE + '/location/states/' + encodeURIComponent(countryId); }

  async function loadStates(countryId, preselect){
    reset(stateSel, 'Loading…');
    submitBtn.disabled = true;

    if(!countryId){
      reset(stateSel, 'Select State(Part)');
      submitBtn.disabled = false;
      return;
    }
    try {
      const res = await fetch(statesUrl(countryId), { headers: { 'Accept':'application/json' }, cache:'no-store' });
      if(!res.ok){
        console.error('States HTTP error', res.status);
        reset(stateSel, 'Select State(Part)');
        submitBtn.disabled = false;
        return;
      }
      const states = await res.json();
      reset(stateSel, 'Select State(Part)');
      if(Array.isArray(states) && states.length){
        states.forEach(s => option(stateSel, s.id, s.name, String(preselect)===String(s.id)));
      } else {
        option(stateSel, '', 'No states found');
      }
    } catch(e){
      console.error('States fetch failed', e);
      reset(stateSel, 'Select State(Part)');
    } finally {
      submitBtn.disabled = false;
    }
  }

  // country change → load states
  countrySel.addEventListener('change', ()=>loadStates(countrySel.value, null));

  // normalize pincode to digits only and max 6
  pincodeInp.addEventListener('input', () => {
    const digits = pincodeInp.value.replace(/\D+/g,'').slice(0,6);
    if (pincodeInp.value !== digits) pincodeInp.value = digits;
  });

  // client-side rule: if pincode filled, city_name required
  form.addEventListener('submit', (e) => {
    const pin = pincodeInp.value.trim();
    const city = cityInp.value.trim();
    if (pin.length > 0 && city.length === 0) {
      e.preventDefault();
      cityInp.classList.add('is-invalid');
      let fb = cityInp.parentElement.querySelector('.invalid-feedback');
      if (!fb) {
        fb = document.createElement('div');
        fb.className = 'invalid-feedback';
        cityInp.parentElement.appendChild(fb);
      }
      fb.textContent = 'City/Town/Village is required when pincode is provided.';
      cityInp.focus();
    }
  });

  // remove error state when user types city
  cityInp.addEventListener('input', () => {
    cityInp.classList.remove('is-invalid');
    const fb = cityInp.parentElement.querySelector('.invalid-feedback');
    if (fb) fb.textContent = '';
  });

  // initial autoload
  document.addEventListener('DOMContentLoaded', ()=>{
    let initialCountry = countrySel.value;
    if(!initialCountry){
      const first = Array.from(countrySel.options).find(o => o.value);
      if(first){ first.selected = true; initialCountry = first.value; }
    }
    const preState = "{{ old('state_id') }}";
    if(initialCountry){ loadStates(initialCountry, preState); }
  });
})();
</script>
@endsection
