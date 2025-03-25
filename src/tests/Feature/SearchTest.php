<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        Item::factory()->create(['name' => 'テスト商品A']);
        Item::factory()->create(['name' => 'サンプルB']);
        Item::factory()->create(['name' => 'テスト商品C']);

        $response = $this->get(route('search', ['query' => 'テスト']));

        $response->assertStatus(200);

        $response->assertSee('テスト商品A');
        $response->assertSee('テスト商品C');

        $response->assertDontSee('サンプルB');
    }

    /** @test */
    public function 検索状態がマイリストでも保持されている()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $item1 = Item::factory()->create([
            'name' => '検索対象のアイテム',
            'user_id' => $otherUser->id,
        ]);
        $item2 = Item::factory()->create([
            'name' => '別のアイテム',
            'user_id' => $otherUser->id,
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item1->id,
        ]);

        $response = $this->actingAs($user)->get(route('search', ['query' => '検索']));

        $response->assertStatus(200);

        $response->assertSee('検索対象のアイテム');

        $response = $this->actingAs($user)->get(route('top', ['tab' => 'mylist', 'query' => '検索']));

        $response->assertStatus(200);

        $response->assertSee('検索対象のアイテム');

        $response->assertDontSee('別のアイテム');
    }
}
