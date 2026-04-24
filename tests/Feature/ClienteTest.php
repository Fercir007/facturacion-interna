<?php

namespace Tests\Feature;

use App\Enums\TipoCliente;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // --- Auth ---

    public function test_redirige_a_login_si_no_autenticado(): void
    {
        $this->get(route('clientes.index'))
            ->assertRedirect(route('login'));
    }

    public function test_login_correcto_redirige_al_dashboard(): void
    {
        $user = User::factory()->create(['password' => bcrypt('secret')]);
        $this->post(route('login'), ['email' => $user->email, 'password' => 'secret'])
            ->assertRedirect(route('clientes.index'));
    }

    public function test_login_incorrecto_devuelve_error(): void
    {
        $this->post(route('login'), ['email' => 'no@existe.com', 'password' => 'mal'])
            ->assertSessionHasErrors('email');
    }

    // --- CRUD web ---

    public function test_index_lista_clientes(): void
    {
        Cliente::factory()->count(3)->create();
        $this->actingAs($this->user)
            ->get(route('clientes.index'))
            ->assertOk()
            ->assertViewIs('clientes.index')
            ->assertViewHas('clientes');
    }

    public function test_puede_crear_cliente(): void
    {
        $this->actingAs($this->user)
            ->post(route('clientes.store'), [
                'tipo_cliente' => TipoCliente::Comercio->value,
                'razon_social' => 'Empresa Test SA',
                'cuit'         => '20123456789',
                'email'        => 'empresa@test.com',
            ])
            ->assertRedirect(route('clientes.index'));

        $this->assertDatabaseHas('clientes', [
            'cuit'         => '20123456789',
            'razon_social' => 'Empresa Test SA',
        ]);
    }

    public function test_cuit_duplicado_es_rechazado(): void
    {
        Cliente::factory()->create(['cuit' => '20123456789']);

        $this->actingAs($this->user)
            ->post(route('clientes.store'), [
                'tipo_cliente' => TipoCliente::Comercio->value,
                'razon_social' => 'Otra SA',
                'cuit'         => '20123456789',
            ])
            ->assertSessionHasErrors('cuit');
    }

    public function test_cuit_corto_es_rechazado(): void
    {
        $this->actingAs($this->user)
            ->post(route('clientes.store'), [
                'tipo_cliente' => TipoCliente::Comercio->value,
                'razon_social' => 'Test SA',
                'cuit'         => '123',
            ])
            ->assertSessionHasErrors('cuit');
    }

    public function test_razon_social_es_obligatoria(): void
    {
        $this->actingAs($this->user)
            ->post(route('clientes.store'), [
                'tipo_cliente' => TipoCliente::Comercio->value,
                'cuit'         => '20123456789',
            ])
            ->assertSessionHasErrors('razon_social');
    }

    public function test_puede_ver_cliente(): void
    {
        $cliente = Cliente::factory()->create();
        $this->actingAs($this->user)
            ->get(route('clientes.show', $cliente))
            ->assertOk()
            ->assertViewIs('clientes.show');
    }

    public function test_puede_editar_cliente(): void
    {
        $cliente = Cliente::factory()->create();
        $this->actingAs($this->user)
            ->put(route('clientes.update', $cliente), [
                'tipo_cliente' => TipoCliente::Comercio->value,
                'razon_social' => 'Nombre Actualizado SA',
                'cuit'         => $cliente->cuit,
            ])
            ->assertRedirect(route('clientes.show', $cliente));

        $this->assertDatabaseHas('clientes', ['razon_social' => 'Nombre Actualizado SA']);
    }

    public function test_puede_eliminar_cliente(): void
    {
        $cliente = Cliente::factory()->create();
        $this->actingAs($this->user)
            ->delete(route('clientes.destroy', $cliente))
            ->assertRedirect(route('clientes.index'));

        // Soft delete: no está en DB activa pero sí en la tabla
        $this->assertSoftDeleted('clientes', ['id' => $cliente->id]);
    }

    // --- API ---

    public function test_api_lista_clientes(): void
    {
        Cliente::factory()->count(3)->create();
        $this->actingAs($this->user)
            ->getJson('/api/clientes')
            ->assertOk()
            ->assertJsonStructure(['data', 'meta']);
    }

    public function test_api_crea_cliente(): void
    {
        $this->actingAs($this->user)
            ->postJson('/api/clientes', [
                'tipo_cliente' => TipoCliente::Comercio->value,
                'razon_social' => 'API Test SA',
                'cuit'         => '27987654321',
            ])
            ->assertCreated()
            ->assertJsonPath('data.cuit', '27987654321');
    }
}