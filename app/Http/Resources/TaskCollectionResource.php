<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskCollectionResource extends JsonResource
{
    public static $wrap = 'Tasks';

    protected $message = null;

    public function setMessage($message)
    {
        $this->message = $message ;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Tasks' => TaskResource::collection($this),

        ];
    }

    public function with(Request $request)
    {
        return [
            'success' => true,
            'message' => $this->message,
        ];
    }
}
