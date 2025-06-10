Pour organiser tout cela proprement et te permettre un **copier-coller facile**, je vais te l’envoyer **en plusieurs parties claires** (fichiers ou blocs de code séparés). Voici le plan :

---

### ✅ Plan de livraison en plusieurs messages

1. **\[Partie 1] Migration + Modèle Eloquent**
2. **\[Partie 2] Middleware d’activité + IA**
3. **\[Partie 3] Service d’analyse IA**
4. **\[Partie 4] Événements + Listener + Logging config**
5. **\[Partie 5] Vue Blade admin (liste + Livewire/JS)**
6. **\[Partie 6] Dashboard IA ou assistant admin**
7. **\[Optionnel] Script d’installation / seeders / routes**

---

Nous commençons avec :

## 🧱 \[Partie 1] Migration + Modèle `ActivityLog`

### 📁 `database/migrations/xxxx_xx_xx_create_activity_logs_table.php`

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('action');
            $table->text('details')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('admin_id')->nullable()->constrained('admins');
            $table->boolean('is_suspicious')->default(false);
            $table->string('suspicion_type')->nullable();
            $table->timestamps();

            $table->index(['ip_address', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
```

---

### 📁 `app/Models/ActivityLog.php`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'admin_id', 'action', 'details',
        'ip_address', 'user_agent', 'metadata',
        'is_suspicious', 'suspicion_type'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_suspicious' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
```

---

🟢 **Prêt pour la Partie 2 (Middleware avec IA + Diffusion d’événements) ?**

Parfait, continuons avec :

---

## 🧩 \[Partie 2] Middleware : Journalisation + IA + Événements

### 📁 `app/Http/Middleware/LogUserActivity.php`

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
use App\Events\UserActivityLogged;

class LogUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check() && $this->shouldLog($request)) {
            $activity = ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $request->method(),
                'details' => $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => $this->getRequestMetadata($request),
            ]);

            $this->detectSuspiciousActivity($activity);

            event(new UserActivityLogged($activity));
        }

        return $response;
    }

    protected function shouldLog(Request $request): bool
    {
        return !in_array($request->method(), ['OPTIONS', 'HEAD']) &&
               !preg_match('#^/(api|_debugbar|livewire|assets|js|css|images|fonts)#', $request->path());
    }

    protected function getRequestMetadata(Request $request): array
    {
        return [
            'route' => $request->route()?->getName(),
            'parameters' => $request->route()?->parameters(),
            'headers' => collect($request->headers->all())->except(['cookie', 'x-csrf-token'])->toArray(),
        ];
    }

    protected function detectSuspiciousActivity(ActivityLog $activity): void
    {
        $recent = ActivityLog::where('ip_address', $activity->ip_address)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->count();

        if ($recent > 30) {
            $activity->update([
                'is_suspicious' => true,
                'suspicion_type' => 'rate_limiting',
            ]);
        }
    }
}
```

---

### 📁 `app/Events/UserActivityLogged.php`

```php
namespace App\Events;

use App\Models\ActivityLog;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserActivityLogged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ActivityLog $activity;

    public function __construct(ActivityLog $activity)
    {
        $this->activity = $activity->load('user');
    }

    public function broadcastOn(): array
    {
        return ['user-activity'];
    }

    public function broadcastAs(): string
    {
        return 'UserActivityLogged';
    }
}
```

---

👉 Ensuite, on passe à la **\[Partie 3] : Service d’analyse IA avec OpenAI** pour une détection intelligente et enrichie.

Super, passons maintenant à :

---

## 🤖 \[Partie 3] Service d’Analyse IA avec OpenAI

Ce service permet de soumettre chaque activité à **ChatGPT (ou GPT-4 via OpenAI)** pour une **analyse de menace** plus intelligente.

---

### 📁 `app/Services/ActivityAnalyzer.php`

```php
namespace App\Services;

use App\Models\ActivityLog;
use OpenAI\Laravel\Facades\OpenAI;

class ActivityAnalyzer
{
    public function analyze(ActivityLog $activity): void
    {
        $prompt = $this->createPrompt($activity);

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Tu es un analyste de sécurité. Ton rôle est d’identifier si une activité est suspecte.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ],
                ],
            ]);

            $content = trim($response->choices[0]->message->content);
            $analysis = json_decode($content, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $activity->update([
                    'is_suspicious' => $analysis['is_suspicious'] ?? false,
                    'suspicion_type' => $analysis['suspicion_type'] ?? null,
                    'metadata' => array_merge($activity->metadata ?? [], [
                        'ai_reason' => $analysis['reason'] ?? null,
                    ]),
                ]);
            }

        } catch (\Exception $e) {
            logger()->error('AI Analysis failed: ' . $e->getMessage());
        }
    }

    protected function createPrompt(ActivityLog $activity): string
    {
        return <<<EOT
Analyse l’activité suivante et détermine si elle est suspecte.

Données :
- IP : {$activity->ip_address}
- User ID : {$activity->user_id}
- Action : {$activity->action}
- URL : {$activity->details}
- Agent : {$activity->user_agent}

Réponds en JSON : 
{
  "is_suspicious": true|false,
  "suspicion_type": "brute_force|ddos|rate_limiting|null",
  "reason": "Brève explication de la détection"
}
EOT;
    }
}
```

---

### ✅ Comment l’utiliser dans le middleware ou listener :

```php
use App\Services\ActivityAnalyzer;

