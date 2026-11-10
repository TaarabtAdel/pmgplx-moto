<?php

namespace App\Models\Concerns;

use App\Support\ManhlinhMoto;

trait UsesManhlinhMotoConnection
{
    public function getConnectionName(): ?string
    {
        return ManhlinhMoto::connection();
    }
}
