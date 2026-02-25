<?php

use App\Models\Ticket;

it('submits the contact form and creates a ticket', function () {
    $response = $this->post('/contact', [
        'name' => 'Test User',
        'email' => 'contact@example.com',
        'subject' => 'Help needed',
        'message' => 'Please assist with this request.',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('tickets', [
        'email' => 'contact@example.com',
        'subject' => 'Help needed',
    ]);
});