// ...

(new ActivityAnalyzer)->analyze($activity);
```

---

🧠 Ce système te permet de **déléguer l’analyse des comportements à une IA**, de **fournir des insights en temps réel** et de **stocker les raisons dans les logs**.

📦 Prêt pour la **\[Partie 4] : Listeners + Configuration du logging + Notifications** ?

Parfait 👌 ! Voici :

---

## 🧩 \[Partie 4] Listener, Configuration Logging, Notifications

On va créer un **listener** pour analyser les logs en arrière-plan, configurer les **logs séparés**, et préparer les **notifications pour les admins**.

---

### 📁 `app/Listeners/AnalyzeUserActivity.php`

```php
namespace App\Listeners;

use App\Events\UserActivityLogged;
use App\Services\ActivityAnalyzer;

class AnalyzeUserActivity
{
    public function handle(UserActivityLogged $event): void
    {
        (new ActivityAnalyzer)->analyze($event->activity);
    }
}
```

---

### 📁 `app/Listeners/NotifySuspiciousActivity.php`

```php
namespace App\Listeners;

use App\Events\UserActivityLogged;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SuspiciousActivityDetected;

class NotifySuspiciousActivity
{
    public function handle(UserActivityLogged $event): void
    {
        $activity = $event->activity;

        if ($activity->is_suspicious) {
            Notification::route('mail', config('security.alert_email'))
                ->notify(new SuspiciousActivityDetected($activity));
        }
    }
}
```

---

### 📁 `app/Notifications/SuspiciousActivityDetected.php`

```php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\ActivityLog;

class SuspiciousActivityDetected extends Notification
{
    use Queueable;

    public function __construct(public ActivityLog $activity) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Activité suspecte détectée')
            ->line("Action : {$this->activity->action}")
            ->line("Utilisateur ID : {$this->activity->user_id}")
            ->line("IP : {$this->activity->ip_address}")
            ->line("Raison IA : " . ($this->activity->metadata['ai_reason'] ?? 'Non précisée'))
            ->action('Voir les logs', url("/admin/activities/{$this->activity->id}"));
    }
}
```

---

### 📁 `config/logging.php`

Ajoute ces **channels personnalisés** :

```php
'channels' => [

    // ... autres channels ...

    'activity' => [
        'driver' => 'daily',
        'path' => storage_path('logs/activity/activity.log'),
        'level' => 'info',
        'days' => 14,
    ],

    'suspicious_activity' => [
        'driver' => 'daily',
        'path' => storage_path('logs/activity/suspicious.log'),
        'level' => 'warning',
        'days' => 30,
    ],
],
```

---

### 📁 `app/Providers/EventServiceProvider.php`

```php
use App\Events\UserActivityLogged;
use App\Listeners\AnalyzeUserActivity;
use App\Listeners\NotifySuspiciousActivity;

protected $listen = [
    UserActivityLogged::class => [
        AnalyzeUserActivity::class,
        NotifySuspiciousActivity::class,
    ],
];
```

---

### 📁 `.env`

Ajoute une adresse e-mail d’alerte :

```env
SECURITY_ALERT_EMAIL=admin@example.com
```

Et dans `config/security.php` (à créer si nécessaire) :

```php
return [
    'alert_email' => env('SECURITY_ALERT_EMAIL', 'admin@example.com'),
];
```

---

✅ On est prêt pour la suite :
👉 **\[Partie 5] Interface Blade admin + Vue en temps réel + Alertes Toastr** ?

Génial 🎯 ! Voici :

---

## 🖥️ \[Partie 5] Interface Blade Admin + Vue Temps Réel + Alertes

On va construire une interface Blade admin moderne avec **TailwindCSS**, **Toastr**, et **Laravel Echo** pour recevoir des logs en temps réel.

---

### 📁 `resources/views/admin/activities/index.blade.php`

```blade
@extends('layouts.admin')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-semibold mb-4">Journal des Activités</h1>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 text-sm text-left text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Utilisateur</th>
                    <th class="px-4 py-2">Action</th>
                    <th class="px-4 py-2">IP</th>
                    <th class="px-4 py-2">Statut</th>
                    <th class="px-4 py-2">Voir</th>
                </tr>
            </thead>
            <tbody id="activity-log-body" class="bg-white divide-y divide-gray-200 text-sm">
                @foreach($activities as $activity)
                <tr class="{{ $activity->is_suspicious ? 'bg-red-100' : '' }}">
                    <td class="px-4 py-2">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-2">
                        {{ optional($activity->user)->name ?? 'Invité' }}
                    </td>
                    <td class="px-4 py-2">{{ $activity->action }}</td>
                    <td class="px-4 py-2">{{ $activity->ip_address }}</td>
                    <td class="px-4 py-2">
                        @if($activity->is_suspicious)
                            <span class="text-red-600 font-bold">Suspect</span>
                        @else
                            <span class="text-green-600">Normal</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.activities.show', $activity) }}" class="text-blue-500 hover:underline">Détails</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-4 py-4">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" />

