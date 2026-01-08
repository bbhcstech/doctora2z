{{-- resources/views/layouts/menu.blade.php --}}
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        {{-- Home Page --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}" target="_blank" rel="noopener">
                <i class="bi bi-house-door custom-icon"></i>
                <span>Home Page</span>
            </a>
        </li>

        {{-- Dashboard --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid custom-icon"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- Manage Clinic / Doctor --}}
        @php
            $clinicDoctorGroupActive = request()->routeIs(
                'clients*',
                'doctors*',
                'doctor.index',
                'doctorinline.create',
                'doctor_inline.*',
            );
        @endphp
        <li class="nav-item">
            <a class="nav-link {{ $clinicDoctorGroupActive ? '' : 'collapsed' }}" data-bs-toggle="collapse"
                data-bs-target="#clinic-doctor-nav" href="#"
                aria-expanded="{{ $clinicDoctorGroupActive ? 'true' : 'false' }}" aria-controls="clinic-doctor-nav">
                <i class="bi bi-menu-button-wide custom-icon"></i>
                <span>Manage Clinic/Doctor</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="clinic-doctor-nav" class="nav-content collapse {{ $clinicDoctorGroupActive ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">

                @if (auth()->user()->role === 'admin' || auth()->user()->role === 'clinic')
                    <li>
                        <a href="{{ route('clients.index') }}"
                            class="{{ request()->routeIs('clients.index') ? 'active' : '' }}">
                            <i class="bi bi-building custom-icon"></i>
                            <span>Clinics</span>
                        </a>
                    </li>
                @endif

                @if (in_array(auth()->user()->role, ['admin', 'clinic', 'doctor'], true))
                    <li>
                        <a href="{{ route('doctors.index') }}"
                            class="{{ request()->routeIs('doctors.index') ? 'active' : '' }}">
                            <i class="bi bi-person-badge custom-icon"></i>
                            <span>Old Doctors</span>

                        </a>
                    </li>
                @endif

                <li>
                    <a href="{{ route('doctor_inline.index') }}"
                        class="{{ request()->routeIs('doctor_inline.*') ? 'active' : '' }}">
                        <i class="bi bi-pencil-square custom-icon"></i>
                        <span>Doctors</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Manage Location (Admin) --}}
        @if (auth()->user()->role === 'admin')
            @php
                $locationGroupActive = request()->routeIs(
                    'country*',
                    'state*',
                    'district*',
                    'town-village*',
                    'district.ajax',
                );
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $locationGroupActive ? '' : 'collapsed' }}" data-bs-toggle="collapse"
                    data-bs-target="#location-nav" href="#"
                    aria-expanded="{{ $locationGroupActive ? 'true' : 'false' }}" aria-controls="location-nav">
                    <i class="bi bi-map custom-icon"></i>
                    <span>Manage Location</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="location-nav" class="nav-content collapse {{ $locationGroupActive ? 'show' : '' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('country.index') }}"
                            class="{{ request()->routeIs('country.index') ? 'active' : '' }}">
                            <i class="bi bi-geo-alt custom-icon"></i>
                            <span>Country</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('state.index') }}"
                            class="{{ request()->routeIs('state.index') ? 'active' : '' }}">
                            <i class="bi bi-geo custom-icon"></i>
                            <span>State (Part)</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('district.index') }}"
                            class="{{ request()->routeIs('district.index') ? 'active' : '' }}">
                            <i class="bi bi-geo-fill custom-icon"></i>
                            <span>District/City/Town/Village</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('district.ajax') }}"
                            class="{{ request()->routeIs('district.ajax') ? 'active' : '' }}">
                            <i class="bi bi-pin-map custom-icon"></i>
                            <span>Set Pincode</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        {{-- Manage Category (Admin) --}}
        @if (auth()->user()->role === 'admin')
            @php
                $categoryGroupActive = request()->routeIs(
                    'category*',
                    'subcategory*',
                    'category_ajax*',
                    'categories.index',
                );
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $categoryGroupActive ? '' : 'collapsed' }}" data-bs-toggle="collapse"
                    data-bs-target="#category-nav" href="#"
                    aria-expanded="{{ $categoryGroupActive ? 'true' : 'false' }}" aria-controls="category-nav">
                    <i class="bi bi-folder custom-icon"></i>
                    <span>Manage Category</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="category-nav" class="nav-content collapse {{ $categoryGroupActive ? 'show' : '' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('category.index') }}"
                            class="{{ request()->routeIs('category.index') ? 'active' : '' }}">
                            <i class="bi bi-tags custom-icon"></i>
                            <span>Category List</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('subcategory.index') }}"
                            class="{{ request()->routeIs('subcategory.index') ? 'active' : '' }}">
                            <i class="bi bi-tag custom-icon"></i>
                            <span>Subcategory List</span>
                        </a>
                    </li>

                    {{-- ✅ New Added: Category AJAX --}}
                    <li>
                        <a href="{{ route('categories.index') }}"
                            class="{{ request()->routeIs('categories.index') ? 'active' : '' }}">
                            <i class="bi bi-lightning-charge custom-icon"></i>
                            <span>Category Ajax</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        {{-- Manage Pages (Admin) --}}
        @if (auth()->user()->role === 'admin')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pages.index') ? 'active' : '' }}"
                    href="{{ route('pages.index') }}">
                    <i class="bi bi-chat-left-text custom-icon"></i>
                    <span>Manage Patients Say</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('hospital.index') ? 'active' : '' }}"
                    href="{{ route('hospital.index') }}">
                    <i class="bi bi-building custom-icon"></i>
                    <span>Manage Hospital</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('medicashop.index') ? 'active' : '' }}"
                    href="{{ route('medicashop.index') }}">
                    <i class="bi bi-shop custom-icon"></i>
                    <span>Manage Medical Shop</span>
                </a>
            </li>
        @endif

        {{-- Page Settings (Admin) --}}
        @if (auth()->user()->role === 'admin')
            @php
                $settingsGroupActive = request()->routeIs('banner*', 'about-us*', 'contact-us*');
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $settingsGroupActive ? '' : 'collapsed' }}" data-bs-toggle="collapse"
                    data-bs-target="#settings-nav" href="#"
                    aria-expanded="{{ $settingsGroupActive ? 'true' : 'false' }}" aria-controls="settings-nav">
                    <i class="bi bi-gear custom-icon"></i>
                    <span>Manage Page Settings</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="settings-nav" class="nav-content collapse {{ $settingsGroupActive ? 'show' : '' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('banner.index') }}"
                            class="{{ request()->routeIs('banner.index') ? 'active' : '' }}">
                            <i class="bi bi-image custom-icon"></i>
                            <span>Banner List</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about-us.edit') }}"
                            class="{{ request()->routeIs('about-us.edit') ? 'active' : '' }}">
                            <i class="bi bi-info-circle custom-icon"></i>
                            <span>About Us Page</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact-us.edit') }}"
                            class="{{ request()->routeIs('contact-us.edit') ? 'active' : '' }}">
                            <i class="bi bi-envelope custom-icon"></i>
                            <span>Contact Us Page</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        {{-- Advertisement (Admin) --}}
        @if (auth()->user()->role === 'admin')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('advertisement.index') ? 'active' : '' }}"
                    href="{{ route('advertisement.index') }}">
                    <i class="bi bi-megaphone custom-icon"></i>
                    <span>Manage Advertisement</span>
                </a>
            </li>
        @endif

        {{-- Social Links (Admin) --}}
        @if (auth()->user()->role === 'admin')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('social_links.index') ? 'active' : '' }}"
                    href="{{ route('social_links.index') }}">
                    <i class="bi bi-link-45deg custom-icon"></i>
                    <span>Manage Social Links</span>
                </a>
            </li>
        @endif

        {{-- Footer Settings (Admin) --}}
        @if (auth()->user()->role === 'admin')
            @php
                $footerGroupActive = request()->routeIs(
                    'trending-doctors*',
                    'trending-clinic*',
                    'trending-hospital*',
                    'trending-shop*',
                );
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $footerGroupActive ? '' : 'collapsed' }}" data-bs-toggle="collapse"
                    data-bs-target="#footer-settings-nav" href="#"
                    aria-expanded="{{ $footerGroupActive ? 'true' : 'false' }}" aria-controls="footer-settings-nav">
                    <i class="bi bi-folder custom-icon"></i>
                    <span>Footer Settings</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="footer-settings-nav" class="nav-content collapse {{ $footerGroupActive ? 'show' : '' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a class="{{ request()->routeIs('trending-doctors.index') ? 'active' : '' }}"
                            href="{{ route('trending-doctors.index') }}">
                            <i class="bi bi-person custom-icon"></i>
                            <span>Doctor</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('trending-clinic.index') ? 'active' : '' }}"
                            href="{{ route('trending-clinic.index') }}">
                            <i class="bi bi-hospital custom-icon"></i>
                            <span>Clinic</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('trending-hospital.index') ? 'active' : '' }}"
                            href="{{ route('trending-hospital.index') }}">
                            <i class="bi bi-building custom-icon"></i>
                            <span>Hospital</span>
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('trending-shop.index') ? 'active' : '' }}"
                            href="{{ route('trending-shop.index') }}">
                            <i class="bi bi-bag custom-icon"></i>
                            <span>Medical Shop</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif
    </ul>
</aside>
<!-- End Sidebar -->
