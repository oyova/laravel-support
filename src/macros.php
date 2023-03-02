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
    ! method_exists(Field::class, 'showOnPeekAndPreview')
) {
    Field::macro('showOnPeekAndPreview', function (): Field {
        return $this
            ->showWhenPeeking()
            ->showOnPreview();
    });
}

if (
    class_exists(Field::class) &&
    ! method_exists(Field::class, 'showOnAllExceptForms')
) {
    Field::macro('showOnAllExceptForms', function (): Field {
        return $this
            ->exceptOnForms()
            ->showOnPeekAndPreview();
    });
}
