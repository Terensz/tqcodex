<?php

use function Pest\Laravel\{get};

uses()->group('basic');

it('gives back successful response for basic home page', function (): void {
    //Act & Asert
    get(route('home'))->assertOk();
});
