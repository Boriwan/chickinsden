<?php

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);

    $response->assertSee("Welcome to Chickins Den🐓☕️");
    $response->assertSee("Dens");
    $response->assertSee("Chickens");
});
