<?php

namespace TVHung\Theme\Events;

use TVHung\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class RenderingSiteMapEvent extends Event
{
    use SerializesModels;
}
