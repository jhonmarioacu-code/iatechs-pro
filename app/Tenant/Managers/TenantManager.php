<?php

declare(strict_types=1);

namespace App\Tenant\Managers;

use RuntimeException;
use App\Domains\Companies\Models\Company;

class TenantManager
{
    /**
     * Tenant actual cargado.
     */
    protected ?Company $tenant = null;

    /**
     * Establece el tenant actual.
     */
    public function setTenant(
        ?Company $company
    ): void {

        $this->tenant = $company;
    }

    /**
     * Obtiene el tenant actual.
     */
    public function tenant(): ?Company
    {
        return $this->tenant;
    }

    /**
     * Obtiene el tenant actual o lanza excepción.
     */
    public function requireTenant(): Company
    {
        if (!$this->tenant) {

            throw new RuntimeException(
                'Tenant not resolved.'
            );
        }

        return $this->tenant;
    }

    /**
     * Obtiene el ID de la empresa actual.
     */
    public function companyId(): ?int
    {
        return $this->tenant?->id;
    }

    /**
     * Verifica si existe tenant activo.
     */
    public function hasTenant(): bool
    {
        return $this->tenant !== null;
    }

    /**
     * Limpia tenant actual.
     */
    public function forgetTenant(): void
    {
        $this->tenant = null;
    }
}