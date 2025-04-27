<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type', // apartment, house, land, etc.
        'status', // for_sale, for_rent, sold, rented
        'price',
        'address',
        'city',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'bedrooms',
        'bathrooms',
        'area',
        'year_built',
        'features', // JSON array
        'is_featured',
        'owner_id',
        'company_id',
        'images', // JSON array
        'videos', // JSON array
        'has_virtual_tour',
        'virtual_tour_type', // 'basic', 'panoramic', '3d'
        'virtual_tour_url',
        'panoramic_images',
    ];

    protected $casts = [
        'features' => 'array',
        'images' => 'array',
        'videos' => 'array',
        'is_featured' => 'boolean',
        'has_virtual_tour' => 'boolean',
        'panoramic_images' => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function favoriteUsers()
    {
        return $this->belongsToMany(User::class, 'user_favorites')
            ->withTimestamps();
    }

    /**
     * Vérifie si la propriété est dans les favoris de l'utilisateur connecté.
     */
    public function isFavorited()
    {
        if (!Auth::check()) {
            return false;
        }
        
        return $this->favorites()->where('user_id', Auth::id())->exists();
    }
    /**
     * Les utilisateurs qui ont mis cette propriété en favori.
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get the visits for the property.
     */
    public function visits()
    {
        return $this->hasMany(PropertyVisit::class);
    }

    /**
     * Get the upcoming visits for the property.
     */
    public function upcomingVisits()
    {
        return $this->visits()->upcoming();
    }

    /**
     * Get the available time slots for visits.
     * 
     * @param \Carbon\Carbon $date
     * @return array
     */
    public function getAvailableTimeSlots($date)
    {
        // Définir les créneaux horaires disponibles (par exemple, de 9h à 18h par tranches de 1h)
        $availableSlots = [];
        $startHour = 9;
        $endHour = 18;
        $duration = 60; // durée en minutes
        
        // Récupérer les visites déjà programmées pour cette date
        $bookedVisits = $this->visits()
            ->whereDate('visit_date', $date->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();
        
        // Créer tous les créneaux possibles
        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $slotStart = $date->copy()->setHour($hour)->setMinute(0)->setSecond(0);
            $slotEnd = $slotStart->copy()->addMinutes($duration);
            
            // Vérifier si le créneau est déjà réservé
            $isAvailable = true;
            foreach ($bookedVisits as $visit) {
                $visitStart = \Carbon\Carbon::parse($visit->visit_date->format('Y-m-d') . ' ' . $visit->visit_time_start);
                $visitEnd = \Carbon\Carbon::parse($visit->visit_date->format('Y-m-d') . ' ' . $visit->visit_time_end);
                
                // Si le créneau chevauche une visite existante, il n'est pas disponible
                if ($slotStart < $visitEnd && $slotEnd > $visitStart) {
                    $isAvailable = false;
                    break;
                }
            }
            
            // Si le créneau est dans le passé, il n'est pas disponible
            if ($slotStart < now()) {
                $isAvailable = false;
            }
            
            if ($isAvailable) {
                $availableSlots[] = [
                    'start' => $slotStart->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                    'formatted' => $slotStart->format('H:i') . ' - ' . $slotEnd->format('H:i')
                ];
            }
        }
        
        return $availableSlots;
    }
}