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
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
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
            margin-bottom: 20px;
        }
        .section h2 {
            color: #1e40af;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .metric {
            margin-bottom: 15px;
        }
        .metric h3 {
            margin-bottom: 5px;
            color: #4b5563;
        }
        .metric p {
            font-size: 18px;
            font-weight: bold;
            margin-top: 0;
            color: #2563eb;
        }
        .button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport d'activité {{ ucfirst($type) }}</h1>
        <p>Période : {{ $report['period']['start'] }} à {{ $report['period']['end'] }}</p>
    </div>

    <div class="section">
        <h2>Résumé</h2>
        
        <div class="metric">
            <h3>Activités totales</h3>
            <p>{{ number_format($report['summary']['total_activities']) }}</p>
        </div>
        
        <div class="metric">
            <h3>Activités suspectes</h3>
            <p>{{ number_format($report['summary']['suspicious_activities']) }} ({{ $report['summary']['suspicious_percentage'] }}%)</p>
        </div>
        
        <div class="metric">
            <h3>Utilisateurs uniques</h3>
            <p>{{ number_format($report['summary']['unique_users']) }}</p>
        </div>
    </div>

    <div class="section">
        <h2>Métriques financières</h2>
        
        <div class="metric">
            <h3>Revenu total</h3>
            <p>{{ number_format($report['financial_metrics']['total_revenue']) }} €</p>
        </div>
        
        <div class="metric">
            <h3>Valeur moyenne</h3>
            <p>{{ number_format($report['financial_metrics']['average_order_value']) }} €</p>
        </div>
    </div>

    <p>Veuillez consulter le rapport complet en pièce jointe pour plus de détails.</p>

    <div class="footer">
        <p>Ce rapport a été généré automatiquement. Merci de ne pas répondre à cet email.</p>
    </div>
</body>
</html>