<script>
    Echo.channel('user-activity')
        .listen('.UserActivityLogged', (event) => {
            const activity = event.activity;
            const tr = document.createElement('tr');
            tr.className = activity.is_suspicious ? 'bg-red-100' : '';
            tr.innerHTML = `
                <td class="px-4 py-2">${new Date(activity.created_at).toLocaleString()}</td>
                <td class="px-4 py-2">${activity.user?.name ?? 'Invité'}</td>
                <td class="px-4 py-2">${activity.action}</td>
                <td class="px-4 py-2">${activity.ip_address}</td>
                <td class="px-4 py-2">${activity.is_suspicious ? '<span class="text-red-600 font-bold">Suspect</span>' : '<span class="text-green-600">Normal</span>'}</td>
                <td class="px-4 py-2"><a href="/admin/activities/${activity.id}" class="text-blue-500 hover:underline">Détails</a></td>
            `;
            document.getElementById('activity-log-body').prepend(tr);

            if (activity.is_suspicious) {
                toastr.warning('Activité suspecte détectée : ' + activity.suspicion_type, 'Alerte sécurité');
            }
        });
</script>
@endsection
```

---

### ✅ Prérequis JS :

1. **Installer Laravel Echo + Pusher** (ou Ably, Soketi, Redis...)

```bash
npm install --save laravel-echo pusher-js
```

2. **Configurer `resources/js/bootstrap.js`** :

```js
import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    encrypted: true
});
```

3. **.env**

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-id
PUSHER_APP_KEY=your-key
PUSHER_APP_SECRET=your-secret
PUSHER_APP_CLUSTER=mt1
```

---

🎯 Tu as maintenant une interface moderne, réactive et alimentée en temps réel !

👉 Tu veux la **\[Partie 6] Routes + Contrôleur + Filtres** ?

Parfait ! 🔥 Voici :

---

## 🧭 \[Partie 6] Routes + Contrôleur + Filtres d'Activité

On ajoute les routes pour accéder à l’interface admin, les filtres (ex. : activité suspecte seulement), et le contrôleur associé.

---

### 📁 `routes/web.php`

Ajoute dans une section protégée par middleware `admin` ou `auth` selon ton architecture :

```php
use App\Http\Controllers\Admin\ActivityLogController;

Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    Route::get('activities', [ActivityLogController::class, 'index'])->name('activities.index');
    Route::get('activities/{activity}', [ActivityLogController::class, 'show'])->name('activities.show');
});
```

---

### 📁 `app/Http/Controllers/Admin/ActivityLogController.php`

```php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user', 'admin'])
            ->orderByDesc('created_at');

        // Filtres
        if ($request->get('filter') === 'suspicious') {
            $query->where('is_suspicious', true);
        }

        $activities = $query->paginate(30);

        return view('admin.activities.index', compact('activities'));
    }

    public function show(ActivityLog $activity)
    {
        return view('admin.activities.show', compact('activity'));
    }
}
```

---

### 📁 `resources/views/admin/activities/show.blade.php`

```blade
@extends('layouts.admin')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    <h2 class="text-xl font-semibold mb-4">Détail de l’activité</h2>

    <div class="bg-white rounded-lg shadow p-6 text-sm space-y-4">
        <div><strong>Date :</strong> {{ $activity->created_at->format('d/m/Y H:i') }}</div>
        <div><strong>Utilisateur :</strong>
            {{ optional($activity->user)->name ?? 'Invité' }}
            @if($activity->admin_id)
                (Admin)
            @endif
        </div>
        <div><strong>Action :</strong> {{ $activity->action }}</div>
        <div><strong>Détails :</strong> {{ $activity->details }}</div>
        <div><strong>IP :</strong> {{ $activity->ip_address }}</div>
        <div><strong>User Agent :</strong> {{ $activity->user_agent }}</div>
        <div><strong>Suspect :</strong>
            @if($activity->is_suspicious)
                <span class="text-red-600 font-semibold">Oui ({{ $activity->suspicion_type }})</span>
            @else
                <span class="text-green-600">Non</span>
            @endif
        </div>
        @if($activity->metadata)
        <div>
            <strong>Métadonnées :</strong>
            <pre class="bg-gray-100 p-2 rounded">{{ json_encode($activity->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
        @endif
    </div>
</div>
@endsection
```

