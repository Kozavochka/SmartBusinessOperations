<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\IndexTenantRequest;
use App\Http\Requests\Tenant\StoreTenantRequest;
use App\Http\Requests\Tenant\UpdateTenantRequest;
use App\Http\Resources\TenantResource;
use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Http\JsonResponse;

class TenantController extends Controller
{
    public function __construct(private readonly TenantService $tenants)
    {
    }

    public function index(IndexTenantRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $perPage = (int) ($validated['per_page'] ?? 15);
        $page = isset($validated['page']) ? (int) $validated['page'] : null;

        $paginator = $this->tenants->list($perPage, $page);
        $resource = TenantResource::collection($paginator);

        return $this->respondSuccess($resource);
    }

    public function store(StoreTenantRequest $request): JsonResponse
    {
        $tenant = $this->tenants->create($request->validated());

        return $this->respondSuccess(new TenantResource($tenant), 'Tenant created.', 201);
    }

    public function show(Tenant $tenant): JsonResponse
    {
        $tenant->load('domains');

        return $this->respondSuccess(new TenantResource($tenant));
    }

    public function update(UpdateTenantRequest $request, Tenant $tenant): JsonResponse
    {
        $tenant = $this->tenants->update($tenant, $request->validated());

        return $this->respondSuccess(new TenantResource($tenant), 'Tenant updated.');
    }

    public function destroy(Tenant $tenant): JsonResponse
    {
        $this->tenants->delete($tenant);

        return $this->respondSuccess(null, 'Tenant deleted.');
    }
}
