<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport d'activité</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2563eb;
            margin-bottom: 5px;
        }
        .header p {
            color: #6b7280;
            margin-top: 0;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            color: #1e40af;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #e5e7eb;
        }
        th, td {
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
        }
        .metric {
            display: inline-block;
            width: 30%;
            margin-right: 3%;
            margin-bottom: 20px;
            vertical-align: top;
        }
        .metric h3 {
            margin-bottom: 5px;
            color: #4b5563;
        }
        .metric p {
            font-size: 24px;
            font-weight: bold;
            margin-top: 0;
            color: #2563eb;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport d'activité {{ ucfirst($period['type']) }}</h1>
        <p>Période : {{ $period['start'] }} à {{ $period['end'] }}</p>
    </div>

    <div class="section">
        <h2>Résumé</h2>
        
        <div class="metric">
            <h3>Activités totales</h3>
            <p>{{ number_format($summary['total_activities']) }}</p>
        </div>
        
        <div class="metric">
            <h3>Activités suspectes</h3>
            <p>{{ number_format($summary['suspicious_activities']) }} ({{ $summary['suspicious_percentage'] }}%)</p>
        </div>
        
        <div class="metric">
            <h3>Utilisateurs uniques</h3>
            <p>{{ number_format($summary['unique_users']) }}</p>
        </div>
    </div>

    <div class="section">
        <h2>Activité des utilisateurs</h2>
        
        <h3>Utilisateurs les plus actifs</h3>
        <table>
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Activités</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user_activity['most_active_users'] as $user)
                <tr>
                    <td>{{ $user->user->name ?? 'N/A' }}</td>
                    <td>{{ $user->user->email ?? 'N/A' }}</td>
                    <td>{{ number_format($user->total) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <h3>Nouveaux utilisateurs</h3>
        <p>{{ number_format($user_activity['new_users']) }} nouveaux utilisateurs enregistrés pendant cette période.</p>
    </div>

    <div class="section">
        <h2>Activités suspectes</h2>
        
        <h3>Types d'activités suspectes</h3>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suspicious_activity['suspicious_types'] as $type)
                <tr>
                    <td>{{ $type->suspicion_type ?? 'Non spécifié' }}</td>
                    <td>{{ number_format($type->total) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <h3>Activités à haut risque</h3>
        <table>
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Action</th>
                    <th>Score de risque</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suspicious_activity['high_risk_activities'] as $activity)
                <tr>
                    <td>{{ $activity->user->name ?? 'N/A' }}</td>
                    <td>{{ $activity->action }}</td>
                    <td>{{ $activity->risk_score }}</td>
                    <td>{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Métriques financières</h2>
        
        <div class="metric">
            <h3>Activités de paiement</h3>
            <p>{{ number_format($financial_metrics['payment_activities']) }}</p>
        </div>
        
        <div class="metric">
            <h3>Revenu total</h3>
            <p>{{ number_format($financial_metrics['total_revenue']) }} €</p>
        </div>
        
        <div class="metric">
            <h3>Valeur moyenne</h3>
            <p>{{ number_format($financial_metrics['average_order_value']) }} €</p>
        </div>
    </div>

    <div class="section">
        <h2>Répartition géographique</h2>
        
        <h3>Top pays</h3>
        <table>
            <thead>
                <tr>
                    <th>Pays</th>
                    <th>Activités</th>
                </tr>
            </thead>
            <tbody>
                @foreach($geo_activity['countries'] as $country)
                <tr>
                    <td>{{ str_replace('"', '', $country->country) }}</td>
                    <td>{{ number_format($country->total) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Rapport généré automatiquement le {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
