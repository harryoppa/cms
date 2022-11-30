<?php

namespace TVHung\Theme\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RvMedia;
use TVHung\Page\Models\Page;


class SlugViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'content'       => shortcode()->compile($this->content, true)->toHtml(),
            'image'         => $this->image ? RvMedia::getImageUrl($this->image) : null,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'tags'          => $this->when($this->tags, $this->tags),
            'categories'    => $this->when($this->categories, $this->categories),
            'slug_type'     => $this->when($this->slugable, function() {
                return match($this->slugable->reference_type) {
                    Page::class => 'page',
                    default => 'post',
                };
            }),
            'template'      => $this->when('template', $this->template),
        ];
    }
}
