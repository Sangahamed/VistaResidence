<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('chatify.name') }} | {{ config('app.name') }}</title>

    @include('Chatify::layouts.headLinks')
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 p-0">
                <div class="d-flex align-items-center justify-content-between bg-primary text-white p-3">
                    <div>
                        <a href="{{ route('home') }}" class="text-white text-decoration-none">
                            <i class="fas fa-arrow-left me-2"></i>Retour au site
                        </a>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ config('chatify.name') }}</h5>
                    </div>
                    <div>
                        <a href="{{ route('messages.agents') }}" class="text-white text-decoration-none">
                            <i class="fas fa-users me-2"></i>Nos agents
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="messenger">
        {{-- ----------------------Users/Groups lists side---------------------- --}}
        <div class="messenger-listView {{ !!$id ? 'conversation-active' : '' }}">
            {{-- Header and search bar --}}
            <div class="m-header">
                <nav>
                    <a href="#"><i class="fas fa-inbox"></i> <span class="messenger-headTitle">MESSAGES</span>
                    </a>
                    {{-- header buttons --}}
                    <nav class="m-header-right">
                        <a href="#"><i class="fas fa-cog settings-btn"></i></a>
                        <a href="#" class="listView-x"><i class="fas fa-times"></i></a>
                    </nav>
                </nav>
                {{-- Search input --}}
                <input type="text" class="messenger-search" placeholder="Rechercher" />
                {{-- Tabs --}}
                <div class="messenger-listView-tabs">
                    <a href="#" class="active-tab" data-view="users">
                        <span class="far fa-user"></span> Contacts</a>
                </div>
            </div>
            {{-- tabs and lists --}}
            <div class="m-body contacts-container">
                {{-- Lists [Users/Group] --}}
                {{-- ---------------- [ User Tab ] ---------------- --}}
                <div class="show messenger-tab users-tab app-scroll" data-view="users">
                    {{-- Favorites --}}
                    <div class="favorites-section">
                        <p class="messenger-title"><span>Favoris</span></p>
                        <div class="messenger-favorites app-scroll-hidden"></div>
                    </div>
                    {{-- Saved Messages --}}
                    <p class="messenger-title"><span>Votre espace</span></p>
                    {!! view('Chatify::layouts.listItem', ['get' => 'saved']) !!}
                    {{-- Contact --}}
                    <p class="messenger-title"><span>Tous les messages</span></p>
                    <div class="listOfContacts" style="width: 100%;height: calc(100% - 272px);position: relative;">
                    </div>
                </div>
                {{-- ---------------- [ Search Tab ] ---------------- --}}
                <div class="messenger-tab search-tab app-scroll" data-view="search">
                    {{-- items --}}
                    <p class="messenger-title"><span>Recherche</span></p>
                    <div class="search-records">
                        <p class="message-hint center-el"><span>Tapez pour rechercher..</span></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ----------------------Messaging side---------------------- --}}
        <div class="messenger-messagingView">
            {{-- header title [conversation name] amd buttons --}}
            <div class="m-header m-header-messaging">
                <nav class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                    {{-- header back button, avatar and user name --}}
                    <div class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                        <a href="#" class="show-listView"><i class="fas fa-arrow-left"></i></a>
                        <div class="avatar av-s header-avatar"
                            style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px;">
                        </div>
                        <a href="#" class="user-name">{{ config('chatify.name') }}</a>
                    </div>
                    {{-- header buttons --}}
                    <nav class="m-header-right">
                        <a href="#" class="add-to-favorite"><i class="fas fa-star"></i></a>
                        <a href="/"><i class="fas fa-home"></i></a>
                        <a href="#" class="show-infoSide"><i class="fas fa-info-circle"></i></a>
                    </nav>
                </nav>
                {{-- Internet connection --}}
                <div class="internet-connection">
                    <span class="ic-connected">Connecté</span>
                    <span class="ic-connecting">Connexion...</span>
                    <span class="ic-noInternet">Pas d'accès Internet</span>
                </div>
            </div>

            {{-- Messaging area --}}
            <div class="m-body messages-container app-scroll">
                <div class="messages">
                    <p class="message-hint center-el"><span>Sélectionnez un chat pour commencer la messagerie</span></p>
                </div>
                {{-- Typing indicator --}}
                <div class="typing-indicator">
                    <div class="message-card typing">
                        <div class="message">
                            <span class="typing-dots">
                                <span class="dot dot-1"></span>
                                <span class="dot dot-2"></span>
                                <span class="dot dot-3"></span>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
            {{-- Send Message Form --}}
            @include('Chatify::layouts.sendForm')
        </div>
        {{-- ---------------------- Info side ---------------------- --}}
        <div class="messenger-infoView app-scroll">
            {{-- nav actions --}}
            <nav>
                <p>Détails du contact</p>
                <a href="#"><i class="fas fa-times"></i></a>
            </nav>
            {!! view('Chatify::layouts.info')->render() !!}
        </div>
    </div>

    @include('Chatify::layouts.modals')
    @include('Chatify::layouts.footerLinks')
</body>

</html>
