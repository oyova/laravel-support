<?php

namespace Oyova\LaravelSupport\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laravel\Nova\Filters\BooleanFilter;

class EnumFilter extends BooleanFilter
{
    private string $column;

    private string $enumClass;

    private array $defaults;

    public function __construct(
        string $column,
        string $enumClass,
        array $defaults = []
    ) {
        $this->column = $column;
        $this->enumClass = $enumClass;
        $this->defaults = $defaults;
    }

    public function name(): string
    {
        return str($this->column)->snake()->replace('_', ' ')->title();
    }

    public function key(): string
    {
        return "enum_{$this->column}";
    }

    public function apply(Request $request, $query, $values): Builder
    {
        return $query->whereIn(
            $this->column,
            collect($values)->filter()->keys()
        );
    }

    public function options(Request $request): Collection
    {
        return collect($this->enumClass::cases())
            ->mapWithKeys(fn ($c) => [$c->label => $c->value]);
    }

    public function default(): Collection
    {
        return collect($this->defaults ?: $this->enumClass::cases())
            ->mapWithKeys(fn ($c) => [$c->value => true]);
    }
}
