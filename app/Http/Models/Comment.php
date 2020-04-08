<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'messageComment', 'user_id', 'folder_id', 'comment_id'
    ];

    public function getResults($filter)
    {
        if (!isset($filter['filter']) && !isset($filter['folder_id']))
            return $this->orderBy('id', 'DESC')->with(['user', 'folder', 'commentsChield'])->get();

        return $this->where(function ($query) use ($filter) {
            if (isset($filter['folder_id']))
                $query->where('folder_id', $filter['folder_id']);
        })
            // ->toSql();dd($result);
            ->orderBy('id', 'DESC')
            ->with(['user', 'folder', 'commentsChield'])
            ->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
    public function commentsChield()
    {
        return $this->hasMany(Comment::class, 'comment_id');
    }
    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }
}
