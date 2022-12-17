<?php

namespace App\Hateoas;

use App\Models\Message;
use GDebrauwer\Hateoas\Link;
use GDebrauwer\Hateoas\Traits\CreatesLinks;

class MessageHateoas
{
    use CreatesLinks;

    public function self(Message $message): ?Link
    {
        if (!auth()->user()->can('view', $message)) {
            return;
        }

        return $this->link('message.show', ['message' => $message]);
    }

    public function delete(Message $message): ?Link
    {
        if (!auth()->user()->can('delete', $message)) {
            return $this->link('message.archive', ['message' => $message]);
        }

        return $this->link('message.destroy', ['message' => $message]);
    }
}
