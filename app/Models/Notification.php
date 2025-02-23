<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    protected $table = 'notification';

    protected $fillable = [
        'from_id',
        'to_id',
        'title',
        'message',
        'status',
        'type',
        'url'
    ];

    protected $appends = [
        'time_ago'
    ];

    public function from()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    public function to()
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getNotification($limit = 5)
    {
        $data = Notification::where('to_id', Auth::user()->id)
            ->where('status', 'unread')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $data;
    }

    public function readNotification($id)
    {
        $data = Notification::where('id', $id)
            ->where('to_id', Auth::user()->id)
            ->update(['status' => 'read']);

        return $data;
    }

    public function readAllNotification()
    {
        $data = Notification::where('to_id', Auth::user()->id)
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        return $data;
    }

    public function insertNotification($to, $title, $message, $type = 'info', $url = null)
    {
        $data = new Notification();
        $data->from_id = Auth::user()->id;
        $data->to_id = $to;
        $data->title = $title;
        $data->message = $message;
        $data->type = $type;
        $data->url = $url;
        $data->save();

        return $data;
    }

    public function countNotification()
    {
        $data = Notification::where('to_id', Auth::user()->id)
            ->where('status', 'unread')
            ->count();

        return $data;
    }
}
