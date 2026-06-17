<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Attachment;
use App\Models\Thread;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Find all attachments where post_id is null, and associate them with the first post of their thread
        $attachments = Attachment::whereNull('post_id')->whereNotNull('thread_id')->get();
        
        foreach ($attachments as $attachment) {
            $thread = Thread::find($attachment->thread_id);
            if ($thread) {
                $firstPost = $thread->posts()->orderBy('created_at', 'asc')->first();
                if ($firstPost) {
                    $attachment->post_id = $firstPost->id;
                    $attachment->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data correction migrations generally do not need a reverse operation
    }
};
