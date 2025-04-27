<title>{{ config('chatify.name') }}</title>

{{-- Meta tags --}}
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="id" content="{{ $id }}">
{{-- <meta name="type" content="{{ $type }}"> --}}
<meta name="messenger-color" content="{{ $messengerColor }}">
<meta name="messenger-theme" content="{{ $dark_mode }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="url" content="{{ url('').'/'.config('chatify.routes.prefix') }}" data-user="{{ Auth::user()->id }}">

{{-- scripts --}}
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/chatify/font.awesome.min.js') }}"></script>
<script src="{{ asset('js/chatify/autosize.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src='https://unpkg.com/nprogress@0.2.0/nprogress.js'></script>

{{-- styles --}}
<link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css'/>
<link href="{{ asset('css/chatify/style.css') }}" rel="stylesheet" />
<link href="{{ asset('css/chatify/'.$dark_mode.'.mode.css') }}" rel="stylesheet" />
<link href="{{ asset('css/app.css') }}" rel="stylesheet" />

{{-- Setting messenger primary color to css --}}
<style>
  :root {
      --primary-color: #2180f3;
  }
  
  .messenger {
      border-radius: 0.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
  
  .messenger-listView {
      border-right: 1px solid #e5e7eb;
  }
  
  .messenger-listView-tabs {
      border-bottom: 1px solid #e5e7eb;
  }
  
  .messenger-listView .m-header .user-name {
      font-weight: 600;
  }
  
  .messenger-messagingView .m-header .user-name {
      font-weight: 600;
  }
  
  .messenger-infoView {
      background-color: #f9fafb;
      border-left: 1px solid #e5e7eb;
  }
  
  .messenger-infoView .info-name {
      font-weight: 600;
  }
  
  .messenger-title {
      font-weight: 600;
  }
  
  .message-card .message {
      border-radius: 1rem;
  }
  
  .m-send {
      border-top: 1px solid #e5e7eb;
  }
</style>
