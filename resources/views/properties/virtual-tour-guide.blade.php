@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Guide de création de visites virtuelles</h1>
            <p class="lead">Apprenez à créer des visites virtuelles pour vos propriétés, quel que soit votre budget.</p>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Visite basique</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-images fa-4x text-primary"></i>
                    </div>
                    <h6>Coût : Gratuit</h6>
                    <p>Idéal pour les propriétaires avec un budget limité.</p>
                    <ul>
                        <li>Utilise vos photos existantes</li>
                        <li>Facile à mettre en place</li>
                        <li>Aucun équipement spécial requis</li>
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="#basic-guide" class="btn btn-primary w-100">Voir le guide</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Visite panoramique</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-panorama fa-4x text-primary"></i>
                    </div>
                    <h6>Coût : Gratuit à faible</h6>
                    <p>Pour ceux qui veulent une expérience plus immersive.</p>
                    <ul>
                        <li>Utilise des photos à 360°</li>
                        <li>Nécessite un smartphone</li>
                        <li>Applications gratuites disponibles</li>
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="#panoramic-guide" class="btn btn-primary w-100">Voir le guide</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Visite 3D</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-cube fa-4x text-primary"></i>
                    </div>
                    <h6>Coût : Modéré à élevé</h6>
                    <p>Pour une expérience professionnelle complète.</p>
                    <ul>
                        <li>Modèle 3D complet de la propriété</li>
                        <li>Nécessite un équipement spécial ou un service professionnel</li>
                        <li>Résultat de qualité supérieure</li>
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="#3d-guide" class="btn btn-primary w-100">Voir le guide</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5" id="basic-guide">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Guide pour la visite basique</h4>
                </div>
                <div class="card-body">
                    <h5>Matériel nécessaire :</h5>
                    <ul class="mb-4">
                        <li>Un smartphone avec appareil photo ou un appareil photo numérique</li>
                        <li>Un bon éclairage (naturel de préférence)</li>
                    </ul>

                    <h5>Étapes :</h5>
                    <ol class="mb-4">
                        <li class="mb-2">
                            <strong>Préparez votre propriété</strong>
                            <p>Rangez et nettoyez chaque pièce. Ouvrez les rideaux pour maximiser la lumière naturelle.</p>
                        </li>
                        <li class="mb-2">
                            <strong>Prenez des photos de qualité</strong>
                            <p>Prenez des photos de chaque pièce sous différents angles. Tenez votre appareil à hauteur des yeux et en format paysage (horizontal).</p>
                        </li>
                        <li class="mb-2">
                            <strong>Organisez vos photos</strong>
                            <p>Prenez des photos dans un ordre logique, comme si vous faisiez visiter la propriété (entrée, salon, cuisine, etc.).</p>
                        </li>
                        <li class="mb-2">
                            <strong>Téléchargez vos photos</strong>
                            <p>Ajoutez vos photos à votre annonce de propriété sur notre plateforme.</p>
                        </li>
                        <li class="mb-2">
                            <strong>Activez la visite virtuelle basique</strong>
                            <p>Dans l'édition de votre propriété, cliquez sur "Créer une visite basique" pour transformer automatiquement vos photos en visite virtuelle.</p>
                        </li>
                    </ol>

                    <h5>Conseils pour de meilleures photos :</h5>
                    <ul>
                        <li>Prenez les photos pendant la journée pour profiter de la lumière naturelle</li>
                        <li>Évitez de vous refléter dans les miroirs ou les fenêtres</li>
                        <li>Prenez des photos en position debout pour une perspective naturelle</li>
                        <li>Assurez-vous que l'appareil est stable pour éviter les photos floues</li>
                        <li>Prenez plusieurs photos de chaque pièce sous différents angles</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5" id="panoramic-guide">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Guide pour la visite panoramique</h4>
                </div>
                <div class="card-body">
                    <h5>Matériel nécessaire :</h5>
                    <ul class="mb-4">
                        <li>Un smartphone avec une application de photo panoramique 360° (Google Street View, Panorama 360, etc.)</li>
                        <li>Un trépied pour smartphone (recommandé mais pas obligatoire)</li>
                        <li>Un bon éclairage (naturel de préférence)</li>
                    </ul>

                    <h5>Applications gratuites recommandées :</h5>
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6>Google Street View</h6>
                                    <p class="small">Application gratuite, facile à utiliser</p>
                                    <div class="d-grid gap-2">
                                        <a href="https://play.google.com/store/apps/details?id=com.google.android.street" class="btn btn-sm btn-outline-primary" target="_blank">Android</a>
                                        <a href="https://apps.apple.com/fr/app/google-street-view/id904418768" class="btn btn-sm btn-outline-primary" target="_blank">iOS</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6>Panorama 360</h6>
                                    <p class="small">Simple et efficace pour les débutants</p>
                                    <div class="d-grid gap-2">
                                        <a href="https://play.google.com/store/apps/details?id=com.vtcreator.android360" class="btn btn-sm btn-outline-primary" target="_blank">Android</a>
                                        <a href="https://apps.apple.com/fr/app/panorama-360-camera/id1058807408" class="btn btn-sm btn-outline-primary" target="_blank">iOS</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6>RICOH THETA</h6>
                                    <p class="small">Fonctionne aussi sans caméra RICOH</p>
                                    <div class="d-grid gap-2">
                                        <a href="https://play.google.com/store/apps/details?id=com.theta360" class="btn btn-sm btn-outline-primary" target="_blank">Android</a>
                                        <a href="https://apps.apple.com/fr/app/ricoh-theta/id667238484" class="btn btn-sm btn-outline-primary" target="_blank">iOS</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5>Étapes avec Google Street View (gratuit) :</h5>
                    <ol class="mb-4">
                        <li class="mb-2">
                            <strong>Téléchargez l'application Google Street View</strong>
                            <p>Disponible gratuitement sur Android et iOS.</p>
                        </li>
                        <li class="mb-2">
                            <strong>Préparez votre propriété</strong>
                            <p>Rangez et nettoyez chaque pièce. Assurez-vous d'avoir un bon éclairage.</p>
                        </li>
                        <li class="mb-2">
                            <strong>Ouvrez l'application et créez une photo 360°</strong>
                            <p>Appuyez sur l'icône appareil photo, puis sélectionnez "Appareil photo" pour créer une photo 360°.</p>
                        </li>
                        <li class="mb-2">
                            <strong>Prenez la photo panoramique</strong>
                            <p>Placez-vous au centre de la pièce. Suivez les instructions à l'écran pour tourner lentement sur vous-même et capturer toute la pièce à 360°.</p>
                        </li>
                        <li class="mb-2">
                            <strong>Répétez pour chaque pièce</strong>
                            <p>Créez une photo 360° pour chaque pièce importante de votre propriété.</p>
                        </li>
                        <li class="mb-2">
                            <strong>Exportez les images</strong>
                            <p>Dans l'application, ouvrez votre photo 360°, appuyez sur les trois points, puis "Exporter l'image" ou "Partager" et enregistrez-la sur votre appareil.</p>
                        </li>
                        <li class="mb-2">
                            <strong>Téléchargez sur notre plateforme</strong>
                            <p>Dans l'édition de votre propriété, sélectionnez "Visite panoramique" et téléchargez vos images 360°.</p>
                        </li>
                    </ol>

                    <h5>Conseils pour de meilleures photos panoramiques :</h5>
                    <ul>
                        <li>Utilisez un trépied si possible pour des résultats plus stables</li>
                        <li>Placez-vous exactement au centre de la pièce</li>
                        <li>Tournez lentement et régulièrement pendant la capture</li>
                        <li>Évitez les mouvements brusques</li>
                        <li>Assurez-vous que toutes les lumières sont allumées pour un éclairage uniforme</li>
                        <li>Prenez les photos quand il y a beaucoup de lumière naturelle</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5" id="3d-guide">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Guide pour la visite 3D</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Cette option nécessite généralement un équipement spécial ou un service professionnel. Cependant, il existe des alternatives abordables.
                    </div>

                    <h5>Options selon votre budget :</h5>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Option 1 : Zillow 3D Home (Gratuit)</h6>
                        </div>
                        <div class="card-body">
                            <p>Zillow propose une application gratuite pour créer des visites 3D avec un iPhone.</p>
                            <h6>Matériel nécessaire :</h6>
                            <ul>
                                <li>Un iPhone avec iOS 12 ou supérieur</li>
                                <li>L'application Zillow 3D Home (gratuite)</li>
                            </ul>
                            <h6>Étapes :</h6>
                            <ol>
                                <li>Téléchargez l'application Zillow 3D Home</li>
                                <li>Suivez les instructions pour capturer chaque pièce</li>
                                <li>L'application génère automatiquement une visite 3D</li>
                                <li>Exportez et copiez le lien de partage</li>
                                <li>Collez ce lien dans notre formulaire de visite 3D</li>
                            </ol>
                            <div class="text-center">
                                <a href="https://apps.apple.com/us/app/zillow-3d-home/id1424227414" class="btn btn-outline-primary" target="_blank">
                                    <i class="fab fa-apple me-2"></i>Télécharger pour iOS
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Option 2 : Polycam (Partiellement gratuit)</h6>
                        </div>
                        <div class="card-body">
                            <p>Polycam permet de créer des modèles 3D avec un iPhone récent équipé d'un scanner LiDAR.</p>
                            <h6>Matériel nécessaire :</h6>
                            <ul>
                                <li>Un iPhone 12 Pro/Pro Max ou plus récent avec scanner LiDAR</li>
                                <li>L'application Polycam (version de base gratuite)</li>
                            </ul>
                            <h6>Étapes :</h6>
                            <ol>
                                <li>Téléchargez l'application Polycam</li>
                                <li>Créez un scan 3D de chaque pièce</li>
                                <li>Exportez votre modèle 3D</li>
                                <li>Partagez-le et copiez le lien</li>
                                <li>Collez ce lien dans notre formulaire de visite 3D</li>
                            </ol>
                            <div class="text-center">
                                <a href="https://apps.apple.com/us/app/polycam-lidar-3d-scanner/id1532482376" class="btn btn-outline-primary" target="_blank">
                                    <i class="fab fa-apple me-2"></i>Télécharger pour iOS
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Option 3 : Service professionnel local</h6>
                        </div>
                        <div class="card-body">
                            <p>Engagez un photographe local spécialisé dans l'immobilier qui propose des services de visite virtuelle.</p>
                            <h6>Avantages :</h6>
                            <ul>
                                <li>Qualité professionnelle garantie</li>
                                <li>Aucun équipement à acheter</li>
                                <li>Économie de temps</li>
                            </ul>
                            <h6>Coût estimé :</h6>
                            <p>Entre 100€ et 300€ selon la taille de la propriété et le prestataire.</p>
                            <div class="alert alert-success">
                                <i class="fas fa-lightbulb me-2"></i><strong>Astuce :</strong> Demandez à plusieurs propriétaires de votre immeuble ou quartier s'ils souhaitent aussi faire des visites virtuelles. Vous pourriez négocier un tarif de groupe avec un photographe professionnel.
                            </div>
                        </div>
                    </div>

                    <h5>Conseils pour les visites 3D :</h5>
                    <ul>
                        <li>Assurez-vous que toutes les pièces sont bien éclairées</li>
                        <li>Rangez et nettoyez soigneusement avant la capture</li>
                        <li>Suivez précisément les instructions de l'application ou du service utilisé</li>
                        <li>Testez votre visite virtuelle sur différents appareils avant de la publier</li>
                        <li>Incluez toutes les pièces importantes, même les petites</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Besoin d'aide ?</h4>
                </div>
                <div class="card-body">
                    <p>Si vous avez des questions ou besoin d'assistance pour créer votre visite virtuelle, n'hésitez pas à nous contacter :</p>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                                    <h6>Email</h6>
                                    <p>support@immoconnect.com</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                                    <h6>Téléphone</h6>
                                    <p>01 23 45 67 89</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-comments fa-2x text-primary mb-3"></i>
                                    <h6>Chat</h6>
                                    <a href="{{ route('messenger') }}" class="btn btn-sm btn-primary">Discuter avec un conseiller</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection