<?php return array (
  'App\\Providers\\EventServiceProvider' => 
  array (
    'Illuminate\\Auth\\Events\\Registered' => 
    array (
      0 => 'Illuminate\\Auth\\Listeners\\SendEmailVerificationNotification',
    ),
    'App\\Events\\PendaftarNotificationSent' => 
    array (
      0 => 'App\\Listeners\\SendWhatsAppNotification',
    ),
  ),
  'Illuminate\\Foundation\\Support\\Providers\\EventServiceProvider' => 
  array (
    'App\\Events\\PendaftarNotificationSent' => 
    array (
      0 => 'App\\Listeners\\SendWhatsAppNotification@handle',
    ),
  ),
);