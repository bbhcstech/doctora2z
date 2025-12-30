<?php

namespace App\Imports;

use App\Models\Country;
use App\Models\State;
use App\Models\District;
use App\Models\City;
use App\Models\Pincode;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class DistrictsImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    protected array $stats = [
        'rows_processed'      => 0,
        'countries_created'   => 0,
        'states_created'      => 0,
        'districts_created'   => 0,
        'cities_created'      => 0,
        'pincodes_inserted'   => 0,
        'pincodes_updated'    => 0,
        'rows_no_pincode'     => 0,
        'rows_bad_pincode'    => 0,
        'rows_errors'         => 0,
    ];

    public function collection(Collection $rows)
    {
        foreach ($rows as $i => $row) {
            $this->stats['rows_processed']++;

            // 1-based line no. (+1 for headings)
            $line = $i + 2;

            // Flexible headings: accept *_name or plain keys
            $countryName  = $this->clean((string)($row['country_name']  ?? $row['country']  ?? 'India'));
            $stateName    = $this->clean((string)($row['state_name']    ?? $row['state']    ?? ''));
            $districtName = $this->clean((string)($row['district_name'] ?? $row['district'] ?? ''));
            $cityName     = $this->clean((string)($row['city_name']     ?? $row['city']     ?? ''));
            $pincodeRaw   = trim((string)($row['pincode'] ?? ''));

            // must have state + district to place things correctly
            if ($stateName === '' || $districtName === '') {
                // we still continue with other rows
                $this->stats['rows_errors']++;
                continue;
            }

            // Normalize pincode to 6 digits if present
            $pin = null;
            if ($pincodeRaw !== '') {
                $pin = substr(preg_replace('/\D+/', '', $pincodeRaw), 0, 6);
                if (strlen($pin) !== 6) {
                    $this->stats['rows_bad_pincode']++;
                    $pin = null; // treat as no pincode
                }
            } else {
                $this->stats['rows_no_pincode']++;
            }

            try {
                DB::transaction(function () use ($countryName, $stateName, $districtName, $cityName, $pin) {

                    // Country
                    $country = $this->firstOrCreateNameInsensitive(new Country, $countryName);
                    if ($country->wasRecentlyCreated) $this->stats['countries_created']++;

                    // State
                    $state = $this->firstOrCreateNameInsensitive(
                        (new State)->setRelation('country', $country),
                        $stateName,
                        ['country_id' => $country->id]
                    );
                    if ($state->wasRecentlyCreated) $this->stats['states_created']++;

                    // District
                    $district = $this->firstOrCreateNameInsensitive(
                        (new District)->setRelation('state', $state),
                        $districtName,
                        ['state_id' => $state->id]
                    );
                    if ($district->wasRecentlyCreated) $this->stats['districts_created']++;

                    // City (fallback to district name if blank)
                    $finalCityName = $cityName !== '' ? $cityName : $district->name;
                    $city = $this->firstOrCreateCityInDistrict($finalCityName, $district->id);
                    if ($city->wasRecentlyCreated) $this->stats['cities_created']++;

                    // Pincode (optional): upsert by (pincode, city_id)
                    if ($pin) {
                        $existing = Pincode::where('pincode', $pin)
                            ->where('city_id', $city->id)
                            ->first();

                        if ($existing) {
                            $existing->update([
                                'country_id'  => $country->id,
                                'state_id'    => $state->id,
                                'district_id' => $district->id,
                            ]);
                            $this->stats['pincodes_updated']++;
                        } else {
                            Pincode::create([
                                'pincode'     => $pin,
                                'country_id'  => $country->id,
                                'state_id'    => $state->id,
                                'district_id' => $district->id,
                                'city_id'     => $city->id,
                            ]);
                            $this->stats['pincodes_inserted']++;
                        }
                    }
                });
            } catch (\Throwable $e) {
                // swallow and keep going; count it
                $this->stats['rows_errors']++;
                // optional: log the error if you want
                // \Log::error('Import row failed', ['line' => $line, 'error' => $e->getMessage()]);
            }
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function summary(): array
    {
        return $this->stats;
    }

    /* ------------ helpers ------------ */

    // Trim & collapse spaces; keep original casing (DB may already contain uppercase names)
    protected function clean(string $v): string
    {
        return trim(preg_replace('/\s+/', ' ', $v));
    }

    /**
     * Find by name case-insensitively; if not found, create with given extras.
     * Works for Country, State, District models that have a "name" column.
     */
    protected function firstOrCreateNameInsensitive($model, string $name, array $extra = [])
    {
        if ($name === '') {
            // Fallback safeguard; should not happen because we guard earlier
            $name = 'UNKNOWN';
        }

        $found = $model->newQuery()
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
            ->first();

        if ($found) return $found;

        $created = $model->newQuery()->create(array_merge(['name' => $name], $extra));
        // mark as recently created so counters work
        $created->wasRecentlyCreated = true;

        return $created;
    }

    /**
     * Cities are per-district; match by name (case-insensitive) and district_id.
     */
    protected function firstOrCreateCityInDistrict(string $name, int $districtId): City
    {
        $found = City::where('district_id', $districtId)
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
            ->first();

        if ($found) return $found;

        $created = City::create([
            'name'        => $name,
            'district_id' => $districtId,
        ]);
        $created->wasRecentlyCreated = true;

        return $created;
    }
}
