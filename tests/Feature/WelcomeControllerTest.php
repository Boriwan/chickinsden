<?php

test('welcome page renders with navigation', function () {
    $response = $this->get('/');

    $response->assertOk();

    $response->assertSee('Welcome to Chickins Den');
    $response->assertSee(route('chickens.index'));
    $response->assertSee(route('breeds.index'));
    $response->assertSee(route('about'));
    $response->assertSee(route('admin.index'));
});
