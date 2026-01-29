<?php

namespace App\Services;

use App\Filters\TenantFilter;
use App\Models\Tenant;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TenantService
{
    public function __construct(private readonly TenantFilter $filter)
    {
    }

    public function list(int $perPage = 15, ?int $page = null): LengthAwarePaginator
    {
        return QueryBuilder::for(Tenant::query()->with('domains'))
            ->allowedFilters([
                AllowedFilter::custom('search', $this->filter),
                AllowedFilter::exact('is_active'),
            ])
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function create(array $data): Tenant
    {
        $domains = $this->extractDomains($data);
        $tenant = Tenant::create($data);

        foreach ($domains as $domain) {
            $tenant->createDomain($domain);
        }

        return $tenant->load('domains');
    }

    public function update(Tenant $tenant, array $data): Tenant
    {
        $tenant->update($data);

        return $tenant->refresh()->load('domains');
    }

    public function delete(Tenant $tenant): void
    {
        $tenant->delete();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<int, string>
     */
    private function extractDomains(array $data): array
    {
        $domains = [];

        if (isset($data['domain']) && is_string($data['domain']) && $data['domain'] !== '') {
            $domains[] = $data['domain'];
        }

        if (isset($data['domains']) && is_array($data['domains'])) {
            foreach ($data['domains'] as $domain) {
                if (is_string($domain) && $domain !== '') {
                    $domains[] = $domain;
                }
            }
        }

        return array_values(array_unique($domains));
    }

}
