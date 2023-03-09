<?php

namespace TVHung\Base\Models\Concerns;

use Illuminate\Support\Str;

trait HasUuidsOrIntegerIds
{
    public static function bootHasUuidsOrIntegerIds(): void
    {
        static::creating(static function (self $model): void {
            if (! self::determineIfUsingUuidsForId()) {
                return;
            }

            $model->{$model->getKeyName()} = $model->newUniqueId();
        });
    }

    public static function newUniqueId(): string
    {
        return (string) Str::orderedUuid();
    }

    public function getKeyType(): string
    {
        if (self::determineIfUsingUuidsForId()) {
            return 'string';
        }

        return $this->keyType;
    }

    public function getIncrementing(): bool
    {
        if (self::determineIfUsingUuidsForId()) {
            return false;
        }

        return $this->incrementing;
    }

    public static function determineIfUsingUuidsForId(): bool
    {
        return config('core.base.general.using_uuids_for_id', false);
    }
}