---

### 🔎 Bonus : `ActivityLog` relations (modèle)

Ajoute dans `app/Models/ActivityLog.php` :

```php
public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}

public function admin()
{
    return $this->belongsTo(\App\Models\Admin::class);
}
```

---

✅ Avec ça :

* Tu as une interface de logs filtrable.
* Une page de détail.
* Les données sont automatiquement enrichies avec relations et IA.
* Et tout est déjà prêt pour l’intégration frontend ou notifications.

👉 Prêt pour la **Partie 7 (optionnelle)** : *Dashboard Graphique + Vue JS/Tailwind Charts* ?

Parfait, allons-y pour la **🚀 Partie 7**. Ensuite je te répondrai sur `OpenAI`.

---

## 📊 \[Partie 7] Dashboard Graphique en Blade avec Tailwind + Chart.js

Objectif : visualiser les logs (totaux, suspects, par heure/IP/user) de façon claire pour les admins.

---

### 📁 Route : `routes/web.php`

Ajoute à ton group admin :

```php
Route::get('activity-dashboard', [ActivityLogController::class, 'dashboard'])->name('activities.dashboard');
```

---

### 📁 Méthode : `ActivityLogController.php`

```php
public function dashboard()
{
    $total = ActivityLog::count();
    $suspect = ActivityLog::where('is_suspicious', true)->count();

    $logsPerHour = ActivityLog::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
        ->groupByRaw('HOUR(created_at)')
        ->orderBy('hour')
        ->get();

    $byIp = ActivityLog::selectRaw('ip_address, COUNT(*) as total')
        ->groupBy('ip_address')
        ->orderByDesc('total')
        ->limit(5)
        ->get();

    return view('admin.activities.dashboard', compact('total', 'suspect', 'logsPerHour', 'byIp'));
}
```

---

### 📁 Vue : `resources/views/admin/activities/dashboard.blade.php`

```blade
@extends('layouts.admin')

@section('content')
<div class="px-6 py-4">
    <h2 class="text-xl font-semibold mb-6">📈 Dashboard Activités</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-sm text-gray-600">Total Activités</h3>
            <p class="text-2xl font-bold">{{ $total }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-sm text-gray-600">Activités Suspectes</h3>
            <p class="text-2xl font-bold text-red-600">{{ $suspect }}</p>
        </div>
    </div>

    <div class="bg-white p-4 rounded shadow mb-6">
        <h3 class="text-sm text-gray-600 mb-2">Activités par Heure</h3>
        <canvas id="logsByHourChart"></canvas>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <h3 class="text-sm text-gray-600 mb-2">Top IPs</h3>
        <ul class="list-disc pl-5">
            @foreach($byIp as $ip)
                <li>{{ $ip->ip_address }} - {{ $ip->total }} logs</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('logsByHourChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($logsPerHour->pluck('hour')),
            datasets: [{
                label: 'Logs par Heure',
                data: @json($logsPerHour->pluck('count')),
                backgroundColor: '#3b82f6'
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true },
                x: { title: { display: true, text: 'Heure' } }
            }
        }
    });
</script>
@endsection
```

---

## ❓ Réponse à ta question : `use OpenAI\Laravel\Facades\OpenAI;`

Non, **OpenAI n’est pas installé par défaut dans Laravel**.

Pour utiliser `OpenAI::chat()` comme dans notre `ActivityAnalyzer`, tu dois installer le package :

```bash
composer require openai-php/laravel
```

Puis publier la config :

```bash
php artisan vendor:publish --tag="openai-config"
```

Et configurer ta clé dans `.env` :

```env
OPENAI_API_KEY=sk-...
```

📘 Docs officielles : [https://github.com/openai-php/laravel](https://github.com/openai-php/laravel)

---

✅ Tu as maintenant :

* Un dashboard moderne 📊
* Les logs en temps réel
* L’analyse IA
* Une base solide pour un système de détection + alerte

---

Souhaites-tu maintenant :

1. Ajouter un **filtrage avancé dynamique** côté admin ?
2. Intégrer **notifications Telegram/Discord/mail** en cas d'activité suspecte ?
3. Générer un **rapport PDF téléchargeable** des logs ?

Dis-moi simplement : **go notifications**, **go PDF**, ou **go filtres avancés**.
