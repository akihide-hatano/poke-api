<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MoveController extends Controller
{
    /**
     * 特定の技の詳細ページを表示する (Bladeビューを返す)
     * 例: GET /moves/{name}
     *
     * @param string $name URLから渡される技の名前
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($name)
    {
        try {
            // 1. PokeAPIへのリクエスト直後にdd()
            // dd("Attempting to fetch move: {$name}");

            $response = Http::get("https://pokeapi.co/api/v2/move/{$name}/");

            // 2. レスポンスオブジェクトの確認
            // dd($response);

            if ($response->successful()) {
                $move = $response->json();

                // 3. 取得した生JSONデータの確認
                // dd($move);

                // 日本語の技名を取得する（あれば）
                $japaneseMoveName = collect($move['names'])->firstWhere('language.name', 'ja')['name'] ?? $move['name'];

                // 4. 日本語技名と元の技名の確認
                // dd(['japaneseName' => $japaneseMoveName, 'originalName' => $move['name']]);

                // その技を覚えるポケモンのリスト
                $learnedByPokemon = [];
                if (isset($move['learned_by_pokemon'])) {
                    foreach ($move['learned_by_pokemon'] as $pokemonInfo) {
                        // ポケモンのIDをURLから抽出（例: "https://pokeapi.co/api/v2/pokemon/132/" から 132 を取得）
                        preg_match('/\/pokemon\/(\d+)\//', $pokemonInfo['url'], $matches);
                        $pokemonId = $matches[1] ?? null;

                        $learnedByPokemon[] = [
                            'name' => $pokemonInfo['name'],
                            'url' => route('pokemons.show', ['pokemon' => $pokemonId]), // ポケモン詳細ページへのリンク
                        ];
                    }
                }

                // 5. 最終的にビューに渡すデータの確認
                // dd([
                //     'move' => $move,
                //     'japaneseMoveName' => $japaneseMoveName,
                //     'learnedByPokemon' => $learnedByPokemon,
                // ]);

                return view('moves.show', [
                    'move' => $move,
                    'japaneseMoveName' => $japaneseMoveName,
                    'learnedByPokemon' => $learnedByPokemon,
                ]);

            } else {
                // エラーハンドリング
                // dd("Failed to fetch move: {$name}. Status: " . $response->status());
                abort(404, 'Move not found.');
            }
        } catch (\Exception $e) {
            // dd("Exception caught: " . $e->getMessage());
            abort(500, 'An error occurred while fetching move data: ' . $e->getMessage());
        }
    }

    /**
     * 技の一覧ページを表示する (Bladeビューを返す)
     * 例: GET /moves
     *
     * @return \Illuminate\View\View
     */
    public function index() // Route::resource の 'index' アクションに対応
    {
        try {
            // 1. PokeAPIへのリクエスト直前にdd()
            // dd("Attempting to fetch move list.");

            $response = Http::get("https://pokeapi.co/api/v2/move/", [
                'limit' => 200, // 最初の200件を取得
                'offset' => 0,
            ]);

            // 2. レスポンスオブジェクトの確認
            // dd($response);

            if ($response->successful()) {
                $data = $response->json();
                $moveList = $data['results']; // 技の名前とURLのリスト

                // 3. 取得した技リストの確認
                // dd($moveList);

                return view('moves.index', compact('moveList'));

            } else {
                // dd("Failed to fetch move list. Status: " . $response->status());
                return view('error')->with('message', '技リストの取得に失敗しました。ステータスコード: ' . $response->status());
            }
        } catch (\Exception $e) {
            // dd("Exception caught: " . $e->getMessage());
            return view('error')->with('message', '技リストの取得中にエラーが発生しました: ' . $e->getMessage());
        }
    }
}