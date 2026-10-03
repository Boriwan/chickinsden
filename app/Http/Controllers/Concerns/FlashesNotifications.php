<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\RedirectResponse;

/**
 * Queues the bottom-right notifications the layout renders.
 *
 * The action travels with the message so the notification can pick its icon
 * and colour — created green, updated cyan, deleted red — rather than the
 * wording having to be parsed back out of the sentence.
 */
trait FlashesNotifications
{
    /**
     * Queue a notification carrying its own action.
     *
     * Each redirect carries exactly one toast. Appending to whatever was
     * already in the session would let two writes in the same request stack up
     * a list that never gets shown, so the key is simply replaced.
     */
    protected function notify(RedirectResponse $response, string $message, string $action): RedirectResponse
    {
        return $response->with('notice', [
            ['message' => $message, 'action' => $action],
        ]);
    }
}
