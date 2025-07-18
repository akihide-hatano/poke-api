<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PokemonController extends Controller
{
    // ... (既存の show, index メソッド (API用) はそのまま残しても良いですが、
    // 混乱を避けるため、今回はWebビュー用のindexとshowに焦点を当てます)

    /**
     * ポケモン一覧ページを表示する (Bladeビューを返す)
     * 例: /pokemons
     *
     * @return \Illuminate\View\View
     */
    public function index() // showIndexPage から index に変更
    {
        try {
            $response = Http::get("https://pokeapi.co/api/v2/pokemon/", [
                'limit' => 20,
                'offset' => 0,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $pokemonList = $data['results'];

                $detailedPokemonList = [];
                foreach ($pokemonList as $pokemon) {
                    $detailResponse = Http::get($pokemon['url']);
                    if ($detailResponse->successful()) {
                        $detailedPokemonList[] = $detailResponse->json();
                    }
                }

                return view('pokemon.index', compact('detailedPokemonList'));

            } else {
                return view('error')->with('message', 'ポケモンリストの取得に失敗しました。ステータスコード: ' . $response->status());
            }
        } catch (\Exception $e) {
            return view('error')->with('message', 'ポケモンリストの取得中にエラーが発生しました: ' . $e->getMessage());
        }
    }

    /**
     * 特定のポケモンの詳細ページを表示する (Bladeビューを返す)
     * 例: /pokemons/{nameOrId}
     *
     * @param string $nameOrId ポケモンの名前またはID
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($nameOrId) // showDetailPage から show に変更
    {
        try {
            $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$nameOrId}/");

            if ($response->successful()) {
                $pokemon = $response->json();

                return view('pokemon.show', compact('pokemon'));

            } else {
                // `pokemons.index` は `Route::resource` によって生成されるルート名
                return redirect()->route('pokemons.index')->with('error', '指定されたポケモンが見つからないか、データの取得に失敗しました。');
            }
        } catch (\Exception $e) {
            return redirect()->route('pokemons.index')->with('error', 'ポケモン詳細の取得中にエラーが発生しました: ' . $e->getMessage());
        }
    }
}