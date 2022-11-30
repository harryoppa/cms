<?php

namespace TVHung\Page\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RvMedia;

class PageResource extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'content'     => shortcode()->compile($this->content, true)->toHtml(),
            'image'       => $this->image ? RvMedia::getImageUrl($this->image) : null,
            'template'    => $this->template,
            'status'      => $this->status,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'url'         => $this->url,
        ];
    }

}
