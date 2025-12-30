<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Client;
use App\Models\Doctor;
use App\Models\Item;

class SearchController extends Controller
{
    /**
     * Handle full search page (doctors + clinics).
     */
    public function search(Request $request)
    {
        $advertisements = Advertisement::all();
        $query = trim((string) $request->input('query', ''));
        $collation = 'utf8mb4_unicode_ci';

        // 1) if user clicked a doctor suggestion → show only that doctor
        $doctorId = $request->input('doctor_id');
        if ($doctorId) {
            $doctor = Doctor::with([
                    'city',
                    'state',
                    'country',
                    'category',
                    'clinicSchedules',
                ])
                ->active()
                ->find($doctorId);

            $results = $doctor ? collect([$doctor]) : collect();
            $query   = $doctor ? $doctor->display_name : $query;

            return view('frontend.search_result', compact('results', 'query', 'advertisements'));
        }

        // Log search term (best effort)
        if ($query !== '') {
            try {
                Item::create(['name' => $query]);
            } catch (\Throwable $e) {
                // ignore logging errors
            }
        }

        // No query: return empty results
        if ($query === '') {
            $results = collect();
            return view('frontend.search_result', compact('results', 'query', 'advertisements'));
        }

        // Normalize query: lowercase, remove leading "Dr", "Doctor"
        $cleanQuery = mb_strtolower($query, 'UTF-8');
        $cleanQuery = preg_replace('/^dr\.?\s*/iu', '', $cleanQuery);
        $cleanQuery = preg_replace('/^doctor\s*/iu', '', $cleanQuery);
        $cleanQuery = trim($cleanQuery);

        // Split into words, ignore single-character terms
        $searchTerms = array_filter(explode(' ', $cleanQuery), function ($term) {
            return mb_strlen($term, 'UTF-8') > 1;
        });

        $doctorResults = collect();
        $clinicResults = collect();

        // ---------------- DOCTOR SEARCH ----------------
        if (!empty($searchTerms)) {
            $doctorQuery = Doctor::with([
                    'city',
                    'state',
                    'country',
                    'category',
                    'clinicSchedules',
                ])
                ->active();

            foreach ($searchTerms as $term) {
                $term = mb_strtolower($term, 'UTF-8');

                $doctorQuery->where(function ($q) use ($term, $collation) {
                    $q->whereRaw("LOWER(CONVERT(name USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$term}%"])
                        ->orWhereRaw("LOWER(CONVERT(degree USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$term}%"])
                        ->orWhereRaw("LOWER(CONVERT(speciality USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$term}%"])
                        ->orWhereRaw("LOWER(CONVERT(address USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$term}%"])
                        ->orWhereHas('city', function ($cityQuery) use ($term, $collation) {
                            $cityQuery->whereRaw("LOWER(CONVERT(name USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$term}%"]);
                        })
                        ->orWhereHas('category', function ($catQuery) use ($term, $collation) {
                            $catQuery->whereRaw("LOWER(CONVERT(name USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$term}%"]);
                        });
                });
            }

            $doctorResults = $doctorQuery->get()->each(function ($doctor) {
                $doctor->type = 'doctor';
            });
        }

        // ---------------- CLINIC SEARCH ----------------
        if (!empty($searchTerms)) {
            $clinicQuery = Client::query();

            foreach ($searchTerms as $term) {
                $term = mb_strtolower($term, 'UTF-8');

                $clinicQuery->where(function ($q) use ($term, $collation) {
                    $q->whereRaw("LOWER(CONVERT(name USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$term}%"])
                        ->orWhereRaw("LOWER(CONVERT(city_name USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$term}%"])
                        ->orWhereRaw("LOWER(CONVERT(state_name USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$term}%"])
                        ->orWhereRaw("LOWER(CONVERT(address USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$term}%"]);
                });
            }

            $clinicResults = $clinicQuery->get()->each(function ($clinic) {
                $clinic->type = 'clinic';
            });
        }

        // Combine doctor + clinic results
        $results = $doctorResults->merge($clinicResults);

        // ---------------- FALLBACK BROADER DOCTOR SEARCH ----------------
        if ($results->isEmpty() && !empty($searchTerms)) {
            $degreeTerms = ['mbbs', 'bds', 'bhms', 'bams', 'md', 'ms', 'dch'];
            $nameParts = array_filter($searchTerms, function ($term) use ($degreeTerms) {
                return !in_array(mb_strtolower($term, 'UTF-8'), $degreeTerms, true);
            });

            if (!empty($nameParts)) {
                $doctorQuery = Doctor::with([
                        'city',
                        'state',
                        'country',
                        'category',
                        'clinicSchedules',
                    ])
                    ->active();

                foreach ($nameParts as $term) {
                    $term = mb_strtolower($term, 'UTF-8');

                    $doctorQuery->where(function ($q) use ($term, $collation) {
                        $q->whereRaw("LOWER(CONVERT(name USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$term}%"])
                            ->orWhereRaw("LOWER(CONVERT(speciality USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$term}%"])
                            ->orWhereHas('category', function ($catQuery) use ($term, $collation) {
                                $catQuery->whereRaw("LOWER(CONVERT(name USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$term}%"]);
                            });
                    });
                }

                $doctorResults = $doctorQuery->get()->each(function ($doctor) {
                    $doctor->type = 'doctor';
                });

                $results = $doctorResults;
            }
        }

        Log::info('Search performed', [
            'query'           => $query,
            'clean_query'     => $cleanQuery,
            'search_terms'    => $searchTerms,
            'results_doctors' => $doctorResults->count(),
            'results_clinics' => $clinicResults->count(),
            'results_total'   => $results->count(),
        ]);

        return view('frontend.search_result', compact('results', 'query', 'advertisements'));
    }

    /**
     * AJAX suggestions for search bar.
     */
    public function getSuggestions(Request $request)
    {
        $q = trim((string) $request->input('query', ''));

        if ($q === '' || mb_strlen($q, 'UTF-8') < 2) {
            return response()->json(['suggestions' => []]);
        }

        $collation = 'utf8mb4_unicode_ci';

        // Normalize
        $cleanQuery = mb_strtolower($q, 'UTF-8');
        $cleanQuery = preg_replace('/^dr\.?\s*/iu', '', $cleanQuery);
        $cleanQuery = preg_replace('/^doctor\s*/iu', '', $cleanQuery);
        $cleanQuery = trim($cleanQuery);

        // ---------- Doctor suggestions ----------
        $doctorSuggestions = Doctor::with('category')
            ->active()
            ->where(function ($query) use ($cleanQuery, $collation) {
                $query->whereRaw("LOWER(CONVERT(name USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$cleanQuery}%"])
                    ->orWhereRaw("LOWER(CONVERT(degree USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$cleanQuery}%"])
                    ->orWhereRaw("LOWER(CONVERT(speciality USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$cleanQuery}%"])
                    ->orWhereRaw("LOWER(CONVERT(address USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$cleanQuery}%"])
                    ->orWhereHas('category', function ($catQuery) use ($cleanQuery, $collation) {
                        $catQuery->whereRaw("LOWER(CONVERT(name USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$cleanQuery}%"]);
                    });
            })
            ->limit(7)
            ->get()
            ->map(function ($doctor) {
                return [
                    'id'   => $doctor->id,
                    'name' => $doctor->display_name,
                    'type' => 'doctor',
                    'meta' => $doctor->speciality
                        ?? ($doctor->category->name ?? 'Doctor'),
                ];
            })
            ->toArray();

        // ---------- Clinic suggestions ----------
        $clinicSuggestions = Client::whereRaw(
                "LOWER(CONVERT(name USING utf8mb4)) COLLATE {$collation} LIKE ?",
                ["%{$cleanQuery}%"]
            )
            ->orWhereRaw("LOWER(CONVERT(city_name USING utf8mb4)) COLLATE {$collation} LIKE ?", ["%{$cleanQuery}%"])
            ->limit(3)
            ->get()
            ->map(function ($clinic) {
                return [
                    'id'   => $clinic->id,
                    'name' => trim($clinic->name),
                    'type' => 'clinic',
                    'meta' => $clinic->city_name ?? 'Clinic',
                ];
            })
            ->toArray();

        // ---------- Category suggestions ----------
        $categorySuggestions = Category::whereRaw(
                "LOWER(CONVERT(name USING utf8mb4)) COLLATE {$collation} LIKE ?",
                ["%{$cleanQuery}%"]
            )
            ->limit(3)
            ->get()
            ->map(function ($category) {
                return [
                    'id'   => $category->id,
                    'name' => trim($category->name),
                    'type' => 'category',
                    'meta' => 'Specialization',
                ];
            })
            ->toArray();

        // Combine
        $suggestions = array_merge($doctorSuggestions, $clinicSuggestions, $categorySuggestions);

        // De-duplicate by name
        $seen = [];
        $uniqueSuggestions = [];

        foreach ($suggestions as $suggestion) {
            $key = mb_strtolower(trim($suggestion['name']), 'UTF-8');
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $uniqueSuggestions[] = $suggestion;
            }
        }

        // If we have a category match, hide clinics (to keep list short)
        $hasCategory = false;
        foreach ($uniqueSuggestions as $s) {
            if ($s['type'] === 'category') {
                $hasCategory = true;
                break;
            }
        }

        if ($hasCategory) {
            $uniqueSuggestions = array_values(array_filter($uniqueSuggestions, function ($s) {
                return $s['type'] !== 'clinic';
            }));
        }

        // Sort: exact match first, then categories, then doctors, then clinics
        usort($uniqueSuggestions, function ($a, $b) use ($cleanQuery) {
            $order = [
                'category' => 1,
                'doctor'   => 2,
                'clinic'   => 3,
            ];

            $aExact = mb_strtolower($a['name'], 'UTF-8') === $cleanQuery;
            $bExact = mb_strtolower($b['name'], 'UTF-8') === $cleanQuery;

            if ($aExact && !$bExact) return -1;
            if ($bExact && !$aExact) return 1;

            return ($order[$a['type']] ?? 4) <=> ($order[$b['type']] ?? 4);
        });

        $uniqueSuggestions = array_slice($uniqueSuggestions, 0, 7);

        return response()->json([
            'suggestions' => $uniqueSuggestions,
        ]);
    }
}
