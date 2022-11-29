<?php

namespace TVHung\Menu\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuNodeResource extends JsonResource
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
            'menu'          => $this->menu_id,
            'parent_id'     => $this->parent_id,
            'reference_id'  => $this->reference_id,
            'reference_type'=> $this->reference_type,
            'title'         => $this->title,
            'url'           => $this->url,
            'target'        => $this->target,
            'icon_font'     => $this->icon_font,
            'css_class'     => $this->css_class,
            'status'        => $this->status,
            'order'         => $this->order,
            'has_child'     => $this->has_child,
            'children'      => $this->when($this->has_child, function() {
                return MenuNodeResource::collection($this->child);
            }),
        ];
    }
}
