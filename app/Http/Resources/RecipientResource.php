<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecipientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
        // $data = parent::toArray($request);
        // $data['name'] = 'teste';
        // return parent::toArray($request);

        // return $data;

        // return [
        //     'id' => $this->id,
        // ];
    }
}
