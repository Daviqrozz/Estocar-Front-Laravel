<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable,HasFactory;

    protected $fillable = ['name', 'email', 'password'];

    public function adminlte_image()
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
    }
}
