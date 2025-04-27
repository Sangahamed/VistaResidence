<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedSearch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'criteria',
        'alert_frequency',
        'last_alert_sent_at',
    ];

    protected $casts = [
        'criteria' => 'json',
        'last_alert_sent_at' => 'datetime',
    ];

    /**
     * L'utilisateur qui a sauvegardé cette recherche.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifie si cette recherche a des alertes activées.
     */
    public function hasAlerts()
    {
        return !empty($this->alert_frequency);
    }

    /**
     * Détermine si une alerte doit être envoyée en fonction de la fréquence.
     */
    public function shouldSendAlert()
    {
        if (!$this->hasAlerts()) {
            return false;
        }

        if (!$this->last_alert_sent_at) {
            return true;
        }

        switch ($this->alert_frequency) {
            case 'daily':
                return $this->last_alert_sent_at->diffInDays(now()) >= 1;
            case 'weekly':
                return $this->last_alert_sent_at->diffInWeeks(now()) >= 1;
            case 'monthly':
                return $this->last_alert_sent_at->diffInMonths(now()) >= 1;
            case 'instant':
                return true;
            default:
                return false;
        }
    }

    /**
     * Marque cette recherche comme ayant reçu une alerte.
     */
    public function markAlertSent()
    {
        $this->update(['last_alert_sent_at' => now()]);
    }
}