<?php

namespace TVHung\Theme\Events;

use TVHung\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class RenderingHomePageEvent extends Event
{
    use SerializesModels;
}