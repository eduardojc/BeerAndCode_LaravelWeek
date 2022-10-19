<?php

/*
    returna uma URL podendo ser usado um URL homologação como default e uma URL
    atraves do env('PUNKAPI_BASE_URL');
*/

return ['url' => env('PUNKAPI_BASE_URL','https://api.punkapi.com/v2')];