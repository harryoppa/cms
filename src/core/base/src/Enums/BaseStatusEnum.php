<?php

namespace TVHung\Base\Enums;

use TVHung\Base\Supports\Enum;
use Html;

/**
 * @method static BaseStatusEnum DRAFT()
 * @method static BaseStatusEnum PUBLISHED()
 * @method static BaseStatusEnum PENDING()
 */
class BaseStatusEnum extends Enum
{
    public const PUBLISHED = 'published';
    public const DRAFT = 'draft';
    public const PENDING = 'pending';

    /**
     * @var string
     */
    public static $langPath = 'core/base::enums.statuses';

    /**
     * @return string
     */
    public function toHtml()
    {
        return match ($this->value) {
            self::DRAFT => Html::tag('span', self::DRAFT()->label(), ['class' => 'label-info status-label'])
                ->toHtml(),
            self::PENDING => Html::tag('span', self::PENDING()->label(), ['class' => 'label-warning status-label'])
                ->toHtml(),
            self::PUBLISHED => Html::tag('span', self::PUBLISHED()->label(), ['class' => 'label-success status-label'])
                ->toHtml(),
            default => parent::toHtml(),
        };
    }
}
