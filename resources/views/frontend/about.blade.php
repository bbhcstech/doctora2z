@extends('partials.app')

@section('title', 'About')

@section('content')

    <style>
        /* Banner */
        .header-carousel .owl-carousel-item img {
            height: 400px;
            object-fit: cover;
        }

        .mt-md-7 {
            margin-top: 5rem !important;
        }

        #about-section {
            margin: 20px 0;
        }

        .about-panel {
            background: rgba(10, 80, 140, 0.06);
            padding: 24px;
            border-radius: 8px;
            margin-top: 40px;
            /* moved down */
        }

        /* Improve typography and spacing inside panel */
        .about-panel h2 {
            margin-bottom: 0.75rem;
            font-weight: 700;
        }

        .about-panel p {
            text-align: justify;
            margin-bottom: 1rem;
            color: #333;
            line-height: 1.6;
        }

        .why_point {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .point_icon {
            width: 56px;
            height: 56px;
            object-fit: contain;
            border-radius: 8px;
            padding: 8px;
            background-color: #fff;
            flex-shrink: 0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .why_ah_points {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
        }

        .counter-holder {
            font-weight: 600;
            font-size: 1.5rem;
            display: inline-block;
        }

        .who-card .card-img {
            border-radius: 10px;
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        /* Ensure left and right columns align nicely on wide screens */
        @media (min-width: 992px) {
            #about-section .row.align-center {
                display: flex;
                align-items: flex-start;
            }

            /* keep image and panel roughly aligned by reducing panel margin at larger widths */
            .about-panel {
                margin-top: 40px;
            }

            .who-card {
                margin-top: 40px;
            }
        }

        /* Mobile adjustments */
        @media (max-width: 767px) {
            .header-carousel .owl-carousel-item img {
                height: 220px;
            }

            .about-panel {
                padding: 16px;
                margin-top: 16px;
            }

            .mt-md-7 {
                margin-top: 2rem !important;
            }

            .why_point {
                gap: 12px;
            }

            .point_icon {
                width: 48px;
                height: 48px;
            }

            .counter-holder {
                font-size: 1.25rem;
            }

            .who-card {
                margin-top: 0.5rem;
            }
        }

        /* Optional small animation for entrance (subtle) */
        .animated-section {
            opacity: 0;
            transform: translateY(8px);
            transition: opacity 300ms ease, transform 300ms ease;
        }

        .animated-section.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <!-- Banner -->
    <div class="container-fluid p-0 mb-0">
        <div class="owl-carousel header-carousel position-relative">
            <div class="owl-carousel-item position-relative">
                <img class="img-fluid w-100"
                    src="{{ $about->banner_image ? asset('/admin/uploads/about/' . $about->banner_image) : asset('img/default-banner.jpg') }}"
                    alt="{{ $about->title ?? 'About banner' }}" loading="lazy">
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div id="about-section" class="search-results mt-4 animated-section">
        <div class="container-xxl py-2">
            <div class="container">
                @if ($about)
                    <div class="row g-lg-5 g-3 align-center">
                        <!-- Left: About content + counters -->
                        <div class="col-md-7">
                            <div class="about-panel">
                                <h2 class="mb-3">{{ $about->title ?? 'About Us' }}</h2>
                                <p>{{ $about->description ?? 'Content coming soon.' }}</p>

                                <div class="row g-xl-3 g-2 pt-lg-1 pb-lg-0">
                                    @php
                                        $counters = [
                                            [
                                                'icon' => '01-Counters-Hospitals-1.svg',
                                                'count' => $doctorCount,
                                                'label' => 'Verified Doctors',
                                            ],
                                            [
                                                'icon' => '02-Counters-Clinics-2.svg',
                                                'count' => $specializationCount,
                                                'label' => 'Specializations',
                                            ],
                                            [
                                                'icon' => '04-Pharmacies-2.svg',
                                                'count' => $hospitalCount,
                                                'label' => 'Hospitals',
                                            ],
                                            [
                                                'icon' => '06-Doctors-2.svg',
                                                'count' => $clinicCount,
                                                'label' => 'Clinics',
                                            ],
                                        ];
                                    @endphp

                                    @foreach ($counters as $counter)
                                        <div class="col-6 col-md-6">
                                            <div class="py-2 why_point">
                                                <img src="https://cdn.apollohospitals.com/apollohospitals-live/wca/{{ $counter['icon'] }}"
                                                    alt="{{ $counter['label'] }} icon" class="point_icon">
                                                <div class="why_ah_points ps-1">
                                                    <h4 class="mb-0"><span class="counter-holder"
                                                            data-count="{{ $counter['count'] }}">0</span>+</h4>
                                                    <div>{{ $counter['label'] }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Right: Image -->
                        <div class="col-md-5">
                            <div class="card border-0 who-card">
                                <img src="{{ $about->page_image ? asset('/admin/uploads/about/' . $about->page_image) : asset('img/default-page.jpg') }}"
                                    class="card-img mt-3 mt-md-0" alt="{{ $about->title ?? 'About image' }}" loading="lazy">
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <h3>No about content found</h3>
                        <p>Please add an About Us record in the admin panel or pass data from the controller.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Counter Animation and entrance animation toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // reveal animated section
            const animated = document.querySelectorAll('.animated-section');
            animated.forEach(el => requestAnimationFrame(() => el.classList.add('visible')));

            // counters
            const counters = document.querySelectorAll('.counter-holder');
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-count') || 0;
                    let count = +counter.innerText.replace(/,/g, '') || 0;
                    const increment = Math.max(1, Math.ceil(target / 200));

                    if (count < target) {
                        counter.innerText = (count + increment).toLocaleString();
                        setTimeout(updateCount, 15);
                    } else {
                        counter.innerText = target.toLocaleString();
                    }
                };
                updateCount();
            });
        });
    </script>

@endsection
