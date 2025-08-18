<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'company_id',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

      public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


       public function sendEmailVerificationNotification()
    {
        $this->notify(new class extends VerifyEmail {
            protected function buildMailMessage($url)
            {
                return (new MailMessage)
                    ->subject('DigitalBookのメールアドレスを確認してください')
                    ->view('emails.verify', [
                        'url' => $url,
                    ]);
            }
        });
    }



    public function sendPasswordResetNotification($token)
{
    $this->notify(new class($token) extends ResetPassword {
        public function __construct($token)
        {
            parent::__construct($token);
        }

        public function toMail($notifiable)
        {
            $url = url("/reset-password/{$this->token}"); 
        

            return (new MailMessage)
                ->subject('DigitalBookのパスワードリセット')
                ->view('emails.reset-password', [
                    'url' => $url,
                    'user' => $notifiable,
                ]);
        }
    });
}
}
