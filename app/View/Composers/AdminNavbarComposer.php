<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Chat\ChatMessage;
use Illuminate\Support\Facades\Auth;

class AdminNavbarComposer
{
    public function compose(View $view)
    {
        $unreadMessagesCount = 0;
        
        if (Auth::check()) {
            $unreadMessagesCount = ChatMessage::where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();
        }

        $view->with('unreadMessagesCount', $unreadMessagesCount);
    }
}