<?php

return [

        /*
    |--------------------------------------------------------------------------
    | Token de Acesso à Galeria (Público)
    |--------------------------------------------------------------------------
    |
    | Token UUID usado pelos convidados para aceder à galeria e adicionar fotos.
    | Este token deve estar no QR Code partilhado.
    |
    */
    'token' => env('WEDDING_GALLERY_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Token de Administrador (Admin)
    |--------------------------------------------------------------------------
    |
    | Token UUID usado pelos administradores. Permite todas as ações do token
    | público + eliminar fotografias.
    |
    */
    'admin_token' => env('WEDDING_GALLERY_ADMIN_TOKEN'),
];