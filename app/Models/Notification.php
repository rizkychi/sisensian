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

    public static function getNotification($limit = 5)
    {
        $data = Notification::where('to_id', Auth::user()->id)
            ->where('status', 'unread')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $data;
    }

    public static function readNotification($id)
    {
        $data = Notification::where('id', $id)
            ->where('to_id', Auth::user()->id)
            ->update(['status' => 'read']);

        return $data;
    }

    public static function readAllNotification()
    {
        $data = Notification::where('to_id', Auth::user()->id)
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        return $data;
    }

    public static function insertNotification($to, $title, $message, $url = null, $type = 'info')
    {
        if ($to == 'admin') {
            $to = User::where('role', 'superadmin')->pluck('id');
            
            foreach ($to as $id) {
                $data = new Notification();
                $data->from_id = Auth::user()->id;
                $data->to_id = $id;
                $data->title = $title;
                $data->message = $message;
                $data->type = $type;
                $data->url = $url;
                $data->save();
            }

            return $data;
        }

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

    public static function countNotification()
    {
        $data = Notification::where('to_id', Auth::user()->id)
            ->where('status', 'unread')
            ->count();

        return $data;
    }
}
