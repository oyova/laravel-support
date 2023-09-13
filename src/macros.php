<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Laravel\Nova\Fields\Field;

if (
    class_exists(Stringable::class) &&
    ! method_exists(Stringable::class, 'squish')
) {
    /**
     * This method exists in Laravel >= 9.
     */
    Stringable::macro('squish', function (): Stringable {
        return new static(Str::squish($this->value));
    });
}

if (
    class_exists(Str::class) &&
    ! method_exists(Str::class, 'squish')
) {
    /**
     * This method exists in Laravel >= 9.
     */
    Str::macro('squish', function ($value): string {
        return preg_replace(
            '~(\s|\x{3164})+~u',
            ' ',
            preg_replace('~^[\s﻿]+|[\s﻿]+$~u', '', $value)
        );
    });
}

if (
    class_exists(Collection::class) &&
    ! method_exists(Collection::class, 'rescueEach')
) {
    Collection::macro(
        'rescueEach',
        function (callable $callback, $rescue = null, $report = true) {
            return $this->each(
                function ($item, $key) use ($callback, $rescue, $report) {
                    return rescue(
                        function () use ($callback, $item, $key) {
                            return $callback($item, $key);
                        },
                        $rescue,
                        $report
                    );
                }
            );
        }
    );
}

if (
    class_exists(Field::class) &&
    ! method_exists(Field::class, 'showOn')
) {
    Field::macro('showOn', function ($on = []): Field {
        $on = (array) $on;

        $all = $on['all'] ?? (array_search('all', $on, true) !== false ? true : null);
        $forms = $on['forms'] ?? (array_search('forms', $on, true) !== false ? true : null);

        $this->showOnIndex = $on['index'] ?? $all ?? array_search('index', $on, true) !== false;
        $this->showWhenPeeking = $on['peek'] ?? $all ?? array_search('peek', $on, true) !== false;
        $this->showOnPreview = $on['preview'] ?? $all ?? array_search('preview', $on, true) !== false;
        $this->showOnDetail = $on['detail'] ?? $all ?? array_search('detail', $on, true) !== false;
        $this->showOnCreation = $on['creation'] ?? $forms ?? $all ?? array_search('creation', $on, true) !== false;
        $this->showOnUpdate = $on['update'] ?? $forms ?? $all ?? array_search('update', $on, true) !== false;

        return $this;
    });
}

if (
    class_exists(Field::class) &&
    ! method_exists(Field::class, 'showOnPeekAndPreview')
) {
    /**
     * @deprecated in favor of showOn()
     */
    Field::macro('showOnPeekAndPreview', function (): Field {
        return $this
            ->showOn([
                'peek',
                'preview',
            ]);
    });
}

if (
    class_exists(Field::class) &&
    ! method_exists(Field::class, 'showOnAll')
) {
    /**
     * @deprecated in favor of showOn()
     */
    Field::macro('showOnAll', function (): Field {
        return $this->showOn('all');
    });
}

if (
    class_exists(Field::class) &&
    ! method_exists(Field::class, 'showOnAllExceptForms')
) {
    /**
     * @deprecated in favor of showOn()
     */
    Field::macro('showOnAllExceptForms', function (): Field {
        return $this
            ->showOn([
                'all',
                'forms' => false,
            ]);
    });
}
