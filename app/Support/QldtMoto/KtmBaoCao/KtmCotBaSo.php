<?php

namespace App\Support\QldtMoto\KtmBaoCao;

final class KtmCotBaSo
{
    public function __construct(
        public int $tong,
        public int $dat,
        public int $rot,
    ) {}
}