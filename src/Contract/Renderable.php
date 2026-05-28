<?php

namespace App\Contract;

interface Renderable {
    public function render(): string;
}